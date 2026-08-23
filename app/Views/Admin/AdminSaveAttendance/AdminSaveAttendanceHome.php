<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'ระบบบันทึกการมาทำงาน' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR ATTENDANCE SYSTEM
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --att-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
        --primary: #0284c7;
        --primary-dark: #1d4ed8;
        --primary-light: #e0f2fe;
        --secondary: #64748b;
        --success: #16a34a;
        --danger: #dc2626;
        --warning: #d97706;
        --info: #0284c7;
        --orange: #ea580c;
        --card-shadow: 0 4px 20px rgba(2, 132, 199, 0.08);
    }

    /* Hero Banner Card */
    .att-hero-card {
        background: var(--att-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .att-hero-card::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
        top: -80px;
        right: -60px;
        pointer-events: none;
    }

    .att-hero-card::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(56, 189, 248, 0) 70%);
        bottom: -50px;
        left: 25%;
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

    /* Filter Card */
    .filter-card-lux {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        padding: 1.25rem 1.4rem;
        margin-bottom: 1.5rem;
    }

    .filter-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.4rem;
        display: block;
    }

    .filter-input {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.65rem 0.9rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .filter-input:focus {
        border-color: #0284c7;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    /* Stat Cards */
    .stat-card-att {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .stat-card-att:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(2, 132, 199, 0.12);
        border-color: #bae6fd;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.82rem;
        font-weight: 600;
    }

    /* Section Cards */
    .section-card-lux {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Table Lux */
    .table-premium thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 750;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-premium tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .table-premium tbody tr:hover {
        background-color: #f0f9ff !important;
    }

    /* Status Badges */
    .badge-status {
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-present { background: #dcfce7 !important; color: #166534 !important; border: 1px solid #86efac; }
    .badge-absent { background: #fee2e2 !important; color: #991b1b !important; border: 1px solid #fca5a5; }
    .badge-sick { background: #fef3c7 !important; color: #92400e !important; border: 1px solid #fcd34d; }
    .badge-official { background: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #7dd3fc; }
    .badge-personal { background: #ffedd5 !important; color: #9a3412 !important; border: 1px solid #fdba74; }
    .badge-late { background: #e0e7ff !important; color: #3730a3 !important; border: 1px solid #c7d2fe; }
    .badge-other { background: #f1f5f9 !important; color: #475569 !important; border: 1px solid #cbd5e1; }

    .icon-present { background: #dcfce7; color: #16a34a; }
    .icon-absent { background: #fee2e2; color: #dc2626; }
    .icon-sick { background: #fef3c7; color: #d97706; }
    .icon-official { background: #e0f2fe; color: #0284c7; }
    .icon-personal { background: #ffedd5; color: #ea580c; }
    .icon-late { background: #e0e7ff; color: #4f46e5; }
    .icon-other { background: #f1f5f9; color: #64748b; }
</style>

<!-- Hero Banner Card -->
<div class="att-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-fingerprint me-1"></i> ระบบบันทึกเวลาทำงาน
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-calendar me-1"></i> รายงานสถิติการปฏิบัติงาน
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                ระบบบันทึกเวลาปฏิบัติหน้าที่และสแกนนิ้ว
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                ศูนย์จัดการ ติดตามเวลามาทำงาน นำเข้าไฟล์สแกนนิ้ว และสรุปรายงานการปฏิบัติหน้าที่ของบุคลากรทุกฝ่าย
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <button class="btn btn-sm btn-white text-primary fw-bold shadow-sm px-4 py-2 bg-white" data-bs-toggle="modal" data-bs-target="#attendanceModal" style="border-radius: 10px;">
                <i class="bx bx-plus-circle me-1"></i> บันทึกการมาทำงาน
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-card-lux">
    <div class="row g-3 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="filter-label"><i class="bx bx-time-five me-1 text-primary"></i>เลือกช่วงเวลา</label>
            <select id="dateType" class="form-select filter-input shadow-none fw-bold">
                <option value="day" selected>รายวัน (Daily)</option>
                <option value="month">รายเดือน (Monthly)</option>
                <option value="year">รายปี (Yearly)</option>
            </select>
        </div>
        <div class="col-lg-3 col-md-6">
            <div id="datePickerContainer">
                <label class="filter-label"><i class="bx bx-calendar me-1 text-primary"></i>เลือกวันที่</label>
                <input type="date" id="dateInput" class="form-control filter-input shadow-none fw-bold" value="<?=date('Y-m-d')?>">
            </div>
        </div>
        <div class="col-lg-6 col-md-12 text-md-end">
            <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#leaveSummaryModal">
                    <i class="bx bx-user-pin me-1"></i> สรุปรายบุคคล
                </button>
                <button class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#leaveByPositionModal">
                    <i class="bx bx-sitemap me-1"></i> สรุปตามตำแหน่ง
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart Section -->
    <div class="col-xl-4 col-lg-5">
        <div class="section-card-lux h-100">
            <h5 class="section-title mb-3">
                <i class="bx bx-pie-chart-alt-2 text-primary fs-4"></i> สถิติการมาทำงาน
            </h5>
            <div id="chart" style="min-height: 350px;"></div>
            <div class="mt-3 p-3 rounded-3 text-center small text-muted" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <i class="bx bx-info-circle me-1 text-primary"></i> แสดงเปอร์เซ็นต์การมาปฏิบัติหน้าที่ตามช่วงเวลาที่เลือก
            </div>
        </div>
    </div>

    <!-- Stats & Table Section -->
    <div class="col-xl-8 col-lg-7">
        <!-- Stat Cards Row (Loaded by JS) -->
        <div class="row g-3 mb-4" id="summaryBox">
            <!-- Dynamically loaded by JS -->
        </div>

        <!-- Attendance Table -->
        <div class="section-card-lux">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="section-title mb-0">
                    <i class="bx bx-table text-primary fs-4"></i> รายละเอียดการมาทำงาน
                </h5>
                <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm" id="btn-export-excel">
                    <i class="bx bx-download me-1"></i> ส่งออก Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-premium" id="TbDashboradAttendance">
                    <thead>
                        <tr>
                            <th style="width: 100px;">วันที่</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th class="text-center" style="width: 100px;">สถานะ</th>
                            <th class="text-center" style="width: 90px;">เวลามา</th>
                            <th class="text-center" style="width: 90px;">เวลากลับ</th>
                            <th>หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: บันทึกการมาทำงาน -->
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form id="attendance-form">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                    <div>
                        <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                            <i class="bx bx-edit fs-4"></i> บันทึกการมาปฏิบัติหน้าที่
                        </h5>
                        <p class="text-white text-opacity-80 mb-0 small">เลือกสถานะการมาปฏิบัติหน้าที่ของบุคลากรรายบุคคล หรือดึงจากไฟล์ Excel</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row mb-4 align-items-end g-3">
                        <div class="col-md-3">
                            <label class="filter-label"><i class="bx bx-calendar me-1 text-primary"></i>ระบุวันที่บันทึก</label>
                            <input type="date" id="att_date" name="att_date" class="form-control filter-input shadow-none fw-bold" value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="col-md-5">
                            <label class="filter-label d-flex justify-content-between align-items-center">
                                <span><i class="bx bx-file me-1 text-success"></i>ดึงข้อมูลจากไฟล์ Excel สแกนนิ้ว</span>
                                <a href="javascript:void(0);" class="text-primary small fw-bold" data-bs-toggle="collapse" data-bs-target="#excelFormatGuide">
                                    <i class="bx bx-help-circle me-1"></i>รูปแบบไฟล์ Excel
                                </a>
                            </label>
                            <div class="input-group">
                                <input type="file" id="excel_file" class="form-control shadow-none" accept=".xlsx, .xls">
                                <button type="button" class="btn btn-success fw-bold" id="btn-parse-excel">
                                    <i class="bx bx-import me-1"></i> ดึงข้อมูล
                                </button>
                            </div>
                            <div class="collapse mt-2" id="excelFormatGuide">
                                <div class="card card-body p-3 rounded-3 small" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <h6 class="fw-bold mb-2 text-primary" style="font-size: 0.85rem;"><i class="bx bx-info-circle me-1"></i>รายละเอียดข้อกำหนดไฟล์ Excel (รูปแบบใหม่)</h6>
                                    <ul class="mb-3 ps-3 text-muted" style="line-height: 1.5; font-size: 0.8rem;">
                                        <li>ต้องมีคอลัมน์ทั้งหมดอย่างน้อย <strong>5 คอลัมน์ (A ถึง E)</strong></li>
                                        <li><strong>คอลัมน์ A:</strong> เลขสแกนนิ้ว/รหัสพนักงาน (Finger ID)</li>
                                        <li><strong>คอลัมน์ B:</strong> ชื่อ - นามสกุล</li>
                                        <li><strong>คอลัมน์ C:</strong> วันที่บันทึก (รูปแบบ <code>YYYY-MM-DD</code> เช่น <code>2026-06-16</code>)</li>
                                        <li><strong>คอลัมน์ D:</strong> เวลาสแกนเข้างาน (รูปแบบ <code>HH:MM</code> หรือ <code>HH.MM</code> เช่น <code>07.55</code>)</li>
                                        <li><strong>คอลัมน์ E:</strong> เวลาสแกนออกงาน (รูปแบบ <code>HH:MM</code> หรือ <code>HH.MM</code> เช่น <code>16.44</code>)</li>
                                        <li>ระบบจะเริ่มอ่านข้อมูลตั้งแต่ <strong>แถวที่ 2 เป็นต้นไป</strong> (แถวแรกจะข้ามเป็นหัวตาราง)</li>
                                    </ul>
                                    <div class="text-center mt-2">
                                        <a href="<?= base_url('Admin/SaveAttendance/DownloadTemplate') ?>" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bx bx-download me-1"></i> ดาวน์โหลดไฟล์ตัวอย่าง (.xlsx)
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="alert alert-info border-0 mb-0 py-2 px-3 d-inline-flex align-items-center rounded-3" style="background: #f0f9ff; color: #0369a1;">
                                <i class="bx bx-info-circle me-2 fs-5"></i>
                                <span class="small fw-bold">บันทึกซ้ำจะนำสถานะล่าสุดทดทับข้อมูลเดิม</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loading Placeholder -->
                    <div id="attendance-loading" class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mb-0 fw-bold">กำลังโหลดข้อมูลบุคลากร...</p>
                    </div>
                    
                    <!-- Table Container (hidden until loaded) -->
                    <div id="attendance-table-container" style="display: none;">
                        <div class="table-responsive rounded-3 border" style="max-height: 50vh; overflow-y: auto;">
                            <table class="table table-premium mb-0" id="TbSaveAttendance">
                                <thead class="sticky-top bg-white">
                                    <tr class="text-center">
                                        <th class="text-start" style="min-width: 200px;">บุคลากร/สังกัด</th>
                                        <th style="width: 100px;">เลขสแกนนิ้ว</th>
                                        <th style="width: 90px;">เวลาเข้า</th>
                                        <th style="width: 90px;">เวลาออก</th>
                                        <th style="width: 50px;">มา</th>
                                        <th style="width: 50px;">สาย</th>
                                        <th style="width: 50px;">ขาด</th>
                                        <th style="width: 50px;">ลากิจ</th>
                                        <th style="width: 55px;">ลาป่วย</th>
                                        <th style="width: 55px;">ราชการ</th>
                                        <th style="width: 50px;">อื่นๆ</th>
                                        <th style="width: 120px;">หมายเหตุ</th>
                                    </tr>
                                </thead>
                                <tbody id="personnel-tbody">
                                    <!-- JS Populated -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="btn-save-attendance">
                        <i class="bx bx-check-circle me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: สรุปรายบุคคล -->
<div class="modal fade" id="leaveSummaryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                    <i class="bx bx-user-pin fs-4"></i> สรุปวันปฏิบัติหน้าที่และวันลา (รายบุคคล)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4 p-3 rounded-3 align-items-end" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="col-md-5">
                        <label class="filter-label"><i class="bx bx-calendar me-1 text-primary"></i>เริ่มต้นวันที่</label>
                        <input type="date" id="leaveDateStart" class="form-control filter-input shadow-none fw-bold" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-md-5">
                        <label class="filter-label"><i class="bx bx-calendar me-1 text-primary"></i>สิ้นสุดวันที่</label>
                        <input type="date" id="leaveDateEnd" class="form-control filter-input shadow-none fw-bold" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm" id="btnSearchLeave">
                            <i class="bx bx-search me-1"></i> ค้นหา
                        </button>
                    </div>
                </div>
                <div class="table-responsive rounded-3 border">
                    <table class="table table-premium mb-0" id="LeaveSummaryTable">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2" class="text-start">ชื่อ-นามสกุล</th>
                                <th rowspan="2">ตำแหน่ง</th>
                                <th rowspan="2">รวมวัน</th>
                                <th colspan="7">รายละเอียดสถานะ (จำนวนวัน)</th>
                            </tr>
                            <tr>
                                <th class="text-success">มา</th>
                                <th class="text-info">สาย</th>
                                <th class="text-danger">ขาด</th>
                                <th style="color: var(--orange);">ลากิจ</th>
                                <th class="text-warning">ลาป่วย</th>
                                <th class="text-primary">ราชการ</th>
                                <th class="text-secondary">อื่นๆ</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: สรุปตามตำแหน่ง -->
<div class="modal fade" id="leaveByPositionModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                    <i class="bx bx-sitemap fs-4"></i> รายงานสรุปวันลาแยกตามตำแหน่ง
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4 p-3 rounded-3 align-items-end" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="col-md-5">
                        <label class="filter-label"><i class="bx bx-calendar me-1 text-primary"></i>ระบุวันที่ต้องการตรวจสอบ</label>
                        <input type="date" id="leaveSummaryDate" class="form-control filter-input shadow-none fw-bold" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm" id="btnSearchLeaveByDay">
                            <i class="bx bx-search me-1"></i> ค้นหา
                        </button>
                    </div>
                </div>
                <div class="table-responsive rounded-3 border">
                    <table class="table table-premium mb-0" id="LeaveSummaryByPositionTable">
                        <thead class="text-center">
                            <tr>
                                <th class="text-start">กลุ่มตำแหน่ง/สังกัด</th>
                                <th>จำนวนบุคลากร</th>
                                <th>มาปฏิบัติหน้าที่</th>
                                <th>ลาป่วย</th>
                                <th>ลากิจ</th>
                                <th>ไปราชการ</th>
                                <th>อื่นๆ/ขาด</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminSaveAttendance.js?v=2.2"></script>
<script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminStaffLeaveReport.js?v=1.2"></script>
<script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminReportPositionLeave.js?v=1.3"></script>
<?= $this->endSection() ?>