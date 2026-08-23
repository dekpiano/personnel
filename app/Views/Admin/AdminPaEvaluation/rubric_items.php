<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'จัดการหัวข้อการประเมิน PA' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR PA RUBRICS CONFIGURATION
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --pa-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
    }

    /* Hero Banner Card */
    .rubric-hero-card {
        background: var(--pa-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .rubric-hero-card::before {
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

    .rubric-hero-card::after {
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

    /* Stat KPI Cards */
    .rubric-stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .rubric-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(2, 132, 199, 0.12);
        border-color: #bae6fd;
    }

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        transition: transform 0.3s ease;
    }

    .rubric-stat-card:hover .stat-icon-wrap {
        transform: scale(1.1) rotate(4deg);
    }

    /* Table Lux */
    .table-lux thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 750;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
    }

    .table-lux tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-lux tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-lux tbody tr:hover {
        background-color: #f0f9ff !important;
    }
</style>

<!-- Hero Banner Card -->
<div class="rubric-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-list-check me-1"></i> เกณฑ์การประเมิน PA
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-award me-1"></i> มาตรฐาน ก.ค.ศ. ว 9/2564
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                จัดการหัวข้อและเกณฑ์การประเมิน PA (Rubrics)
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                กำหนดหัวข้อเกณฑ์การประเมิน คำอธิบายลักษณะงาน และระดับผลการปฏิบัติงานที่คาดหวังตามมาตรฐานวิทยฐานะ
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-sm btn-white text-primary fw-bold shadow-sm px-4 py-2 bg-white" data-bs-toggle="modal" data-bs-target="#addRubricItemModal" style="border-radius: 10px;">
                <i class="bx bx-plus me-1"></i> เพิ่มหัวข้อใหม่
            </button>
        </div>
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
        <div class="rubric-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">หัวข้อประเมินทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-dark" data-counter="<?= $total_items; ?>"><?= $total_items; ?></h3>
                    <div class="x-small text-muted mt-1">เกณฑ์การประเมินรวม</div>
                </div>
                <div class="stat-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bx bx-task"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="rubric-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ส่วนที่ 1: มาตรฐานตำแหน่ง</span>
                    <h3 class="fw-bold mb-0 text-success" data-counter="<?= $part1_count; ?>"><?= $part1_count; ?></h3>
                    <div class="x-small text-success fw-bold mt-1">ข้อตกลงในการพัฒนางาน</div>
                </div>
                <div class="stat-icon-wrap" style="background: #dcfce7; color: #16a34a;">
                    <i class="bx bx-book-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="rubric-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ส่วนที่ 2: ประเด็นท้าทาย</span>
                    <h3 class="fw-bold mb-0 text-primary" data-counter="<?= $part2_count; ?>"><?= $part2_count; ?></h3>
                    <div class="x-small text-primary fw-bold mt-1">ข้อตกลงในการประเมิน</div>
                </div>
                <div class="stat-icon-wrap" style="background: #f0fdf4; color: #059669;">
                    <i class="bx bx-award"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-label-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-table"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">รายการหัวข้อและเกณฑ์การประเมิน PA</h5>
        </div>
        <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold"><?= $total_items; ?> รายการ</span>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="rubrics-table" class="table table-hover table-lux align-middle w-100">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="ps-3">ส่วนที่</th>
                        <th style="width: 170px;">หมวดหมู่</th>
                        <th style="width: 60px;" class="text-center">ลำดับ</th>
                        <th>รายละเอียดลักษณะงาน / ระดับที่คาดหวัง</th>
                        <th style="width: 130px;">วิทยฐานะ</th>
                        <th style="width: 110px;">ตำแหน่ง</th>
                        <th style="width: 100px;" class="text-center pe-3">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rubrics)):
                        foreach ($rubrics as $item): ?>
                    <tr>
                        <td class="ps-3">
                            <?php if ($item['ri_part'] == 1): ?>
                                <span class="badge bg-label-info rounded-pill px-2 py-1"><i class="bx bx-layer me-1"></i>ส่วนที่ 1</span>
                            <?php else: ?>
                                <span class="badge bg-label-primary rounded-pill px-2 py-1"><i class="bx bx-layer me-1"></i>ส่วนที่ 2</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="fw-bold text-dark"><?= esc($item['ri_domain']); ?></span></td>
                        <td class="text-center"><span class="badge bg-light text-dark border fw-bold px-2 py-1"><?= esc($item['ri_item_number']); ?></span></td>
                        <td style="white-space: normal;">
                            <div class="fw-bold text-dark mb-1"><?= esc($item['ri_item_description']); ?></div>
                            <?php if (!empty($item['ri_expected_level_description'])): ?>
                            <div class="p-2 rounded-3 small text-muted border-start border-3 border-primary" style="background: #f8fafc; font-size: 0.82rem;">
                                <strong class="text-primary"><i class="bx bx-target-lock me-1"></i>ระดับที่คาดหวัง:</strong> <?= esc($item['ri_expected_level_description']); ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $standing = $item['ri_academic_standing'] ?? 'ทั่วไป';
                                if (empty($standing) || $standing === 'ทั่วไป') {
                                    echo '<span class="badge bg-label-secondary">ทั่วไป</span>';
                                } else {
                                    echo '<span class="badge bg-label-success fw-bold">' . esc($standing) . '</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-label-warning fw-bold"><?= esc(!empty($item['ri_position']) ? $item['ri_position'] : 'ทั้งหมด'); ?></span>
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-icon btn-sm btn-label-warning rounded-pill shadow-none edit-rubric-btn" data-bs-toggle="modal"
                                    data-bs-target="#addRubricItemModal" data-id="<?= esc($item['ri_id']); ?>"
                                    data-part="<?= esc($item['ri_part']); ?>" data-domain="<?= esc($item['ri_domain']); ?>"
                                    data-item_number="<?= esc($item['ri_item_number']); ?>"
                                    data-item_description="<?= esc($item['ri_item_description']); ?>"
                                    data-expected_level_description="<?= esc($item['ri_expected_level_description']); ?>"
                                    data-academic_standing="<?= esc($item['ri_academic_standing']); ?>"
                                    data-position="<?= esc($item['ri_position'] ?? ''); ?>" title="แก้ไข">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-label-danger rounded-pill shadow-none delete-rubric-btn"
                                    data-id="<?= esc($item['ri_id']); ?>" title="ลบ">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bx bx-info-circle fs-2 d-block mb-2 text-secondary"></i>
                            <div class="fw-bold">ยังไม่มีหัวข้อการประเมินในระบบ</div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Rubric Item Modal -->
<div class="modal fade" id="addRubricItemModal" tabindex="-1" aria-labelledby="addRubricItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form id="rubricItemForm">
                <input type="hidden" id="ri_id" name="ri_id" value="">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="addRubricItemModalLabel">
                        <i class="bx bx-task fs-4"></i> เพิ่มหัวข้อการประเมิน
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="ri_part" class="form-label fw-bold small text-dark"><i class="bx bx-layer me-1 text-primary"></i>ส่วนที่</label>
                            <select class="form-select shadow-none" id="ri_part" name="ri_part" required>
                                <option value="1">ส่วนที่ 1 ข้อตกลงในการพัฒนางานตามมาตรฐานตำแหน่ง</option>
                                <option value="2">ส่วนที่ 2 ข้อตกลงในการพัฒนางานที่เป็นประเด็นท้าทาย</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="ri_domain" class="form-label fw-bold small text-dark"><i class="bx bx-category me-1 text-primary"></i>ด้าน / หมวดหมู่</label>
                            <input type="text" class="form-control shadow-none" id="ri_domain" name="ri_domain" placeholder="เช่น ด้านที่ 1 ด้านการจัดการเรียนรู้" required>
                        </div>
                        <div class="col-md-3">
                            <label for="ri_item_number" class="form-label fw-bold small text-dark"><i class="bx bx-hash me-1 text-primary"></i>ลำดับหัวข้อ</label>
                            <input type="text" class="form-control shadow-none" id="ri_item_number" name="ri_item_number" placeholder="เช่น 1.1" required>
                        </div>
                        <div class="col-12">
                            <label for="ri_item_description" class="form-label fw-bold small text-dark"><i class="bx bx-detail me-1 text-primary"></i>รายละเอียดลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง</label>
                            <textarea class="form-control shadow-none" id="ri_item_description" name="ri_item_description" rows="3" placeholder="ระบุรายละเอียดลักษณะงาน" required></textarea>
                        </div>
                        <div class="col-12">
                            <label for="ri_expected_level_description" class="form-label fw-bold small text-dark"><i class="bx bx-target-lock me-1 text-primary"></i>ระดับผลการปฏิบัติงานที่คาดหวัง (Expected Level)</label>
                            <textarea class="form-control shadow-none" id="ri_expected_level_description" name="ri_expected_level_description" rows="2" placeholder="ระบุระดับที่คาดหวัง เช่น ริเริ่ม พัฒนา (Initiative & Development)"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="ri_academic_standing" class="form-label fw-bold small text-dark"><i class="bx bx-award me-1 text-primary"></i>วิทยฐานะ</label>
                            <input type="text" class="form-control shadow-none" id="ri_academic_standing" name="ri_academic_standing" placeholder="เช่น ครูชำนาญการพิเศษ (เว้นว่างหรือพิมพ์ 'ทั่วไป' หากใช้กับทุกคน)">
                        </div>
                        <div class="col-md-6">
                            <label for="ri_position" class="form-label fw-bold small text-dark"><i class="bx bx-briefcase me-1 text-primary"></i>ตำแหน่ง</label>
                            <input type="text" class="form-control shadow-none" id="ri_position" name="ri_position" placeholder="เช่น ครู, ผู้อำนวยการ (เว้นว่างหากใช้กับทุกตำแหน่ง)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm fw-bold">
                        <i class="bx bx-save me-1"></i> บันทึกข้อมูล
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
    // Animated Counters
    $('[data-counter]').each(function() {
        const $this = $(this);
        const target = parseInt($this.attr('data-counter'), 10) || 0;
        if (target === 0) return;
        $({ countNum: 0 }).animate({ countNum: target }, {
            duration: 800,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum).toLocaleString());
            },
            complete: function() {
                $this.text(target.toLocaleString());
            }
        });
    });

    // Initialize DataTable
    $('#rubrics-table').DataTable({
        "order": [[0, "asc"], [2, "asc"]],
        "pageLength": 25,
        "language": {
            "search": "ค้นหาหัวข้อ:",
            "lengthMenu": "แสดง _MENU_ รายการ",
            "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            "infoEmpty": "ไม่มีรายการ",
            "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
            "zeroRecords": "ไม่พบข้อมูลที่ตรงกัน",
            "paginate": {
                "first": "หน้าแรก",
                "last": "หน้าสุดท้าย",
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
            }
        }
    });

    // Reset modal form on open for add
    $('#addRubricItemModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        if (!button.hasClass('edit-rubric-btn')) {
            $('#rubricItemForm')[0].reset();
            $('#ri_id').val('');
            $('#addRubricItemModalLabel').html('<i class="bx bx-plus-circle me-1"></i> เพิ่มหัวข้อการประเมิน');
        }
    });

    // Fill form on edit button click
    $(document).on('click', '.edit-rubric-btn', function() {
        var btn = $(this);
        $('#ri_id').val(btn.data('id'));
        $('#ri_part').val(btn.data('part'));
        $('#ri_domain').val(btn.data('domain'));
        $('#ri_item_number').val(btn.data('item_number'));
        $('#ri_item_description').val(btn.data('item_description'));
        $('#ri_expected_level_description').val(btn.data('expected_level_description'));
        $('#ri_academic_standing').val(btn.data('academic_standing'));
        $('#ri_position').val(btn.data('position'));
        $('#addRubricItemModalLabel').html('<i class="bx bx-edit-alt me-1"></i> แก้ไขหัวข้อการประเมิน');
    });

    // Save rubric item via AJAX
    $('#rubricItemForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...');

        $.ajax({
            url: '<?= base_url('Admin/PaConfig/saveRubricItem') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#addRubricItemModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('ข้อผิดพลาด!', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('ข้อผิดพลาด!', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกข้อมูล');
            }
        });
    });

    // Delete rubric item
    $(document).on('click', '.delete-rubric-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบหัวข้อการประเมินนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('Admin/PaConfig/deleteRubricItem') ?>/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                text: response.message,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('ข้อผิดพลาด!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('ข้อผิดพลาด!', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>