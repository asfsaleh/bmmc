<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Master Services Registry & Organizational Wings Data
 * Bilingual Support: English (Default) & Bengali
 */

require_once __DIR__ . '/i18n.php';

function get_bmmc_pillars(): array {
    $isEn = is_english();

    if ($isEn) {
        return [
            'emergency' => [
                'id' => 'emergency',
                'title' => 'Emergency Response & Seafarer Welfare',
                'title_bn' => 'জরুরি সাড়া ও নাবিক কল্যাণ',
                'icon' => 'bi-shield-exclamation',
                'badge' => 'Emergency Wings',
                'color' => '#ef233c',
                'summary' => 'Immediate coordination for life safety, legal defense, and humanitarian welfare for seafarers in distress at sea or in foreign ports.'
            ],
            'career' => [
                'id' => 'career',
                'title' => 'Cadets, Training & Maritime Career',
                'title_bn' => 'ক্যাডেট, প্রশিক্ষণ ও পেশাগত ক্যারিয়ার',
                'icon' => 'bi-mortarboard-fill',
                'badge' => 'Career & Training',
                'color' => '#00d2ff',
                'summary' => 'Fresh cadet grooming, genuine manning agency vetting, and honorable shore-based transition pathways.'
            ],
            'tools' => [
                'id' => 'tools',
                'title' => 'Seafarer Smart Tools & Digital Utilities',
                'title_bn' => 'নাবিক স্মার্ট ইউটিলিটি টুলস',
                'icon' => 'bi-cpu-fill',
                'badge' => 'Digital Tools',
                'color' => '#f59e0b',
                'summary' => 'Automated sea-time calculators, certificate expiry tracking vaults, and one-click IMO-standard CV generators.'
            ],
            'health' => [
                'id' => 'health',
                'title' => 'Healthcare & Maritime Medical',
                'title_bn' => 'মেরিন স্বাস্থ্য ও ডায়াগনস্টিক',
                'icon' => 'bi-hospital-fill',
                'badge' => 'Health & Medical',
                'color' => '#10b981',
                'summary' => 'Verified directory of DG Shipping-approved medical examiners, certified diagnostic clinics, and marine doctors.'
            ],
            'community' => [
                'id' => 'community',
                'title' => 'Community, Governance & Transparency',
                'title_bn' => 'কমিউনিটি ঐক্য ও আর্থিক স্বচ্ছতা',
                'icon' => 'bi-globe2',
                'badge' => 'Community & Trust',
                'color' => '#8b5cf6',
                'summary' => 'Global seafarer interactive forum, batch brotherhood networks, 100% open financial ledger, and volunteer database.'
            ]
        ];
    }

    return [
        'emergency' => [
            'id' => 'emergency',
            'title' => 'জরুরি সাড়া ও নাবিক কল্যাণ',
            'title_en' => 'Emergency Response & Seafarer Welfare',
            'icon' => 'bi-shield-exclamation',
            'badge' => 'Emergency Wings',
            'color' => '#ef233c',
            'summary' => 'সাগরে বা বন্দরে বিপদগ্রস্ত মেরিনারদের তাৎক্ষণিক জীবন ও অধিকার সুরক্ষার সমন্বয়।'
        ],
        'career' => [
            'id' => 'career',
            'title' => 'ক্যাডেট, প্রশিক্ষণ ও পেশাগত ক্যারিয়ার',
            'title_en' => 'Cadets, Training & Maritime Career',
            'icon' => 'bi-mortarboard-fill',
            'badge' => 'Career & Training',
            'color' => '#00d2ff',
            'summary' => 'নতুন ক্যাডেট তৈরি, জেনুইন এজেন্সি বাছাই এবং শোর-বেসড চাকরি নিশ্চিতকরণ।'
        ],
        'tools' => [
            'id' => 'tools',
            'title' => 'নাবিক স্মার্ট ইউটিলিটি টুলস',
            'title_en' => 'Seafarer Smart Tools & Digital Utilities',
            'icon' => 'bi-cpu-fill',
            'badge' => 'Digital Tools',
            'color' => '#f59e0b',
            'summary' => 'সি-টাইম ক্যালকুলেটর, ডকুমেন্ট এক্সপায়ারি ট্র্যাকার ও এক-ক্লিকে সিভি তৈরির অটোমেশন।'
        ],
        'health' => [
            'id' => 'health',
            'title' => 'মেরিন স্বাস্থ্য ও ডায়াগনস্টিক',
            'title_en' => 'Healthcare & Maritime Medical',
            'icon' => 'bi-hospital-fill',
            'badge' => 'Health & Medical',
            'color' => '#10b981',
            'summary' => 'ডিজি শিপিং অনুমোদিত মেডিকেল সেন্টার, ডাক্তার এবং ডায়াগনস্টিক সেবা ডিরেক্টরি।'
        ],
        'community' => [
            'id' => 'community',
            'title' => 'কমিউনিটি ঐক্য ও আর্থিক স্বচ্ছতা',
            'title_en' => 'Community, Governance & Transparency',
            'icon' => 'bi-globe2',
            'badge' => 'Community & Trust',
            'color' => '#8b5cf6',
            'summary' => 'মেরিটাইম ফোরাম, ব্যাচ নেটওয়ার্ক, শতভাগ উন্মুক্ত ফান্ড হিসাব ও ভলান্টিয়ার ডাটাবেজ।'
        ]
    ];
}

