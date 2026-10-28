<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.4);
        --header-gradient: linear-gradient(135deg, #03c3ec 0%, #029dbd 100%);
        --primary-gradient: linear-gradient(135deg, #03c3ec 0%, #00d2ff 100%);
        --accent-pink: #FF4B91;
        --accent-blue: #03c3ec;
        --accent-teal: #41C9E2;
    }

    /* Premium Header */
    .personnel-header {
        position: relative;
        background: var(--header-gradient);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2.5rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(78, 84, 200, 0.25);
    }
    .personnel-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    }
    .header-content {
        position: relative;
        z-index: 2;
    }

    /* Stats Section */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    /* Section Headers */
    .category-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #2c3e50;
        margin: 3rem 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.5px;
    }
    .category-title .icon-bg {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .category-title::after {
        content: "";
        flex: 1;
        height: 2px;
        background: linear-gradient(to right, #dee2e6, transparent);
        border-radius: 2px;
    }

    /* Group Cards */
    .group-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
    }
    .group-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; height: 4px;
        border-radius: 20px 20px 0 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .group-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .group-card:hover::before {
        opacity: 1;
    }

    /* Custom Colors per Category */
    .card-executive::before { background: var(--accent-pink); }
    .card-learning::before { background: var(--accent-blue); }
    .card-support::before { background: var(--accent-teal); }

    .avatar-group .avatar {
        margin-left: -0.6rem;
        transition: all 0.3s ease;
        border: 2px solid #fff;
    }
    .avatar-group .avatar:hover {
        transform: translateY(-5px) scale(1.1);
        z-index: 10;
    }

    /* Buttons */
    .btn-action-main {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-add-personnel {
        background: white;
        color: #03c3ec;
        border: none;
    }
    .btn-add-personnel:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,255,255,0.3);
    }
    .btn-cleanup {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(5px);
    }
    .btn-cleanup:hover {
        background: #FF3E1D;
        border-color: #FF3E1D;
        color: white;
    }
    .btn-restore {
        background: rgba(255, 255, 255, 0.18);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(5px);
    }
    .btn-restore:hover {
        background: #ffab00;
        border-color: #ffab00;
        color: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 171, 0, 0.35);
    }
    .btn-restore:hover .badge {
        background: #0f172a !important;
        color: #ffffff !important;
    }

    .badge-premium {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }
</style>

