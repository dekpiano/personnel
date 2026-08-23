<?= $this->extend('User/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'ระบบบริหารงานบุคคล โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Modern Fonts: K2D & Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,600&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ====================================================
       PORTAL HOMEPAGE LUXURY DESIGN SYSTEM
       Theme: Animated Mesh Gradient Body & Bento Portal
       ==================================================== */
    :root {
        --home-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
        --primary-blue: #0284c7;
        --dark-blue: #1e3a8a;
    }

    /* 🌊 Animated Background on <body> */
    body {
        font-family: 'K2D', sans-serif !important;
        color: #1e293b;
        position: relative;
        overflow-x: hidden;
        background: linear-gradient(-45deg, #f0f9ff, #e0f2fe, #f8fafc, #f0fdf4, #eff6ff) !important;
        background-size: 400% 400% !important;
        animation: bodyGradientMove 16s ease infinite !important;
    }

    @keyframes bodyGradientMove {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* ====================================================
       🌐 ANIMATED SVG GRID PATTERN SYSTEM (High-Tech Pattern)
       ==================================================== */
    .animated-grid-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }

    .animated-grid-svg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Grid Moving Lattice Animation */
    .grid-moving-layer {
        animation: gridDrift 22s linear infinite;
        will-change: transform;
    }

    @keyframes gridDrift {
        0% {
            transform: translate3d(0, 0, 0);
        }
        100% {
            transform: translate3d(48px, 48px, 0);
        }
    }

    /* Pulsing Glowing Grid Tiles */
    .pulse-tile {
        opacity: 0;
        filter: drop-shadow(0 0 10px currentColor);
        animation-iteration-count: infinite;
        animation-timing-function: ease-in-out;
        will-change: opacity, transform;
    }

    .tile-1 { animation: pulseGlow1 6s 0.2s infinite; color: #38bdf8; }
    .tile-2 { animation: pulseGlow2 8s 1.5s infinite; color: #0284c7; }
    .tile-3 { animation: pulseGlow1 7s 2.8s infinite; color: #60a5fa; }
    .tile-4 { animation: pulseGlow2 9s 0.8s infinite; color: #38bdf8; }
    .tile-5 { animation: pulseGlow1 8.5s 3.5s infinite; color: #818cf8; }
    .tile-6 { animation: pulseGlow2 6.5s 4.2s infinite; color: #34d399; }
    .tile-7 { animation: pulseGlow1 7.5s 1.1s infinite; color: #38bdf8; }
    .tile-8 { animation: pulseGlow2 8s 2.3s infinite; color: #0284c7; }

    @keyframes pulseGlow1 {
        0%, 100% {
            opacity: 0;
            transform: scale(0.95);
        }
        50% {
            opacity: 0.35;
            transform: scale(1);
        }
    }

    @keyframes pulseGlow2 {
        0%, 100% {
            opacity: 0;
            transform: scale(0.95);
        }
        50% {
            opacity: 0.45;
            transform: scale(1.02);
        }
    }

    /* Ambient Floating Mesh Aura Orbs */
    .ambient-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(85px);
        opacity: 0.42;
        pointer-events: none;
        z-index: 0;
        will-change: transform;
    }

    .orb-1 {
        top: -8%;
        left: -8%;
        width: 580px;
        height: 580px;
        background: radial-gradient(circle, #38bdf8 0%, rgba(56, 189, 248, 0) 70%);
        animation: floatOrb1 20s ease-in-out infinite alternate;
    }

    .orb-2 {
        top: 38%;
        right: -12%;
        width: 620px;
        height: 620px;
        background: radial-gradient(circle, #818cf8 0%, rgba(129, 140, 248, 0) 70%);
        animation: floatOrb2 24s ease-in-out infinite alternate;
    }

    .orb-3 {
        bottom: -10%;
        left: 25%;
        width: 520px;
        height: 520px;
        background: radial-gradient(circle, #34d399 0%, rgba(52, 211, 153, 0) 70%);
        animation: floatOrb3 18s ease-in-out infinite alternate;
    }

    @keyframes floatOrb1 {
        0% { transform: translate3d(0, 0, 0) scale(1); }
        50% { transform: translate3d(80px, 90px, 0) scale(1.15); }
        100% { transform: translate3d(130px, 30px, 0) scale(0.92); }
    }

    @keyframes floatOrb2 {
        0% { transform: translate3d(0, 0, 0) scale(1); }
        50% { transform: translate3d(-90px, -70px, 0) scale(1.1); }
        100% { transform: translate3d(-50px, 110px, 0) scale(0.9); }
    }

    @keyframes floatOrb3 {
        0% { transform: translate3d(0, 0, 0) scale(1); }
        50% { transform: translate3d(60px, -50px, 0) scale(1.2); }
        100% { transform: translate3d(-70px, -20px, 0) scale(1); }
    }

    /* Floating Animated Tech Shapes & Sparkles */
    .bg-decor-svg {
        position: fixed;
        pointer-events: none;
        z-index: 0;
        opacity: 0.18;
        will-change: transform;
    }

    .decor-node-1 {
        top: 22%;
        left: 3%;
        width: 120px;
        height: 120px;
        animation: rotateDecor 30s linear infinite;
    }

    .decor-node-2 {
        bottom: 18%;
        right: 4%;
        width: 140px;
        height: 140px;
        animation: floatDecor 14s ease-in-out infinite alternate;
    }

    @keyframes rotateDecor {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes floatDecor {
        0% { transform: translateY(0) scale(1); }
        100% { transform: translateY(-25px) scale(1.1); }
    }

    /* Hero Section Card */
    .home-hero-card {
        background: var(--home-blue-grad) !important;
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -10px rgba(2, 132, 199, 0.4);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        z-index: 1;
    }

    .home-hero-card::before {
        content: '';
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
        top: -140px;
        right: -80px;
        pointer-events: none;
    }

    .home-hero-card::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.3) 0%, rgba(56, 189, 248, 0) 70%);
        bottom: -80px;
        left: 20%;
        pointer-events: none;
    }

    .hero-logo-wrap {
        width: 80px;
        height: 80px;
        background: #ffffff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        margin-bottom: 1.25rem;
        transition: transform 0.3s ease;
    }

    .hero-logo-wrap:hover {
        transform: scale(1.06) rotate(3deg);
    }

    .glass-pill {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 30px;
        font-size: 0.82rem;
    }

    /* Floating SVG Illustration */
    .hero-illustration-wrap {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: floatIllustration 6s ease-in-out infinite;
    }

    @keyframes floatIllustration {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-12px);
        }
    }

    .hero-illustration-img {
        max-width: 100%;
        height: auto;
        filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.25));
    }

    /* Stat KPI Cards */
    .stat-portal-card {
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.6);
        padding: 1.4rem 1.25rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 1rem;
        z-index: 1;
        height: 100%;
    }

    .stat-portal-card:hover {
        transform: translateY(-5px);
        background: #ffffff;
        box-shadow: 0 14px 30px rgba(2, 132, 199, 0.12);
        border-color: #bae6fd;
    }

    .stat-portal-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .stat-portal-card:hover .stat-portal-icon {
        transform: scale(1.12);
    }

    .stat-portal-number {
        font-family: 'Outfit', 'Kanit', sans-serif;
        font-size: 1.95rem;
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
    }

    .stat-portal-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Service Bento Hub Cards */
    .service-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 1.75rem 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .service-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: transparent;
        transition: background 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-7px);
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(2, 132, 199, 0.14);
        border-color: #7dd3fc;
    }

    .service-card:hover::after {
        background: var(--home-blue-grad);
    }

    .service-icon-box {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon-box {
        transform: scale(1.1) rotate(4deg);
    }

    .service-title {
        font-size: 1.22rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        transition: color 0.2s ease;
    }

    .service-card:hover .service-title {
        color: #0284c7;
    }

    .service-desc {
        color: #64748b;
        font-size: 0.88rem;
        line-height: 1.55;
        margin-bottom: 1.25rem;
    }

    .service-action-link {
        font-weight: 700;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #0284c7;
        transition: transform 0.2s ease;
    }

    .service-card:hover .service-action-link {
        transform: translateX(4px);
    }

    /* Section Headers */
    .section-header-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* Contact Banner */
    .contact-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        z-index: 1;
    }

    /* ====================================================
       📱 MOBILE COMPACT & PROPORTIONAL RESPONSIVENESS
       ==================================================== */
    @media (max-width: 768px) {
        .home-hero-card {
            padding: 1.75rem 1.25rem !important;
            border-radius: 20px !important;
            margin-bottom: 1.25rem !important;
        }

        .hero-logo-wrap {
            width: 65px !important;
            height: 65px !important;
            border-radius: 16px !important;
            padding: 8px !important;
            margin-bottom: 1rem !important;
        }

        .home-hero-card h1 {
            font-size: 1.65rem !important;
            line-height: 1.3 !important;
        }

        .home-hero-card p {
            font-size: 0.9rem !important;
            margin-bottom: 1.25rem !important;
        }

        .hero-illustration-img {
            max-height: 220px !important;
            margin-top: 0.75rem !important;
        }

        .hero-action-btn {
            width: 100% !important;
            justify-content: center !important;
            padding: 0.65rem 1.25rem !important;
            font-size: 0.9rem !important;
        }

        .glass-pill {
            font-size: 0.72rem !important;
            padding: 0.2rem 0.65rem !important;
        }

        /* 2 Columns on Mobile for Stats (Super Compact) */
        .stat-portal-card {
            padding: 0.9rem 0.75rem !important;
            border-radius: 14px !important;
            gap: 0.6rem !important;
        }

        .stat-portal-icon {
            width: 42px !important;
            height: 42px !important;
            border-radius: 12px !important;
            font-size: 1.35rem !important;
        }

        .stat-portal-number {
            font-size: 1.45rem !important;
        }

        .stat-portal-label {
            font-size: 0.72rem !important;
            line-height: 1.2 !important;
        }

        /* Compact Bento Cards on Mobile */
        .service-card {
            padding: 1.25rem 1.15rem !important;
            border-radius: 16px !important;
        }

        .service-icon-box {
            width: 46px !important;
            height: 46px !important;
            border-radius: 12px !important;
            font-size: 1.45rem !important;
            margin-bottom: 0.85rem !important;
        }

        .service-title {
            font-size: 1.05rem !important;
            margin-bottom: 0.35rem !important;
        }

        .service-desc {
            font-size: 0.82rem !important;
            margin-bottom: 0.85rem !important;
            line-height: 1.45 !important;
        }

        .section-header-title {
            font-size: 1.1rem !important;
        }

        .contact-card {
            padding: 1.25rem 1.15rem !important;
            border-radius: 16px !important;
        }

        /* Scale down orbs on mobile */
        .ambient-orb {
            filter: blur(50px) !important;
            opacity: 0.3 !important;
        }

        .orb-1 { width: 350px !important; height: 350px !important; }
        .orb-2 { width: 380px !important; height: 380px !important; }
        .orb-3 { width: 300px !important; height: 300px !important; }
    }
</style>

<!-- 🌐 Animated SVG Grid Pattern Background (Moving Grid Lattice + Pulsing Glowing Cells) -->
<div class="animated-grid-wrapper">
    <svg class="animated-grid-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Pattern Definition -->
            <pattern id="animated-grid-pattern" width="48" height="48" patternUnits="userSpaceOnUse" x="0" y="0">
                <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#0284c7" stroke-width="1" stroke-opacity="0.12" />
                <!-- Grid Intersection Points (Crosshairs / Dots) -->
                <circle cx="0" cy="0" r="1.5" fill="#0284c7" fill-opacity="0.4" />
                <circle cx="48" cy="0" r="1.5" fill="#0284c7" fill-opacity="0.4" />
                <circle cx="0" cy="48" r="1.5" fill="#0284c7" fill-opacity="0.4" />
                <circle cx="48" cy="48" r="1.5" fill="#0284c7" fill-opacity="0.4" />
            </pattern>
            
            <!-- Vignette Soft Radial Fade Mask -->
            <radialGradient id="grid-fade-mask" cx="50%" cy="40%" r="65%">
                <stop offset="0%" stop-color="#ffffff" stop-opacity="1" />
                <stop offset="60%" stop-color="#ffffff" stop-opacity="0.65" />
                <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
            </radialGradient>
            
            <mask id="grid-mask">
                <rect width="100%" height="100%" fill="url(#grid-fade-mask)" />
            </mask>
        </defs>

        <!-- Base Moving Lattice Grid using Radial Mask -->
        <rect width="100%" height="100%" fill="url(#animated-grid-pattern)" mask="url(#grid-mask)" class="grid-moving-layer" />
        
        <!-- Animated Glowing Grid Squares (Random Pulsing Cells) -->
        <g mask="url(#grid-mask)">
            <rect x="144" y="96" width="48" height="48" rx="4" fill="#38bdf8" class="pulse-tile tile-1" />
            <rect x="336" y="192" width="48" height="48" rx="4" fill="#0284c7" class="pulse-tile tile-2" />
            <rect x="528" y="96" width="48" height="48" rx="4" fill="#60a5fa" class="pulse-tile tile-3" />
            <rect x="240" y="384" width="48" height="48" rx="4" fill="#38bdf8" class="pulse-tile tile-4" />
            <rect x="720" y="288" width="48" height="48" rx="4" fill="#818cf8" class="pulse-tile tile-5" />
            <rect x="96" y="480" width="48" height="48" rx="4" fill="#34d399" class="pulse-tile tile-6" />
            <rect x="864" y="144" width="48" height="48" rx="4" fill="#38bdf8" class="pulse-tile tile-7" />
            <rect x="624" y="432" width="48" height="48" rx="4" fill="#0284c7" class="pulse-tile tile-8" />
        </g>
    </svg>
</div>

<!-- 🌌 Animated Ambient Mesh Glow Orbs in Background -->
<div class="ambient-orb orb-1"></div>
<div class="ambient-orb orb-2"></div>
<div class="ambient-orb orb-3"></div>

<!-- Floating Animated Tech Vectors -->
<svg class="bg-decor-svg decor-node-1" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <circle cx="50" cy="50" r="40" stroke="#0284c7" stroke-width="1.2" fill="none" stroke-dasharray="5 5" />
    <circle cx="50" cy="50" r="22" stroke="#38bdf8" stroke-width="1" fill="none" />
    <circle cx="50" cy="10" r="4" fill="#0284c7" />
    <circle cx="20" cy="50" r="3" fill="#6366f1" />
    <circle cx="50" cy="90" r="3.5" fill="#38bdf8" />
</svg>

<svg class="bg-decor-svg decor-node-2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <polygon points="50,10 90,30 90,70 50,90 10,70 10,30" stroke="#38bdf8" stroke-width="1.2" fill="none" stroke-dasharray="4 3"/>
    <circle cx="50" cy="50" r="14" fill="#60a5fa" opacity="0.4"/>
</svg>

<div class="container-xxl py-3 py-md-4 position-relative" style="z-index: 1;">

    <!-- Hero Header Banner (2-Column Layout with Bespoke SVG Illustration) -->
    <div class="home-hero-card position-relative">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            
            <!-- Left Column: Content & Actions -->
            <div class="col-lg-7 col-xl-7 mb-3 mb-lg-0 text-lg-start text-center">
                <div class="d-flex align-items-center justify-content-lg-start justify-content-center">
                    <div class="hero-logo-wrap">
                        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" style="width: 100%; height: auto;">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-lg-start justify-content-center gap-2 mb-2 flex-wrap">
                    <span class="glass-pill px-3 py-1">
                        <i class="bx bx-buildings me-1"></i> โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
                    </span>
                    <span class="glass-pill px-3 py-1">
                        <i class="bx bx-shield-quarter me-1"></i> กลุ่มบริหารงานบุคคล
                    </span>
                </div>

                <h1 class="fw-extrabold text-white mb-2" style="font-size: clamp(1.8rem, 4.5vw, 2.85rem); letter-spacing: -0.5px; line-height: 1.25;">
                    ระบบบริหารงานบุคคล<br>
                    <span style="font-size: 0.85em; font-weight: 600; opacity: 0.95;">(Personnel Management System)</span>
                </h1>

                <p class="text-white text-opacity-90 mb-3 mb-md-4" style="max-width: 600px; font-size: 0.98rem; line-height: 1.6; font-weight: 300;">
                    ศูนย์กลางสารสนเทศครูและบุคลากรทางการศึกษา ระบบประเมิน PA (ว 9/2564) การบันทึกเวลาทำงาน GPS และบริการสารสนเทศออนไลน์ครบวงจร
                </p>

                <div class="d-flex align-items-center justify-content-lg-start justify-content-center gap-2 gap-md-3 flex-wrap mb-3">
                    <a href="<?= base_url('LoginOfficerPersonnel') ?>" class="btn btn-white text-primary fw-bold shadow-lg px-4 py-2.5 bg-white rounded-pill d-inline-flex align-items-center gap-2 hero-action-btn" style="font-size: 0.92rem;">
                        <i class="bx bxs-lock-alt text-primary fs-5"></i> เข้าสู่ระบบเจ้าหน้าที่ / บุคลากร
                    </a>
                    <a href="<?= base_url('directory') ?>" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill d-inline-flex align-items-center gap-2 hero-action-btn" style="font-size: 0.92rem; border-width: 2px;">
                        <i class="bx bx-user-pin fs-5"></i> ทำเนียบครูและบุคลากร
                    </a>
                </div>

                <!-- Quick Highlights -->
                <div class="d-flex align-items-center justify-content-lg-start justify-content-center gap-2 gap-md-3 flex-wrap text-white text-opacity-80 small pt-2 border-top border-white border-opacity-10" style="font-size: 0.78rem;">
                    <span><i class="bx bx-check-circle text-info me-1"></i>ประเมิน PA ออนไลน์</span>
                    <span><i class="bx bx-check-circle text-info me-1"></i>บันทึกเวลา GPS</span>
                    <span><i class="bx bx-check-circle text-info me-1"></i>ยื่นใบลาอิเล็กทรอนิกส์</span>
                </div>
            </div>

            <!-- Right Column: Bespoke SVG Illustration -->
            <div class="col-lg-5 col-xl-5 text-center">
                <div class="hero-illustration-wrap">
                    <img src="<?= base_url('assets/img/illustrations/personnel_system_hero.svg') ?>" 
                         alt="ระบบบริหารงานบุคคล Personnel Management System" 
                         class="hero-illustration-img"
                         style="max-height: 360px;">
                </div>
            </div>

        </div>
    </div>

    <!-- Section 1: Personnel Overview KPI Stats (Compact 2x2 on Mobile) -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
            <h2 class="section-header-title mb-0">
                <i class="bx bx-bar-chart-alt-2 text-primary fs-4 fs-md-3"></i> ข้อมูลสถิติจำนวนบุคลากรปัจจุบัน
            </h2>
            <span class="badge bg-label-primary rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">ข้อมูลล่าสุด</span>
        </div>

        <div class="row g-2 g-md-3">
            <!-- Total -->
            <div class="col-6 col-md-6 col-xl-3">
                <div class="stat-portal-card">
                    <div class="stat-portal-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="bx bx-group"></i>
                    </div>
                    <div>
                        <div class="stat-portal-number" data-counter="<?= esc($stats['total']) ?>"><?= number_format($stats['total']) ?></div>
                        <div class="stat-portal-label">บุคลากรทั้งหมด</div>
                    </div>
                </div>
            </div>

            <!-- Executives -->
            <div class="col-6 col-md-6 col-xl-3">
                <div class="stat-portal-card">
                    <div class="stat-portal-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="bx bx-user-voice"></i>
                    </div>
                    <div>
                        <div class="stat-portal-number" data-counter="<?= esc($stats['executives']) ?>"><?= number_format($stats['executives']) ?></div>
                        <div class="stat-portal-label">คณะผู้บริหาร</div>
                    </div>
                </div>
            </div>

            <!-- Teachers -->
            <div class="col-6 col-md-6 col-xl-3">
                <div class="stat-portal-card">
                    <div class="stat-portal-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="bx bx-id-card"></i>
                    </div>
                    <div>
                        <div class="stat-portal-number" data-counter="<?= esc($stats['teachers']) ?>"><?= number_format($stats['teachers']) ?></div>
                        <div class="stat-portal-label">ครูผู้สอน</div>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="col-6 col-md-6 col-xl-3">
                <div class="stat-portal-card">
                    <div class="stat-portal-icon" style="background: #f3e8ff; color: #9333ea;">
                        <i class="bx bx-support"></i>
                    </div>
                    <div>
                        <div class="stat-portal-number" data-counter="<?= esc($stats['support']) ?>"><?= number_format($stats['support']) ?></div>
                        <div class="stat-portal-label">สายสนับสนุน</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Services & Online Hub -->
    <div class="mb-4 mb-md-5">
        <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
            <h2 class="section-header-title mb-0">
                <i class="bx bx-grid-alt text-primary fs-4 fs-md-3"></i> บริการและระบบงานสารสนเทศ
            </h2>
            <span class="text-muted small d-none d-sm-inline">เลือกบริการที่ต้องการเข้าใช้งาน</span>
        </div>

        <div class="row g-3 g-md-4">
            <!-- 1. Directory -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?= base_url('directory') ?>" class="service-card">
                    <div>
                        <div class="service-icon-box" style="background: #e0f2fe; color: #0284c7;">
                            <i class="bx bx-group"></i>
                        </div>
                        <h3 class="service-title">ทำเนียบครูและบุคลากร</h3>
                        <p class="service-desc">
                            ค้นหารายชื่อ ภาพถ่าย ข้อมูลติดต่อ และสังกัดกลุ่มสาระการเรียนรู้ของคณะครูและบุคลากรทุกคน
                        </p>
                    </div>
                    <div class="service-action-link">
                        <span>เปิดทำเนียบบุคลากร</span>
                        <i class="bx bx-right-arrow-alt fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- 2. PA Evaluation -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?= base_url('pa-login') ?>" class="service-card">
                    <div>
                        <div class="service-icon-box" style="background: #dcfce7; color: #16a34a;">
                            <i class="bx bx-task"></i>
                        </div>
                        <h3 class="service-title">ระบบประเมิน PA ออนไลน์</h3>
                        <p class="service-desc">
                            จัดทำข้อตกลงและบันทึกผลการปฏิบัติงานตามมาตรฐานตำแหน่งและประเด็นท้าทาย (PA) ตามเกณฑ์ ว 9/2564
                        </p>
                    </div>
                    <div class="service-action-link" style="color: #16a34a;">
                        <span>เข้าสู่ระบบประเมิน PA</span>
                        <i class="bx bx-right-arrow-alt fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- 3. Attendance GPS Check-in -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?= base_url('LoginOfficerPersonnel') ?>" class="service-card">
                    <div>
                        <div class="service-icon-box" style="background: #e0e7ff; color: #4f46e5;">
                            <i class="bx bx-fingerprint"></i>
                        </div>
                        <h3 class="service-title">ระบบลงเวลาปฏิบัติหน้าที่</h3>
                        <p class="service-desc">
                            บันทึกเวลาทำงาน สแกนนิ้ว และเช็คชื่อออนไลน์ผ่านระบบพิกัด GPS ประจำวัน
                        </p>
                    </div>
                    <div class="service-action-link" style="color: #4f46e5;">
                        <span>ลงชื่อปฏิบัติงาน</span>
                        <i class="bx bx-right-arrow-alt fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- 4. E-Leave Request -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?= base_url('LoginOfficerPersonnel') ?>" class="service-card">
                    <div>
                        <div class="service-icon-box" style="background: #ffedd5; color: #ea580c;">
                            <i class="bx bx-calendar-check"></i>
                        </div>
                        <h3 class="service-title">ระบบขออนุญาตลาออนไลน์</h3>
                        <p class="service-desc">
                            ยื่นคำขอลาป่วย ลากิจ ไปราชการ ตรวจสอบโควตาวันลาสะสม และติดตามผลการอนุมัติ
                        </p>
                    </div>
                    <div class="service-action-link" style="color: #ea580c;">
                        <span>ยื่นใบลาออนไลน์</span>
                        <i class="bx bx-right-arrow-alt fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- 5. Official Orders -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="https://documentcenter.skj.ac.th/category/dictation-person" target="_blank" class="service-card">
                    <div>
                        <div class="service-icon-box" style="background: #fef3c7; color: #d97706;">
                            <i class="bx bx-file-find"></i>
                        </div>
                        <h3 class="service-title">คลังคำสั่งโรงเรียน</h3>
                        <p class="service-desc">
                            ศูนย์รวมคำสั่งโรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ และเอกสารแต่งตั้งย้อนหลัง
                        </p>
                    </div>
                    <div class="service-action-link" style="color: #d97706;">
                        <span>ค้นหาคำสั่งโรงเรียน</span>
                        <i class="bx bx-link-external fs-5"></i>
                    </div>
                </a>
            </div>

            <!-- 6. Document Forms -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="https://documentcenter.skj.ac.th/category/form-person" target="_blank" class="service-card">
                    <div>
                        <div class="service-icon-box" style="background: #f3e8ff; color: #9333ea;">
                            <i class="bx bx-folder-open"></i>
                        </div>
                        <h3 class="service-title">แบบฟอร์มเอกสารบุคคล</h3>
                        <p class="service-desc">
                            ดาวน์โหลดแบบฟอร์มใบลา เอกสารขออนุญาตไปราชการ และไฟล์สารสนเทศงานบุคคล
                        </p>
                    </div>
                    <div class="service-action-link" style="color: #9333ea;">
                        <span>ดาวน์โหลดแบบฟอร์ม</span>
                        <i class="bx bx-download fs-5"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Section 3: Contact & School Office Card -->
    <div class="contact-card">
        <div class="row align-items-center g-3">
            <div class="col-lg-8 col-md-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="avatar avatar-xs bg-label-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold">
                        <i class="bx bx-map-pin"></i>
                    </div>
                    <h4 class="mb-0 fw-bold fs-6 text-dark">ติดต่อกลุ่มบริหารงานบุคคล</h4>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                    สำนักงานกลุ่มบริหารงานบุคคล อาคาร 4 โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ <br>
                    เลขที่ 160 หมู่ 1 ต.นครสวรรค์ตก อ.เมือง จ.นครสวรรค์ 60000 | <strong>โทรศัพท์:</strong> 056-200-765
                </p>
            </div>
            <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center gap-2 flex-wrap">
                <a href="https://skj.ac.th" target="_blank" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2 w-100 w-md-auto">
                    <i class="bx bx-globe"></i> เว็บไซต์หลักโรงเรียน
                </a>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animated KPI Counters
        $('[data-counter]').each(function() {
            const $this = $(this);
            const target = parseInt($this.attr('data-counter'), 10) || 0;
            if (target === 0) return;
            $({ countNum: 0 }).animate({ countNum: target }, {
                duration: 800,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum).toLocaleString());
                },
                complete: function() {
                    $this.text(target.toLocaleString());
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>