<?php
/**
 * Blood Matching Engine
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/NotificationService.php';

class MatchingService {
    private PDO $pdo;
    private NotificationService $notificationService;

    // Blood compatibility map: Recipient Group => Compatible Donor Groups
    private array $compatibilityMap = [
        'A+'  => ['A+', 'A-', 'O+', 'O-'],
        'A-'  => ['A-', 'O-'],
        'B+'  => ['B+', 'B-', 'O+', 'O-'],
        'B-'  => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['AB-', 'A-', 'B-', 'O-'],
        'O+'  => ['O+', 'O-'],
        'O-'  => ['O-']
    ];

    public function __construct() {
        $this->pdo = DB::getConnection();
        $this->notificationService = new NotificationService();
    }

    /**
     * Match available donors for a blood request and trigger notifications
     */
    public function matchAndNotify(int $requestId, bool $exactMatchOnly = false): array {
        // 1. Fetch request details
        $stmt = $this->pdo->prepare("SELECT * FROM blood_requests WHERE id = ?");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch();

        if (!$request) {
            return ['status' => false, 'message' => 'অনুরোধ পাওয়া যায়নি'];
        }

        $neededBlood = $request['blood_group'];
        $eligibleGroups = $exactMatchOnly ? [$neededBlood] : ($this->compatibilityMap[$neededBlood] ?? [$neededBlood]);
        $inQuery = implode(',', array_fill(0, count($eligibleGroups), '?'));

        $today = date('Y-m-d');
        // 2. Query available donors (Resting period checked: is_available=1 and next_available_date <= today or NULL)
        // Prioritize same district first, then same area
        $sql = "
            SELECT d.*, u.name, u.email, u.phone, u.user_type, u.cdc_sid_no, u.mariner_rank
            FROM donors d
            JOIN users u ON d.user_id = u.id
            WHERE d.blood_group IN ({$inQuery})
              AND d.is_available = 1
              AND (d.next_available_date IS NULL OR d.next_available_date <= ?)
              AND d.id NOT IN (
                  -- Do not re-notify donors who were already notified for this request
                  SELECT donor_id FROM donor_responses WHERE request_id = ?
              )
            ORDER BY 
                CASE WHEN d.district = ? THEN 0 ELSE 1 END,
                CASE WHEN d.area LIKE ? THEN 0 ELSE 1 END,
                d.last_donation_date ASC
            LIMIT 50
        ";

        $params = array_merge($eligibleGroups, [$today, $requestId, $request['district'], '%' . $request['area'] . '%']);
        $donorStmt = $this->pdo->prepare($sql);
        $donorStmt->execute($params);
        $matchingDonors = $donorStmt->fetchAll();

        $notifiedCount = 0;
        foreach ($matchingDonors as $donor) {
            // Generate secure random token
            $responseToken = bin2hex(random_bytes(32));

            // Insert into donor_responses
            $insResp = $this->pdo->prepare("
                INSERT INTO donor_responses (donor_id, request_id, response_token, status)
                VALUES (?, ?, ?, 'notified')
            ");
            $insResp->execute([$donor['id'], $requestId, $responseToken]);

            // Dispatch notification
            if (!empty($donor['email'])) {
                $this->notificationService->notifyDonorForRequest($donor, $request, $responseToken);
            }
            $notifiedCount++;
        }

        // Update request status if donors found
        if ($notifiedCount > 0) {
            $upd = $this->pdo->prepare("UPDATE blood_requests SET status = 'matched' WHERE id = ? AND status = 'open'");
            $upd->execute([$requestId]);
        }

        return [
            'status' => true,
            'notified_count' => $notifiedCount,
            'donors' => $matchingDonors
        ];
    }
}
