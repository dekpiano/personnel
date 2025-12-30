<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.4);
        --header-gradient: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        --primary-gradient: linear-gradient(135deg, #007bff 0%, #00d2ff 100%);
        --accent-pink: #FF4B91;
        --accent-blue: #007bff;
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
        color: #4e54c8;
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
        <div class="mt-4 mt-md-0 d-flex gap-2">
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
                            <img class="lozad rounded-circle border-2 border-white" data-src="<?= base_url('uploads/admin/Personnal/' . $value) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" alt="Avatar">
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
                            <img class="lozad rounded-circle border-2 border-white" data-src="<?= base_url('uploads/admin/Personnal/' . $value) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" alt="Avatar">
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
                            <img class="lozad rounded-circle border-2 border-white" data-src="<?= base_url('uploads/admin/Personnal/' . $value) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" alt="Avatar">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.3"></script>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>
<?= $this->endSection() ?>

