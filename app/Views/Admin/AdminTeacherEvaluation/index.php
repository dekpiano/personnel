<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'ติดตามการส่งผลการปฏิบัติงาน' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR TEACHER EVALUATION
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --eval-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
    }

    /* Hero Banner Card */
    .eval-hero-card {
        background: var(--eval-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .eval-hero-card::before {
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

    .eval-hero-card::after {
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
    .eval-stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .eval-stat-card:hover {
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

    .eval-stat-card:hover .stat-icon-wrap {
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

    /* High-contrast status badges */
    .badge-status-submitted {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #86efac !important;
        font-weight: 750;
    }

    .badge-status-pending {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fca5a5 !important;
        font-weight: 750;
    }
</style>

<!-- Hero Banner Card -->
<div class="eval-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-medal me-1"></i> ประเมินเลื่อนเงินเดือน
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-calendar me-1"></i> ปีงบประมาณ พ.ศ. <?= esc($sel_year); ?>
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-sync me-1"></i> รอบที่ <?= esc($sel_round); ?>
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                ตรวจสอบการส่งผลการปฏิบัติงาน (เลื่อนเงินเดือน)
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                ศูนย์ติดตามการส่งไฟล์เอกสารและหลักฐานการปฏิบัติงานของครูและบุคลากรทางการศึกษา
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex flex-column align-items-md-end align-items-start justify-content-center gap-2">
            <div>
                <?php if($is_active_by_date): ?>
                    <span class="badge bg-success shadow-sm rounded-pill px-3 py-2 fw-bold" style="font-size: 0.82rem;">
                        <i class="bx bx-lock-open-alt me-1"></i> ระบบเปิดรับข้อมูล
                    </span>
                <?php elseif($is_open): ?>
                    <span class="badge bg-warning text-dark shadow-sm rounded-pill px-3 py-2 fw-bold" style="font-size: 0.82rem;">
                        <i class="bx bx-time me-1"></i> นอกกำหนดเวลาส่ง
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary shadow-sm rounded-pill px-3 py-2 fw-bold" style="font-size: 0.82rem;">
                        <i class="bx bx-lock-alt me-1"></i> ระบบปิดรับข้อมูล
                    </span>
                <?php endif; ?>
            </div>

            <?php if($conf_start_date && $conf_end_date): ?>
                <div class="text-white text-opacity-90 small">
                    <i class="bx bx-calendar-event me-1"></i> กำหนดส่ง: <?= date('d/m/Y', strtotime($conf_start_date)) ?> - <?= date('d/m/Y', strtotime($conf_end_date)) ?>
                </div>
            <?php endif; ?>

            <button type="button" class="btn btn-sm btn-white text-primary fw-bold shadow-sm px-3 py-2 bg-white mt-1" data-bs-toggle="modal" data-bs-target="#configModal" style="border-radius: 10px;">
                <i class="bx bx-cog me-1"></i> ตั้งค่าระบบรับข้อมูล
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
        <form action="<?= base_url('Admin/TeacherEvaluation') ?>" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted mb-1"><i class="bx bx-calendar me-1 text-primary"></i>ปีงบประมาณ</label>
                <select name="year" class="form-select form-select-sm bg-light border-0 py-2 shadow-none fw-bold" onchange="this.form.submit()">
                    <?php foreach($years as $y): ?>
                        <option value="<?= $y ?>" <?= $y == $sel_year ? 'selected' : '' ?>>พ.ศ. <?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted mb-1"><i class="bx bx-sync me-1 text-primary"></i>รอบการประเมิน</label>
                <select name="round" class="form-select form-select-sm bg-light border-0 py-2 shadow-none fw-bold" onchange="this.form.submit()">
                    <option value="1" <?= $sel_round == 1 ? 'selected' : '' ?>>รอบที่ 1 (1 ต.ค. - 31 มี.ค.)</option>
                    <option value="2" <?= $sel_round == 2 ? 'selected' : '' ?>>รอบที่ 2 (1 เม.ย. - 30 ก.ย.)</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold small text-muted mb-1"><i class="bx bx-search me-1 text-primary"></i>ค้นหาครูและบุคลากร</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" id="teacherSearchInput" class="form-control bg-light border-0 py-2 shadow-none" placeholder="ค้นหาชื่อ - สกุล หรือตำแหน่ง...">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Section -->
<?php 
    $percentSent = $stats['total'] > 0 ? round(($stats['sent'] / $stats['total']) * 100, 1) : 0;
?>
<div class="row g-3 mb-4">
    <!-- Card 1: Total -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="eval-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">จำนวนครูทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-dark" data-counter="<?= $stats['total'] ?>"><?= number_format($stats['total']) ?></h3>
                    <div class="x-small text-muted mt-1">ประจำรอบที่ <?= $sel_round ?></div>
                </div>
                <div class="stat-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bx bx-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Sent -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="eval-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ส่งผลงานแล้ว</span>
                    <h3 class="fw-bold mb-0 text-success" data-counter="<?= $stats['sent'] ?>"><?= number_format($stats['sent']) ?></h3>
                    <div class="x-small text-success fw-bold mt-1">คิดเป็น <?= $percentSent ?>%</div>
                </div>
                <div class="stat-icon-wrap" style="background: #dcfce7; color: #16a34a;">
                    <i class="bx bx-check-double"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Pending -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="eval-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ยังไม่ส่งงาน</span>
                    <h3 class="fw-bold mb-0 text-danger" data-counter="<?= $stats['pending'] ?>"><?= number_format($stats['pending']) ?></h3>
                    <div class="x-small text-danger fw-bold mt-1">คงเหลือ <?= number_format($stats['pending']) ?> คน</div>
                </div>
                <div class="stat-icon-wrap" style="background: #fee2e2; color: #dc2626;">
                    <i class="bx bx-time"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Progress Indicator -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="eval-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold small">อัตราการส่งงานรวม</span>
                <span class="fw-bold text-primary fs-6"><?= $percentSent ?>%</span>
            </div>
            <div class="progress" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentSent ?>%; border-radius: 10px;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center x-small text-muted mt-2">
                <span>ส่งแล้ว <?= $stats['sent'] ?>/<?= $stats['total'] ?> คน</span>
                <span class="text-primary fw-bold">เป้าหมาย 100%</span>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-label-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-list-check"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">ตารางติดตามการส่งผลการปฏิบัติงาน (ปีงบประมาณ พ.ศ. <?= esc($sel_year) ?> รอบที่ <?= esc($sel_round) ?>)</h5>
        </div>
        <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold"><?= count($teachers) ?> คน</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-lux align-middle mb-0" id="teacherEvalTable">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">#</th>
                        <th style="min-width: 220px;">ชื่อ-นามสกุลครู</th>
                        <th style="min-width: 140px;">ตำแหน่ง</th>
                        <th class="text-center" style="width: 140px;">สถานะ</th>
                        <th class="text-center" style="min-width: 200px;">หลักฐานการส่งงาน</th>
                        <th class="pe-4 text-center" style="width: 180px;">วันที่ส่งล่าสุด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($teachers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bx bx-info-circle fs-2 d-block mb-2 text-secondary"></i>
                                <div class="fw-bold">ไม่พบข้อมูลครูในระบบสำหรับรอบนี้</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($teachers as $index => $t): ?>
                        <tr class="teacher-eval-row">
                            <td class="ps-4 text-muted fw-bold"><?= $index + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 bg-label-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold">
                                        <?= mb_substr($t['pers_firstname'], 0, 1, 'UTF-8') ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark teacher-eval-name"><?= esc($t['pers_prefix'].$t['pers_firstname'].' '.$t['pers_lastname']) ?></div>
                                        <div class="x-small text-muted">รหัส: <?= esc($t['pers_id']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary fw-bold"><?= esc($t['posi_name'] ?: 'ครู') ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($t['eva_status'] == 'ส่งแล้ว'): ?>
                                    <span class="badge badge-status-submitted rounded-pill px-3 py-1">
                                        <i class="bx bx-check-circle me-1"></i> ส่งแล้ว
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-status-pending rounded-pill px-3 py-1">
                                        <i class="bx bx-x-circle me-1"></i> ยังไม่ส่ง
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <?php if($t['eva_file']): ?>
                                        <a href="<?= $upload_url . $sel_year . '/' . $sel_round . '/' . $t['eva_file'] ?>" target="_blank" class="btn btn-sm btn-label-primary rounded-pill px-3 shadow-none fw-bold" title="เปิดไฟล์ PDF">
                                            <i class="bx bxs-file-pdf me-1 text-danger"></i> ไฟล์ PDF
                                        </a>
                                    <?php endif; ?>
                                    <?php if($t['eva_canva_link']): ?>
                                        <a href="<?= $t['eva_canva_link'] ?>" target="_blank" class="btn btn-sm btn-label-info rounded-pill px-3 shadow-none fw-bold" title="เปิดลิงก์เอกสาร">
                                            <i class="bx bx-link-external me-1"></i> ลิงก์เอกสาร
                                        </a>
                                    <?php endif; ?>
                                    <?php if(!$t['eva_file'] && !$t['eva_canva_link']): ?>
                                        <span class="text-muted small">- ยังไม่มีหลักฐาน -</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="pe-4 text-center small text-muted">
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

<!-- Modal: ตั้งค่าเปิด-ปิดการส่งงาน -->
<div class="modal fade" id="configModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="bx bx-cog fs-4"></i> ตั้งค่าการรับข้อมูลผลการปฏิบัติงาน
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="configForm">
                    <input type="hidden" name="conf_year" value="<?= $sel_year ?>">
                    <input type="hidden" name="conf_round" value="<?= $sel_round ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted mb-1">ปีงบประมาณ/รอบที่เลือก</label>
                        <div class="p-3 rounded-3" style="background: #f0f9ff; border: 1px solid #bae6fd;">
                            <i class="bx bx-calendar me-1 text-primary"></i> ปีงบประมาณ พ.ศ. <strong><?= $sel_year ?></strong>
                            <span class="mx-2 text-muted">|</span>
                            <i class="bx bx-sync me-1 text-info"></i> รอบที่ <strong><?= $sel_round ?></strong>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark mb-1"><i class="bx bx-calendar-event me-1 text-primary"></i>กำหนดช่วงเวลาส่งงาน</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted mb-1">เริ่มต้นวันที่</label>
                                <input type="date" name="conf_start_date" class="form-control shadow-none rounded-3" value="<?= $conf_start_date ?>">
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">สิ้นสุดวันที่</label>
                                <input type="date" name="conf_end_date" class="form-control shadow-none rounded-3" value="<?= $conf_end_date ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark d-block mb-2"><i class="bx bx-toggle-left me-1 text-primary"></i>สถานะการเปิดรับข้อมูล</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="conf_status" id="status_open" value="1" <?= $is_open ? 'checked' : '' ?>>
                                <label class="btn btn-outline-success w-100 py-3 rounded-3 fw-bold" for="status_open">
                                    <i class="bx bx-lock-open-alt d-block fs-3 mb-1"></i>
                                    เปิดระบบ
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="conf_status" id="status_close" value="0" <?= !$is_open ? 'checked' : '' ?>>
                                <label class="btn btn-outline-danger w-100 py-3 rounded-3 fw-bold" for="status_close">
                                    <i class="bx bx-lock-alt d-block fs-3 mb-1"></i>
                                    ปิดระบบ
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top py-2 px-4">
                <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" form="configForm" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm fw-bold" id="btnSaveConfig">
                    <i class="bx bx-save me-1"></i> บันทึกการตั้งค่า
                </button>
            </div>
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

    // Real-time Teacher Search
    $('#teacherSearchInput').on('keyup', function() {
        const query = $(this).val().toLowerCase().trim();
        $('.teacher-eval-row').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(query));
        });
    });

    // Save Config via AJAX
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
                btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกการตั้งค่า');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
