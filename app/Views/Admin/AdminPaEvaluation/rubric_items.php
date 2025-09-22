<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการประเมิน PA /</span> จัดการหัวข้อการประเมิน</h4>

<!-- Table Card -->
<div class="card p-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">รายการหัวข้อการประเมิน</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRubricItemModal">
            <i class="bx bx-plus me-1"></i> เพิ่มหัวข้อใหม่
        </button>
    </div>
    <div class="table-responsive text-nowrap">
        <?php if (session()->getFlashdata('Success')): ?>
        <div class="alert alert-success mx-3" role="alert">
            <?= session()->getFlashdata('Success'); ?>
        </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('Error')): ?>
        <div class="alert alert-danger mx-3" role="alert">
            <?= session()->getFlashdata('Error'); ?>
        </div>
        <?php endif; ?>
        <table id="rubrics-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ส่วนที่</th>
                    <th>หมวดหมู่</th>
                    <th>ลำดับ</th>
                    <th>รายละเอียดลักษณะงาน / ระดับที่คาดหวัง</th>
                    <th>วิทยฐานะ</th>
                    <th>ตำแหน่ง</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rubrics)):
                    foreach ($rubrics as $item): ?>
                <tr>
                    <td><?= esc($item['ri_part']); ?></td>
                    <td><?= esc($item['ri_domain']); ?></td>
                    <td><?= esc($item['ri_item_number']); ?></td>
                    <td style="white-space: normal;">
                        <?= esc($item['ri_item_description']); ?>
                        <?php if (!empty($item['ri_expected_level_description'])): ?>
                        <div class="mt-2 text-muted small">
                            <strong>ระดับที่คาดหวัง:</strong> <?= esc($item['ri_expected_level_description']); ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($item['ri_academic_standing'] ?? 'ทั่วไป'); ?></td>
                    <td><?= esc($item['ri_position'] ?? 'ทั้งหมด'); ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm edit-rubric-btn" data-bs-toggle="modal"
                            data-bs-target="#addRubricItemModal" data-id="<?= esc($item['ri_id']); ?>"
                            data-part="<?= esc($item['ri_part']); ?>" data-domain="<?= esc($item['ri_domain']); ?>"
                            data-item_number="<?= esc($item['ri_item_number']); ?>"
                            data-item_description="<?= esc($item['ri_item_description']); ?>"
                            data-expected_level_description="<?= esc($item['ri_expected_level_description']); ?>"
                            data-academic_standing="<?= esc($item['ri_academic_standing']); ?>"
                            data-position="<?= esc($item['ri_position'] ?? ''); ?>">
                            แก้ไข
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-rubric-btn"
                            data-id="<?= esc($item['ri_id']); ?>">ลบ</button>
                    </td>
                </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="7" class="text-center">ยังไม่มีหัวข้อการประเมิน</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Rubric Item Modal -->