function get_bmmc_services(): array {
    $isEn = is_english();

    if ($isEn) {
        return [
            // 1. Blood Donation Network (LIVE FLAGSHIP)
            'blood-network' => [
                'slug' => 'blood-network',
                'pillar' => 'emergency',
                'title' => 'Blood Donation Network',
                'title_en' => 'Blood Donation Network',
                'status' => 'live',
                'badge' => 'Active Service (Live)',
                'icon' => 'bi-droplet-fill',
                'color' => '#ef233c',
                'summary' => 'A unified emergency blood donation platform connecting seafarers and citizens, featuring a 4-month automated medical resting protocol and rapid donor matching.',
                'description' => 'The premier fully active humanitarian wing of the Bangladesh Merchant Mariners Community (BMMC). A selfless, intermediary-free blood management network dedicated to hospital patients across Bangladesh and the families of seafarers serving abroad at sea.',
                'problem' => 'Locating matching blood in critical emergencies is often agonizing for patient relatives. Donors are frequently called repeatedly without health buffers, causing donor fatigue and physical strain.',
                'solution' => 'The BMMC automated engine dispatches immediate notifications based on exact blood groups and geographic districts. After donation, an automated 120-day medical resting lockout protects donor health.',
                'features' => [
                    'District and zone-level smart donor filtering',
                    'Medically supervised 4-month (120-day) resting period automation',
                    'One-click SMS/Email consent and direct contact bridging',
                    '24/7 dedicated emergency blood helpline coordination',
                    'Unified volunteer database combining mariner and civilian donors'
                ],
                'button_text' => 'Go to Blood Portal',
                'link' => 'blood.php'
            ],

            // 2. Emergency Response Team
            'emergency-team' => [
                'slug' => 'emergency-team',
                'pillar' => 'emergency',
                'title' => 'Emergency Response Team',
                'title_en' => 'Emergency Response Team',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-telephone-plus-fill',
                'color' => '#ef233c',
                'summary' => '24/7 global rapid helpline and rescue desk for mariners facing sudden distress, piracy risk, abandonment, or medical crises abroad.',
                'description' => 'A dedicated crisis intervention cell designed to coordinate urgent rescue and welfare operations whenever Bangladeshi mariners face vessel abandonment by shipowners, unfair detention, acute medical emergencies at sea, or sign-off crises.',
                'problem' => 'In distant foreign ports or anchorages, unscrupulous shipowners or local agents sometimes withhold crew wages, deny medical disembarkation, or abandon crews without provisions, while mariners lack direct channels to embassies or international authorities.',
                'solution' => 'The BMMC Emergency Response Team establishes direct rapid liaison with Bangladesh Consulates, DG Shipping, ITF Inspectors, and international seafarer welfare organizations to secure swift legal, medical, and repatriation support.',
                'features' => [
                    '24/7 global satellite and WhatsApp emergency hotline',
                    'Urgent intervention for port abandonment and sign-off impasses',
                    'Direct protocol liaison with Bangladesh Embassies and Department of Shipping',
                    'Medical evacuation and airlift coordination for critically ill seafarers',
                    'Online emergency case intake and resolution tracking dashboard'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=emergency-team'
            ],

            // 3. Legal Support Cell
            'legal-support' => [
                'slug' => 'legal-support',
                'pillar' => 'emergency',
                'title' => 'Legal Support Cell & ITF Guidance',
                'title_en' => 'Legal Support Cell & ITF Guidance',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-hammer',
                'color' => '#f59e0b',
                'summary' => 'Advisory cell led by experienced Master Mariners and maritime lawyers protecting employment agreements (SEA), watch-keeping hours, and onboard rights.',
                'description' => 'A specialized maritime legal wing operating under international maritime law conventions (MLC 2006, STCW, IMO) to protect seafarers\' contracts, fair wages, humane working conditions, and onboard human rights.',
                'problem' => 'Unscrupulous recruiters frequently persuade inexperienced cadets and crew into signing one-sided, defective contracts, resulting in wage deductions, excessive hours, or lack of insurance coverage at sea.',
                'solution' => 'Independent pre-joining contract vetting and free legal counsel under ITF standards to safeguard mariner rights and resolve maritime disputes.',
                'features' => [
                    'Complimentary pre-signing contract vetting (SEA validation)',
                    'MLC 2006 and ILO Seafarers\' Bill of Rights compliance guidelines',
                    'Overtime disputes and rest hour violation guidance',
                    'Global ITF inspectorate and local maritime legal aid directory',
                    'Strictly confidential one-to-one maritime legal consultations'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=legal-support'
            ],

            // 4. Maritime Forum & Q/A Hub
            'maritime-forum' => [
                'slug' => 'maritime-forum',
                'pillar' => 'community',
                'title' => 'Maritime Forum & Q/A Hub',
                'title_en' => 'Maritime Forum & Q/A Hub',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-chat-square-quote-fill',
                'color' => '#00d2ff',
                'summary' => 'An interactive maritime knowledge repository where junior officers and cadets interact directly with veteran Captains and Chief Engineers.',
                'description' => 'A rich knowledge platform for all Bangladeshi mariners, enabling knowledge sharing on complex nautical, engineering, and electro-technical topics, CoC oral exam preparation, and onboard troubleshooting.',
                'problem' => 'Newly joined cadets and junior officers frequently lack reliable, verified mentoring when confronting unfamiliar machinery, bridge challenges, or oral exam questions.',
                'solution' => 'Category-specific moderated forums archiving verified solutions from experienced nautical and engineering superintendents across Bangladesh and international fleets.',
                'features' => [
                    'Departmental discussion categories (Nautical, Engine, ETO, Catering)',
                    'DG Shipping CoC oral & written examination experience archives',
                    'Real-world machinery troubleshooting and incident case studies',
                    'Verified Chief Engineer and Master Mariner badge endorsements',
                    'Searchable, peer-reviewed maritime technical query database'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=maritime-forum'
            ],

            // 5. Manning Agency Review / Rating
            'agency-rating' => [
                'slug' => 'agency-rating',
                'pillar' => 'career',
                'title' => 'Manning Agency & Training Review',
                'title_en' => 'Manning Agency & Training Review',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-star-half',
                'color' => '#f59e0b',
                'summary' => 'Official registry of authorized manning agents coupled with genuine seafarer ratings to dismantle fraudulent recruitment schemes.',
                'description' => 'An independent review portal listing DG Shipping-licensed manning agents, their historical compliance track records, and authentic, unedited feedback from active mariners.',
                'problem' => 'Unlicensed brokers and fraudulent agencies deceive aspiring cadets and crew members with fabricated offer letters, extracting exorbitant sums for non-existent voyages.',
                'solution' => 'Official license verification tools and crowdsourced seafarer ratings that expose predatory recruiters and highlight reputable shipping principals.',
                'features' => [
                    'Up-to-date registry of DG Shipping-approved manning agencies',
                    'Transparent seafarer rating metrics (onboard food, timely wages, sign-off compliance)',
                    'Real-time blacklist alert registry of fraudulent entities',
                    'Independent review of maritime training academies and simulator facilities',
                    'Whistleblower portal to anonymously report recruitment malpractice'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=agency-rating'
            ],

            // 6. Loss of Life / Pension Support Fund Info
            'pension-fund' => [
                'slug' => 'pension-fund',
                'pillar' => 'emergency',
                'title' => 'Loss of Life / Pension Support Fund',
                'title_en' => 'Loss of Life / Pension Support Fund',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-heartbreak-fill',
                'color' => '#ef233c',
                'summary' => 'Rapid community fund mobilization and pension claim advisory to stand beside bereaved families of fallen mariners.',
                'description' => 'A dedicated brotherhood welfare desk ensuring that when a Bangladeshi mariner is lost at sea, suffers fatal accidents, or sustains permanent disability, their family is never left abandoned to economic despair.',
                'problem' => 'When a seafarer loses their life on duty, lengthy insurance litigation and corporate bureaucracy can leave their grieving dependents without financial resources for survival.',
                'solution' => 'Instant community-wide broadcast notification and transparent escrow-backed emergency disbursements directly into verified family bank accounts.',
                'features' => [
                    'Instant community broadcast notification during maritime fatalities',
                    '100% public, audit-backed emergency aid disbursement dashboard',
                    'Legal assistance for securing P&I Club and owner death compensation',
                    'Educational scholarships for children of deceased mariners',
                    'Direct bank wire transfers with published audit statements'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=pension-fund'
            ],

            // 7. Fresh Cadet Training Programme
            'cadet-programme' => [
                'slug' => 'cadet-programme',
                'pillar' => 'career',
                'title' => 'Fresh Cadet Training Programme',
                'title_en' => 'Fresh Cadet Training Programme',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-compass-fill',
                'color' => '#00d2ff',
                'summary' => 'A comprehensive Do\'s and Don\'ts orientation guide and mentorship initiative for cadets embarking on their maiden deep-sea voyage.',
                'description' => 'Bridging the critical gap between academic theory and deep-sea reality. Preparing deck and engine cadets for shipboard hierarchy, bridge watch-keeping, engine room safety, and Training Record Book (TRB) mastery.',
                'problem' => 'New cadets frequently experience severe culture shock and operational uncertainty on their first voyage, risking poor appraisals or safety incidents.',
                'solution' => 'Senior Master Mariners and Chief Engineers deliver practical multimedia guidance, TRB strategies, and personal mentorship to cultivate disciplined, competent maritime officers.',
                'features' => [
                    'Comprehensive Do\'s & Don\'ts handbook and code of conduct',
                    'Practical checklists for navigational bridge and engine watch-keeping',
                    'Systematic Training Record Book (TRB) daily completion roadmap',
                    'Intercultural adaptation guidelines for multinational crew environments',
                    'Direct mentoring and motivational workshops with senior officers'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=cadet-programme'
            ],

            // 8. CDC Smart Profile & One-Click CV Builder
            'cv-builder' => [
                'slug' => 'cv-builder',
                'pillar' => 'tools',
                'title' => 'CDC Smart Profile & One-Click CV Builder',
                'title_en' => 'CDC Smart Profile & One-Click CV Builder',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-file-earmark-person-fill',
                'color' => '#00d2ff',
                'summary' => 'Automated professional PDF resume generator adhering strictly to international IMO standards and global crewing company formats.',
                'description' => 'Eliminates repetitive manual CV reformatting. Mariners log their voyages, vessel parameters, and certificates once into a secure profile to generate tailored, publication-grade maritime resumes instantly.',
                'problem' => 'International ship managers require precise IMO-compliant resume formats. Inconsistently formatted CVs often cause qualified mariners to be filtered out by recruitment algorithms.',
                'solution' => 'A dynamic resume generator producing tailored layouts for Tankers, Bulk Carriers, Containers, and Offshore vessels with instant PDF export and verification QR codes.',
                'features' => [
                    'International IMO Seafarer CV format compliance',
                    'Vessel-specific resume layouts (Tanker, Container, Bulk, Offshore)',
                    'Automated sea-time aggregation and rank duration computations',
                    'Instant PDF download equipped with tamper-evident QR verification',
                    'Seamless automated updates whenever new voyages are logged'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=cv-builder'
            ],

            // 9. Social Media & Batch Community Integration
            'batch-community' => [
                'slug' => 'batch-community',
                'pillar' => 'community',
                'title' => 'Social Media & Batch Network',
                'title_en' => 'Social Media & Batch Network',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-people-fill',
                'color' => '#8b5cf6',
                'summary' => 'Uniting alumni batch groups, maritime academy graduates, and global Facebook/WhatsApp seafarer communities under a single umbrella.',
                'description' => 'An official partnership and broadcasting hub linking academy batch committees, professional forums, and overseas Bangladeshi seafarer associations into a unified network.',
                'problem' => 'Fragmented social groups prevent emergency blood appeals, job circulars, and crisis alerts from reaching the broader maritime fraternity in time.',
                'solution' => 'A centralized federation enabling verified batch representatives and community leaders to coordinate welfare, broadcasts, and mutual support efficiently.',
                'features' => [
                    'Comprehensive Bangladeshi mariner batch directory and point-of-contact system',
                    'Verified community hubs across Facebook, Telegram, and WhatsApp',
                    'Central welfare advisory panel formed by senior batch delegates',
                    'Synchronized multi-channel broadcast for maritime emergency appeals',
                    'Coordination of annual reunions to strengthen professional brotherhood'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=batch-community'
            ],

            // 10. Sea-Time Calculator
            'seatime-calculator' => [
                'slug' => 'seatime-calculator',
                'pillar' => 'tools',
                'title' => 'Sea-Time & Watch-keeping Calculator',
                'title_en' => 'Sea-Time & Watch-keeping Calculator',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-calculator-fill',
                'color' => '#f59e0b',
                'summary' => 'Precision algorithm computing qualifying sea service, calendar days, and bridge/engine watch-keeping hours in strict compliance with STCW & DG Shipping.',
                'description' => 'Accurate sea-time computation is essential for CoC examinations and certificate revalidation. This smart utility ensures error-free qualification reporting under STCW Manila Amendments.',
                'problem' => 'Manual sea-time calculation across multiple ships with varied trading limits frequently leads to discrepancies and examination application delays.',
                'solution' => 'Simply enter sign-on/sign-off dates, trading limits, and vessel tonnage/power to instantly generate an official qualifying sea-service statement.',
                'features' => [
                    'STCW 2010 Manila Amendments compliant mathematical formulas',
                    'Automated classification of Foreign Going (FG) vs. Coastal voyages',
                    'Real-time tracking of remaining sea-time required for next CoC tier',
                    'Print-ready, downloadable Sea Service Statement summaries',
                    'Multi-vessel entry reconciliation aligned with official CDC log pages'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=seatime-calculator'
            ],

            // 11. Medical & Test Centre Directory
            'medical-directory' => [
                'slug' => 'medical-directory',
                'pillar' => 'health',
                'title' => 'Medical & Test Centre Directory',
                'title_en' => 'Medical & Test Centre Directory',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-hospital',
                'color' => '#10b981',
                'summary' => 'Trusted directory of maritime physicians, DG Shipping panel doctors, and certified pre-sea medical examination centers.',
                'description' => 'A verified guide to authorized medical examiners, mandatory pre-boarding vaccinations (Yellow Fever, Polio), and diagnostic centers certified by global flag administrations.',
                'problem' => 'Undergoing medical examinations at unapproved facilities results in rejection by shipping principals and port state authorities, squandering precious time and funds.',
                'solution' => 'A curated directory detailing contact information, consultation fees, and flag approvals (Panama, Marshall Islands, Liberia, Singapore) for certified clinics.',
                'features' => [
                    'Official roster of DG Shipping-approved marine surgeons and panel clinics',
                    'Flag-state filtering (Panama, Liberia, Singapore, Marshall Islands)',
                    'Yellow Fever vaccination scheduling, locations, and certificate guidance',
                    'Transparent baseline pricing and prerequisite documentation checklists',
                    'Direct phone connectivity and GPS navigational mapping'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=medical-directory'
            ],

            // 12. Tax, NRI Account & Financial Advisory
            'financial-advisory' => [
                'slug' => 'financial-advisory',
                'pillar' => 'tools',
                'title' => 'Tax, NRI Account & Financial Advisory',
                'title_en' => 'Tax, NRI Account & Financial Advisory',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-cash-coin',
                'color' => '#10b981',
                'summary' => 'Legal guidance on Non-Resident (NRB) status, 182-day sea service tax exemptions, offshore banking, and government remittance incentives.',
                'description' => 'Navigating financial regulations for mariners. Assisting with National Board of Revenue (NBR) tax exemptions, NRB foreign currency bank accounts, and legitimate wealth protection.',
                'problem' => 'Unfamiliarity with tax laws leads to erroneous tax assessments, while many mariners miss out on official government remittance bonuses and offshore investment vehicles.',
                'solution' => 'Clear legal guidelines curated with marine finance experts and chartered accountants to optimize tax exemptions and legitimate wealth building.',
                'features' => [
                    'Step-by-step zero-tax filing guide based on 182 days qualifying sea service',
                    'Streamlined NRB and offshore foreign currency bank account opening procedures',
                    'Access to the official 2.5% government remittance incentive on earnings',
                    'Retirement asset planning, mutual funds, and seafarer pension roadmap',
                    'Direct consultations with experienced maritime tax attorneys'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=financial-advisory'
            ],

            // 13. Digital Logbook & Document Expiry Tracker
            'document-tracker' => [
                'slug' => 'document-tracker',
                'pillar' => 'tools',
                'title' => 'Digital Logbook & Document Expiry Tracker',
                'title_en' => 'Digital Logbook & Document Expiry Tracker',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-journal-check',
                'color' => '#00d2ff',
                'summary' => 'Military-grade encrypted digital safety vault sending automated WhatsApp and Email notifications up to 12 months before certificate expiry.',
                'description' => 'Never let an expired passport, CDC, CoC, STCW refresher, or yellow fever booklet jeopardize your career. A secure cloud vault with proactive renewal notifications.',
                'problem' => 'Amid intense shipboard operations, mariners frequently overlook expiration dates, resulting in unexpected port visa penalties or sign-off delays.',
                'solution' => 'Automated alerts delivered at 12 months, 6 months, and 90 days prior to expiry, providing ample lead time for course bookings and renewals.',
                'features' => [
                    'Central encrypted repository for Passport, CDC, CoC, Visas, and STCW certificates',
                    'Tiered multi-stage alerts triggered 12, 6, and 3 months ahead of expiration',
                    'Direct notifications dispatched via Email and WhatsApp',
                    'Direct links to official renewal applications and requisite documentation guides',
                    'AES-256 encrypted cloud backup accessible anywhere worldwide'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=document-tracker'
            ],

            // 14. WhatsApp Single-Bot Notification
            'whatsapp-bot' => [
                'slug' => 'whatsapp-bot',
                'pillar' => 'tools',
                'title' => 'WhatsApp Single-Bot Notification',
                'title_en' => 'WhatsApp Single-Bot Notification',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-whatsapp',
                'color' => '#10b981',
                'summary' => 'Official WhatsApp bot delivering instant notifications for urgent blood appeals, job openings, and maritime safety alerts directly to your phone.',
                'description' => 'Delivering mission-critical BMMC communications without requiring any app installations. Optimized for ultra-low satellite data usage at sea.',
                'problem' => 'High-latency satellite connections on ocean voyages make browsing heavy websites impractical, causing mariners to miss urgent appeals.',
                'solution' => 'Ultra-lightweight text alerts delivered straight into your personal WhatsApp messaging stream with zero data overhead.',
                'features' => [
                    'Instant localized blood emergency alerts pushed directly to available donors',
                    'Verified marine vacancy announcements and certificate course updates',
                    'Automated status queries for sea-time computations and donor eligibility',
                    '100% spam-free, user-controlled subscription preferences',
                    'Flawless performance over low-bandwidth maritime satellite internet'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=whatsapp-bot'
            ],

            // 15. Seafarer Verification Badge
            'verification-badge' => [
                'slug' => 'verification-badge',
                'pillar' => 'tools',
                'title' => 'Seafarer Verification Badge (Verified Mariner)',
                'title_en' => 'Seafarer Verification Badge (Verified Mariner)',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-patch-check-fill',
                'color' => '#00d2ff',
                'summary' => 'Prestigious blue badge awarded upon rigorous verification of authentic CDC, SID, and government documentation.',
                'description' => 'Safeguarding community trust and dignity. Authenticates practicing mariners to eliminate impostors, fake recruitment agents, and fraudulent accounts.',
                'problem' => 'Impostors masquerading as mariners on social platforms mislead the public, orchestrate fraudulent visa operations, and solicit illicit donations.',
                'solution' => 'A robust verification protocol conducted by the BMMC credentialing panel, awarding a distinct digital seal of authenticity to confirmed seafarers.',
                'features' => [
                    'Official CDC, SID, and identity document validation',
                    'Distinctive blue Verified Mariner badge displayed across public profiles',
                    'Priority access to internal maritime forums and verified career boards',
                    'Enhanced community trust and professional authenticity',
                    'Strict zero-tolerance policy against forged documents and credentials'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=verification-badge'
            ],

            // 16. BMMC Member Digital ID Card
            'digital-id' => [
                'slug' => 'digital-id',
                'pillar' => 'tools',
                'title' => 'BMMC Member Digital ID Card',
                'title_en' => 'BMMC Member Digital ID Card',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-qr-code-scan',
                'color' => '#f59e0b',
                'summary' => 'Dynamic QR-coded smart identity card for seamless proof of membership, emergency contacts, and blood group verification.',
                'description' => 'A modern digital credentials card for registered BMMC members. Scanning the dynamic QR code instantly authenticates active membership, blood group, and emergency contact records.',
                'problem' => 'In emergency scenarios or hospital admissions, quick proof of maritime affiliation and blood group was previously hindered by paper documents.',
                'solution' => 'A tamper-proof digital card compatible with Apple/Google Wallets, also exportable as a high-resolution printable document.',
                'features' => [
                    'Dynamic secure QR code scanning technology',
                    'Instant verification of blood group, maritime rank, and emergency contacts',
                    'Exportable to smartphone mobile wallets (Apple Wallet & Google Pay)',
                    'Special privileges and discounts at affiliated hospitals and diagnostic labs',
                    'Print-ready high-resolution PNG and PDF credentials export'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=digital-id'
            ],

            // 17. Transparent Donation & Expense Dashboard
            'financial-dashboard' => [
                'slug' => 'financial-dashboard',
                'pillar' => 'community',
                'title' => 'Transparent Donation & Expense Dashboard',
                'title_en' => 'Transparent Donation & Expense Dashboard',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-graph-up-arrow',
                'color' => '#10b981',
                'summary' => 'A 100% open public ledger publishing every taka received and spent on servers, emergency relief, and welfare operations.',
                'description' => 'Since BMMC operates on pure voluntary goodwill, financial integrity is paramount. This open dashboard provides real-time visibility into all organizational income and expenditures.',
                'problem' => 'Lack of financial transparency in non-profit initiatives frequently breeds skepticism and erodes public trust.',
                'solution' => 'Every single contribution and expense voucher is published directly on the platform for public audit by all community members.',
                'features' => [
                    'Real-time public ledger tracking all donations and operational costs',
                    'Monthly downloadable financial audit statements and scanned receipt vouchers',
                    'Detailed cost allocations (server infrastructure, welfare funds, emergency aid)',
                    'Independent audit reviews certified by a revolving committee of Master Mariners',
                    'Strict compliance with 100% non-profit, non-commercial bylaws'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=financial-dashboard'
            ],

            // 18. Privacy & Data Security Policy
            'privacy-policy' => [
                'slug' => 'privacy-policy',
                'pillar' => 'community',
                'title' => 'Privacy & Military-Grade Data Security Policy',
                'title_en' => 'Privacy & Military-Grade Data Security Policy',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-shield-lock-fill',
                'color' => '#8b5cf6',
                'summary' => 'Bank-grade AES-256 encryption ensuring seafarer CDC, passport, and CV data remains strictly private and protected.',
                'description' => 'Protecting mariners\' sensitive identity and career records with high-grade cybersecurity protocols. Zero third-party data monetization or unauthorized sharing.',
                'problem' => 'Predatory platforms scrape mariners\' passport and CDC records for identity theft, document forgery, or fraudulent crewing schemes.',
                'solution' => 'AES-256 database encryption, anti-bot firewalls, and strict GDPR-aligned data protection principles to ensure full data sovereignty.',
                'features' => [
                    'AES-256 bit encrypted database architecture',
                    'Automated anti-scraping and cloud firewall safeguards',
                    'Strict prohibition against third-party data transfer without explicit consent',
                    'Full right to permanent data deletion upon user request (GDPR standard)',
                    'Continuous vulnerability testing and third-party security audits'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=privacy-policy'
            ],

            // 19. Shore-Based Career Guidance Cell
            'shore-career' => [
                'slug' => 'shore-career',
                'pillar' => 'career',
                'title' => 'Shore-Based Career Guidance Cell',
                'title_en' => 'Shore-Based Career Guidance Cell',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-building-fill',
                'color' => '#00d2ff',
                'summary' => 'Specialized transition guidance, CV adaptation, and networking for senior seafarers seeking honorable shore-based careers.',
                'description' => 'Assisting mariners in making a smooth, rewarding transition from deep-sea life to shore-based positions in marine surveying, shipyard management, port operations, and classification societies.',
                'problem' => 'After years at sea, senior mariners wishing to return home often struggle to navigate the civilian maritime job market or tailor their executive resumes.',
                'solution' => 'Mentorship from veteran ex-mariners holding executive shore roles, coupled with direct employer introductions across ports and shipping management.',
                'features' => [
                    'Mapping of civilian maritime career pathways in Bangladesh and abroad',
                    'Entry requirements for Marine Surveying, Classification Societies, and P&I Clubs',
                    'Shipyard superintendent, drydocking, and port logistics transition roadmaps',
                    'Confidential one-to-one executive mentoring with senior ex-mariners',
                    'Annual Maritime Professionals Shore Career Fair and Industry Networking Meet'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=shore-career'
            ],

            // 20. Verification & Anti-Fraud Alert
            'anti-fraud' => [
                'slug' => 'anti-fraud',
                'pillar' => 'emergency',
                'title' => 'Verification & Anti-Fraud Alert Board',
                'title_en' => 'Verification & Anti-Fraud Alert Board',
                'status' => 'coming_soon',
                'badge' => 'Under Development (Coming Soon)',
                'icon' => 'bi-exclamation-triangle-fill',
                'color' => '#ef233c',
                'summary' => 'Live community-reported blacklist and vigilance board dismantling fake crewing agencies and maritime visa scams.',
                'description' => 'A real-time community intelligence and awareness hub designed to neutralize fraudulent recruiters, bogus offer letters, and international visa rackets targeting mariners.',
                'problem' => 'Every year, dozens of young sailors fall victim to counterfeit contracts, losing substantial sums to unauthorized brokers who disappear overnight.',
                'solution' => 'A free verification service where suspicious offer letters are authenticated, and proven scams are publicly exposed on our vigilance board.',
                'features' => [
                    'Crowdsourced, verified blacklist of scam recruiters and bogus agencies',
                    'Free community helpdesk to authenticate offer letters and visa credentials',
                    'Red-flag indicators checklist educating cadets on deceptive recruitment practices',
                    'Direct legal referral for victims seeking statutory law enforcement action',
                    'Periodic intelligence reports shared with DG Shipping and law enforcement agencies'
                ],
                'button_text' => 'Explore Service Roadmap',
                'link' => 'service.php?slug=anti-fraud'
            ],

            // 21. Become a BMMC Volunteer Database
            'volunteer-network' => [
                'slug' => 'volunteer-network',
                'pillar' => 'community',
                'title' => 'BMMC Volunteer Network & Database',
                'title_en' => 'BMMC Volunteer Network & Database',
                'status' => 'coming_soon',
                'badge' => 'Open Registration',
                'icon' => 'bi-person-heart',
                'color' => '#00d2ff',
                'summary' => 'A nationwide humanitarian corps across Chattogram, Dhaka, Khulna, and ships worldwide, standing ready to serve seafarers and citizens.',
                'description' => 'The selfless lifeblood of BMMC. Uniting active mariners, cadets, and empathetic citizens into organized local squads dedicated to rescue coordination, cadet mentorship, and blood donation.',
                'problem' => 'When crises strike mariners or their families, coordinated boots-on-the-ground support was previously lacking across port cities and regional hubs.',
                'solution' => 'A nationwide volunteer mobilization network categorized by port and district hubs, seamlessly integrated with the Blood Donation Portal in a single form.',
                'features' => [
                    'Regional volunteer squads stationed in Chattogram, Dhaka, Khulna, and Payra',
                    'Seamless automated integration with the Blood Donor Registry via single-click opt-in',
                    'Active volunteer roles across Emergency Rescue, Cadet Mentorship, and IT utilities',
                    'Annual volunteer appreciation awards, certificates, and recognition ceremonies',
                    '100% apolitical, non-commercial humanitarian brotherhood'
                ],
                'button_text' => 'Fill Volunteer Form',
                'link' => 'volunteer_register.php'
            ]
        ];
    }

    // Bengali Default Array
    return [
        // 1. Blood Donation Network (LIVE FLAGSHIP)
        'blood-network' => [
            'slug' => 'blood-network',
            'pillar' => 'emergency',
            'title' => 'রক্তদান নেটওয়ার্ক',
            'title_en' => 'Blood Donation Network',
            'status' => 'live',
            'badge' => 'সক্রিয় সেবা (Live)',
            'icon' => 'bi-droplet-fill',
            'color' => '#ef233c',
            'summary' => 'মেরিনার ও সাধারণ নাগরিকদের যৌথ জরুরি রক্তদান প্ল্যাটফর্ম, ৪ মাসের স্বয়ংক্রিয় রেস্টিং প্রোটোকল ও দ্রুততম ডোনার ম্যাচিং।',
            'description' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)-এর প্রথম সম্পূর্ণ সক্রিয় মানবসেবামূলক উইং। দেশ ও বিদেশের হাসপাতালে রক্তের সংকটে থাকা যেকোনো রোগী বা মেরিনার পরিবারের জন্য শতভাগ নিঃস্বার্থ ও মধ্যস্থতাকারীবিহীন রক্তদান ব্যবস্থাপনা।',
            'problem' => 'হাসপাতালে সংকটাপন্ন রোগীর জন্য জরুরি মুহূর্তে নির্দিষ্ট গ্রুপের রক্ত খুঁজে পাওয়া অত্যন্ত কষ্টসাধ্য। অনেক সময় একই রক্তদাতাকে বারবার কল দিয়ে শারীরিক ঝুঁকির মুখে ফেলা হয়।',
            'solution' => 'BMMC রক্তদান নেটওয়ার্ক রক্তের গ্রুপ ও ভৌগোলিক এলাকা অনুযায়ী প্রস্তুত ডোনারদের স্বয়ংক্রিয় নোটিফিকেশন পাঠায়। সফল রক্তদানের পর ডোনারের শরীর সুস্থ রাখতে ৪ মাস কোনো নতুন কল যায় না।',
            'features' => [
                'ভৌগোলিক জেলা ও এলাকাভিত্তিক স্মার্ট ডোনার ফিল্টারিং',
                'চিকিৎসাবিজ্ঞানসম্মত ৪ মাসের রেস্টিং পিরিয়ড (Resting Period) অটোমেশন',
                'ওয়ান-ক্লিক এসএমএস/ইমেইল সম্মতি ও সরাসরি সংযোগ',
                '২৪/৭ ডেডিকেটেড জরুরি রক্তের হটলাইন সমন্বয়',
                'মেরিনার ডোনার ও সাধারণ ডোনারদের সম্মিলিত ভলান্টিয়ার ডাটাবেস'
            ],
            'button_text' => 'জরুরি রক্তদান পোর্টালে যান',
            'link' => 'blood.php'
        ],

        // 2. Emergency Response Team
        'emergency-team' => [
            'slug' => 'emergency-team',
            'pillar' => 'emergency',
            'title' => 'ইমার্জেন্সি রেসপন্স টিম',
            'title_en' => 'Emergency Response Team',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-telephone-plus-fill',
            'color' => '#ef233c',
            'summary' => 'সাগরে বা বিদেশের বন্দরে কোনো নাবিক বিপদে পড়লে (আইনগত সমস্যা, সাইনিং-অফ জটিলতা, অসুস্থতা) দ্রুত সহায়তার ২৪/৭ হটলাইন ও উদ্ধার সেল।',
            'description' => 'সাগরে বা বিদেশের বন্দরে কোনো নাবিক আকস্মিক আইনি জটিলতা, মালিকপক্ষ দ্বারা পরিত্যক্ত (Abandonment), সাইনিং-অফ জটিলতা, মারাত্মক অসুস্থতা বা জলদস্যু ঝুঁকিতে পড়লে অবিলম্বে রেসকিউ কোঅর্ডিনেশন সহায়তা প্রদানের বিশেষ ইমার্জেন্সি উইং।',
            'problem' => 'বিদেশের দূরবর্তী বন্দরে জাহাজ নোঙররত অবস্থায় অনেক সময় জাহাজের মালিক বা লোকাল এজেন্ট নাবিকদের বেতন আটকে রাখে কিংবা চিকিৎসা সহায়তা না দিয়ে অসহায় করে ফেলে। দূতাবাস বা আন্তর্জাতিক কর্তৃপক্ষের সাথে যোগাযোগের সঠিক পথ জানা থাকে না।',
            'solution' => 'BMMC ইমার্জেন্সি রেসপন্স টিম সরাসরি আন্তর্জাতিক মেরিটাইম প্রশাসন, বাংলাদেশ কনস্যুলেট, ITF ইন্সপেক্টর এবং মেরিন ওয়েলফেয়ার নেটওয়ার্কের মাধ্যমে দ্রুততম সময়ে সমস্যা সমাধানে আইনগত ও লজিস্টিক সাপোর্ট নিশ্চিত করবে।',
            'features' => [
                '২৪/৭ গ্লোবাল স্যাটেলাইট ও হোয়াটসঅ্যাপ ইমার্জেন্সি হটলাইন',
                'আন্তর্জাতিক পোর্টে জাহাজ অ্যাবান্ডনমেন্ট ও সাইনিং-অফ জরুরি সহায়তা',
                'বাংলাদেশ দূতাবাস ও সমুদ্র পরিবহন অধিদপ্তরের সাথে সরাসরি লিয়াজোঁ',
                'সমুদ্রে গুরুতর অসুস্থ নাবিকদের জন্য এয়ারলিফট ও মেডিকেল সাপোর্ট সমন্বয়',
                'অনলাইনে জরুরি অভিযোগ ও উদ্ধারের রিকোয়েস্ট ট্র্যাকিং সিস্টেম'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=emergency-team'
        ],

        // 3. Legal Support Cell
        'legal-support' => [
            'slug' => 'legal-support',
            'pillar' => 'emergency',
            'title' => 'আইনি সহায়তা সেল',
            'title_en' => 'Legal Support Cell & ITF Guidance',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-hammer',
            'color' => '#f59e0b',
            'summary' => 'সাধারণ চুক্তিপত্র (SEA), ওয়াচ-কিপিং সময় বা অন-বোর্ড রাইটস সুরক্ষায় অভিজ্ঞ মাস্টার মেরিনার ও মেরিটাইম আইনজীবীদের পরামর্শ।',
            'description' => 'নাবিকদের কর্মসংস্থান চুক্তি (Seafarers Employment Agreement - SEA), নিরাপদ কর্মঘণ্টা, বেতন বকেয়া এবং অন-বোর্ড মানবাধিকার সুরক্ষায় আন্তর্জাতিক মেরিটাইম আইন (MLC 2006, STCW, IMO) বিশেষজ্ঞ ও অভিজ্ঞ আইনি পরামর্শকদের তত্ত্বাবধানে পরিচালিত লিগ্যাল সেল।',
            'problem' => 'অনেক এজেন্সি অনভিজ্ঞ নাবিকদের এমন ত্রুটিপূর্ণ বা একতরফা চুক্তিতে সই করিয়ে নেয় যাতে জাহাজে গিয়ে অতিরিক্ত শ্রম, অপর্যাপ্ত খাবার বা বেতন কর্তনের শিকার হতে হয়। সঠিক আইনি তথ্যের অভাবে নাবিকরা তাদের ন্যায্য অধিকার থেকে বঞ্চিত হন।',
            'solution' => 'জাহাজে ওঠার আগেই চুক্তিপত্র পরীক্ষা-নিরীক্ষা (Contract Vetting) এবং অন-বোর্ড কোনো অবিচার হলে ITF নিয়ম অনুযায়ী অধিকার আদায়ে বিনা ফিতে পরামর্শ ও দিকনির্দেশনা প্রদান।',
            'features' => [
                'সাইন-অন এর পূর্বে SEA চুক্তিপত্রের ফ্রি লিগ্যাল ভেটিং',
                'MLC 2006 ও আন্তর্জাতিক শ্রম সংস্থার (ILO) অধিকার নির্দেশিকা',
                'অন-বোর্ড ওয়ার্কিং আওয়ার ও ওভারটাইম ডিসপিউট সমাধান গাইড',
                'গ্লোবাল ITF ইন্সপেক্টরেট ও লোকাল মেরিটাইম লিগ্যাল এইড ডিরেক্টরি',
                'গোপনীয়তার সাথে অভিজ্ঞদের ব্যক্তিগত আইনি পরামর্শ নেওয়ার সুবিধা'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=legal-support'
        ],

        // 4. Maritime Forum & Q/A
        'maritime-forum' => [
            'slug' => 'maritime-forum',
            'pillar' => 'community',
            'title' => 'মেরিটাইম ফোরাম ও প্রশ্নোত্তর',
            'title_en' => 'Maritime Forum & Q/A Hub',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-chat-square-quote-fill',
            'color' => '#00d2ff',
            'summary' => 'ওপেন ব্লগের পাশাপাশি নির্দিষ্ট ফোরাম যেখানে জুনিয়র বা ক্যাডেটরা অভিজ্ঞ ক্যাপ্টেন ও চিফ ইঞ্জিনিয়ারদের সরাসরি টেকনিক্যাল বা নেভিগেশনাল প্রশ্ন করতে পারবে।',
            'description' => 'বাংলাদেশের সর্বস্তরের মেরিনারদের জন্য একটি সমৃদ্ধ ইন্টারেক্টিভ নলেজ হাব। যেখানে ডেকে ও ইঞ্জিন রুমে উদ্ভূত জটিল প্রযুক্তিগত সমস্যা, ওরাল পরীক্ষা প্রস্তুতি, ইকুইপমেন্ট মেইনটেন্যান্স এবং নেভিগেশনাল চ্যালেঞ্জ নিয়ে সিনিয়রদের সাথে সরাসরি জ্ঞান আদান-প্রদান করা যাবে।',
            'problem' => 'জাহাজে নতুন জয়েন করা ক্যাডেট ও জুনিয়র অফিসাররা অনেক সময় বাস্তব যন্ত্রপাতি চালানো বা সার্টিফিকেশন ওরাল পরীক্ষার প্রশ্নোত্তরে দিকভ্রান্ত বোধ করেন। নির্ভরযোগ্য সোর্সের অভাব থাকে।',
            'solution' => 'ক্যাটাগরিভিত্তিক ওপেন ফোরাম—যেখানে ইঞ্জিন, নটিক্যাল, ইলেকট্রো-টেকনিক্যাল ও রুলস অব দ্য রোড (ROR) নিয়ে অভিজ্ঞ অফিসারদের ভেরিফায়েড উত্তরের আর্কাইভ সংরক্ষিত থাকবে।',
            'features' => [
                'ডিপার্টমেন্ট অনুযায়ী সেগমেন্টেড ডিসকাশন থ্রেড (Nautical, Engine, ETO, Catering)',
                'ডিজি শিপিং CoC ওরাল ও রিটেন পরীক্ষার রিয়েল এক্সপেরিয়েন্স ব্যাংক',
                'ইকুইপমেন্ট ট্রাবলশুটিং ও কেস স্টাডি লাইব্রেরি',
                'ভেরিফায়েড চিফ ইঞ্জিনিয়ার ও ক্যাপ্টেনদের ব্যাজড উত্তর',
                'সার্চেবল মেরিটাইম টেকনিক্যাল প্রশ্ন-উত্তর ডেটাবেস'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=maritime-forum'
        ],

        // 5. Manning Agency Review / Rating
        'agency-rating' => [
            'slug' => 'agency-rating',
            'pillar' => 'career',
            'title' => 'ম্যানিং এজেন্সি ও ট্রেনিং রিভিউ',
            'title_en' => 'Manning Agency & Training Review',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-star-half',
            'color' => '#f59e0b',
            'summary' => 'অনুমোদিত শিপিং ও ম্যানিং এজেন্সিগুলোর সরকারি ডেটাবেস এবং নাবিকদের জেনুইন বাস্তব রিভিউ (যাতে ভুয়া এজেন্সির প্রতারণা বন্ধ হয়)।',
            'description' => 'সমুদ্র পরিবহন অধিদপ্তর (DG Shipping) অনুমোদিত বৈধ ম্যানিং এজেন্টদের তালিকা, সার্ভিস ট্র‍্যাক রেকর্ড এবং সেগুলোতে কর্মরত বাংলাদেশি নাবিকদের সরাসরি স্বাধীন ও নিরপেক্ষ রেটিং প্ল্যাটফর্ম।',
            'problem' => 'অনুমোদনহীন দালাল চক্র ও ফেক ম্যানিং এজেন্সি বিভিন্ন চটকদার বিজ্ঞাপন দিয়ে সাধারণ ক্যাডেট ও নাবিকদের লাখ লাখ টাকা হাতিয়ে নিয়ে ভুয়া অফার লেটার ধরিয়ে দেয়।',
            'solution' => 'সরকারি অনুমোদিত বৈধ লাইসেন্স নম্বর যাচাইকরণ টুল এবং হাজারো মেরিনারের রেটিং ও সত্য রিভিউয়ের মাধ্যমে প্রতারকদের মুখোশ উন্মোচন ও নিরাপদ চাকরি প্রাপ্তি নিশ্চিতকরণ।',
            'features' => [
                'ডিজি শিপিং অনুমোদিত বৈধ শিপিং ও ম্যানিং এজেন্সির আপডেটেড তালিকা',
                'নাবিকদের বাস্তব অভিজ্ঞতাভিত্তিক স্বচ্ছ রেটিং (অন-বোর্ড ফুড, অন-টাইম স্যালারি, সাইন-অফ)',
                'ফেক এজেন্সি ও স্ক্যামারদের ব্ল্যাকলিস্ট ডেটাবেস',
                'ট্রেইনিং ইনস্টিটিউট ও সিমুলেটর কোর্সের গুণগত মান যাচাই',
                'গোপনীয়তা রক্ষা করে প্রতারণার ঘটনা সরাসরি রিপোর্ট করার পোর্টাল'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=agency-rating'
        ],

        // 6. Loss of Life / Pension Support Fund Info
        'pension-fund' => [
            'slug' => 'pension-fund',
            'pillar' => 'emergency',
            'title' => 'জীবনহানি ও পেনশন সহায়তা তহবিল',
            'title_en' => 'Loss of Life / Pension Support Fund',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-heartbreak-fill',
            'color' => '#ef233c',
            'summary' => 'সার্ভিস চলাকালে কোনো সহকর্মী মারা গেলে তার অসহায় পরিবারের পাশে দাঁড়াতে বিশেষ তহবিল সংগ্রহ ও জরুরি নোটিফিকেশন ক্যাম্পেইন।',
            'description' => 'উত্তাল সাগরে দেশের জন্য রেমিট্যান্স উপার্জন করতে গিয়ে কোনো মেরিনার ভাই নিহত, নিখোঁজ বা স্থায়ী পঙ্গুত্বের শিকার হলে তার শোকসন্তপ্ত পরিবার যেন চরম অর্থকষ্টে না পড়ে, সেজন্য দ্রুততম ফান্ড সংগ্রহ ও পেনশন ক্লিম সহায়তা উইং।',
            'problem' => 'জাহাজে কর্মরত অবস্থায় নাবিক মারা গেলে তার পরিবার বীমা ও ক্ষতিপূরণের দীর্ঘ আইনি মারপ্যাঁচে নিঃস্ব হয়ে পড়ে এবং তাৎক্ষণিক সংসার চালানোর মতো কোনো তহবিল থাকে না।',
            'solution' => 'BMMC প্ল্যাটফর্মের মাধ্যমে সহকর্মীর মৃত্যুর খবর সমস্ত সদস্য ও কমিউনিটির কাছে নোটিফিকেশন আকারে যাবে এবং সম্পূর্ণ স্বচ্ছ এসক্রো অ্যাকাউন্টের মাধ্যমে আর্থিক সহায়তা সরাসরি পরিবারের হাতে পৌঁছে দেওয়া হবে।',
            'features' => [
                'তাৎক্ষণিক ইমার্জেন্সি নোটিফিকেশন ও ব্লাস্ট ব্রডকাস্ট সিস্টেম',
                'শতভাগ লাইভ ও স্বচ্ছ অনুদান সংগ্রহ ও ডিসবার্সমেন্ট ড্যাশবোর্ড',
                'শিপিং কোম্পানি ও পিএন্ডআই ক্লাব (P&I Club) থেকে মৃত্যু ক্ষতিপূরণ আদায়ের আইনি গাইড',
                'নিহত মেরিনারদের সন্তানদের শিক্ষাবৃত্তি সহায়তা উদ্যোগ',
                'ব্যাংক অ্যাকাউন্টে সরাসরি অর্থ হস্তান্তর ও অডিট রিপোর্ট'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=pension-fund'
        ],

        // 7. Fresh Cadet Training Programme
        'cadet-programme' => [
            'slug' => 'cadet-programme',
            'pillar' => 'career',
            'title' => 'ফ্রেশ ক্যাডেট ট্রেইনিং প্রোগ্রাম',
            'title_en' => 'Fresh Cadet Training Programme',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-compass-fill',
            'color' => '#00d2ff',
            'summary' => 'হোয়াট টু ডু অ্যান্ড হোয়াট নট টু ডু—প্রথমবার জাহাজে সাইন-অন করার আগে ক্যাডেটদের জন্য পূর্ণাঙ্গ ডুস অ্যান্ড ডোন্টস গাইড।',
            'description' => 'মেরিন একাডেমি থেকে পাস করা নতুন ডেক ও ইঞ্জিন ক্যাডেটদের জন্য বাস্তব জাহাজি জীবনের পূর্ণাঙ্গ ওরিয়েন্টেশন। জাহাজের অনুশাসন, ব্রিজ ওয়াচ, ইঞ্জিন রুম প্রোটোকল এবং ট্রেইনিং রেকর্ড বুক (TRB) পূরণের সঠিক কৌশল শেখানো।',
            'problem' => 'একাডেমির থিওরিটিক্যাল পড়াশোনা আর জাহাজের কঠোর বাস্তবতার মধ্যে বিশাল অমিল থাকে। প্রথম সাইন-অন করে অনেক ক্যাডেট ভুল আচরণ বা দ্বিধাদ্বন্দ্বের কারণে অন-বোর্ড মূল্যায়নে বিপদে পড়েন।',
            'solution' => 'সিনিয়র মাস্টার মেরিনার ও চিফ ইঞ্জিনিয়ারদের তৈরি অডিও-ভিজ্যুয়াল গাইডবুক, হ্যান্ডবুক ও মেন্টরশিপ প্রোগ্রামের মাধ্যমে ক্যাডেটদের জাহাজের যোগ্য পেশাদার কর্মকর্তা হিসেবে গড়ে তোলা।',
            'features' => [
                'ক্যাডেটদের জন্য Do\'s and Don\'ts গাইডবুক ও আচরণবিধি',
                'ইঞ্জিন ও ব্রিজ ওয়াচ-কিপিং বাস্তবসম্মত চেকলিস্ট',
                'ট্রেইনিং রেকর্ড বুক (TRB) প্রতিদিন নিয়মমাফিক পূরণের টিপস',
                'আন্তর্জাতিক জাহাজে বিভিন্ন দেশের ক্রুদের সাথে কালচারাল অ্যাডাপ্টেশন',
                'সিনিয়র অফিসারদের সরাসরি মেন্টরিং ও মোটিভেশনাল সেশন'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=cadet-programme'
        ],

        // 8. CDC Smart Profile & One-Click CV Builder
        'cv-builder' => [
            'slug' => 'cv-builder',
            'pillar' => 'tools',
            'title' => 'সিডিসি স্মার্ট প্রোফাইল ও সিভি বিল্ডার',
            'title_en' => 'CDC Smart Profile & One-Click CV Builder',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-file-earmark-person-fill',
            'color' => '#00d2ff',
            'summary' => 'তথ্য দিলেই আন্তর্জাতিক আইএমও স্ট্যান্ডার্ড ও শিপিং কোম্পানি ফরম্যাটের প্রফেশনাল PDF CV এক ক্লিকে স্বয়ংক্রিয়ভাবে তৈরি হবে।',
            'description' => 'নাবিকদের ক্যারিয়ারের সবচেয়ে বড় ঝামেলা হলো বারবার জটিল এক্সেল বা ওয়ার্ড ফাইলে সিভি আপডেট করা। BMMC স্মার্ট প্রোফাইলে সমুদ্রের অভিজ্ঞতা একবার যুক্ত করলেই স্বয়ংক্রিয়ভাবে যেকোনো টাইপের জাহাজের জন্য পারফেক্ট সিভি রেডি হবে।',
            'problem' => 'শিপিং কোম্পানিগুলো আন্তর্জাতিক স্ট্যান্ডার্ড ফরম্যাটে সিভি দেখতে চায়। ত্রুটিপূর্ণ সিভির কারণে অনেক যোগ্য মেরিনার ভালো কোম্পানির প্রাথমিক ইন্টারভিউ কল থেকে বাদ পড়েন।',
            'solution' => 'ট্যাংকার, বাল্ক ক্যারিয়ার, কন্টেইনার বা অফশোরের জন্য তৈরি প্রি-ডিজাইনড IMO স্ট্যান্ডার্ড ফরম্যাটে যেকোনো সময় ১-ক্লিকেই ডাউনলোডযোগ্য ডিজিটাল PDF সিভি জেনারেটর।',
            'features' => [
                'আন্তর্জাতিক IMO Seafarer CV ফরম্যাট কমপ্লায়েন্স',
                'জাহাজের ধরনভিত্তিক (Tanker, Container, Bulk, Offshore) কাস্টম লেআউট',
                'স্বয়ংক্রিয় সি-টাইম ও র্যাঙ্ক ডিউরেশন ক্যালকুলেশন',
                'QR কোড ভেরিফিকেশন সহ ইনস্ট্যান্ট PDF ডাউনলোড',
                'যেকোনো নতুন ভয়েজ বা ট্রেনিং যুক্ত করার সাথে সাথে অটো-আপডেট'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=cv-builder'
        ],

        // 9. Social Media & Batch Community Integration
        'batch-community' => [
            'slug' => 'batch-community',
            'pillar' => 'community',
            'title' => 'সামাজিক যোগাযোগ ও ব্যাচ নেটওয়ার্ক',
            'title_en' => 'Social Media & Batch Network',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-people-fill',
            'color' => '#8b5cf6',
            'summary' => 'ফেসবুক, হোয়াটসঅ্যাপ, টেলিগ্রাম এবং মেরিন একাডেমির বিভিন্ন ব্যাচ গ্রুপগুলোকে ঐক্যবদ্ধ প্ল্যাটফর্মে সংযুক্ত করা।',
            'description' => 'বাংলাদেশ মেরিন একাডেমি ও প্রাইভেট মেরিটাইম ইনস্টিটিউটসমূহের ব্যাচভিত্তিক কমিটি এবং দেশ-বিদেশের প্রধান মেরিনার গ্রুপগুলোর সাথে অফিশিয়াল অংশীদারিত্ব ও ব্রডকাস্ট কোঅর্ডিনেশন হাব।',
            'problem' => 'মেরিনারদের সোশ্যাল গ্রুপগুলো ছড়িয়ে-ছিটিয়ে থাকার কারণে জরুরি রক্তের অনুরোধ, চাকরি বা নাবিকদের আইনি বিপদের খবর সময়মতো সঠিক মানুষের কাছে পৌঁছায় না।',
            'solution' => 'BMMC ফেডারেল নেটওয়ার্কের মাধ্যমে সমস্ত ব্যাচ অ্যাডমিন ও সিনিয়রদের যুক্ত করে একটি সমন্বিত শক্তিশালী সেন্ট্রাল চ্যানেল তৈরি করা।',
            'features' => [
                'বাংলাদেশি মেরিনার ব্যাচ ডিরেক্টরি ও যোগাযোগ ব্যবস্থা',
                'ভেরিফায়েড অফিশিয়াল ফেসবুক, টেলিগ্রাম ও হোয়াটসঅ্যাপ কমিউনিটি হাব',
                'ব্যাচ প্রতিনিধিদের সমন্বয়ে গঠিত সেন্ট্রাল ওয়েলফেয়ার কমিটি',
                'জরুরি যেকোনো মেরিন ক্রাইসিসে একযোগে সোশ্যাল ব্রডকাস্ট সুবিধা',
                'পারস্পরিক ভ্রাতৃত্ব ও সৌহার্দ্য বৃদ্ধির বাৎসরিক রি-ইউনিয়ন কোঅর্ডিনেশন'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=batch-community'
        ],

        // 10. Sea-Time Calculator
        'seatime-calculator' => [
            'slug' => 'seatime-calculator',
            'pillar' => 'tools',
            'title' => 'সি-টাইম ক্যালকুলেটর',
            'title_en' => 'Sea-Time & Watch-keeping Calculator',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-calculator-fill',
            'color' => '#f59e0b',
            'summary' => 'সাইন-অন ও সাইন-অফ তারিখ দিলে মোট ওয়াচ-কিপিং, ক্যালেন্ডার ডেজ ও কোয়ালিফাইং সি-টাইম স্বয়ংক্রিয় হিসাবের নির্ভুল টুল।',
            'description' => 'CoC পরীক্ষা ও সার্টিফিকেট রি-ভ্যালিডেশনের জন্য সি-টাইম হিসাব করা প্রত্যেক নাবিকের দৈনন্দিন প্রয়োজন। ডিজি শিপিং ও STCW কনভেনশনের সুনির্দিষ্ট গাণিতিক নিয়মে সি-টাইমের নির্ভুল ডিজিটাল হিসাব।',
            'problem' => 'হাতে কলমে মাস, দিন ও জাহাজের ক্রুজিং সময় হিসাব করতে গিয়ে প্রায়শই ভুল হয়, যার ফলে ডিজি শিপিং এক্সামিনেশনে গিয়ে আবেদন বাতিল বা দেরির শিকার হতে হয়।',
            'solution' => 'জাহাজের ধরণ, সাইন-অন, সাইন-অফ ও অ্যাঙ্কর পিরিয়ড ইনপুট করলেই নিমিষেই মোট কোয়ালিফাইং সি-সার্ভিস রিপোর্ট তৈরি হয়ে যায়।',
            'features' => [
                'STCW 2010 Manila Amendments অনুমোদিত অ্যালগরিদম',
                'ফরেন গোয়িং (FG) ও কোস্টাল ট্রেডের স্বয়ংক্রিয় সেপারেশন',
                'পরবর্তী CoC পরীক্ষার জন্য প্রয়োজনীয় অবশিষ্ট দিনের রিয়েল-টাইম ট্র্যাকিং',
                'ডাউনলোড ও প্রিন্টযোগ্য সি-সার্ভিস স্টেটমেন্ট শীট',
                'সিডিসি পাতার সাথে মিলিয়ে একাধিক জাহাজের রেকর্ড মার্জ করার সুবিধা'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=seatime-calculator'
        ],

        // 11. Medical & Test Centre Directory
        'medical-directory' => [
            'slug' => 'medical-directory',
            'pillar' => 'health',
            'title' => 'মেডিকেল ও টেস্ট সেন্টার ডিরেক্টরি',
            'title_en' => 'Medical & Test Centre Directory',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-hospital',
            'color' => '#10b981',
            'summary' => 'ডিজি শিপিং ও বিভিন্ন দেশের মেরিটাইম প্রশাসন অনুমোদিত মেরিন ডাক্তার ও ডায়াগনস্টিক সেন্টারের বিশ্বস্ত তালিকা।',
            'description' => 'জাহাজে সাইন-অন করার পূর্বে আবশ্যকীয় ফিটনেস মেডিকেল টেস্ট, ভ্যাকসিনেশন (Yellow Fever, Polio) এবং অনুমোদিত সার্জনদের হালনাগাদ ফোন নম্বর, কনসালটেন্সি ফি ও লোকেশন ডিরেক্টরি।',
            'problem' => 'অননুমোদিত ডাক্তার বা সাধারণ ল্যাব থেকে টেস্ট করালে শিপিং কোম্পানি ও ডিজি শিপিং তা গ্রহণ করে না, যার ফলে টাকা ও সময় দুটোই অপচয় হয়।',
            'solution' => 'সরকারিভাবে স্বীকৃত ও আন্তর্জাতিক ফ্ল্যাগ স্টেট (Panama, Marshall Islands, Liberia) অনুমোদিত ক্লিনিশিয়ানদের সার্বক্ষণিক তথ্যসমৃদ্ধ ভেরিফায়েড গাইড।',
            'features' => [
                'DG Shipping অনুমোদিত মেরিন সার্জন ও প্যানেল ডাক্তার তালিকা',
                'ফ্ল্যাগ স্টেট ভিত্তিক মেডিক্যাল সেন্টার ফিল্টারিং (Panama, Liberia, Singapore)',
                'ইয়েলো ফিভার ভ্যাকসিনেশন সেন্টার ও সিডিউল ট্র্যাকিং',
                'মেডিক্যাল টেস্টের আনুমানিক সরকারি ফি ও রিকোয়ারমেন্ট চেকলিস্ট',
                'জরুরি প্রয়োজনে ডাক্তারের সাথে সরাসরি ফোন ও ম্যাপ ডিরেকশন'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=medical-directory'
        ],

        // 12. Tax, NRI Account & Financial Advisory
        'financial-advisory' => [
            'slug' => 'financial-advisory',
            'pillar' => 'tools',
            'title' => 'ট্যাক্স, এনআরবি একাউন্ট ও আর্থিক পরামর্শ',
            'title_en' => 'Tax, NRI Account & Financial Advisory',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-cash-coin',
            'color' => '#10b981',
            'summary' => 'নন-রেসিডেন্ট (NRI) স্ট্যাটাস, ১৮২ দিনের ট্যাক্স এক্সেমপশন, অফশোর ব্যাংকিং ও রেমিট্যান্সের সঠিক আইনি পরামর্শ।',
            'description' => 'মেরিনারদের কঠোর পরিশ্রমে অর্জিত বৈদেশিক মুদ্রার সঠিক ব্যবস্থাপনা, জাতীয় রাজস্ব বোর্ডের (NBR) নিয়ম অনুযায়ী ১৮২ দিনের প্রবাস শর্তে ট্যাক্স রিটার্ন ফাইল এবং এনআরবি ব্যাংক একাউন্টের সঠিক গাইডলাইন।',
            'problem' => 'ট্যাক্স সংক্রান্ত জটিল নিয়ম না জানার কারণে অনেক মেরিনার অন্যায় নোটিশের মুখে পড়েন কিংবা সঠিক ব্যাংকিং চ্যানেলে রেমিট্যান্স পাঠিয়েও সরকারি প্রণোদনা ও ইনভেস্টমেন্ট সুবিধা থেকে বঞ্চিত হন।',
            'solution' => 'মেরিন ফাইন্যান্স বিশেষজ্ঞ ও চার্টার্ড অ্যাকাউন্ট্যান্টদের যৌথ নির্দেশনায় ট্যাক্স অব্যাহতি ও রেমিট্যান্স ইনসেন্টিভের সঠিক আইনি তথ্য প্রদান।',
            'features' => [
                '১৮২ দিনের সি-সার্ভিসের ভিত্তিতে জিরো ট্যাক্স রিটার্ন ফাইলিং গাইডলাইন',
                'বিভিন্ন বাণিজ্যিক ব্যাংকের NRB ও অফশোর ব্যাংকিং ডেস্কে সহজ অ্যাকাউন্ট ওপেনিং',
                'রেমিট্যান্স ইনসেন্টিভ (সরকারি ২.৫% বোনাস) প্রাপ্তির প্রক্রিয়া',
                'নাবিকদের অবসরকালীন নিরাপদ বিনিয়োগ ও পেনশন ফান্ড প্ল্যানিং',
                'অভিজ্ঞ কর আইনজীবীদের দ্বারা কনসালটেশন সাপোর্ট'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=financial-advisory'
        ],

        // 13. Digital Logbook & Document Expiry Tracker
        'document-tracker' => [
            'slug' => 'document-tracker',
            'pillar' => 'tools',
            'title' => 'ডিজিটাল লগবুক ও ডকুমেন্ট ট্র্যাকার',
            'title_en' => 'Digital Logbook & Document Expiry Tracker',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-journal-check',
            'color' => '#00d2ff',
            'summary' => 'পাসপোর্ট, সিডিসি, ইয়েলো ফিভার ও সিওসি আপলোড রাখলে মেয়াদ শেষের ১২ মাস আগে ইমেইল ও হোয়াটসঅ্যাপে নোটিফিকেশন পাঠাবে।',
            'description' => 'নাবিকদের প্রয়োজনীয় বহুবিধ ডকুমেন্টের মেয়াদ ট্র্যাক রাখার জন্য ক্লাউড-এনক্রিপ্টেড ডিজিটাল সেফটি ভল্ট। সমুদ্রযাত্রার মাঝপথে কোনো সনদের মেয়াদ উত্তীর্ণ হওয়ার অনাকাঙ্ক্ষিত ঝুঁকি থেকে স্থায়ী মুক্তি।',
            'problem' => 'জাহাজে ব্যস্ততার মাঝে পাসপোর্ট বা কোনো কোর্সের সার্টিফিকেটের মেয়াদ শেষ হয়ে গেলে পরবর্তী সাইন-অফ বা পোর্টে ভিসা জটিলতায় বড় ধরনের জরিমানা ও জাহাজে আটকে থাকার ঝুঁকি তৈরি হয়।',
            'solution' => 'মেয়াদ উত্তীর্ণের ১২ মাস, ৬ মাস ও ৯০ দিন পূর্বে মাল্টি-চ্যানেল অটোমেটেড রিমাইন্ডার সিস্টেম, যা সময়মতো রি-ভ্যালিডেশনের সুযোগ করে দেয়।',
            'features' => [
                'পাসপোর্ট, সিডিসি, সিওসি, স্ট্যাচুটরি সার্টিফিকেট ও ভিসার সেন্ট্রাল ভল্ট',
                'মেয়াদ শেষ হওয়ার ১২ মাস, ৬ মাস ও ৩ মাস পূর্বে প্রি-ওয়ার্নিং নোটিফিকেশন',
                'ইমেইল ও হোয়াটসঅ্যাপে অটোমেটেড রিমাইন্ডার অ্যালার্ট',
                'ডকুমেন্ট রিনিউয়াল প্রসেস ও প্রয়োজনীয় ফর্মের সরাসরি ডাউনলোড লিংক',
                'হাই-সিকিউরিটি এনক্রিপ্টেড ক্লাউড স্টোরেজ'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=document-tracker'
        ],

        // 14. WhatsApp Single-Bot Notification
        'whatsapp-bot' => [
            'slug' => 'whatsapp-bot',
            'pillar' => 'tools',
            'title' => 'হোয়াটসঅ্যাপ সিঙ্গেল-বট নোটিফিকেশন',
            'title_en' => 'WhatsApp Single-Bot Notification',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-whatsapp',
            'color' => '#10b981',
            'summary' => 'ওয়েবসাইটের নতুন জব, জরুরি ব্লাড রিকোয়েস্ট বা ইভেন্টের খবর সরাসরি ব্যক্তিগত হোয়াটসঅ্যাপে পৌঁছে দেওয়ার অটোমেশন।',
            'description' => 'কোনো অতিরিক্ত অ্যাপ ইনস্টল না করেই প্রতিটি মেরিনারের হাতের মুঠোয় BMMC-এর সমস্ত জরুরি আপডেট পৌঁছে দিতে অফিশিয়াল হোয়াটসঅ্যাপ বিজনেস এপিআই বট ইন্টিগ্রেশন।',
            'problem' => 'সাগরে দুর্বল ইন্টারনেটের কারণে ভারী ওয়েবসাইট ব্রাউজ করা কঠিন হয়ে পড়ে, ফলে জরুরি রক্তের আবেদন বা জরুরি নোটিশগুলো নাবিকদের চোখ এড়িয়ে যায়।',
            'solution' => 'অত্যন্ত হালকা টেক্সট ডাটাযুক্ত হোয়াটসঅ্যাপ বটের মাধ্যমে মুহূর্তের মধ্যে ব্যক্তিগত চ্যাটে জরুরি নোটিফিকেশন ডেলিভারি।',
            'features' => [
                'জরুরি রক্তের আবেদন সরাসরি এলাকার ডোনারদের হোয়াটসঅ্যাপে পুশ',
                'মেরিন জব সার্কুলার ও ট্রেইনিং কোর্সের তাৎক্ষণিক মেসেজ আপডেট',
                'বটের মাধ্যমে নিজের সি-টাইম বা রক্তদানের স্ট্যাটাস যাচাই',
                'শতভাগ স্প্যাম-মুক্ত ও ব্যবহারকারী নিয়ন্ত্রিত সাবস্ক্রিপশন',
                'লো-ব্যান্ডউইথ সমুদ্র ইন্টারনেটেও দ্রুত রেসপন্স'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=whatsapp-bot'
        ],

        // 15. Seafarer Verification Badge
        'verification-badge' => [
            'slug' => 'verification-badge',
            'pillar' => 'tools',
            'title' => 'নাবিক ভেরিফিকেশন ব্যাজ',
            'title_en' => 'Seafarer Verification Badge (Verified Mariner)',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-patch-check-fill',
            'color' => '#00d2ff',
            'summary' => 'সিডিসি (CDC) ও সরকারি জেনুইন ডকুমেন্টস যাচাই করে প্রোফাইলে "Verified Mariner" সম্মানজনক ব্লু ব্যাজ প্রদান।',
            'description' => 'কমিউনিটির বিশ্বাসযোগ্যতা ও নিরাপত্তা নিশ্চিত করতে মেরিনার প্রোফাইল ভেরিফিকেশন সিস্টেম। এর মাধ্যমে ভুয়া পরিচয় বা অ-মেরিনারদের ছদ্মবেশ দূর করে প্রকৃত পেশাদার নাবিকদের স্বীকৃতি দেওয়া হবে।',
            'problem' => 'অনলাইনে বিভিন্ন ফেক আইডি নিজেদের নাবিক পরিচয় দিয়ে সাধারণ মানুষদের বিভ্রান্ত করে এবং অনুদান বা চাকরির নামে অনৈতিক সুবিধা নেয়।',
            'solution' => 'সিডিসি ও বিএমএমসি ভেরিফিকেশন টিমের কঠোর পরীক্ষার মাধ্যমে শতভাগ জেনুইন মেরিনারদের প্রোফাইলে একটি স্বাতন্ত্র্যসূচক সিল যুক্ত করা।',
            'features' => [
                'অফিশিয়াল সিডিসি ও এসআইডি যাচাইকরণ প্রক্রিয়া',
                'প্রোফাইলে ব্লু ভেরিফাইড মেরিনার ব্যাজ ডিসপ্লে',
                'অভ্যন্তরীণ ফোরাম ও জব পোর্টালে প্রায়োরিটি অ্যাক্সেস',
                'কমিউনিটি ট্রাস্ট লেভেল ও প্রফেশনাল অথেন্টিসিটি নিশ্চয়তা',
                'নকল ও জাল ডকুমেন্টের বিরুদ্ধে কঠোর জিরো টলারেন্স প্রটোকল'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=verification-badge'
        ],

        // 16. BMMC Member Digital ID Card
        'digital-id' => [
            'slug' => 'digital-id',
            'pillar' => 'tools',
            'title' => 'বিএমএমসি ডিজিটাল আইডি কার্ড',
            'title_en' => 'BMMC Member Digital ID Card',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-qr-code-scan',
            'color' => '#f59e0b',
            'summary' => 'ইউজার প্রোফাইল তৈরি হলে ডায়নামিক কিউআর কোড (Dynamic QR Code) সহ ডিজিটাল আইডেন্টিটি কার্ড তৈরি হবে।',
            'description' => 'BMMC সদস্যদের জন্য আন্তর্জাতিক মানের ডিজিটাল স্মার্ট পরিচয়পত্র। মেম্বার আইডি কার্ডের কিউআর কোড স্ক্যান করলেই তাৎক্ষণিকভাবে মেম্বারশিপ স্ট্যাটাস, রক্তের গ্রুপ ও জরুরি কন্ট্যাক্ট ভেরিফাই করা যাবে।',
            'problem' => 'বিপদে-আপদে বা হাসপাতালে পরিচয় ও রক্তের গ্রুপ নিশ্চিত করার জন্য কোনো স্বীকৃত ডিজিটাল মেরিনার পরিচয়পত্র পূর্বে ছিল না।',
            'solution' => 'মোবাইল ওয়ালেটে সংরক্ষণযোগ্য এবং যেকোনো প্রিন্টারে প্রিন্ট করার উপযোগী সিকিউর ডিজিটাল কিউআর কোড আইডি কার্ড।',
            'features' => [
                'ডায়নামিক কিউআর কোড স্ক্যানিং প্রযুক্তি',
                'রক্তের গ্রুপ, পদবী ও জরুরি পরিচিতির তাত্ক্ষণিক যাচাই',
                'স্মার্টফোনে সেভ বা অ্যাপল/গুগল ওয়ালেটে সংরক্ষণের সুবিধা',
                'পার্টনার ডায়াগনস্টিক সেন্টার ও হাসপাতালে মেম্বারশিপ ডিসকাউন্ট প্রাপ্তি',
                'উচ্চমানের প্রিন্ট-রেডি পিএনজি ও পিডিএফ কার্ড এক্সপোর্ট'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=digital-id'
        ],

        // 17. Transparent Donation & Expense Dashboard
        'financial-dashboard' => [
            'slug' => 'financial-dashboard',
            'pillar' => 'community',
            'title' => 'স্বচ্ছ অনুদান ও ব্যয় ড্যাশবোর্ড',
            'title_en' => 'Transparent Donation & Expense Dashboard',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-graph-up-arrow',
            'color' => '#10b981',
            'summary' => 'মাসে কত টাকা ডোনেশন এলো এবং কোথায় কীভাবে খরচ হলো (সার্ভার ফি, কল্যাণ তহবিল) তার শতভাগ উন্মুক্ত লাইভ হিসাব।',
            'description' => 'প্ল্যাটফর্মটি যেহেতু সম্পূর্ণ সেবামূলক এবং অনুদানে চলবে, তাই প্রতিটি টাকার আর্থিক স্বচ্ছতা নিশ্চিত করার জন্য তৈরি উন্মুক্ত পাবলিক লেজার ও ফাইন্যান্সিয়াল ট্র্যাকার।',
            'problem' => 'অধিকাংশ স্বেচ্ছাসেবী সংস্থায় অনুদানের টাকা কোথায় খরচ হয় তা নিয়ে অস্পষ্টতা থাকে, যার ফলে মানুষের বিশ্বাসভঙ্গ ঘটে।',
            'solution' => 'BMMC-এর প্রতিটি অনুদান ও প্রতিটি ব্যয়ের ভাউচার লাইভ ওয়েবসাইটে প্রকাশ করা হবে—যাতে যেকেউ যেকোনো সময় হিসাব নিরীক্ষা করতে পারে।',
            'features' => [
                'রিয়েল-টাইম লাইভ ইনকাম ও এক্সপেন্সেস লেজার',
                'মাসিক অডিট স্টেটমেন্ট ও ভাউচার ডাউনলোড সুবিধা',
                'সার্ভার খরচ, ওয়েলফেয়ার ফান্ড ও জরুরি অনুদানের খাতভিত্তিক চার্ট',
                'স্বতন্ত্র মেরিনার অডিট কমিটির প্রত্যয়নপত্র',
                'শতভাগ অলাভজনক নীতিমালার দৃঢ় প্রয়োগ'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=financial-dashboard'
        ],

        // 18. Privacy & Data Security Policy
        'privacy-policy' => [
            'slug' => 'privacy-policy',
            'pillar' => 'community',
            'title' => 'প্রাইভেসি ও ডেটা সিকিউরিটি পলিসি',
            'title_en' => 'Privacy & Military-Grade Data Security Policy',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-shield-lock-fill',
            'color' => '#8b5cf6',
            'summary' => 'ইউজারদের সিডিসি, পাসপোর্ট ও সিভির সংবেদনশীল তথ্য সুরক্ষিত রাখতে এন্ড-টু-এন্ড এনক্রিপশন সিস্টেম।',
            'description' => 'মেরিনারদের জাতীয় নিরাপত্তা ও ক্যারিয়ার সংশ্লিষ্ট সংবেদনশীল ডাটা সুরক্ষায় ব্যাংক-লেভেল এনক্রিপশন ও সাইবার সিকিউরিটি প্রোটোকল। অনুমতি ছাড়া কোনো থার্ড পার্টি বা ভুয়া এজেন্সি ডেটা স্ক্র্যাপ করতে পারবে না।',
            'problem' => 'অনলাইনে অনেক ভুয়া প্ল্যাটফর্ম নাবিকদের পাসপোর্ট ও সিডিসি সংগ্রহ করে পরে তা জালিয়াতি বা মানবপাচারের মতো অপরাধমূলক কাজে ব্যবহার করে।',
            'solution' => 'AES-256 ডাটাবেস এনক্রিপশন, বট-প্রটেকশন এবং জিরো-শেয়ারিং নীতিমালার মাধ্যমে নাবিকদের সকল ব্যক্তিগত নথি সম্পূর্ণ সুরক্ষিত রাখা।',
            'features' => [
                'AES-256 বিট এনক্রিপ্টেড ডাটাবেস আর্কিটেকচার',
                'অটোমেটেড অ্যান্টি-স্ক্র্যাপিং ও বট ফায়ারওয়াল সুরক্ষা',
                'নাবিকদের অনুমতি ব্যতীত তৃতীয় কোনো পক্ষের কাছে ডেটা হস্তান্তর নিষিদ্ধ',
                'নিজস্ব ডেটা যেকোনো সময় স্থায়ীভাবে মুছে ফেলার পূর্ণ অধিকার (GDPR standard)',
                'নিয়মিত সিকিউরিটি ভলনারেবিলিটি স্ক্যানিং ও সুরক্ষা নিরীক্ষা'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=privacy-policy'
        ],

        // 19. Shore-Based Career Guidance Cell
        'shore-career' => [
            'slug' => 'shore-career',
            'pillar' => 'career',
            'title' => 'শোর-বেসড ক্যারিয়ার গাইডেন্স সেল',
            'title_en' => 'Shore-Based Career Guidance Cell',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-building-fill',
            'color' => '#00d2ff',
            'summary' => 'দীর্ঘদিন সাগরে থাকার পর পাড়ে (শিপইয়ার্ড, সার্ভেয়ার, পোর্ট ম্যানেজমেন্ট) স্থায়ী হতে বিশেষ ক্যারিয়ার গাইডলাইন ও জব ফেয়ার।',
            'description' => 'সমুদ্র জীবন থেকে পাড়ের পেশায় মসৃণ স্থানান্তরের জন্য অভিজ্ঞ এক্স-মেরিনারদের সমন্বয়ে গঠিত স্পেশালাইজড ক্যারিয়ার উইং। পরিবারকে সময় দেওয়ার পাশাপাশি সম্মানের সাথে দেশে ক্যারিয়ার গড়ার নির্দেশনা।',
            'problem' => 'সাগরে দীর্ঘ বছর কাটানোর পর দেশে ফিরে কোন সেক্টরে চাকরি পাওয়া যাবে, কীভাবে সিভি সাজাতে হবে বা পোর্ট ম্যানেজমেন্টে কীভাবে যোগ দিতে হবে তা নিয়ে অধিকাংশ সিনিয়র নাবিকও বিভ্রান্ত থাকেন।',
            'solution' => 'মেরিন সার্ভেয়িং, শিপইয়ার্ড সুপারিনটেনডেন্ট, পোর্ট ট্রাফিক কন্ট্রোল, ড্রেজিং ও মেরিটাইম ইন্স্যুরেন্স খাতের প্রফেশনাল দিকনির্দেশনা ও সরাসরি চাকরির সুযোগ তৈরি করা।',
            'features' => [
                'শোর-বেসড সিভিলিয়ান মেরিটাইম জব সেক্টর ম্যাপিং',
                'মেরিন সার্ভেয়ার ও ক্লাসিফিকেশন সোসাইটি জব রিকোয়ারমেন্ট গাইড',
                'শিপইয়ার্ড ও পোর্ট ম্যানেজমেন্ট ট্রানজিশন রোডম্যাপ',
                'পাড়ে কর্মরত অভিজ্ঞ সিনিয়র মেরিনারদের সাথে ওয়ান-টু-ওয়ান মেন্টরিং',
                'বাৎসরিক মেরিন প্রফেশনালস শোর জব ফেয়ার ও নেটওয়ার্কিং মিট'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=shore-career'
        ],

        // 20. Verification & Anti-Fraud Alert
        'anti-fraud' => [
            'slug' => 'anti-fraud',
            'pillar' => 'emergency',
            'title' => 'জালিয়াতি ও স্ক্যাম প্রতিরোধ নোটিশ বোর্ড',
            'title_en' => 'Verification & Anti-Fraud Alert Board',
            'status' => 'coming_soon',
            'badge' => 'শীঘ্রই উন্মুক্ত হচ্ছে (Coming Soon)',
            'icon' => 'bi-exclamation-triangle-fill',
            'color' => '#ef233c',
            'summary' => 'ভুয়া শিপিং কোম্পানি, ফেক ম্যানিং এজেন্সি ও ভিসা স্ক্যাম প্রতিরোধে কমিউনিটি রিপোর্টভিত্তিক লাইভ ব্ল্যাকলিস্ট নোটিশ বোর্ড।',
            'description' => 'মেরিন সেক্টরে ভুয়া রিক্রুটার ও আন্তর্জাতিক ভিসা স্ক্যামারদের সক্রিয় জাল ভেঙে দিতে রিয়েল-টাইম কমিউনিটি ইনটেলিজেন্স ও সচেতনতা কেন্দ্র।',
            'problem' => 'প্রতিবছর শত শত তরুণ ভুয়া অফার লেটার ও ভুয়া সাইনিং-অন কন্ট্রাক্টের ফাঁদে পড়ে লাখ লাখ টাকা হারিয়ে নিঃস্ব হচ্ছেন। স্ক্যামাররা প্রতিনিয়ত নতুন নাম নিয়ে আসে।',
            'solution' => 'যেকোনো সন্দেহের ক্ষেত্রে এজেন্সি নাম বা অফার লেটার জমা দিয়ে সত্যতা যাচাই এবং প্রমাণসাপেক্ষে প্রতারকদের সর্বসমক্ষে উন্মোচন করা।',
            'features' => [
                'কমিউনিটি রিপোর্টিংভিত্তিক লাইভ স্ক্যামার ও ফেক এজেন্সি ব্ল্যাকলিস্ট',
                'অফার লেটার ও ভিসা সত্যতা যাচাইয়ে ফ্রি কমিউনিটি হেল্পডেস্ক',
                'নাবিকদের সচেতন করতে রেড-ফ্ল্যাগ (Red Flag) স্ক্যাম ইন্ডিকেটর গাইড',
                'প্রতারণার শিকার ভুক্তভোগীদের আইনি পদক্ষেপ গ্রহণে দিকনির্দেশনা',
                'DG Shipping ও আইন প্রয়োগকারী সংস্থাকে নিয়মিত প্রতারক চক্রের রিপোর্ট প্রদান'
            ],
            'button_text' => 'সেবার রোডম্যাপ দেখুন',
            'link' => 'service.php?slug=anti-fraud'
        ],

        // 21. Become a BMMC Volunteer Database
        'volunteer-network' => [
            'slug' => 'volunteer-network',
            'pillar' => 'community',
            'title' => 'বিএমএমসি ভলান্টিয়ার ডাটাবেজ',
            'title_en' => 'BMMC Volunteer Network & Database',
            'status' => 'coming_soon',
            'badge' => 'উন্মুক্ত রেজিষ্ট্রেশন (Open)',
            'icon' => 'bi-person-heart',
            'color' => '#00d2ff',
            'summary' => 'চট্টগ্রাম, ঢাকা, খুলনা বা সমুদ্রের বন্দরে সেচ্ছাসেবার সম্মিলিত নেটওয়ার্ক—রক্তদান ও অন্যান্য সেবায় মানবকল্যাণের ব্রত।',
            'description' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটির প্রাণ হলো এর নিঃস্বার্থ স্বেচ্ছাসেবকগণ। দেশের গুরুত্বপূর্ণ বন্দর ও বিভাগীয় শহরে সমন্বিত টিম গঠন করে মানবতার সেবায় নিবেদিত সেচ্ছাসেবী বহর।',
            'problem' => 'বিপদগ্রস্ত নাবিক বা সাধারণ মানুষের প্রয়োজনে বিভিন্ন এলাকায় তাৎক্ষণিকভাবে উপস্থিত হয়ে সহযোগিতা করার মতো সুসংগঠিত লোকাল টিমের অভাব।',
            'solution' => 'অবস্থান (চট্টগ্রাম, ঢাকা, খুলনা, পায়রা ইত্যাদি) অনুযায়ী ভলান্টিয়ার নেটওয়ার্ক গড়ে তোলা এবং রক্তদান প্রোগ্রামের সাথে একই ফর্মে সমন্বিত নিবন্ধন।',
            'features' => [
                'পোর্ট ও জেলাভিত্তিক আঞ্চলিক ভলান্টিয়ার স্কোয়াড',
                'রক্তদান সম্মতি (Agree to Donate Blood) এর স্বয়ংক্রিয় ইন্টিগ্রেশন',
                'জরুরি উদ্ধার, রক্তদান ও ক্যাডেট সহায়তা উইংয়ে কাজের সুযোগ',
                'স্বেচ্ছাসেবীদের জন্য বাৎসরিক সম্মাননা সনদপত্র ও স্বীকৃতি',
                '১০০% অরাজনৈতিক ও অলাভজনক মানবিক প্ল্যাটফর্ম'
            ],
            'button_text' => 'ভলান্টিয়ার ফরম পূরণ করুন',
            'link' => 'volunteer_register.php'
        ]
    ];
}

function get_service_by_slug(string $slug): ?array {
    $services = get_bmmc_services();
    return $services[$slug] ?? null;
}

function get_services_by_pillar(string $pillarId): array {
    $services = get_bmmc_services();
    return array_filter($services, function($s) use ($pillarId) {
        return ($s['pillar'] ?? '') === $pillarId;
    });
}
