<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --leave-primary: #0284c7;
        --holiday-gradient: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #0369a1 100%);
        --card-radius: 18px;
    }

    .modern-card, .mobile-holiday-card, .modal-content {
        color: #0f172a !important;
    }

    .holiday-hero-header {
        background: var(--holiday-gradient);
        border-radius: var(--card-radius);
        padding: 2.25rem 2.25rem;
        color: #ffffff;
        margin-bottom: 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        position: relative;
        overflow: hidden;
    }

    .holiday-hero-header::before {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.3) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .modern-card {
        background: #ffffff;
        border-radius: var(--card-radius);
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 1.75rem;
    }

    .modern-card-header {
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .custom-table thead th {
        background: #f1f5f9;
        color: #0f172a !important;
        font-size: 0.88rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #cbd5e1;
        padding: 1.1rem 1.25rem;
    }

    .custom-table tbody td {
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        color: #0f172a !important;
    }

    /* High contrast icon boxes */
    .icon-badge-holiday {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1.5px solid #bae6fd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .icon-badge-header {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #0284c7;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        flex-shrink: 0;
    }

    .btn-circle {
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        font-size: 0.95rem;
    }
    .btn-circle:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .mobile-holiday-card {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.85rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    /* Mobile view for holidays */
    .mobile-holiday-list {
        display: none;
    }

    @media (max-width: 991.98px) {
        .desktop-holiday-table {
            display: none;
        }
        .mobile-holiday-list {
            display: block;
            padding: 1rem;
        }
        .holiday-hero-header {
            padding: 1.5rem;
        }
    }

    /* Hero Subtitle Pill */
    .hero-top-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(15, 23, 42, 0.7) !important;
        color: #38bdf8 !important;
        border: 1.5px solid #38bdf8 !important;
        padding: 0.4rem 1rem;
        border-radius: 50rem;
        font-size: 0.85rem;
        font-weight: 800;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .hero-top-pill span {
        color: #ffffff !important;
    }
</style>

<!-- Hero Section -->
<div class="holiday-hero-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="hero-top-pill mb-2">
                <i class="bi bi-calendar2-range text-info"></i>
                <span>การตั้งค่าระบบการลา</span>
            </div>
            <h2 class="text-white mb-1 fw-bold fs-3">
                <i class="bi bi-calendar-event-fill text-warning me-2"></i><?= $title ?>
            </h2>
            <p class="text-white text-opacity-90 mb-0 small font-weight-500">
                กำหนดวันหยุดนักขัตฤกษ์และวันหยุดพิเศษ เพื่อนำไปหักลบการนับวันลาอัตโนมัติ
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('Admin/Leave') ?>" class="btn btn-outline-light rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-arrow-left"></i>
                <span>กลับหน้ารายการลา</span>
            </a>
            <button class="btn btn-warning fw-bold text-dark rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" id="btnAddHoliday">
                <i class="bi bi-plus-circle-fill text-dark"></i>
                <span>เพิ่มวันหยุด</span>
            </button>
        </div>
    </div>
</div>

<div class="modern-card">
    <div class="modern-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-badge-header">
                <i class="bi bi-calendar-week-fill"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold text-dark fs-5">ปฏิทินวันหยุดราชการ</h5>
                <small class="text-muted fw-semibold">รายการวันหยุดที่ถูกบันทึกไว้ในระบบ (ทั้งหมด <?= count($holidays) ?> รายการ)</small>
            </div>
        </div>

        <!-- Year Filter Dropdown -->
        <div class="d-flex align-items-center gap-2">
            <form method="GET" action="<?= base_url('Admin/Holiday') ?>" class="d-flex align-items-center gap-2 m-0" id="formYearFilter">
                <label for="filter_year" class="fw-bold text-dark text-nowrap small mb-0">
                    <i class="bi bi-funnel-fill text-primary me-1"></i>เลือกปี:
                </label>
                <select name="year" id="filter_year" class="form-select form-select-sm fw-bold border-secondary border-opacity-50 text-dark rounded-pill px-3 py-2 shadow-none" style="min-width: 150px;" onchange="this.form.submit()">
                    <?php foreach ($available_years as $yr): ?>
                        <option value="<?= $yr ?>" <?= ($selected_year == $yr) ? 'selected' : '' ?>>
                            ปี ค.ศ. <?= $yr ?> (พ.ศ. <?= $yr + 543 ?>)
                        </option>
                    <?php endforeach; ?>
                    <option value="all" <?= ($selected_year === 'all') ? 'selected' : '' ?>>
                        -- แสดงทุกปี --
                    </option>
                </select>
            </form>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="desktop-holiday-table table-responsive p-0">
        <table class="table align-middle custom-table mb-0 datatable">
            <thead>
                <tr>
                    <th width="70" class="ps-4">#</th>
                    <th width="240">วันที่หยุด</th>
                    <th>ชื่อวันหยุดราชการ / วันหยุดพิเศษ</th>
                    <th width="140" class="text-end pe-4">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($holidays as $h): ?>
                <tr>
                    <td class="ps-4 text-dark fw-bold"><?= $i++ ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-badge-holiday">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark fs-6"><?= thai_date_short($h['holiday_date']) ?></span>
                                <small class="text-secondary d-block fw-semibold"><?= thai_date_full($h['holiday_date']) ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-bold text-dark fs-6"><?= $h['holiday_name'] ?></span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-outline-primary btn-circle shadow-sm btn-edit fw-bold" data-json='<?= json_encode($h) ?>' title="แก้ไข">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-circle shadow-sm btn-delete fw-bold" data-id="<?= $h['holiday_id'] ?>" title="ลบ">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($holidays)): ?>
                    <tr><td colspan="4" class="text-center py-5 text-dark fw-bold">ยังไม่มีข้อมูลวันหยุด</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile List View -->
    <div class="mobile-holiday-list">
        <?php foreach($holidays as $h): ?>
        <div class="mobile-holiday-card">
            <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="icon-badge-holiday" style="width: 38px; height: 38px; font-size: 1.1rem;">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6"><?= $h['holiday_name'] ?></div>
                        <small class="text-secondary fw-semibold"><?= thai_date_full($h['holiday_date']) ?></small>
                    </div>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small fw-bold" style="color: #0369a1 !important;">
                    <?= thai_date_short($h['holiday_date']) ?>
                </span>
            </div>
            <div class="d-flex justify-content-end gap-2 pt-2 mt-2 border-top">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-edit fw-bold" data-json='<?= json_encode($h) ?>'>
                    <i class="bi bi-pencil-fill me-1"></i> แก้ไข
                </button>
                <button class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-delete fw-bold" data-id="<?= $h['holiday_id'] ?>">
                    <i class="bi bi-trash-fill me-1"></i> ลบ
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($holidays)): ?>
            <div class="text-center py-5 text-dark fw-bold">ยังไม่มีข้อมูลวันหยุด</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal เพิ่ม/แก้ไข -->
<div class="modal fade" id="modalHoliday" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-3 p-md-4">
                <h5 class="modal-title fw-bold text-white mb-0" id="modalTitle">
                    <i class="bi bi-calendar-plus text-info me-2"></i>เพิ่มวันหยุด
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHoliday">
                <div class="modal-body p-4">
                    <input type="hidden" name="holiday_id" id="holiday_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control text-dark border bg-white" name="holiday_date" id="holiday_date" placeholder="วว/ดด/ปปปป" required>
                                <label><i class="bi bi-calendar-event me-1 text-primary"></i> วันที่หยุด (พ.ศ.)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control text-dark border" name="holiday_name" id="holiday_name" placeholder="วันสงกรานต์" required>
                                <label><i class="bi bi-type me-1 text-primary"></i> ชื่อวันหยุด (เช่น วันสงกรานต์, วันปีใหม่)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-3 p-md-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow fw-semibold">
                        <i class="bi bi-check-lg me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json' },
        order: [[1, 'asc']]
    });

    const holidayPicker = flatpickr("#holiday_date", {
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d"
    });

    $('#btnAddHoliday').click(function() {
        $('#modalTitle').html('<i class="bi bi-calendar-plus text-info me-2"></i>เพิ่มวันหยุด');
        $('#formHoliday')[0].reset();
        $('#holiday_id').val('');
        if (holidayPicker) holidayPicker.clear();
        $('#modalHoliday').modal('show');
    });

    $(document).on('click', '.btn-edit', function() {
        const data = $(this).data('json');
        $('#modalTitle').html('<i class="bi bi-pencil-square text-info me-2"></i>แก้ไขวันหยุด');
        $('#holiday_id').val(data.holiday_id);
        if (holidayPicker) holidayPicker.setDate(data.holiday_date, true);
        $('#holiday_name').val(data.holiday_name);
        $('#modalHoliday').modal('show');
    });

    $('#formHoliday').submit(function(e) {
        e.preventDefault();
        $.post('<?= base_url('Admin/Holiday/Save') ?>', $(this).serialize(), function(res) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1400, showConfirmButton: false })
            .then(() => location.reload());
        });
    });

    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบวันหยุดนี้?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ลบข้อมูล',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('Admin/Holiday/Delete/') ?>' + id, function(res) {
                    location.reload();
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>


