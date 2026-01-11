<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --header-settings-gradient: linear-gradient(135deg, #0267C1 0%, #009DFF 100%);
        --card-border-radius: 20px;
    }

    .settings-header {
        background: var(--header-settings-gradient);
        border-radius: var(--card-border-radius);
        padding: 2rem 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(2, 103, 193, 0.2);
    }

    .glass-card {
        background: white;
        border-radius: var(--card-border-radius);
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table thead th {
        background: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #6c757d;
        border-bottom: none;
    }

    .bg-label-primary-blue {
        background-color: #e7f3ff;
        color: #0267C1;
    }

    /* New styles from instruction */
    .bg-label-secondary {
        background-color: #e2e6ea;
    }
    .bg-label-success {
        background-color: #d4edda;
    }
    .bg-label-danger {
        background-color: #f8d7da;
    }
    .btn-circle {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="settings-header d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1 fw-bold text-white"><i class="bi bi-gear-wide-connected me-2"></i><?= $title ?></h3>
        <p class="mb-0 opacity-75">กำหนดประเภทการลาและจำนวนวันที่ลาได้ต่อปี</p>
    </div>
    <!-- The add button for leave type is now inside the card header -->
</div>

<div class="row g-4 mt-1">
    <!-- ประเภทการลา -->
    <div class="col-lg-12">
        <div class="glass-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-stars me-2 text-primary"></i>รายการประเภทการลา</h5>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnAddType">
                    <i class="bi bi-plus-lg me-1"></i> เพิ่มประเภทการลา
                </button>
            </div>
            <div class="table-responsive px-4 pb-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>ชื่อประเภทการลา</th>
                            <th class="text-center">โควตา (วัน/ปี)</th>
                            <th class="text-center">สถานะ</th>
                            <th width="120" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($leaveTypes as $type): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td class="fw-bold fs-6 text-primary"><?= $type['leave_type_name'] ?></td>
                            <td class="text-center">
                                <span class="badge bg-label-secondary text-dark rounded-pill px-3">
                                    <?= $type['leave_type_quota'] ?> วัน
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($type['leave_type_status'] == 'active'): ?>
                                    <span class="badge bg-label-success text-success"><i class="bi bi-check-circle me-1"></i> ใช้งาน</span>
                                <?php else: ?>
                                    <span class="badge bg-label-danger text-danger"><i class="bi bi-x-circle me-1"></i> ระงับ</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-primary rounded-circle shadow-sm btn-edit-type" style="width: 32px; height: 32px; padding: 0;" data-json='<?= json_encode($type) ?>' title="แก้ไข">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger rounded-circle shadow-sm btn-delete-type" style="width: 32px; height: 32px; padding: 0;" data-id="<?= $type['leave_type_id'] ?>" title="ลบ">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($leaveTypes)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">ยังไม่มีข้อมูลประเภทการลา</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ปีการศึกษา สำหรับสรุปวันลา -->
    <div class="col-lg-12">
        <div class="glass-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4 text-white" style="background: var(--header-gradient);">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-calendar-range me-2"></i>ช่วงปีการศึกษาที่สรุปวันลา</h5>
                <button class="btn btn-light text-primary rounded-pill px-4 shadow-sm fw-bold" id="btnAddYear">
                    <i class="bi bi-plus-lg me-1"></i> เพิ่มช่วงปีการศึกษา
                </button>
            </div>
            <div class="table-responsive px-4 pb-4 mt-3">
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                    <div>
                        โควตาการลาจะถูกนับสะสมเฉพาะภายใน <strong>ช่วงวันที่</strong> ของปีการศึกษาที่ถูกกำหนดให้เป็น <strong>"ใช้งานอยู่"</strong> เท่านั้น
                    </div>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>ชื่อปีการศึกษา</th>
                            <th class="text-center">เริ่มนับ</th>
                            <th class="text-center">สิ้นสุด</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th width="150" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j=1; foreach($leaveYears as $year): ?>
                        <tr class="<?= $year['ly_status'] == 'active' ? 'table-primary border-primary' : '' ?>">
                            <td><?= $j++ ?></td>
                            <td class="fw-bold"><?= $year['ly_name'] ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($year['ly_start_date'])) ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($year['ly_end_date'])) ?></td>
                            <td class="text-center">
                                <?php if($year['ly_status'] == 'active'): ?>
                                    <div class="badge bg-primary rounded-pill px-3 shadow-sm border border-white">
                                        <i class="bi bi-star-fill me-1"></i> ใช้งานปีนี้
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-set-active" data-id="<?= $year['ly_id'] ?>">
                                        สลับมาใช้ปีนี้
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-light border btn-circle btn-edit-year" data-json='<?= json_encode($year) ?>' title="แก้ไข">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>
                                    <?php if($year['ly_status'] != 'active'): ?>
                                    <button class="btn btn-sm btn-light border btn-circle btn-delete-year" data-id="<?= $year['ly_id'] ?>" title="ลบ">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($leaveYears)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">ยังไม่มีข้อมูลปีการศึกษา</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับประเภทการลา -->
