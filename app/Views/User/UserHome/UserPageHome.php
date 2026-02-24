<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --skj-blue: #0066cc;
        --skj-blue-dark: #004c99;
        --text-dark: #1a202c;
        --text-muted: #4a5568;
        --bg-color: #f8fbff; /* Changed to a very soft pastel blue */
        
        /* Pastel Colors for Cards */
        --pastel-pink-bg: #ffe4e6;
        --pastel-pink-text: #e11d48;
        --pastel-blue-bg: #e0f2fe;
        --pastel-blue-text: #0284c7;
        --pastel-orange-bg: #ffedd5;
        --pastel-orange-text: #ea580c;
        --pastel-green-bg: #dcfce7;
        --pastel-green-text: #16a34a;
    }

    body {
        font-family: 'Kanit', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-dark);
        -webkit-font-smoothing: antialiased;
        /* Subtle animated background pattern */
        background-image: radial-gradient(#e0e7ff 1px, transparent 1px);
        background-size: 20px 20px;
        animation: bgMove 60s linear infinite;
    }

    /* Keyframes for animations */
    @keyframes bgMove {
        0% { background-position: 0 0; }
        100% { background-position: 400px 400px; }
    }

    @keyframes floatLight {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    @keyframes pulseSoft {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes spinSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* --- Welcome Hero (Mobile First) --- */
    .welcome-hero {
        background: linear-gradient(135deg, var(--skj-blue) 0%, var(--skj-blue-dark) 100%);
        border-radius: 1.25rem;
        padding: 2.5rem 1.5rem;
        color: white;
        margin-bottom: 2rem;
        margin-top: 1rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 102, 204, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
    }

    /* Animated background elements in Hero */
    .hero-bg-shape {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        filter: blur(10px);
        pointer-events: none;
        animation: spinSlow 20s linear infinite;
    }

    .hero-bg-shape-2 {
        position: absolute;
        bottom: -40px;
        left: -40px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        filter: blur(15px);
        pointer-events: none;
        animation: spinSlow 25s linear infinite reverse;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .hero-logo {
        width: 70px;
        height: auto;
        margin: 0 auto 1rem auto;
        background: white;
        padding: 8px;
        border-radius: 50%;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        animation: floatLight 4s ease-in-out infinite;
    }

    .hero-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.3;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        text-align: center;
    }

    .hero-subtitle {
        font-size: 1.05rem;
        opacity: 0.95;
        font-weight: 400;
        margin: 0;
    }

    /* --- Main Grid (Mobile First) --- */
    .main-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* --- Action Cards (Mobile First Horizontal Layout) --- */
    .action-card {
        display: flex;
        flex-direction: row;
        align-items: center;
        background: white;
        border-radius: 1.25rem;
        padding: 1.25rem;
        text-decoration: none !important;
        border: 2px solid transparent;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }

    .action-card:active {
        transform: scale(0.97) !important;
    }

    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        flex-shrink: 0;
        margin-right: 1.25rem;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: inset 0 0 0 2px rgba(255,255,255,0.5);
    }

    /* Individual Card Colors */
    .card-purple .icon-box {
        background: #f3e8ff;
        color: #9333ea;
    }
    .card-orange .icon-box {
        background: var(--pastel-orange-bg);
        color: var(--pastel-orange-text);
    }
    .card-blue .icon-box {
        background: var(--pastel-blue-bg);
        color: var(--pastel-blue-text);
    }
    .card-pink .icon-box {
        background: var(--pastel-pink-bg);
        color: var(--pastel-pink-text);
    }
    .card-green .icon-box {
        background: var(--pastel-green-bg);
        color: var(--pastel-green-text);
    }

    /* Hover effects for mobile (active) and desktop */
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
    }

    .action-card:hover .icon-box {
        transform: scale(1.15) rotate(5deg);
    }
    
    .action-card:hover i.bxs-user-detail,
    .action-card:hover i.bxs-file-blank {
        animation: pulseSoft 1s infinite alternate;
    }

    .card-green:hover { border-color: #86efac; background-color: #f0fdf4; }
    .card-blue:hover { border-color: #bae6fd; background-color: #f0f9ff; }
    .card-orange:hover { border-color: #fdba74; background-color: #fffaf0; }
    .card-pink:hover { border-color: #fda4af; background-color: #fff1f2; }

    .text-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }

    .card-label {
        font-size: 1.35rem; /* ใหญ่ อ่านง่าย */
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .card-desc {
        font-size: 1rem; /* ใหญ่ อ่านง่าย */
        color: var(--text-muted);
        line-height: 1.4;
        font-weight: 400;
        margin: 0;
    }

    .arrow-icon {
        color: #cbd5e1;
        font-size: 1.8rem;
        margin-left: 0.5rem;
        transition: transform 0.3s ease;
    }

    .action-card:hover .arrow-icon {
        transform: translateX(6px);
    }

    /* --- Tablets and Desktop --- */
    @media (min-width: 768px) {
        .welcome-hero {
            padding: 3rem 2rem;
            border-radius: 1.5rem;
        }

        .hero-logo {
            width: 90px;
        }

        .hero-title {
            font-size: 2.25rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
        }

        .main-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .action-card {
            flex-direction: column;
            text-align: center;
            padding: 2.5rem 1.5rem;
        }

        .action-card:hover {
            transform: translateY(-10px);
        }

        .icon-box {
            margin-right: 0;
            margin-bottom: 1.5rem;
            width: 85px;
            height: 85px;
            font-size: 3.5rem;
            border-radius: 1.25rem;
        }

        .card-label {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .card-desc {
            font-size: 1.1rem;
        }

        .arrow-icon {
            display: none; /* ซ่อนลูกศรในโหมด desktop เพราะการจัดเรียงเปลี่ยนไป */
        }
    }

    @media (min-width: 1024px) {
        .main-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
    }
</style>

<div class="welcome-hero">
    <div class="hero-bg-shape"></div>
    <div class="hero-bg-shape-2"></div>
    <div class="hero-content">
        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="Logo" class="hero-logo">
        <h1 class="hero-title">ยินดีต้อนรับสู่ระบบงานบุคคล</h1>
        <p class="hero-subtitle">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
    </div>
</div>

<div class="main-grid">
    <!-- Directory -->
    <a href="<?= base_url('directory') ?>" class="action-card card-purple">
        <div class="icon-box">
            <i class='bx bxs-user-detail'></i>
        </div>
        <div class="text-box">
            <div class="card-label">ทำเนียบครู</div>
            <div class="card-desc">ค้นหาและดูข้อมูลบุคลากร แยกตามกลุ่มสาระฯ</div>
        </div>
        <i class='bx bx-chevron-right arrow-icon'></i>
    </a>

    <!-- PA Form -->
    <a href="<?= base_url('pa-login') ?>" class="action-card card-blue">
        <div class="icon-box">
            <i class='bx bxs-file-blank'></i>
        </div>
        <div class="text-box">
            <div class="card-label">ระบบประเมิน PA</div>
            <div class="card-desc">จัดทำและส่งแบบข้อตกลงในการพัฒนางาน</div>
        </div>
        <i class='bx bx-chevron-right arrow-icon'></i>
    </a>

    <!-- Orders -->
    <a href="https://documentcenter.skj.ac.th/category/dictation-person" target="_blank" class="action-card card-orange">
        <div class="icon-box">
            <i class='bx bxs-file-archive'></i>
        </div>
        <div class="text-box">
            <div class="card-label">คลังคำสั่ง</div>
            <div class="card-desc">ค้นหาและดาวน์โหลดคำสั่งโรงเรียน</div>
        </div>
        <i class='bx bx-chevron-right arrow-icon'></i>
    </a>

    <!-- Forms -->
    <a href="https://documentcenter.skj.ac.th/category/form-person" target="_blank" class="action-card card-green">
        <div class="icon-box">
            <i class='bx bxs-collection'></i>
        </div>
        <div class="text-box">
            <div class="card-label">แบบฟอร์มต่างๆ</div>
            <div class="card-desc">ดาวน์โหลดไฟล์เอกสารและแบบฟอร์ม</div>
        </div>
        <i class='bx bx-chevron-right arrow-icon'></i>
    </a>
</div>

<div class="py-5"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Add simple entrance animation for cards
    $('.action-card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(15px)'
        });
        
        setTimeout(() => {
            $(this).css({
                'transition': 'all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1)',
                'opacity': '1',
                'transform': 'translateY(0)'
            });
            // Reset transition after animation so hover works properly
            setTimeout(() => {
                $(this).css('transition', 'all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1)');
            }, 500);
        }, 100 * (index + 1));
    });
});
</script>
<?= $this->endSection() ?>