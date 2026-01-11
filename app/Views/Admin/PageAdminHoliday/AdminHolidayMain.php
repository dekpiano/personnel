<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --header-holiday-gradient: linear-gradient(135deg, #0267C1 0%, #009DFF 100%);
        --card-border-radius: 20px;
    }

    .holiday-header {
        background: var(--header-holiday-gradient);
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

    .btn-circle {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="holiday-header d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1 fw-bold text-white"><i class="bi bi-calendar-x me-2"></i><?= $title ?></h3>
        <p class="mb-0 opacity-75">จัดการวันหยุดราชการเพื่อนำไปใช้คำนวณวันลาอัตโนมัติ</p>
    </div>
    <button class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow" id="btnAddHoliday">
        <i class="bi bi-plus-lg me-1"></i> เพิ่มวันหยุด
    </button>
</div>

<div class="glass-card">
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th>วันที่</th>
                    <th>ชื่อวันหยุด</th>
                    <th width="150" class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($holidays as $h): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td class="fw-bold"><?= date('d/m/Y', strtotime($h['holiday_date'])) ?></td>
                    <td><?= $h['holiday_name'] ?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-primary btn-circle shadow-sm btn-edit" data-json='<?= json_encode($h) ?>' title="แก้ไข">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-circle shadow-sm btn-delete" data-id="<?= $h['holiday_id'] ?>" title="ลบ">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal เพิ่ม/แก้ไข -->
<div class="modal fade" id="modalHoliday" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalTitle">เพิ่มวันหยุด</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHoliday">
                <div class="modal-body pb-4">
                    <input type="hidden" name="holiday_id" id="holiday_id">
                    <div class="row g-4 mt-1">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="holiday_date" id="holiday_date" required>
                                <label><i class="bi bi-calendar-event me-1"></i> วันที่หยุด</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="holiday_name" id="holiday_name" placeholder="วันสงกรานต์" required>
                                <label><i class="bi bi-type me-1"></i> ชื่อวันหยุด</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-save me-2"></i> บันทึกข้อมูล
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
        order: [[1, 'desc']]
    });

    $('#btnAddHoliday').click(function() {
        $('#modalTitle').text('เพิ่มวันหยุด');
        $('#formHoliday')[0].reset();
        $('#holiday_id').val('');
        $('#modalHoliday').modal('show');
    });

    $(document).on('click', '.btn-edit', function() {
        const data = $(this).data('json');
        $('#modalTitle').text('แก้ไขวันหยุด');
        $('#holiday_id').val(data.holiday_id);
        $('#holiday_date').val(data.holiday_date);
        $('#holiday_name').val(data.holiday_name);
        $('#modalHoliday').modal('show');
    });

    $('#formHoliday').submit(function(e) {
        e.preventDefault();
        $.post('<?= base_url('Admin/Holiday/Save') ?>', $(this).serialize(), function(res) {
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false })
            .then(() => location.reload());
        });
    });

    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบข้อมูล'
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
