<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --lux-primary: #0f172a;
        --lux-accent: #3b82f6;
        --lux-gold: #d4af37;
        --lux-glass: rgba(255, 255, 255, 0.7);
        --lux-glass-dark: rgba(15, 23, 42, 0.03);
        --lux-border: rgba(255, 255, 255, 0.5);
        --lux-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --lux-glow: 0 0 20px rgba(59, 130, 246, 0.2);
    }

    /* Staggered Entrance Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 10px rgba(59, 130, 246, 0.2); }
        50% { box-shadow: 0 0 25px rgba(59, 130, 246, 0.5); }
    }

    .reveal {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .reveal-delay-1 { animation-delay: 0.1s; }
    .reveal-delay-2 { animation-delay: 0.2s; }
    .reveal-delay-3 { animation-delay: 0.3s; }
    .reveal-delay-4 { animation-delay: 0.4s; }

    /* Dashboard Container */
    .dashboard-luxury {
        padding: 0.75rem;
        perspective: 1000px;
    }

    /* Hero Banner Premium */
    .hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 32px;
        padding: 3.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
        z-index: 0;
    }

    .hero-title {
        font-weight: 800;
        font-size: clamp(2rem, 5vw, 3.5rem);
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        color: #94a3b8;
        font-size: 1.25rem;
        max-width: 600px;
        margin-bottom: 2rem;
    }

    .hero-img-replacement {
        font-size: 15rem;
        color: rgba(255, 255, 255, 0.1);
        position: absolute;
        right: -20px;
        bottom: -40px;
        transform: rotate(-15deg);
        z-index: 0;
        pointer-events: none;
        transition: all 1s ease;
    }

    .hero-banner:hover .hero-img-replacement {
        transform: rotate(0deg) scale(1.1);
        color: rgba(59, 130, 246, 0.2);
    }

    .hero-icon-main {
        font-size: 4rem;
        background: linear-gradient(135deg, var(--lux-gold) 0%, #fff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 2rem;
        filter: drop-shadow(0 0 15px rgba(212, 175, 55, 0.3));
    }

    /* Premium Stat Cards */
    .stat-card-lux {
        background: var(--lux-glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--lux-border);
        border-radius: 24px;
        padding: 1.75rem;
        height: 100%;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: var(--lux-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        position: relative;
    }

    .stat-card-lux::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .stat-card-lux:hover {
        transform: translateY(-12px) scale(1.02);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 40px 60px -15px rgba(0, 0, 0, 0.15);
        border-color: var(--lux-accent);
    }

    .stat-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.4s ease;
        box-shadow: 0 8px 16px -4px rgba(0,0,0,0.1);
    }

    .stat-card-lux:hover .stat-icon-box {
        transform: rotate(-10deg) scale(1.1) translateY(-5px);
        box-shadow: 0 15px 25px -5px rgba(0,0,0,0.15);
    }

    .stat-value-lux {
        font-family: 'Inter', sans-serif; 
        font-size: 2.75rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 0.25rem;
        letter-spacing: -1.5px;
        line-height: 1;
    }

    .stat-label-lux {
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        margin-bottom: 1.5rem;
    }

    .progress-lux {
        height: 8px;
        border-radius: 100px;
        background: #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    /* Buttons luxury */
    .btn-lux {
        border-radius: 14px;
        font-weight: 800;
        padding: 12px 24px;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-lux-primary {
        background: var(--lux-accent);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
    }

    .btn-lux-primary:hover {
        background: #2563eb;
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.5);
    }

    .btn-lux-outline {
        background: rgba(59, 130, 246, 0.05);
        color: var(--lux-accent);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .btn-lux-outline:hover {
        background: rgba(59, 130, 246, 0.1);
        border-color: var(--lux-accent);
    }

    /* Mobile Responsive Tweak */
    @media (max-width: 991.98px) {
        .hero-banner { padding: 2rem; border-radius: 20px; }
        .hero-title { font-size: 2.2rem; }
        .hero-img { margin-top: 2rem; max-width: 280px; margin-left: auto; margin-right: auto; display: block; }
        .stat-card-lux { padding: 1.25rem; }
    }

    /* Summary Overview */
    .summary-section {
        background: white;
        border-radius: 32px;
        padding: 2.5rem;
        box-shadow: var(--lux-shadow);
        border: 1px solid #f1f5f9;
        margin-top: 1rem;
    }

    .summary-header {
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }

    .lux-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .pulsing-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-glow 2s infinite;
    }
</style>

<div class="dashboard-luxury">
    <!-- Hero BannerSection -->
    <div class="reveal">
        <div class="hero-banner">
            <!-- Decorative Icon Background -->
            <i class='bx bxs-briefcase hero-img-replacement'></i>
            
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-lg-8">
                    <div class="lux-badge mb-4">
                        <span class="pulsing-dot"></span>
                        SYSTEM ACTIVE • PERSONNEL HUB V2.5
                    </div>
                    <i class='bx bxs-diamond hero-icon-main'></i>
                    <h1 class="hero-title">Welcome to<br>SKJ Personnel Hub</h1>
                    <p class="hero-subtitle">
                        ยกระดับการบริหารทรัพยากรบุคคลสู่มาตรฐานสากล 
                        รวดเร็ว แม่นยำ และโปร่งใส ด้วยเทคโนโลยีที่ออกแบบมาเพื่อคุณโดยเฉพาะ
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="btn btn-lux btn-lux-primary">
                            <i class='bx bx-user-plus'></i> เพิ่มบุคลากรใหม่
                        </a>
                        <a href="<?=base_url('Admin/SaveAttendance');?>" class="btn btn-lux btn-lux-outline">
                            <i class='bx bx-time-five'></i> ตรวจสอบการลงเวลา
                        </a>
                        <a href="<?=base_url('Admin/PaConfig');?>" class="btn btn-lux btn-lux-outline">
                            <i class='bx bx-bar-chart-alt-2'></i> จัดการระบบ PA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6 reveal reveal-delay-1">
            <div class="stat-card-lux">
                <div>
                    <div class="stat-icon-box bg-primary bg-opacity-10 text-primary">
                        <i class='bx bx-id-card'></i>
                    </div>
                    <div class="stat-label-lux">บุคลากรในสังกัด</div>
                    <div class="stat-value-lux"><?=number_format($countAllPersonnel);?></div>
                </div>
                <div>
                    <div class="progress-lux">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Update: Realtime</span>
                        <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="text-primary fw-bold small text-decoration-none">View All →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 reveal reveal-delay-2">
            <div class="stat-card-lux">
                <div>
                    <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                        <i class='bx bx-check-shield'></i>
                    </div>
                    <div class="stat-label-lux">มาปฏิบัติงานวันนี้</div>
                    <div class="stat-value-lux"><?=number_format($countAttendanceToday);?></div>
                </div>
                <div>
                    <div class="progress-lux">
                        <div class="progress-bar bg-success" style="width: <?= ($countAllPersonnel > 0) ? ($countAttendanceToday/$countAllPersonnel*100) : 0 ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Rate: <?= ($countAllPersonnel > 0) ? round($countAttendanceToday/$countAllPersonnel*100, 1) : 0 ?>%</span>
                        <a href="<?=base_url('Admin/SaveAttendance');?>" class="text-success fw-bold small text-decoration-none">Insights →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 reveal reveal-delay-3">
            <div class="stat-card-lux">
                <div>
                    <div class="stat-icon-box bg-info bg-opacity-10 text-info">
                        <i class='bx bx-edit-alt'></i>
                    </div>
                    <div class="stat-label-lux">รอการประเมิน PA</div>
                    <div class="stat-value-lux"><?=number_format($countPendingEvaluations);?></div>
                </div>
                <div>
                    <div class="progress-lux">
                        <div class="progress-bar bg-info" style="width: 70%"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">FY: 2568 (Current)</span>
                        <a href="<?=base_url('Admin/PaConfig');?>" class="text-info fw-bold small text-decoration-none">Track →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 reveal reveal-delay-4">
            <div class="stat-card-lux">
                <div>
                    <div class="stat-icon-box bg-warning bg-opacity-10 text-warning">
                        <i class='bx bx-lock-alt'></i>
                    </div>
                    <div class="stat-label-lux">สิทธิ์ผู้ดูแลระบบ</div>
                    <div class="stat-value-lux"><?=number_format($countTotalUsers);?></div>
                </div>
                <div>
                    <div class="progress-lux">
                        <div class="progress-bar bg-warning" style="width: 100%"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Access Control</span>
                        <a href="<?=base_url('Admin/Rloes/Setting');?>" class="text-warning fw-bold small text-decoration-none">Security →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Overview Section -->
    <div class="summary-section reveal reveal-delay-4">
        <div class="summary-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-900 text-dark mb-1">สรุปการทำงานรายวัน</h4>
                <p class="text-muted mb-0">Daily System & Logistics Overview</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-light btn-sm rounded-3 px-3 py-2" data-bs-toggle="dropdown">
                    <i class='bx bx-dots-vertical-rounded'></i> Option
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                    <li><a class="dropdown-item py-2" href="#"><i class='bx bx-export me-2'></i> Export PDF</a></li>
                    <li><a class="dropdown-item py-2" href="#"><i class='bx bx-printer me-2'></i> Print Report</a></li>
                </ul>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-3 col-6 text-center text-lg-start border-end border-light">
                <p class="text-muted small fw-bold text-uppercase mb-2">Personnel</p>
                <h3 class="fw-800 mb-1"><?=number_format($countAllPersonnel);?></h3>
                <span class="badge bg-soft-primary text-primary rounded-pill px-3">Active Data</span>
            </div>
            <div class="col-lg-3 col-6 text-center text-lg-start border-end border-light">
                <p class="text-muted small fw-bold text-uppercase mb-2">Today Status</p>
                <h3 class="fw-800 mb-1"><?= ($countAllPersonnel > 0) ? round($countAttendanceToday/$countAllPersonnel*100, 1) : 0 ?>%</h3>
                <span class="badge bg-soft-success text-success rounded-pill px-3">+2.5% from Avg.</span>
            </div>
            <div class="col-lg-3 col-6 text-center text-lg-start border-end border-light">
                <p class="text-muted small fw-bold text-uppercase mb-2">Evaluations</p>
                <h3 class="fw-800 mb-1"><?=number_format($countPendingEvaluations);?></h3>
                <span class="badge bg-soft-info text-info rounded-pill px-3">Pending Tasks</span>
            </div>
            <div class="col-lg-3 col-6 text-center text-lg-start">
                <p class="text-muted small fw-bold text-uppercase mb-2">Server Load</p>
                <h3 class="fw-800 mb-1">99.9%</h3>
                <span class="badge bg-soft-warning text-warning rounded-pill px-3">Stable</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Utility badge colors */
    .bg-soft-primary { background-color: rgba(59, 130, 246, 0.1); }
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    
    .fw-800 { font-weight: 800; }
    .fw-900 { font-weight: 900; }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple Intersection Observer to trigger animations on scroll
        const reveals = document.querySelectorAll('.reveal');
        
        const observerOptions = {
            threshold: 0.1
        };

        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    // The animation-name is already set in the CSS class, 
                    // we just need to ensure opacity starts correctly if needed.
                    // But our CSS already handles opacity: 0 and forwards.
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        reveals.forEach(el => {
            revealObserver.observe(el);
        });
    });
</script>
<?= $this->endSection() ?>