<div class="modal fade" id="addRubricItemModal" tabindex="-1" aria-labelledby="addRubricItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRubricItemModalLabel">เพิ่มหัวข้อการประเมินใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRubricForm" action="<?= base_url('Admin/PaConfig/addRubricItem'); ?>" method="POST">
                    <input type="hidden" id="ri_id" name="ri_id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ri_position" class="form-label">สำหรับตำแหน่ง</label>
                                <select class="form-select" id="ri_position" name="ri_position">
                                    <option value="">ทั้งหมด</option>
                                    <option value="ครู">ครู</option>
                                    <option value="ผู้อำนวยการ">ผู้อำนวยการ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ri_academic_standing" class="form-label">สำหรับวิทยฐานะ
                                    (หากไม่เลือกจะถือว่าเป็นหัวข้อทั่วไป)</label>
                                <select class="form-select" id="ri_academic_standing" name="ri_academic_standing">
                                    <option value="">ทั่วไป (สำหรับทุกวิทยฐานะ)</option>
                                    <option value="ไม่มีวิทยฐานะ">ไม่มีวิทยฐานะ</option>
                                    <option value="ชำนาญการ">ชำนาญการ</option>
                                    <option value="ชำนาญการพิเศษ">ชำนาญการพิเศษ</option>
                                    <option value="เชี่ยวชาญ">เชี่ยวชาญ</option>
                                    <option value="เชี่ยวชาญพิเศษ">เชี่ยวชาญพิเศษ</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="ri_part" class="form-label">ส่วนของการประเมิน (1 หรือ 2)</label>
                            <input type="number" class="form-control" id="ri_part" name="ri_part" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ri_domain" class="form-label">หมวดหมู่ของงาน (เช่น
                                ด้านการจัดการเรียนรู้)</label>
                            <input type="text" class="form-control" id="ri_domain" name="ri_domain" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ri_item_number" class="form-label">ลำดับหัวข้อ (เช่น 1.1, 2.3)</label>
                            <input type="text" class="form-control" id="ri_item_number" name="ri_item_number" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ri_item_description" class="form-label">รายละเอียดลักษณะงานที่ปฏิบัติ</label>
                        <textarea class="form-control" id="ri_item_description" name="ri_item_description" rows="3"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="ri_expected_level_description"
                            class="form-label">คำอธิบายระดับการปฏิบัติที่คาดหวัง</label>
                        <textarea class="form-control" id="ri_expected_level_description"
                            name="ri_expected_level_description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" form="addRubricForm" class="btn btn-primary">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#rubrics-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
        }
    });

    // Function to reset the form
    function resetRubricForm() {
        $('#addRubricForm')[0].reset();
        $('#ri_id').val('');
        $('#addRubricItemModalLabel').text('เพิ่มหัวข้อการประเมินใหม่');
        $('#addRubricForm').attr('action', '<?= base_url('Admin/PaConfig/addRubricItem'); ?>');
        $('#addRubricForm input, #addRubricForm textarea, #ri_academic_standing, #ri_position').removeClass(
            'is-invalid');
    }

    // When the "Add New Rubric Item" button is clicked
    $('button[data-bs-target="#addRubricItemModal"]').not('.edit-rubric-btn').on('click', function() {
        resetRubricForm();
    });

    // When an "Edit" button is clicked
    $('.edit-rubric-btn').on('click', function() {
        const button = $(this);
        $('#addRubricItemModalLabel').text('แก้ไขหัวข้อการประเมิน');
        $('#addRubricForm').attr('action',
            '<?= base_url('Admin/PaConfig/updateRubricItem'); ?>'); // Assuming an update endpoint

        $('#ri_id').val(button.data('id'));
        $('#ri_part').val(button.data('part'));
        $('#ri_domain').val(button.data('domain'));
        $('#ri_item_number').val(button.data('item_number'));
        $('#ri_item_description').val(button.data('item_description'));
        $('#ri_expected_level_description').val(button.data('expected_level_description'));
        $('#ri_academic_standing').val(button.data('academic_standing'));
        $('#ri_position').val(button.data('position'));
    });

    // Form submission validation and AJAX submission
    $('#addRubricForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let isValid = true;
        const requiredFields = [
            'ri_part',
            'ri_domain',
            'ri_item_number',
            'ri_item_description'
        ];

        requiredFields.forEach(function(fieldId) {
            const input = $('#' + fieldId);
            if (input.val().trim() === '') {
                input.addClass('is-invalid'); // Add Bootstrap validation class
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด!',
                text: 'กรุณากรอกข้อมูลให้ครบถ้วนในช่องที่จำเป็น',
                confirmButtonText: 'ตกลง'
            });
            return; // Stop execution if validation fails
        }

        // If validation passes, proceed with AJAX submission
        const formData = $(this).serialize(); // Serialize form data for AJAX
        const formAction = $(this).attr('action'); // Get the form action URL

        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            dataType: 'json', // Expect JSON response from the server
            beforeSend: function() {
                // Optional: Show a loading indicator
                Swal.fire({
                    title: 'กำลังบันทึก...',
                    text: 'กรุณารอสักครู่',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.close(); // Close loading indicator

                if (response.success) {
                    $('#addRubricItemModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: response.message,
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optional: Close modal and refresh page or update table

                            location.reload(); // Reload the page to show new data
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด!',
                        text: response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                        confirmButtonText: 'ตกลง'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close(); // Close loading indicator
                $('#addRubricItemModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด!',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้: ' + error,
                    confirmButtonText: 'ตกลง'
                });
                console.error("AJAX Error: ", status, error, xhr);
            }
        });
    });

    // Remove validation class on input change
    $('#addRubricForm input, #addRubricForm textarea, #ri_academic_standing, #ri_position').on('change input',
        function() {
            if ($(this).val().trim() !== '') {
                $(this).removeClass('is-invalid');
            }
        });

    // Handle delete button click
    $('.delete-rubric-btn').on('click', function() {
        const riId = $(this).data('id');

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบหัวข้อการประเมินนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('Admin/PaConfig/deleteRubricItem'); ?>',
                    type: 'POST',
                    data: {
                        ri_id: riId
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'กำลังลบ...',
                            text: 'กรุณารอสักครู่',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: response.message,
                                confirmButtonText: 'ตกลง'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'ข้อผิดพลาด!',
                                text: response.message ||
                                    'เกิดข้อผิดพลาดในการลบข้อมูล',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'ข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้: ' +
                                error,
                            confirmButtonText: 'ตกลง'
                        });
                        console.error("AJAX Error: ", status, error, xhr);
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>