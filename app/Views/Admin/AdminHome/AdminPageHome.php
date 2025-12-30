<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    .premium-card {
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #007bff 0%, #66b0ff 100%);
        color: white;
        border-radius: 0.5rem;
        position: relative;
    }
    .welcome-banner img {
        position: absolute;
        right: 20px;
        bottom: 0;
        height: 120%;
        z-index: 1;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%); }
    
    /* Override primary button and text colors for this page */
    .btn-outline-primary {
        color: #007bff;
        border-color: #007bff;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary, .bg-primary {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }
    .text-primary {
        color: #007bff !important;
    }
    .bg-label-primary {
        background-color: #e6f2ff !important;
        color: #007bff !important;
    }
</style>

<div class="row">
    <!-- Welcome Banner -->
    <div class="col-12 mb-4">
        <div class="card welcome-banner shadow-none border-0 overflow-visible">
            <div class="d-flex align-items-center row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h4 class="card-title text-white mb-1">ยินดีต้อนรับสู่ระบบจัดการบุคลากร! 🎉</h4>
                        <p class="mb-4 text-white opacity-75">
                            จัดการข้อมูลบุคลากร การลงเวลา และการประเมินผล PA ได้อย่างง่ายดายในที่เดียว
                        </p>
                        <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="btn btn-sm btn-white text-primary fw-bold" style="background: white;">เริ่มจัดการข้อมูล</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img class="lozad" data-src="<?=base_url()?>/assets/img/illustrations/man-with-laptop-light.png" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card premium-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-label-primary me-3">
                        <i class='bx bx-group'></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">บุคลากร</h5>
                        <small class="text-muted">ทั้งหมด</small>
                    </div>
                </div>
                <div class="d-flex align-items-baseline mb-3">
                    <h3 class="mb-0 me-2"><?=number_format($countAllPersonnel);?></h3>
                    <span class="text-primary fw-semibold">คน</span>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="btn btn-xs btn-outline-primary w-100">จัดการข้อมูล</a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card premium-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-label-success me-3">
                        <i class='bx bx-calendar-check'></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">ลงเวลาทำงาน</h5>
                        <small class="text-muted">วันนี้</small>
                    </div>
                </div>
                <div class="d-flex align-items-baseline mb-3">
                    <h3 class="mb-0 me-2"><?=number_format($countAttendanceToday);?></h3>
                    <span class="text-success fw-semibold">คน</span>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($countAllPersonnel > 0) ? ($countAttendanceToday/$countAllPersonnel*100) : 0 ?>%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <a href="<?=base_url('Admin/SaveAttendance');?>" class="btn btn-xs btn-outline-success w-100">ดูรายละเอียด</a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card premium-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-label-info me-3">
                        <i class='bx bx-book-alt'></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">การประเมิน PA</h5>
                        <small class="text-muted">ปีงบประมาณ 2568</small>
                    </div>
                </div>
                <div class="d-flex align-items-baseline mb-3">
                    <h3 class="mb-0 me-2"><?=number_format($countPendingEvaluations);?></h3>
                    <span class="text-info fw-semibold">รายการ</span>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <a href="<?=base_url('Admin/PaConfig');?>" class="btn btn-xs btn-outline-info w-100">ตั้งค่า/สรุปผล</a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card premium-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-label-warning me-3">
                        <i class='bx bx-shield-quarter'></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">สิทธิ์ผู้ใช้งาน</h5>
                        <small class="text-muted">แอดมิน/จัดการ</small>
                    </div>
                </div>
                <div class="d-flex align-items-baseline mb-3">
                    <h3 class="mb-0 me-2"><?=number_format($countTotalUsers);?></h3>
                    <span class="text-warning fw-semibold">คน</span>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <a href="<?=base_url('Admin/Rloes/Setting');?>" class="btn btn-xs btn-outline-warning w-100">จัดการสิทธิ์</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Activity Timeline or Charts could go here to make it even more premium -->
    <div class="col-md-12">
        <div class="card premium-card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">ภาพรวมระบบ (Overview)</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                        <a class="dropdown-item" href="javascript:void(0);">ล้างข้อมูล</a>
                        <a class="dropdown-item" href="javascript:void(0);">ดาวน์โหลดรายงาน</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center g-4">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-label-secondary rounded">
                            <h4 class="mb-1 fw-bold"><?=number_format($countAllPersonnel);?></h4>
                            <p class="mb-0 text-muted">บุคลากร</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                         <div class="p-3 bg-label-primary rounded">
                            <h4 class="mb-1 fw-bold"><?= ($countAllPersonnel > 0) ? round($countAttendanceToday/$countAllPersonnel*100, 1) : 0 ?>%</h4>
                            <p class="mb-0 text-muted">มาทำงานวันนี้</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-label-info rounded">
                            <h4 class="mb-1 fw-bold"><?=number_format($countPendingEvaluations);?></h4>
                            <p class="mb-0 text-muted">บันทึก PA</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-label-warning rounded">
                            <h4 class="mb-1 fw-bold"><?=number_format($countTotalUsers);?></h4>
                            <p class="mb-0 text-muted">ผู้ดูแลระบบ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
