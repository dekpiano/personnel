<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary-accent: #007bff;
        --secondary-accent: #0056b3;
        --soft-bg: #f8faff;
    }

    .breadcrumb-custom {
        background: white;
        padding: 12px 24px;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    /* Teacher List Item */
    .teacher-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 1rem;
        border: 1px solid rgba(0,0,0,0.02);
    }
    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.1) !important;
        border-color: rgba(0, 123, 255, 0.1);
    }

    .teacher-img-wrapper {
        position: relative;
        padding: 5px;
        background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
        border-radius: 18px;
        display: inline-block;
    }
    .teacher-img {
        object-fit: cover;
        border-radius: 15px;
        border: 3px solid #fff;
        background: #f8f9fa;
    }

    .teacher-details h5 {
        color: #2c3e50;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .info-item {
        margin-bottom: 4px;
        font-size: 0.9rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-item i {
        color: var(--primary-accent);
        font-size: 1.1rem;
    }

    /* Sortable Handle */
    .drag-handle {
        cursor: grab;
        padding: 10px;
        color: #dee2e6;
        transition: color 0.2s;
    }
    .teacher-card:hover .drag-handle {
        color: var(--primary-accent);
    }
    .drag-handle:active {
        cursor: grabbing;
    }

    /* Buttons */
    .btn-edit-premium {
        background: var(--primary-accent);
        color: white;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
    }
    .btn-edit-premium:hover {
        background: #3f44a1;
        transform: scale(1.05);
        color: white;
        box-shadow: 0 5px 15px rgba(78, 84, 200, 0.3);
    }

    .empty-container {
        padding: 5rem 1rem;
        background: white;
        border-radius: 30px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
    }
    .empty-illustration {
        width: 250px;
        margin-bottom: 2rem;
        opacity: 0.7;
    }

    /* Action Buttons Group */
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    .btn-action-premium {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        color: white;
    }
    .btn-action-view { background: #696cff; }
    .btn-action-pdf { background: #ff3e1d; }
    .btn-action-edit { background: #03c3ec; }
    
    .btn-action-premium:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        color: white;
    }
    .btn-action-premium i { font-size: 1.25rem; }

    /* Profile Preview Modal */
    .profile-header-preview {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem;
    }
    .modal-content-rounded {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }
    .preview-img-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #f8f9fa;
    }
</style>

<div class="breadcrumb-custom d-flex justify-content-between align-items-center flex-wrap gap-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('Admin/Home') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('Admin/WorkPerson/Personnel') ?>">Personnel</a></li>
            <li class="breadcrumb-item active fw-bold" aria-current="page"><?= $title; ?></li>
        </ol>
    </nav>
    <div class="text-muted small">
        <i class='bx bx-info-circle me-1'></i> ลากและวางเพื่อสลับลำดับการแสดงผล
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div id="items" class="sortable">
            <?php if(empty($Teacher)): ?>
                <div class="empty-container">
                    <img class="lozad empty-illustration" data-src="<?= base_url('assets/img/illustrations/page-misc-under-maintenance.png') ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" alt="No data">
                    <h3 class="fw-bold">ไม่พบข้อมูลบุคลากร</h3>
                    <p class="text-muted mb-0">ยังไม่มีรายชื่อบุคลากรในกลุ่มหรือประเภทงานนี้</p>
                    <a href="<?= base_url('Admin/WorkPerson/Personnel') ?>" class="btn btn-outline-primary mt-4 rounded-pill px-4">กลับหน้าหลัก</a>
                </div>
            <?php else: ?>
                <?php foreach ($Teacher as $key => $v_Teacher) : ?>
                <div class="card teacher-card shadow-sm mb-3" data-id="<?= $v_Teacher->pers_id; ?>">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="drag-handle">
                                    <i class='bx bx-grid-vertical fs-3'></i>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="teacher-img-wrapper">
                                    <img class="lozad teacher-img" data-src="<?= base_url('uploads/admin/Personnal/' . ($v_Teacher->pers_img ?: 'default.png')) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" alt="user-avatar"
                                        height="90" width="90">
                                </div>
                            </div>
                            <div class="col teacher-details ps-lg-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h5 class="mb-0"><?= $v_Teacher->pers_prefix . $v_Teacher->pers_firstname . ' ' . $v_Teacher->pers_lastname ?></h5>
                                    <span class="badge bg-label-secondary small" style="font-size: 0.65rem;">ID: <?= $v_Teacher->pers_id ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-briefcase-alt-2'></i> 
                                    <span><?= $v_Teacher->posi_name ?></span>
                                </div>
                                <?php if ($Teach) : ?>
                                <div class="info-item">
                                    <i class='bx bx-award'></i> 
                                    <span>วิทยฐานะ: <span class="badge bg-label-info"><?= $v_Teacher->pers_academic ?: 'ไม่มี' ?></span></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-auto text-end me-lg-3">
                                <div class="action-group">
                                    <button type="button" class="btn-action-premium btn-action-view view-profile-btn" 
                                            data-id="<?= $v_Teacher->pers_id ?>" 
                                            data-bs-toggle="tooltip" title="ดูรายละเอียดด่วน">
                                        <i class="bx bx-show-alt"></i>
                                    </button>
                                    <a href="<?= base_url('Admin/WorkPerson/Personnel/PDF/' . $v_Teacher->pers_id) ?>" 
                                       target="_blank"
                                       class="btn-action-premium btn-action-pdf" 
                                       data-bs-toggle="tooltip" title="พิมพ์แบบ กพ.7 (PDF)">
                                        <i class="bx bxs-file-pdf"></i>
                                    </a>
                                    <a href="<?= base_url('Admin/WorkPerson/Personnel/Update/' . $v_Teacher->pers_id) ?>" 
                                       class="btn-action-premium btn-action-edit" 
                                       data-bs-toggle="tooltip" title="แก้ไขข้อมูลหลัก">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Profile Preview -->
<div class="modal fade" id="profilePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-content-rounded shadow-lg">
            <div class="profile-header-preview d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <img id="preview_pers_img" src="" class="preview-img-circle" alt="Profile">
                    <div>
                        <h3 class="mb-1 fw-bold text-white" id="preview_fullname">-</h3>
                        <p class="mb-0 opacity-75 d-flex align-items-center gap-2">
                             <i class='bx bx-id-card'></i> ID: <span id="preview_pers_id">-</span> | 
                             <i class='bx bx-briefcase-alt-2'></i> <span id="preview_posi_name">-</span>
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-preview-general">
                                <i class="bx bx-user me-1"></i> ทั่วไป
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-preview-edu">
                                <i class="bx bx-book-reader me-1"></i> การศึกษา
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-preview-work">
                                <i class="bx bx-history me-1"></i> ประวัติงาน
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-preview-deco">
                                <i class="bx bx-medal me-1"></i> เครื่องราชฯ
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-preview-training">
                                <i class="bx bx-book-open me-1"></i> อบรม
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content border-0">
                        <div class="tab-pane fade show active" id="navs-preview-general" role="tabpanel">
                            <div class="row g-4 p-4">
                                <div class="col-md-6 border-end">
                                    <h6 class="fw-bold text-primary mb-3">ข้อมูลส่วนตัว</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr><td width="35%">วันเกิด:</td><td class="fw-bold" id="preview_birthday">-</td></tr>
                                        <tr><td>เลขบัตรประชาชน:</td><td class="fw-bold" id="preview_id_card">-</td></tr>
                                        <tr><td>สัญชาติ/เชื้อชาติ:</td><td class="fw-bold" id="preview_nat_race">-</td></tr>
                                        <tr><td>ศาสนา:</td><td class="fw-bold" id="preview_religion">-</td></tr>
                                        <tr><td>สถานภาพ:</td><td class="fw-bold" id="preview_marital">-</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary mb-3">การติดต่อ / อื่นๆ</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr><td width="35%">เบอร์โทรศัพท์:</td><td class="fw-bold" id="preview_phone">-</td></tr>
                                        <tr><td>Line ID:</td><td class="fw-bold" id="preview_line">-</td></tr>
                                        <tr><td>ใบอนุญาตวิชาชีพ:</td><td id="preview_license">-</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3" id="navs-preview-edu" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="preview_edu_table">
                                    <thead><tr class="bg-light"><th>ปี</th><th>วุฒิ</th><th>วิชาเอก</th><th>สถาบัน</th></tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3" id="navs-preview-work" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="preview_work_table">
                                    <thead><tr class="bg-light"><th>วันที่</th><th>ตำแหน่ง</th><th>วิทยฐานะ</th><th>สังกัด</th></tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3" id="navs-preview-deco" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="preview_deco_table">
                                    <thead><tr class="bg-light"><th>ปี</th><th>ชั้นตรา</th><th>ราชกิจจานุเบกษา</th></tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3" id="navs-preview-training" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="preview_training_table">
                                    <thead><tr class="bg-light"><th>วันที่</th><th>หัวข้อการอบรม</th><th>สถานที่</th><th>ชม.</th></tr></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-label-secondary px-4 hstack gap-2" data-bs-dismiss="modal">
                     <i class='bx bx-x'></i> ปิดหน้าต่าง
                </button>
                <a id="preview_full_edit_link" href="#" class="btn btn-primary px-4 hstack gap-2">
                    <i class='bx bx-edit'></i> แก้ไขข้อมูลฉบับเต็ม
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.2"></script>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>
<?= $this->endSection() ?>

