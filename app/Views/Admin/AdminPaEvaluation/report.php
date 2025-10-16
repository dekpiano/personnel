<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการประเมิน PA /</span> รายงานประเมิน PA</h4>

<div class="card">
    <h5 class="card-header">รายงานผลการประเมิน PA ปีการศึกษา <?= esc($fiscal_year) ?></h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ-สกุล</th>
                        <th>กลุ่มสาระ</th>
                        <th>ตำแหน่ง</th>
                        <th>วิทยฐานะ</th>
                        <th>สถานะการประเมิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($personnel)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($personnel as $person): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']) ?></td>
                                <td><?= esc($person['learning_area_name']) ?></td>
                                <td><?= esc($person['position_name']) ?></td>
                                <td><?= esc(empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $person['pers_academic']) ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Evaluators">
                                        <?php if (!empty($person['evaluators_info'])):
                                            foreach ($person['evaluators_info'] as $evaluator):
                                                $is_evaluated = !empty($evaluator['has_evaluated']);
                                                $btn_class = $is_evaluated ? 'btn-success' : 'btn-outline-secondary';
                                                ?>
                                                <button type="button"
                                                        class="btn <?= $btn_class ?> btn-sm <?= $is_evaluated ? 'view-scores' : '' ?>"
                                                    <?php if ($is_evaluated): ?>
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#evaluationScoreModal"
                                                        data-person-id="<?= esc($person['pers_id']) ?>"
                                                        data-evaluator-id="<?= esc($evaluator['id']) ?>"
                                                        data-person-name="<?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']) ?>"
                                                        data-evaluator-name="<?= esc($evaluator['name']) ?>"
                                                    <?php else: ?>
                                                        disabled
                                                    <?php endif; ?>>
                                                    <?= esc($evaluator['name']) ?>
                                                </button>
                                            <?php endforeach;
                                        else: ?>
                                            <span>ไม่มีผู้ประเมิน</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">ไม่พบข้อมูลบุคลากร</td>
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
            <div class="modal-header">
                <h5 class="modal-title" id="evaluationScoreModalLabel">รายละเอียดผลการประเมิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="scoreDetailsContainer">
                    <p class="text-center">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreModal = document.getElementById('evaluationScoreModal');
    if (scoreModal) {
        scoreModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const personId = button.getAttribute('data-person-id');
            const evaluatorId = button.getAttribute('data-evaluator-id');
            const personName = button.getAttribute('data-person-name');
            const evaluatorName = button.getAttribute('data-evaluator-name');

            const modalTitle = scoreModal.querySelector('.modal-title');
            const modalBody = scoreModal.querySelector('#scoreDetailsContainer');

            modalTitle.textContent = 'ผลการประเมินของ ' + personName + ' (ผู้ประเมิน: ' + evaluatorName + ')';
            modalBody.innerHTML = '<p class="text-center">กำลังโหลดข้อมูล...</p>';

            // IMPORTANT: You need to create this route and controller method in your application
            const url = `<?= site_url('Admin/PaEvaluation/Scores/') ?>${personId}/${evaluatorId}`;

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
                    modalBody.innerHTML = '<p class="text-center text-danger">เกิดข้อผิดพลาดในการดึงข้อมูล: ' + error.message + '</p><p>Please check the console for more details and ensure the backend endpoint is correct.</p>';
                });
        });
    }
});
</script>

<?= $this->endSection() ?>