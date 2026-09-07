<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       ULTRA LUXURY UI/UX DESIGN SYSTEM - PA INSPECTION HUB
       Font: Google K2D + Outfit Numbers
       Theme: Deep Royal Blue Gradient & Luxury Modern Glass
       ==================================================== */
    @import url('https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Outfit:wght@400;500;600;700;800&display=swap');

    :root {
        --pa-primary: #1d4ed8;
        --pa-primary-light: #0284c7;
        --pa-primary-dark: #0f172a;
        --pa-blue-grad: linear-gradient(135deg, #1e40af 0%, #0284c7 50%, #0369a1 100%);
        --pa-bg-glass: rgba(255, 255, 255, 0.85);
        --pa-shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
        --pa-shadow-md: 0 8px 24px -4px rgba(2, 132, 199, 0.12), 0 4px 8px -2px rgba(0, 0, 0, 0.04);
        --pa-shadow-lg: 0 20px 35px -8px rgba(2, 132, 199, 0.22);
    }

    * {
        font-family: 'K2D', sans-serif !important;
    }

    .num-font {
        font-family: 'Outfit', 'K2D', sans-serif !important;
    }

    /* ===== HERO BANNER ===== */
    .pa-hero-banner {
        background: var(--pa-blue-grad);
        border-radius: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--pa-shadow-lg);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 2.2rem 2.2rem;
    }

    .pa-hero-banner::before {
        content: '';
        position: absolute;
        width: 360px;
        height: 360px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        top: -100px;
        right: -80px;
        pointer-events: none;
    }

    .pa-hero-banner::after {
        content: '';
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.3) 0%, rgba(56, 189, 248, 0) 70%);
        bottom: -90px;
        left: 20%;
        pointer-events: none;
    }

    .glass-badge-pill {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 50px;
        padding: 0.35rem 1rem;
        font-size: 0.78rem;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
    }

    /* ===== KPI STAT CARDS ===== */
    .stat-kpi-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 1.35rem 1.4rem;
        box-shadow: var(--pa-shadow-sm);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--pa-shadow-md);
        border-color: #bae6fd;
    }

    .kpi-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-kpi-card:hover .kpi-icon-box {
        transform: scale(1.12) rotate(6deg);
    }

    /* Gradient Icon Backgrounds */
    .icon-box-blue {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1d4ed8;
    }

    .icon-box-green {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
    }

    .icon-box-purple {
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        color: #be185d;
    }

    .icon-box-amber {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
    }

    /* ===== SEARCH & FILTER HUB ===== */
    .filter-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--pa-shadow-sm);
    }

    .custom-input-search {
        background-color: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 0.6rem 1rem !important;
        font-size: 0.9rem !important;
        transition: all 0.25s ease;
    }

    .custom-input-search:focus {
        background-color: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3.5px rgba(2, 132, 199, 0.15) !important;
    }

    .custom-select-filter {
        background-color: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 0.6rem 1rem !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        color: #334155 !important;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .custom-select-filter:focus {
        background-color: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3.5px rgba(2, 132, 199, 0.15) !important;
    }

    /* ===== LEARNING GROUP CARD ===== */
    .group-accordion-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--pa-shadow-sm);
        margin-bottom: 1.35rem;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .group-accordion-card:hover {
        border-color: #cbd5e1;
        box-shadow: var(--pa-shadow-md);
    }

    .group-accordion-header {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.1rem 1.4rem;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
    }

    .group-accordion-header:hover {
        background: #f1f5f9;
    }

    .chevron-rotate {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .collapsed .chevron-rotate {
        transform: rotate(-90deg);
    }

    /* ===== MODERN TABLE DESIGN ===== */
    .modern-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        padding: 0.85rem 1rem;
    }

    .modern-table tbody td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f0f9ff !important;
    }

    /* ===== FILE CHIP BUTTONS ===== */
    .btn-file-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 9px;
        border-radius: 8px;
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid transparent;
        line-height: 1.2;
    }

    .btn-file-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    .chip-pres-active {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        color: #be185d !important;
        border-color: #fbcfe8;
    }
    .chip-pres-active:hover {
        background: #be185d;
        color: #ffffff !important;
        border-color: #be185d;
    }

    .chip-plan-active {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        color: #15803d !important;
        border-color: #bbf7d0;
    }
    .chip-plan-active:hover {
        background: #15803d;
        color: #ffffff !important;
        border-color: #15803d;
    }

    .chip-pa1-active {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8 !important;
        border-color: #bfdbfe;
    }
    .chip-pa1-active:hover {
        background: #1d4ed8;
        color: #ffffff !important;
        border-color: #1d4ed8;
    }

    .chip-disabled {
        background: #f8fafc;
        color: #94a3b8 !important;
        border: 1px dashed #cbd5e1;
        opacity: 0.75;
        cursor: not-allowed;
    }

    .btn-action-round {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-action-round:hover {
        transform: scale(1.12);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    /* ===== STATUS PILLS ===== */
    .status-pill {
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 750;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-pill-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .status-pill-info {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #7dd3fc;
    }

    .status-pill-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .status-pill-rose {
        background: #fdf2f8;
        color: #be185d;
        border: 1px solid #fbcfe8;
    }

    .status-pill-purple {
        background: #f3e8ff;
        color: #6b21a8;
        border: 1px solid #d8b4fe;
    }

    /* Dropzone */
    .dropzone-lux {
        border: 2.5px dashed #93c5fd;
        border-radius: 18px;
        padding: 36px 20px;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .dropzone-lux:hover, .dropzone-lux.dragover {
        border-color: #0284c7;
        background-color: #f0f9ff;
        transform: scale(1.01);
    }
</style>

<!-- 1. HERO BANNER CARD -->
<div class="pa-hero-banner mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge-pill">
                    <i class="bx bxs-check-shield fs-6 me-1 text-warning"></i> ระบบตรวจการส่งงาน PA
                </span>
                <span class="glass-badge-pill">
                    <i class="bx bx-calendar fs-6 me-1"></i> ปีงบประมาณ พ.ศ. <?= esc($fiscal_year); ?>
                </span>
            </div>
            <h3 class="fw-bold text-white mb-2" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                ศูนย์ตรวจการส่งงานและข้อตกลง PA ข้าราชการครู
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 680px; font-size: 0.95rem; line-height: 1.55;">
                ตรวจความครบถ้วนของเอกสาร PA (สื่อนำเสนอ, แผนการจัดการเรียนรู้, ข้อตกลง PA1) พร้อมพรีวิวและดาวน์โหลดไฟล์ แยกตามกลุ่มสาระการเรียนรู้
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <!-- Year Selector Form -->
            <form method="GET" action="<?= base_url('Admin/PaAgreement'); ?>" class="d-flex align-items-center">
                <div class="input-group input-group-sm shadow-sm" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
                    <span class="input-group-text bg-white border-0 fw-bold text-primary pe-1" style="font-size: 0.85rem;">
                        <i class="bx bx-calendar-event me-1"></i> ปีงบฯ
                    </span>
                    <select name="fiscal_year" id="fiscal_year" class="form-select form-select-sm border-0 fw-bold bg-white text-dark shadow-none ps-1" onchange="this.form.submit()">
                        <?php foreach ($available_years as $y): ?>
                            <option value="<?= $y; ?>" <?= ($y == $fiscal_year) ? 'selected' : ''; ?>>
                                พ.ศ. <?= $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <!-- Export Excel Report Button -->
            <a href="<?= base_url('Admin/PaAgreement/exportExcel?fiscal_year=' . $fiscal_year); ?>" class="btn btn-sm btn-success shadow-sm fw-bold px-3 py-2 text-white" style="border-radius: 12px;" title="ดาวน์โหลดรายงานสรุปการส่งงานแบบ Excel">
                <i class="bx bxs-file-export me-1"></i> ส่งออกรายงาน Excel
            </a>

            <button type="button" class="btn btn-sm btn-danger shadow-sm fw-bold px-3 py-2" id="btnCleanJunkFiles" style="border-radius: 12px;" title="ล้างไฟล์ขยะตกค้างที่ไม่พบในฐานข้อมูล">
                <i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ
            </button>
        </div>
    </div>
</div>

<!-- 2. QUICK STAT KPI CARDS -->
<?php 
    $percentUploaded = $total_teachers > 0 ? round(($uploaded_count / $total_teachers) * 100, 1) : 0;
?>
<div class="row g-3 mb-4">
    <!-- Card 1: Total Teachers -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ข้าราชการครูทั้งหมด</span>
                    <h2 class="fw-bold mb-0 text-dark num-font" data-counter="<?= $total_teachers; ?>"><?= number_format($total_teachers); ?></h2>
                    <div class="x-small text-muted mt-1">ประจำปีงบฯ พ.ศ. <?= $fiscal_year ?></div>
                </div>
                <div class="kpi-icon-box icon-box-blue">
                    <i class="bx bx-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: PA1 Uploaded -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ส่งไฟล์แบบ PA1 แล้ว</span>
                    <h2 class="fw-bold mb-0 text-success num-font" data-counter="<?= $uploaded_count; ?>"><?= number_format($uploaded_count); ?></h2>
                    <div class="x-small text-success fw-bold mt-1">
                        <i class="bx bx-trending-up me-1"></i>คิดเป็น <?= $percentUploaded ?>% ของทั้งหมด
                    </div>
                </div>
                <div class="kpi-icon-box icon-box-green">
                    <i class="bx bxs-file-pdf"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Other Files (Plan & Presentation) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ส่งสื่อนำเสนอ & แผนการสอน</span>
                    <div class="d-flex align-items-baseline gap-2 mt-1">
                        <span class="fw-bold text-danger fs-4 num-font" title="สื่อนำเสนอ"><i class="bx bx-slideshow me-1"></i><?= $has_pres_count ?></span>
                        <span class="text-muted">/</span>
                        <span class="fw-bold text-success fs-4 num-font" title="แผนการสอน"><i class="bx bx-book-open me-1"></i><?= $has_plan_count ?></span>
                    </div>
                    <div class="x-small text-muted mt-1">สื่อ <?= $has_pres_count ?> คน | แผน <?= $has_plan_count ?> คน</div>
                </div>
                <div class="kpi-icon-box icon-box-purple">
                    <i class="bx bx-layer"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Pending -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ยังไม่ส่งงานใดๆ</span>
                    <h2 class="fw-bold mb-0 text-warning num-font" data-counter="<?= $pending_count; ?>"><?= number_format($pending_count); ?></h2>
                    <div class="x-small text-warning fw-bold mt-1">
                        <i class="bx bx-time-five me-1"></i>คงเหลืออีก <?= number_format($pending_count); ?> คน
                    </div>
                </div>
                <div class="kpi-icon-box icon-box-amber">
                    <i class="bx bx-time-five"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. SEARCH & FILTER HUB -->
<div class="filter-card p-3 mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-lg-5 col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 ps-3 text-muted"><i class="bx bx-search fs-5"></i></span>
                <input type="text" id="searchInput" class="form-control custom-input-search" placeholder="พิมพ์ชื่อ - นามสกุล, ตำแหน่ง หรือวิทยฐานะ เพื่อค้นหา...">
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <select id="filterLearning" class="form-select custom-select-filter">
                <option value="">-- แสดงทุกกลุ่มสาระการเรียนรู้ --</option>
                <?php foreach ($grouped_teachers as $g): ?>
                    <option value="<?= esc($g['lear_id']); ?>"><?= esc($g['lear_name']); ?> (<?= $g['total'] ?> คน)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-3 col-md-12 d-flex gap-2">
            <select id="filterStatus" class="form-select custom-select-filter">
                <option value="">-- สถานะส่งงานทั้งหมด --</option>
                <option value="uploaded">เฉพาะที่มีไฟล์ PA1 (<?= $uploaded_count ?>)</option>
                <option value="complete">มีงานส่งแล้ว (<?= $total_teachers - $pending_count ?>)</option>
                <option value="pending">ยังไม่ส่งงานใดๆ (<?= $pending_count ?>)</option>
            </select>
            <button type="button" class="btn btn-outline-secondary text-nowrap px-3 d-flex align-items-center justify-content-center" id="btnToggleAllGroups" title="ขยาย/ยุบกลุ่มทั้งหมด" style="border-radius: 12px;">
                <i class="bx bx-expand-vertical fs-5"></i>
            </button>
        </div>
    </div>
</div>

<!-- 4. TEACHERS GROUPED BY LEARNING AREA -->
<div id="learningGroupsContainer">
    <?php if (empty($grouped_teachers)): ?>
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body text-center py-5 text-muted">
                <i class="bx bx-user-x fs-1 mb-2 text-secondary"></i>
                <div class="fw-bold fs-5">ไม่พบข้อมูลข้าราชการครูในปีงบประมาณนี้</div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($grouped_teachers as $grpKey => $grp): ?>
            <?php 
                $grpTotalExpected = $grp['total'] * 3; // ทั้งหมด 3 รายการต่อคน (สื่อ + แผน + PA1)
                $grpSubmittedCount = ($grp['has_pres'] ?? 0) + ($grp['has_plan'] ?? 0) + ($grp['uploaded'] ?? 0);
                $grpPercent = $grpTotalExpected > 0 ? round(($grpSubmittedCount / $grpTotalExpected) * 100, 1) : 0;
            ?>
            <div class="group-accordion-card" data-lear-id="<?= esc($grp['lear_id']) ?>">
                <!-- Group Header -->
                <div class="group-accordion-header d-flex justify-content-between align-items-center flex-wrap gap-2" data-bs-toggle="collapse" data-bs-target="#collapseGrp_<?= $grpKey ?>">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-sm bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 1.1rem;">
                            <i class="bx bxs-folder-open"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark fs-6 d-block"><?= esc($grp['lear_name']); ?></span>
                            <span class="badge bg-label-secondary rounded-pill px-2 py-0" style="font-size: 0.72rem;"><?= $grp['total'] ?> คน</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        <!-- สื่อนำเสนอ -->
                        <span class="status-pill status-pill-rose" title="ส่งสื่อนำเสนอแล้ว (ลิงก์/ไฟล์)">
                            <i class="bx bx-slideshow"></i> สื่อ: <?= $grp['has_pres'] ?>/<?= $grp['total'] ?>
                        </span>
                        <!-- แผนการสอน -->
                        <span class="status-pill status-pill-success" title="ส่งแผนการจัดการเรียนรู้แล้ว">
                            <i class="bx bx-book-open"></i> แผน: <?= $grp['has_plan'] ?>/<?= $grp['total'] ?>
                        </span>
                        <!-- แบบ PA1 -->
                        <span class="status-pill status-pill-info" title="ส่งแบบข้อตกลง PA1 แล้ว">
                            <i class="bx bxs-file-pdf"></i> PA1: <?= $grp['uploaded'] ?>/<?= $grp['total'] ?>
                        </span>
                        <!-- ยังไม่ส่งงานใดๆ -->
                        <?php if ($grp['pending'] > 0): ?>
                            <span class="status-pill status-pill-warning" title="ยังไม่ส่งงานใดๆ เลย">
                                <i class="bx bx-time"></i> รอส่ง: <?= $grp['pending'] ?>
                            </span>
                        <?php else: ?>
                            <span class="status-pill status-pill-success" title="ทุกคนส่งงานแล้ว">
                                <i class="bx bx-check-double"></i> ครบทุกคน
                            </span>
                        <?php endif; ?>
                        <!-- เปอร์เซ็นต์ความครบถ้วนของไฟล์แนบทั้งหมดในกลุ่ม -->
                        <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold num-font shadow-sm" title="ส่งไฟล์/สื่อแล้วรวม <?= $grpSubmittedCount ?>/<?= $grpTotalExpected ?> รายการ (คิดเป็น <?= $grpPercent ?>%)" data-bs-toggle="tooltip">
                            <?= $grpPercent ?>%
                        </span>
                        <i class="bx bx-chevron-down fs-4 text-muted chevron-rotate ms-1"></i>
                    </div>
                </div>

                <!-- Table Content -->
                <div id="collapseGrp_<?= $grpKey ?>" class="collapse show">
                    <div class="table-responsive">
                        <table class="table modern-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 text-center" style="width: 60px;">#</th>
                                    <th>ข้าราชการครูผู้รับการประเมิน</th>
                                    <th>ตำแหน่ง / วิทยฐานะ</th>
                                    <th class="text-center text-nowrap" style="min-width: 320px;">ไฟล์แนบ / สื่อผลงานที่ส่ง</th>
                                    <th class="text-center text-nowrap" style="width: 140px;">สถานะการส่งงาน</th>
                                    <th class="text-center text-nowrap pe-4" style="width: 140px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $idx = 1; foreach ($grp['teachers'] as $t): ?>
                                    <?php 
                                        $agreement = $t['pa_agreement'] ?? null;
                                        $hasPa1  = !empty($agreement['pa_file_pa1']);
                                        $hasPlan = !empty($agreement['pa_file_lesson_plan']);
                                        $hasPresFile = !empty($agreement['pa_file_presentation']);
                                        $hasPresLink = !empty($agreement['pa_presentation_link']);
                                        $hasPres = $hasPresFile || $hasPresLink;

                                        $pa1Name  = $agreement['pa_file_pa1'] ?? '';
                                        $planName = $agreement['pa_file_lesson_plan'] ?? '';
                                        $presFileName = $agreement['pa_file_presentation'] ?? '';
                                        $rawPresLink = trim($agreement['pa_presentation_link'] ?? '', " \t\n\r\0\x0B\"'");
                                        $paId     = $agreement['pa_id'] ?? '';

                                        // Smart presentation detector (ตรวจทั้งคอลัมน์ pa_file_presentation และ pa_presentation_link)
                                        $presType = ''; // 'web_link', 'uploaded_file', 'local_file', 'empty'
                                        $presLink = '';
                                        $presDisplayName = '';

                                        if ($hasPresFile) {
                                            $presType = 'uploaded_file';
                                            $presLink = $pa_upload_baseurl . $fiscal_year . '/presentation/' . $presFileName;
                                            $presDisplayName = $presFileName;
                                        } elseif ($hasPresLink) {
                                            $presDisplayName = $rawPresLink;
                                            if (preg_match('#^([a-zA-Z]:[\\\\/]|file:///|\\\\\\\\)#i', $rawPresLink)) {
                                                $presType = 'local_file';
                                                $presLink = 'local_file';
                                            } elseif (preg_match('#^(https?://|www\.|canva\.com|drive\.google\.com|docs\.google\.com|onedrive|sharepoint|youtu)#i', $rawPresLink)) {
                                                $presType = 'web_link';
                                                $presLink = preg_match('#^https?://#i', $rawPresLink) ? $rawPresLink : ('https://' . $rawPresLink);
                                            } elseif (preg_match('#\.(pptx?|pdf|zip|rar|key|mp4|mov|doc|docx|xlsx)$#i', $rawPresLink)) {
                                                $cleanFileName = basename(str_replace('\\', '/', $rawPresLink));
                                                $presType = 'uploaded_file';
                                                $presLink = $pa_upload_baseurl . $fiscal_year . '/presentation/' . $cleanFileName;
                                            } else {
                                                $presType = 'web_link';
                                                $presLink = preg_match('#^https?://#i', $rawPresLink) ? $rawPresLink : ('https://' . $rawPresLink);
                                            }
                                        } else {
                                            $presType = 'empty';
                                        }

                                        $pa1Url  = $hasPa1 ? ($pa_upload_baseurl . $fiscal_year . '/pa1/' . $pa1Name) : '';
                                        $planUrl = $hasPlan ? ($pa_upload_baseurl . $fiscal_year . '/lesson_plan/' . $planName) : '';

                                        $fullName = trim(($t['pers_prefix'] ?? '') . $t['pers_firstname'] . ' ' . $t['pers_lastname']);
                                        $posiName = $t['posi_name'] ?? 'ครู';
                                        $academic = empty($t['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $t['pers_academic'];

                                        $statusClass = $hasPa1 ? 'uploaded' : ($hasPlan || $hasPres ? 'partial' : 'pending');
                                    ?>
                                    <tr class="teacher-row" 
                                        data-lear-id="<?= esc($grp['lear_id']) ?>" 
                                        data-status="<?= $statusClass ?>"
                                        data-has-pa1="<?= $hasPa1 ? '1' : '0' ?>"
                                        data-has-plan="<?= $hasPlan ? '1' : '0' ?>"
                                        data-has-pres="<?= $hasPres ? '1' : '0' ?>">
                                        <td class="ps-4 text-center text-muted fw-bold num-font"><?= $idx++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-label-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold text-primary flex-shrink-0 shadow-sm" style="width:40px; height:40px; font-size: 1rem;">
                                                    <?php if (!empty($t['pers_img']) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $t['pers_img'])): ?>
                                                        <img src="<?= base_url('uploads/admin/Personnal/' . $t['pers_img']) ?>" alt="Avatar" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                                    <?php else: ?>
                                                        <?= mb_substr($t['pers_firstname'], 0, 1, 'UTF-8') ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark teacher-name fs-6"><?= esc($fullName) ?></div>
                                                    <div class="x-small text-muted num-font"><i class="bx bx-id-card me-1"></i><?= esc($t['pers_id']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark small"><?= esc($posiName) ?></div>
                                            <span class="badge bg-label-secondary px-2 py-0" style="font-size: 0.72rem;"><?= esc($academic) ?></span>
                                        </td>
                                        <!-- คอลัมน์ตรวจไฟล์แนบ 3 ชนิด -->
                                        <td class="text-center text-nowrap">
                                            <div class="d-inline-flex align-items-center gap-1 flex-nowrap justify-content-center">
                                                <!-- 1. สื่อนำเสนอ (รองรับทั้ง: ลิงก์เว็บ Canva/Slides และ ไฟล์แนบ PPTX/PDF) -->
                                                <?php if ($hasPres && $presType === 'local_file'): ?>
                                                    <button type="button" class="btn-file-chip bg-label-warning text-dark border-warning btn-pres-local-file" data-path="<?= esc($presDisplayName) ?>" title="ครูแนบเป็นพาธไฟล์ในเครื่องคอมพิวเตอร์" data-bs-toggle="tooltip">
                                                        <i class="bx bx-error text-warning"></i> สื่อ (ไฟล์ในเครื่อง)
                                                    </button>
                                                <?php elseif ($hasPres && $presType === 'uploaded_file'): ?>
                                                    <a href="<?= esc($presLink); ?>" target="_blank" rel="noopener noreferrer" class="btn-file-chip chip-pres-active" title="ดาวน์โหลดไฟล์สื่อนำเสนอ (<?= esc($presDisplayName) ?>)" data-bs-toggle="tooltip" download>
                                                        <i class="bx bx-download"></i> สื่อ (ไฟล์)
                                                    </a>
                                                <?php elseif ($hasPres && !empty($presLink)): ?>
                                                    <a href="<?= esc($presLink); ?>" target="_blank" rel="noopener noreferrer" class="btn-file-chip chip-pres-active" title="เปิดดูสื่อนำเสนอออนไลน์ (Canva / Slides / Drive)" data-bs-toggle="tooltip">
                                                        <i class="bx bx-slideshow"></i> สื่อ (ลิงก์)
                                                    </a>
                                                <?php else: ?>
                                                    <span class="btn-file-chip chip-disabled" title="ยังไม่แนบสื่อนำเสนอ" data-bs-toggle="tooltip">
                                                        <i class="bx bx-slideshow"></i> -
                                                    </span>
                                                <?php endif; ?>

                                                <!-- 2. แผนการสอน -->
                                                <?php if ($hasPlan): ?>
                                                    <a href="<?= esc($planUrl); ?>" target="_blank" class="btn-file-chip chip-plan-active" title="คลิกเพื่อดู/ดาวน์โหลดแผนการจัดการเรียนรู้ (PDF)" data-bs-toggle="tooltip">
                                                        <i class="bx bx-book-open"></i> แผน
                                                    </a>
                                                <?php else: ?>
                                                    <span class="btn-file-chip chip-disabled" title="ยังไม่แนบแผนการสอน" data-bs-toggle="tooltip">
                                                        <i class="bx bx-book-open"></i> -
                                                    </span>
                                                <?php endif; ?>

                                                <!-- 3. ข้อตกลง PA1 -->
                                                <?php if ($hasPa1): ?>
                                                    <a href="<?= esc($pa1Url); ?>" target="_blank" class="btn-file-chip chip-pa1-active" title="คลิกเพื่อดู/ดาวน์โหลดแบบข้อตกลง PA1 (PDF)" data-bs-toggle="tooltip">
                                                        <i class="bx bxs-file-pdf"></i> PA1
                                                    </a>
                                                <?php else: ?>
                                                    <span class="btn-file-chip chip-disabled" title="ยังไม่มีไฟล์ PA1" data-bs-toggle="tooltip">
                                                        <i class="bx bxs-file-pdf"></i> -
                                                    </span>
                                                <?php endif; ?>

                                                <!-- ปุ่มสรุป Modal -->
                                                <button type="button" class="btn btn-action-round bg-label-warning text-dark btn-view-pa-summary ms-1"
                                                    data-name="<?= esc($fullName) ?>"
                                                    data-posi="<?= esc($posiName) ?>"
                                                    data-academic="<?= esc($academic) ?>"
                                                    data-lear="<?= esc($grp['lear_name']) ?>"
                                                    data-pres="<?= esc($presLink) ?>"
                                                    data-pres-type="<?= esc($presType) ?>"
                                                    data-pres-name="<?= esc($presDisplayName) ?>"
                                                    data-plan="<?= esc($planUrl) ?>"
                                                    data-plan-name="<?= esc($planName) ?>"
                                                    data-pa1="<?= esc($pa1Url) ?>"
                                                    data-pa1-name="<?= esc($pa1Name) ?>"
                                                    data-updated="<?= esc($agreement['pa_updated_at'] ?? $agreement['pa_created_at'] ?? '-') ?>"
                                                    title="ดูรายละเอียดการส่งงานทั้งหมด" data-bs-toggle="tooltip">
                                                    <i class="bx bx-detail"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <!-- คอลัมน์สถานะการส่งงานรวม -->
                                        <td class="text-center text-nowrap">
                                            <?php 
                                                $submittedItemsCount = ($hasPres ? 1 : 0) + ($hasPlan ? 1 : 0) + ($hasPa1 ? 1 : 0);
                                            ?>
                                            <?php if ($submittedItemsCount === 3): ?>
                                                <span class="status-pill status-pill-success" title="ส่งครบทั้ง 3 รายการ (สื่อ + แผน + PA1)">
                                                    <i class="bx bx-check-circle"></i> ครบ 3 รายการ
                                                </span>
                                            <?php elseif ($submittedItemsCount > 0): ?>
                                                <span class="status-pill status-pill-info" title="ส่งงานแล้ว <?= $submittedItemsCount ?> จาก 3 รายการ">
                                                    <i class="bx bx-time"></i> ส่ง <?= $submittedItemsCount ?>/3 อย่าง
                                                </span>
                                            <?php else: ?>
                                                <span class="status-pill status-pill-warning" title="ยังไม่มีการส่งเอกสารหรือสื่อใดๆ">
                                                    <i class="bx bx-time-five"></i> ยังไม่ส่งงาน
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <!-- จัดการอัปโหลดไฟล์ PA1 -->
                                        <td class="text-center pe-4">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                <?php if ($hasPa1): ?>
                                                    <button type="button" 
                                                            class="btn btn-action-round bg-label-info text-info btn-open-upload-modal" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-has-file="1" 
                                                            data-file-name="<?= esc($pa1Name) ?>" 
                                                            data-bs-toggle="tooltip"
                                                            title="อัปโหลดไฟล์ PA1 ใหม่แทนที่">
                                                        <i class="bx bx-upload"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-action-round bg-label-danger text-danger btn-delete-pa" 
                                                            data-pa-id="<?= esc($paId) ?>" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-bs-toggle="tooltip"
                                                            title="ลบไฟล์ PA1">
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

<!-- 5. MODAL SUMMARY การส่งงาน PA ทั้งหมดของครู -->
<div class="modal fade" id="paDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: var(--pa-blue-grad); color: #ffffff;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="bx bx-folder-open fs-4 text-warning"></i> ข้อมูลการส่งงานและข้อตกลง PA ของครู
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Teacher Profile Header -->
                <div class="p-3 rounded-4 mb-4 d-flex align-items-center gap-3" style="background: #f0f9ff; border: 1.5px solid #bae6fd;">
                    <div class="avatar avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 52px; height: 52px;">
                        <i class="bx bx-user"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-primary mb-1" id="detailTeacherName">-</h5>
                        <div class="small text-muted">
                            <span id="detailTeacherPosi" class="fw-semibold text-dark">-</span> | <span id="detailTeacherAcademic">-</span> | กลุ่มสาระฯ: <span class="fw-bold text-primary" id="detailTeacherLear">-</span>
                        </div>
                    </div>
                </div>

                <!-- 3 Attachments Showcase Grid -->
                <div class="row g-3">
                    <!-- Item 1: Presentation Link -->
                    <div class="col-md-4">
                        <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="avatar avatar-sm rounded-3 mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #be185d 0%, #db2777 100%); width: 44px; height: 44px;">
                                    <i class="bx bx-slideshow fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1 text-dark">1. สื่อนำเสนอผลงาน</h6>
                                <p class="x-small text-muted mb-3">Canva, Google Slides, PowerPoint</p>
                            </div>
                            <div id="detailPresContainer">
                                <span class="badge bg-light text-muted border px-2 py-1">ยังไม่แนบสื่อ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2: Lesson Plan -->
                    <div class="col-md-4">
                        <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="avatar avatar-sm rounded-3 mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #15803d 0%, #16a34a 100%); width: 44px; height: 44px;">
                                    <i class="bx bx-book-open fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1 text-dark">2. แผนการจัดการเรียนรู้</h6>
                                <p class="x-small text-muted mb-3">ไฟล์ PDF แผนการสอน</p>
                            </div>
                            <div id="detailPlanContainer">
                                <span class="badge bg-light text-muted border px-2 py-1">ยังไม่แนบแผน</span>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3: PA1 Agreement Document -->
                    <div class="col-md-4">
                        <div class="p-3 border rounded-4 text-center h-100 bg-white shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="avatar avatar-sm rounded-3 mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); width: 44px; height: 44px;">
                                    <i class="bx bxs-file-pdf fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1 text-dark">3. บันทึกข้อตกลง PA1</h6>
                                <p class="x-small text-muted mb-3">ไฟล์ PDF แบบข้อตกลง PA1</p>
                            </div>
                            <div id="detailPa1Container">
                                <span class="badge bg-light text-muted border px-2 py-1">ยังไม่มีไฟล์ PA1</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center x-small text-muted">
                    <span>ปีงบประมาณ: <strong>พ.ศ. <?= $fiscal_year ?></strong></span>
                    <span>อัปเดตล่าสุด: <strong id="detailLastUpdate">-</strong></span>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4 bg-light">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 fw-bold" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. MODAL UPLOAD FILE -->
<div class="modal fade" id="uploadPaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: var(--pa-blue-grad); color: #ffffff;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="uploadModalTitle">
                    <i class="bx bx-cloud-upload fs-4 text-warning"></i> อัปโหลดไฟล์ข้อตกลง PA (PA1)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 rounded-3 mb-3" style="background: #f0f9ff; border: 1.5px solid #bae6fd;">
                    <div class="small text-muted mb-1">คุณครูผู้รับการประเมิน:</div>
                    <h6 class="fw-bold text-primary mb-0 fs-6" id="modalTeacherName">-</h6>
                    <div class="x-small text-muted mt-1">ประจำปีงบประมาณ: <span class="fw-bold text-dark">พ.ศ. <?= $fiscal_year ?></span></div>
                </div>

                <div id="existingFileAlert" class="alert alert-warning py-2 px-3 small d-none" style="border-radius: 12px;">
                    <i class="bx bx-info-circle me-1"></i> มีไฟล์เดิมในระบบแล้ว หากอัปโหลดใหม่ไฟล์เดิมจะถูกเขียนทับโดยอัตโนมัติ
                </div>

                <input type="file" id="modalFileInput" accept=".pdf" class="d-none">
                
                <div class="dropzone-lux" id="modalDropzone">
                    <i class="bx bxs-file-pdf text-danger fs-1 mb-2"></i>
                    <div class="small fw-bold text-dark">ลากและวางไฟล์ PDF ข้อตกลง PA ที่นี่</div>
                    <div class="x-small text-muted mt-1">หรือคลิกเพื่อเลือกไฟล์ (PDF สูงสุด 20MB)</div>
                </div>

                <div class="file-selected-card mt-3 d-none p-3 border rounded-3 bg-white" id="modalFileIndicator">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center text-truncate me-2">
                            <i class="bx bxs-file-pdf text-danger fs-3 me-2"></i>
                            <div class="text-truncate">
                                <div class="small fw-bold text-truncate text-dark" id="selectedFileName">-</div>
                                <div class="x-small text-muted num-font" id="selectedFileSize">-</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-xs btn-icon btn-label-secondary rounded-circle" id="modalRemoveFile">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>

                <div class="progress mt-3 d-none" id="modalProgress" style="height: 10px; border-radius: 8px;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated fw-bold num-font" role="progressbar" style="width: 0%; font-size: 0.7rem;"></div>
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
        const CHUNK_SIZE = 512 * 1024; // 512KB per chunk
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
                $('.group-accordion-card .collapse').collapse('hide');
                $(this).html('<i class="bx bx-collapse-vertical fs-5"></i>');
                allExpanded = false;
            } else {
                $('.group-accordion-card .collapse').collapse('show');
                $(this).html('<i class="bx bx-expand-vertical fs-5"></i>');
                allExpanded = true;
            }
        });

        // Filter Functionality
        function filterRows() {
            const search = $('#searchInput').val().toLowerCase().trim();
            const selectedLearId = $('#filterLearning').val();
            const status = $('#filterStatus').val();

            $('.group-accordion-card').each(function() {
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
                    const hasPa1 = row.data('has-pa1') == 1;

                    let matchSearch = !search || name.includes(search);
                    let matchStatus = true;

                    if (status === 'uploaded') {
                        matchStatus = hasPa1;
                    } else if (status === 'complete') {
                        matchStatus = (rowStatus === 'uploaded' || rowStatus === 'partial');
                    } else if (status === 'pending') {
                        matchStatus = (rowStatus === 'pending');
                    }

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

        // Open PA Summary Modal
        $('.btn-view-pa-summary').on('click', function() {
            const btn = $(this);
            $('#detailTeacherName').text(btn.data('name') || '-');
            $('#detailTeacherPosi').text(btn.data('posi') || '-');
            $('#detailTeacherAcademic').text(btn.data('academic') || '-');
            $('#detailTeacherLear').text(btn.data('lear') || '-');
            $('#detailLastUpdate').text(btn.data('updated') || '-');

            const pres = btn.data('pres');
            const presType = btn.data('pres-type');
            const presName = btn.data('pres-name');
            const plan = btn.data('plan');
            const pa1 = btn.data('pa1');

            // Presentation
            if (presType === 'local_file') {
                $('#detailPresContainer').html(`
                    <div class="alert alert-warning py-2 px-3 mb-0 x-small text-start rounded-3">
                        <i class="bx bx-error me-1 fw-bold"></i> ครูแนบเป็นพาธไฟล์ในเครื่องคอมพิวเตอร์ (ไม่สามารถเปิดออนไลน์ได้ กรุณาแจ้งให้ครูอัปโหลดขึ้น Google Drive หรือ Canva ครับ)
                    </div>
                `);
            } else if (presType === 'uploaded_file' && pres) {
                $('#detailPresContainer').html(`
                    <a href="${pres}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger w-100 rounded-pill fw-bold" download>
                        <i class="bx bx-download me-1"></i> ดาวน์โหลดไฟล์สื่อนำเสนอ
                    </a>
                `);
            } else if (pres) {
                $('#detailPresContainer').html(`
                    <a href="${pres}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger w-100 rounded-pill fw-bold">
                        <i class="bx bx-slideshow me-1"></i> เปิดดูสื่อนำเสนอออนไลน์
                    </a>
                `);
            } else {
                $('#detailPresContainer').html('<span class="badge bg-light text-muted border px-2 py-1">ยังไม่แนบสื่อ</span>');
            }

            // Plan
            if (plan) {
                $('#detailPlanContainer').html(`
                    <a href="${plan}" target="_blank" class="btn btn-sm btn-outline-success w-100 rounded-pill fw-bold" download>
                        <i class="bx bx-book-open me-1"></i> ดู/โหลดแผนการสอน
                    </a>
                `);
            } else {
                $('#detailPlanContainer').html('<span class="badge bg-light text-muted border px-2 py-1">ยังไม่แนบแผน</span>');
            }

            // PA1
            if (pa1) {
                $('#detailPa1Container').html(`
                    <a href="${pa1}" target="_blank" class="btn btn-sm btn-outline-primary w-100 rounded-pill fw-bold" download>
                        <i class="bx bxs-file-pdf me-1"></i> ดู/โหลดไฟล์ PA1
                    </a>
                `);
            } else {
                $('#detailPa1Container').html('<span class="badge bg-light text-muted border px-2 py-1">ยังไม่มีไฟล์ PA1</span>');
            }

            $('#paDetailModal').modal('show');
        });

        // Handle clicks on local computer file paths
        $(document).on('click', '.btn-pres-local-file', function() {
            const path = $(this).data('path') || '';
            Swal.fire({
                icon: 'warning',
                title: 'ครูแนบไฟล์ในเครื่องคอมพิวเตอร์',
                html: `คุณครูท่านนี้ระบุลิงก์เป็นพาธไฟล์ในคอมพิวเตอร์ส่วนตัว:<br><br><code class="text-danger p-2 bg-light rounded d-block text-break">${path}</code><br>ซึ่งผู้อื่นไม่สามารถเปิดอ่านผ่านอินเทอร์เน็ตได้<br><b>คำแนะนำ:</b> กรุณาแจ้งคุณครูให้อัปโหลดไฟล์นี้ขึ้น <b>Google Drive</b> หรือ <b>Canva</b> แล้วนำลิงก์ (Share Link) มาวางแทนครับ`,
                confirmButtonText: 'รับทราบ',
                confirmButtonColor: '#0284c7'
            });
        });

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