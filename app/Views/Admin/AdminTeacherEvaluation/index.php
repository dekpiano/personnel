<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    .card-stat {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    .card-stat:hover {
        transform: translateY(-5px);
    }
    .status-badge {
        font-size: 0.85rem;
        padding: 0.5em 1em;
        border-radius: 10px;
    }
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold"><i class="bx bx-check-shield me-2 text-primary"></i><?= $title ?></h3>
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">ตรวจสอบสถานะการส่งไฟล์ประเมินผลการปฏิบัติงาน (เลื่อนเงินเดือน) ของครูและบุคลากร</p>
                <div class="text-end">
                    <?php if($is_active_by_date): ?>
                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bx bx-lock-open-alt me-1"></i> ระบบเปิดรับข้อมูล</span>
                    <?php elseif($is_open): ?>
                         <span class="badge bg-warning rounded-pill px-3 py-2 text-dark"><i class="bx bx-time me-1"></i> นอกกำหนดเวลาส่ง</span>
                    <?php else: ?>
                        <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bx bx-lock-alt me-1"></i> ระบบปิดรับข้อมูล</span>
                    <?php endif; ?>
                    <?php if($conf_start_date && $conf_end_date): ?>
                        <div class="small text-muted mt-1">
                            <i class="bx bx-calendar-event me-1"></i> กำหนดส่ง: <?= date('d/m/Y', strtotime($conf_start_date)) ?> - <?= date('d/m/Y', strtotime($conf_end_date)) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4 shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body">
            <form action="<?= base_url('Admin/TeacherEvaluation') ?>" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">ปีงบประมาณ</label>
                    <select name="year" class="form-select border-0 bg-light">
                        <?php foreach($years as $y): ?>
                            <option value="<?= $y ?>" <?= $y == $sel_year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">รอบที่การประเมิน</label>
                    <select name="round" class="form-select border-0 bg-light">
                        <option value="1" <?= $sel_round == 1 ? 'selected' : '' ?>>รอบที่ 1 (1 ต.ค. - 31 มี.ค.)</option>
                        <option value="2" <?= $sel_round == 2 ? 'selected' : '' ?>>รอบที่ 2 (1 เม.ย. - 30 ก.ย.)</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-bold">
                        <i class="bx bx-search-alt me-1"></i> ค้นหาข้อมูล
                    </button>
                    <button type="button" class="btn btn-dark rounded-pill fw-bold px-4" data-bs-toggle="modal" data-bs-target="#configModal">
                        <i class="bx bx-cog me-1"></i> ตั้งค่าระบบ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card card-stat shadow-sm bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-white mb-1">จำนวนครูทั้งหมด</h6>
                        <h2 class="mb-0 fw-bold text-white"><?= number_format($stats['total']) ?></h2>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bx bx-group"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat shadow-sm bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-white mb-1">ส่งแล้ว</h6>
                        <h2 class="mb-0 fw-bold text-white"><?= number_format($stats['sent']) ?></h2>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bx bx-check-double"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat shadow-sm bg-danger text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-white mb-1">ยังไม่ส่ง</h6>
                        <h2 class="mb-0 fw-bold text-white"><?= number_format($stats['pending']) ?></h2>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bx bx-time"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ลำดับ</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ตำแหน่ง</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">หลักฐานการส่ง</th>
                            <th class="pe-4 text-center">วันที่ส่งล่าสุด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($teachers)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">ไม่พบข้อมูลครูในระบบ</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($teachers as $index => $t): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold"><?= $t['pers_prefix'].$t['pers_firstname'].' '.$t['pers_lastname'] ?></div>
                                    <small class="text-muted">ID: <?= $t['pers_id'] ?></small>
                                </td>
                                <td><?= $t['posi_name'] ?></td>
                                <td class="text-center">
                                    <?php if($t['eva_status'] == 'ส่งแล้ว'): ?>
                                        <span class="status-badge bg-success-light text-success"><i class="bx bx-check-circle me-1"></i>ส่งแล้ว</span>
                                    <?php else: ?>
                                        <span class="status-badge bg-danger-light text-danger"><i class="bx bx-x-circle me-1"></i>ยังไม่ส่ง</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if($t['eva_file']): ?>
                                            <a href="<?= $upload_url . $sel_year . '/' . $sel_round . '/' . $t['eva_file'] ?>" target="_blank" class="btn btn-xs btn-primary rounded-pill px-3" title="เปิดไฟล์ PDF">
                                                <i class="bx bxs-file-pdf me-1"></i> ไฟล์ PDF
                                            </a>
                                        <?php endif; ?>
                                        <?php if($t['eva_canva_link']): ?>
                                            <a href="<?= $t['eva_canva_link'] ?>" target="_blank" class="btn btn-xs btn-info rounded-pill px-3" title="เปิดลิงก์เอกสาร">
                                                <i class="bx bx-link-external me-1"></i> ลิงก์เอกสาร
                                            </a>
                                        <?php endif; ?>
                                        <?php if(!$t['eva_file'] && !$t['eva_canva_link']): ?>
                                            <span class="text-muted small">ยังไม่มีหลักฐาน</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="pe-4 text-center">
                                    <?= $t['eva_updated_at'] ? date('d/m/Y H:i', strtotime($t['eva_updated_at'])) : '-' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Modal: ตั้งค่าเปิด-ปิดการส่งงาน -->
<div class="modal fade" id="configModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bx bx-cog me-2"></i>ตั้งค่าการรับข้อมูล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <form id="configForm">
                    <input type="hidden" name="conf_year" value="<?= $sel_year ?>">
                    <input type="hidden" name="conf_round" value="<?= $sel_round ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">ปีงบประมาณ/รอบที่เลือก</label>
                        <div class="p-3 bg-light rounded-3">
                            <i class="bx bx-calendar me-1 text-primary"></i> ปีงบประมาณ <?= $sel_year ?> 
                            <span class="mx-2">|</span>
                            <i class="bx bx-sync me-1 text-info"></i> รอบที่ <?= $sel_round ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">กำหนดช่วงเวลาส่งงาน</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted mb-1">เริ่มต้นวันที่</label>
                                <input type="date" name="conf_start_date" class="form-control rounded-3" value="<?= $conf_start_date ?>">
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">สิ้นสุดวันที่</label>
                                <input type="date" name="conf_end_date" class="form-control rounded-3" value="<?= $conf_end_date ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold d-block mb-3">สถานะการเปิดรับข้อมูล</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="conf_status" id="status_open" value="1" <?= $is_open ? 'checked' : '' ?>>
                                <label class="btn btn-outline-success w-100 py-3 rounded-4" for="status_open">
                                    <i class="bx bx-lock-open-alt d-block fs-3 mb-1"></i>
                                    เปิดระบบ
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="conf_status" id="status_close" value="0" <?= !$is_open ? 'checked' : '' ?>>
                                <label class="btn btn-outline-danger w-100 py-3 rounded-4" for="status_close">
                                    <i class="bx bx-lock-alt d-block fs-3 mb-1"></i>
                                    ปิดระบบ
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold w-100" id="btnSaveConfig">
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#configForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btnSaveConfig');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...');

        $.ajax({
            url: '<?= base_url('Admin/TeacherEvaluation/saveConfig') ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('บันทึกการตั้งค่า');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
