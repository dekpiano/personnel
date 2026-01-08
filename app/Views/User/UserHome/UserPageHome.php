<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --skj-blue: #007bff;
        --skj-blue-dark: #0056b3;
    }

    body {
        font-family: 'Kanit', sans-serif;
        background-color: #f5f5f9;
        color: #566a7f;
    }

    /* --- Welcome Hero --- */
    .welcome-hero {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border-radius: 2rem;
        padding: 4rem 2rem;
        color: white;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.25);
    }

    .welcome-hero::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(40px);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 300;
    }

    /* --- Quick Action Cards --- */
    .action-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        height: 100%;
        border: 1px solid #f0f2f4;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-align: center;
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .action-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        border-color: var(--skj-blue);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        background: #f0f7ff;
        color: var(--skj-blue);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .action-card:hover .icon-box {
        background: var(--skj-blue);
        color: white;
        transform: scale(1.1) rotate(5deg);
    }

    .card-label {
        font-size: 1.15rem;
        font-weight: 700;
        color: #32475c;
        margin-bottom: 0.5rem;
    }

    .card-desc {
        font-size: 0.85rem;
        color: #8e94a9;
        line-height: 1.5;
    }

    /* --- Grid Layout --- */
    .main-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }

    @media (max-width: 1200px) {
        .main-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 576px) {
        .welcome-hero { 
            padding: 2rem 1rem; 
            margin-bottom: 1.5rem; 
            border-radius: 1.5rem;
        }
        .hero-title { font-size: 1.5rem; }
        .hero-subtitle { font-size: 0.85rem; }
        .hero-content img { width: 60px; margin-bottom: 0.75rem !important; }

        .main-grid { 
            grid-template-columns: repeat(2, 1fr); 
            gap: 0.6rem; 
        }
        .action-card { 
            padding: 1rem 0.5rem; 
            border-radius: 1rem;
        }
        .icon-box { 
            width: 45px; 
            height: 45px; 
            font-size: 1.25rem; 
            margin-bottom: 0.75rem; 
            border-radius: 0.85rem;
        }
        .card-label { font-size: 0.85rem; margin-bottom: 0.2rem; }
        .card-desc { display: none; } /* ซ่อนคำอธิบายเพื่อความกระชับที่สุด */
    }
</style>

<div class="welcome-hero text-center">
    <div class="hero-content">
        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="Logo" width="80" class="mb-4">
        <h1 class="hero-title text-white">ยินดีต้อนรับสู่ระบบงานบุคคล</h1>
        <p class="hero-subtitle">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
    </div>
</div>

<div class="main-grid">
    <!-- Directory -->
    <a href="<?= base_url('directory') ?>" class="action-card">
        <div class="icon-box">
            <i class='bx bxs-graduation'></i>
        </div>
        <div class="card-label">ทำเนียบครู</div>
        <div class="card-desc">เข้าชมข้อมูลบุคลากร แยกตามกลุ่มสาระการเรียนรู้</div>
    </a>

    <!-- PA Form -->
    <a href="<?= base_url('pa-login') ?>" class="action-card">
        <div class="icon-box">
            <i class='bx bxs-file-blank'></i>
        </div>
        <div class="card-label">ระบบประเมิน PA</div>
        <div class="card-desc">จัดทำและส่งแบบข้อตกลงในการพัฒนางาน (PA)</div>
    </a>

    <!-- Orders -->
    <a href="https://documentcenter.skj.ac.th/category/dictation-person" target="_blank" class="action-card">
        <div class="icon-box">
            <i class='bx bxs-receipt'></i>
        </div>
        <div class="card-label">คลังคำสั่ง</div>
        <div class="card-desc">ดาวน์โหลดคำสั่งโรงเรียน เกี่ยวกับงานบุคลากร</div>
    </a>

    <!-- Forms -->
    <a href="https://documentcenter.skj.ac.th/category/form-person" target="_blank" class="action-card">
        <div class="icon-box">
            <i class='bx bxs-collection'></i>
        </div>
        <div class="card-label">แบบฟอร์มต่างๆ</div>
        <div class="card-desc">ดาวน์โหลดไฟล์เอกสารและแบบฟอร์มที่เกี่ยวข้อง</div>
    </a>
</div>

<div class="py-5"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Reveal animation
    $('.action-card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(100 * index).animate({
            'opacity': '1',
            'transform': 'translateY(0)'
        }, 600);
    });
});
</script>
<?= $this->endSection() ?>