<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">แบบฟอร์ม /</span> แบบประเมิน PA</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">แบบประเมินผลการพัฒนางานตามข้อตกลง (PA)</h5>
            <div class="card-body">
                <form action="<?= base_url('user/pa-evaluation/save'); ?>" method="post">
                    <p>สำหรับข้าราชการครูและบุคลากรทางการศึกษา ตำแหน่ง:
                        <strong><?= esc($person['posi_name']); ?></strong> (วิทยฐานะ:
                        <strong><?= empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic']); ?></strong>)
                    </p>

                    <?php if (empty($rubricItems)): ?>
                    <div class="alert alert-danger text-center mt-4" role="alert">
                        <h4 class="alert-heading">ไม่พบหัวข้อการประเมิน!</h4>
                        <p>ไม่พบหัวข้อการประเมินสำหรับตำแหน่งและวิทยฐานะนี้</p>
                        <hr>
                        <p class="mb-0">กรุณาติดต่อผู้ดูแลระบบเพื่อทำการตั้งค่าหัวข้อการประเมินให้ถูกต้อง</p>
                    </div>
                    <?php else: ?>

                    <hr class="my-4">

                    <!-- Section 1: Evaluated Person's Info -->
                    <h6>ข้อมูลทั่วไป</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="teacherName" class="form-label">ชื่อผู้รับการประเมิน</label>
                            <input type="text" class="form-control" id="teacherName"
                                value="<?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']); ?>"
                                readonly>
                            <input type="hidden" name="person_id" value="<?= esc($person['pers_id']); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="teacherPosition" class="form-label">ตำแหน่ง</label>
                            <input type="text" class="form-control" id="teacherPosition"
                                value="<?= esc($person['posi_name']); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="academicStanding" class="form-label">วิทยฐานะ</label>
                            <input type="text" class="form-control" id="academicStanding"
                                value="<?= empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic']); ?>"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="evaluationPeriod" class="form-label">รอบการประเมิน</label>
                            <select id="evaluationPeriod" name="evaluationPeriod" class="form-select">
                                <option selected>เลือกรอบ...</option>
                                <option value="1" selected>รอบการประเมิน (1 ตุลาคม 2567 - 30 กันยายน 2568)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="academicYear" class="form-label">ปีการศึกษา</label>
                            <input type="text" class="form-control" id="academicYear" name="academicYear"
                                placeholder="ระบุปีการศึกษา" value="<?= date('Y') + 543; ?>">
                        </div>
                    </div>

                    <hr class="my-5">

                    <!-- Section 2: Performance Agreement Part 1 -->
                    <h6>ส่วนที่ 1 ข้อตกลงในการพัฒนางานตามมาตรฐานกำหนดตำแหน่ง (60 คะแนน)</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <?php 
                                     $de = "ลักษณะงานที่ปฏิบัติตามมาตรฐานกำหนดตำแหน่ง ระดับการปฏิบัติที่คาดหวัง ริเริ่ม พัฒนา (Originate & Improve)";
                                        $NoExpertise ="ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง ระดับการปฏิบัติที่คาดหวัง ปรับประยุกต์ (Apply & Adapt)";
                                        $Expertise = "ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง ระดับการปฏิบัติที่คาดหวัง แก้ไขปัญหา (Solve the Problem)";    
                                        $SpecialExpertise = "ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง ระดับการปฏิบัติที่คาดหวัง ริเริ่ม พัฒนา (Originate & Improve)";


                                    if($person['posi_name'] == "ผู้อำนวยการสถานศึกษา" || $person['posi_name'] == "รองผู้อำนวยการสถานศึกษา" && $person['pers_academic'] == "ชำนาญการพิเศษ"){
                                            echo "<th rowspan='2' style='vertical-align: middle;'>$de</th>";
                                        }elseif(($person['posi_name'] == "ครูผู้ช่วย" || $person['posi_name'] == "ครู") && empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic'])){
                                            echo "<th rowspan='2' style='vertical-align: middle;'>$NoExpertise</th>";
                                        }elseif($person['posi_name'] == "ครู" && $person['pers_academic'] == "ชำนาญการ"){
                                            echo "<th rowspan='2' style='vertical-align: middle;'>$Expertise</th>";
                                        }elseif($person['posi_name'] == "ครู" && $person['pers_academic'] == "ชำนาญการพิเศษ" ){
                                            echo "<th rowspan='2' style='vertical-align: middle;'>$SpecialExpertise</th>";
                                        }
                                       
                                        
                                    ?>
                                    <th colspan="4" class="text-center">ระดับผลการประเมิน </th>
                                    <th rowspan="2" style="vertical-align: middle;">คะแนนที่ได้</th>
                                </tr>
                                <tr>
                                    <th class="text-center">1<br>ปฏิบัติได้<br>ต่ำกว่าระดับฯ<br>ที่คาดหวังมาก</th>
                                    <th class="text-center">2<br>ปฏิบัติได้<br>ต่ำกว่าระดับฯ<br>ที่คาดหวัง</th>
                                    <th class="text-center">3<br>ปฏิบัติได้<br>ตามระดับฯ<br>ที่คาดหวัง</th>
                                    <th class="text-center">4<br>ปฏิบัติได้<br>สูงกว่าระดับฯ<br>ที่คาดหวัง</th>
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
                                <tr>
                                    <td colspan="6" class="table-light"><strong><?= esc($item['ri_domain']); ?></strong>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>
                                        <?= esc($item['ri_item_number']); ?> <?= esc($item['ri_item_description']); ?>
                                        <?php if (!empty($item['ri_expected_level_description'])): ?>
                                        <div class="mt-2 text-muted small">
                                            <strong>ระดับที่คาดหวัง:</strong>
                                            <?= esc($item['ri_expected_level_description']); ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="1" data-part="1"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 1) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="2" data-part="1"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 2) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="3" data-part="1"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 3) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="4" data-part="1"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 4) ? 'checked' : ''; ?>>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" min="0" max="4"
                                            data-part="1" readonly name="points_<?= esc($item['ri_id']); ?>"
                                            value="<?= isset($itemScores[$item['ri_id']]) ? esc($itemScores[$item['ri_id']]) : ''; ?>">
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">ไม่พบหัวข้อการประเมินสำหรับส่วนที่ 1</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-5">

                    <!-- Section 2: Performance Agreement Part 2 -->
                    <h6>ส่วนที่ 2 ข้อตกลงในการพัฒนางานที่เป็นประเด็นท้าทาย (40 คะแนน)</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">ลักษณะงานที่ปฏิบัติตามมาตรฐานตำแหน่ง
                                        ระดับการปฏิบัติที่คาดหวัง
                                        ปรับประยุกต์ <br>(Apply & Adapt)
                                    </th>
                                    <th colspan="4" class="text-center">ระดับผลการประเมิน</th>
                                    <th rowspan="2" style="vertical-align: middle;">คะแนนที่ได้</th>
                                </tr>
                                <tr>
                                    <th class="text-center">1<br>ปฏิบัติได้<br>ต่ำกว่าระดับฯ<br>ที่คาดหวังมาก</th>
                                    <th class="text-center">2<br>ปฏิบัติได้<br>ต่ำกว่าระดับฯ<br>ที่คาดหวัง</th>
                                    <th class="text-center">3<br>ปฏิบัติได้<br>ตามระดับฯ<br>ที่คาดหวัง</th>
                                    <th class="text-center">4<br>ปฏิบัติได้<br>สูงกว่าระดับฯ<br>ที่คาดหวัง</th>
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
                                <tr>
                                    <td colspan="6" class="table-light"><strong><?= esc($item['ri_domain']); ?></strong>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>
                                        <?= esc($item['ri_item_number']); ?> <?= esc($item['ri_item_description']); ?>
                                        <?php if (!empty($item['ri_expected_level_description'])): ?>
                                        <div class="mt-2 text-muted small">
                                            <strong>ระดับที่คาดหวัง:</strong>
                                            <?= esc($item['ri_expected_level_description']); ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="1" data-part="2"
                                            data-item-number="<?= esc($item['ri_item_number']); ?>"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 1) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="2" data-part="2"
                                            data-item-number="<?= esc($item['ri_item_number']); ?>"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 2) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="3" data-part="2"
                                            data-item-number="<?= esc($item['ri_item_number']); ?>"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 3) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="text-center"><input class="form-check-input" type="radio"
                                            name="raw_score_<?= esc($item['ri_id']); ?>" value="4" data-part="2"
                                            data-item-number="<?= esc($item['ri_item_number']); ?>"
                                            <?= (isset($rawItemScores[$item['ri_id']]) && $rawItemScores[$item['ri_id']] == 4) ? 'checked' : ''; ?>>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" min="0" max="20"
                                            data-part="2" data-item-number="<?= esc($item['ri_item_number']); ?>"
                                            readonly name="points_<?= esc($item['ri_id']); ?>"
                                            value="<?= isset($itemScores[$item['ri_id']]) ? esc($itemScores[$item['ri_id']]) : ''; ?>">
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">ไม่พบหัวข้อการประเมินสำหรับส่วนที่ 2</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-5">

                    <!-- Section 3: Challenging Issue -->
                    <p><strong>สรุปข้อสังเกตเกี่ยวกับ จุดเด่น จุดที่ควรพัฒนา และข้อคิดเห็น</strong></p>
                    <div class="mb-3">
                        <label for="challengeDescription" class="form-label">จุดเด่น</label>
                        <textarea class="form-control" id="challengeDescription" name="challengeDescription" rows="3"
                            placeholder="อธิบายประเด็นท้าทายในการพัฒนางาน"><?= isset($evaluatorScore['es_strong_points']) ? esc($evaluatorScore['es_strong_points']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="challengeResult" class="form-label">จุดที่ควรพัฒนา</label>
                        <textarea class="form-control" id="challengeResult" name="challengeResult" rows="3"
                            placeholder="อธิบายผลลัพธ์เชิงปริมาณและคุณภาพ"><?= isset($evaluatorScore['es_areas_for_improvement']) ? esc($evaluatorScore['es_areas_for_improvement']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comments" class="form-label">ข้อคิดเห็น</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3"
                            placeholder="ระบุข้อคิดเห็น"><?= isset($evaluatorScore['es_comments']) ? esc($evaluatorScore['es_comments']) : ''; ?></textarea>
                    </div>


                    <hr class="my-5">

                    <!-- Section 4: Summary & Signatures -->
                    <h6>ส่วนที่ 3: สรุปผลการประเมิน</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>รวมคะแนนตอนที่ 1:</strong> <span
                                    id="totalScore1"><?= isset($evaluatorScore['es_part1_score']) ? esc($evaluatorScore['es_part1_score']) : '0'; ?></span>
                                / 80</p>
                            <input type="hidden" name="totalScore1"
                                value="<?= isset($evaluatorScore['es_part1_score']) ? esc($evaluatorScore['es_part1_score']) : '0'; ?>">
                        </div>
                        <div class="col-md-4">
                            <p><strong>รวมคะแนนตอนที่ 2:</strong> <span
                                    id="totalScore2"><?= isset($evaluatorScore['es_part2_score']) ? esc($evaluatorScore['es_part2_score']) : '0'; ?></span>
                                / 20</p>
                            <input type="hidden" name="totalScore2"
                                value="<?= isset($evaluatorScore['es_part2_score']) ? esc($evaluatorScore['es_part2_score']) : '0'; ?>">
                        </div>
                        <div class="col-md-4">
                            <p><strong>รวมคะแนนทั้งหมด:</strong> <span
                                    id="totalScore"><?= isset($evaluatorScore['es_total_score']) ? esc($evaluatorScore['es_total_score']) : '0'; ?></span>
                                / 100</p>
                            <input type="hidden" name="totalScore"
                                value="<?= isset($evaluatorScore['es_total_score']) ? esc($evaluatorScore['es_total_score']) : '0'; ?>">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="evaluatorName" class="form-label">ชื่อผู้ประเมิน</label>
                            <?php if ($evaluator): ?>
                            <input type="text" class="form-control" id="evaluatorName" placeholder="ระบุชื่อผู้ประเมิน"
                                value="<?= esc($evaluator['e_first_name'] . ' ' . $evaluator['e_last_name']); ?>"
                                readonly>
                            <input type="hidden" name="evaluator_id" value="<?= esc($evaluator['e_id']); ?>">
                            <?php else: ?>
                            <input type="text" class="form-control" id="evaluatorName"
                                placeholder="ไม่พบข้อมูลผู้ประเมิน" value="ไม่พบผู้ประเมิน" readonly>
                            <input type="hidden" name="evaluator_id" value="">
                            <div class="text-danger mt-1">ไม่พบข้อมูลผู้ประเมิน
                                กรุณาเข้าสู่ระบบใหม่หรือติดต่อผู้ดูแลระบบ</div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="mt-5 text-center">
                        <button type="submit" class="btn btn-primary">บันทึกแบบประเมิน</button>
                        <button type="button" class="btn btn-label-secondary">ยกเลิก</button>
                    </div>

                    <?php endif; ?>

                </form>
            </div>
        </div>
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
            const score = parseFloat(input.value) || 0; // Use parseFloat for decimal scores
            const part = input.dataset.part;

            if (part == 1) {
                part1Total += score;
            } else if (part == 2) {
                part2Total += score;
            }
        });

        // Update total scores for display and hidden inputs
        document.getElementById('totalScore1').textContent = part1Total;
        document.querySelector('input[name="totalScore1"]').value = part1Total;

        document.getElementById('totalScore2').textContent = part2Total;
        document.querySelector('input[name="totalScore2"]').value = part2Total;

        const totalScore = part1Total + part2Total;
        document.getElementById('totalScore').textContent = totalScore;
        document.querySelector('input[name="totalScore"]').value = totalScore;
    }

    scoreRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const itemId = this.name.replace('raw_score_', '');
            const pointsInput = document.querySelector(`input[name="points_${itemId}"]`);
            const selectedValue = parseInt(this.value);
            const part = this.dataset.part;
            const itemNumber = this.dataset.itemNumber; // Get the item number

            if (pointsInput) {
                let calculatedScore = 0;
                if (part == 1) {
                    calculatedScore = selectedValue; // Part 1 uses direct 1-4 scoring
                } else if (part == 2) {
                    if (itemNumber === '2.1' || itemNumber === '2.2') {
                        // Scoring for 2.1 and 2.2: 4=10, 3=7.50, 2=5, 1=2.50
                        switch (selectedValue) {
                            case 1:
                                calculatedScore = 2.50;
                                break;
                            case 2:
                                calculatedScore = 5;
                                break;
                            case 3:
                                calculatedScore = 7.50;
                                break;
                            case 4:
                                calculatedScore = 10;
                                break;
                            default:
                                calculatedScore = 0;
                        }
                    } else {
                        // Default scoring for other Part 2 items: 4=20, 3=15, 2=10, 1=5
                        switch (selectedValue) {
                            case 1:
                                calculatedScore = 5;
                                break;
                            case 2:
                                calculatedScore = 10;
                                break;
                            case 3:
                                calculatedScore = 15;
                                break;
                            case 4:
                                calculatedScore = 20;
                                break;
                            default:
                                calculatedScore = 0;
                        }
                    }
                }
                pointsInput.value = calculatedScore;
                calculateTotalScores(); // Recalculate totals when a radio button changes
            }
        });
    });

    // Initial calculation when the page loads
    calculateTotalScores();

    function validateRadioButtons() {
        let isValid = true;
        const rubricItems = document.querySelectorAll('input[type="radio"][name^="raw_score_"]');
        const uniqueNames = new Set();
        let firstUncheckedElement = null; // To store the first element that is not checked

        // Collect unique radio button group names
        rubricItems.forEach(radio => {
            uniqueNames.add(radio.name);
        });

        for (const name of uniqueNames) { // Use for...of to allow breaking early
            const radiosInGroup = document.querySelectorAll(`input[type="radio"][name="${name}"]`);
            let isGroupChecked = false;
            for (const radio of radiosInGroup) {
                if (radio.checked) {
                    isGroupChecked = true;
                    break; // Found a checked radio in this group, move to next group
                }
            }

            if (!isGroupChecked) {
                isValid = false;
                if (!firstUncheckedElement) {
                    // Find the parent <tr> or a suitable container to scroll to
                    // This might need adjustment based on your exact HTML structure
                    firstUncheckedElement = radiosInGroup[0].closest('tr');
                }
                // No need to break here, continue checking other groups if desired,
                // but we only scroll to the first one.
            }
        }

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลไม่ครบถ้วน!',
                text: 'กรุณาเลือกคะแนนสำหรับทุกข้อการประเมิน',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                if (firstUncheckedElement) {
                    firstUncheckedElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    // Optionally, add a temporary visual highlight
                    firstUncheckedElement.style.backgroundColor = '#fff3cd'; // Light yellow background
                    setTimeout(() => {
                        firstUncheckedElement.style.backgroundColor =
                            ''; // Remove highlight after a short delay
                    }, 3000);
                }
            });
        }
        return isValid;
    }

    // Handle form submission via AJAX
    const paForm = document.querySelector('form');
    if (paForm) {
        paForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent default form submission

            // Perform validation before submitting
            if (!validateRadioButtons()) {
                return; // Stop submission if validation fails
            }

            const formData = new FormData(paForm);
            // You might want to add a loading indicator here

            try {
                const response = await fetch(paForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Indicate AJAX request
                    }
                });

                const result = await response.json(); // Assuming controller returns JSON

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ!',
                        text: result.message || 'ข้อมูลการประเมินถูกบันทึกเรียบร้อยแล้ว',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        // Optionally, you can reset the form here if needed
                        // paForm.reset();
                        // Or do nothing and stay on the page
                    });
                } else {
                    console.error('Server error:', result.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: result.message || 'ไม่สามารถบันทึกข้อมูลการประเมินได้',
                        confirmButtonText: 'ตกลง'
                    });
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + error.message);
            } finally {
                // Remove loading indicator here
            }
        });
    }
});
</script>
<?= $this->endSection() ?>