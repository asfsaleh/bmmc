<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Mechanical Engineering Interactive Maintenance Mode Page
 */

// Load maintenance configuration if available
$configFile = __DIR__ . '/config/maintenance.json';
$mConfig = [
    'title' => 'সাইটের যান্ত্রিক রক্ষণাবেক্ষণ চলছে — BMMC',
    'headline' => 'সাইটের সিস্টেম আপগ্রেড ও যান্ত্রিক রক্ষণাবেক্ষণ চলছে',
    'message' => 'সম্মানিত মেরিনার ও ভিজিটরবৃন্দ, বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) পোর্টালটির ইঞ্জিন ও ডাটাবেস সিস্টেম আপগ্রেডের কাজ চলছে। উন্নত সেবা ও নির্ভরযোগ্য রক্তদান নেটওয়ার্ক নিশ্চিত করতে সাইটটি সাময়িকভাবে রক্ষণাবেক্ষণ মোডে রয়েছে।',
    'estimated_time' => 'খুব শীঘ্রই আমরা পুনরায় লাইভে আসছি',
    'contact_phone' => '+8801711000000',
    'contact_email' => 'admin@bmmc.org'
];

if (file_exists($configFile)) {
    $loaded = json_decode(file_get_contents($configFile), true);
    if (is_array($loaded)) {
        $mConfig = array_merge($mConfig, $loaded);
    }
}

