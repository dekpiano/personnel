<style>
    .leave-stat-card {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        text-align: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .leave-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .leave-stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }
    .leave-stat-card .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }
    .leave-stat-card.sick .stat-value { color: #dc3545; }
    .leave-stat-card.personal .stat-value { color: #fd7e14; }
    .leave-stat-card.official .stat-value { color: #0d6efd; }
    .leave-stat-card.other .stat-value { color: #6c757d; }
    .leave-stat-card.present .stat-value { color: #198754; }
    .leave-stat-card.absent .stat-value { color: #212529; }
    .date-range-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>

<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h6 class="fw-bold text-primary mb-0 d-flex align-items-center">
            <i class='bx bx-calendar-x fs-4 me-2'></i> สรุปการลา / การมาทำงาน
        </h6>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <!-- Date Range Picker -->
            <div class="input-group input-group-sm" style="width: auto;">
                <span class="input-group-text bg-light border-end-0"><i class='bx bx-calendar'></i></span>
                <input type="text" class="form-control border-start-0 selectorLeave" id="leave_start_date" placeholder="วันเริ่มต้น" style="width: 110px;"
                    value="<?= date('01/01/') . (date('Y') + 543) ?>">
            </div>
            <span class="text-muted">ถึง</span>
            <div class="input-group input-group-sm" style="width: auto;">
                <span class="input-group-text bg-light border-end-0"><i class='bx bx-calendar'></i></span>
                <input type="text" class="form-control border-start-0 selectorLeave" id="leave_end_date" placeholder="วันสิ้นสุด" style="width: 110px;"
                    value="<?= date('d/m/') . (date('Y') + 543) ?>">
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="btnLoadAttendance">
                <i class='bx bx-search me-1'></i> ค้นหา
            </button>
            <a href="<?= base_url('Admin/SaveAttendance') ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                <i class='bx bx-link-external me-1'></i> Attendance
            </a>
        </div>
    </div>

    <!-- Date Range Display -->
    <div class="mb-3">
        <span class="date-range-display" id="date-range-display">
            <i class='bx bx-calendar-check me-1'></i> 
            สรุปข้อมูล: <span id="display-start"><?= date('01/01/') . (date('Y') + 543) ?></span> - <span id="display-end"><?= date('d/m/') . (date('Y') + 543) ?></span>
        </span>
    </div>

    <!-- Leave Summary Stats -->
    <div class="row g-3 mb-4" id="leave-summary-stats">
        <div class="col-6 col-md-2">
            <div class="leave-stat-card present">
                <div class="stat-value" id="stat-present">-</div>
                <div class="stat-label">มาปกติ</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="leave-stat-card sick">
                <div class="stat-value" id="stat-sick">-</div>
                <div class="stat-label">ลาป่วย</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="leave-stat-card personal">
                <div class="stat-value" id="stat-personal">-</div>
                <div class="stat-label">ลากิจ</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="leave-stat-card official">
                <div class="stat-value" id="stat-official">-</div>
                <div class="stat-label">ไปราชการ</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="leave-stat-card absent">
                <div class="stat-value" id="stat-absent">-</div>
                <div class="stat-label">ขาด</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="leave-stat-card other">
                <div class="stat-value" id="stat-other">-</div>
                <div class="stat-label">อื่นๆ</div>
            </div>
        </div>
    </div>
    
    <div class="table-responsive rounded-4 border overflow-hidden">
        <table class="table table-hover mb-0" id="leaveTable">
            <thead class="bg-light">
                <tr class="text-uppercase small fw-bold">
                    <th class="ps-3 py-3">วันที่</th>
                    <th class="py-3">ประเภท</th>
                    <th class="py-3">หมายเหตุ/เหตุผล</th>
                </tr>
            </thead>
            <tbody>
                <!-- Load via AJAX from Attendance System -->
            </tbody>
        </table>
    </div>
    <div class="text-muted small mt-2">
        <i class='bx bx-info-circle me-1'></i> ข้อมูลดึงจากระบบบันทึกการมาทำงาน (Attendance) โดยอัตโนมัติ
    </div>
</div>

<script>
// Initialize Flatpickr for Leave Date Range
document.addEventListener('DOMContentLoaded', function() {
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".selectorLeave", {
            dateFormat: "d/m/Y",
            locale: "th",
            allowInput: true
        });
    }
});
</script>
