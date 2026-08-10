<?php
$rubricItems = $evaluation_data['rubric_items'] ?? [];
$itemScores = $evaluation_data['item_scores'] ?? [];
$calculatedScores = $evaluation_data['calculated_scores'] ?? [];
$summary = $evaluation_data['summary'] ?? [];
?>

<?php if (empty($rubricItems) || empty($summary)): ?>
    <div class="alert alert-danger text-center my-3" role="alert">
        <i class="bx bx-error-circle me-1"></i> ไม่พบข้อมูลการประเมินผล
    </div>
<?php else: ?>

    <!-- Nav Tabs Header -->
    <ul class="nav nav-tabs nav-fill mb-3" id="scoreModalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-content"
                type="button" role="tab">
                <i class="bx bx-pie-chart-alt-2 me-1 text-primary"></i>สรุปผลคะแนน & ข้อคิดเห็น
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="part1-tab" data-bs-toggle="tab" data-bs-target="#part1-content"
                type="button" role="tab">
                <i class="bx bx-book-open me-1 text-info"></i>ส่วนที่ 1 (60 คะแนน)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="part2-tab" data-bs-toggle="tab" data-bs-target="#part2-content"
                type="button" role="tab">
                <i class="bx bx-award me-1 text-success"></i>ส่วนที่ 2 (40 คะแนน)
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content p-0 border-0" id="scoreModalTabsContent">

        <!-- Tab 1: สรุปผลคะแนน & ข้อคิดเห็น -->
        <div class="tab-pane fade show active" id="summary-content" role="tabpanel">
            <!-- 1. Top Score Summary Cards -->
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm text-center h-100"
                        style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%); border-start: 4px solid #03c3ec !important;">
                        <div class="card-body p-3">
                            <span class="text-muted fw-semibold small d-block mb-1">รวมคะแนนส่วนที่ 1</span>
                            <h3 class="fw-bold mb-0 text-primary"><?= number_format($summary['es_part1_score'] ?? 0, 2) ?>
                                <span class="fs-6 text-muted fw-normal">/ 60.00</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm text-center h-100"
                        style="background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%); border-start: 4px solid #2e7d32 !important;">
                        <div class="card-body p-3">
                            <span class="text-muted fw-semibold small d-block mb-1">รวมคะแนนส่วนที่ 2</span>
                            <h3 class="fw-bold mb-0 text-success"><?= number_format($summary['es_part2_score'] ?? 0, 2) ?>
                                <span class="fs-6 text-muted fw-normal">/ 40.00</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm text-center text-white h-100"
                        style="background: linear-gradient(135deg, #03c3ec 0%, #0288d1 100%);">
                        <div class="card-body p-3">
                            <span class="text-white-50 fw-semibold small d-block mb-1">รวมคะแนนทั้งหมด</span>
                            <h2 class="fw-bold mb-0 text-white"><?= number_format($summary['es_total_score'] ?? 0, 2) ?>
                                <span class="fs-6 text-white-50 fw-normal">/ 100.00</span></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Observations Feedback (Side-by-Side Cards) -->
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card border shadow-none h-100">
                        <div class="card-header bg-light py-2 px-3 fw-bold small text-success d-flex align-items-center">
                            <i class="bx bx-like me-1"></i> จุดเด่น
                        </div>
                        <div class="card-body p-3 small text-secondary" style="max-height: 180px; overflow-y: auto;">
                            <?= !empty($summary['es_strong_points']) ? nl2br(esc($summary['es_strong_points'])) : '<em class="text-muted">- ไม่มีข้อมูล -</em>' ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border shadow-none h-100">
                        <div class="card-header bg-light py-2 px-3 fw-bold small text-warning d-flex align-items-center">
                            <i class="bx bx-trending-up me-1"></i> จุดที่ควรพัฒนา
                        </div>
                        <div class="card-body p-3 small text-secondary" style="max-height: 180px; overflow-y: auto;">
                            <?= !empty($summary['es_areas_for_improvement']) ? nl2br(esc($summary['es_areas_for_improvement'])) : '<em class="text-muted">- ไม่มีข้อมูล -</em>' ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border shadow-none h-100">
                        <div class="card-header bg-light py-2 px-3 fw-bold small text-info d-flex align-items-center">
                            <i class="bx bx-comment-detail me-1"></i> ข้อคิดเห็นเพิ่มเติม
                        </div>
                        <div class="card-body p-3 small text-secondary" style="max-height: 180px; overflow-y: auto;">
                            <?= !empty($summary['es_comments']) ? nl2br(esc($summary['es_comments'])) : '<em class="text-muted">- ไม่มีข้อมูล -</em>' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: ส่วนที่ 1 -->
        <div class="tab-pane fade" id="part1-content" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle mb-0" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="py-1">ลักษณะงานตามมาตรฐานตำแหน่ง</th>
                            <th class="text-center py-1" style="width: 35px;">1</th>
                            <th class="text-center py-1" style="width: 35px;">2</th>
                            <th class="text-center py-1" style="width: 35px;">3</th>
                            <th class="text-center py-1" style="width: 35px;">4</th>
                            <th class="text-center py-1" style="width: 75px;">คะแนน</th>
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
                                    <tr class="table-secondary py-0">
                                        <td colspan="6" class="fw-bold text-dark py-1" style="font-size: 0.8rem;"><i
                                                class="bx bx-folder me-1 text-primary"></i><?= esc($item['ri_domain']); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="py-1 px-2">
                                        <span class="fw-bold text-dark me-1"><?= esc($item['ri_item_number']); ?></span>
                                        <span class="text-dark"><?= esc($item['ri_item_description']); ?></span>
                                    </td>
                                    <?php
                                    $raw_score = $itemScores[$item['ri_id']] ?? 0;
                                    for ($i = 1; $i <= 4; $i++): ?>
                                        <td class="text-center align-middle py-1 px-0">
                                            <?= ($raw_score == $i) ? '<span class="badge bg-success rounded-circle p-1" style="font-size:0.65rem;">✓</span>' : '<span class="text-muted" style="font-size:0.7rem;">-</span>' ?>
                                        </td>
                                    <?php endfor; ?>
                                    <td class="text-center align-middle fw-bold text-primary py-1 px-1">
                                        <?= isset($calculatedScores[$item['ri_id']]) ? number_format($calculatedScores[$item['ri_id']], 2) : '0.00' ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: ส่วนที่ 2 -->
        <div class="tab-pane fade" id="part2-content" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle mb-0" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="py-1">ลักษณะงานตามมาตรฐานตำแหน่ง</th>
                            <th class="text-center py-1" style="width: 35px;">1</th>
                            <th class="text-center py-1" style="width: 35px;">2</th>
                            <th class="text-center py-1" style="width: 35px;">3</th>
                            <th class="text-center py-1" style="width: 35px;">4</th>
                            <th class="text-center py-1" style="width: 75px;">คะแนน</th>
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
                                    <tr class="table-secondary py-0">
                                        <td colspan="6" class="fw-bold text-dark py-1" style="font-size: 0.8rem;"><i
                                                class="bx bx-folder me-1 text-success"></i><?= esc($item['ri_domain']); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="py-1 px-2">
                                        <span class="fw-bold text-dark me-1"><?= esc($item['ri_item_number']); ?></span>
                                        <span class="text-dark"><?= esc($item['ri_item_description']); ?></span>
                                    </td>
                                    <?php
                                    $raw_score = $itemScores[$item['ri_id']] ?? 0;
                                    for ($i = 1; $i <= 4; $i++): ?>
                                        <td class="text-center align-middle py-1 px-0">
                                            <?= ($raw_score == $i) ? '<span class="badge bg-success rounded-circle p-1" style="font-size:0.65rem;">✓</span>' : '<span class="text-muted" style="font-size:0.7rem;">-</span>' ?>
                                        </td>
                                    <?php endfor; ?>
                                    <td class="text-center align-middle fw-bold text-success py-1 px-1">
                                        <?= isset($calculatedScores[$item['ri_id']]) ? number_format($calculatedScores[$item['ri_id']], 2) : '0.00' ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<?php endif; ?>