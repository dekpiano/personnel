<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR PA AGREEMENT MANAGEMENT
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --pa-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
        --pa-card-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.1), 0 8px 10px -6px rgba(2, 132, 199, 0.06);
    }

    /* Hero Banner Card */
    .pa-hero-card {
        background: var(--pa-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .pa-hero-card::before {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
        top: -80px;
        right: -60px;
        pointer-events: none;
    }

    .pa-hero-card::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(56, 189, 248, 0) 70%);
        bottom: -50px;
        left: 20%;
        pointer-events: none;
    }

    .glass-badge {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 30px;
    }

    /* Stat KPI Cards */
    .pa-stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .pa-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(2, 132, 199, 0.12);
        border-color: #bae6fd;
    }

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        transition: transform 0.3s ease;
    }

    .pa-stat-card:hover .stat-icon-wrap {
        transform: scale(1.1) rotate(4deg);
    }

    /* Learning Group Accordion Section Cards */
    .learning-section-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 1.25rem;
        background: #ffffff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .learning-section-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .learning-section-header {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 0.95rem 1.4rem;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s ease;
    }

    .learning-section-header:hover {
        background: #e2e8f0;
    }

    .transition-arrow {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .collapsed .transition-arrow {
        transform: rotate(-90deg);
    }

    /* Table Styling */
    .table-lux thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 750;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
    }

    .table-lux tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-lux tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-lux tbody tr:hover {
        background-color: #f0f9ff !important;
    }

    /* Modal Dropzone */
    .dropzone-box-modal {
        border: 2px dashed #93c5fd;
        border-radius: 16px;
        padding: 32px 20px;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.25s ease-in-out;
    }

    .dropzone-box-modal:hover, .dropzone-box-modal.dragover {
        border-color: #0284c7;
        background-color: #e0f2fe;
        transform: scale(1.01);
    }

    /* High Contrast Status Badges */
    .badge-pa-uploaded {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #86efac !important;
        font-weight: 750;
    }

    .badge-pa-pending {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fcd34d !important;
        font-weight: 750;
    }
</style>

<!-- Hero Banner Card -->
<div class="pa-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-award me-1"></i> ระบบประเมิน PA 1
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-calendar me-1"></i> ปีงบประมาณ พ.ศ. <?= $fiscal_year; ?>
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                จัดการข้อตกลงในการพัฒนางาน (PA) รายบุคคล
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                ศูนย์จัดการและนำเข้าไฟล์แบบบันทึกข้อตกลง PA1 ของข้าราชการครู แยกตามกลุ่มสาระการเรียนรู้ ประจำปีงบประมาณ พ.ศ. <?= $fiscal_year; ?>
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <!-- Year Selector Form -->
            <form method="GET" action="<?= base_url('Admin/PaAgreement'); ?>" class="d-flex align-items-center">
                <div class="input-group input-group-sm shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <span class="input-group-text bg-white border-0 fw-bold text-primary" style="font-size: 0.82rem;">
                        <i class="bx bx-calendar-event me-1"></i> ปีงบฯ
                    </span>
                    <select name="fiscal_year" id="fiscal_year" class="form-select form-select-sm border-0 fw-bold bg-white text-dark shadow-none" onchange="this.form.submit()">
                        <?php foreach ($available_years as $y): ?>
                            <option value="<?= $y; ?>" <?= ($y == $fiscal_year) ? 'selected' : ''; ?>>
                                พ.ศ. <?= $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <button type="button" class="btn btn-sm btn-danger shadow-sm fw-bold px-3 py-2" id="btnCleanJunkFiles" style="border-radius: 10px;">
                <i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ
            </button>
        </div>
    </div>
</div>

<!-- Quick Stat Cards -->
<?php 
    $percentUploaded = $total_teachers > 0 ? round(($uploaded_count / $total_teachers) * 100, 1) : 0;
