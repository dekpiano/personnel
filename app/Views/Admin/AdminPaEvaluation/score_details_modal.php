<?php
// This view is loaded via AJAX into the modal.
// It expects a variable $evaluation_data which is an array containing:
// - 'rubric_items' => array of all rubric items
// - 'item_scores' => an associative array mapping [ri_id => raw_score]
// - 'calculated_scores' => an associative array mapping [ri_id => calculated_point]
// - 'summary' => an array with keys like 'es_part1_score', 'es_part2_score', 'es_total_score', 'es_strong_points', etc.

$rubricItems = $evaluation_data['rubric_items'] ?? [];
$itemScores = $evaluation_data['item_scores'] ?? [];
$calculatedScores = $evaluation_data['calculated_scores'] ?? [];
$summary = $evaluation_data['summary'] ?? [];

?>

<?php if (empty($rubricItems) || empty($summary)): ?>
    <div class="alert alert-danger text-center" role="alert">
        ไม่พบข้อมูลการประเมินผล
    </div>
<?php else: ?>

    <!-- Part 1 Table -->
    <h6>ส่วนที่ 1 ข้อตกลงในการพัฒนางานตามมาตรฐานกำหนดตำแหน่ง (60 คะแนน)</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง</th>
                    <th colspan="4" class="text-center">ระดับผลการประเมิน</th>
                    <th rowspan="2" style="vertical-align: middle;">คะแนนที่ได้</th>
                </tr>
                <tr>
                    <th class="text-center" style="width: 8%;">1</th>
                    <th class="text-center" style="width: 8%;">2</th>
                    <th class="text-center" style="width: 8%;">3</th>
                    <th class="text-center" style="width: 8%;">4</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentPart1Domain = null;
                foreach ($rubricItems as $item):
                    if ($item['ri_part'] == 1):
                        if ($item['ri_domain'] !== $currentPart1Domain):
                            $currentPart1Domain = $item['ri_domain'];
                ?>
                            <tr>
                                <td colspan="6" class="table-light"><strong><?= esc($item['ri_domain']); ?></strong></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>
                                <?= esc($item['ri_item_number']); ?> <?= esc($item['ri_item_description']); ?>
                                <?php if (!empty($item['ri_expected_level_description'])): ?>
                                    <div class="mt-2 text-muted small">
                                        <strong>ระดับที่คาดหวัง:</strong> <?= esc($item['ri_expected_level_description']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <?php
                            $raw_score = $itemScores[$item['ri_id']] ?? 0;
                            for ($i = 1; $i <= 4; $i++): ?>
                                <td class="text-center align-middle">
                                    <?= ($raw_score == $i) ? '<span class="text-success fw-bold">✔</span>' : '' ?>
                                </td>
                            <?php endfor; ?>
                            <td class="text-center align-middle">
                                <?= isset($calculatedScores[$item['ri_id']]) ? number_format($calculatedScores[$item['ri_id']], 2) : '0.00' ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <hr class="my-4">

    <!-- Part 2 Table -->
    <h6>ส่วนที่ 2 ข้อตกลงในการพัฒนางานที่เป็นประเด็นท้าทาย (40 คะแนน)</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง</th>
                    <th colspan="4" class="text-center">ระดับผลการประเมิน</th>
                    <th rowspan="2" style="vertical-align: middle;">คะแนนที่ได้</th>
                </tr>
                <tr>
                    <th class="text-center" style="width: 8%;">1</th>
                    <th class="text-center" style="width: 8%;">2</th>
                    <th class="text-center" style="width: 8%;">3</th>
                    <th class="text-center" style="width: 8%;">4</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentPart2Domain = null;
                foreach ($rubricItems as $item):
                    if ($item['ri_part'] == 2):
                        if ($item['ri_domain'] !== $currentPart2Domain):
                            $currentPart2Domain = $item['ri_domain'];
                ?>
                            <tr>
                                <td colspan="6" class="table-light"><strong><?= esc($item['ri_domain']); ?></strong></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>
                                <?= esc($item['ri_item_number']); ?> <?= esc($item['ri_item_description']); ?>
                                <?php if (!empty($item['ri_expected_level_description'])): ?>
                                    <div class="mt-2 text-muted small">
                                        <strong>ระดับที่คาดหวัง:</strong> <?= esc($item['ri_expected_level_description']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <?php
                            $raw_score = $itemScores[$item['ri_id']] ?? 0;
                            for ($i = 1; $i <= 4; $i++): ?>
                                <td class="text-center align-middle">
                                    <?= ($raw_score == $i) ? '<span class="text-success fw-bold">✔</span>' : '' ?>
                                </td>
                            <?php endfor; ?>
                            <td class="text-center align-middle">
                                <?= isset($calculatedScores[$item['ri_id']]) ? number_format($calculatedScores[$item['ri_id']], 2) : '0.00' ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <hr class="my-4">

    <!-- Summary Section -->
    <p><strong>สรุปข้อสังเกตเกี่ยวกับ จุดเด่น จุดที่ควรพัฒนา และข้อคิดเห็น</strong></p>
    <div class="card mb-3">
        <div class="card-header">จุดเด่น</div>
        <div class="card-body">
            <p class="card-text"><?= !empty($summary['es_strong_points']) ? esc($summary['es_strong_points']) : '<em>- ไม่มีข้อมูล -</em>' ?></p>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">จุดที่ควรพัฒนา</div>
        <div class="card-body">
            <p class="card-text"><?= !empty($summary['es_areas_for_improvement']) ? esc($summary['es_areas_for_improvement']) : '<em>- ไม่มีข้อมูล -</em>' ?></p>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">ข้อคิดเห็น</div>
        <div class="card-body">
            <p class="card-text"><?= !empty($summary['es_comments']) ? esc($summary['es_comments']) : '<em>- ไม่มีข้อมูล -</em>' ?></p>
        </div>
    </div>

    <hr class="my-4">

    <!-- Totals Section -->
    <h6>ส่วนที่ 3: สรุปผลการประเมิน</h6>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">รวมคะแนนตอนที่ 1</h5>
                    <p class="card-text fs-4 fw-bold"><?= number_format($summary['es_part1_score'] ?? 0, 2) ?> / 60.00</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">รวมคะแนนตอนที่ 2</h5>
                    <p class="card-text fs-4 fw-bold"><?= number_format($summary['es_part2_score'] ?? 0, 2) ?> / 40.00</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">รวมคะแนนทั้งหมด</h5>
                    <p class="card-text fs-4 fw-bold"><?= number_format($summary['es_total_score'] ?? 0, 2) ?> / 100.00</p>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>