<div class="personnel-header">
    <div class="header-content d-flex justify-content-between align-items-center flex-column flex-md-row">
        <div>
            <span class="badge bg-white text-primary mb-2 fw-bold px-3">SKJ Personnel Management</span>
            <h2 class="text-white mb-1 fw-bold"><i class='bx bx-id-card me-2'></i><?= $title; ?></h2>
            <p class="text-white opacity-75 mb-0">ระบบบริหารจัดการข้อมูลบุคลากร และตำแหน่งหน้าที่อย่างเป็นระบบ</p>
        </div>
        <div class="mt-4 mt-md-0 d-flex flex-wrap gap-2">
            <button type="button" class="btn-action-main btn-restore shadow-sm" id="btnOpenRestoreModal"
                    data-url-get="<?= base_url('Admin/WorkPerson/Personnel/DB/GetInactivePersonnel') ?>"
                    data-url-restore="<?= base_url('Admin/WorkPerson/Personnel/DB/Restore') ?>"
                    data-url-update="<?= base_url('Admin/WorkPerson/Personnel/Update') ?>"
                    data-img-base="<?= base_url('uploads/admin/Personnal') ?>"
                    data-img-default="<?= base_url('assets/img/avatars/default_user.svg') ?>">
                <i class="bx bx-history fs-4"></i> กู้คืนข้อมูลบุคลากร
                <span class="badge bg-warning text-dark ms-1 rounded-pill fw-bold" id="badgeInactiveCount"><?= number_format($countInactive ?? 0); ?></span>
            </button>
            <button type="button" class="btn-action-main btn-cleanup" id="btnCleanupImages" data-url="<?= base_url('Admin/WorkPerson/Personnel/DB/CleanupImages') ?>">
                <i class="bx bx-trash-alt"></i> ล้างรูปขยะ
            </button>
            <a class="btn-action-main btn-add-personnel shadow-sm" href="<?= base_url('Admin/WorkPerson/Personnel/Add') ?>">
                <i class="bx bx-user-plus fs-4"></i> เพิ่มบุคลากร
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats Summary -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-pink);">
            <i class='bx bxs-group'></i>
        </div>
        <div>
            <h6 class="text-muted mb-0 small uppercase fw-bold">Executive</h6>
            <h4 class="mb-0 fw-bold"><?= number_format($Executive[0]->NumAll); ?> <small class="text-muted small fs-6">คน</small></h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-blue);">
            <i class='bx bxs-graduation'></i>
        </div>
        <div>
            <h6 class="text-muted mb-0 small uppercase fw-bold">Teachers</h6>
            <h4 class="mb-0 fw-bold">
                <?php 
                    $totalT = 0; 
                    foreach($Learning as $l) $totalT += $l->NumAll;
                    echo number_format($totalT);
                ?> 
                <small class="text-muted small fs-6">คน</small>
            </h4>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-teal);">
            <i class='bx bxs-user-pin' ></i>
        </div>
        <div>
            <h6 class="text-muted mb-0 small uppercase fw-bold">Support Staff</h6>
            <h4 class="mb-0 fw-bold">
                <?php 
                    $totalS = 0; 
                    foreach($Support as $s) $totalS += $s->NumAll;
                    echo number_format($totalS);
                ?>
                <small class="text-muted small fs-6">คน</small>
            </h4>
        </div>
    </div>
</div>

<!-- Section: Executive -->
<div class="category-title">
    <div class="icon-bg"><i class='bx bxs-crown text-warning fs-4'></i></div>
    ผู้บริหารโรงเรียน
</div>
<div class="row">
    <div class="col-sm-6 col-xl-4">
        <div class="card group-card card-executive shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="badge bg-label-danger badge-premium"><?= number_format($Executive[0]->NumAll); ?> บุคลากร</span>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        <?php 
                        $sub = $Executive[0]->AllImg ? explode(',', $Executive[0]->AllImg) : [];
                        $max_show = 5;
                        foreach (array_slice($sub, 0, $max_show) as $value): 
                        ?>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up">
                            <img class="lozad rounded-circle border-2 border-white" data-src="<?= base_url('uploads/admin/Personnal/' . $value) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onerror="this.onerror=null; this.src='<?= base_url('uploads/admin/Personnal/default.png') ?>';" loading="lazy" alt="Avatar">
                        </li>
                        <?php endforeach; ?>
                        <?php if(count($sub) > $max_show): ?>
                            <li class="avatar avatar-sm"><span class="avatar-initial rounded-circle bg-label-secondary">+<?= count($sub)-$max_show ?></span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <h4 class="fw-bold mb-2">ฝ่ายบริหาร</h4>
                <p class="text-muted small mb-4">บริหารงานวิชาการ งบประมาณ บุคลากร และงานบริหารทั่วไป</p>
                <a href="<?= base_url('Admin/WorkPerson/Personnel/Group/Executive') ?>" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold">
                    ดูรายชื่อทั้งหมด <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Section: Teachers -->
<div class="category-title">
    <div class="icon-bg"><i class='bx bxs-book-open text-primary fs-4'></i></div>
    กลุ่มสาระการเรียนรู้