?>
<div class="row g-3 mb-4">
    <!-- Card 1: Total Teachers -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pa-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ข้าราชการครูทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-dark" data-counter="<?= $total_teachers; ?>"><?= number_format($total_teachers); ?></h3>
                    <div class="x-small text-muted mt-1">ประจำปีงบฯ พ.ศ. <?= $fiscal_year ?></div>
                </div>
                <div class="stat-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bx bx-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Uploaded -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pa-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ส่งไฟล์ PA1 แล้ว</span>
                    <h3 class="fw-bold mb-0 text-success" data-counter="<?= $uploaded_count; ?>"><?= number_format($uploaded_count); ?></h3>
                    <div class="x-small text-success fw-bold mt-1">คิดเป็น <?= $percentUploaded ?>%</div>
                </div>
                <div class="stat-icon-wrap" style="background: #dcfce7; color: #16a34a;">
                    <i class="bx bx-check-double"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Pending -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pa-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ยังไม่มีไฟล์ (รอดำเนินการ)</span>
                    <h3 class="fw-bold mb-0 text-warning" data-counter="<?= $pending_count; ?>"><?= number_format($pending_count); ?></h3>
                    <div class="x-small text-warning fw-bold mt-1">เหลืออีก <?= number_format($pending_count); ?> คน</div>
                </div>
                <div class="stat-icon-wrap" style="background: #fef3c7; color: #d97706;">
                    <i class="bx bx-time-five"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Progress Indicator -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pa-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold small">อัตราความสำเร็จรวม</span>
                <span class="fw-bold text-primary fs-6"><?= $percentUploaded ?>%</span>
            </div>
            <div class="progress" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentUploaded ?>%; border-radius: 10px;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center x-small text-muted mt-2">
                <span>ส่งแล้ว <?= $uploaded_count ?>/<?= $total_teachers ?> คน</span>
                <span class="text-primary fw-bold">เป้าหมาย 100%</span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Hub -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
        <div class="row g-2 align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0 ps-3"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control bg-light border-0 py-2 shadow-none" placeholder="ค้นหาชื่อ - สกุล, ตำแหน่ง หรือรหัสบุคลากร...">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <select id="filterLearning" class="form-select form-select-sm bg-light border-0 py-2 shadow-none fw-semibold">
                    <option value="">-- แสดงทุกกลุ่มสาระการเรียนรู้ --</option>
                    <?php foreach ($grouped_teachers as $g): ?>
                        <option value="<?= esc($g['lear_id']); ?>"><?= esc($g['lear_name']); ?> (<?= $g['total'] ?> คน)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-12 d-flex gap-2">
                <select id="filterStatus" class="form-select form-select-sm bg-light border-0 py-2 shadow-none fw-semibold">
                    <option value="">-- สถานะทั้งหมด --</option>
                    <option value="uploaded">เฉพาะที่มีไฟล์แล้ว (<?= $uploaded_count ?>)</option>
                    <option value="pending">เฉพาะที่ยังไม่มีไฟล์ (<?= $pending_count ?>)</option>
                </select>
                <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap px-3" id="btnToggleAllGroups" title="ขยาย/ยุบกลุ่มทั้งหมด">
                    <i class="bx bx-expand-vertical"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Teachers Grouped by Learning Area -->
