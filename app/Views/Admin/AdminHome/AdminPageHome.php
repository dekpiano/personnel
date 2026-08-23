<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --dash-primary: #0284c7;
        --dash-primary-dark: #0369a1;
        --dash-accent: #3b82f6;
        --dash-success: #10b981;
        --dash-warning: #f59e0b;
        --dash-danger: #ef4444;
        --dash-purple: #8b5cf6;
        --dash-bg-card: #ffffff;
        --dash-border: #e2e8f0;
        --dash-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
        --dash-shadow-hover: 0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.05);
    }

    /* Staggered Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.5);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(52, 211, 153, 0);
        }
    }

    @keyframes floatSlow {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
    }

    .reveal-box {
        opacity: 0;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.12s; }
    .delay-3 { animation-delay: 0.18s; }
    .delay-4 { animation-delay: 0.25s; }
    .delay-5 { animation-delay: 0.32s; }

    /* Dashboard Container */
    .dashboard-container {
        padding: 0.5rem 0.25rem;
    }

    /* Modern Hero Banner (Rich Blue Theme) */
    .hero-panel {
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #0369a1 100%);
        border-radius: 24px;
        padding: 2.25rem 2.5rem;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 20px 35px -10px rgba(2, 132, 199, 0.4), 0 10px 15px -3px rgba(15, 23, 42, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1.75rem;
    }

    .hero-panel::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 550px;
        height: 550px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(56, 189, 248, 0.15) 40%, transparent 70%);
        pointer-events: none;
    }

    .hero-panel::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    }

    .hero-badge {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background-color: #34d399;
        border-radius: 50%;
        animation: pulseGlow 2s infinite;
    }

    .hero-title {
        font-size: clamp(1.6rem, 3.5vw, 2.3rem);
        font-weight: 800;
        line-height: 1.2;
        margin-top: 0.75rem;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
        color: #ffffff;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    }

    .hero-desc {
        color: #e0f2fe;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        max-width: 600px;
        font-weight: 400;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .hero-time-card {
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        color: #fff;
        text-align: right;
        display: inline-block;
        min-width: 260px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .hero-clock {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: #ffffff;
        line-height: 1;
        margin-bottom: 4px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .hero-date-th {
        font-size: 0.85rem;
        color: #bae6fd;
        font-weight: 700;
    }

    .btn-hero-primary {
        background: #ffffff;
        color: #0369a1;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.25s ease;
    }

    .btn-hero-primary:hover {
        background: #f0fdf4;
        color: #0284c7;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
    }

    .btn-hero-glass {
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(8px);
        transition: all 0.25s ease;
    }

    .btn-hero-glass:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        border-color: #ffffff;
        transform: translateY(-2px);
    }

    /* KPI Stat Cards */
    .kpi-card {
        background: var(--dash-bg-card);
        border-radius: 20px;
        padding: 1.35rem 1.4rem;
        border: 1px solid var(--dash-border);
        box-shadow: var(--dash-shadow);
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--dash-shadow-hover);
        border-color: rgba(2, 132, 199, 0.4);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: transparent;
        transition: background 0.3s;
    }

    .kpi-card.kpi-blue::before { background: linear-gradient(90deg, #0284c7, #38bdf8); }
    .kpi-card.kpi-green::before { background: linear-gradient(90deg, #059669, #34d399); }
    .kpi-card.kpi-amber::before { background: linear-gradient(90deg, #d97706, #fbbf24); }
    .kpi-card.kpi-purple::before { background: linear-gradient(90deg, #7c3aed, #a78bfa); }

    .kpi-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .kpi-card:hover .kpi-icon-wrap {
        transform: scale(1.1) rotate(5deg);
    }

    .kpi-number {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        font-size: 2.1rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .kpi-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-meta {
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        border-top: 1px solid #f1f5f9;
    }

    /* Quick Action Cards */
    .quick-action-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.15rem 1.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #1e293b;
        position: relative;
        overflow: hidden;
    }

    .quick-action-card:hover {
        transform: translateY(-4px) scale(1.01);
        border-color: #0284c7;
        box-shadow: 0 12px 20px -6px rgba(2, 132, 199, 0.15);
        color: #0284c7;
    }

    .quick-action-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        transition: all 0.3s ease;
    }

    .quick-action-card:hover .quick-action-icon {
        transform: scale(1.12);
    }

    .quick-action-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .quick-action-desc {
        font-size: 0.76rem;
        color: #64748b;
        margin-bottom: 0;
    }

    .quick-action-arrow {
        margin-left: auto;
        font-size: 1.2rem;
        color: #cbd5e1;
        transition: all 0.3s ease;
    }

    .quick-action-card:hover .quick-action-arrow {
        color: #0284c7;
        transform: translateX(4px);
    }

    /* Section Cards */
    .dashboard-card {
        background: #ffffff;
        border-radius: 22px;
        border: 1px solid var(--dash-border);
        box-shadow: var(--dash-shadow);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .dashboard-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .dashboard-card-body {
        padding: 1.5rem;
        flex-grow: 1;
    }

    /* Attendance Visual Bars */
    .attendance-bar-container {
        display: flex;
        height: 12px;
        border-radius: 999px;
        overflow: hidden;
        background: #f1f5f9;
        margin-bottom: 1.25rem;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
    }

    .attendance-segment {
        transition: width 1s ease-in-out;
        position: relative;
    }

    .pill-stat {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        text-align: center;
        transition: all 0.2s;
    }

    .pill-stat:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .pill-stat-num {
        font-family: 'Inter', system-ui, sans-serif;
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .pill-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* High-contrast list items */
    .personnel-list-item {
        padding: 0.85rem 1rem;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        background: #ffffff;
        margin-bottom: 0.65rem;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .personnel-list-item:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateX(3px);
    }

    .avatar-sm-custom {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    .avatar-fallback {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    /* High Contrast Badges */
    .badge-contrast-primary {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #7dd3fc;
        font-weight: 700;
    }
    .badge-contrast-success {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
        font-weight: 700;
    }
    .badge-contrast-warning {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid #fcd34d;
        font-weight: 700;
    }
    .badge-contrast-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
        font-weight: 700;
    }
    .badge-contrast-purple {
        background-color: #f3e8ff;
        color: #6b21a8;
        border: 1px solid #d8b4fe;
        font-weight: 700;
    }

    /* Empty state */
    .empty-state-box {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        opacity: 0.6;
    }

    /* SVG Illustration Floating */
    .hero-illustration {
        animation: floatSlow 6s ease-in-out infinite;
        max-width: 220px;
        filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.3));
    }

    @media (max-width: 991.98px) {
        .hero-panel { padding: 1.75rem 1.5rem; border-radius: 18px; }
        .hero-time-card { text-align: left; width: 100%; margin-top: 1.25rem; }
        .hero-clock { font-size: 1.8rem; }
        .hero-illustration { display: none; }
    }
</style>

<div class="dashboard-container">
    
    <!-- 1. Hero Executive Panel (Blue Theme) -->
    <div class="reveal-box delay-1">
        <div class="hero-panel">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-badge mb-3">
                        <span class="live-dot"></span>
                        <span>ระบบบริหารทรัพยากรบุคคล SKJ • ปีงบประมาณ <?= esc($fiscalYearBE) ?></span>
                    </div>
                    
                    <h1 class="hero-title" id="greetingTitle">
                        ยินดีต้อนรับสู่ระบบบริหารงานบุคคล
                    </h1>
                    
                    <p class="hero-desc">
                        โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ — บริหารจัดการบุคลากร การลงเวลา การลา และการประเมิน PA ครบจบในที่เดียว
                    </p>

                    <div class="d-flex flex-wrap gap-2 pt-1">
                        <a href="<?= base_url('Admin/WorkPerson/Personnel') ?>" class="btn btn-sm btn-hero-primary rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                            <i class='bx bx-user-plus fs-5'></i> เพิ่มบุคลากร
                        </a>
                        <a href="<?= base_url('Admin/SaveAttendance') ?>" class="btn btn-sm btn-hero-glass rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                            <i class='bx bx-fingerprint fs-5'></i> ลงเวลาทำงาน
                        </a>
                        <a href="<?= base_url('Admin/Leave') ?>" class="btn btn-sm btn-hero-glass rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                            <i class='bx bx-calendar-event fs-5'></i> ตรวจสอบการลา
                            <?php if ($countPendingLeave > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-1 px-2"><?= $countPendingLeave ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                    <div class="d-inline-flex flex-column align-items-lg-end">
                        <!-- Free SVG Executive Hero Graphic -->
                        <div class="d-none d-lg-block mb-3">
                            <svg class="hero-illustration" viewBox="0 0 240 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="20" y="20" width="200" height="120" rx="16" fill="url(#heroGrad1)" fill-opacity="0.25" stroke="#ffffff" stroke-width="1.5" stroke-dasharray="4 4" />
                                <rect x="35" y="35" width="80" height="40" rx="10" fill="#ffffff" fill-opacity="0.3" />
                                <rect x="125" y="35" width="80" height="40" rx="10" fill="#38bdf8" fill-opacity="0.4" />
                                <circle cx="55" cy="55" r="10" fill="#ffffff" />
                                <path d="M72 50H100M72 58H90" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" />
                                <path d="M140 60L155 45L170 52L190 38" stroke="#34d399" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                <rect x="35" y="90" width="170" height="35" rx="8" fill="#0f172a" fill-opacity="0.6" stroke="#38bdf8" stroke-width="1" />
                                <circle cx="52" cy="107" r="6" fill="#34d399" />
                                <path d="M68 107H130M155 107H190" stroke="#e0f2fe" stroke-width="2.5" stroke-linecap="round" />
                                <defs>
                                    <linearGradient id="heroGrad1" x1="20" y1="20" x2="220" y2="140" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#ffffff" />
                                        <stop offset="1" stop-color="#38bdf8" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>

                        <!-- Realtime Live Clock Card -->
                        <div class="hero-time-card">
                            <div class="hero-clock" id="liveDigitalClock">00:00:00</div>
                            <div class="hero-date-th">
                                <i class='bx bx-calendar me-1'></i> <?= thai_date_full($todayDate) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Core 4 KPI Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Personnel -->
        <div class="col-sm-6 col-xl-3 reveal-box delay-2">
            <div class="kpi-card kpi-blue">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="kpi-label">บุคลากรในสังกัด</div>
                        <div class="kpi-number" data-counter="<?= (int)$countAllPersonnel ?>"><?= number_format($countAllPersonnel) ?></div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                        <i class='bx bx-id-card'></i>
                    </div>
                </div>
                <div>
                    <div class="progress" style="height: 6px; border-radius: 999px; background: #e2e8f0;">
                        <div class="progress-bar bg-primary" style="width: 100%;"></div>
                    </div>
                    <div class="kpi-meta">
                        <span class="badge badge-contrast-primary rounded-pill px-2 py-1">
                            <i class='bx bx-check-circle me-1'></i>สถานะใช้งาน
                        </span>
                        <a href="<?= base_url('Admin/WorkPerson/Personnel') ?>" class="text-primary fw-bold text-decoration-none">
                            ดูรายชื่อ <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Today's Attendance -->
        <div class="col-sm-6 col-xl-3 reveal-box delay-3">
            <div class="kpi-card kpi-green">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="kpi-label">ลงเวลาวันนี้</div>
                        <div class="kpi-number text-success" data-counter="<?= (int)$countAttendanceToday ?>"><?= number_format($countAttendanceToday) ?></div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class='bx bx-fingerprint'></i>
                    </div>
                </div>
                <div>
                    <div class="progress" style="height: 6px; border-radius: 999px; background: #e2e8f0;">
                        <div class="progress-bar bg-success" style="width: <?= $attendanceRate ?>%;"></div>
                    </div>
                    <div class="kpi-meta">
                        <span class="text-muted fw-bold">คิดเป็น <?= $attendanceRate ?>%</span>
                        <a href="<?= base_url('Admin/SaveAttendance') ?>" class="text-success fw-bold text-decoration-none">
                            รายละเอียด <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Pending Leave Requests -->
        <div class="col-sm-6 col-xl-3 reveal-box delay-4">
            <div class="kpi-card kpi-amber">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="kpi-label">รอพิจารณาการลา</div>
                        <div class="kpi-number text-warning" data-counter="<?= (int)$countPendingLeave ?>"><?= number_format($countPendingLeave) ?></div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class='bx bx-time-five'></i>
                    </div>
                </div>
                <div>
                    <div class="progress" style="height: 6px; border-radius: 999px; background: #e2e8f0;">
                        <div class="progress-bar bg-warning" style="width: <?= ($countPendingLeave > 0) ? '100' : '0' ?>%;"></div>
                    </div>
                    <div class="kpi-meta">
                        <?php if ($countPendingLeave > 0): ?>
                            <span class="badge badge-contrast-warning rounded-pill px-2 py-1">
                                <i class='bx bx-bell me-1'></i>มีคำขอใหม่
                            </span>
                        <?php else: ?>
                            <span class="text-muted small">เรียบร้อยทั้งหมด</span>
                        <?php endif; ?>
                        <a href="<?= base_url('Admin/Leave') ?>" class="text-warning fw-bold text-decoration-none">
                            ตรวจสอบ <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: PA Evaluations / Current Fiscal Year -->
        <div class="col-sm-6 col-xl-3 reveal-box delay-5">
            <div class="kpi-card kpi-purple">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="kpi-label">การประเมิน PA (พ.ศ. <?= esc($fiscalYearBE) ?>)</div>
                        <div class="kpi-number text-purple" style="color: #8b5cf6;" data-counter="<?= (int)$countPendingEvaluations ?>"><?= number_format($countPendingEvaluations) ?></div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class='bx bx-award'></i>
                    </div>
                </div>
                <div>
                    <div class="progress" style="height: 6px; border-radius: 999px; background: #e2e8f0;">
                        <div class="progress-bar" style="width: 80%; background: #8b5cf6;"></div>
                    </div>
                    <div class="kpi-meta">
                        <span class="badge badge-contrast-purple rounded-pill px-2 py-1">
                            <i class='bx bx-shield-quarter me-1'></i>ระบบประเมิน
                        </span>
                        <a href="<?= base_url('Admin/PaConfig') ?>" class="fw-bold text-decoration-none" style="color: #8b5cf6;">
                            จัดการ <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Quick Action Hub (6 Modern Service Modules) -->
    <div class="mb-4 reveal-box delay-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class='bx bxs-grid-alt text-primary'></i> เมนูลัดและบริการระบบหลัก
                </h5>
            </div>
            <span class="text-muted small">Quick Shortcuts</span>
        </div>

        <div class="row g-3">
            <!-- 1. Personnel Management -->
            <div class="col-md-4 col-sm-6">
                <a href="<?= base_url('Admin/WorkPerson/Personnel') ?>" class="quick-action-card">
                    <div class="quick-action-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class='bx bx-user-pin'></i>
                    </div>
                    <div>
                        <div class="quick-action-title">ทะเบียนประวัติบุคลากร</div>
                        <p class="quick-action-desc">ข้อมูลส่วนตัว ตำแหน่ง และการแต่งตั้ง</p>
                    </div>
                    <i class='bx bx-chevron-right quick-action-arrow'></i>
                </a>
            </div>

            <!-- 2. Attendance System -->
            <div class="col-md-4 col-sm-6">
                <a href="<?= base_url('Admin/SaveAttendance') ?>" class="quick-action-card">
                    <div class="quick-action-icon" style="background: #dcfce7; color: #15803d;">
                        <i class='bx bx-time-five'></i>
                    </div>
                    <div>
                        <div class="quick-action-title">ระบบบันทึกเวลาทำงาน</div>
                        <p class="quick-action-desc">สแกนนิ้ว เช็คชื่อ และสถิติการมาทำงาน</p>
                    </div>
                    <i class='bx bx-chevron-right quick-action-arrow'></i>
                </a>
            </div>

            <!-- 3. Leave Management -->
            <div class="col-md-4 col-sm-6">
                <a href="<?= base_url('Admin/Leave') ?>" class="quick-action-card">
                    <div class="quick-action-icon" style="background: #fef3c7; color: #b45309;">
                        <i class='bx bx-calendar-check'></i>
                    </div>
                    <div>
                        <div class="quick-action-title">ระบบบริหารการลา</div>
                        <p class="quick-action-desc">คำนวณโควตา 23/45 วัน และอนุมัติใบลา</p>
                    </div>
                    <i class='bx bx-chevron-right quick-action-arrow'></i>
                </a>
            </div>

            <!-- 4. PA System -->
            <div class="col-md-4 col-sm-6">
                <a href="<?= base_url('Admin/PaConfig') ?>" class="quick-action-card">
                    <div class="quick-action-icon" style="background: #f3e8ff; color: #7e22ce;">
                        <i class='bx bx-task'></i>
                    </div>
                    <div>
                        <div class="quick-action-title">ระบบประเมินผล PA</div>
                        <p class="quick-action-desc">ข้อตกลง ตัวชี้วัด และการให้คะแนน</p>
                    </div>
                    <i class='bx bx-chevron-right quick-action-arrow'></i>
                </a>
            </div>

            <!-- 5. Holiday Calendar -->
            <div class="col-md-4 col-sm-6">
                <a href="<?= base_url('Admin/Holiday') ?>" class="quick-action-card">
                    <div class="quick-action-icon" style="background: #ffe4e6; color: #be123c;">
                        <i class='bx bx-calendar-star'></i>
                    </div>
                    <div>
                        <div class="quick-action-title">ปฏิทินวันหยุดราชการ</div>
                        <p class="quick-action-desc">กำหนดวันหยุดและวันหยุดพิเศษประจำปี</p>
                    </div>
                    <i class='bx bx-chevron-right quick-action-arrow'></i>
                </a>
            </div>

            <!-- 6. Roles & Settings -->
            <div class="col-md-4 col-sm-6">
                <a href="<?= base_url('Admin/Rloes/Setting') ?>" class="quick-action-card">
                    <div class="quick-action-icon" style="background: #f1f5f9; color: #334155;">
                        <i class='bx bx-shield-quarter'></i>
                    </div>
                    <div>
                        <div class="quick-action-title">จัดการสิทธิ์และความปลอดภัย</div>
                        <p class="quick-action-desc">กำหนดสิทธิ์ผู้ดูแลและผู้ตรวจประเมิน</p>
                    </div>
                    <i class='bx bx-chevron-right quick-action-arrow'></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Two Column Operational Insights Section -->
    <div class="row g-4">
        <!-- Left: Today's Attendance & Active Leaves Summary -->
        <div class="col-lg-6 reveal-box delay-3">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class='bx bx-pie-chart-alt-2 text-primary fs-5'></i> ภาพรวมการปฏิบัติงานวันนี้
                        </h6>
                        <small class="text-muted">วันที่ <?= thai_date_medium($todayDate) ?></small>
                    </div>
                    <a href="<?= base_url('Admin/SaveAttendance') ?>" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-primary">
                        ตรวจบันทึกเวลา
                    </a>
                </div>
                <div class="dashboard-card-body">
                    <!-- Dynamic Attendance Progress Bar -->
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark">สัดส่วนการลงเวลา (<?= $countAttendanceToday ?> / <?= $countAllPersonnel ?> คน)</span>
                        <span class="badge badge-contrast-success rounded-pill px-2"><?= $attendanceRate ?>% มาปฏิบัติงาน</span>
                    </div>

                    <?php 
                        $pctOnTime = ($countAllPersonnel > 0) ? ($attOnTime / $countAllPersonnel) * 100 : 0;
                        $pctLate = ($countAllPersonnel > 0) ? ($attLate / $countAllPersonnel) * 100 : 0;
                        $pctLeave = ($countAllPersonnel > 0) ? ($attLeave / $countAllPersonnel) * 100 : 0;
                        $pctNotCheck = ($countAllPersonnel > 0) ? ($attNotChecked / $countAllPersonnel) * 100 : 0;
                    ?>
                    <div class="attendance-bar-container">
                        <div class="attendance-segment bg-success" style="width: <?= $pctOnTime ?>%;" title="มาตรงเวลา: <?= $attOnTime ?> คน"></div>
                        <div class="attendance-segment bg-warning" style="width: <?= $pctLate ?>%;" title="มาสาย: <?= $attLate ?> คน"></div>
                        <div class="attendance-segment bg-info" style="width: <?= $pctLeave ?>%;" title="ลา: <?= $attLeave ?> คน"></div>
                        <div class="attendance-segment bg-light" style="width: <?= $pctNotCheck ?>%;" title="ยังไม่ลงเวลา: <?= $attNotChecked ?> คน"></div>
                    </div>

                    <!-- 4 Attendance Counters -->
                    <div class="row g-2 mb-4">
                        <div class="col-3">
                            <div class="pill-stat">
                                <div class="pill-stat-num text-success"><?= $attOnTime ?></div>
                                <div class="pill-stat-label">มาตรงเวลา</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="pill-stat">
                                <div class="pill-stat-num text-warning"><?= $attLate ?></div>
                                <div class="pill-stat-label">มาสาย</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="pill-stat">
                                <div class="pill-stat-num text-info"><?= $attLeave ?></div>
                                <div class="pill-stat-label">ลางาน</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="pill-stat">
                                <div class="pill-stat-num text-muted"><?= $attNotChecked ?></div>
                                <div class="pill-stat-label">ยังไม่บันทึก</div>
                            </div>
                        </div>
                    </div>

                    <!-- Leaves Today List -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark small text-uppercase">
                                <i class='bx bx-user-x text-danger me-1'></i> บุคลากรที่ลางานวันนี้ (<?= count($leavesToday) ?> คน)
                            </span>
                            <a href="<?= base_url('Admin/Leave') ?>" class="small text-decoration-none fw-bold text-primary">ดูทั้งหมด →</a>
                        </div>

                        <?php if (!empty($leavesToday)): ?>
                            <div style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($leavesToday as $l): ?>
                                    <div class="personnel-list-item">
                                        <?php if (!empty($l['pers_img'])): ?>
                                            <img src="<?= base_url('uploads/personnel/' . $l['pers_img']) ?>" class="avatar-sm-custom" alt="Profile" onerror="this.src='https://placehold.co/80x80/e0f2fe/0369a1?text=SKJ';">
                                        <?php else: ?>
                                            <div class="avatar-fallback">
                                                <?= mb_substr($l['pers_firstname'] ?? 'U', 0, 1, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-dark text-truncate small">
                                                <?= esc(($l['pers_prefix'] ?? '') . ($l['pers_firstname'] ?? '') . ' ' . ($l['pers_lastname'] ?? '')) ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.73rem;">
                                                <?= esc($l['leave_topic'] ?? '-') ?>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge badge-contrast-warning rounded-pill px-2 py-1 small">
                                                <?= esc($l['leave_type_name'] ?? 'ลา') ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-box py-3">
                                <i class='bx bx-check-shield fs-1 text-success mb-2 d-block'></i>
                                <span class="small fw-bold text-muted">วันนี้ไม่มีบุคลากรแจ้งลางาน</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Recent Leave Requests & Admin Action Center -->
        <div class="col-lg-6 reveal-box delay-4">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class='bx bx-bell text-warning fs-5'></i> รายการขอลาล่าสุด
                        </h6>
                        <small class="text-muted">คำขอลาเพื่อรอพิจารณาและประวัติ</small>
                    </div>
                    <a href="<?= base_url('Admin/Leave') ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        จัดการการลา
                    </a>
                </div>
                <div class="dashboard-card-body">
                    <?php if (!empty($recentLeaves)): ?>
                        <div>
                            <?php foreach ($recentLeaves as $req): ?>
                                <div class="personnel-list-item">
                                    <?php if (!empty($req['pers_img'])): ?>
                                        <img src="<?= base_url('uploads/personnel/' . $req['pers_img']) ?>" class="avatar-sm-custom" alt="Profile" onerror="this.src='https://placehold.co/80x80/e0f2fe/0369a1?text=SKJ';">
                                    <?php else: ?>
                                        <div class="avatar-fallback">
                                            <?= mb_substr($req['pers_firstname'] ?? 'U', 0, 1, 'UTF-8') ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold text-dark text-truncate small">
                                                <?= esc(($req['pers_prefix'] ?? '') . ($req['pers_firstname'] ?? '') . ' ' . ($req['pers_lastname'] ?? '')) ?>
                                            </span>
                                            <small class="text-muted" style="font-size: 0.7rem;">
                                                <?= thai_date_medium($req['leave_start_date']) ?>
                                                <?= ($req['leave_start_date'] != $req['leave_end_date']) ? ' - ' . thai_date_medium($req['leave_end_date']) : '' ?>
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge badge-contrast-primary rounded-pill px-2" style="font-size: 0.7rem;">
                                                <?= esc($req['leave_type_name'] ?? 'ลา') ?> (<?= esc($req['leave_total_days']) ?> วัน)
                                            </span>
                                            <?php if ($req['leave_status'] === 'pending'): ?>
                                                <span class="badge badge-contrast-warning rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                    <i class='bx bx-time me-1'></i>รออนุมัติ
                                                </span>
                                            <?php elseif ($req['leave_status'] === 'approved'): ?>
                                                <span class="badge badge-contrast-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                    <i class='bx bx-check me-1'></i>อนุมัติแล้ว
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-contrast-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                    <i class='bx bx-x me-1'></i>ไม่อนุมัติ
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-box">
                            <svg class="empty-state-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="32" r="28" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="4 4" />
                                <path d="M22 32L29 39L42 25" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <h6 class="fw-bold text-dark mb-1">ไม่มีคำขอลาค้างในระบบ</h6>
                            <p class="text-muted small mb-0">ระบบพร้อมใช้งานและข้อมูลเป็นปัจจุบัน</p>
                        </div>
                    <?php endif; ?>

                    <!-- Bottom Server & Security Quick Status -->
                    <div class="mt-3 p-3 rounded-3 bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class='bx bxs-shield-check text-success fs-4'></i>
                            <div>
                                <div class="fw-bold text-dark small">ความปลอดภัยของระบบ</div>
                                <div class="text-muted" style="font-size: 0.72rem;">ผู้ดูแลระบบในระบบ <?= number_format($countTotalUsers) ?> บัญชี</div>
                            </div>
                        </div>
                        <a href="<?= base_url('Admin/Rloes/Setting') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 small fw-bold">
                            ตรวจสอบสิทธิ์
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Dynamic Greeting Based on Current Time
        function updateGreeting() {
            const hour = new Date().getHours();
            let greeting = 'ยินดีต้อนรับสู่ระบบบริหารงานบุคคล';
            if (hour >= 5 && hour < 12) {
                greeting = 'อรุณสวัสดิ์ ☀️ ยินดีต้อนรับสู่ SKJ Personnel';
            } else if (hour >= 12 && hour < 17) {
                greeting = 'สวัสดีตอนบ่าย 🌤️ ศูนย์บริหารงานบุคคล SKJ';
            } else if (hour >= 17 && hour < 21) {
                greeting = 'สวัสดีตอนเย็น 🌇 ศูนย์บริหารงานบุคคล SKJ';
            } else {
                greeting = 'ราตรีสวัสดิ์ 🌙 ศูนย์บริหารงานบุคคล SKJ';
            }
            const titleEl = document.getElementById('greetingTitle');
            if (titleEl) {
                titleEl.innerText = greeting;
            }
        }
        updateGreeting();

        // 2. Realtime Digital Live Clock
        function updateLiveClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('liveDigitalClock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        updateLiveClock();
        setInterval(updateLiveClock, 1000);

        // 3. Smooth Counter Animation for KPI Numbers
        const counters = document.querySelectorAll('[data-counter]');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-counter');
            if (target === 0) return;
            
            const duration = 1200; // ms
            const stepTime = 20;
            const steps = duration / stepTime;
            const increment = target / steps;
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.innerText = Number(target).toLocaleString('th-TH');
                    clearInterval(timer);
                } else {
                    counter.innerText = Math.floor(current).toLocaleString('th-TH');
                }
            }, stepTime);
        });
    });
</script>
<?= $this->endSection() ?>