// Ensure 503 Service Unavailable header
if (!headers_sent()) {
    http_response_code(503);
    header('Retry-After: 3600');
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mConfig['title']) ?></title>
    
    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Orbitron:wght@600;800&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-deep: #050e1a;
            --bg-navy: #0a192f;
            --steel-dark: #1e293b;
            --steel-light: #94a3b8;
            --brass-primary: #f59e0b;
            --brass-glow: rgba(245, 158, 11, 0.4);
            --copper: #ea580c;
            --cyan-glow: #00d2ff;
            --blood-red: #ef233c;
            --font-bengali: 'Hind Siliguri', sans-serif;
            --font-mono: 'Share Tech Mono', monospace;
            --font-tech: 'Orbitron', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
            -webkit-user-select: none;
        }

        body {
            background-color: var(--bg-deep);
            color: #f8fafc;
            font-family: var(--font-bengali);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            background-image: 
                radial-gradient(circle at 50% 35%, rgba(10, 42, 80, 0.7) 0%, transparent 65%),
                radial-gradient(circle at 10% 90%, rgba(239, 35, 60, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 10%, rgba(245, 158, 11, 0.1) 0%, transparent 40%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Blueprint Grid Overlay */
        .blueprint-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(0, 210, 255, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 210, 255, 0.035) 1px, transparent 1px);
            pointer-events: none;
            z-index: 1;
        }

        /* Canvas Particle Layer for Sparks & Shockwaves */
        #sparkCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 99;
        }

        /* Industrial Warning Top & Bottom Hazard Strips */
        .hazard-strip {
            height: 6px;
            width: 100%;
            background: repeating-linear-gradient(
                45deg,
                #000,
                #000 12px,
                var(--brass-primary) 12px,
                var(--brass-primary) 24px
            );
            box-shadow: 0 0 15px var(--brass-glow);
            z-index: 10;
        }

        .main-wrapper {
            position: relative;
            z-index: 5;
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px 20px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Header Badges */
        .status-badge-container {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(15, 30, 55, 0.85);
            border: 1px solid rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
            padding: 8px 20px;
            border-radius: 30px;
            margin-bottom: 24px;
            backdrop-filter: blur(8px);
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background-color: var(--brass-primary);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--brass-primary);
            animation: pulseGlow 1.5s infinite alternate;
        }

        @keyframes pulseGlow {
            0% { transform: scale(0.9); opacity: 0.6; box-shadow: 0 0 4px var(--brass-primary); }
            100% { transform: scale(1.3); opacity: 1; box-shadow: 0 0 15px var(--brass-primary); }
        }

        .status-text {
            font-family: var(--font-tech);
            font-size: 0.82rem;
            letter-spacing: 1.5px;
            color: #fef3c7;
            text-transform: uppercase;
        }

        /* Titles */
        .site-title {
            font-size: clamp(1.8rem, 4.5vw, 2.7rem);
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
            line-height: 1.25;
        }

        .site-subtitle {
            font-size: clamp(0.95rem, 2vw, 1.15rem);
            color: #94a3b8;
            max-width: 680px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        /* -------------------------------------------------------------
           MECHANICAL MACHINERY STAGE (Gears, Pistons, Pressure Gauges)
        ------------------------------------------------------------- */
        .machine-stage {
            position: relative;
            width: 100%;
            max-width: 720px;
            height: 380px;
            margin: 0 auto 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1000px;
            cursor: pointer;
        }

        /* Mechanical Rig Container that jolts / vibrates on click */
        .machine-rig {
            position: relative;
            width: 100%;
            height: 100%;
            transform-origin: center center;
            transition: transform 0.05s ease;
        }

        /* Machine tremor animation when clicked */
        .machine-rig.tremor {
            animation: mechanicalRecoil 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }

        @keyframes mechanicalRecoil {
            0% { transform: scale(1) translate(0, 0) rotate(0deg); }
            15% { transform: scale(0.975) translate(-4px, 3px) rotate(-1deg); filter: brightness(1.3); }
            30% { transform: scale(1.015) translate(4px, -3px) rotate(0.8deg); }
            50% { transform: scale(0.99) translate(-2px, 1px) rotate(-0.4deg); }
            70% { transform: scale(1.005) translate(2px, -1px) rotate(0.2deg); }
            100% { transform: scale(1) translate(0, 0) rotate(0deg); filter: brightness(1); }
        }

        /* Heavy Machinery Mounting Baseplate */
        .baseplate {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 88%;
            height: 78%;
            background: linear-gradient(135deg, rgba(15, 28, 48, 0.75) 0%, rgba(8, 16, 28, 0.9) 100%);
            border: 2px solid rgba(0, 210, 255, 0.25);
            border-radius: 24px;
            box-shadow: 
                inset 0 0 30px rgba(0, 0, 0, 0.8),
                0 15px 40px rgba(0, 0, 0, 0.6),
                0 0 25px rgba(0, 210, 255, 0.15);
            backdrop-filter: blur(12px);
            z-index: 1;
        }

        /* Rivets on Baseplate Corners */
        .rivet {
            position: absolute;
            width: 12px;
            height: 12px;
            background: radial-gradient(circle at 35% 35%, #94a3b8 0%, #334155 70%, #0f172a 100%);
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
        }
        .rivet.tl { top: 14px; left: 14px; }
        .rivet.tr { top: 14px; right: 14px; }
        .rivet.bl { bottom: 14px; left: 14px; }
        .rivet.br { bottom: 14px; right: 14px; }

        /* SVG Gear Train Elements */
        .gear-svg {
            position: absolute;
            transform-origin: center center;
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.65));
            transition: filter 0.2s ease;
        }

        .gear-svg:hover {
            filter: drop-shadow(0 0 18px rgba(245, 158, 11, 0.8)) drop-shadow(0 8px 16px rgba(0, 0, 0, 0.8));
        }

        /* 1. Master Drive Gear (Center - 24 Teeth) */
        .gear-master {
            width: 210px;
            height: 210px;
            top: 50%;
            left: 36%;
            margin-top: -105px;
            margin-left: -105px;
            z-index: 4;
            animation: rotateClockwise 16s linear infinite;
        }

        /* 2. Interlocking Planetary Gear (Top Right - 16 Teeth) */
        .gear-planetary {
            width: 146px;
            height: 146px;
            top: 50%;
            left: 36%;
            margin-top: -168px;
            margin-left: 54px;
            z-index: 3;
            animation: rotateCounterClockwise 10.666s linear infinite;
        }

        /* 3. Interlocking Pinion Gear (Bottom Right - 12 Teeth) */
        .gear-pinion {
            width: 114px;
            height: 114px;
            top: 50%;
            left: 36%;
            margin-top: 48px;
            margin-left: 62px;
            z-index: 3;
            animation: rotateClockwise 8s linear infinite;
        }

        /* 4. Bevel Transmission Gear (Far Left - 18 Teeth) */
        .gear-bevel {
            width: 138px;
            height: 138px;
            top: 50%;
            left: 36%;
            margin-top: -69px;
            margin-left: -198px;
            z-index: 2;
            animation: rotateCounterClockwise 12s linear infinite;
        }

        /* Gear Rotations */
        @keyframes rotateClockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotateCounterClockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

        /* Friction Hotspots where sparks fly */
        .friction-hotspot {
            position: absolute;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: transparent;
            z-index: 10;
            pointer-events: auto;
            cursor: pointer;
        }

        .friction-hotspot::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 6px;
            height: 6px;
            margin: -3px;
            background: rgba(245, 158, 11, 0.4);
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.6);
            animation: pulseGlow 1s infinite alternate;
        }

        /* Contact Point 1: Master & Planetary */
        .hotspot-1 {
            top: 36%;
            left: 45%;
        }

        /* Contact Point 2: Master & Pinion */
        .hotspot-2 {
            top: 67%;
            left: 44%;
        }

        /* Contact Point 3: Master & Bevel */
        .hotspot-3 {
            top: 50%;
            left: 20%;
        }

        /* Marine Steam Engine Piston Assembly (Right Side) */
        .piston-assembly {
            position: absolute;
            right: 8%;
            top: 50%;
            transform: translateY(-50%);
            width: 130px;
            height: 80px;
            z-index: 4;
            display: flex;
            align-items: center;
        }

        .piston-cylinder {
            width: 70px;
            height: 54px;
            background: linear-gradient(to right, #1e293b, #334155, #0f172a);
            border: 2px solid #475569;
            border-radius: 6px;
            box-shadow: inset 0 0 10px #000;
            position: relative;
        }

        .piston-cylinder::after {
            content: 'STEAM';
            position: absolute;
            top: 4px;
            left: 6px;
            font-family: var(--font-tech);
            font-size: 8px;
            color: #38bdf8;
            letter-spacing: 1px;
        }

        .piston-rod {
            width: 60px;
            height: 14px;
            background: linear-gradient(to bottom, #cbd5e1, #64748b, #cbd5e1);
            border: 1px solid #475569;
            border-radius: 4px;
            animation: pistonStroke 2s ease-in-out infinite alternate;
            box-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        @keyframes pistonStroke {
            0% { transform: translateX(0px); }
            100% { transform: translateX(-35px); }
        }

        /* Industrial Pressure Gauge */
        .marine-gauge {
            position: absolute;
            bottom: 18px;
            left: 22px;
            width: 72px;
            height: 72px;
            background: radial-gradient(circle, #0f172a 60%, #1e293b 100%);
            border: 3px solid #b45309;
            border-radius: 50%;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.3), inset 0 0 8px #000;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gauge-dial {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .gauge-needle {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 28px;
            height: 2px;
            background: #ef233c;
            transform-origin: 0% 50%;
            border-radius: 2px;
            animation: gaugeOscillate 3s ease-in-out infinite alternate;
            box-shadow: 0 0 4px #ef233c;
        }

        @keyframes gaugeOscillate {
            0% { transform: rotate(-50deg); }
            50% { transform: rotate(15deg); }
            100% { transform: rotate(-10deg); }
        }

        .gauge-label {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            font-family: var(--font-tech);
            font-size: 7px;
            color: #f59e0b;
            letter-spacing: 0.5px;
        }

        /* Interactive Action Hint */
        .interactive-hint {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #38bdf8;
            background: rgba(0, 210, 255, 0.08);
            border: 1px dashed rgba(0, 210, 255, 0.3);
            border-radius: 30px;
            padding: 8px 20px;
            margin-bottom: 30px;
            animation: pulseHint 2s infinite ease-in-out;
        }

        @keyframes pulseHint {
            0%, 100% { opacity: 0.8; transform: translateY(0); }
            50% { opacity: 1; transform: translateY(-2px); }
        }

        /* Progress Card & Emergency Info */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
            margin-bottom: 30px;
        }

        .info-card {
            background: rgba(13, 38, 77, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 22px;
            text-align: left;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(12px);
            transition: border-color 0.3s ease;
        }

        .info-card:hover {
            border-color: rgba(0, 210, 255, 0.4);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .info-card-icon {
            width: 38px;
            height: 38px;
            background: rgba(0, 210, 255, 0.15);
            border: 1px solid rgba(0, 210, 255, 0.4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #00d2ff;
        }

        .info-card-icon.red {
            background: rgba(239, 35, 60, 0.15);
            border-color: rgba(239, 35, 60, 0.4);
            color: #ef233c;
        }

        .info-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .info-card-body {
            color: #cbd5e1;
            font-size: 0.92rem;
            line-height: 1.6;
        }

        /* Emergency Action Buttons */
        .btn-emergency {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #ef233c 0%, #b91c1c 100%);
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(239, 35, 60, 0.4);
            transition: all 0.25s ease;
            margin-top: 12px;
            width: 100%;
        }

        .btn-emergency:hover {
            background: linear-gradient(135deg, #ff3b53 0%, #dc2626 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 35, 60, 0.6);
        }

        /* Machine Progress Bar */
        .machine-progress-track {
            width: 100%;
            height: 12px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(0, 210, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
            margin: 14px 0 8px;
            position: relative;
        }

        .machine-progress-bar {
            height: 100%;
            width: 78%;
            background: repeating-linear-gradient(
                -45deg,
                #0284c7,
                #0284c7 10px,
                #00d2ff 10px,
                #00d2ff 20px
            );
            background-size: 40px 40px;
            animation: progressStripe 1.5s linear infinite;
            box-shadow: 0 0 10px rgba(0, 210, 255, 0.5);
            border-radius: 10px;
        }

        @keyframes progressStripe {
            0% { background-position: 0 0; }
            100% { background-position: 40px 0; }
        }

        /* Footer */
        .footer-note {
            font-size: 0.82rem;
            color: #64748b;
            text-align: center;
            margin-top: 10px;
        }

        .admin-link {
            color: #475569;
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.78rem;
            transition: color 0.2s ease;
        }
        .admin-link:hover {
            color: #00d2ff;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .machine-stage {
                height: 280px;
                transform: scale(0.85);
            }
            .piston-assembly {
                display: none;
            }
            .marine-gauge {
                width: 58px;
                height: 58px;
                bottom: 8px;
                left: 10px;
            }
        }

        @media (max-width: 480px) {
            .machine-stage {
                height: 240px;
                transform: scale(0.72);
            }
            .main-wrapper {
                padding: 16px 14px 30px;
            }
            .site-title {
                font-size: 1.55rem;
            }
        }
    </style>
</head>
<body>

    <!-- Top Industrial Hazard Strip -->
    <div class="hazard-strip"></div>

    <!-- Blueprint Grid Background -->
    <div class="blueprint-grid"></div>

    <!-- Interactive Canvas Layer for Sparks & Click Shockwaves -->
    <canvas id="sparkCanvas"></canvas>

    <div class="main-wrapper">
        <!-- Status Indicator Pill -->
        <div class="status-badge-container">
            <span class="pulse-dot"></span>
            <span class="status-text">MAINTENANCE IN PROGRESS • BMMC SYSTEM</span>
        </div>

        <!-- Main Headline -->
        <h1 class="site-title"><?= htmlspecialchars($mConfig['headline']) ?></h1>
        <p class="site-subtitle"><?= htmlspecialchars($mConfig['message']) ?></p>

        <!-- Interactive Machinery Stage -->
        <div class="machine-stage" id="machineStage" title="মেশিনে ক্লিক করলে কেঁপে উঠবে এবং ঘষা লাগলে স্পার্ক হবে!">
            <div class="machine-rig" id="machineRig">
                
                <!-- Baseplate Mount -->
                <div class="baseplate">
                    <span class="rivet tl"></span>
                    <span class="rivet tr"></span>
                    <span class="rivet bl"></span>
                    <span class="rivet br"></span>
                </div>

                <!-- 1. Master Drive Gear (24 Teeth - Brass & Anchor Hub) -->
                <svg class="gear-svg gear-master" viewBox="0 0 200 200">
                    <defs>
                        <radialGradient id="brassGrad" cx="35%" cy="35%" r="65%">
                            <stop offset="0%" stop-color="#fef08a"/>
                            <stop offset="35%" stop-color="#f59e0b"/>
                            <stop offset="75%" stop-color="#b45309"/>
                            <stop offset="100%" stop-color="#78350f"/>
                        </radialGradient>
                        <radialGradient id="hubGrad" cx="40%" cy="40%" r="60%">
                            <stop offset="0%" stop-color="#38bdf8"/>
                            <stop offset="60%" stop-color="#0284c7"/>
                            <stop offset="100%" stop-color="#0369a1"/>
                        </radialGradient>
                    </defs>
                    <!-- Gear Rim & Teeth (24 Teeth) -->
                    <path fill="url(#brassGrad)" stroke="#78350f" stroke-width="2" d="
                        M 100 10 
                        L 104 10 L 105 24 L 111 25 L 119 13 L 123 15 L 122 29 L 127 31 L 137 21 L 141 24 L 138 38 L 143 41 L 154 34 L 157 38 L 150 51 L 154 55 L 167 50 L 169 55 L 160 67 L 163 71 L 176 70 L 177 75 L 165 85 L 167 90 L 180 91 L 180 96 L 166 104 L 166 109 L 179 113 L 178 118 L 163 123 L 162 128 L 174 135 L 172 140 L 156 142 L 154 147 L 164 157 L 161 161 L 146 159 L 143 163 L 150 176 L 145 179 L 132 173 L 129 177 L 132 191 L 127 193 L 117 184 L 113 187 L 112 201 L 107 202 L 100 190 
                        L 93 202 L 88 201 L 87 187 L 83 184 L 73 193 L 68 191 L 71 177 L 68 173 L 55 179 L 50 176 L 57 163 L 54 159 L 39 161 L 36 157 L 46 147 L 44 142 L 28 140 L 26 135 L 38 128 L 37 123 L 22 118 L 21 113 L 34 109 L 34 104 L 20 96 L 20 91 L 33 90 L 35 85 L 23 75 L 24 70 L 37 71 L 40 67 L 31 55 L 33 50 L 46 55 L 50 51 L 43 38 L 46 34 L 57 41 L 62 38 L 59 24 L 63 21 L 73 31 L 78 29 L 77 15 L 81 13 L 89 25 L 95 24 Z
                    "/>
                    <!-- Inner Cutouts -->
                    <circle cx="100" cy="100" r="62" fill="#0f172a" stroke="#b45309" stroke-width="3"/>
                    <circle cx="100" cy="100" r="48" fill="url(#brassGrad)" stroke="#78350f" stroke-width="2"/>
                    <!-- Central Hub with BMMC Nautical Anchor -->
                    <circle cx="100" cy="100" r="32" fill="url(#hubGrad)" stroke="#f8fafc" stroke-width="2"/>
                    <!-- Stylized Anchor Emblem in Hub -->
                    <path d="M 100 80 L 100 115 M 92 88 L 108 88 M 86 106 C 88 118, 112 118, 114 106" stroke="#ffffff" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <circle cx="100" cy="78" r="4" fill="none" stroke="#ffffff" stroke-width="2"/>
                </svg>

                <!-- 2. Interlocking Planetary Gear (16 Teeth - Stainless Steel) -->
                <svg class="gear-svg gear-planetary" viewBox="0 0 150 150">
                    <defs>
                        <radialGradient id="steelGrad" cx="30%" cy="30%" r="70%">
                            <stop offset="0%" stop-color="#ffffff"/>
                            <stop offset="30%" stop-color="#cbd5e1"/>
                            <stop offset="70%" stop-color="#64748b"/>
                            <stop offset="100%" stop-color="#1e293b"/>
                        </radialGradient>
                    </defs>
                    <path fill="url(#steelGrad)" stroke="#334155" stroke-width="2" d="
                        M 75 8 L 78 8 L 79 19 L 85 20 L 92 11 L 96 13 L 95 24 L 100 27 L 109 20 L 112 24 L 109 34 L 114 38 L 123 34 L 125 39 L 119 48 L 122 53 L 132 52 L 133 57 L 124 65 L 125 70 L 135 73 L 134 78 L 123 83 L 122 88 L 130 96 L 128 100 L 117 101 L 114 106 L 120 115 L 117 118 L 107 115 L 103 119 L 106 129 L 101 131 L 93 124 L 88 127 L 88 138 L 83 139 L 75 130 L 67 139 L 62 138 L 62 127 L 57 124 L 49 131 L 44 129 L 47 119 L 43 115 L 33 118 L 30 115 L 36 106 L 33 101 L 22 100 L 20 96 L 28 88 L 27 83 L 16 78 L 15 73 L 25 70 L 26 65 L 17 57 L 18 52 L 28 53 L 31 48 L 25 39 L 27 34 L 36 38 L 41 34 L 38 24 L 41 20 L 50 27 L 55 24 L 54 13 L 58 11 L 65 20 L 71 19 Z
                    "/>
                    <circle cx="75" cy="75" r="44" fill="#0f172a" stroke="#475569" stroke-width="2"/>
                    <circle cx="75" cy="75" r="26" fill="url(#steelGrad)" stroke="#334155" stroke-width="2"/>
                    <!-- Weight reduction circular cutouts -->
                    <circle cx="75" cy="46" r="6" fill="#0f172a"/>
                    <circle cx="75" cy="104" r="6" fill="#0f172a"/>
                    <circle cx="46" cy="75" r="6" fill="#0f172a"/>
                    <circle cx="104" cy="75" r="6" fill="#0f172a"/>
                </svg>

                <!-- 3. Interlocking Pinion Gear (12 Teeth - Maritime Copper) -->
                <svg class="gear-svg gear-pinion" viewBox="0 0 120 120">
                    <defs>
                        <radialGradient id="copperGrad" cx="30%" cy="30%" r="70%">
                            <stop offset="0%" stop-color="#fed7aa"/>
                            <stop offset="35%" stop-color="#f97316"/>
                            <stop offset="75%" stop-color="#c2410c"/>
                            <stop offset="100%" stop-color="#7c2d12"/>
                        </radialGradient>
                    </defs>
                    <path fill="url(#copperGrad)" stroke="#7c2d12" stroke-width="2" d="
                        M 60 8 L 63 8 L 64 18 L 70 20 L 78 12 L 82 15 L 79 25 L 85 28 L 95 24 L 98 28 L 92 37 L 96 42 L 106 42 L 107 47 L 98 54 L 99 60 L 108 65 L 107 70 L 96 72 L 94 78 L 101 87 L 97 91 L 87 87 L 82 91 L 84 102 L 79 104 L 72 96 L 66 98 L 65 109 L 60 109 L 55 109 L 54 98 L 48 96 L 41 104 L 36 102 L 38 91 L 33 87 L 23 91 L 19 87 L 26 78 L 24 72 L 13 70 L 12 65 L 21 60 L 22 54 L 13 47 L 14 42 L 24 42 L 28 37 L 22 28 L 25 24 L 35 28 L 41 25 L 38 15 L 42 12 L 50 20 L 56 18 Z
                    "/>
                    <circle cx="60" cy="60" r="32" fill="#0f172a" stroke="#7c2d12" stroke-width="2"/>
                    <circle cx="60" cy="60" r="16" fill="url(#copperGrad)"/>
                </svg>

                <!-- 4. Bevel Transmission Gear (Left - 18 Teeth Gunmetal) -->
                <svg class="gear-svg gear-bevel" viewBox="0 0 140 140">
                    <defs>
                        <radialGradient id="gunmetalGrad" cx="35%" cy="35%" r="65%">
                            <stop offset="0%" stop-color="#94a3b8"/>
                            <stop offset="40%" stop-color="#475569"/>
                            <stop offset="80%" stop-color="#1e293b"/>
                            <stop offset="100%" stop-color="#0f172a"/>
                        </radialGradient>
                    </defs>
                    <path fill="url(#gunmetalGrad)" stroke="#1e293b" stroke-width="2" d="
                        M 70 8 L 73 8 L 74 19 L 80 20 L 87 12 L 91 14 L 89 25 L 95 28 L 104 22 L 107 26 L 103 36 L 108 40 L 118 38 L 120 43 L 113 52 L 117 57 L 127 58 L 128 63 L 118 70 L 119 75 L 128 80 L 127 85 L 116 88 L 115 93 L 122 102 L 119 106 L 109 104 L 105 109 L 109 119 L 104 122 L 96 115 L 91 118 L 91 129 L 86 130 L 78 122 L 72 123 L 70 134 L 65 134 L 63 123 L 57 122 L 49 130 L 44 129 L 44 118 L 39 115 L 31 122 L 26 119 L 30 109 L 26 104 L 16 106 L 13 102 L 20 93 L 19 88 L 8 85 L 7 80 L 16 75 L 17 70 L 7 63 L 8 58 L 18 57 L 22 52 L 15 43 L 17 38 L 27 40 L 32 36 L 28 26 L 31 22 L 40 28 L 46 25 L 44 14 L 48 12 L 55 20 L 61 19 Z
                    "/>
                    <circle cx="70" cy="70" r="38" fill="#0f172a" stroke="#475569" stroke-width="2"/>
                    <circle cx="70" cy="70" r="22" fill="url(#gunmetalGrad)"/>
                </svg>

                <!-- Marine Steam Engine Piston Assembly (Right Side) -->
                <div class="piston-assembly">
                    <div class="piston-cylinder"></div>
                    <div class="piston-rod"></div>
                </div>

                <!-- Industrial Pressure Dial Gauge (Bottom Left) -->
                <div class="marine-gauge">
                    <div class="gauge-dial">
                        <div class="gauge-needle"></div>
                        <span class="gauge-label">PSI 65</span>
                    </div>
                </div>

                <!-- Contact Point Hotspots (Hover to ignite sparks!) -->
                <div class="friction-hotspot hotspot-1" id="hotspot1" title="গিয়ারের ঘর্ষণ পয়েন্ট - মাউস আনলে স্পার্ক হবে!"></div>
                <div class="friction-hotspot hotspot-2" id="hotspot2" title="গিয়ারের ঘর্ষণ পয়েন্ট - মাউস আনলে স্পার্ক হবে!"></div>
                <div class="friction-hotspot hotspot-3" id="hotspot3" title="গিয়ারের ঘর্ষণ পয়েন্ট - মাউস আনলে স্পার্ক হবে!"></div>

            </div>
        </div>

        <!-- Interactive Action Hint -->
        <div class="interactive-hint">
            <i class="bi bi-hand-index-thumb-fill fs-5"></i>
            <span>মাউস দিয়ে গিয়ারে নিলে <strong>স্পার্ক (Spark)</strong> জ্বলবে এবং যেকোনো জায়গায় <strong>ক্লিক বা টাচ</strong> করলে মেশিন কেঁপে উঠবে!</span>
        </div>

        <!-- System Progress & Emergency Helplines -->
        <div class="info-grid">
            <!-- Progress Card -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <h4 class="info-card-title">ইঞ্জিন আপগ্রেড অগ্রগতি</h4>
                        <small class="text-secondary"><?= htmlspecialchars($mConfig['estimated_time']) ?></small>
                    </div>
                </div>
                <div class="info-card-body">
                    আমাদের টেকনিক্যাল ও মেরিন ইঞ্জিনিয়ার টিম ডাটাবেস পারফরম্যান্স বৃদ্ধি এবং ডোনার ম্যাচিং অ্যালগরিদম সুরক্ষিত করার কাজ করছে।
                    <div class="machine-progress-track">
                        <div class="machine-progress-bar"></div>
                    </div>
                    <div class="d-flex justify-content-between text-secondary" style="font-size: 0.78rem;">
                        <span>সিস্টেম ব্যাকআপ: সম্পূর্ণ</span>
                        <span class="text-info fw-bold">৮৫% সম্পন্ন</span>
                    </div>
                </div>
            </div>

            <!-- Emergency Blood Contact Card -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon red">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                    <div>
                        <h4 class="info-card-title">জরুরি রক্ত প্রয়োজন?</h4>
                        <small class="text-danger fw-semibold">জরুরি হটলাইন সেবা চালু আছে</small>
                    </div>
                </div>
                <div class="info-card-body">
                    মেইনটেন্যান্স চলাকালীন রক্তের জরুরি প্রয়োজন হলে আমাদের জরুরি সমন্বয়ক টিমের সাথে সরাসরি যোগাযোগ করুন:
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $mConfig['contact_phone']) ?>?text=<?= urlencode('জরুরি রক্তের প্রয়োজন (BMMC Emergency)') ?>" target="_blank" class="btn-emergency">
                        <i class="bi bi-whatsapp fs-5"></i>
                        <span>হোয়াটসঅ্যাপে যোগাযোগ: <?= htmlspecialchars($mConfig['contact_phone']) ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer & Admin Access -->
        <div class="footer-note">
            <p class="mb-1">&copy; <?= date('Y') ?> বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC). সর্বস্বত্ব সংরক্ষিত।</p>
            <p class="mb-0">
                <a href="<?= (defined('BASE_URL') ? BASE_URL : '') ?>/m" class="admin-link" title="অ্যাডমিন মেইনটেন্যান্স কন্ট্রোল">
                    <i class="bi bi-shield-lock me-1"></i>System Panel (/m)
                </a>
            </p>
        </div>
    </div>

    <!-- Bottom Industrial Hazard Strip -->
    <div class="hazard-strip"></div>

    <!-- =========================================================
         PHYSICS SPARK ENGINE & MECHANICAL VIBRATION INTERACTION
    ========================================================== -->
    <script>
    (function () {
        const canvas = document.getElementById('sparkCanvas');
        const ctx = canvas.getContext('2d');
        const machineRig = document.getElementById('machineRig');
        const machineStage = document.getElementById('machineStage');
        
        let width = window.innerWidth;
        let height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;

        window.addEventListener('resize', () => {
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = width;
            canvas.height = height;
        });

        // Sparks Collection & Shockwave Rings
        const particles = [];
        const shockwaves = [];

        // Audio Context for mechanical clink feedback
        let audioCtx = null;
        function playMechanicalClink() {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const now = audioCtx.currentTime;
                // Clink Oscillator
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(680, now);
                osc.frequency.exponentialRampToValueAtTime(140, now + 0.12);
                gain.gain.setValueAtTime(0.25, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.12);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(now);
                osc.stop(now + 0.13);
            } catch (e) {}
        }

        // Particle Class (Molten Metal Welding Sparks)
        class Spark {
            constructor(x, y, vx, vy, isBurst = false) {
                this.x = x;
                this.y = y;
                this.vx = vx;
                this.vy = vy;
                this.size = Math.random() * (isBurst ? 3.5 : 2.5) + 1;
                this.life = 0;
                this.maxLife = Math.random() * 35 + 25; // frames
                this.decay = Math.random() * 0.02 + 0.018;
                this.alpha = 1;
                this.color = Math.random() > 0.4 ? '#f59e0b' : (Math.random() > 0.5 ? '#ffffff' : '#ea580c');
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.vy += 0.18; // gravity
                this.vx *= 0.98; // air drag
                this.life++;
                this.alpha -= this.decay;
            }

            draw() {
                if (this.alpha <= 0) return;
                ctx.save();
                ctx.globalAlpha = Math.max(0, this.alpha);
                ctx.fillStyle = this.color;
                ctx.shadowColor = this.color;
                ctx.shadowBlur = 8;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }
        }

        // Shockwave Ring on Click
        class Shockwave {
            constructor(x, y) {
                this.x = x;
                this.y = y;
                this.radius = 10;
                this.maxRadius = Math.random() * 50 + 60;
                this.alpha = 0.9;
            }
            update() {
                this.radius += 4;
                this.alpha -= 0.04;
            }
            draw() {
                if (this.alpha <= 0) return;
                ctx.save();
                ctx.globalAlpha = Math.max(0, this.alpha);
                ctx.strokeStyle = '#00d2ff';
                ctx.lineWidth = 2.5;
                ctx.shadowColor = '#00d2ff';
                ctx.shadowBlur = 12;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.stroke();
                ctx.restore();
            }
        }

        // Emit Continuous Hover Sparks from coordinate
        function emitSparks(x, y, count = 5, direction = null) {
            for (let i = 0; i < count; i++) {
                const angle = direction !== null 
                    ? direction + (Math.random() - 0.5) * 1.5 
                    : Math.random() * Math.PI * 2;
                const speed = Math.random() * 5 + 2;
                const vx = Math.cos(angle) * speed;
                const vy = Math.sin(angle) * speed;
                particles.push(new Spark(x, y, vx, vy));
            }
        }

        // Emit Explosive Click Burst
        function emitImpactBurst(x, y) {
            shockwaves.push(new Shockwave(x, y));
            for (let i = 0; i < 35; i++) {
                const angle = Math.random() * Math.PI * 2;
                const speed = Math.random() * 8 + 3;
                const vx = Math.cos(angle) * speed;
                const vy = Math.sin(angle) * speed - 1.5;
                particles.push(new Spark(x, y, vx, vy, true));
            }
        }

        // Animation Render Loop
        function animate() {
            ctx.clearRect(0, 0, width, height);

            // Update & Draw Shockwaves
            for (let i = shockwaves.length - 1; i >= 0; i--) {
                const sw = shockwaves[i];
                sw.update();
                sw.draw();
                if (sw.alpha <= 0) {
                    shockwaves.splice(i, 1);
                }
            }

            // Update & Draw Sparks
            for (let i = particles.length - 1; i >= 0; i--) {
                const p = particles[i];
                p.update();
                p.draw();
                if (p.alpha <= 0) {
                    particles.splice(i, 1);
                }
            }

            requestAnimationFrame(animate);
        }
        animate();

        // 1. Friction Hotspots Hover Listener
        const hotspots = [
            document.getElementById('hotspot1'),
            document.getElementById('hotspot2'),
            document.getElementById('hotspot3')
        ];

        hotspots.forEach((hs, idx) => {
            if (!hs) return;
            hs.addEventListener('mousemove', (e) => {
                const rect = hs.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const cy = rect.top + rect.height / 2;
                emitSparks(cx, cy, 4, idx === 0 ? -Math.PI / 4 : (idx === 1 ? Math.PI / 4 : -Math.PI / 2));
            });

            hs.addEventListener('mouseenter', () => {
                const rect = hs.getBoundingClientRect();
                emitSparks(rect.left + rect.width / 2, rect.top + rect.height / 2, 10);
            });
        });

        // 2. Mouse Move across Machinery emits occasional friction sparks
        let lastSparkTime = 0;
        machineStage.addEventListener('mousemove', (e) => {
            const now = performance.now();
            if (now - lastSparkTime > 75) {
                lastSparkTime = now;
                emitSparks(e.clientX, e.clientY, 3);
            }
        });

        // 3. Click Anywhere on Screen: Machine Tremors + Impact Sparks!
        function handleInteractionClick(clientX, clientY) {
            // Shake the machine
            machineRig.classList.remove('tremor');
            void machineRig.offsetWidth; // trigger reflow
            machineRig.classList.add('tremor');

            // Spawn sparks and shockwave at click coordinate
            emitImpactBurst(clientX, clientY);

            // Play sound
            playMechanicalClink();
        }

        window.addEventListener('click', (e) => {
            handleInteractionClick(e.clientX, e.clientY);
        });

        // 4. Touch Support for Mobile Devices
        window.addEventListener('touchstart', (e) => {
            if (e.touches && e.touches[0]) {
                const touch = e.touches[0];
                handleInteractionClick(touch.clientX, touch.clientY);
            }
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (e.touches && e.touches[0]) {
                const touch = e.touches[0];
                const now = performance.now();
                if (now - lastSparkTime > 90) {
                    lastSparkTime = now;
                    emitSparks(touch.clientX, touch.clientY, 3);
                }
            }
        }, { passive: true });

    })();
    </script>
</body>
</html>
