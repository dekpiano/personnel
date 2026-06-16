<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Modern Fonts -->
<link
    href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Kanit:wght@200;400;500;600&display=swap"
    rel="stylesheet">

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --primary-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60efff 100%);
        --accent-blue: #2563eb;
        --accent-cyan: #06b6d4;
        --text-slate: #0f172a;
    }

    body {
        background: radial-gradient(circle at top right, #f8faff 0%, #e0f2fe 100%);
        font-family: 'Kanit', sans-serif;
        color: var(--text-slate);
        overflow-x: hidden;
    }

    /* Floating shapes for visual interest */
    .shape-blob {
        position: fixed;
        width: 600px;
        height: 600px;
        background: var(--primary-gradient);
        filter: blur(100px);
        opacity: 0.12;
        border-radius: 50%;
        z-index: -1;
        animation: move 30s infinite alternate;
    }

    @keyframes move {
        from {
            transform: translate(-15%, -15%);
        }

        to {
            transform: translate(25%, 25%);
        }
    }

    .bento-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto;
        gap: 1.25rem;
        margin-top: 1.5rem;
    }

    .hero-glass {
        grid-column: span 4;
        background: var(--primary-gradient);
        border-radius: 30px;
        padding: 3rem 1.5rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(30, 64, 175, 0.3);
        color: white;
        margin-bottom: 0px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .hero-glass::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.6;
    }

    .hero-logo-box {
        width: 85px;
        height: 85px;
        background: white;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        margin-bottom: 1.5rem;
        animation: floatHero 6s ease-in-out infinite;
        z-index: 2;
    }

    @keyframes floatHero {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(3deg);
        }
    }

    .hero-title {
        font-family: 'Outfit', 'Kanit', sans-serif;
        font-size: clamp(1.8rem, 4.5vw, 2.8rem);
        font-weight: 700;
        letter-spacing: -0.01em;
        margin-bottom: 0.75rem;
        z-index: 2;
        color: #ffffff;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        /* Stronger shadow for better visibility on light blue */
        background: rgba(0, 0, 0, 0.05);
        /* Very subtle backdrop for the text */
        padding: 5px 15px;
        border-radius: 12px;
        backdrop-filter: blur(4px);
    }

    .hero-desc {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.95);
        max-width: 600px;
        font-weight: 300;
        z-index: 2;
        line-height: 1.5;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    }

    /* Glass Cards - Elite Blue */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 1.75rem;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .glass-card:hover {
        transform: translateY(-12px) scale(1.03);
        background: rgba(255, 255, 255, 0.85);
        box-shadow: 0 40px 80px -15px rgba(30, 64, 175, 0.15);
        border-color: #3b82f6;
    }

    .glass-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s;
        pointer-events: none;
    }

    .glass-card:hover::after {
        opacity: 1;
    }

    /* Card sizing for Bento effect */
    .card-large {
        grid-column: span 2;
        grid-row: span 1;
    }

    .card-small {
        grid-column: span 1;
        grid-row: span 1;
    }

    .card-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        margin-bottom: 1.25rem;
        transition: all 0.4s;
    }

    .glass-card:hover .card-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Card variations with Blue accents */
    .bg-blue-soft {
        background: #eff6ff;
        color: #1e40af;
    }

    .bg-cyan-soft {
        background: #ecfeff;
        color: #0891b2;
    }

    .bg-sky-soft {
        background: #f0f9ff;
        color: #0369a1;
    }

    .bg-indigo-soft {
        background: #eef2ff;
        color: #3730a3;
    }

    .card-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.6rem;
    }

    .card-text {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Mobile Responsive */
    @media (max-width: 1024px) {
        .bento-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .hero-glass {
            grid-column: span 2;
        }
    }

    @media (max-width: 640px) {
        .bento-grid {
            grid-template-columns: 1fr;
        }

        .hero-glass {
            grid-column: span 1;
            padding: 3rem 1.5rem;
        }

        .card-large {
            grid-column: span 1;
        }

        .glass-card {
            padding: 1.75rem;
        }

        .hero-title {
            font-size: 2.2rem;
        }
    }

    /* Login Button - Azure Elite */
    .btn-premium {
        background: white;
        color: #1e40af;
        padding: 10px 32px;
        border-radius: 100px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 1.5rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        z-index: 3;
    }

    .btn-premium:hover {
        transform: scale(1.1) translateY(-3px);
        color: #1d4ed8;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }

    /* Stats Section */
    .stats-container {
        grid-column: span 4;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 1.5rem 1.25rem;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 8px 32px 0 rgba(30, 64, 175, 0.04);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.8);
        border-color: #3b82f6;
        box-shadow: 0 15px 35px rgba(30, 64, 175, 0.1);
    }

    .stat-icon {
        font-size: 1.75rem;
        color: #2563eb;
        margin-bottom: 0.5rem;
        opacity: 0.85;
    }

    .stat-number {
        font-family: 'Outfit', 'Kanit', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
    }

    .grid-title-row {
        grid-column: span 4;
        margin-top: 1.5rem;
        margin-bottom: -0.25rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        font-size: 1.5rem;
        color: #2563eb;
    }

    /* Floating SVG backgrounds */
    .bg-svg-decor {
        position: fixed;
        z-index: -1;
        opacity: 0.18;
        pointer-events: none;
    }

    .decor-1 {
        top: 18%;
        left: 2%;
        width: 140px;
        height: 140px;
        animation: float-slow 15s ease-in-out infinite alternate;
    }

    .decor-2 {
        top: 48%;
        right: 3%;
        width: 160px;
        height: 160px;
        animation: rotate-slow 28s linear infinite;
    }

    .decor-3 {
        bottom: 12%;
        left: 4%;
        width: 120px;
        height: 120px;
        animation: float-mid 14s ease-in-out infinite alternate-reverse;
    }

    .decor-4 {
        top: 35%;
        left: 48%;
        width: 100px;
        height: 100px;
        opacity: 0.08;
        animation: float-pulse 9s ease-in-out infinite;
    }

    @keyframes float-slow {
        0% {
            transform: translateY(0) rotate(0deg) scale(1);
        }

        100% {
            transform: translateY(-30px) rotate(45deg) scale(1.15);
        }
    }

    @keyframes rotate-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes float-mid {
        0% {
            transform: translateY(0) translateX(0) scale(1);
        }

        100% {
            transform: translateY(25px) translateX(20px) scale(0.85);
        }
    }

    @keyframes float-pulse {

        0%,
        100% {
            transform: scale(1) translateY(0);
            opacity: 0.06;
        }

        50% {
            transform: scale(1.25) translateY(-20px);
            opacity: 0.12;
        }
    }
</style>

<!-- Animated Background SVGs -->
<!-- Decor 1: Concentric Rings & Orbiting Nodes (Top Left) -->
<svg class="bg-svg-decor decor-1" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <circle cx="50" cy="50" r="40" stroke="#3b82f6" stroke-width="0.75" fill="none" stroke-dasharray="4 4" />
    <circle cx="50" cy="50" r="25" stroke="#2563eb" stroke-width="1" fill="none" />
    <circle cx="50" cy="50" r="10" stroke="#60efff" stroke-width="1.5" fill="none" stroke-dasharray="2 1" />
    <circle cx="50" cy="10" r="3" fill="#3b82f6" />
    <circle cx="25" cy="50" r="4" fill="#06b6d4" />
    <circle cx="50" cy="75" r="2" fill="#6366f1" />
</svg>

<!-- Decor 2: Tech Gear / Rotating Hexagon Polygon (Middle Right) -->
<svg class="bg-svg-decor decor-2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <path d="M50 5 L89 27.5 L89 72.5 L50 95 L11 72.5 L11 27.5 Z" stroke="#3b82f6" stroke-width="1.2" fill="none" />
    <path d="M50 15 L80 32.5 L80 67.5 L50 85 L20 67.5 L20 32.5 Z" stroke="#06b6d4" stroke-width="0.8" fill="none"
        stroke-dasharray="3 3" />
    <circle cx="50" cy="50" r="15" stroke="#6366f1" stroke-width="1" fill="none" />
    <line x1="50" y1="5" x2="50" y2="95" stroke="#3b82f6" stroke-width="0.5" stroke-dasharray="2 2" />
    <line x1="11" y1="27.5" x2="89" y2="72.5" stroke="#3b82f6" stroke-width="0.5" stroke-dasharray="2 2" />
    <line x1="11" y1="72.5" x2="89" y2="27.5" stroke="#3b82f6" stroke-width="0.5" stroke-dasharray="2 2" />
</svg>

<!-- Decor 3: 3D-Like Floating Sphere Grid (Bottom Left) -->
<svg class="bg-svg-decor decor-3" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <path d="M20,50 Q50,20 80,50 Q50,80 20,50" fill="none" stroke="#2563eb" stroke-width="1" />
    <path d="M50,20 Q20,50 50,80 Q80,50 50,20" fill="none" stroke="#06b6d4" stroke-width="1" />
    <ellipse cx="50" cy="50" rx="30" ry="10" fill="none" stroke="#6366f1" stroke-width="0.75" />
    <ellipse cx="50" cy="50" rx="10" ry="30" fill="none" stroke="#3b82f6" stroke-width="0.75" stroke-dasharray="3 2" />
    <circle cx="50" cy="50" r="5" fill="#60efff" />
</svg>

<!-- Decor 4: Tech Dot Matrix Grid (Center Floating) -->
<svg class="bg-svg-decor decor-4" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <circle cx="10" cy="10" r="2.5" fill="#3b82f6" />
    <circle cx="30" cy="10" r="2.5" fill="#3b82f6" />
    <circle cx="50" cy="10" r="2.5" fill="#3b82f6" />
    <circle cx="70" cy="10" r="2.5" fill="#3b82f6" />
    <circle cx="90" cy="10" r="2.5" fill="#3b82f6" />

    <circle cx="10" cy="30" r="2.5" fill="#3b82f6" />
    <circle cx="30" cy="30" r="1.5" fill="#2563eb" />
    <circle cx="50" cy="30" r="1.5" fill="#2563eb" />
    <circle cx="70" cy="30" r="1.5" fill="#2563eb" />
    <circle cx="90" cy="30" r="2.5" fill="#3b82f6" />

    <circle cx="10" cy="50" r="2.5" fill="#3b82f6" />
    <circle cx="30" cy="50" r="1.5" fill="#2563eb" />
    <circle cx="50" cy="50" r="3.5" fill="#06b6d4" />
    <circle cx="70" cy="50" r="1.5" fill="#2563eb" />
    <circle cx="90" cy="50" r="2.5" fill="#3b82f6" />

    <circle cx="10" cy="70" r="2.5" fill="#3b82f6" />
    <circle cx="30" cy="70" r="1.5" fill="#2563eb" />
    <circle cx="50" cy="70" r="1.5" fill="#2563eb" />
    <circle cx="70" cy="70" r="1.5" fill="#2563eb" />
    <circle cx="90" cy="70" r="2.5" fill="#3b82f6" />

    <circle cx="10" cy="90" r="2.5" fill="#3b82f6" />
    <circle cx="30" cy="90" r="2.5" fill="#3b82f6" />
    <circle cx="50" cy="90" r="2.5" fill="#3b82f6" />
    <circle cx="70" cy="90" r="2.5" fill="#3b82f6" />
    <circle cx="90" cy="90" r="2.5" fill="#3b82f6" />
</svg>

<div class="shape-blob" style="top: 5%; right: 5%;"></div>
<div class="shape-blob" style="bottom: 5%; left: 5%; background: var(--accent-cyan);"></div>

<div class="container-xxl">
    <div class="bento-grid">
        <!-- Hero Section - CSS Animated -->
        <div class="hero-glass">
            <div class="hero-logo-box">
                <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo"
                    style="width: 100%; height: auto;">
            </div>
            <h1 class="hero-title">ระบบบริหารงานบุคคล</h1>
            <p class="hero-desc mb-3" style="font-size: 1.2rem; font-weight: 500;">โรงเรียนสวนกุหลาบวิทยาลัย
                (จิรประวัติ) นครสวรรค์</p>

            <a href="<?= base_url('LoginOfficerPersonnel') ?>" class="btn-premium no-decoration">
                <i class='bx bxs-lock-alt'></i> เข้าสู่ระบบจัดการข้อมูล
            </a>
        </div>

        <!-- Section Title: Stats -->
        <div class="grid-title-row reveal reveal-delay-1">
            <h2 class="section-title">
                <i class='bx bx-bar-chart-alt-2'></i> ข้อมูลสถิติจำนวนบุคลากรปัจจุบัน
            </h2>
        </div>

        <!-- Stats Container Grid -->
        <div class="stats-container reveal reveal-delay-1">
            <div class="stat-card">
                <i class='bx bx-group stat-icon'></i>
                <div class="stat-number"><?= esc($stats['total']) ?></div>
                <div class="stat-label">บุคลากรทั้งหมด</div>
            </div>
            <div class="stat-card">
                <i class='bx bx-user-voice stat-icon'></i>
                <div class="stat-number"><?= esc($stats['executives']) ?></div>
                <div class="stat-label">คณะผู้บริหาร</div>
            </div>
            <div class="stat-card">
                <i class='bx bx-id-card stat-icon'></i>
                <div class="stat-number"><?= esc($stats['teachers']) ?></div>
                <div class="stat-label">ข้าราชการครู / ครูผู้สอน</div>
            </div>
            <div class="stat-card">
                <i class='bx bx-support stat-icon'></i>
                <div class="stat-number"><?= esc($stats['support']) ?></div>
                <div class="stat-label">บุคลากรสายสนับสนุน</div>
            </div>
        </div>

        <!-- Section Title: Menu -->
        <div class="grid-title-row reveal reveal-delay-2">
            <h2 class="section-title">
                <i class='bx bx-grid-alt'></i> บริการและระบบงานต่างๆ
            </h2>
        </div>

        <!-- Directory -->
        <a href="<?= base_url('directory') ?>" class="glass-card card-large reveal reveal-delay-2">
            <div>
                <div class="card-icon bg-blue-soft">
                    <i class='bx bx-group'></i>
                </div>
                <h3 class="card-title">ทำเนียบครูและบุคลากร</h3>
                <p class="card-text">ค้นหารายชื่อและข้อมูลพื้นฐานของบุคลากรภายในโรงเรียน
                    แยกตามกลุ่มสาระการเรียนรู้และฝ่ายบริหาร</p>
            </div>
            <div class="mt-4 text-end">
                <span class="fw-bold" style="color: #1e40af;">สำรวจข้อมูล <i class='bx bx-right-arrow-alt'></i></span>
            </div>
        </a>

        <!-- PA Form -->
        <a href="<?= base_url('pa-login') ?>" class="glass-card card-small reveal reveal-delay-3">
            <div>
                <div class="card-icon bg-cyan-soft">
                    <i class='bx bx-task'></i>
                </div>
                <h3 class="card-title">ระบบประเมิน PA</h3>
                <p class="card-text">จัดทำข้อตกลงและพัฒนางานออนไลน์</p>
            </div>
            <div class="mt-3">
                <span class="badge bg-soft-cyan" style="background: #cffafe; color: #0891b2;">Smart System</span>
            </div>
        </a>

        <!-- Booking -->
        <a href="https://documentcenter.skj.ac.th/category/dictation-person" target="_blank"
            class="glass-card card-small reveal reveal-delay-3">
            <div>
                <div class="card-icon bg-sky-soft">
                    <i class='bx bx-file-find'></i>
                </div>
                <h3 class="card-title">คลังคำสั่ง</h3>
                <p class="card-text">รวบรวมคำสั่งโรงเรียนย้อนหลัง</p>
            </div>
            <div class="mt-3">
                <i class='bx bx-link-external text-muted'></i>
            </div>
        </a>

        <!-- Forms -->
        <a href="https://documentcenter.skj.ac.th/category/form-person" target="_blank"
            class="glass-card card-large reveal reveal-delay-4">
            <div>
                <div class="card-icon bg-indigo-soft">
                    <i class='bx bx-folder-open'></i>
                </div>
                <h3 class="card-title">แบบฟอร์มเอกสารบุคคล</h3>
                <p class="card-text">ดาวน์โหลดแบบฟอร์มใบลา, เอกสารการขออนุญาตต่างๆ
                    และไฟล์สารสนเทศบุคลากรสำหรับนำไปใช้ภายนอก</p>
            </div>
            <div class="mt-4 text-end">
                <span class="fw-bold" style="color: #3730a3;">ดาวน์โหลด <i class='bx bx-download'></i></span>
            </div>
        </a>

        <!-- Contact/Info Banner -->
        <div class="glass-card card-large reveal reveal-delay-5"
            style="grid-column: span 4; background: rgba(255, 255, 255, 0.4); padding: 1.5rem; margin-top: 1rem; border-style: dashed;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2" style="font-weight: 600; color: #1e293b; font-size: 1.1rem;"><i
                            class='bx bx-info-circle text-primary me-2'></i>ติดต่อ กลุ่มบริหารงานบุคคล</h4>
                    <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                        สำนักงานกลุ่มบริหารงานบุคคล อาคาร 4 โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ <br>
                        เลขที่ 160 หมู่ 1 ต.นครสวรรค์ตก อ.เมือง จ.นครสวรรค์ 60000 | โทรศัพท์: 056-200-765
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="https://skj.ac.th" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class='bx bx-globe me-1'></i> เว็บไซต์โรงเรียน
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reveals = document.querySelectorAll('.reveal');

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });

        reveals.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.22, 1, 0.36, 1)';
            revealObserver.observe(el);
        });

        // Simple helper to add active class
        window.addEventListener('scroll', () => {
            reveals.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }
            });
        });
        // Trigger once for initial view
        window.dispatchEvent(new Event('scroll'));
    });
</script>
<?= $this->endSection() ?>