<div id="learningGroupsContainer">
    <?php if (empty($grouped_teachers)): ?>
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body text-center py-5 text-muted">
                <i class="bx bx-user-x fs-1 mb-2 text-secondary"></i>
                <div class="fw-bold">ไม่พบข้อมูลข้าราชการครูในปีงบประมาณนี้</div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($grouped_teachers as $grpKey => $grp): ?>
            <?php 
                $grpPercent = $grp['total'] > 0 ? round(($grp['uploaded'] / $grp['total']) * 100) : 0;
            ?>
            <div class="learning-section-card" data-lear-id="<?= esc($grp['lear_id']) ?>">
                <!-- Group Header -->
                <div class="learning-section-header d-flex justify-content-between align-items-center flex-wrap gap-2" data-bs-toggle="collapse" data-bs-target="#collapseGrp_<?= $grpKey ?>">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            <i class="bx bxs-folder"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark fs-6"><?= esc($grp['lear_name']); ?></span>
                            <span class="badge bg-label-secondary rounded-pill ms-1 px-2 py-0" style="font-size: 0.72rem;"><?= $grp['total'] ?> คน</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-pa-uploaded rounded-pill px-2 py-1 small">
                            <i class="bx bx-check me-1"></i>ส่งแล้ว: <span class="grp-uploaded-cnt"><?= $grp['uploaded'] ?></span>
                        </span>
                        <span class="badge badge-pa-pending rounded-pill px-2 py-1 small">
                            <i class="bx bx-time me-1"></i>รอส่ง: <span class="grp-pending-cnt"><?= $grp['pending'] ?></span>
                        </span>
                        <span class="badge bg-label-primary rounded-pill px-2 py-1 small">
                            <?= $grpPercent ?>%
                        </span>
                        <i class="bx bx-chevron-down fs-4 text-muted transition-arrow ms-1"></i>
                    </div>
                </div>

                <!-- Table Content -->
                <div id="collapseGrp_<?= $grpKey ?>" class="collapse show">
                    <div class="table-responsive">
                        <table class="table table-hover table-lux align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width: 50px;">#</th>
                                    <th>ข้าราชการครูผู้รับการประเมิน</th>
                                    <th>ตำแหน่ง / วิทยฐานะ</th>
                                    <th class="text-center" style="width: 170px;">สถานะไฟล์ PA</th>
                                    <th class="text-center pe-3" style="width: 180px;">จัดการไฟล์ PA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $idx = 1; foreach ($grp['teachers'] as $t): ?>
                                    <?php 
                                        $hasFile = !empty($t['pa_agreement']['pa_file_pa1']);
                                        $fileName = $t['pa_agreement']['pa_file_pa1'] ?? '';
                                        $paId = $t['pa_agreement']['pa_id'] ?? '';
                                        $fullName = trim(($t['pers_prefix'] ?? '') . $t['pers_firstname'] . ' ' . $t['pers_lastname']);
                                    ?>
                                    <tr class="teacher-row" 
                                        data-lear-id="<?= esc($grp['lear_id']) ?>" 
                                        data-status="<?= $hasFile ? 'uploaded' : 'pending' ?>">
                                        <td class="ps-3 text-muted fw-bold"><?= $idx++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-label-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold text-primary">
                                                    <?= mb_substr($t['pers_firstname'], 0, 1, 'UTF-8') ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark teacher-name"><?= esc($fullName) ?></div>
                                                    <div class="x-small text-muted">รหัสประจำตัว: <?= esc($t['pers_id']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary small fw-bold"><?= esc($t['posi_name'] ?? 'ครู') ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($hasFile): ?>
                                                <span class="badge badge-pa-uploaded rounded-pill px-3 py-1">
                                                    <i class="bx bx-check-circle me-1"></i> มีไฟล์แล้ว
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-pa-pending rounded-pill px-3 py-1">
                                                    <i class="bx bx-time-five me-1"></i> ยังไม่มีไฟล์
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                <?php if ($hasFile): ?>
                                                    <a href="<?= env('upload.server.baseurl.pa_agreement') . $fiscal_year . '/pa1/' . $fileName ?>" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-icon btn-label-primary rounded-pill shadow-none" 
                                                       data-bs-toggle="tooltip"
                                                       title="เปิดดูไฟล์ข้อตกลง PA">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-icon btn-label-info rounded-pill shadow-none btn-open-upload-modal" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-has-file="1" 
                                                            data-file-name="<?= esc($fileName) ?>" 
                                                            data-bs-toggle="tooltip"
                                                            title="อัปโหลดไฟล์ใหม่แทนที่">
                                                        <i class="bx bx-upload"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-icon btn-label-danger rounded-pill shadow-none btn-delete-pa" 
                                                            data-pa-id="<?= esc($paId) ?>" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-bs-toggle="tooltip"
                                                            title="ลบไฟล์ PA">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary rounded-pill px-3 py-1 btn-open-upload-modal shadow-sm fw-bold" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-has-file="0">
                                                        <i class="bx bx-cloud-upload me-1"></i> อัปโหลด
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadPaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="uploadModalTitle">
                    <i class="bx bx-cloud-upload fs-4"></i> อัปโหลดไฟล์ข้อตกลง PA (PA1)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 rounded-3 mb-3" style="background: #f0f9ff; border: 1px solid #bae6fd;">
                    <div class="small text-muted mb-1">คุณครูผู้รับการประเมิน:</div>
                    <h6 class="fw-bold text-primary mb-0 fs-6" id="modalTeacherName">-</h6>
                    <div class="x-small text-muted mt-1">ประจำปีงบประมาณ: <span class="fw-bold text-dark">พ.ศ. <?= $fiscal_year ?></span></div>
                </div>

                <div id="existingFileAlert" class="alert alert-warning py-2 px-3 small d-none" style="border-radius: 10px;">
                    <i class="bx bx-info-circle me-1"></i> มีไฟล์เดิมในระบบแล้ว หากอัปโหลดใหม่ไฟล์เดิมจะถูกเขียนทับโดยอัตโนมัติ
                </div>

                <input type="file" id="modalFileInput" accept=".pdf" class="d-none">
                
                <div class="dropzone-box-modal" id="modalDropzone">
                    <i class="bx bxs-file-pdf text-danger fs-1 mb-2"></i>
                    <div class="small fw-bold text-dark">ลากและวางไฟล์ PDF ข้อตกลง PA ที่นี่</div>
                    <div class="x-small text-muted">หรือคลิกเพื่อเลือกไฟล์ (PDF สูงสุด 20MB)</div>
                </div>

                <div class="file-selected-card mt-3 d-none p-3 border rounded-3 bg-white" id="modalFileIndicator">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center text-truncate me-2">
                            <i class="bx bxs-file-pdf text-danger fs-3 me-2"></i>
                            <div class="text-truncate">
                                <div class="small fw-bold text-truncate" id="selectedFileName">-</div>
                                <div class="x-small text-muted" id="selectedFileSize">-</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-xs btn-icon btn-label-secondary rounded-circle" id="modalRemoveFile">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>

                <div class="progress mt-3 d-none" id="modalProgress" style="height: 10px; border-radius: 8px;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated fw-bold" role="progressbar" style="width: 0%; font-size: 0.7rem;"></div>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4 bg-light">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold" id="modalSubmitBtn" disabled>
                    <i class="bx bx-save me-1"></i> บันทึกไฟล์ PA
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const year = '<?= $fiscal_year ?>';
        const CHUNK_SIZE = 512 * 1024; // 512KB per chunk (bypass 1MB server limit)
        let currentTargetTeacherId = null;
        let selectedFile = null;

        // Animated Counters
        $('[data-counter]').each(function() {
            const $this = $(this);
            const target = parseInt($this.attr('data-counter'), 10) || 0;
            if (target === 0) return;
            $({ countNum: 0 }).animate({ countNum: target }, {
                duration: 1000,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum).toLocaleString());
                },
                complete: function() {
                    $this.text(target.toLocaleString());
                }
            });
        });

        // Initialize Bootstrap Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Toggle Expand/Collapse All Groups
        let allExpanded = true;
        $('#btnToggleAllGroups').on('click', function() {
            if (allExpanded) {
                $('.learning-section-card .collapse').collapse('hide');
                $(this).html('<i class="bx bx-collapse-vertical"></i>');
                allExpanded = false;
            } else {
                $('.learning-section-card .collapse').collapse('show');
                $(this).html('<i class="bx bx-expand-vertical"></i>');
                allExpanded = true;
            }
        });

        // Filter Functionality
        function filterRows() {
            const search = $('#searchInput').val().toLowerCase().trim();
            const selectedLearId = $('#filterLearning').val();
            const status = $('#filterStatus').val();

            $('.learning-section-card').each(function() {
                const grpCard = $(this);
                const grpLearId = grpCard.data('lear-id');
                let visibleRowsInGroup = 0;

                let matchGroupFilter = !selectedLearId || String(grpLearId) === String(selectedLearId);

                if (!matchGroupFilter) {
                    grpCard.hide();
                    return;
                }

                grpCard.find('.teacher-row').each(function() {
                    const row = $(this);
                    const name = row.find('.teacher-name').text().toLowerCase();
                    const rowStatus = row.data('status');

                    let matchSearch = !search || name.includes(search);
                    let matchStatus = !status || rowStatus === status;

                    if (matchSearch && matchStatus) {
                        row.show();
                        visibleRowsInGroup++;
                    } else {
                        row.hide();
                    }
                });

                if (visibleRowsInGroup > 0) {
                    grpCard.show();
                } else {
                    grpCard.hide();
                }
            });
        }

        $('#searchInput').on('keyup', filterRows);
        $('#filterLearning, #filterStatus').on('change', filterRows);

        // Open Upload Modal
        $('.btn-open-upload-modal').on('click', function() {
            currentTargetTeacherId = $(this).data('teacher-id');
            const teacherName = $(this).data('teacher-name');
            const hasFile = $(this).data('has-file');

            $('#modalTeacherName').text(teacherName);
            if (hasFile == 1) {
                $('#existingFileAlert').removeClass('d-none');
            } else {
                $('#existingFileAlert').addClass('d-none');
            }

            resetModalForm();
            $('#uploadPaModal').modal('show');
        });

        function resetModalForm() {
            selectedFile = null;
            $('#modalFileInput').val('');
            $('#modalFileIndicator').addClass('d-none');
            $('#modalDropzone').show();
            $('#modalProgress').addClass('d-none').find('.progress-bar').css('width', '0%').text('0%');
            $('#modalSubmitBtn').prop('disabled', true).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
        }

        // Dropzone & File Selection
        const dropzone = $('#modalDropzone');
        const fileInput = $('#modalFileInput');

        dropzone.on('click', () => fileInput.trigger('click'));

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone[0].addEventListener(eventName, (e) => { e.preventDefault(); e.stopPropagation(); }, false);
        });

        dropzone.on('dragenter dragover', () => dropzone.addClass('dragover'));
        dropzone.on('dragleave drop', () => dropzone.removeClass('dragover'));

        dropzone[0].addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) handleSelectedFile(files[0]);
        });

        fileInput.on('change', function() {
            if (this.files.length > 0) handleSelectedFile(this.files[0]);
        });

        function handleSelectedFile(file) {
            if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
                Swal.fire('ข้อผิดพลาด', 'กรุณาเลือกเฉพาะไฟล์ PDF เท่านั้น', 'warning');
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                Swal.fire('ข้อผิดพลาด', 'ขนาดไฟล์ต้องไม่เกิน 20MB', 'warning');
                return;
            }

            selectedFile = file;
            $('#selectedFileName').text(file.name);
            $('#selectedFileSize').text((file.size / (1024 * 1024)).toFixed(2) + ' MB');
            $('#modalDropzone').hide();
            $('#modalFileIndicator').removeClass('d-none');
            $('#modalSubmitBtn').prop('disabled', false);
        }

        $('#modalRemoveFile').on('click', resetModalForm);

        // Upload Helper (Chunked Upload)
        async function uploadChunked(file, teacherId) {
            const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
            const timestamp = Math.floor(Date.now() / 1000);
            const targetFilename = `PA_${year}_${teacherId}_${timestamp}.pdf`;
            const targetPath = `personnel/teacher/pa_agreement/${year}/pa1`;

            $('#modalProgress').removeClass('d-none');
            const progressBar = $('#modalProgress').find('.progress-bar');

            let finalSavedName = targetFilename;

            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('path', targetPath);
                formData.append('filename', targetFilename);
                formData.append('chunk', chunkIndex);
                formData.append('chunks', totalChunks);

                const res = await $.ajax({
                    url: '<?= base_url('Admin/PaAgreement/upload-chunk') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json'
                });

                if (res.status !== 'success' && res.status !== 'chunk_saved') {
                    throw new Error(res.message || 'อัปโหลดไฟล์ไม่สำเร็จ');
                }

                if (chunkIndex === totalChunks - 1 && res.filename) {
                    finalSavedName = res.filename;
                }

                const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                progressBar.css('width', percent + '%').text(percent + '%');
            }

            return finalSavedName;
        }

        // Submit Upload
        $('#modalSubmitBtn').on('click', async function() {
            if (!selectedFile || !currentTargetTeacherId) return;

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังอัปโหลด...');

            try {
                const uploadedFilename = await uploadChunked(selectedFile, currentTargetTeacherId);

                $.ajax({
                    url: '<?= base_url('Admin/PaAgreement/save-file') ?>',
                    type: 'POST',
                    data: {
                        teacher_id: currentTargetTeacherId,
                        pa_year: year,
                        uploaded_pa1_filename: uploadedFilename
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false })
                                .then(() => location.reload());
                        } else {
                            btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
                            Swal.fire('ผิดพลาด', res.message, 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
                        Swal.fire('ผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                    }
                });
            } catch (err) {
                btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
                $('#modalProgress').addClass('d-none');
                Swal.fire('ผิดพลาด', err.message, 'error');
            }
        });

        // Delete PA File
        $('.btn-delete-pa').on('click', function() {
            const paId = $(this).data('pa-id');
            const teacherId = $(this).data('teacher-id');
            const teacherName = $(this).data('teacher-name');

            Swal.fire({
                title: 'ยืนยันการลบไฟล์ PA?',
                text: `คุณต้องการลบไฟล์บันทึกข้อตกลง PA ของ "${teacherName}" ประจำปีงบประมาณ พ.ศ. ${year} ใช่หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('Admin/PaAgreement/delete-file') ?>',
                        type: 'POST',
                        data: { pa_id: paId, teacher_id: teacherId, pa_year: year },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1200, showConfirmButton: false })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('ผิดพลาด', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('ผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
                        }
                    });
                }
            });
        });

        // Clean Junk & Orphan Files
        $('#btnCleanJunkFiles').on('click', function() {
            const btn = $(this);
            Swal.fire({
                title: 'ยืนยันการล้างไฟล์ขยะ?',
                text: 'ระบบจะล้างไฟล์ชั่วคราว (Chunks) ที่อัปโหลดตกค้างบนเซิร์ฟเวอร์',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ล้างไฟล์ขยะทันที',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังล้าง...');
                    $.ajax({
                        url: '<?= base_url('Admin/PaAgreement/clean-junk') ?>',
                        type: 'POST',
                        data: { pa_year: year },
                        dataType: 'json',
                        success: function(res) {
                            btn.prop('disabled', false).html('<i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ');
                            if (res.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message });
                            } else {
                                Swal.fire('ผิดพลาด', res.message, 'error');
                            }
                        },
                        error: function() {
                            btn.prop('disabled', false).html('<i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ');
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>