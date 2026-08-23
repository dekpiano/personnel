<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --leave-primary: #0284c7;
        --leave-primary-dark: #0369a1;
        --leave-gradient: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #0369a1 100%);
        --card-radius: 18px;
        --pending-color: #9a3412;
        --pending-bg: #fff7ed;
        --approved-color: #065f46;
        --approved-bg: #ecfdf5;
        --rejected-color: #991b1b;
        --rejected-bg: #fef2f2;
    }

    /* Force sharp readable body font within leave section */
    .leave-content-card, .modal-leave-detail, .kpi-card {
        color: #0f172a !important;
    }

    /* Hero Header */
    .leave-hero-header {
        background: var(--leave-gradient);
        border-radius: var(--card-radius);
        padding: 2.25rem 2.25rem;
        color: #ffffff;
        margin-bottom: 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        position: relative;
        overflow: hidden;
    }

    .leave-hero-header::before {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(3, 195, 236, 0.3) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .leave-hero-header::after {
        content: "";
        position: absolute;
        bottom: -50px;
        left: 20%;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.22) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .leave-hero-header .hero-subtitle {
        color: #e2e8f0 !important;
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* KPI Stat Cards - Ultra High Contrast */
    .kpi-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.25rem;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease-in-out;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .kpi-card:hover, .kpi-card.active {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.15);
        border-color: #0284c7;
    }

    .kpi-card.active {
        background: #f0fdfa;
        outline: 2px solid #0284c7;
    }

    .kpi-card .kpi-label {
        color: #334155 !important;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .kpi-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Main Container Card */
    .leave-content-card {
        background: #ffffff;
        border-radius: var(--card-radius);
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    /* Filter Tabs - Crisp & Readable */
    .filter-pills-wrap {
        display: flex;
        gap: 0.6rem;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
    }
    .filter-pills-wrap::-webkit-scrollbar {
        height: 4px;
    }

    .filter-pill-btn {
        border-radius: 50rem;
        padding: 0.5rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 700;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #1e293b;
        white-space: nowrap;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-pill-btn:hover, .filter-pill-btn.active {
        background: #0f172a;
        color: #ffffff !important;
        border-color: #0f172a;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);
    }

    .filter-pill-btn .pill-count {
        background: #e2e8f0;
        color: #0f172a;
        padding: 2px 9px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .filter-pill-btn.active .pill-count {
        background: rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
    }

    /* Search Input */
    .search-input-box {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 50rem !important;
        overflow: hidden;
    }
    .search-input-box input {
        color: #0f172a !important;
        font-weight: 600;
    }
    .search-input-box input::placeholder {
        color: #64748b !important;
        font-weight: 500;
    }

    /* Leave Type Badges - Ultra Sharp & Distinct */
    .leave-type-badge {
        display: inline-block;
        background: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1.5px solid #7dd3fc !important;
        font-weight: 800 !important;
        font-size: 0.82rem !important;
        padding: 0.4rem 0.9rem !important;
        border-radius: 50rem !important;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(3, 105, 161, 0.08);
    }

    /* Hero Subtitle Pill */
    .hero-top-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(15, 23, 42, 0.6) !important;
        color: #38bdf8 !important;
        border: 1px solid rgba(56, 189, 248, 0.4) !important;
        padding: 0.35rem 0.95rem;
        border-radius: 50rem;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .hero-top-pill span {
        color: #ffffff !important;
    }

    /* Status Badges - Sharp High Contrast with solid borders */
    .status-badge-modern {
        padding: 0.45rem 0.95rem;
        border-radius: 50rem;
        font-weight: 800;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge-modern.pending {
        background: #fef3c7;
        color: #78350f !important;
        border: 1.5px solid #f59e0b;
    }
    .status-badge-modern.approved {
        background: #d1fae5;
        color: #064e3b !important;
        border: 1.5px solid #10b981;
    }
    .status-badge-modern.rejected {
        background: #fee2e2;
        color: #7f1d1d !important;
        border: 1.5px solid #ef4444;
    }
    .status-badge-modern.cancelled {
        background: #f1f5f9;
        color: #1e293b !important;
        border: 1.5px solid #94a3b8;
    }

    /* Desktop Table */
    .custom-leave-table thead th {
        background: #f1f5f9;
        color: #0f172a !important;
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #cbd5e1;
        padding: 1.15rem 1.25rem;
    }

    .custom-leave-table tbody td {
        padding: 1.15rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        color: #0f172a !important;
    }

    /* Text Helpers for Ultra Clarity */
    .text-dark-contrast {
        color: #0f172a !important;
        font-weight: 700;
    }
    .text-sub-contrast {
        color: #475569 !important;
        font-weight: 600;
    }

    /* Personnel Avatar / Icon Fallback */
    .avatar-wrapper-personnel {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #e2e8f0;
        border: 2px solid #cbd5e1;
        box-shadow: 0 2px 6px rgba(15,23,42,0.08);
    }

    .avatar-wrapper-personnel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-icon-fallback {
        font-size: 1.75rem;
        color: #334155;
    }

    /* Mobile Leave Card List (Visible only on < 992px) */
    .mobile-leave-stream {
        display: none;
    }

    @media (max-width: 991.98px) {
        .desktop-table-container {
            display: none;
        }
        .mobile-leave-stream {
            display: block;
            padding: 1rem;
        }
        .leave-hero-header {
            padding: 1.5rem;
        }
    }

    .mobile-card-item {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 0.95rem;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .mobile-card-item:hover, .mobile-card-item:active {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.1);
        border-color: #0284c7;
    }

    /* Modal Styling */
    .modal-leave-detail .modal-content {
        border-radius: 22px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35);
    }

    .modal-leave-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff !important;
        padding: 1.5rem 1.75rem;
    }

    .info-tile {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 14px;
        padding: 1.1rem;
        color: #0f172a !important;
    }
    .info-tile-label {
        color: #334155 !important;
        font-weight: 700;
        font-size: 0.85rem;
    }
    .info-tile-value {
        color: #0f172a !important;
        font-weight: 800;
    }
</style>

<!-- Hero Section -->
<div class="leave-hero-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="hero-top-pill mb-2">
                <i class="bi bi-shield-check"></i>
                <span>ระบบบริหารงานบุคคล / บุคลากร</span>
            </div>
            <h2 class="text-white mb-1 fw-bold fs-3">
                <i class="bi bi-calendar2-check-fill text-info me-2"></i><?= $title ?>
            </h2>
            <p class="hero-subtitle mb-0 small">
                พิจารณาอนุมัติคำขอลา ติดตามสถานะ และตรวจสอบสถิติวันลาของบุคลากร
            </p>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <!-- ตัวกรองปีงบประมาณอัตโนมัติ -->
            <div class="bg-white p-1 rounded-pill shadow-sm d-inline-flex align-items-center border border-light">
                <i class="bi bi-calendar3 text-primary ms-2 me-1 fs-6"></i>
                <select id="fiscalYearFilter" class="form-select form-select-sm border-0 bg-transparent py-0 pe-4 shadow-none fw-bold text-dark" style="width: auto; cursor: pointer;" onchange="window.location.href='<?= base_url('Admin/Leave?fiscal_year=') ?>' + this.value">
                    <option value="" <?= empty($selectedFiscalYear) ? 'selected' : '' ?>>แสดงทุกปีงบประมาณ</option>
                    <?php foreach ($availableFiscalYears as $fy): ?>
                        <option value="<?= $fy ?>" <?= ($selectedFiscalYear == $fy) ? 'selected' : '' ?>>
                            ปีงบประมาณ <?= $fy ?> <?= ($fy == $currentFiscalInfo['fiscal_year_be']) ? '🌟 (ปีปัจจุบัน)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <a href="<?= base_url('Admin/Leave/Settings') ?>" class="btn btn-light fw-bold text-dark rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-sliders2 text-primary"></i>
                <span>ตั้งค่าโควตา & ปี</span>
            </a>
            <a href="<?= base_url('Admin/Holiday') ?>" class="btn btn-outline-light rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-calendar-event"></i>
                <span>วันหยุด</span>
            </a>
        </div>
    </div>
</div>

<!-- KPI Stats Row -->
<?php
    $totalCount = count($leaveRequests);
    $pendingCount = count(array_filter($leaveRequests, fn($x) => $x['leave_status'] == 'pending'));
    $approvedCount = count(array_filter($leaveRequests, fn($x) => $x['leave_status'] == 'approved'));
    $rejectedCount = count(array_filter($leaveRequests, fn($x) => in_array($x['leave_status'], ['rejected', 'cancelled'])));
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card" data-filter-status="all" id="kpi-all">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label mb-1">คำขอทั้งหมด</div>
                    <div class="fs-2 fw-bold text-dark-contrast"><?= number_format($totalCount) ?></div>
                </div>
                <div class="kpi-icon-box bg-light text-primary border border-secondary border-opacity-25">
                    <i class="bi bi-collection-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card" data-filter-status="pending" id="kpi-pending">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label mb-1">รอการอนุมัติ</div>
                    <div class="fs-2 fw-bold" style="color: #9a3412 !important;"><?= number_format($pendingCount) ?></div>
                </div>
                <div class="kpi-icon-box" style="background: #fef3c7; color: #9a3412; border: 1.5px solid #f59e0b;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card" data-filter-status="approved" id="kpi-approved">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label mb-1">อนุมัติแล้ว</div>
                    <div class="fs-2 fw-bold" style="color: #065f46 !important;"><?= number_format($approvedCount) ?></div>
                </div>
                <div class="kpi-icon-box" style="background: #d1fae5; color: #065f46; border: 1.5px solid #10b981;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card" data-filter-status="rejected" id="kpi-rejected">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label mb-1">ไม่อนุมัติ / ยกเลิก</div>
                    <div class="fs-2 fw-bold" style="color: #991b1b !important;"><?= number_format($rejectedCount) ?></div>
                </div>
                <div class="kpi-icon-box" style="background: #fee2e2; color: #991b1b; border: 1.5px solid #ef4444;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Leave Container -->
<div class="leave-content-card">
    <!-- Controls Header -->
    <div class="p-3 p-md-4 border-bottom bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <!-- Filter Pills -->
            <div class="filter-pills-wrap">
                <button type="button" class="filter-pill-btn active" data-status="">
                    <i class="bi bi-grid-fill"></i>
                    <span>ทั้งหมด</span>
                    <span class="pill-count"><?= $totalCount ?></span>
                </button>
                <button type="button" class="filter-pill-btn" data-status="pending">
                    <i class="bi bi-clock-history text-warning"></i>
                    <span>รออนุมัติ</span>
                    <span class="pill-count"><?= $pendingCount ?></span>
                </button>
                <button type="button" class="filter-pill-btn" data-status="approved">
                    <i class="bi bi-check2-circle text-success"></i>
                    <span>อนุมัติแล้ว</span>
                    <span class="pill-count"><?= $approvedCount ?></span>
                </button>
                <button type="button" class="filter-pill-btn" data-status="rejected">
                    <i class="bi bi-x-circle text-danger"></i>
                    <span>ไม่อนุมัติ / ยกเลิก</span>
                    <span class="pill-count"><?= $rejectedCount ?></span>
                </button>
            </div>

            <!-- Search box with high contrast -->
            <div class="input-group search-input-box" style="max-width: 320px;">
                <span class="input-group-text bg-white border-0 text-secondary"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-white border-0 ps-0" id="leaveSearchInput" placeholder="ค้นหาชื่อ, รหัส, ประเภทการลา...">
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="desktop-table-container">
        <div class="table-responsive">
            <table class="table align-middle custom-leave-table mb-0" id="tableLeaveRequests">
                <thead>
                    <tr>
                        <th class="ps-4">บุคลากรผู้ขอลา</th>
                        <th>ประเภทการลา</th>
                        <th>ช่วงวันที่ขอลา</th>
                        <th>จำนวนวัน</th>
                        <th>สถานะคำขอ</th>
                        <th class="text-end pe-4">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($leaveRequests as $leave): ?>
                    <tr class="leave-row-item" data-status="<?= $leave['leave_status'] ?>" data-search="<?= esc(strtolower($leave['pers_firstname'].' '.$leave['pers_lastname'].' '.$leave['pers_id'].' '.$leave['leave_type_name'].' '.$leave['leave_topic'])) ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-wrapper-personnel">
                                    <?php if(!empty($leave['pers_img']) && $leave['pers_img'] !== 'default.png' && file_exists(FCPATH . 'uploads/admin/Personnal/' . $leave['pers_img'])): ?>
                                        <img src="<?= base_url('uploads/admin/Personnal/' . $leave['pers_img']) ?>" alt="<?= esc($leave['pers_firstname']) ?>">
                                    <?php else: ?>
                                        <i class="bi bi-person-fill avatar-icon-fallback"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="text-dark-contrast fs-6"><?= $leave['pers_prefix'].$leave['pers_firstname'].' '.$leave['pers_lastname'] ?></div>
                                    <div class="text-sub-contrast small d-flex align-items-center gap-1">
                                        <i class="bi bi-person-badge"></i> รหัส: <?= $leave['pers_id'] ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="leave-type-badge">
                                <?= $leave['leave_type_name'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="text-dark-contrast">
                                <i class="bi bi-calendar3 text-primary me-1"></i>
                                <?= thai_date_short($leave['leave_start_date']) ?> - <?= thai_date_short($leave['leave_end_date']) ?>
                            </div>
                            <div class="text-sub-contrast small text-truncate mt-1" style="max-width: 240px;" title="<?= esc($leave['leave_topic']) ?>">
                                <i class="bi bi-chat-left-text me-1"></i><?= $leave['leave_topic'] ?: '-' ?>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-baseline gap-1">
                                <span class="fs-5 text-dark-contrast"><?= floatval($leave['leave_total_days']) ?></span>
                                <span class="text-sub-contrast small">วัน</span>
                            </div>
                            <span class="badge bg-light text-dark border border-secondary border-opacity-50 px-2 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                <?= $leave['leave_period'] === 'full' ? 'เต็มวัน' : ($leave['leave_period'] === 'morning' ? 'ครึ่งวันเช้า' : 'ครึ่งวันบ่าย') ?>
                            </span>
                        </td>
                        <td>
                            <?php if($leave['leave_status'] == 'pending'): ?>
                                <span class="status-badge-modern pending"><i class="bi bi-clock-history"></i> รออนุมัติ</span>
                            <?php elseif($leave['leave_status'] == 'approved'): ?>
                                <span class="status-badge-modern approved"><i class="bi bi-check-circle-fill"></i> อนุมัติแล้ว</span>
                            <?php elseif($leave['leave_status'] == 'rejected'): ?>
                                <span class="status-badge-modern rejected"><i class="bi bi-x-circle-fill"></i> ไม่อนุมัติ</span>
                            <?php else: ?>
                                <span class="status-badge-modern cancelled"><i class="bi bi-dash-circle"></i> ยกเลิก</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="<?= base_url('Admin/Leave/Print/' . $leave['leave_id']) ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 shadow-sm d-inline-flex align-items-center gap-1 fw-bold" title="พิมพ์ใบขออนุญาตลา">
                                    <i class="bi bi-printer-fill"></i>
                                    <span class="d-none d-xl-inline">พิมพ์</span>
                                </a>
                                <button class="btn btn-sm btn-primary rounded-pill px-3 py-1 shadow-sm btn-view-leave d-inline-flex align-items-center gap-1 fw-bold" data-id="<?= $leave['leave_id'] ?>">
                                    <i class="bi bi-eye-fill"></i>
                                    <span>พิจารณา</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($leaveRequests)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-dark-contrast">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            ยังไม่มีข้อมูลคำขอลาในระบบ
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card Stream View -->
    <div class="mobile-leave-stream">
        <?php foreach($leaveRequests as $leave): ?>
        <div class="mobile-card-item leave-row-item" data-status="<?= $leave['leave_status'] ?>" data-search="<?= esc(strtolower($leave['pers_firstname'].' '.$leave['pers_lastname'].' '.$leave['pers_id'].' '.$leave['leave_type_name'].' '.$leave['leave_topic'])) ?>">
            <!-- Header with Avatar and Status Badge -->
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-wrapper-personnel">
                        <?php if(!empty($leave['pers_img']) && $leave['pers_img'] !== 'default.png' && file_exists(FCPATH . 'uploads/admin/Personnal/' . $leave['pers_img'])): ?>
                            <img src="<?= base_url('uploads/admin/Personnal/' . $leave['pers_img']) ?>" alt="">
                        <?php else: ?>
                            <i class="bi bi-person-fill avatar-icon-fallback"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="text-dark-contrast fs-6"><?= $leave['pers_prefix'].$leave['pers_firstname'].' '.$leave['pers_lastname'] ?></div>
                        <div class="text-sub-contrast small">รหัส: <?= $leave['pers_id'] ?></div>
                    </div>
                </div>
                <div>
                    <?php if($leave['leave_status'] == 'pending'): ?>
                        <span class="status-badge-modern pending"><i class="bi bi-clock-history"></i> รออนุมัติ</span>
                    <?php elseif($leave['leave_status'] == 'approved'): ?>
                        <span class="status-badge-modern approved"><i class="bi bi-check-circle-fill"></i> อนุมัติ</span>
                    <?php elseif($leave['leave_status'] == 'rejected'): ?>
                        <span class="status-badge-modern rejected"><i class="bi bi-x-circle-fill"></i> ไม่อนุมัติ</span>
                    <?php else: ?>
                        <span class="status-badge-modern cancelled"><i class="bi bi-dash-circle"></i> ยกเลิก</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Body Details -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="leave-type-badge"><?= $leave['leave_type_name'] ?></span>
                    <span class="text-dark-contrast fw-bold">
                        <?= floatval($leave['leave_total_days']) ?> วัน
                        <small class="text-secondary fw-normal">(<?= $leave['leave_period'] === 'full' ? 'เต็มวัน' : ($leave['leave_period'] === 'morning' ? 'ครึ่งวันเช้า' : 'ครึ่งวันบ่าย') ?>)</small>
                    </span>
                </div>
                <div class="text-dark-contrast small mb-1">
                    <i class="bi bi-calendar3 text-primary me-1"></i>
                    <?= thai_date_short($leave['leave_start_date']) ?> - <?= thai_date_short($leave['leave_end_date']) ?>
                </div>
                <?php if(!empty($leave['leave_topic'])): ?>
                <div class="small text-sub-contrast mt-1 text-truncate">
                    <i class="bi bi-chat-quote me-1"></i><?= $leave['leave_topic'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Action Button -->
            <div class="d-flex gap-2">
                <a href="<?= base_url('Admin/Leave/Print/' . $leave['leave_id']) ?>" target="_blank" class="btn btn-outline-info rounded-pill py-2 shadow-sm d-flex align-items-center justify-content-center gap-1 fw-bold flex-grow-1">
                    <i class="bi bi-printer-fill"></i>
                    <span>พิมพ์ใบลา</span>
                </a>
                <button class="btn btn-primary rounded-pill py-2 shadow-sm btn-view-leave d-flex align-items-center justify-content-center gap-1 fw-bold flex-grow-1" data-id="<?= $leave['leave_id'] ?>">
                    <i class="bi bi-eye-fill"></i>
                    <span>พิจารณา</span>
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(empty($leaveRequests)): ?>
        <div class="text-center py-5 text-dark-contrast">
            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
            ไม่มีรายการคำขอลา
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Review Slip -->
<div class="modal fade modal-leave-detail" id="modalLeaveDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-leave-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-file-earmark-text text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">รายละเอียดคำขอลา</h5>
                        <small class="text-white text-opacity-90">ตรวจสอบสิทธิ์และพิจารณาอนุมัติ</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-3 p-md-4">
                <div id="leaveDetailContent">
                    <!-- Dynamic AJAX Content -->
                </div>

                <div class="mt-4 pt-3 border-top">
                    <form id="formUpdateStatus">
                        <input type="hidden" name="leave_id" id="modal_leave_id">
                        <div class="form-floating mb-3">
                            <textarea class="form-control text-dark border border-secondary border-opacity-50" name="leave_comment" placeholder="ความคิดเห็น/เหตุผล" style="height: 90px" id="modal_leave_comment"></textarea>
                            <label class="text-dark"><i class="bi bi-chat-left-dots text-primary me-1"></i> ความเห็นประกอบการพิจารณา (ระบุหรือไม่ระบุก็ได้)</label>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 p-3 p-md-4 d-flex justify-content-between flex-wrap gap-2">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> ปิด
                    </button>
                    <a href="javascript:void(0)" id="modalBtnPrint" target="_blank" class="btn btn-outline-info rounded-pill px-4 fw-bold d-inline-flex align-items-center gap-1">
                        <i class="bi bi-printer-fill"></i>
                        <span>พิมพ์ใบลา (PDF)</span>
                    </a>
                </div>
                <div id="approvalButtons" class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4 fw-bold" onclick="handleApproval('rejected')">
                        <i class="bi bi-x-circle me-1"></i> ไม่อนุมัติ
                    </button>
                    <button type="button" class="btn btn-success rounded-pill px-4 fw-bold shadow" onclick="handleApproval('approved')">
                        <i class="bi bi-check-circle-fill me-1"></i> อนุมัติคำขอ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let currentFilterStatus = '';
    
    function applyLeaveFilters() {
        const query = $('#leaveSearchInput').val().toLowerCase().trim();
        
        $('.leave-row-item').each(function() {
            const rowStatus = $(this).data('status');
            const rowSearch = $(this).data('search');
            
            let statusMatch = false;
            if (!currentFilterStatus) {
                statusMatch = true;
            } else if (currentFilterStatus === 'rejected') {
                statusMatch = (rowStatus === 'rejected' || rowStatus === 'cancelled');
            } else {
                statusMatch = (rowStatus === currentFilterStatus);
            }
            
            const searchMatch = !query || (rowSearch && rowSearch.indexOf(query) > -1);
            
            if (statusMatch && searchMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Filter Pills Click
    $('.filter-pill-btn').on('click', function() {
        $('.filter-pill-btn').removeClass('active');
        $(this).addClass('active');
        currentFilterStatus = $(this).data('status');
        
        $('.kpi-card').removeClass('active');
        if(currentFilterStatus) {
            $(`#kpi-${currentFilterStatus}`).addClass('active');
        } else {
            $('#kpi-all').addClass('active');
        }
        
        applyLeaveFilters();
    });

    // KPI Cards Click
    $('.kpi-card').on('click', function() {
        const filter = $(this).data('filter-status');
        const targetStatus = filter === 'all' ? '' : filter;
        $(`.filter-pill-btn[data-status="${targetStatus}"]`).trigger('click');
    });

    // Search Input
    $('#leaveSearchInput').on('input', function() {
        applyLeaveFilters();
    });

    // เปิด Modal ดูรายละเอียด
    $(document).on('click', '.btn-view-leave', function() {
        const id = $(this).data('id');
        $('#modal_leave_id').val(id);
        $('#leaveDetailContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="text-dark fw-bold small mt-2">กำลังโหลดข้อมูลคำขอ...</div>
            </div>
        `);
        $('#modalLeaveDetail').modal('show');

        $.get(`<?= base_url('Admin/Leave/Get/') ?>${id}`, function(res) {
            let statusBadge = '';
            if (res.leave_status === 'pending') {
                statusBadge = '<span class="status-badge-modern pending"><i class="bi bi-clock-history"></i> รอการอนุมัติ</span>';
            } else if (res.leave_status === 'approved') {
                statusBadge = '<span class="status-badge-modern approved"><i class="bi bi-check-circle-fill"></i> อนุมัติแล้ว</span>';
            } else if (res.leave_status === 'rejected') {
                statusBadge = '<span class="status-badge-modern rejected"><i class="bi bi-x-circle-fill"></i> ไม่อนุมัติ</span>';
            } else {
                statusBadge = '<span class="status-badge-modern cancelled"><i class="bi bi-dash-circle"></i> ยกเลิก</span>';
            }

            let periodText = res.leave_period === 'full' ? 'เต็มวัน' : (res.leave_period === 'morning' ? 'ครึ่งวันเช้า' : 'ครึ่งวันบ่าย');
            let quotaPercent = res.quota_total > 0 ? Math.min(100, Math.round((res.used_days / res.quota_total) * 100)) : 0;

            let avatarHtml = '';
            if (res.pers_img && res.pers_img !== 'default.png') {
                avatarHtml = `<img src="<?= base_url('uploads/admin/Personnal/') ?>${res.pers_img}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.parentElement.innerHTML='<i class=\\'bi bi-person-fill avatar-icon-fallback\\' style=\\'font-size: 2rem;\\'></i>'">`;
            } else {
                avatarHtml = `<i class="bi bi-person-fill avatar-icon-fallback" style="font-size: 2rem;"></i>`;
            }

            let html = `
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-wrapper-personnel" style="width: 54px; height: 54px;">
                            ${avatarHtml}
                        </div>
                        <div>
                            <div class="text-dark-contrast fs-5">${res.pers_prefix}${res.pers_firstname} ${res.pers_lastname}</div>
                            <div class="text-sub-contrast small"><i class="bi bi-person-badge me-1"></i>รหัสบุคลากร: ${res.pers_id}</div>
                        </div>
                    </div>
                    <div>${statusBadge}</div>
                </div>

                <!-- Fiscal Year & Quota Statistics Dashboard -->
                <div class="card border-0 mb-3" style="background: #f8fafc; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge bg-primary px-3 py-1 rounded-pill fw-bold fs-7">
                                <i class="bi bi-calendar3 me-1"></i> ${res.fiscal_year_name}
                            </span>
                            <span class="badge bg-light text-dark border px-2 py-1 small">
                                <i class="bi bi-clock-history me-1 text-primary"></i>รอบปีงบประมาณ: ${res.fiscal_start_th} - ${res.fiscal_end_th}
                            </span>
                        </div>
                        <span class="leave-type-badge">${res.leave_type_name}</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <!-- 1. โควตาปีงบประมาณปัจจุบัน (23 วัน) -->
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark-contrast small"><i class="bi bi-pie-chart-fill text-primary me-1"></i> ปีงบประมาณนี้ (${res.fiscal_year_be})</span>
                                        <span class="badge bg-light text-dark border">จำกัด 23 วัน/ปี</span>
                                    </div>
                                    <div class="d-flex align-items-baseline justify-content-between mt-2">
                                        <div>
                                            <small class="text-secondary d-block">ใช้รวมทุกประเภท</small>
                                            <span class="fs-5 fw-bold ${res.fiscal_used_days > 20 ? 'text-danger' : 'text-primary'}">${parseFloat(res.fiscal_used_days).toFixed(1)}</span>
                                            <small class="text-secondary">/ ${res.fiscal_max_days} วัน</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-secondary d-block">คงเหลือปีนี้</small>
                                            <span class="fs-5 fw-bold ${res.fiscal_remaining_days <= 3 ? 'text-danger' : 'text-success'}">${parseFloat(res.fiscal_remaining_days).toFixed(1)} วัน</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                                        <div class="progress-bar ${res.fiscal_used_days > 20 ? 'bg-danger' : 'bg-primary'}" role="progressbar" style="width: ${Math.min(100, Math.round((res.fiscal_used_days / res.fiscal_max_days) * 100))}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. โควตาสะสมรอบ 2 ปีงบประมาณ (45 วัน) -->
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark-contrast small"><i class="bi bi-calendar2-range-fill text-info me-1"></i> รวม 2 ปีงบ (${res.two_year_period_text})</span>
                                        <span class="badge bg-light text-dark border">สะสม 45 วัน</span>
                                    </div>
                                    <div class="d-flex align-items-baseline justify-content-between mt-2">
                                        <div>
                                            <small class="text-secondary d-block">ใช้รวม 2 ปีงบ</small>
                                            <span class="fs-5 fw-bold ${res.two_year_used_days > 40 ? 'text-danger' : 'text-info'}">${parseFloat(res.two_year_used_days).toFixed(1)}</span>
                                            <small class="text-secondary">/ ${res.two_year_max_days} วัน</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-secondary d-block">คงเหลือรวม 2 ปี</small>
                                            <span class="fs-5 fw-bold ${res.two_year_remaining_days <= 5 ? 'text-danger' : 'text-success'}">${parseFloat(res.two_year_remaining_days).toFixed(1)} วัน</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                                        <div class="progress-bar ${res.two_year_used_days > 40 ? 'bg-danger' : 'bg-info'}" role="progressbar" style="width: ${Math.min(100, Math.round((res.two_year_used_days / res.two_year_max_days) * 100))}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-tile h-100">
                            <div class="info-tile-label mb-1"><i class="bi bi-calendar-event me-1 text-primary"></i> ช่วงวันที่ขอลา</div>
                            <div class="info-tile-value fs-6">${res.start_date_th} - ${res.end_date_th}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-tile h-100">
                            <div class="info-tile-label mb-1"><i class="bi bi-clock-history me-1 text-primary"></i> จำนวนและช่วงเวลา</div>
                            <div>
                                <span class="fs-4 fw-bold text-primary">${parseFloat(res.leave_total_days)}</span> <span class="text-dark fw-bold">วัน</span>
                                <span class="badge bg-white text-dark border border-secondary border-opacity-50 ms-2 px-2 py-1 fw-bold">${periodText}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-tile">
                            <div class="info-tile-label mb-1"><i class="bi bi-card-heading me-1 text-primary"></i> เหตุผล/หัวข้อการลา</div>
                            <div class="info-tile-value fs-6">${res.leave_topic || '-'}</div>
                            ${res.leave_detail ? `<div class="mt-2 text-dark border-top pt-2" style="font-weight: 500;">${res.leave_detail}</div>` : ''}
                        </div>
                    </div>
                </div>
            `;

            $('#leaveDetailContent').html(html);
            $('#modal_leave_comment').val(res.leave_comment || '');
            $('#modalBtnPrint').attr('href', '<?= base_url('Admin/Leave/Print/') ?>' + res.leave_id);
            
            if(res.leave_status !== 'pending') {
                $('#approvalButtons').hide();
            } else {
                $('#approvalButtons').show();
            }
        }).fail(function() {
            $('#leaveDetailContent').html('<div class="alert alert-danger fw-bold">ไม่สามารถดึงข้อมูลคำขอลาได้ กรุณาลองใหม่อีกครั้ง</div>');
        });
    });
});

function handleApproval(status) {
    const leaveId = $('#modal_leave_id').val();
    const comment = $('#modal_leave_comment').val();
    const statusText = status === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
    const confirmColor = status === 'approved' ? '#10b981' : '#ef4444';

    Swal.fire({
        title: `ยืนยันพิจารณา [${statusText}]?`,
        text: "เมื่อบันทึกแล้ว ระบบจะอัปเดตสถิติและสถานะไปยังบุคลากรทันที",
        icon: status === 'approved' ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#64748b',
        confirmButtonText: `ยืนยัน${statusText}`,
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'กำลังบันทึก...',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });

            $.post('<?= base_url('Admin/Leave/UpdateStatus') ?>', {
                leave_id: leaveId,
                leave_status: status,
                leave_comment: comment
            }, function(res) {
                if(res.status === 'success'){
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.message,
                        timer: 1400,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', res.message || 'ไม่สามารถบันทึกได้', 'error');
                }
            }).fail(function() {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
            });
        }
    });
}
</script>
<?= $this->endSection() ?>