<div class="modal fade" id="modalType" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold" id="titleType">เพิ่มประเภทการลา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formType">
                <div class="modal-body p-4">
                    <input type="hidden" name="leave_type_id" id="leave_type_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-primary" name="leave_type_name" id="leave_type_name" placeholder="ชื่อประเภทการลา" required>
                                <label><i class="bi bi-tag-fill me-1"></i> ชื่อประเภทการลา</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control border-primary" name="leave_type_quota" id="leave_type_quota" placeholder="30" required>
                                <label><i class="bi bi-calculator-fill me-1"></i> โควตา (วัน/ปี)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select border-primary" name="leave_type_status" id="leave_type_status">
                                    <option value="active">เปิดใช้งาน</option>
                                    <option value="inactive">ระงับการใช้</option>
                                </select>
                                <label><i class="bi bi-toggle2-on me-1"></i> สถานะ</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">
                        <i class="bi bi-save me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal สำหรับปีการศึกษา -->
<div class="modal fade" id="modalYear" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold" id="titleYear">เพิ่มช่วงปีการศึกษา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formYear">
                <div class="modal-body p-4">
                    <input type="hidden" name="ly_id" id="ly_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border-primary" name="ly_name" id="ly_name" placeholder="ปีการศึกษา 2568" required>
                                <label><i class="bi bi-hash me-1 text-primary"></i> ชื่อช่วงปีการศึกษา (เช่น 2568)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control border-primary" name="ly_start_date" id="ly_start_date" required>
                                <label><i class="bi bi-calendar-check me-1 text-success"></i> วันที่เริ่มนับ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control border-primary" name="ly_end_date" id="ly_end_date" required>
                                <label><i class="bi bi-calendar-x me-1 text-danger"></i> วันที่สิ้นสุด</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">
                        <i class="bi bi-save me-1"></i> บันทึกข้อมูล
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
        $('#titleType').text('เพิ่มประเภทการลา');
        $('#modalType').modal('show');
    });

    $(document).on('click', '.btn-edit-type', function() {
        const data = $(this).data('json');
        $('#leave_type_id').val(data.leave_type_id);
        $('#leave_type_name').val(data.leave_type_name);
        $('#leave_type_quota').val(data.leave_type_quota);
        $('#leave_type_status').val(data.leave_type_status);
        $('#titleType').text('แก้ไขประเภทการลา');
        $('#modalType').modal('show');
    });

    $('#formType').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= base_url('Admin/Leave/SaveType') ?>', $(this).serialize(), function(res) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
        });
    });

    $(document).on('click', '.btn-delete-type', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "หากลบแล้วข้อมูลรายการลาที่เกี่ยวข้องอาจมีปัญหา",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('Admin/Leave/DeleteType/') ?>' + id, function(res) {
                    location.reload();
                });
            }
        });
    });

    // --- จัดการช่วงปีการศึกษา ---
    $('#btnAddYear').click(function() {
        $('#ly_id').val('');
        $('#formYear')[0].reset();
        $('#titleYear').text('เพิ่มช่วงปีการศึกษา');
        $('#modalYear').modal('show');
    });

    $(document).on('click', '.btn-edit-year', function() {
        const data = $(this).data('json');
        $('#ly_id').val(data.ly_id);
        $('#ly_name').val(data.ly_name);
        $('#ly_start_date').val(data.ly_start_date);
        $('#ly_end_date').val(data.ly_end_date);
        $('#titleYear').text('แก้ไขช่วงปีการศึกษา');
        $('#modalYear').modal('show');
    });

    $('#formYear').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= base_url('Admin/Leave/SaveYear') ?>', $(this).serialize(), function(res) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
        });
    });

    $(document).on('click', '.btn-set-active', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'เปลี่ยนปีการศึกษาที่สรุปผล?',
            text: "ระบบจะสรุปยอดวันลาสะสมใหม่ตามช่วงวันที่ของปีการศึกษานี้",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ยืนยันเปลี่ยน'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('Admin/Leave/SetActiveYear/') ?>' + id, function(res) {
                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1000, showConfirmButton: false })
                    .then(() => location.reload());
                });
            }
        });
    });

    $(document).on('click', '.btn-delete-year', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('Admin/Leave/DeleteYear/') ?>' + id, function(res) {
                    location.reload();
                }).fail(function(err) {
                    Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: err.responseJSON.message });
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
