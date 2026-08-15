<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Header Breadcrumb & Profile Card -->
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #025588 0%, #01345b 100%); color: white; border-radius: 16px;">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-xl me-3 flex-shrink-0" style="width: 70px; height: 70px;">
                    <?php if (!empty($person['pers_img']) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $person['pers_img'])): ?>
                        <img src="<?= base_url('uploads/admin/Personnal/' . $person['pers_img']) ?>" alt="Profile Image" class="rounded-circle w-100 h-100 border border-3 border-white shadow-sm" style="object-fit: cover;">
                    <?php else: ?>
                        <div class="w-100 h-100 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm border border-3 border-white">
                            <?= mb_substr($person['pers_firstname'] ?? 'ครู', 0, 1, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="badge bg-white text-dark rounded-pill mb-1 px-3 fw-bold" style="font-size: 0.8rem;">
                        แบบประเมินผลการพัฒนางานตามข้อตกลง (PA)
                    </span>
                    <h3 class="fw-bold text-white mb-0">
                        <?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']); ?>
                    </h3>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <span class="badge bg-white text-dark border fw-bold rounded-pill"><i class="bx bx-briefcase me-1 text-primary"></i><?= esc($person['posi_name']); ?></span>
                        <span class="badge bg-white text-dark border fw-bold rounded-pill"><i class="bx bx-award me-1 text-primary"></i><?= empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic']); ?></span>
                    </div>
                </div>
            </div>
            <div>
                <a href="<?= base_url('pa-personnel'); ?>" class="btn btn-light rounded-pill shadow-sm text-dark fw-bold btn-md">
                    <i class="bx bx-arrow-back me-1"></i>ย้อนกลับหน้ารายชื่อ
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <form action="<?= base_url('user/pa-evaluation/save'); ?>" method="post">
            <input type="hidden" name="person_id" value="<?= esc($person['pers_id']); ?>">

            <?php if (empty($rubricItems)): ?>
            <div class="alert alert-danger text-center my-4" role="alert">
                <h5 class="alert-heading fw-bold"><i class="bx bx-error-circle me-1"></i>ไม่พบหัวข้อการประเมิน!</h5>
                <p class="mb-0 text-dark">ไม่พบหัวข้อการประเมินสำหรับตำแหน่งและวิทยฐานะนี้ กรุณาติดต่อผู้ดูแลระบบเพื่อทำการตั้งค่าหัวข้อการประเมินให้ถูกต้อง</p>
            </div>
            <?php else: ?>

            <!-- Period Setup Row -->
            <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                <div class="col-md-6">
                    <label for="evaluationPeriod" class="form-label fw-bold text-dark fs-6"><i class="bx bx-calendar me-1 text-primary"></i>รอบการประเมิน</label>
                    <select id="evaluationPeriod" name="evaluationPeriod" class="form-select border shadow-none bg-white text-dark fw-bold">
                        <option value="1" selected>รอบการประเมิน (1 ตุลาคม <?= (date('Y') + 542) ?> - 30 กันยายน <?= (date('Y') + 543) ?>)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="academicYear" class="form-label fw-bold text-dark fs-6"><i class="bx bx-time me-1 text-primary"></i>ปีการศึกษาประจำแบบประเมิน</label>
                    <input type="text" class="form-control border shadow-none bg-white fw-bold text-dark fs-6" id="academicYear" name="academicYear"
                        placeholder="ระบุปีการศึกษา" value="<?= esc($fiscal_year_be ?? (date('Y') + 543)); ?>">
                </div>
            </div>

            <!-- PA Agreement Reference Documents Card -->
            <?php 
                $agreement = $paAgreement ?? null;
                $has_pres = !empty($agreement['pa_presentation_link']);
                $has_plan = !empty($agreement['pa_file_lesson_plan']);
                $has_pa1  = !empty($agreement['pa_file_pa1']);
                $plan_url = $has_plan ? ($pa_upload_baseurl . ($fiscal_year_be ?? date('Y')+543) . '/lesson_plan/' . $agreement['pa_file_lesson_plan']) : '';
                $pa1_url  = $has_pa1 ? ($pa_upload_baseurl . ($fiscal_year_be ?? date('Y')+543) . '/pa1/' . $agreement['pa_file_pa1']) : '';
            ?>
            <div class="card border border-primary-subtle shadow-sm mb-4" style="border-radius: 14px; background: #fbfdff;">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                            <i class="bx bx-folder-open fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark fs-6">
                            เอกสารและสื่อนำเสนอผลการพัฒนางานตามข้อตกลง (PA) ของผู้รับการประเมิน
                        </h6>
                    </div>
                    <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold">ประจำปีการศึกษา <?= esc($fiscal_year_be ?? (date('Y') + 543)); ?></span>
                </div>
                <div class="card-body p-3">
                    <?php if ($agreement && ($has_pres || $has_plan || $has_pa1)): ?>
                        <div class="row g-3">
                            <!-- 1. สื่อนำเสนอ -->
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between shadow-xs">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="avatar avatar-sm bg-primary text-white rounded-3 d-flex align-items-center justify-content-center me-2 flex-shrink-0">
                                            <i class="bx bx-slideshow fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">1. สื่อนำเสนอ PA</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">Canva / PowerPoint / Slides</small>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if ($has_pres): ?>
                                            <a href="<?= esc($agreement['pa_presentation_link']); ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold">
                                                <i class="bx bx-link-external me-1"></i>เปิดดูสื่อนำเสนอ
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm w-100 rounded-pill text-muted border" disabled>ยังไม่ได้แนบสื่อ</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. แผนการจัดการเรียนรู้ -->
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between shadow-xs">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="avatar avatar-sm bg-info text-white rounded-3 d-flex align-items-center justify-content-center me-2 flex-shrink-0">
                                            <i class="bx bx-book-open fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">2. แผนการจัดการเรียนรู้</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">ไฟล์เอกสารแผนการสอน (PDF)</small>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if ($has_plan): ?>
                                            <a href="<?= esc($plan_url); ?>" target="_blank" class="btn btn-outline-info btn-sm w-100 rounded-pill fw-bold">
                                                <i class="bx bx-file me-1"></i>ดูไฟล์แผนการสอน
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm w-100 rounded-pill text-muted border" disabled>ยังไม่ได้แนบแผน</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. แบบข้อตกลง PA1 -->
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 border h-100 d-flex flex-column justify-content-between shadow-xs">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="avatar avatar-sm bg-danger text-white rounded-3 d-flex align-items-center justify-content-center me-2 flex-shrink-0">
                                            <i class="bx bxs-file-pdf fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">3. แบบข้อตกลง (PA1)</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">บันทึกข้อตกลงการพัฒนางาน (PDF)</small>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if ($has_pa1): ?>
                                            <a href="<?= esc($pa1_url); ?>" target="_blank" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold">
                                                <i class="bx bxs-file-pdf me-1"></i>ดูแบบข้อตกลง PA1
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm w-100 rounded-pill text-muted border" disabled>ยังไม่ได้แนบ PA1</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-muted">
                            <i class="bx bx-info-circle fs-4 d-block mb-1 text-secondary"></i>
                            <span class="small">ผู้รับการประเมินยังไม่ได้ส่งเอกสารหรือสื่อนำเสนอในระบบบันทึกข้อมูลครู สำหรับปีการศึกษานี้</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section 1: Performance Agreement Part 1 -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-book-open text-primary me-2"></i>ส่วนที่ 1: ข้อตกลงในการพัฒนางานตามมาตรฐานกำหนดตำแหน่ง (60 คะแนน)</h5>
            </div>

            <div class="table-responsive mb-5">
                <table class="table table-hover table-bordered align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <?php 
                                $de = "ลักษณะงานที่ปฏิบัติตามมาตรฐานกำหนดตำแหน่ง";
                                $NoExpertise ="ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง";
                                $Expertise = "ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง";    
                                $SpecialExpertise = "ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง";

                            if($person['posi_name'] == "ผู้อำนวยการสถานศึกษา" || $person['posi_name'] == "รองผู้อำนวยการสถานศึกษา" && $person['pers_academic'] == "ชำนาญการพิเศษ"){
                                    echo "<th rowspan='2' class='align-middle fw-bold text-dark'>$de</th>";
                                }elseif(($person['posi_name'] == "ครูผู้ช่วย" || $person['posi_name'] == "ครู") && empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic'])){
                                    echo "<th rowspan='2' class='align-middle fw-bold text-dark'>$NoExpertise</th>";
                                }elseif($person['posi_name'] == "ครู" && $person['pers_academic'] == "ชำนาญการ"){
                                    echo "<th rowspan='2' class='align-middle fw-bold text-dark'>$Expertise</th>";
                                }elseif($person['posi_name'] == "ครู" && $person['pers_academic'] == "ชำนาญการพิเศษ" ){
                                    echo "<th rowspan='2' class='align-middle fw-bold text-dark'>$SpecialExpertise</th>";
                                } else {
                                    echo "<th rowspan='2' class='align-middle fw-bold text-dark'>ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง</th>";
                                }
                            ?>
                            <th colspan="4" class="text-center py-2 fw-bold text-dark">ระดับผลการประเมิน</th>
                            <th rowspan="2" class="text-center align-middle fw-bold text-dark" style="width: 100px;">คะแนน</th>
                        </tr>
                        <tr>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">1<br><span class="text-dark fw-normal" style="font-size:0.75rem;">ต่ำกว่ามาก</span></th>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">2<br><span class="text-dark fw-normal" style="font-size:0.75rem;">ต่ำกว่า</span></th>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">3<br><span class="text-dark fw-normal" style="font-size:0.75rem;">ตามคาดหวัง</span></th>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">4<br><span class="text-dark fw-normal" style="font-size:0.75rem;">สูงกว่า</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentPart1Domain = null;
                        if (!empty($rubricItems)):
                            foreach ($rubricItems as $item):
                                if ($item['ri_part'] == 1):
                                    if ($item['ri_domain'] !== $currentPart1Domain):
                                        $currentPart1Domain = $item['ri_domain'];
                                        ?>
                        <tr class="table-dark">
                            <td colspan="6" class="fw-bold text-white py-2"><i class="bx bx-folder me-1 text-warning"></i><?= esc($item['ri_domain']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="py-2 text-dark">
                                <span class="fw-bold text-dark me-1"><?= esc($item['ri_item_number']); ?></span>
                                <span class="text-dark fw-semibold"><?= esc($item['ri_item_description']); ?></span>
                                <?php if (!empty($item['ri_expected_level_description'])): ?>
                                <div class="mt-1 text-dark small" style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; border-left: 3px solid #0288d1;">
                                    <i class="bx bx-target-lock text-primary me-1"></i><strong class="text-dark">ระดับที่คาดหวัง:</strong>
                                    <span class="text-dark"><?= esc($item['ri_expected_level_description']); ?></span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="1" data-part="1"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 1) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="2" data-part="1"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 2) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="3" data-part="1"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 3) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="4" data-part="1"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 4) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input type="text" class="form-control form-control-sm text-center fw-bold text-dark border bg-white fs-6" min="0" max="4"
                                    data-part="1" readonly name="points_<?= esc($item['ri_id']); ?>"
                                    value="<?= isset($itemScores[$item['ri_id']]) ? esc($itemScores[$item['ri_id']]) : ''; ?>">
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-dark fw-bold">ไม่พบหัวข้อการประเมินสำหรับส่วนที่ 1</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Section 2: Performance Agreement Part 2 -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-award text-success me-2"></i>ส่วนที่ 2: ข้อตกลงในการพัฒนางานที่เป็นประเด็นท้าทาย (40 คะแนน)</h5>
            </div>

            <div class="table-responsive mb-5">
                <table class="table table-hover table-bordered align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th rowspan="2" class="align-middle fw-bold text-dark">ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง / ประเด็นท้าทาย</th>
                            <th colspan="4" class="text-center py-2 fw-bold text-dark">ระดับผลการประเมิน</th>
                            <th rowspan="2" class="text-center align-middle fw-bold text-dark" style="width: 100px;">คะแนน</th>
                        </tr>
                        <tr>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">1<br><span class="text-dark fw-normal" style="font-size:0.75rem;">ต่ำกว่ามาก</span></th>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">2<br><span class="text-dark fw-normal" style="font-size:0.75rem;">ต่ำกว่า</span></th>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">3<br><span class="text-dark fw-normal" style="font-size:0.75rem;">ตามคาดหวัง</span></th>
                            <th class="text-center py-1 fw-bold text-dark" style="width: 80px;">4<br><span class="text-dark fw-normal" style="font-size:0.75rem;">สูงกว่า</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentPart2Domain = null;
                        if (!empty($rubricItems)):
                            foreach ($rubricItems as $item):
                                if ($item['ri_part'] == 2):
                                    if ($item['ri_domain'] !== $currentPart2Domain):
                                        $currentPart2Domain = $item['ri_domain'];
                                        ?>
                        <tr class="table-dark">
                            <td colspan="6" class="fw-bold text-white py-2"><i class="bx bx-folder me-1 text-warning"></i><?= esc($item['ri_domain']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="py-2 text-dark">
                                <span class="fw-bold text-dark me-1"><?= esc($item['ri_item_number']); ?></span>
                                <span class="text-dark fw-semibold"><?= esc($item['ri_item_description']); ?></span>
                                <?php if (!empty($item['ri_expected_level_description'])): ?>
                                <div class="mt-1 text-dark small" style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; border-left: 3px solid #2e7d32;">
                                    <i class="bx bx-target-lock text-success me-1"></i><strong class="text-dark">ระดับที่คาดหวัง:</strong>
                                    <span class="text-dark"><?= esc($item['ri_expected_level_description']); ?></span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="1" data-part="2"
                                    data-item-number="<?= esc($item['ri_item_number']); ?>"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 1) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="2" data-part="2"
                                    data-item-number="<?= esc($item['ri_item_number']); ?>"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 2) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="3" data-part="2"
                                    data-item-number="<?= esc($item['ri_item_number']); ?>"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 3) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input class="form-check-input" type="radio" style="transform: scale(1.3); cursor: pointer;"
                                    name="raw_score_<?= esc($item['ri_id']); ?>" value="4" data-part="2"
                                    data-item-number="<?= esc($item['ri_item_number']); ?>"
                                    <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 4) ? 'checked' : ''; ?>>
                            </td>
                            <td class="text-center align-middle">
                                <input type="text" class="form-control form-control-sm text-center fw-bold text-dark border bg-white fs-6" min="0" max="20"
                                    data-part="2" data-item-number="<?= esc($item['ri_item_number']); ?>"
                                    readonly name="points_<?= esc($item['ri_id']); ?>"
                                    value="<?= isset($itemScores[$item['ri_id']]) ? esc($itemScores[$item['ri_id']]) : ''; ?>">
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-dark fw-bold">ไม่พบหัวข้อการประเมินสำหรับส่วนที่ 2</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Section 3: Challenging Issue & Observations -->
            <div class="card border border-secondary-subtle bg-white p-4 mb-4 rounded-3 shadow-sm">
                <h5 class="fw-bold mb-3 text-dark"><i class="bx bx-comment-detail text-primary me-2"></i>สรุปข้อสังเกตเกี่ยวกับ จุดเด่น จุดที่ควรพัฒนา และข้อคิดเห็น</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="challengeDescription" class="form-label fw-bold fs-6 text-dark"><i class="bx bx-like me-1 text-success"></i>จุดเด่น</label>
                        <textarea class="form-control shadow-none border text-dark fw-semibold" id="challengeDescription" name="challengeDescription" rows="4"
                            placeholder="ระบุจุดเด่นของผู้รับการประเมิน..."><?= isset($evaluatorScore['es_strong_points']) ? esc($evaluatorScore['es_strong_points']) : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="challengeResult" class="form-label fw-bold fs-6 text-dark"><i class="bx bx-trending-up me-1 text-warning"></i>จุดที่ควรพัฒนา</label>
                        <textarea class="form-control shadow-none border text-dark fw-semibold" id="challengeResult" name="challengeResult" rows="4"
                            placeholder="ระบุจุดที่ควรพัฒนา..."><?= isset($evaluatorScore['es_areas_for_improvement']) ? esc($evaluatorScore['es_areas_for_improvement']) : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="comments" class="form-label fw-bold fs-6 text-dark"><i class="bx bx-comment-edit me-1 text-info"></i>ข้อคิดเห็นเพิ่มเติม</label>
                        <textarea class="form-control shadow-none border text-dark fw-semibold" id="comments" name="comments" rows="4"
                            placeholder="ระบุข้อคิดเห็นเพิ่มเติม..."><?= isset($evaluatorScore['es_comments']) ? esc($evaluatorScore['es_comments']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 4: Summary Scores & Evaluator Card -->
            <div class="card border border-secondary-subtle shadow-sm p-4 mb-4" style="background: #f8fafc; border-radius: 16px;">
                <h5 class="fw-bold mb-3 text-dark"><i class="bx bx-pie-chart-alt-2 text-primary me-2"></i>สรุปผลการประเมินรวม</h5>
                <div class="row g-3 text-center mb-4">
                    <div class="col-md-4">
                        <div class="card border border-primary-subtle shadow-sm h-100 p-3 bg-white">
                            <span class="text-dark fs-6 fw-bold d-block mb-1">รวมคะแนนส่วนที่ 1</span>
                            <h2 class="fw-bold text-dark mb-0"><span id="totalScore1"><?= isset($evaluatorScore['es_part1_score']) ? esc($evaluatorScore['es_part1_score']) : '0'; ?></span> <span class="fs-6 text-dark font-normal">/ 60.00</span></h2>
                            <input type="hidden" name="totalScore1" value="<?= isset($evaluatorScore['es_part1_score']) ? esc($evaluatorScore['es_part1_score']) : '0'; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border border-success-subtle shadow-sm h-100 p-3 bg-white">
                            <span class="text-dark fs-6 fw-bold d-block mb-1">รวมคะแนนส่วนที่ 2</span>
                            <h2 class="fw-bold text-dark mb-0"><span id="totalScore2"><?= isset($evaluatorScore['es_part2_score']) ? esc($evaluatorScore['es_part2_score']) : '0'; ?></span> <span class="fs-6 text-dark font-normal">/ 40.00</span></h2>
                            <input type="hidden" name="totalScore2" value="<?= isset($evaluatorScore['es_part2_score']) ? esc($evaluatorScore['es_part2_score']) : '0'; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-white h-100 p-3" style="background: #025588;">
                            <span class="text-white fs-6 fw-bold d-block mb-1">รวมคะแนนทั้งหมด</span>
                            <h1 class="fw-bold text-white mb-0"><span id="totalScore"><?= isset($evaluatorScore['es_total_score']) ? esc($evaluatorScore['es_total_score']) : '0'; ?></span> <span class="fs-6 text-white font-normal">/ 100.00</span></h1>
                            <input type="hidden" name="totalScore" value="<?= isset($evaluatorScore['es_total_score']) ? esc($evaluatorScore['es_total_score']) : '0'; ?>">
                        </div>
                    </div>
                </div>

                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6">
                        <label for="evaluatorName" class="form-label fw-bold fs-6 text-dark"><i class="bx bx-user-check me-1 text-primary"></i>กรรมการผู้ประเมิน</label>
                        <?php if ($evaluator): ?>
                            <input type="text" class="form-control border shadow-none bg-white fw-bold text-dark fs-6" id="evaluatorName"
                                value="<?= esc($evaluator['e_first_name'] . ' ' . $evaluator['e_last_name']); ?>" readonly>
                            <input type="hidden" name="evaluator_id" value="<?= esc($evaluator['e_id']); ?>">
                        <?php else: ?>
                            <input type="text" class="form-control border shadow-none bg-white text-danger fw-bold fs-6" id="evaluatorName"
                                value="ไม่พบข้อมูลผู้ประเมิน" readonly>
                            <input type="hidden" name="evaluator_id" value="">
                            <div class="text-danger fw-bold small mt-1"><i class="bx bx-error-circle me-1"></i>ไม่พบข้อมูลผู้ประเมิน กรุณาติดต่อผู้ดูแลระบบ</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-end mt-3 mt-md-0">
                        <a href="<?= base_url('pa-personnel'); ?>" class="btn btn-outline-dark rounded-pill px-4 me-2 fw-bold">
                            <i class="bx bx-x me-1"></i>ยกเลิก
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold btn-lg">
                            <i class="bx bx-save me-1"></i>บันทึกแบบประเมิน
                        </button>
                    </div>
                </div>
            </div>

            <?php endif; ?>

        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreRadios = document.querySelectorAll('input[type="radio"][name^="raw_score_"]');
    const pointsInputs = document.querySelectorAll('input[type="text"][name^="points_"]');

    function calculateTotalScores() {
        let part1Total = 0;
        let part2Total = 0;

        pointsInputs.forEach(input => {
            const score = parseFloat(input.value) || 0;
            const part = input.dataset.part;

            if (part == 1) {
                part1Total += score;
            } else if (part == 2) {
                part2Total += score;
            }
        });

        document.getElementById('totalScore1').textContent = part1Total.toFixed(2);
        document.querySelector('input[name="totalScore1"]').value = part1Total;

        document.getElementById('totalScore2').textContent = part2Total.toFixed(2);
        document.querySelector('input[name="totalScore2"]').value = part2Total;

        const totalScore = part1Total + part2Total;
        document.getElementById('totalScore').textContent = totalScore.toFixed(2);
        document.querySelector('input[name="totalScore"]').value = totalScore;
    }

    scoreRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const itemId = this.name.replace('raw_score_', '');
            const pointsInput = document.querySelector(`input[name="points_${itemId}"]`);
            const selectedValue = parseInt(this.value);
            const part = this.dataset.part;
            const itemNumber = this.dataset.itemNumber;

            if (pointsInput) {
                let calculatedScore = 0;
                if (part == 1) {
                    calculatedScore = selectedValue;
                } else if (part == 2) {
                    if (itemNumber === '2.1' || itemNumber === '2.2') {
                        switch (selectedValue) {
                            case 1: calculatedScore = 2.50; break;
                            case 2: calculatedScore = 5; break;
                            case 3: calculatedScore = 7.50; break;
                            case 4: calculatedScore = 10; break;
                            default: calculatedScore = 0;
                        }
                    } else {
                        switch (selectedValue) {
                            case 1: calculatedScore = 5; break;
                            case 2: calculatedScore = 10; break;
                            case 3: calculatedScore = 15; break;
                            case 4: calculatedScore = 20; break;
                            default: calculatedScore = 0;
                        }
                    }
                }
                pointsInput.value = calculatedScore;
                calculateTotalScores();
            }
        });
    });

    calculateTotalScores();

    function validateRadioButtons() {
        let isValid = true;
        const rubricItems = document.querySelectorAll('input[type="radio"][name^="raw_score_"]');
        const uniqueNames = new Set();
        let firstUncheckedElement = null;

        rubricItems.forEach(radio => {
            uniqueNames.add(radio.name);
        });

        for (const name of uniqueNames) {
            const radiosInGroup = document.querySelectorAll(`input[type="radio"][name="${name}"]`);
            let isGroupChecked = false;
            for (const radio of radiosInGroup) {
                if (radio.checked) {
                    isGroupChecked = true;
                    break;
                }
            }

            if (!isGroupChecked) {
                isValid = false;
                if (!firstUncheckedElement) {
                    firstUncheckedElement = radiosInGroup[0].closest('tr');
                }
            }
        }

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลไม่ครบถ้วน!',
                text: 'กรุณาเลือกคะแนนให้ครบถ้วนทุกข้อการประเมิน',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                if (firstUncheckedElement) {
                    firstUncheckedElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstUncheckedElement.style.backgroundColor = '#fff3cd';
                    setTimeout(() => {
                        firstUncheckedElement.style.backgroundColor = '';
                    }, 3000);
                }
            });
        }
        return isValid;
    }

    const paForm = document.querySelector('form');
    if (paForm) {
        paForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            if (!validateRadioButtons()) {
                return;
            }

            const formData = new FormData(paForm);

            try {
                const response = await fetch(paForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ!',
                        text: result.message || 'ข้อมูลการประเมินถูกบันทึกเรียบร้อยแล้ว',
                        confirmButtonText: 'ตกลง'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: result.message || 'ไม่สามารถบันทึกข้อมูลการประเมินได้',
                        confirmButtonText: 'ตกลง'
                    });
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + error.message);
            }
        });
    }
});
</script>
<?= $this->endSection() ?>