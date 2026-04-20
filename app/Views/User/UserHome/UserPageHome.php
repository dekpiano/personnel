<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Modern Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Kanit:wght@200;400;500;600&display=swap" rel="stylesheet">

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
        from { transform: translate(-15%, -15%); }
        to { transform: translate(25%, 25%); }
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
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        margin-bottom: 1.5rem;
        animation: floatHero 6s ease-in-out infinite;
        z-index: 2;
    }

    @keyframes floatHero {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(3deg); }
    }

    .hero-title {
        font-family: 'Outfit', 'Kanit', sans-serif;
        font-size: clamp(1.8rem, 4.5vw, 2.8rem);
        font-weight: 700;
        letter-spacing: -0.01em;
        margin-bottom: 0.75rem;
        z-index: 2;
        color: #ffffff;
        text-shadow: 0 2px 15px rgba(0,0,0,0.3); /* Stronger shadow for better visibility on light blue */
        background: rgba(0, 0, 0, 0.05); /* Very subtle backdrop for the text */
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
        text-shadow: 0 1px 5px rgba(0,0,0,0.2);
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
    }

    .glass-card:hover::after {
        opacity: 1;
    }

    /* Card sizing for Bento effect */
    .card-large { grid-column: span 2; grid-row: span 1; }
    .card-small { grid-column: span 1; grid-row: span 1; }

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
    .bg-blue-soft { background: #eff6ff; color: #1e40af; }
    .bg-cyan-soft { background: #ecfeff; color: #0891b2; }
    .bg-sky-soft { background: #f0f9ff; color: #0369a1; }
    .bg-indigo-soft { background: #eef2ff; color: #3730a3; }

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
        .bento-grid { grid-template-columns: repeat(2, 1fr); }
        .hero-glass { grid-column: span 2; }
    }

    @media (max-width: 640px) {
        .bento-grid { grid-template-columns: 1fr; }
        .hero-glass { grid-column: span 1; padding: 3rem 1.5rem; }
        .card-large { grid-column: span 1; }
        .glass-card { padding: 1.75rem; }
        .hero-title { font-size: 2.2rem; }
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
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
        z-index: 3;
    }

    .btn-premium:hover {
        transform: scale(1.1) translateY(-3px);
        color: #1d4ed8;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    }
</style>

<div class="shape-blob" style="top: 5%; right: 5%;"></div>
<div class="shape-blob" style="bottom: 5%; left: 5%; background: var(--accent-cyan);"></div>

<div class="container-xxl">
    <div class="bento-grid">
        <!-- Hero Section - CSS Animated -->
        <div class="hero-glass">
            <div class="hero-logo-box">
                <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" style="width: 100%; height: auto;">
            </div>
            <h1 class="hero-title">ยินดีต้อนรับสู่ระบบงานบุคคล</h1>
            <p class="hero-desc">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
            
            <a href="<?= base_url('LoginOfficerPersonnel') ?>" class="btn-premium no-decoration">
                <i class='bx bxs-lock-alt'></i> เข้าสู่ระบบ
            </a>
        </div>

        <!-- Directory -->
        <a href="<?= base_url('directory') ?>" class="glass-card card-large reveal reveal-delay-1">
            <div>
                <div class="card-icon bg-blue-soft">
                    <i class='bx bx-group'></i>
                </div>
                <h3 class="card-title">ทำเนียบครูและบุคลากร</h3>
                <p class="card-text">ค้นหารายชื่อและข้อมูลพื้นฐานของบุคลากรภายในโรงเรียน แยกตามกลุ่มสาระการเรียนรู้และฝ่ายบริหาร</p>
            </div>
            <div class="mt-4 text-end">
                <span class="fw-bold" style="color: #1e40af;">สำรวจข้อมูล <i class='bx bx-right-arrow-alt'></i></span>
            </div>
        </a>

        <!-- PA Form -->
        <a href="<?= base_url('pa-login') ?>" class="glass-card card-small reveal reveal-delay-2">
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
        <a href="https://documentcenter.skj.ac.th/category/dictation-person" target="_blank" class="glass-card card-small reveal reveal-delay-3">
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
        <a href="https://documentcenter.skj.ac.th/category/form-person" target="_blank" class="glass-card card-large reveal reveal-delay-4">
            <div>
                <div class="card-icon bg-indigo-soft">
                    <i class='bx bx-folder-open'></i>
                </div>
                <h3 class="card-title">แบบฟอร์มเอกสารบุคคล</h3>
                <p class="card-text">ดาวน์โหลดแบบฟอร์มใบลา, เอกสารการขออนุญาตต่างๆ และไฟล์สารสนเทศบุคลากรสำหรับนำไปใช้ภายนอก</p>
            </div>
            <div class="mt-4 text-end">
                <span class="fw-bold" style="color: #3730a3;">ดาวน์โหลด <i class='bx bx-download'></i></span>
            </div>
        </a>
    </div>
</div>

<div class="py-5"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                if(rect.top < window.innerHeight - 100) {
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