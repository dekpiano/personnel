<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold m-0"><i class="bx bx-bar-chart-alt-2 text-primary me-2"></i>รายงานประเมิน PA</h4>
        <small class="text-muted">สรุปผลและติดตามสถานะการประเมินผลการพัฒนางานตามข้อตกลง (PA)</small>
    </div>
    <div class="d-flex align-items-center bg-white p-2 rounded shadow-sm border">
        <label for="selectFiscalYear" class="form-label me-2 mb-0 fw-bold small text-muted"><i class="bx bx-calendar me-1"></i>ปีการศึกษา:</label>
        <select id="selectFiscalYear" class="form-select form-select-sm select2" style="min-width: 150px;" onchange="location = this.value;">
            <?php foreach ($available_years as $yr): ?>
                <option value="<?= base_url('Admin/PaReport?fiscal_year=' . $yr) ?>" <?= $yr == $fiscal_year ? 'selected' : '' ?>>
                    ปีการศึกษา <?= esc($yr) ?>
                </option>
            <?php endforeach; ?>
        </select>
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
?>
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">ครูข้าราชการทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-primary"><?= $total_personnel; ?> <span class="fs-6 text-muted fw-normal">คน</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-group fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">ประเมินเสร็จสิ้นแล้ว</span>
                    <h3 class="fw-bold mb-0 text-success"><?= $completed_count; ?> <span class="fs-6 text-muted fw-normal">คน</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-check-circle fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fff3e0 0%, #ffffff 100%);">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">อยู่ระหว่างดำเนินการ</span>
                    <h3 class="fw-bold mb-0 text-warning"><?= $pending_count; ?> <span class="fs-6 text-muted fw-normal">คน</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-time-five fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold fs-6"><i class="bx bx-file me-2 text-primary"></i>รายงานผลการประเมิน PA ครูข้าราชการ ปีการศึกษา <?= esc($fiscal_year) ?></h5>
        <span class="badge bg-label-primary rounded-pill"><i class="bx bx-award me-1"></i>เฉพาะครูข้าราชการ ประจำปี <?= esc($fiscal_year) ?></span>
    </div>
    <div class="card-body pt-3">
        <div class="table-responsive">
            <table id="report-table" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;" class="text-nowrap">#</th>
                        <th class="text-nowrap" style="width: 220px;">ชื่อ-นามสกุล (ครูข้าราชการ)</th>
                        <th class="text-nowrap" style="width: 140px;">กลุ่มสาระ</th>
                        <th class="text-nowrap" style="width: 100px;">ตำแหน่ง</th>
                        <th class="text-nowrap" style="width: 110px;">วิทยฐานะ</th>
                        <th class="text-nowrap">สถานะประเมิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($personnel)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($personnel as $person): ?>
                            <tr>
                                <td class="text-nowrap"><?= $i++ ?></td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2 bg-label-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                            <i class="bx bx-user fs-6"></i>
                                        </div>
                                        <span class="fw-semibold text-dark text-nowrap">
                                            <?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-label-info text-nowrap"><?= esc(str_replace('กลุ่มสาระการเรียนรู้', '', $person['learning_area_name'])) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary text-nowrap"><?= esc($person['position_name']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-label-success text-nowrap">
                                        <?= esc(empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $person['pers_academic']) ?>
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-flex flex-wrap gap-1" role="group" aria-label="Evaluators">
                                        <?php if (!empty($person['evaluators_info'])):
                                            foreach ($person['evaluators_info'] as $evaluator):
                                                $is_evaluated = !empty($evaluator['has_evaluated']);
                                                ?>
                                                <button type="button"
                                                        class="btn btn-sm text-nowrap <?= $is_evaluated ? 'btn-success shadow-sm' : 'btn-outline-secondary' ?> <?= $is_evaluated ? 'view-scores' : '' ?>"
                                                    <?php if ($is_evaluated): ?>
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#evaluationScoreModal"
                                                        data-person-id="<?= esc($person['pers_id']) ?>"
                                                        data-evaluator-id="<?= esc($evaluator['id']) ?>"
                                                        data-person-name="<?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']) ?>"
                                                        data-evaluator-name="<?= esc($evaluator['name']) ?>"
                                                        data-fiscal-year="<?= esc($fiscal_year) ?>"
                                                    <?php else: ?>
                                                        disabled
                                                    <?php endif; ?>>
                                                    <i class="bx <?= $is_evaluated ? 'bx-check-circle' : 'bx-time' ?> me-1"></i>
                                                    <?= esc($evaluator['name']) ?>
                                                </button>
                                            <?php endforeach;
                                        else: ?>
                                            <span class="text-muted small text-nowrap"><i class="bx bx-info-circle me-1"></i>ไม่มีผู้ประเมิน</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">ไม่พบข้อมูลบุคลากร</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Evaluation Scores -->
<div class="modal fade" id="evaluationScoreModal" tabindex="-1" aria-labelledby="evaluationScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="evaluationScoreModalLabel"><i class="bx bx-file-find text-primary me-2"></i>รายละเอียดผลการประเมิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="scoreDetailsContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">กำลังโหลดข้อมูล...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top p-3">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });

    $('#report-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
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

            modalTitle.innerHTML = '<i class="bx bx-file-find text-primary me-2"></i>ผลการประเมินของ ' + personName + ' <span class="badge bg-label-info ms-2">ผู้ประเมิน: ' + evaluatorName + '</span> <span class="badge bg-label-primary ms-1">ปีการศึกษา ' + fiscalYear + '</span>';
            modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">กำลังโหลดข้อมูล...</p></div>';

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

<?= $this->endSection() ?>