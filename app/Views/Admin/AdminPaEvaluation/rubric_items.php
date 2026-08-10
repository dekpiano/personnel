<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold m-0"><i class="bx bx-list-check text-primary me-2"></i>จัดการหัวข้อการประเมิน PA</h4>
        <small class="text-muted">กำหนดหัวข้อเกณฑ์การประเมิน คำอธิบาย และระดับที่คาดหวังตามวิทยฐานะ</small>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addRubricItemModal">
            <i class="bx bx-plus me-1"></i> เพิ่มหัวข้อใหม่
        </button>
    </div>
</div>

<!-- Stat Summary Cards -->
<?php
    $total_items = count($rubrics);
    $part1_count = 0;
    $part2_count = 0;
    foreach ($rubrics as $r) {
        if ($r['ri_part'] == 1) $part1_count++;
        if ($r['ri_part'] == 2) $part2_count++;
    }
?>
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">หัวข้อประเมินทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-primary"><?= $total_items; ?> <span class="fs-6 text-muted fw-normal">รายการ</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-task fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">ส่วนที่ 1 (ข้อตกลงในการพัฒนางาน)</span>
                    <h3 class="fw-bold mb-0 text-success"><?= $part1_count; ?> <span class="fs-6 text-muted fw-normal">รายการ</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-book-open fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f3e5f5 0%, #ffffff 100%);">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">ส่วนที่ 2 (ข้อตกลงในการประเมิน)</span>
                    <h3 class="fw-bold mb-0 text-purple" style="color: #8e24aa;"><?= $part2_count; ?> <span class="fs-6 text-muted fw-normal">รายการ</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-purple rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="color: #8e24aa;">
                    <i class="bx bx-award fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold fs-6"><i class="bx bx-table me-2 text-primary"></i>รายการหัวข้อการประเมิน</h5>
        <span class="badge bg-label-primary rounded-pill"><?= $total_items; ?> รายการ</span>
    </div>
    <div class="card-body pt-3">
        <div class="table-responsive">
            <table id="rubrics-table" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">ส่วนที่</th>
                        <th style="width: 160px;">หมวดหมู่</th>
                        <th style="width: 60px;">ลำดับ</th>
                        <th>รายละเอียดลักษณะงาน / ระดับที่คาดหวัง</th>
                        <th style="width: 120px;">วิทยฐานะ</th>
                        <th style="width: 100px;">ตำแหน่ง</th>
                        <th style="width: 90px;" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rubrics)):
                        foreach ($rubrics as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['ri_part'] == 1): ?>
                                <span class="badge bg-label-info"><i class="bx bx-layer me-1"></i>ส่วนที่ 1</span>
                            <?php else: ?>
                                <span class="badge bg-label-primary"><i class="bx bx-layer me-1"></i>ส่วนที่ 2</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="fw-semibold text-dark"><?= esc($item['ri_domain']); ?></span></td>
                        <td><span class="badge bg-light text-dark fw-bold"><?= esc($item['ri_item_number']); ?></span></td>
                        <td style="white-space: normal;">
                            <div class="fw-semibold text-dark mb-1"><?= esc($item['ri_item_description']); ?></div>
                            <?php if (!empty($item['ri_expected_level_description'])): ?>
                            <div class="p-2 bg-light rounded small text-muted border-start border-3 border-primary">
                                <strong><i class="bx bx-target-lock me-1 text-primary"></i>ระดับที่คาดหวัง:</strong> <?= esc($item['ri_expected_level_description']); ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $standing = $item['ri_academic_standing'] ?? 'ทั่วไป';
                                if (empty($standing) || $standing === 'ทั่วไป') {
                                    echo '<span class="badge bg-label-secondary">ทั่วไป</span>';
                                } else {
                                    echo '<span class="badge bg-label-success">' . esc($standing) . '</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-label-warning"><?= esc(!empty($item['ri_position']) ? $item['ri_position'] : 'ทั้งหมด'); ?></span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-icon btn-sm btn-outline-warning shadow-sm edit-rubric-btn" data-bs-toggle="modal"
                                    data-bs-target="#addRubricItemModal" data-id="<?= esc($item['ri_id']); ?>"
                                    data-part="<?= esc($item['ri_part']); ?>" data-domain="<?= esc($item['ri_domain']); ?>"
                                    data-item_number="<?= esc($item['ri_item_number']); ?>"
                                    data-item_description="<?= esc($item['ri_item_description']); ?>"
                                    data-expected_level_description="<?= esc($item['ri_expected_level_description']); ?>"
                                    data-academic_standing="<?= esc($item['ri_academic_standing']); ?>"
                                    data-position="<?= esc($item['ri_position'] ?? ''); ?>" title="แก้ไข">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger shadow-sm delete-rubric-btn"
                                    data-id="<?= esc($item['ri_id']); ?>" title="ลบ">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">ยังไม่มีหัวข้อการประเมิน</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Rubric Item Modal -->
<div class="modal fade" id="addRubricItemModal" tabindex="-1" aria-labelledby="addRubricItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="addRubricItemModalLabel"><i class="bx bx-plus-circle me-1 text-primary"></i>เพิ่มหัวข้อการประเมินใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRubricForm" action="<?= base_url('Admin/PaConfig/addRubricItem'); ?>" method="POST">
                    <input type="hidden" id="ri_id" name="ri_id" value="">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ri_position" class="form-label fw-semibold small">สำหรับตำแหน่ง</label>
                            <select class="form-select select2-modal" id="ri_position" name="ri_position">
                                <option value="">ทั้งหมด</option>
                                <option value="ครู">ครู</option>
                                <option value="ผู้อำนวยการ">ผู้อำนวยการ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ri_academic_standing" class="form-label fw-semibold small">สำหรับวิทยฐานะ</label>
                            <select class="form-select select2-modal" id="ri_academic_standing" name="ri_academic_standing">
                                <option value="">ทั่วไป (สำหรับทุกวิทยฐานะ)</option>
                                <option value="ไม่มีวิทยฐานะ">ไม่มีวิทยฐานะ</option>
                                <option value="ชำนาญการ">ชำนาญการ</option>
                                <option value="ชำนาญการพิเศษ">ชำนาญการพิเศษ</option>
                                <option value="เชี่ยวชาญ">เชี่ยวชาญ</option>
                                <option value="เชี่ยวชาญพิเศษ">เชี่ยวชาญพิเศษ</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="ri_part" class="form-label fw-semibold small">ส่วนของการประเมิน (1 หรือ 2)</label>
                            <input type="number" class="form-control shadow-none" id="ri_part" name="ri_part" min="1" max="2" placeholder="1 หรือ 2" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ri_domain" class="form-label fw-semibold small">หมวดหมู่ของงาน</label>
                            <input type="text" class="form-control shadow-none" id="ri_domain" name="ri_domain" placeholder="เช่น ด้านการจัดการเรียนรู้" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ri_item_number" class="form-label fw-semibold small">ลำดับหัวข้อ</label>
                            <input type="text" class="form-control shadow-none" id="ri_item_number" name="ri_item_number" placeholder="เช่น 1.1, 2.3" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ri_item_description" class="form-label fw-semibold small">รายละเอียดลักษณะงานที่ปฏิบัติ</label>
                        <textarea class="form-control shadow-none" id="ri_item_description" name="ri_item_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="ri_expected_level_description" class="form-label fw-semibold small">คำอธิบายระดับการปฏิบัติที่คาดหวัง</label>
                        <textarea class="form-control shadow-none" id="ri_expected_level_description" name="ri_expected_level_description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top p-3">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" form="addRubricForm" class="btn btn-primary"><i class="bx bx-save me-1"></i>บันทึก</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize Select2 in Modal
    $('.select2-modal').select2({
        dropdownParent: $('#addRubricItemModal'),
        width: '100%'
    });

    $('#rubrics-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
        }
    });

    // Function to reset the form
    function resetRubricForm() {
        $('#addRubricForm')[0].reset();
        $('#ri_id').val('');
        $('#ri_academic_standing').val('').trigger('change.select2');
        $('#ri_position').val('').trigger('change.select2');
        $('#addRubricItemModalLabel').html('<i class="bx bx-plus-circle me-1 text-primary"></i>เพิ่มหัวข้อการประเมินใหม่');
        $('#addRubricForm').attr('action', '<?= base_url('Admin/PaConfig/addRubricItem'); ?>');
        $('#addRubricForm input, #addRubricForm textarea, #ri_academic_standing, #ri_position').removeClass(
            'is-invalid');
    }

    // When the "Add New Rubric Item" button is clicked
    $('button[data-bs-target="#addRubricItemModal"]').not('.edit-rubric-btn').on('click', function() {
        resetRubricForm();
    });

    // When an "Edit" button is clicked
    $(document).on('click', '.edit-rubric-btn', function() {
        const button = $(this);
        $('#addRubricItemModalLabel').html('<i class="bx bx-edit-alt me-1 text-warning"></i>แก้ไขหัวข้อการประเมิน');
        $('#addRubricForm').attr('action',
            '<?= base_url('Admin/PaConfig/updateRubricItem'); ?>');

        $('#ri_id').val(button.data('id'));
        $('#ri_part').val(button.data('part'));
        $('#ri_domain').val(button.data('domain'));
        $('#ri_item_number').val(button.data('item_number'));
        $('#ri_item_description').val(button.data('item_description'));
        $('#ri_expected_level_description').val(button.data('expected_level_description'));
        $('#ri_academic_standing').val(button.data('academic_standing')).trigger('change.select2');
        $('#ri_position').val(button.data('position')).trigger('change.select2');
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
        const form = this;
        const formData = new FormData(form); // Use FormData for a more robust submission
        const formAction = $(form).attr('action'); // Get the form action URL

        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            processData: false,  // Important: Don't process the files
            contentType: false,  // Important: Set content type to false
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