</div>
<div class="row g-4">
    <?php foreach ($Learning as $key => $v_Lear): ?>
    <div class="col-sm-6 col-md-4 col-xl-4">
        <div class="card group-card card-learning shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="badge bg-label-primary badge-premium"><?= number_format($v_Lear->NumAll); ?> บุคลากร</span>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        <?php 
                        $sub = $v_Lear->AllImg ? explode(',', $v_Lear->AllImg) : [];
                        foreach (array_slice($sub, 0, 4) as $value): 
                        ?>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up">
                            <img class="lozad rounded-circle border-2 border-white" data-src="<?= base_url('uploads/admin/Personnal/' . $value) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onerror="this.onerror=null; this.src='<?= base_url('uploads/admin/Personnal/default.png') ?>';" loading="lazy" alt="Avatar">
                        </li>
                        <?php endforeach; ?>
                        <?php if(count($sub) > 4): ?>
                            <li class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary">+<?= count($sub)-4 ?></span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <h5 class="fw-bold mb-1 text-truncate"><?= $v_Lear->lear_namethai ?></h5>
                <p class="text-muted small mb-4 text-truncate">จัดการเรียนการสอนกลุ่มสาระฯ</p>
                <a href="<?= base_url('Admin/WorkPerson/Personnel/Group/' . $v_Lear->lear_id) ?>" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold">
                    จัดการรายชื่อ <i class='bx bx-cog'></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Section: Support -->
<div class="category-title">
    <div class="icon-bg"><i class='bx bxs-briefcase text-info fs-4'></i></div>
    สายสนับสนุนและลูกจ้าง
</div>
<div class="row g-4 mb-5">
    <?php foreach ($Support as $key => $v_Support) : ?>
    <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="card group-card card-support shadow-sm border-0 h-100">
            <div class="card-body p-4 text-center">
                <div class="avatar avatar-lg mx-auto mb-3">
                    <span class="avatar-initial rounded-circle bg-label-info shadow-sm" style="width: 60px; height: 60px;"><i class='bx bxs-user-badge fs-2'></i></span>
                </div>
                <h5 class="fw-bold mb-1"><?= $v_Support->posi_name ?></h5>
                <p class="badge bg-label-info badge-premium mb-4"><?= number_format($v_Support->NumAll) ?> คน</p>
                
                <div class="d-flex justify-content-center mb-4">
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        <?php 
                        $sub = $v_Support->AllImg ? explode(',', $v_Support->AllImg) : [];
                        foreach (array_slice($sub, 0, 3) as $value): 
                        ?>
                        <li class="avatar avatar-xs pull-up">
                            <img class="lozad rounded-circle border-2 border-white" data-src="<?= base_url('uploads/admin/Personnal/' . $value) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onerror="this.onerror=null; this.src='<?= base_url('uploads/admin/Personnal/default.png') ?>';" loading="lazy" alt="Avatar">
                        </li>
                        <?php endforeach; ?>
                        <?php if(count($sub) > 3): ?>
                            <li class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary">+<?= count($sub)-3 ?></span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <a href="<?= base_url('Admin/WorkPerson/Personnel/Group/' . $v_Support->posi_id) ?>" class="btn btn-xs btn-outline-info px-4">ดูรายชื่อ</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ========================================== -->
