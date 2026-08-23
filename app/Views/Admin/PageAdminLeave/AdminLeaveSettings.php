<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --leave-primary: #0284c7;
        --leave-gradient: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #0369a1 100%);
        --card-radius: 18px;
    }

    .modern-card, .mobile-setting-card, .modal-content {
        color: #0f172a !important;
    }

    .settings-hero-header {
        background: var(--leave-gradient);
        border-radius: var(--card-radius);
        padding: 2.25rem 2.25rem;
        color: #ffffff;
        margin-bottom: 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
        position: relative;
        overflow: hidden;
    }

    .settings-hero-header::before {
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

    /* High contrast icon badges */
    .icon-badge-setting {
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

    .mobile-setting-card {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.85rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    /* Mobile specific style for settings items */
    .mobile-settings-list {
        display: none;
    }

    @media (max-width: 991.98px) {
        .desktop-settings-table {
            display: none;
        }
        .mobile-settings-list {
            display: block;
            padding: 1rem;
        }
        .settings-hero-header {
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
<div class="settings-hero-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="hero-top-pill mb-2">
                <i class="bi bi-gear-fill text-info"></i>
                <span>การตั้งค่าระบบการลา</span>
            </div>
            <h2 class="text-white mb-1 fw-bold fs-3">
                <i class="bi bi-sliders2-vertical text-warning me-2"></i><?= $title ?>
            </h2>
            <p class="text-white text-opacity-90 mb-0 small font-weight-500">
                กำหนดประเภทการลา โควตาประจำปี และช่วงปีการศึกษา/ปีงบประมาณสำหรับคำนวณวันลาสะสม
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('Admin/Leave') ?>" class="btn btn-outline-light rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-arrow-left"></i>
                <span>กลับหน้ารายการคำขอ</span>
            </a>
            <a href="<?= base_url('Admin/Holiday') ?>" class="btn btn-warning fw-bold text-dark rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-calendar-event text-dark"></i>
                <span>จัดการวันหยุด</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- 1. ประเภทการลา -->
    <div class="col-lg-12">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-badge-header">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark fs-5">รายการประเภทการลา</h5>
                        <small class="text-muted fw-semibold">กำหนดสิทธิโควตาวันลาและสถานะเปิด/ปิดใช้งาน</small>
                    </div>
                </div>
                <button class="btn btn-primary rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2 fw-bold" id="btnAddType">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>เพิ่มประเภทการลา</span>
                </button>
            </div>

            <!-- Desktop Table -->
            <div class="desktop-settings-table table-responsive">
                <table class="table align-middle custom-table mb-0">
                    <thead>
                        <tr>
                            <th width="70" class="ps-4">#</th>
                            <th>ชื่อประเภทการลา</th>
                            <th class="text-center">โควตาที่ได้รับ (วัน/ปี)</th>
                            <th class="text-center">สถานะใช้งาน</th>
                            <th width="140" class="text-end pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($leaveTypes as $type): ?>
                        <tr>
                            <td class="ps-4 text-dark fw-bold"><?= $i++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-badge-setting">
                                        <i class="bi bi-bookmark-fill"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark fs-6"><?= $type['leave_type_name'] ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border border-secondary border-opacity-50 px-3 py-2 rounded-pill fw-bold fs-6">
                                    <i class="bi bi-calendar-check text-primary me-1"></i><?= floatval($type['leave_type_quota']) ?> วัน
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($type['leave_type_status'] == 'active'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold" style="color: #047857 !important;">
                                        <i class="bi bi-check-circle-fill me-1"></i> ใช้งานอยู่
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold" style="color: #b91c1c !important;">
                                        <i class="bi bi-x-circle-fill me-1"></i> ระงับการใช้
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-primary btn-circle shadow-sm btn-edit-type fw-bold" data-json='<?= json_encode($type) ?>' title="แก้ไข">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-circle shadow-sm btn-delete-type fw-bold" data-id="<?= $type['leave_type_id'] ?>" title="ลบ">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($leaveTypes)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-dark fw-bold">ยังไม่มีข้อมูลประเภทการลา</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile List View -->
            <div class="mobile-settings-list">
                <?php foreach($leaveTypes as $type): ?>
                <div class="mobile-setting-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-badge-setting" style="width: 36px; height: 36px; font-size: 1.1rem;">
                                <i class="bi bi-bookmark-fill"></i>
                            </div>
                            <div class="fw-bold text-dark fs-6"><?= $type['leave_type_name'] ?></div>
                        </div>
                        <?php if($type['leave_type_status'] == 'active'): ?>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1 small fw-bold" style="color: #047857 !important;">
                                ใช้งาน
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1 small fw-bold" style="color: #b91c1c !important;">
                                ระงับ
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div class="small text-dark fw-bold">
                            โควตา: <span class="text-primary fs-6"><?= floatval($type['leave_type_quota']) ?></span> วัน/ปี
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-edit-type fw-bold" data-json='<?= json_encode($type) ?>'>
                                <i class="bi bi-pencil-fill me-1"></i> แก้ไข
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-delete-type fw-bold" data-id="<?= $type['leave_type_id'] ?>">
                                <i class="bi bi-trash-fill me-1"></i> ลบ
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับประเภทการลา -->
<div class="modal fade" id="modalType" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-3 p-md-4">
                <h5 class="modal-title fw-bold text-white mb-0" id="titleType">
                    <i class="bi bi-tag-fill text-info me-2"></i>เพิ่มประเภทการลา
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formType">
                <div class="modal-body p-4">
                    <input type="hidden" name="leave_type_id" id="leave_type_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control text-dark border" name="leave_type_name" id="leave_type_name" placeholder="ชื่อประเภทการลา" required>
                                <label><i class="bi bi-tag me-1 text-primary"></i> ชื่อประเภทการลา (เช่น ลาป่วย, ลากิจ)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control text-dark border" name="leave_type_quota" id="leave_type_quota" placeholder="30" min="0" step="0.5" required>
                                <label><i class="bi bi-calculator me-1 text-primary"></i> โควตา (วัน/ปี)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select text-dark border" name="leave_type_status" id="leave_type_status">
                                    <option value="active">เปิดใช้งาน</option>
                                    <option value="inactive">ระงับการใช้</option>
                                </select>
                                <label><i class="bi bi-toggle-on me-1 text-primary"></i> สถานะ</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-3 p-md-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow fw-bold">
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
    // --- จัดการประเภทการลา ---
    $('#btnAddType').click(function() {
        $('#leave_type_id').val('');
        $('#formType')[0].reset();
        $('#titleType').html('<i class="bi bi-tag-fill text-info me-2"></i>เพิ่มประเภทการลา');
        $('#modalType').modal('show');
    });

    $(document).on('click', '.btn-edit-type', function() {
        const data = $(this).data('json');
        $('#leave_type_id').val(data.leave_type_id);
        $('#leave_type_name').val(data.leave_type_name);
        $('#leave_type_quota').val(data.leave_type_quota);
        $('#leave_type_status').val(data.leave_type_status);
        $('#titleType').html('<i class="bi bi-pencil-square text-info me-2"></i>แก้ไขประเภทการลา');
        $('#modalType').modal('show');
    });

    $('#formType').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= base_url('Admin/Leave/SaveType') ?>', $(this).serialize(), function(res) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1400, showConfirmButton: false })
            .then(() => location.reload());
        });
    });

    $(document).on('click', '.btn-delete-type', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบประเภทการลานี้?',
            text: "หากลบแล้วข้อมูลรายการลาในอดีตที่เชื่อมโยงอาจแสดงผลไม่สมบูรณ์",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ยืนยันลบข้อมูล',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('Admin/Leave/DeleteType/') ?>' + id, function(res) {
                    location.reload();
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>


