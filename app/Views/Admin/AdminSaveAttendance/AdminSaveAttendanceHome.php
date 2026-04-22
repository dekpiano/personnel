<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary: #03c3ec;
        --primary-dark: #0056b3;
        --primary-light: #e7f3ff;
        --secondary: #6c757d;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #ffc107;
        --info: #17a2b8;
        --orange: #fd7e14;
        --body-bg: #f4f6f9;
        --card-shadow: 0 4px 20px rgba(0, 123, 255, 0.08);
    }

    .dashboard-wrapper {
        padding: 1.5rem;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 2rem 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .page-header h1 {
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin-bottom: 0;
    }

    /* Filter Section */
    .filter-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
        box-shadow: var(--card-shadow);
    }

    .filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .filter-input {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 0.7rem 1rem;
        transition: all 0.2s;
    }

    .filter-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
        box-shadow: var(--card-shadow);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #333;
    }

    .stat-label {
        color: var(--secondary);
        font-size: 0.9rem;
    }

    /* Section Card */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #e9ecef;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--primary);
    }

    /* Buttons */
    .btn-primary-custom {
        background: var(--primary);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        color: white;
    }

    .btn-outline-custom {
        border: 1.5px solid #dee2e6;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        background: white;
        color: #333;
        transition: all 0.2s;
    }

    .btn-outline-custom:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    /* Table Styling */
    .table-premium thead th {
        background: #f8f9fa;
        color: var(--secondary);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 1rem;
        border: none;
    }

    .table-premium tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        color: #495057;
    }

    .table-premium tbody tr:hover {
        background: var(--primary-light);
    }

    /* Status Badges */
    .badge-status {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-present { background: rgba(40, 167, 69, 0.15); color: var(--success); }
    .badge-absent { background: rgba(220, 53, 69, 0.15); color: var(--danger); }
    .badge-sick { background: rgba(255, 193, 7, 0.15); color: #b38600; }
    .badge-official { background: rgba(0, 123, 255, 0.15); color: var(--primary); }
    .badge-personal { background: rgba(253, 126, 20, 0.15); color: var(--orange); }
    .badge-late { background: rgba(23, 162, 184, 0.15); color: var(--info); }
    .badge-other { background: rgba(108, 117, 125, 0.15); color: var(--secondary); }

    /* Icon Background Colors */
    .icon-present { background: rgba(40, 167, 69, 0.15); color: var(--success); }
    .icon-absent { background: rgba(220, 53, 69, 0.15); color: var(--danger); }
    .icon-sick { background: rgba(255, 193, 7, 0.15); color: #b38600; }
    .icon-official { background: rgba(0, 123, 255, 0.15); color: var(--primary); }
    .icon-personal { background: rgba(253, 126, 20, 0.15); color: var(--orange); }
    .icon-late { background: rgba(23, 162, 184, 0.15); color: var(--info); }
    .icon-other { background: rgba(108, 117, 125, 0.15); color: var(--secondary); }

    /* Modal Styling */
    .modal-content {
        border-radius: 20px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #f1f3f5;
        padding: 1.5rem 2rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid #f1f3f5;
        padding: 1.5rem 2rem;
        background: #f8f9fa;
        border-radius: 0 0 20px 20px;
    }
</style>

<div class="dashboard-wrapper">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-calendar2-check me-2"></i>ระบบบันทึกการมาทำงาน</h1>
            <p>จัดการและติดตามการมาปฏิบัติหน้าที่ของบุคลากรทุกตำแหน่ง</p>
        </div>
        <div>
            <button class="btn btn-light btn-lg fw-bold" data-bs-toggle="modal" data-bs-target="#attendanceModal">
                <i class="bi bi-plus-circle me-2"></i>บันทึกการมาทำงาน
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="filter-label">เลือกช่วงเวลา</label>
                <select id="dateType" class="form-select filter-input">
                    <option value="day" selected>รายวัน</option>
                    <option value="month">รายเดือน</option>
                    <option value="year">รายปี</option>
                </select>
            </div>
            <div class="col-md-3">
                <div id="datePickerContainer">
                    <label class="filter-label">เลือกวันที่</label>
                    <input type="date" id="dateInput" class="form-control filter-input" value="<?=date('Y-m-d')?>">
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                    <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#leaveSummaryModal">
                        <i class="bi bi-person-lines-fill me-2"></i>สรุปรายบุคคล
                    </button>
                    <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#leaveByPositionModal">
                        <i class="bi bi-diagram-3 me-2"></i>สรุปตามตำแหน่ง
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart Section -->
        <div class="col-xl-4 col-lg-5">
            <div class="section-card">
                <h5 class="section-title">
                    <i class="bi bi-pie-chart-fill"></i> สถิติการมาทำงาน
                </h5>
                <div id="chart" style="min-height: 350px;"></div>
                <div class="mt-3 p-3 bg-light rounded-3 text-center small text-muted">
                    <i class="bi bi-info-circle me-1"></i> แสดงเปอร์เซ็นต์การมาปฏิบัติหน้าที่ตามช่วงเวลาที่เลือก
                </div>
            </div>
        </div>

        <!-- Stats & Table Section -->
        <div class="col-xl-8 col-lg-7">
            <!-- Stat Cards Row -->
            <div class="row g-3 mb-4" id="summaryBox">
                <!-- Dynamically loaded by JS -->
            </div>

            <!-- Attendance Table -->
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-table"></i> รายละเอียดการมาทำงาน
                    </h5>
                    <button class="btn btn-sm btn-light rounded-pill px-3" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> พิมพ์รายงาน
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium" id="TbDashboradAttendance">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>สถานะ</th>
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
</div>

<!-- Modal: บันทึกการมาทำงาน -->
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form id="attendance-form">
                <div class="modal-header">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>บันทึกการมาทำงาน</h5>
                        <p class="text-muted mb-0 small">กรุณาเลือกสถานะการมาปฏิบัติหน้าที่ของบุคลากรรายบุคคล</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row mb-4 align-items-end g-3">
                        <div class="col-md-3">
                            <label class="filter-label">ระบุวันที่บันทึก</label>
                            <input type="date" id="att_date" name="att_date" class="form-control filter-input" value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="col-md-5">
                            <label class="filter-label">ดึงข้อมูลจากไฟล์ Excel สแกนนิ้ว</label>
                            <div class="input-group">
                                <input type="file" id="excel_file" class="form-control" accept=".xlsx, .xls">
                                <button type="button" class="btn btn-success" id="btn-parse-excel">ดึงข้อมูล</button>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="alert alert-info border-0 mb-0 py-2 px-3 d-inline-flex align-items-center rounded-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <span class="small">บันทึกซ้ำจะนำสถานะล่าสุดทบทับข้อมูลเดิม</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loading Placeholder -->
                    <div id="attendance-loading" class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mb-0">กำลังโหลดข้อมูลบุคลากร...</p>
                    </div>
                    
                    <!-- Table Container (hidden until loaded) -->
                    <div id="attendance-table-container" style="display: none;">
                        <div class="table-responsive rounded-3 border" style="max-height: 50vh; overflow-y: auto;">
                            <table class="table table-premium mb-0" id="TbSaveAttendance">
                                <thead class="sticky-top bg-white">
                                    <tr class="text-center">
                                        <th class="text-start" style="min-width: 200px;">บุคลากร/สังกัด</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary-custom" id="btn-save-attendance">
                        <i class="bi bi-check2-circle me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal: สรุปรายบุคคล -->
<div class="modal fade" id="leaveSummaryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>สรุปวันปฏิบัติหน้าที่และวันลา (รายบุคคล)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4 p-3 bg-light rounded-3 align-items-end">
                    <div class="col-md-5">
                        <label class="filter-label">เริ่มต้นวันที่</label>
                        <input type="date" id="leaveDateStart" class="form-control filter-input" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-md-5">
                        <label class="filter-label">สิ้นสุดวันที่</label>
                        <input type="date" id="leaveDateEnd" class="form-control filter-input" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary-custom w-100" id="btnSearchLeave">
                            <i class="bi bi-search"></i> ค้นหา
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2 text-primary"></i>รายงานสรุปวันลาแยกตามตำแหน่ง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4 p-3 bg-light rounded-3 align-items-end">
                    <div class="col-md-4">
                        <label class="filter-label">ระบุวันที่ต้องการตรวจสอบ</label>
                        <input type="date" id="leaveSummaryDate" class="form-control filter-input" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary-custom w-100" id="btnSearchLeaveByDay">
                            <i class="bi bi-search"></i> ค้นหา
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
<script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminSaveAttendance.js?v=2.0"></script>
<script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminStaffLeaveReport.js?v=1.1"></script>
<script src="<?=base_url()?>/assets/js/Admin/AdminSaveAttendance/AdminReportPositionLeave.js?v=1.2"></script>
<?= $this->endSection() ?>