<!-- MODAL 1: ศูนย์กู้คืนข้อมูลบุคลากร (Inactive / Resigned List) -->
<!-- ========================================== -->
<div class="modal fade" id="modalRestorePersonnel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; font-family: 'K2D', sans-serif;">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%);">
                <div>
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2 mb-1">
                        <i class='bx bx-history fs-3'></i> ศูนย์กู้คืนข้อมูลครูและบุคลากรที่พ้นสภาพ
                    </h5>
                    <p class="text-white opacity-75 small mb-0">ค้นหาประวัติเดิมของผู้ที่เคยลาออกหรือย้ายสถานศึกษา เพื่อนำข้อมูลเดิมกลับมาใช้งานใหม่ได้ทันที</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <!-- Search & Filter Controls -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class='bx bx-search fs-5'></i></span>
                                    <input type="text" id="searchInactivePersonnel" class="form-control border-start-0 ps-0 shadow-none" placeholder="ค้นหาด้วยชื่อ, นามสกุล, รหัสประจำตัว หรือตำแหน่งเดิม...">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end" id="filterStatusPills">
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 filter-status-btn active" data-status="ALL">
                                        ทั้งหมด <span class="badge bg-white text-primary ms-1" id="pillCountAll">0</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 filter-status-btn" data-status="ลาออก">
                                        ลาออก <span class="badge bg-danger text-white ms-1" id="pillCountResigned">0</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 filter-status-btn" data-status="ย้ายสถานศึกษา">
                                        ย้ายสถานศึกษา <span class="badge bg-warning text-dark ms-1" id="pillCountTransferred">0</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-status-btn" data-status="เกษียรอายุ">
                                        เกษียรอายุ <span class="badge bg-secondary text-white ms-1" id="pillCountRetired">0</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personnel List Container -->
                <div id="inactivePersonnelLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-3 fw-semibold">กำลังโหลดข้อมูลบุคลากรที่พ้นสภาพ...</p>
                </div>

                <div id="inactivePersonnelEmpty" class="text-center py-5 d-none">
                    <i class='bx bx-user-x text-muted' style="font-size: 4rem;"></i>
                    <h5 class="fw-bold mt-3 text-secondary">ไม่พบข้อมูลบุคลากร</h5>
                    <p class="text-muted small">ไม่มีรายชื่อที่ตรงกับคำค้นหาหรือเงื่อนไขที่เลือก</p>
                </div>

                <div id="inactivePersonnelContainer" class="d-none">
                    <div class="table-responsive bg-white rounded-4 shadow-sm p-2">
                        <table class="table table-hover align-middle mb-0" id="tableInactivePersonnel">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 70px;">รูปภาพ</th>
                                    <th>รหัส / ชื่อ - นามสกุล</th>
                                    <th>ตำแหน่งเดิม / กลุ่มสาระ</th>
                                    <th>สถานะเดิม</th>
                                    <th class="text-end pe-3" style="width: 140px;">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyInactivePersonnel">
                                <!-- Rendered dynamically by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between">
                <span class="text-muted small" id="textInactiveSummary">แสดงผล 0 รายการ</span>
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: ยืนยันและระบุการกู้คืนข้อมูล (Confirm & Quick Assign) -->
<!-- ========================================== -->
<div class="modal fade" id="modalConfirmRestore" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; font-family: 'K2D', sans-serif;">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #0284c7 0%, #075985 100%);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2 mb-0">
                    <i class='bx bx-user-check fs-4'></i> ยืนยันการกู้คืนข้อมูลบุคลากร
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formConfirmRestore">
                <div class="modal-body p-4">
                    <!-- Profile Preview Card -->
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-4 mb-4 border border-light">
                        <img id="restoreTargetImg" src="" class="rounded-circle border border-2 border-white shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
                        <div class="flex-grow-1 overflow-hidden">
                            <span class="badge bg-label-secondary small mb-1" id="restoreTargetIdBadge">ID: -</span>
                            <h6 class="fw-bold mb-0 text-truncate text-dark" id="restoreTargetName">-</h6>
                            <small class="text-muted text-truncate d-block" id="restoreTargetFormerInfo">-</small>
                        </div>
                    </div>

                    <input type="hidden" name="pers_id" id="restore_pers_id">

                    <!-- Restore Settings Form -->
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark mb-1"><i class='bx bx-check-circle text-success me-1'></i> สถานะการทำงานใหม่</label>
                            <input type="text" class="form-control fw-bold bg-white text-success border-success" name="pers_status" value="กำลังใช้งาน" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark mb-1"><i class='bx bx-briefcase text-primary me-1'></i> ตำแหน่งทางการศึกษา <span class="text-danger">*</span></label>
                            <select class="form-select select2Personnel" id="restore_pers_position" name="pers_position" required>
                                <option value="">-- เลือกตำแหน่งหลัก --</option>
                                <?php if (!empty($allPositions)): foreach ($allPositions as $pos): ?>
                                    <option value="<?= $pos->posi_id ?>"><?= $pos->posi_name ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <!-- กลุ่มสาระการเรียนรู้ (แสดงเมื่อเป็นตำแหน่งครู posi_003-posi_006) -->
                        <div class="col-12" id="restoreLearningGroupWrapper">
                            <label class="form-label fw-bold text-dark mb-1"><i class='bx bx-book-open text-info me-1'></i> กลุ่มสาระการเรียนรู้</label>
                            <select class="form-select select2Personnel" id="restore_pers_learning" name="pers_learning">
                                <option value="">-- เลือกกลุ่มสาระ --</option>
                                <?php if (!empty($allLearnings)): foreach ($allLearnings as $lear): ?>
                                    <option value="<?= $lear->lear_id ?>"><?= $lear->lear_namethai ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <!-- สายงานสนับสนุน (แสดงเมื่อเป็นตำแหน่งสายสนับสนุน posi_007+) -->
                        <div class="col-12" id="restoreWorkOtherWrapper" style="display: none;">
                            <label class="form-label fw-bold text-dark mb-1"><i class='bx bx-id-card text-info me-1'></i> สายงาน/ตำแหน่งย่อย</label>
                            <select class="form-select select2Personnel" id="restore_pers_workother_id" name="pers_workother_id">
                                <option value="">-- เลือกสายงานย่อย --</option>
                                <?php if (!empty($allWorkOther)): foreach ($allWorkOther as $wo): ?>
                                    <option value="<?= $wo->work_id ?>"><?= $wo->work_name ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <!-- วิทยฐานะ -->
                        <div class="col-12" id="restoreAcademicWrapper">
                            <label class="form-label fw-bold text-dark mb-1"><i class='bx bx-award text-warning me-1'></i> วิทยฐานะ</label>
                            <select class="form-select select2Personnel" id="restore_pers_academic" name="pers_academic">
                                <option value="">-- ระบุวิทยฐานะ (ถ้ามี) --</option>
                                <option value="ชำนาญการ">ชำนาญการ</option>
                                <option value="ชำนาญการพิเศษ">ชำนาญการพิเศษ</option>
                                <option value="เชี่ยวชาญ">เชี่ยวชาญ</option>
                                <option value="เชี่ยวชาญพิเศษ">เชี่ยวชาญพิเศษ</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">ยกเลิก</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary rounded-pill px-3" id="btnSubmitRestoreStay">
                            <i class='bx bx-check me-1'></i> กู้คืนและอยู่ที่นี่
                        </button>
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnSubmitRestoreEdit">
                            <i class='bx bx-edit-alt me-1'></i> กู้คืน & แก้ไขข้อมูล
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.4"></script>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>

