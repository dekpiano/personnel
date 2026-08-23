<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'รายงานประเมิน PA' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR PA REPORT
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --pa-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
    }

    /* Hero Banner Card */
    .pareport-hero-card {
        background: var(--pa-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .pareport-hero-card::before {
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

    .pareport-hero-card::after {
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
    .pareport-stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .pareport-stat-card:hover {
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

    .pareport-stat-card:hover .stat-icon-wrap {
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

    .btn-evaluator-done {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border: 1px solid #86efac !important;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .btn-evaluator-done:hover {
        background-color: #16a34a !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.25);
    }

    .btn-evaluator-pending {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px dashed #cbd5e1 !important;
        font-weight: 600;
    }
</style>

<!-- Hero Banner Card -->
<div class="pareport-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-bar-chart-alt-2 me-1"></i> รายงานสรุปผล PA
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-calendar me-1"></i> ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                รายงานผลการประเมิน PA ข้าราชการครู
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                สรุปผลคะแนน ติดตามความคืบหน้า และตรวจสอบผลการประเมินจากคณะกรรมการรายบุคคล ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <!-- Year Selector Form -->
            <div class="input-group input-group-sm shadow-sm" style="border-radius: 12px; overflow: hidden; max-width: 240px;">
                <span class="input-group-text bg-white border-0 fw-bold text-primary" style="font-size: 0.82rem;">
                    <i class="bx bx-calendar-event me-1"></i> ปีการศึกษา
                </span>
                <select id="selectFiscalYear" class="form-select form-select-sm border-0 fw-bold bg-white text-dark shadow-none" onchange="location = this.value;">
                    <?php foreach ($available_years as $yr): ?>
                        <option value="<?= base_url('Admin/PaReport?fiscal_year=' . $yr) ?>" <?= $yr == $fiscal_year ? 'selected' : '' ?>>
                            พ.ศ. <?= esc($yr) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Stat Summary Cards -->
<?php
    $total_personnel = count($personnel);
    $completed_count = 0;
    $pending_count = 0;

    foreach ($personnel as $p) {
        $all_eval_done = true;
        $has_evaluators = !empty($p['evaluators_info']);
        
        if ($has_evaluators) {
            foreach ($p['evaluators_info'] as $e) {
                if (empty($e['has_evaluated'])) {
                    $all_eval_done = false;
                    break;
                }
            }
        } else {
            $all_eval_done = false;
        }

        if ($all_eval_done && $has_evaluators) {
            $completed_count++;
        } else {
            $pending_count++;
        }
    }
    $percentDone = $total_personnel > 0 ? round(($completed_count / $total_personnel) * 100, 1) : 0;
?>
<div class="row g-3 mb-4">
    <!-- Card 1: Total -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pareport-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ครูข้าราชการทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-dark" data-counter="<?= $total_personnel; ?>"><?= number_format($total_personnel); ?></h3>
                    <div class="x-small text-muted mt-1">ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year) ?></div>
                </div>
                <div class="stat-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bx bx-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Completed -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pareport-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ประเมินเสร็จสิ้นแล้ว</span>
                    <h3 class="fw-bold mb-0 text-success" data-counter="<?= $completed_count; ?>"><?= number_format($completed_count); ?></h3>
                    <div class="x-small text-success fw-bold mt-1">คิดเป็น <?= $percentDone ?>%</div>
                </div>
                <div class="stat-icon-wrap" style="background: #dcfce7; color: #16a34a;">
                    <i class="bx bx-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Pending -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pareport-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">อยู่ระหว่างดำเนินการ</span>
                    <h3 class="fw-bold mb-0 text-warning" data-counter="<?= $pending_count; ?>"><?= number_format($pending_count); ?></h3>
                    <div class="x-small text-warning fw-bold mt-1">รอผลอีก <?= number_format($pending_count); ?> คน</div>
                </div>
                <div class="stat-icon-wrap" style="background: #fef3c7; color: #d97706;">
                    <i class="bx bx-time-five"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Progress Indicator -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="pareport-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold small">ความสำเร็จการประเมิน</span>
                <span class="fw-bold text-primary fs-6"><?= $percentDone ?>%</span>
            </div>
            <div class="progress" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentDone ?>%; border-radius: 10px;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center x-small text-muted mt-2">
                <span>เสร็จ <?= $completed_count ?>/<?= $total_personnel ?> คน</span>
                <span class="text-primary fw-bold">เป้าหมาย 100%</span>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-label-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-file"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">ตารางผลการประเมิน PA ครูข้าราชการ (ปีการศึกษา พ.ศ. <?= esc($fiscal_year) ?>)</h5>
        </div>
        <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold">
            <i class="bx bx-award me-1"></i>เฉพาะครูข้าราชการ
        </span>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="report-table" class="table table-hover table-lux align-middle w-100">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-nowrap ps-3">#</th>
                        <th class="text-nowrap" style="min-width: 200px;">ชื่อ-นามสกุล (ครูข้าราชการ)</th>
                        <th class="text-nowrap" style="min-width: 140px;">กลุ่มสาระการเรียนรู้</th>
                        <th class="text-nowrap" style="min-width: 110px;">ตำแหน่ง</th>
                        <th class="text-nowrap" style="min-width: 120px;">วิทยฐานะ</th>
                        <th class="text-nowrap pe-3">สถานะและคะแนนจากผู้ประเมิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($personnel)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($personnel as $person): ?>
                            <tr>
                                <td class="text-nowrap ps-3 text-muted fw-bold"><?= $i++ ?></td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2 bg-label-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold">
                                            <?= mb_substr($person['pers_firstname'], 0, 1, 'UTF-8') ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark text-nowrap">
                                                <?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']) ?>
                                            </div>
                                            <div class="x-small text-muted">รหัส: <?= esc($person['pers_id']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-label-info text-nowrap fw-bold"><?= esc(str_replace('กลุ่มสาระการเรียนรู้', '', $person['learning_area_name'])) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary text-nowrap fw-bold"><?= esc($person['position_name']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-label-success text-nowrap fw-bold">
                                        <?= esc(empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $person['pers_academic']) ?>
                                    </span>
                                </td>
                                <td class="text-nowrap pe-3">
                                    <div class="d-flex flex-wrap gap-1" role="group" aria-label="Evaluators">
                                        <?php if (!empty($person['evaluators_info'])):
                                            foreach ($person['evaluators_info'] as $evaluator):
                                                $is_evaluated = !empty($evaluator['has_evaluated']);
                                                ?>
                                                <button type="button"
                                                        class="btn btn-sm rounded-pill text-nowrap px-3 py-1 <?= $is_evaluated ? 'btn-evaluator-done' : 'btn-evaluator-pending' ?> <?= $is_evaluated ? 'view-scores' : '' ?>"
                                                    <?php if ($is_evaluated): ?>
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#evaluationScoreModal"
                                                        data-person-id="<?= esc($person['pers_id']) ?>"
                                                        data-evaluator-id="<?= esc($evaluator['id']) ?>"
                                                        data-person-name="<?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']) ?>"
                                                        data-evaluator-name="<?= esc($evaluator['name']) ?>"
                                                        data-fiscal-year="<?= esc($fiscal_year) ?>"
                                                        title="คลิกเพื่อดูรายละเอียดคะแนน"
                                                    <?php else: ?>
                                                        disabled
                                                        title="ยังไม่ได้รับการประเมินจากผู้ประเมินท่านนี้"
                                                    <?php endif; ?>>
                                                    <i class="bx <?= $is_evaluated ? 'bx-check-circle' : 'bx-time' ?> me-1"></i>
                                                    <?= esc($evaluator['name']) ?>
                                                </button>
                                            <?php endforeach;
                                        else: ?>
                                            <span class="text-muted small text-nowrap"><i class="bx bx-info-circle me-1"></i>ยังไม่มีผู้ประเมิน</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bx bx-info-circle fs-2 d-block mb-2 text-secondary"></i>
                                <div class="fw-bold">ไม่พบข้อมูลบุคลากรในปีการศึกษานี้</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Evaluation Scores -->
<div class="modal fade" id="evaluationScoreModal" tabindex="-1" aria-labelledby="evaluationScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="evaluationScoreModalLabel">
                    <i class="bx bx-file-find fs-4"></i> รายละเอียดผลการประเมิน PA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="scoreDetailsContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted fw-bold">กำลังโหลดข้อมูลผลคะแนน...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
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

    $('#report-table').DataTable({
        "pageLength": 25,
        "language": {
            "search": "ค้นหาครู:",
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

    const scoreModal = document.getElementById('evaluationScoreModal');
    if (scoreModal) {
        scoreModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const personId = button.getAttribute('data-person-id');
            const evaluatorId = button.getAttribute('data-evaluator-id');
            const personName = button.getAttribute('data-person-name');
            const evaluatorName = button.getAttribute('data-evaluator-name');
            const fiscalYear = button.getAttribute('data-fiscal-year');

            const modalTitle = scoreModal.querySelector('.modal-title');
            const modalBody = scoreModal.querySelector('#scoreDetailsContainer');

            modalTitle.innerHTML = '<i class="bx bx-file-find fs-4"></i> ผลการประเมินของ ' + personName + ' <span class="badge bg-white text-primary ms-2">ผู้ประเมิน: ' + evaluatorName + '</span> <span class="badge bg-white text-dark ms-1">ปีการศึกษา พ.ศ. ' + fiscalYear + '</span>';
            modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted fw-bold">กำลังโหลดข้อมูลผลคะแนน...</p></div>';

            const url = `<?= site_url('Admin/PaEvaluation/Scores/') ?>${personId}/${evaluatorId}/${fiscalYear}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok. Status: ' + response.statusText);
                    }
                    return response.text();
                })
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching evaluation scores:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger text-center"><i class="bx bx-error me-1"></i>เกิดข้อผิดพลาดในการดึงข้อมูล: ' + error.message + '</div>';
                });
        });
    }
});
</script>
<?= $this->endSection() ?>