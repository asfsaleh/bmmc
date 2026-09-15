<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Mechanical Engineering Interactive Maintenance Mode Page
 * Fullscreen Background Machinery Edition
 */

// Load maintenance configuration if available
$configFile = __DIR__ . '/config/maintenance.json';
$mConfig = [
    'title' => 'System Under Maintenance — BMMC',
    'headline' => 'SYSTEM UNDER MAINTENANCE',
    'message' => 'The Bangladesh Merchant Mariners Community (BMMC) portal is currently undergoing scheduled engineering maintenance and core performance upgrades. We will be back online shortly.',
    'estimated_time' => 'Returning Online Shortly',
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mConfig['title']) ?></title>
    
    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@600;700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-deep: #030812;
            --bg-navy: #071326;
            --steel-dark: #1e293b;
            --steel-light: #94a3b8;
            --brass-primary: #f59e0b;
            --brass-glow: rgba(245, 158, 11, 0.45);
            --copper: #ea580c;
            --cyan-glow: #00d2ff;
            --blood-red: #ef233c;
            --font-main: 'Inter', system-ui, -apple-system, sans-serif;
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

        html, body {
            width: 100%;
            min-height: 100%;
            background-color: var(--bg-deep);
            color: #f8fafc;
            font-family: var(--font-main);
            overflow-x: hidden;
            position: relative;
        }

        /* Top & Bottom Industrial Hazard Warning Strips */
        .hazard-strip {
            position: fixed;
            left: 0;
            width: 100%;
            height: 6px;
            background: repeating-linear-gradient(
                -45deg,
                #000,
                #000 12px,
                var(--brass-primary) 12px,
                var(--brass-primary) 24px
            );
            box-shadow: 0 0 15px var(--brass-glow);
            z-index: 50;
        }
        .hazard-strip.top { top: 0; }
        .hazard-strip.bottom { bottom: 0; }

        /* Canvas Particle Layer for Sparks & Shockwaves */
        #sparkCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 30;
        }

        /* =============================================================
           FULL-SCREEN BACKGROUND MACHINERY SYSTEM
        ============================================================= */
        .fullscreen-machinery {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
            background: radial-gradient(circle at 50% 50%, #08172c 0%, #030812 85%);
        }

        /* Blueprint Grid & Lighting */
        .machinery-grid {
            position: absolute;
            inset: 0;
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(0, 210, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 210, 255, 0.04) 1px, transparent 1px);
            opacity: 0.8;
            pointer-events: none;
        }

        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            opacity: 0.35;
        }
        .glow-1 {
            top: 20%;
            left: 15%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 210, 255, 0.3) 0%, transparent 70%);
        }
        .glow-2 {
            bottom: 15%;
            right: 15%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, transparent 70%);
        }

        /* The Interactive Assembly Rig (Shakes/Recoil on Click Anywhere) */
        .machinery-assembly {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transform-origin: center center;
            transition: transform 0.05s ease;
        }

        .machinery-assembly.tremor {
            animation: mechanicalRecoil 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }

        @keyframes mechanicalRecoil {
            0% { transform: scale(1) translate(0, 0) rotate(0deg); }
            15% { transform: scale(0.985) translate(-6px, 5px) rotate(-1.2deg); filter: brightness(1.35); }
            30% { transform: scale(1.015) translate(6px, -4px) rotate(1deg); }
            50% { transform: scale(0.992) translate(-3px, 2px) rotate(-0.5deg); }
            70% { transform: scale(1.008) translate(3px, -2px) rotate(0.3deg); }
            100% { transform: scale(1) translate(0, 0) rotate(0deg); filter: brightness(1); }
        }

        /* Gear SVGs across the screen */
        .gear-svg {
            position: absolute;
            transform-origin: center center;
            filter: drop-shadow(0 10px 24px rgba(0, 0, 0, 0.8));
            pointer-events: auto;
            cursor: pointer;
            transition: filter 0.25s ease;
        }

        .gear-svg:hover {
            filter: drop-shadow(0 0 25px rgba(245, 158, 11, 0.85)) drop-shadow(0 12px 28px rgba(0, 0, 0, 0.9));
        }

        /* Rotations */
        @keyframes rotateCW {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotateCCW {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

        /* 1. MASTER ENGINE GEAR (Center-Left Backdrop - 24 Teeth) */
        .gear-master {
            width: 440px;
            height: 440px;
            top: 48%;
            left: 28%;
            margin-top: -220px;
            margin-left: -220px;
            z-index: 5;
            animation: rotateCW 24s linear infinite;
        }

        /* 2. PLANETARY GEAR (Upper-Right Mesh - 16 Teeth) */
        .gear-planetary {
            width: 300px;
            height: 300px;
            top: 48%;
            left: 28%;
            margin-top: -345px;
            margin-left: 110px;
            z-index: 4;
            animation: rotateCCW 16s linear infinite;
        }

        /* 3. HIGH SPEED PINION GEAR (Far Upper-Right - 12 Teeth) */
        .gear-pinion {
            width: 210px;
            height: 210px;
            top: 48%;
            left: 28%;
            margin-top: -320px;
            margin-left: 320px;
            z-index: 3;
            animation: rotateCW 12s linear infinite;
        }

        /* 4. BEVEL GEAR (Lower-Center - 18 Teeth) */
        .gear-bevel {
            width: 320px;
            height: 320px;
            top: 48%;
            left: 28%;
            margin-top: 110px;
            margin-left: -80px;
            z-index: 4;
            animation: rotateCCW 18s linear infinite;
        }

        /* 5. AUXILIARY CROWN GEAR (Far Left - 14 Teeth) */
        .gear-aux {
            width: 250px;
            height: 250px;
            top: 48%;
            left: 28%;
            margin-top: 75px;
            margin-left: -330px;
            z-index: 3;
            animation: rotateCW 14s linear infinite;
        }

        /* 6. SECONDARY DRIVER GEAR (Far Right Edge - 20 Teeth) */
        .gear-secondary {
            width: 360px;
            height: 360px;
            top: 55%;
            right: 4%;
            margin-top: -180px;
            z-index: 2;
            animation: rotateCCW 20s linear infinite;
            opacity: 0.85;
        }

        /* 7. TOP LEFT CORNER GEAR (16 Teeth) */
        .gear-corner-tl {
            width: 240px;
            height: 240px;
            top: -50px;
            left: -50px;
            z-index: 2;
            animation: rotateCW 16s linear infinite;
            opacity: 0.7;
        }

        /* 8. BOTTOM RIGHT CORNER GEAR (18 Teeth) */
        .gear-corner-br {
            width: 280px;
            height: 280px;
            bottom: -60px;
            right: -60px;
            z-index: 2;
            animation: rotateCW 18s linear infinite;
            opacity: 0.65;
        }

        /* Mechanical Steam Engine Pistons */
        .steam-piston {
            position: absolute;
            z-index: 3;
            display: flex;
            align-items: center;
            pointer-events: auto;
        }

        .piston-top-right {
            top: 14%;
            right: 18%;
            width: 200px;
            height: 70px;
        }

        .piston-bottom-left {
            bottom: 12%;
            left: 8%;
            width: 220px;
            height: 70px;
        }

        .piston-cylinder {
            width: 90px;
            height: 60px;
            background: linear-gradient(135deg, #1e293b, #334155, #0f172a);
            border: 2px solid #475569;
            border-radius: 8px;
            box-shadow: inset 0 0 12px #000, 0 8px 20px rgba(0, 0, 0, 0.6);
            position: relative;
        }

        .piston-cylinder::before {
            content: 'STEAM-CORE';
            position: absolute;
            top: 5px;
            left: 8px;
            font-family: var(--font-tech);
            font-size: 8px;
            color: var(--cyan-glow);
            letter-spacing: 1px;
        }

        .piston-rod {
            width: 90px;
            height: 18px;
            background: linear-gradient(to bottom, #f1f5f9, #94a3b8, #cbd5e1);
            border: 1px solid #475569;
            border-radius: 4px;
            animation: pistonStroke 2.2s ease-in-out infinite alternate;
            box-shadow: 0 4px 10px rgba(0,0,0,0.6);
        }

        .piston-rod-rev {
            animation-direction: alternate-reverse;
            animation-duration: 1.8s;
        }

        @keyframes pistonStroke {
            0% { transform: translateX(0px); }
            100% { transform: translateX(-45px); }
        }

        /* Industrial Marine Pressure Gauges */
        .marine-gauge {
            position: absolute;
            z-index: 6;
            width: 86px;
            height: 86px;
            background: radial-gradient(circle, #09121f 55%, #1e293b 100%);
            border: 3px solid #b45309;
            border-radius: 50%;
            box-shadow: 0 0 18px rgba(245, 158, 11, 0.35), inset 0 0 10px #000;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: auto;
        }

        .gauge-pos-1 {
            bottom: 40px;
            left: 40px;
        }

        .gauge-pos-2 {
            top: 45px;
            right: 50px;
            border-color: #0284c7;
            box-shadow: 0 0 18px rgba(0, 210, 255, 0.35), inset 0 0 10px #000;
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
            width: 32px;
            height: 2.5px;
            background: #ef233c;
            transform-origin: 0% 50%;
            border-radius: 2px;
            animation: gaugeOscillate 3s ease-in-out infinite alternate;
            box-shadow: 0 0 6px #ef233c;
        }

        .gauge-needle-blue {
            background: #00d2ff;
            box-shadow: 0 0 6px #00d2ff;
            animation: gaugeOscillate2 2.5s ease-in-out infinite alternate;
        }

        @keyframes gaugeOscillate {
            0% { transform: rotate(-60deg); }
            40% { transform: rotate(15deg); }
            80% { transform: rotate(-20deg); }
            100% { transform: rotate(35deg); }
        }

        @keyframes gaugeOscillate2 {
            0% { transform: rotate(-40deg); }
            50% { transform: rotate(25deg); }
            100% { transform: rotate(-10deg); }
        }

        .gauge-label {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            font-family: var(--font-tech);
            font-size: 8px;
            color: #f59e0b;
            letter-spacing: 0.8px;
            font-weight: 700;
        }
        .gauge-pos-2 .gauge-label {
            color: #38bdf8;
        }

        /* Gear Mesh Contact Hotspots (Sparks ignite here on hover) */
        .mesh-hotspot {
            position: absolute;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: transparent;
            z-index: 15;
            pointer-events: auto;
            cursor: pointer;
        }

        .mesh-hotspot::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            margin: -4px;
            background: rgba(245, 158, 11, 0.5);
            border-radius: 50%;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.8);
            animation: pulseMesh 1.2s infinite alternate;
        }

        @keyframes pulseMesh {
            0% { transform: scale(0.8); opacity: 0.4; }
            100% { transform: scale(1.4); opacity: 0.9; }
        }

        /* Hotspot Locations at Exact Interlocking Points */
        .hs-master-planetary {
            top: 48%;
            left: 28%;
            margin-top: -155px;
            margin-left: 20px;
        }

        .hs-planetary-pinion {
            top: 48%;
            left: 28%;
            margin-top: -240px;
            margin-left: 220px;
        }

        .hs-master-bevel {
            top: 48%;
            left: 28%;
            margin-top: 60px;
            margin-left: -50px;
        }

        .hs-bevel-aux {
            top: 48%;
            left: 28%;
            margin-top: 90px;
            margin-left: -200px;
        }

        /* =============================================================
           FOREGROUND HUD / CONTENT CARD (100% ENGLISH, CLEAN GLASSMORPHISM)
        ============================================================= */
        .foreground-wrapper {
            position: relative;
            z-index: 20;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 30px 20px;
            pointer-events: none;
        }

        .hud-card {
            pointer-events: auto;
            background: rgba(6, 18, 36, 0.78);
            border: 1px solid rgba(0, 210, 255, 0.3);
            border-radius: 26px;
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.75),
                0 0 35px rgba(0, 210, 255, 0.12),
                inset 0 0 40px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            max-width: 860px;
            width: 100%;
            padding: 38px 40px 32px;
            margin: auto;
            text-align: center;
            position: relative;
        }

        /* Status Badge Pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(15, 30, 55, 0.85);
            border: 1px solid rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
            padding: 8px 22px;
            border-radius: 30px;
            margin-bottom: 22px;
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background-color: var(--brass-primary);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--brass-primary);
            animation: pulseDot 1.4s infinite alternate;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.85); opacity: 0.6; box-shadow: 0 0 4px var(--brass-primary); }
            100% { transform: scale(1.3); opacity: 1; box-shadow: 0 0 16px var(--brass-primary); }
        }

        .status-text {
            font-family: var(--font-tech);
            font-size: 0.84rem;
            letter-spacing: 1.8px;
            color: #fef3c7;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Headline & Description */
        .site-title {
            font-family: var(--font-tech);
            font-size: clamp(1.6rem, 4vw, 2.5rem);
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 14px;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.8);
            line-height: 1.25;
        }

        .site-subtitle {
            font-size: clamp(0.95rem, 1.8vw, 1.1rem);
            color: #cbd5e1;
            max-width: 720px;
            margin: 0 auto 28px;
            line-height: 1.65;
            font-weight: 400;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
            margin-top: 10px;
        }

        .info-box {
            background: rgba(12, 29, 58, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 22px;
            text-align: left;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            transition: border-color 0.25s ease, transform 0.25s ease;
        }

        .info-box:hover {
            border-color: rgba(0, 210, 255, 0.45);
            transform: translateY(-2px);
        }

        .info-box-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .info-box-icon {
            width: 40px;
            height: 40px;
            background: rgba(0, 210, 255, 0.15);
            border: 1px solid rgba(0, 210, 255, 0.4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #00d2ff;
        }

        .info-box-icon.red {
            background: rgba(239, 35, 60, 0.15);
            border-color: rgba(239, 35, 60, 0.4);
            color: #ef233c;
        }

        .info-box-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
        }

        .info-box-status {
            font-size: 0.78rem;
            color: #38bdf8;
            font-weight: 600;
            font-family: var(--font-tech);
            letter-spacing: 0.5px;
        }

        .info-box-status.red {
            color: #ef233c;
        }

        .info-box-body {
            color: #94a3b8;
            font-size: 0.9rem;
            line-height: 1.55;
        }

        /* Progress Bar */
        .progress-track {
            width: 100%;
            height: 12px;
            background: rgba(8, 16, 28, 0.9);
            border: 1px solid rgba(0, 210, 255, 0.35);
            border-radius: 10px;
            overflow: hidden;
            margin: 14px 0 8px;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            width: 85%;
            background: repeating-linear-gradient(
                -45deg,
                #0284c7,
                #0284c7 10px,
                #00d2ff 10px,
                #00d2ff 20px
            );
            background-size: 40px 40px;
            animation: progressStripe 1.5s linear infinite;
            box-shadow: 0 0 12px rgba(0, 210, 255, 0.5);
            border-radius: 10px;
        }

        @keyframes progressStripe {
            0% { background-position: 0 0; }
            100% { background-position: 40px 0; }
        }

        /* Emergency Action Button */
        .btn-emergency {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            background: linear-gradient(135deg, #ef233c 0%, #b91c1c 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.92rem;
            box-shadow: 0 4px 16px rgba(239, 35, 60, 0.4);
            transition: all 0.25s ease;
            margin-top: 14px;
            width: 100%;
        }

        .btn-emergency:hover {
            background: linear-gradient(135deg, #ff3b53 0%, #dc2626 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(239, 35, 60, 0.6);
        }

        /* Footer */
        .footer-bar {
            pointer-events: auto;
            font-size: 0.84rem;
            color: #64748b;
            text-align: center;
            margin-top: 20px;
        }

        .admin-link {
            color: #475569;
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.8rem;
            transition: color 0.2s ease;
        }
        .admin-link:hover {
            color: #00d2ff;
        }

        /* Mobile Optimization */
        @media (max-width: 768px) {
            .hud-card {
                padding: 28px 20px 24px;
            }
            .gear-master {
                width: 320px;
                height: 320px;
                margin-top: -160px;
                margin-left: -160px;
                left: 50%;
                opacity: 0.55;
            }
            .gear-planetary {
                width: 220px;
                height: 220px;
                margin-top: -240px;
                margin-left: 40px;
                left: 50%;
                opacity: 0.55;
            }
            .gear-bevel {
                width: 230px;
                height: 230px;
                margin-top: 70px;
                margin-left: -140px;
                left: 50%;
                opacity: 0.55;
            }
            .gear-pinion, .gear-aux, .piston-top-right, .piston-bottom-left {
                display: none;
            }
            .marine-gauge {
                width: 64px;
                height: 64px;
            }
            .gauge-pos-1 {
                bottom: 18px;
                left: 18px;
            }
            .gauge-pos-2 {
                top: 18px;
                right: 18px;
            }
        }

        @media (max-width: 480px) {
            .foreground-wrapper {
                padding: 16px 12px;
            }
            .hud-card {
                padding: 22px 16px;
                border-radius: 20px;
            }
            .site-title {
                font-size: 1.45rem;
            }
            .status-pill {
                padding: 6px 16px;
                margin-bottom: 16px;
            }
            .status-text {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>

    <!-- Top Hazard Strip -->
    <div class="hazard-strip top"></div>

    <!-- Particle Spark & Shockwave Canvas -->
    <canvas id="sparkCanvas"></canvas>

    <!-- =============================================================
         FULL-SCREEN BACKGROUND MACHINERY SYSTEM
    ============================================================= -->
    <div class="fullscreen-machinery" id="fullscreenMachinery">
        <!-- Blueprint Grid & Ambient Glows -->
        <div class="machinery-grid"></div>
        <div class="ambient-glow glow-1"></div>
        <div class="ambient-glow glow-2"></div>

        <!-- The Machinery Rig Assembly (Tremors on Click Anywhere) -->
        <div class="machinery-assembly" id="machineryAssembly">

            <!-- 1. Master Drive Gear (24 Teeth - Brass & Nautical Anchor Hub) -->
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
                <path fill="url(#brassGrad)" stroke="#78350f" stroke-width="2" d="
                    M 100 10 
                    L 104 10 L 105 24 L 111 25 L 119 13 L 123 15 L 122 29 L 127 31 L 137 21 L 141 24 L 138 38 L 143 41 L 154 34 L 157 38 L 150 51 L 154 55 L 167 50 L 169 55 L 160 67 L 163 71 L 176 70 L 177 75 L 165 85 L 167 90 L 180 91 L 180 96 L 166 104 L 166 109 L 179 113 L 178 118 L 163 123 L 162 128 L 174 135 L 172 140 L 156 142 L 154 147 L 164 157 L 161 161 L 146 159 L 143 163 L 150 176 L 145 179 L 132 173 L 129 177 L 132 191 L 127 193 L 117 184 L 113 187 L 112 201 L 107 202 L 100 190 
                    L 93 202 L 88 201 L 87 187 L 83 184 L 73 193 L 68 191 L 71 177 L 68 173 L 55 179 L 50 176 L 57 163 L 54 159 L 39 161 L 36 157 L 46 147 L 44 142 L 28 140 L 26 135 L 38 128 L 37 123 L 22 118 L 21 113 L 34 109 L 34 104 L 20 96 L 20 91 L 33 90 L 35 85 L 23 75 L 24 70 L 37 71 L 40 67 L 31 55 L 33 50 L 46 55 L 50 51 L 43 38 L 46 34 L 57 41 L 62 38 L 59 24 L 63 21 L 73 31 L 78 29 L 77 15 L 81 13 L 89 25 L 95 24 Z
                "/>
                <circle cx="100" cy="100" r="62" fill="#071326" stroke="#b45309" stroke-width="3"/>
                <circle cx="100" cy="100" r="48" fill="url(#brassGrad)" stroke="#78350f" stroke-width="2"/>
                <circle cx="100" cy="100" r="32" fill="url(#hubGrad)" stroke="#f8fafc" stroke-width="2"/>
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
                <circle cx="75" cy="75" r="44" fill="#071326" stroke="#475569" stroke-width="2"/>
                <circle cx="75" cy="75" r="26" fill="url(#steelGrad)" stroke="#334155" stroke-width="2"/>
                <circle cx="75" cy="46" r="6" fill="#071326"/>
                <circle cx="75" cy="104" r="6" fill="#071326"/>
                <circle cx="46" cy="75" r="6" fill="#071326"/>
                <circle cx="104" cy="75" r="6" fill="#071326"/>
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
                <circle cx="60" cy="60" r="32" fill="#071326" stroke="#7c2d12" stroke-width="2"/>
                <circle cx="60" cy="60" r="16" fill="url(#copperGrad)"/>
            </svg>

            <!-- 4. Bevel Transmission Gear (18 Teeth - Gunmetal) -->
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
                <circle cx="70" cy="70" r="38" fill="#071326" stroke="#475569" stroke-width="2"/>
                <circle cx="70" cy="70" r="22" fill="url(#gunmetalGrad)"/>
            </svg>

            <!-- 5. Auxiliary Crown Gear (Far Left) -->
            <svg class="gear-svg gear-aux" viewBox="0 0 150 150">
                <path fill="url(#steelGrad)" stroke="#334155" stroke-width="2" d="
                    M 75 8 L 78 8 L 79 19 L 85 20 L 92 11 L 96 13 L 95 24 L 100 27 L 109 20 L 112 24 L 109 34 L 114 38 L 123 34 L 125 39 L 119 48 L 122 53 L 132 52 L 133 57 L 124 65 L 125 70 L 135 73 L 134 78 L 123 83 L 122 88 L 130 96 L 128 100 L 117 101 L 114 106 L 120 115 L 117 118 L 107 115 L 103 119 L 106 129 L 101 131 L 93 124 L 88 127 L 88 138 L 83 139 L 75 130 L 67 139 L 62 138 L 62 127 L 57 124 L 49 131 L 44 129 L 47 119 L 43 115 L 33 118 L 30 115 L 36 106 L 33 101 L 22 100 L 20 96 L 28 88 L 27 83 L 16 78 L 15 73 L 25 70 L 26 65 L 17 57 L 18 52 L 28 53 L 31 48 L 25 39 L 27 34 L 36 38 L 41 34 L 38 24 L 41 20 L 50 27 L 55 24 L 54 13 L 58 11 L 65 20 L 71 19 Z
                "/>
                <circle cx="75" cy="75" r="42" fill="#071326" stroke="#475569" stroke-width="2"/>
                <circle cx="75" cy="75" r="22" fill="url(#brassGrad)"/>
            </svg>

            <!-- 6. Secondary Driver Gear (Far Right Edge) -->
            <svg class="gear-svg gear-secondary" viewBox="0 0 200 200">
                <path fill="url(#brassGrad)" stroke="#78350f" stroke-width="2" d="
                    M 100 10 L 104 10 L 105 24 L 111 25 L 119 13 L 123 15 L 122 29 L 127 31 L 137 21 L 141 24 L 138 38 L 143 41 L 154 34 L 157 38 L 150 51 L 154 55 L 167 50 L 169 55 L 160 67 L 163 71 L 176 70 L 177 75 L 165 85 L 167 90 L 180 91 L 180 96 L 166 104 L 166 109 L 179 113 L 178 118 L 163 123 L 162 128 L 174 135 L 172 140 L 156 142 L 154 147 L 164 157 L 161 161 L 146 159 L 143 163 L 150 176 L 145 179 L 132 173 L 129 177 L 132 191 L 127 193 L 117 184 L 113 187 L 112 201 L 107 202 L 100 190 
                    L 93 202 L 88 201 L 87 187 L 83 184 L 73 193 L 68 191 L 71 177 L 68 173 L 55 179 L 50 176 L 57 163 L 54 159 L 39 161 L 36 157 L 46 147 L 44 142 L 28 140 L 26 135 L 38 128 L 37 123 L 22 118 L 21 113 L 34 109 L 34 104 L 20 96 L 20 91 L 33 90 L 35 85 L 23 75 L 24 70 L 37 71 L 40 67 L 31 55 L 33 50 L 46 55 L 50 51 L 43 38 L 46 34 L 57 41 L 62 38 L 59 24 L 63 21 L 73 31 L 78 29 L 77 15 L 81 13 L 89 25 L 95 24 Z
                "/>
                <circle cx="100" cy="100" r="60" fill="#071326" stroke="#b45309" stroke-width="2"/>
                <circle cx="100" cy="100" r="30" fill="url(#steelGrad)"/>
            </svg>

            <!-- 7. Corner Gears for Full-Screen Ambient Machine Coverage -->
            <svg class="gear-svg gear-corner-tl" viewBox="0 0 150 150">
                <path fill="url(#gunmetalGrad)" stroke="#1e293b" stroke-width="2" d="
                    M 75 8 L 78 8 L 79 19 L 85 20 L 92 11 L 96 13 L 95 24 L 100 27 L 109 20 L 112 24 L 109 34 L 114 38 L 123 34 L 125 39 L 119 48 L 122 53 L 132 52 L 133 57 L 124 65 L 125 70 L 135 73 L 134 78 L 123 83 L 122 88 L 130 96 L 128 100 L 117 101 L 114 106 L 120 115 L 117 118 L 107 115 L 103 119 L 106 129 L 101 131 L 93 124 L 88 127 L 88 138 L 83 139 L 75 130 L 67 139 L 62 138 L 62 127 L 57 124 L 49 131 L 44 129 L 47 119 L 43 115 L 33 118 L 30 115 L 36 106 L 33 101 L 22 100 L 20 96 L 28 88 L 27 83 L 16 78 L 15 73 L 25 70 L 26 65 L 17 57 L 18 52 L 28 53 L 31 48 L 25 39 L 27 34 L 36 38 L 41 34 L 38 24 L 41 20 L 50 27 L 55 24 L 54 13 L 58 11 L 65 20 L 71 19 Z
                "/>
                <circle cx="75" cy="75" r="40" fill="#071326"/>
            </svg>

            <svg class="gear-svg gear-corner-br" viewBox="0 0 150 150">
                <path fill="url(#copperGrad)" stroke="#7c2d12" stroke-width="2" d="
                    M 75 8 L 78 8 L 79 19 L 85 20 L 92 11 L 96 13 L 95 24 L 100 27 L 109 20 L 112 24 L 109 34 L 114 38 L 123 34 L 125 39 L 119 48 L 122 53 L 132 52 L 133 57 L 124 65 L 125 70 L 135 73 L 134 78 L 123 83 L 122 88 L 130 96 L 128 100 L 117 101 L 114 106 L 120 115 L 117 118 L 107 115 L 103 119 L 106 129 L 101 131 L 93 124 L 88 127 L 88 138 L 83 139 L 75 130 L 67 139 L 62 138 L 62 127 L 57 124 L 49 131 L 44 129 L 47 119 L 43 115 L 33 118 L 30 115 L 36 106 L 33 101 L 22 100 L 20 96 L 28 88 L 27 83 L 16 78 L 15 73 L 25 70 L 26 65 L 17 57 L 18 52 L 28 53 L 31 48 L 25 39 L 27 34 L 36 38 L 41 34 L 38 24 L 41 20 L 50 27 L 55 24 L 54 13 L 58 11 L 65 20 L 71 19 Z
                "/>
                <circle cx="75" cy="75" r="40" fill="#071326"/>
            </svg>

            <!-- Steam Pistons -->
            <div class="steam-piston piston-top-right">
                <div class="piston-cylinder"></div>
                <div class="piston-rod"></div>
            </div>

            <div class="steam-piston piston-bottom-left">
                <div class="piston-rod piston-rod-rev"></div>
                <div class="piston-cylinder"></div>
            </div>

            <!-- Industrial Pressure Gauges -->
            <div class="marine-gauge gauge-pos-1">
                <div class="gauge-dial">
                    <div class="gauge-needle"></div>
                    <span class="gauge-label">PSI 85</span>
                </div>
            </div>

            <div class="marine-gauge gauge-pos-2">
                <div class="gauge-dial">
                    <div class="gauge-needle gauge-needle-blue"></div>
                    <span class="gauge-label">RPM 140</span>
                </div>
            </div>

            <!-- Mesh Contact Friction Hotspots (Sparks fly on hover) -->
            <div class="mesh-hotspot hs-master-planetary" id="hs1"></div>
            <div class="mesh-hotspot hs-planetary-pinion" id="hs2"></div>
            <div class="mesh-hotspot hs-master-bevel" id="hs3"></div>
            <div class="mesh-hotspot hs-bevel-aux" id="hs4"></div>

        </div>
    </div>

    <!-- =============================================================
         FOREGROUND CONTENT (100% ENGLISH, CLEAN GLASS HUD)
    ============================================================= -->
    <div class="foreground-wrapper">
        <div></div> <!-- Spacer for vertical centering -->

        <div class="hud-card">
            <!-- Scheduled Maintenance Badge -->
            <div class="status-pill">
                <span class="pulse-dot"></span>
                <span class="status-text">SCHEDULED SYSTEM MAINTENANCE IN PROGRESS</span>
            </div>

            <!-- Primary Headline & Message -->
            <h1 class="site-title"><?= htmlspecialchars($mConfig['headline']) ?></h1>
            <p class="site-subtitle"><?= htmlspecialchars($mConfig['message']) ?></p>

            <!-- Information Grid -->
            <div class="info-grid">
                <!-- Upgrade Progress Card -->
                <div class="info-box">
                    <div class="info-box-header">
                        <div class="info-box-icon">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div>
                            <h4 class="info-box-title">System Engine Optimization</h4>
                            <div class="info-box-status"><?= htmlspecialchars($mConfig['estimated_time']) ?></div>
                        </div>
                    </div>
                    <div class="info-box-body">
                        Our maritime engineers are optimizing high-availability database nodes and calibrating donor matching algorithms.
                        <div class="progress-track">
                            <div class="progress-bar"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.78rem; color: #64748b; font-family: var(--font-mono); margin-top: 4px;">
                            <span>Core Infrastructure: Synced</span>
                            <span style="color: #38bdf8; font-weight: bold;">85% Completed</span>
                        </div>
                    </div>
                </div>

                <!-- Emergency Blood Support Card -->
                <div class="info-box">
                    <div class="info-box-header">
                        <div class="info-box-icon red">
                            <i class="bi bi-droplet-fill"></i>
                        </div>
                        <div>
                            <h4 class="info-box-title">Emergency Blood Hotline</h4>
                            <div class="info-box-status red">24/7 COORDINATOR ON DUTY</div>
                        </div>
                    </div>
                    <div class="info-box-body">
                        Urgent blood requirements are actively monitored during this scheduled maintenance. Reach our emergency duty coordinator directly:
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $mConfig['contact_phone']) ?>?text=<?= urlencode('Urgent Blood Request (BMMC Emergency)') ?>" target="_blank" class="btn-emergency">
                            <i class="bi bi-whatsapp fs-5"></i>
                            <span>WhatsApp Hotline: <?= htmlspecialchars($mConfig['contact_phone']) ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-bar">
            <p class="mb-1">&copy; <?= date('Y') ?> Bangladesh Merchant Mariners Community (BMMC). All rights reserved.</p>
            <p class="mb-0">
                <a href="<?= (defined('BASE_URL') ? BASE_URL : '') ?>/m" class="admin-link">
                    <i class="bi bi-shield-lock me-1"></i>System Panel (/m)
                </a>
            </p>
        </div>
    </div>

    <!-- Bottom Hazard Strip -->
    <div class="hazard-strip bottom"></div>

    <!-- =============================================================
         INTERACTION ENGINE: SPARKS, TREMORS, SHOCKWAVES & AUDIO
    ============================================================= -->
    <script>
    (function () {
        const canvas = document.getElementById('sparkCanvas');
        const ctx = canvas.getContext('2d');
        const assembly = document.getElementById('machineryAssembly');
        
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

        // Particle System
        const particles = [];
        const shockwaves = [];

        // Web Audio API for Mechanical Clink / Tremor Sound
        let audioCtx = null;
        function playMechanicalFeedback() {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const now = audioCtx.currentTime;

                // Metallic Clink Oscillator
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(640, now);
                osc.frequency.exponentialRampToValueAtTime(120, now + 0.14);

                gain.gain.setValueAtTime(0.28, now);
                gain.gain.exponentialRampToValueAtTime(0.005, now + 0.14);

                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(now);
                osc.stop(now + 0.15);

                // Heavy Sub Thud
                const subOsc = audioCtx.createOscillator();
                const subGain = audioCtx.createGain();
                subOsc.type = 'sine';
                subOsc.frequency.setValueAtTime(95, now);
                subOsc.frequency.exponentialRampToValueAtTime(35, now + 0.22);
                subGain.gain.setValueAtTime(0.35, now);
                subGain.gain.exponentialRampToValueAtTime(0.01, now + 0.22);
                subOsc.connect(subGain);
                subGain.connect(audioCtx.destination);
                subOsc.start(now);
                subOsc.stop(now + 0.23);
            } catch (e) {}
        }

        // Spark Particle (Molten Metal Embers)
        class Spark {
            constructor(x, y, vx, vy, isBurst = false) {
                this.x = x;
                this.y = y;
                this.vx = vx;
                this.vy = vy;
                this.size = Math.random() * (isBurst ? 3.8 : 2.5) + 1;
                this.decay = Math.random() * 0.02 + 0.016;
                this.alpha = 1;
                const r = Math.random();
                this.color = r > 0.45 ? '#f59e0b' : (r > 0.15 ? '#ffffff' : '#ea580c');
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.vy += 0.2; // gravity
                this.vx *= 0.98; // drag
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

        // Shockwave Ring
        class Shockwave {
            constructor(x, y) {
                this.x = x;
                this.y = y;
                this.radius = 12;
                this.maxRadius = Math.random() * 50 + 75;
                this.alpha = 0.9;
            }
            update() {
                this.radius += 4.5;
                this.alpha -= 0.038;
            }
            draw() {
                if (this.alpha <= 0) return;
                ctx.save();
                ctx.globalAlpha = Math.max(0, this.alpha);
                ctx.strokeStyle = '#00d2ff';
                ctx.lineWidth = 2.5;
                ctx.shadowColor = '#00d2ff';
                ctx.shadowBlur = 14;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.stroke();
                ctx.restore();
            }
        }

        // Emit Continuous Stream of Sparks
        function emitSparks(x, y, count = 5, direction = null) {
            for (let i = 0; i < count; i++) {
                const angle = direction !== null 
                    ? direction + (Math.random() - 0.5) * 1.5 
                    : Math.random() * Math.PI * 2;
                const speed = Math.random() * 5.5 + 2.5;
                const vx = Math.cos(angle) * speed;
                const vy = Math.sin(angle) * speed;
                particles.push(new Spark(x, y, vx, vy));
            }
        }

        // Emit Explosive Burst of Sparks on Click / Touch
        function emitBurst(x, y) {
            shockwaves.push(new Shockwave(x, y));
            for (let i = 0; i < 40; i++) {
                const angle = Math.random() * Math.PI * 2;
                const speed = Math.random() * 9 + 3;
                const vx = Math.cos(angle) * speed;
                const vy = Math.sin(angle) * speed - 1.8;
                particles.push(new Spark(x, y, vx, vy, true));
            }
        }

        // Animation Loop
        function render() {
            ctx.clearRect(0, 0, width, height);

            for (let i = shockwaves.length - 1; i >= 0; i--) {
                const sw = shockwaves[i];
                sw.update();
                sw.draw();
                if (sw.alpha <= 0) shockwaves.splice(i, 1);
            }

            for (let i = particles.length - 1; i >= 0; i--) {
                const p = particles[i];
                p.update();
                p.draw();
                if (p.alpha <= 0) particles.splice(i, 1);
            }

            requestAnimationFrame(render);
        }
        render();

        // 1. Friction Hotspots Hover Listener
        const hotspots = [
            document.getElementById('hs1'),
            document.getElementById('hs2'),
            document.getElementById('hs3'),
            document.getElementById('hs4')
        ];

        hotspots.forEach((hs, idx) => {
            if (!hs) return;
            hs.addEventListener('mousemove', (e) => {
                const rect = hs.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const cy = rect.top + rect.height / 2;
                const dirs = [-Math.PI / 4, Math.PI / 4, -Math.PI / 2, Math.PI / 3];
                emitSparks(cx, cy, 4, dirs[idx % dirs.length]);
            });

            hs.addEventListener('mouseenter', () => {
                const rect = hs.getBoundingClientRect();
                emitSparks(rect.left + rect.width / 2, rect.top + rect.height / 2, 12);
            });
        });

        // 2. Gears Hover Sparks
        const gearElements = document.querySelectorAll('.gear-svg');
        let lastGearSpark = 0;
        gearElements.forEach(gear => {
            gear.addEventListener('mousemove', (e) => {
                const now = performance.now();
                if (now - lastGearSpark > 60) {
                    lastGearSpark = now;
                    emitSparks(e.clientX, e.clientY, 3);
                }
            });
        });

        // 3. Global Click: Machine Recoil Vibration + Impact Sparks Anywhere!
        function triggerMachineRecoil(clientX, clientY) {
            assembly.classList.remove('tremor');
            void assembly.offsetWidth; // force reflow
            assembly.classList.add('tremor');

            emitBurst(clientX, clientY);
            playMechanicalFeedback();
        }

        window.addEventListener('click', (e) => {
            // Avoid triggering burst if clicking a link or button directly
            if (e.target.closest('a') || e.target.closest('button')) return;
            triggerMachineRecoil(e.clientX, e.clientY);
        });

        // 4. Touch Support for Mobile & Tablets
        window.addEventListener('touchstart', (e) => {
            if (e.touches && e.touches[0]) {
                const touch = e.touches[0];
                if (e.target.closest('a') || e.target.closest('button')) return;
                triggerMachineRecoil(touch.clientX, touch.clientY);
            }
        }, { passive: true });

        let lastTouchSpark = 0;
        window.addEventListener('touchmove', (e) => {
            if (e.touches && e.touches[0]) {
                const touch = e.touches[0];
                const now = performance.now();
                if (now - lastTouchSpark > 80) {
                    lastTouchSpark = now;
                    emitSparks(touch.clientX, touch.clientY, 3);
                }
            }
        }, { passive: true });

    })();
    </script>
</body>
</html>