<script>
$(document).ready(function() {
    let inactivePersonnelData = [];
    let currentFilterStatus = 'ALL';
    const $btnRestore = $('#btnOpenRestoreModal');
    const getInactiveUrl = $btnRestore.data('url-get');
    const restoreUrl = $btnRestore.data('url-restore');
    const imgBaseUrl = $btnRestore.data('img-base');
    const defaultImg = $btnRestore.data('img-default') || '<?= base_url('assets/img/avatars/default_user.svg') ?>';

    // Toggle Learning / Work Other wrappers based on selected position
    function handlePositionChange(posId) {
        const isTeacher = ['posi_003', 'posi_004', 'posi_005', 'posi_006'].includes(posId);
        const isDirector = (posId === 'posi_001');
        const isDeputy = (posId === 'posi_002');
        const isSupport = (!isTeacher && !isDirector && !isDeputy && posId !== '');

        if (isTeacher) {
            $('#restoreLearningGroupWrapper').slideDown(200);
            $('#restoreWorkOtherWrapper').slideUp(200);
            $('#restoreAcademicWrapper').slideDown(200);
        } else if (isSupport) {
            $('#restoreLearningGroupWrapper').slideUp(200);
            $('#restoreWorkOtherWrapper').slideDown(200);
            $('#restoreAcademicWrapper').slideUp(200);
        } else {
            // Director / Deputy or none
            $('#restoreLearningGroupWrapper').slideUp(200);
            $('#restoreWorkOtherWrapper').slideUp(200);
            if (isDirector || isDeputy) {
                $('#restoreAcademicWrapper').slideDown(200);
            } else {
                $('#restoreAcademicWrapper').slideUp(200);
            }
        }
    }

    $('#restore_pers_position').on('change', function() {
        handlePositionChange($(this).val());
    });

    // Open Restore Modal
    $btnRestore.on('click', function() {
        $('#modalRestorePersonnel').modal('show');
        loadInactivePersonnel();
    });

    // Load Inactive Personnel via AJAX
    function loadInactivePersonnel() {
        $('#inactivePersonnelLoading').removeClass('d-none');
        $('#inactivePersonnelContainer').addClass('d-none');
        $('#inactivePersonnelEmpty').addClass('d-none');

        $.ajax({
            url: getInactiveUrl,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                $('#inactivePersonnelLoading').addClass('d-none');
                if (res.status === 'success' && res.data) {
                    inactivePersonnelData = res.data;
                    updatePillCounts();
                    applyFilterAndRender();
                } else {
                    $('#inactivePersonnelEmpty').removeClass('d-none');
                }
            },
            error: function(xhr) {
                $('#inactivePersonnelLoading').addClass('d-none');
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'ไม่สามารถโหลดข้อมูลบุคลากรที่พ้นสภาพได้ กรุณาลองใหม่อีกครั้ง'
                });
            }
        });
    }

    // Update Pill Count Badges
    function updatePillCounts() {
        const total = inactivePersonnelData.length;
        const resigned = inactivePersonnelData.filter(p => (p.pers_status || '').trim() === 'ลาออก').length;
        const transferred = inactivePersonnelData.filter(p => (p.pers_status || '').trim().includes('ย้าย')).length;
        const retired = inactivePersonnelData.filter(p => (p.pers_status || '').trim() === 'เกษียรอายุ').length;

        $('#pillCountAll').text(total);
        $('#pillCountResigned').text(resigned);
        $('#pillCountTransferred').text(transferred);
        $('#pillCountRetired').text(retired);
        $('#badgeInactiveCount').text(total);
    }

    // Filter and Render
    function applyFilterAndRender() {
        const keyword = ($('#searchInactivePersonnel').val() || '').toLowerCase().trim();

        let filtered = inactivePersonnelData.filter(function(item) {
            // Status filter
            if (currentFilterStatus !== 'ALL') {
                const itemStatus = (item.pers_status || '').trim();
                if (currentFilterStatus === 'ย้ายสถานศึกษา') {
                    if (!itemStatus.includes('ย้าย')) return false;
                } else if (itemStatus !== currentFilterStatus) {
                    return false;
                }
            }

            // Keyword filter
            if (keyword) {
                const fullname = (item.pers_prefix + item.pers_firstname + ' ' + item.pers_lastname).toLowerCase();
                const id = (item.pers_id || '').toLowerCase();
                const pos = (item.posi_name || '').toLowerCase();
                const lear = (item.lear_namethai || '').toLowerCase();
                const work = (item.work_name || '').toLowerCase();

                if (!fullname.includes(keyword) && !id.includes(keyword) && !pos.includes(keyword) && !lear.includes(keyword) && !work.includes(keyword)) {
                    return false;
                }
            }

            return true;
        });

        renderList(filtered);
    }

    // Render Table Rows
    function renderList(list) {
        const $tbody = $('#tbodyInactivePersonnel');
        $tbody.empty();

        $('#textInactiveSummary').text(`แสดงผล ${list.length} จากทั้งหมด ${inactivePersonnelData.length} รายการ`);

        if (list.length === 0) {
            $('#inactivePersonnelContainer').addClass('d-none');
            $('#inactivePersonnelEmpty').removeClass('d-none');
            return;
        }

        $('#inactivePersonnelEmpty').addClass('d-none');
        $('#inactivePersonnelContainer').removeClass('d-none');

        list.forEach(function(item) {
            const imgSrc = (item.pers_img && item.pers_img.trim() !== '') ? `${imgBaseUrl}/${item.pers_img}` : defaultImg;
            const fullName = `${item.pers_prefix || ''}${item.pers_firstname || ''} ${item.pers_lastname || ''}`;
            const formerGroup = item.lear_namethai || item.work_name || '-';
            
            let statusBadge = '<span class="badge bg-label-secondary border">ไม่ระบุ</span>';
            const s = (item.pers_status || '').trim();
            if (s === 'ลาออก') {
                statusBadge = '<span class="badge bg-label-danger border border-danger fw-bold">ลาออก</span>';
            } else if (s.includes('ย้าย')) {
                statusBadge = '<span class="badge bg-label-warning border border-warning fw-bold text-dark">ย้ายสถานศึกษา</span>';
            } else if (s === 'เกษียรอายุ') {
                statusBadge = '<span class="badge bg-label-secondary border border-secondary fw-bold">เกษียรอายุ</span>';
            }

            const row = `
                <tr>
                    <td class="ps-3">
                        <img src="${imgSrc}" class="rounded-circle border" style="width: 48px; height: 48px; object-fit: cover;" onerror="this.onerror=null; this.src='${defaultImg}';">
                    </td>
                    <td>
                        <div class="fw-bold text-dark">${fullName}</div>
                        <span class="badge bg-label-secondary small" style="font-size: 0.7rem;">ID: ${item.pers_id}</span>
                        ${item.pers_phone ? `<span class="text-muted ms-2 small"><i class='bx bx-phone me-1'></i>${item.pers_phone}</span>` : ''}
                    </td>
                    <td>
                        <div class="text-dark fw-semibold small">${item.posi_name || 'ไม่ระบุตำแหน่ง'}</div>
                        <div class="text-muted small">${formerGroup}</div>
                    </td>
                    <td>${statusBadge}</td>
                    <td class="text-end pe-3">
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 btn-trigger-restore" data-id="${item.pers_id}">
                            <i class='bx bx-user-check me-1'></i> กู้คืนข้อมูล
                        </button>
                    </td>
                </tr>
            `;
            $tbody.append(row);
        });
    }

    // Filter pills click
    $('.filter-status-btn').on('click', function() {
        $('.filter-status-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        // Reset classes
        $('.filter-status-btn[data-status="ALL"]').addClass('btn-outline-primary');
        $('.filter-status-btn[data-status="ลาออก"]').addClass('btn-outline-danger');
        $('.filter-status-btn[data-status="ย้ายสถานศึกษา"]').addClass('btn-outline-warning');
        $('.filter-status-btn[data-status="เกษียรอายุ"]').addClass('btn-outline-secondary');

        $(this).addClass('active');
        if ($(this).data('status') === 'ALL') {
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        } else if ($(this).data('status') === 'ลาออก') {
            $(this).removeClass('btn-outline-danger').addClass('btn-danger text-white');
        } else if ($(this).data('status') === 'ย้ายสถานศึกษา') {
            $(this).removeClass('btn-outline-warning').addClass('btn-warning text-dark');
        } else if ($(this).data('status') === 'เกษียรอายุ') {
            $(this).removeClass('btn-outline-secondary').addClass('btn-secondary text-white');
        }

        currentFilterStatus = $(this).data('status');
        applyFilterAndRender();
    });

    // Real-time keyword search
    $('#searchInactivePersonnel').on('input', function() {
        applyFilterAndRender();
    });

    // Trigger restore button click in row
    $(document).on('click', '.btn-trigger-restore', function() {
        const persId = $(this).data('id');
        const person = inactivePersonnelData.find(p => p.pers_id === persId);
        if (!person) return;

        // Populate confirm modal
        const imgSrc = (person.pers_img && person.pers_img.trim() !== '') ? `${imgBaseUrl}/${person.pers_img}` : defaultImg;
        const fullName = `${person.pers_prefix || ''}${person.pers_firstname || ''} ${person.pers_lastname || ''}`;
        const formerInfo = `${person.posi_name || 'ตำแหน่งเดิม'} • ${person.lear_namethai || person.work_name || 'กลุ่มงานเดิม'}`;

        $('#restore_pers_id').val(person.pers_id);
        $('#restoreTargetImg').attr('src', imgSrc).off('error').on('error', function() {
            this.onerror = null;
            this.src = defaultImg;
        });
        $('#restoreTargetName').text(fullName);
        $('#restoreTargetIdBadge').text(`ID: ${person.pers_id}`);
        $('#restoreTargetFormerInfo').text(formerInfo);

        // Pre-select former position / learning / academic if valid
        if (person.pers_position) {
            $('#restore_pers_position').val(person.pers_position).trigger('change');
        } else {
            $('#restore_pers_position').val('').trigger('change');
        }

        if (person.pers_learning) {
            $('#restore_pers_learning').val(person.pers_learning).trigger('change');
        }
        if (person.pers_workother_id) {
            $('#restore_pers_workother_id').val(person.pers_workother_id).trigger('change');
        }
        if (person.pers_academic) {
            $('#restore_pers_academic').val(person.pers_academic).trigger('change');
        } else {
            $('#restore_pers_academic').val('').trigger('change');
        }

        handlePositionChange(person.pers_position);

        $('#modalConfirmRestore').modal('show');
    });

    // Submit Restore
    function submitRestore(redirectToEdit) {
        const persId = $('#restore_pers_id').val();
        const pos = $('#restore_pers_position').val();
        if (!pos) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาระบุตำแหน่ง',
                text: 'โปรดเลือกตำแหน่งทางการศึกษาให้กับบุคลากร'
            });
            return;
        }

        const formData = {
            pers_id: persId,
            pers_status: 'กำลังใช้งาน',
            pers_position: pos,
            pers_learning: $('#restore_pers_learning').val(),
            pers_workother_id: $('#restore_pers_workother_id').val(),
            pers_academic: $('#restore_pers_academic').val()
        };

        Swal.fire({
            title: 'ยืนยันการกู้คืนข้อมูล?',
            text: `ต้องการปรับสถานะของ ${$('#restoreTargetName').text()} กลับเป็น 'กำลังใช้งาน' ใช่หรือไม่?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0284c7',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bx bx-check me-1"></i> ยืนยันการกู้คืน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'กำลังบันทึกข้อมูล...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: restoreUrl,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'กู้คืนข้อมูลสำเร็จ!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                if (redirectToEdit && res.redirect_url) {
                                    window.location.href = res.redirect_url;
                                } else {
                                    $('#modalConfirmRestore').modal('hide');
                                    loadInactivePersonnel();
                                    // Reload page to refresh dashboard counters after 1 sec
                                    setTimeout(() => window.location.reload(), 800);
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: res.message || 'เกิดข้อผิดพลาดในการกู้คืนข้อมูล'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'ข้อผิดพลาด',
                            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง'
                        });
                    }
                });
            }
        });
    }

    $('#btnSubmitRestoreStay').on('click', function() {
        submitRestore(false);
    });

    $('#btnSubmitRestoreEdit').on('click', function() {
        submitRestore(true);
    });
});
</script>
<?= $this->endSection() ?>

