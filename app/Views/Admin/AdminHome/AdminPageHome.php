<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.9);
        --glass-border: rgba(255, 255, 255, 0.4);
        --brand-primary: #007bff;
        --brand-success: #1cc88a;
        --brand-info: #36b9cc;
        --brand-warning: #f6c23e;
        --shadow-premium: 0 15px 35px rgba(0, 0, 0, 0.05), 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .dashboard-container {
        padding: 0.5rem;
        background: transparent;
    }

    /* Welcome Banner Premium */
    .welcome-banner-premium {
        background: linear-gradient(135deg, #007bff 0%, #66b0ff 100%);
        border-radius: 24px;
        padding: 2.5rem;
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 123, 255, 0.2);
        margin-bottom: 2rem;
    }

    .welcome-banner-premium::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .welcome-img {
        position: absolute;
        right: 40px;
        bottom: -20px;
        height: 110%;
        z-index: 1;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .welcome-banner-premium:hover .welcome-img {
        transform: scale(1.05) translateY(-5px);
    }

    /* Premium Stat Cards */
    .premium-stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-premium);
        position: relative;
        overflow: hidden;
    }

    .premium-stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }

    .premium-stat-card:hover .icon-wrapper {
        transform: rotate(10deg) scale(1.1);
    }

    .gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; }
    .gradient-success { background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%); color: white; }
    .gradient-info { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); color: white; }
    .gradient-warning { background: linear-gradient(135deg, #f09819 0%, #edde5d 100%); color: white; }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
        letter-spacing: -1px;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
    }

    .progress-premium {
        height: 6px;
        border-radius: 10px;
        background: rgba(0,0,0,0.05);
        margin: 1.2rem 0;
    }

    .btn-action-premium {
        border-radius: 12px;
        font-weight: 700;
        padding: 10px 20px;
        font-size: 0.85rem;
        transition: all 0.3s;
        border: none;
    }

    .btn-action-premium.primary { background: rgba(0, 123, 255, 0.1); color: #007bff; }
    .btn-action-premium.success { background: rgba(0, 176, 155, 0.1); color: #00b09b; }
    .btn-action-premium.info { background: rgba(33, 147, 176, 0.1); color: #2193b0; }
    .btn-action-premium.warning { background: rgba(240, 152, 25, 0.1); color: #f09819; }

    .btn-action-premium:hover {
        transform: scale(1.02);
        opacity: 0.9;
    }

    /* Sub-stats Overview */
    .overview-card {
        background: white;
        border-radius: 24px;
        border: none;
        box-shadow: var(--shadow-premium);
    }

    .overview-item {
        padding: 1.5rem;
        border-radius: 18px;
        transition: all 0.3s;
    }

    .overview-item:hover {
        background: #f8faff;
    }

    .overview-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .section-title-premium {
        font-weight: 800;
        color: #2d3436;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title-premium::before {
        content: '';
        width: 4px;
        height: 24px;
        background: var(--brand-primary);
        border-radius: 10px;
    }
</style>

<div class="dashboard-container">
    <div class="row">
        <!-- Welcome Banner -->
        <div class="col-12 mb-4">
            <div class="card welcome-banner-premium">
                <div class="row align-items-center">
                    <div class="col-lg-7 welcome-content">
                        <h1 class="display-5 fw-bold text-white mb-2">ยินดีต้อนรับสู่ SKJ Personnel Hub</h1>
                        <p class="lead text-white opacity-90 mb-4">
                            ศูนย์กลางการจัดการบุคลากรยุคใหม่ ครบถ้วน รวดเร็ว และแม่นยำ<br>
                            ร่วมขับเคลื่อนองค์กรด้วยข้อมูลที่มีประสิทธิภาพ
                        </p>
                        <div class="d-flex gap-2">
                            <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="btn btn-white px-4 py-2 rounded-pill fw-bold" style="color: #007bff; background: white;">
                                <i class='bx bx-user-plus me-1'></i> จัดการบุคลากร
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="<?=base_url()?>/assets/img/illustrations/man-with-laptop-light.png" class="welcome-img" alt="Management">
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-stat-card h-100">
                <div class="icon-wrapper gradient-primary shadow-sm">
                    <i class='bx bx-group'></i>
                </div>
                <div class="stat-label">บุคลากรทั้งหมด</div>
                <div class="stat-value text-dark"><?=number_format($countAllPersonnel);?></div>
                <div class="progress progress-premium">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">สถานะ: กำลังใช้งาน</span>
                    <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="btn btn-action-premium primary">จัดการข้อมูล</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-stat-card h-100">
                <div class="icon-wrapper gradient-success shadow-sm">
                    <i class='bx bx-time-five'></i>
                </div>
                <div class="stat-label">การลงเวลาวันนี้</div>
                <div class="stat-value text-dark"><?=number_format($countAttendanceToday);?></div>
                <div class="progress progress-premium">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($countAllPersonnel > 0) ? ($countAttendanceToday/$countAllPersonnel*100) : 0 ?>%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">อัตราการมาวันนี้: <?= ($countAllPersonnel > 0) ? round($countAttendanceToday/$countAllPersonnel*100, 1) : 0 ?>%</span>
                    <a href="<?=base_url('Admin/SaveAttendance');?>" class="btn btn-action-premium success">ดูรายงาน</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-stat-card h-100">
                <div class="icon-wrapper gradient-info shadow-sm">
                    <i class='bx bx-chart'></i>
                </div>
                <div class="stat-label">รายการประเมิน PA</div>
                <div class="stat-value text-dark"><?=number_format($countPendingEvaluations);?></div>
                <div class="progress progress-premium">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">ปีงบประมาณ 2568</span>
                    <a href="<?=base_url('Admin/PaConfig');?>" class="btn btn-action-premium info">ติดตามผล</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="premium-stat-card h-100">
                <div class="icon-wrapper gradient-warning shadow-sm">
                    <i class='bx bx-shield-alt-2'></i>
                </div>
                <div class="stat-label">ผู้ดูแลระบบ</div>
                <div class="stat-value text-dark"><?=number_format($countTotalUsers);?></div>
                <div class="progress progress-premium">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">ความปลอดภัยระดับสูง</span>
                    <a href="<?=base_url('Admin/Rloes/Setting');?>" class="btn btn-action-premium warning">สิทธิ์ใช้งาน</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Overview Section -->
    <div class="row">
        <div class="col-12">
            <div class="card overview-card p-4">
                <div class="card-header bg-transparent border-0 px-0 d-flex justify-content-between align-items-center mb-4">
                    <h4 class="section-title-premium mb-0">สรุปภาพรวมระบบ (System Overview)</h4>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded fs-5'></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                            <li><a class="dropdown-item py-2" href="#"><i class='bx bx-refresh me-2'></i> รีเฟรชข้อมูล</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class='bx bx-download me-2'></i> ดาวน์โหลดรายงาน</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="overview-item d-flex align-items-center gap-3">
                                <div class="overview-icon bg-label-primary">
                                    <i class='bx bx-user-circle'></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0 fw-semibold">ข้อมูลบุคลากร</p>
                                    <h4 class="fw-bold mb-0"><?=number_format($countAllPersonnel);?> <small class="text-muted fs-6 fw-normal">รายการ</small></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="overview-item d-flex align-items-center gap-3">
                                <div class="overview-icon bg-label-success">
                                    <i class='bx bx-check-double'></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0 fw-semibold">สถิติมาทำงาน</p>
                                    <h4 class="fw-bold mb-0"><?= ($countAllPersonnel > 0) ? round($countAttendanceToday/$countAllPersonnel*100, 1) : 0 ?>% <small class="text-muted fs-6 fw-normal">วันนี้</small></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="overview-item d-flex align-items-center gap-3">
                                <div class="overview-icon bg-label-info">
                                    <i class='bx bx-file-find'></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0 fw-semibold">ประเมินเสร็จสิ้น</p>
                                    <h4 class="fw-bold mb-0"><?=number_format($countPendingEvaluations);?> <small class="text-muted fs-6 fw-normal">รายการ</small></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="overview-item d-flex align-items-center gap-3">
                                <div class="overview-icon bg-label-warning">
                                    <i class='bx bx-key'></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-0 fw-semibold">ผู้ใช้งานสิทธิ์สูง</p>
                                    <h4 class="fw-bold mb-0"><?=number_format($countTotalUsers);?> <small class="text-muted fs-6 fw-normal">บัญชี</small></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
