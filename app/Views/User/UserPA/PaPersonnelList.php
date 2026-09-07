<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<?php
    $total_count = count($personnel ?? []);
    $completed_count = 0;
    $pending_count = 0;
    foreach ($personnel ?? [] as $p) {
        if (!empty($p['has_pa_evaluation'])) {
            $completed_count++;
        } else {
            $pending_count++;
        }
    }

    $current_month = (int)date('m');
    $current_year_ad = (int)date('Y');
    $current_fiscal_year = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;
    $selected_year = isset($_GET['fiscal_year']) ? (int)$_GET['fiscal_year'] : $current_fiscal_year;
?>

<!-- Header Banner -->
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #0369a1 100%); color: white; border-radius: 16px;">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-white text-dark rounded-pill mb-2 px-3 fw-bold" style="font-size: 0.8rem;">
                    <i class="bx bx-award me-1 text-primary"></i>PA EVALUATION SYSTEM
                </span>
                <h3 class="fw-bold text-white mb-1"><i class="bx bx-user-check me-2"></i>รายชื่อบุคลากรผู้รับการประเมิน PA</h3>
                <p class="text-white mb-0 fs-6" style="opacity: 0.95;">เลือกรายชื่อบุคลากรในขอบเขตการประเมินของคุณเพื่อทำแบบประเมินผลการพัฒนางานตามข้อตกลง</p>
            </div>
            <div class="d-flex align-items-center bg-white p-2 rounded-3 shadow-sm border">
                <label for="fiscalYearSelect" class="form-label mb-0 me-2 text-dark fs-6 fw-bold text-nowrap"><i class="bx bx-calendar me-1 text-primary"></i>ปีการศึกษา:</label>
                <select id="fiscalYearSelect" class="form-select form-select-sm select2 border-0 shadow-none text-dark fw-bold" style="width: 130px; font-size: 0.95rem;" onchange="location = this.value;">
                    <?php for ($y = $current_fiscal_year + 1; $y >= $current_fiscal_year - 3; $y--): ?>
                        <option value="<?= base_url('pa-personnel?fiscal_year=' . $y) ?>" <?= $selected_year == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Stat Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border border-primary-subtle shadow-sm h-100" style="background: #f0f7ff; border-radius: 14px;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-dark fw-bold d-block mb-1 fs-6">บุคลากรในขอบเขตการประเมิน</span>
                    <h2 class="fw-bold mb-0 text-dark"><?= $total_count; ?> <span class="fs-6 text-dark fw-normal">คน</span></h2>
                </div>
                <div class="avatar avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-group fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card border border-success-subtle shadow-sm h-100" style="background: #f0fff4; border-radius: 14px;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-dark fw-bold d-block mb-1 fs-6">ประเมินเรียบร้อยแล้ว</span>
                    <h2 class="fw-bold mb-0 text-dark"><?= $completed_count; ?> <span class="fs-6 text-dark fw-normal">คน</span></h2>
                </div>
                <div class="avatar avatar-md bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-check-circle fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4">
        <div class="card border border-warning-subtle shadow-sm h-100" style="background: #fffdf0; border-radius: 14px;">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <span class="text-dark fw-bold d-block mb-1 fs-6">รอการประเมิน</span>
                    <h2 class="fw-bold mb-0 text-dark"><?= $pending_count; ?> <span class="fs-6 text-dark fw-normal">คน</span></h2>
                </div>
                <div class="avatar avatar-md bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-time-five fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header border-0 d-flex justify-content-between align-items-center py-3" style="background: #025588; color: white; border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <h4 class="card-title mb-0 fw-bold text-white fs-5"><i class="bx bx-list-ol me-2 text-warning"></i>ตารางรายชื่อบุคลากรประจำปี <?= esc($selected_year); ?></h4>
        <span class="badge bg-white text-dark rounded-pill px-3 py-2 fs-6 fw-bold"><i class="bx bx-group me-1 text-primary"></i><?= $total_count; ?> รายการ</span>
    </div>
    <div class="card-body pt-3">
        <div class="table-responsive">
            <table id="personnel-table" class="table table-hover align-middle w-100">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th style="width: 40px;" class="text-nowrap text-center fw-bold text-dark">#</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 200px;">ชื่อ-นามสกุล</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 120px;">กลุ่มสาระ</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 100px;">ตำแหน่ง</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 110px;">วิทยฐานะ</th>
                        <th class="text-nowrap fw-bold text-dark text-center" style="width: 180px;">ข้อมูลข้อตกลง PA ของครู</th>
                        <th class="text-nowrap fw-bold text-dark text-center" style="width: 110px;">สถานะประเมิน</th>
                        <th style="width: 120px;" class="text-center text-nowrap fw-bold text-dark">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($personnel) && count($personnel) > 0): ?>
                        <?php $index = 1; ?>
                        <?php foreach ($personnel as $person): ?>
                            <?php 
                                $agreement = $person['pa_agreement'] ?? null;
                                $has_pres_file = !empty($agreement['pa_file_presentation']);
                                $has_pres_link = !empty($agreement['pa_presentation_link']);
                                $has_pres = $has_pres_file || $has_pres_link;
                                $has_plan = !empty($agreement['pa_file_lesson_plan']);
                                $has_pa1  = !empty($agreement['pa_file_pa1']);
                                
                                $pres_file_name = $agreement['pa_file_presentation'] ?? '';
                                $raw_pres = trim($agreement['pa_presentation_link'] ?? '', " \t\n\r\0\x0B\"'");
                                $pres_url = '';
                                $pres_type = '';
                                $pres_display = '';

                                if ($has_pres_file) {
                                    $pres_type = 'uploaded_file';
                                    $pres_url = $pa_upload_baseurl . $selected_year . '/presentation/' . $pres_file_name;
                                    $pres_display = $pres_file_name;
                                } elseif ($has_pres_link) {
                                    $pres_display = $raw_pres;
                                    if (preg_match('#^([a-zA-Z]:[\\\\/]|file:///)#i', $raw_pres)) {
                                        $pres_type = 'local_file';
                                        $pres_url = 'local_file';
                                    } elseif (preg_match('#^(https?://|www\.|canva\.com|drive\.google\.com|docs\.google\.com|onedrive|sharepoint|youtu)#i', $raw_pres)) {
                                        $pres_type = 'web_link';
                                        $pres_url = preg_match('#^https?://#i', $raw_pres) ? $raw_pres : ('https://' . $raw_pres);
                                    } elseif (preg_match('#\.(pptx?|pdf|zip|rar|key|mp4|mov|doc|docx|xlsx)$#i', $raw_pres)) {
                                        $clean_name = basename(str_replace('\\', '/', $raw_pres));
                                        $pres_type = 'uploaded_file';
                                        $pres_url = $pa_upload_baseurl . $selected_year . '/presentation/' . $clean_name;
                                    } else {
                                        $pres_type = 'web_link';
                                        $pres_url = preg_match('#^https?://#i', $raw_pres) ? $raw_pres : ('https://' . $raw_pres);
                                    }
                                }

                                $plan_url = $has_plan ? ($pa_upload_baseurl . $selected_year . '/lesson_plan/' . $agreement['pa_file_lesson_plan']) : '';
                                $pa1_url  = $has_pa1 ? ($pa_upload_baseurl . $selected_year . '/pa1/' . $agreement['pa_file_pa1']) : '';
                            ?>
                            <tr>
                                <td class="text-center text-nowrap fw-semibold text-dark"><?= $index++; ?></td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2 flex-shrink-0" style="width:32px; height:32px;">
                                            <?php if (!empty($person['pers_img']) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $person['pers_img'])): ?>
                                                <img src="<?= base_url('uploads/admin/Personnal/' . $person['pers_img']) ?>" alt="Avatar" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                            <?php else: ?>
                                                <div class="w-100 h-100 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold small">
                                                    <?= mb_substr($person['pers_firstname'] ?? 'ครู', 0, 1, 'UTF-8'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark text-nowrap d-block">
                                                <?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-info text-white text-nowrap px-2 py-1">
                                        <?= esc(str_replace('กลุ่มสาระการเรียนรู้', '', $person['lear_namethai'] ?? '-')); ?>
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-secondary text-white text-nowrap px-2 py-1"><?= esc($person['posi_name'] ?? '-'); ?></span>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-dark text-white text-nowrap px-2 py-1">
                                        <?= empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic']); ?>
                                    </span>
                                </td>
                                <!-- คอลัมน์ข้อมูลข้อตกลง PA ของครู -->
                                <td class="text-center text-nowrap">
                                    <?php if ($agreement && ($has_pres || $has_plan || $has_pa1)): ?>
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <!-- สื่อนำเสนอ (รองรับทั้งลิงก์ออนไลน์ Canva/Drive และไฟล์ที่อัปโหลด) -->
                                            <?php if ($has_pres && $pres_type === 'local_file'): ?>
                                                <button type="button" class="btn btn-xs btn-outline-warning px-2 py-1 rounded shadow-none text-dark" onclick="Swal.fire('แจ้งเตือน', 'ครูแนบเป็นพาธไฟล์ในเครื่องคอมพิวเตอร์ส่วนตัว (<?= esc(addslashes($pres_display)) ?>)', 'warning')" title="ครูแนบเป็นพาธไฟล์ในเครื่อง" data-bs-toggle="tooltip">
                                                    <i class="bx bx-error fs-6 me-1 text-warning"></i>สื่อ (ในเครื่อง)
                                                </button>
                                            <?php elseif ($has_pres && $pres_type === 'uploaded_file'): ?>
                                                <a href="<?= esc($pres_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-primary px-2 py-1 rounded shadow-none" title="ดาวน์โหลดไฟล์สื่อนำเสนอ (<?= esc($pres_display) ?>)" data-bs-toggle="tooltip" download>
                                                    <i class="bx bx-download fs-6 me-1"></i>สื่อ (ไฟล์)
                                                </a>
                                            <?php elseif ($has_pres && !empty($pres_url)): ?>
                                                <a href="<?= esc($pres_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-primary px-2 py-1 rounded shadow-none" title="เปิดดูสื่อนำเสนอออนไลน์ (Canva / Slides / Drive)" data-bs-toggle="tooltip">
                                                    <i class="bx bx-slideshow fs-6 me-1"></i>สื่อ (ลิงก์)
                                                </a>
                                            <?php else: ?>
                                                <span class="btn btn-xs btn-light text-muted px-2 py-1 rounded border opacity-50" title="ยังไม่แนบสื่อนำเสนอ" data-bs-toggle="tooltip">
                                                    <i class="bx bx-slideshow fs-6 me-1"></i>-
                                                </span>
                                            <?php endif; ?>

                                            <!-- แผนการสอน -->
                                            <?php if ($has_plan): ?>
                                                <a href="<?= esc($plan_url); ?>" target="_blank" class="btn btn-xs btn-outline-info px-2 py-1 rounded shadow-none" title="คลิกเพื่อดู/ดาวน์โหลดไฟล์แผนการสอน (PDF)" data-bs-toggle="tooltip">
                                                    <i class="bx bx-book-open fs-6 me-1"></i>แผน
                                                </a>
                                            <?php else: ?>
                                                <span class="btn btn-xs btn-light text-muted px-2 py-1 rounded border opacity-50" title="ยังไม่แนบแผนการสอน" data-bs-toggle="tooltip">
                                                    <i class="bx bx-book-open fs-6 me-1"></i>-
                                                </span>
                                            <?php endif; ?>

                                            <!-- บันทึกข้อตกลง PA1 -->
                                            <?php if ($has_pa1): ?>
                                                <a href="<?= esc($pa1_url); ?>" target="_blank" class="btn btn-xs btn-outline-danger px-2 py-1 rounded shadow-none" title="คลิกเพื่อดู/ดาวน์โหลดแบบข้อตกลง PA1 (PDF)" data-bs-toggle="tooltip">
                                                    <i class="bx bxs-file-pdf fs-6 me-1"></i>PA1
                                                </a>
                                            <?php else: ?>
                                                <span class="btn btn-xs btn-light text-muted px-2 py-1 rounded border opacity-50" title="ยังไม่แนบไฟล์ PA1" data-bs-toggle="tooltip">
                                                    <i class="bx bxs-file-pdf fs-6 me-1"></i>-
                                                </span>
                                            <?php endif; ?>

                                            <!-- ปุ่ม Modal ดูรายละเอียดทั้งหมด -->
                                            <button type="button" class="btn btn-xs btn-warning text-dark px-2 py-1 rounded fw-bold btn-view-pa-modal" 
                                                data-name="<?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']); ?>"
                                                data-posi="<?= esc($person['posi_name'] ?? '-'); ?>"
                                                data-academic="<?= empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : esc($person['pers_academic']); ?>"
                                                data-pres="<?= esc($pres_url); ?>"
                                                data-pres-type="<?= esc($pres_type); ?>"
                                                data-pres-name="<?= esc($pres_display); ?>"
                                                data-plan="<?= esc($plan_url); ?>"
                                                data-plan-name="<?= esc($agreement['pa_file_lesson_plan'] ?? ''); ?>"
                                                data-pa1="<?= esc($pa1_url); ?>"
                                                data-pa1-name="<?= esc($agreement['pa_file_pa1'] ?? ''); ?>"
                                                data-updated="<?= esc($agreement['pa_updated_at'] ?? $agreement['pa_created_at'] ?? ''); ?>"
                                                title="ดูสรุปข้อมูล PA ทั้งหมด" data-bs-toggle="tooltip">
                                                <i class="bx bx-detail"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1 fw-normal">
                                            <i class="bx bx-x me-1"></i>ยังไม่ส่งเอกสาร
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <?php if ($person['has_pa_evaluation']): ?>
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1"><i class="bx bx-check-circle me-1"></i>ประเมินแล้ว</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-1"><i class="bx bx-time me-1"></i>ยังไม่ประเมิน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <?php if ($person['has_pa_evaluation']): ?>
                                        <a href="<?= base_url('pa-form/' . $person['pers_id'] . '?fiscal_year=' . $selected_year); ?>" class="btn btn-sm btn-success text-white fw-bold rounded-pill px-3 shadow-sm">
                                            <i class="bx bx-edit me-1"></i> ดู / แก้ไข
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('pa-form/' . $person['pers_id'] . '?fiscal_year=' . $selected_year); ?>" class="btn btn-sm btn-primary text-white fw-bold rounded-pill px-3 shadow-sm">
                                            <i class="bx bx-pencil me-1"></i> ประเมิน
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal รายละเอียดข้อมูลข้อตกลง PA -->
<div class="modal fade" id="paAgreementModal" tabindex="-1" aria-labelledby="paAgreementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #025588 0%, #01345b 100%);">
                <h5 class="modal-title text-white fw-bold" id="paAgreementModalLabel">
                    <i class="bx bx-folder-open me-2 text-warning"></i>เอกสารและสื่อนำเสนอผลการพัฒนางาน (PA)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <!-- Info Header in Modal -->
                <div class="card border-0 shadow-sm p-3 mb-3 bg-white" style="border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-1 fs-5" id="modalTeacherName">-</h6>
                            <div class="text-muted small">
                                <span class="badge bg-label-primary rounded-pill me-1" id="modalTeacherPosition">-</span>
                                <span class="badge bg-label-info rounded-pill" id="modalTeacherAcademic">-</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-label-secondary rounded-pill px-3 py-2">
                                <i class="bx bx-calendar me-1"></i>ปีการศึกษา <?= esc($selected_year); ?>
                            </span>
                            <div class="text-muted" style="font-size: 0.75rem; margin-top: 4px;" id="modalUpdatedAt">-</div>
                        </div>
                    </div>
                </div>

                <!-- 3 Items List -->
                <div class="row g-3">
                    <!-- 1. สื่อนำเสนอ -->
                    <div class="col-12">
                        <div class="card border border-primary-subtle shadow-sm p-3 bg-white" style="border-radius: 12px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary text-white rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="bx bx-slideshow fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">1. ลิงก์สื่อนำเสนอผลการพัฒนางานตามข้อตกลง (PA)</h6>
                                        <small class="text-muted">เช่น สื่อ Canva, PowerPoint Online, Google Slides</small>
                                        <div id="modalPresLinkText" class="text-truncate mt-1 text-primary small" style="max-width: 380px;">-</div>
                                    </div>
                                </div>
                                <div id="modalPresAction">
                                    <span class="badge bg-light text-muted border">ไม่มีข้อมูล</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. แผนการจัดการเรียนรู้ -->
                    <div class="col-12">
                        <div class="card border border-info-subtle shadow-sm p-3 bg-white" style="border-radius: 12px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-info text-white rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="bx bx-book-open fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">2. ไฟล์แผนการจัดการเรียนรู้ (Lesson Plan)</h6>
                                        <small class="text-muted">ไฟล์เอกสารประกอบการจัดการเรียนรู้ (PDF)</small>
                                        <div id="modalPlanFileText" class="text-truncate mt-1 text-secondary small" style="max-width: 380px;">-</div>
                                    </div>
                                </div>
                                <div id="modalPlanAction">
                                    <span class="badge bg-light text-muted border">ไม่มีข้อมูล</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. บันทึกข้อตกลง PA1 -->
                    <div class="col-12">
                        <div class="card border border-danger-subtle shadow-sm p-3 bg-white" style="border-radius: 12px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-danger text-white rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                        <i class="bx bxs-file-pdf fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">3. ไฟล์บันทึกข้อตกลงในการพัฒนางาน (PA1)</h6>
                                        <small class="text-muted">แบบข้อตกลงในการพัฒนางานตามมาตรฐานตำแหน่ง (PDF)</small>
                                        <div id="modalPa1FileText" class="text-truncate mt-1 text-secondary small" style="max-width: 380px;">-</div>
                                    </div>
                                </div>
                                <div id="modalPa1Action">
                                    <span class="badge bg-light text-muted border">ไม่มีข้อมูล</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    if ($.fn.select2) {
        $('.select2').select2({
            minimumResultsForSearch: -1
        });
    }

    $('#personnel-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
        },
        "pageLength": 10
    });

    // Handle View PA Agreement Modal
    $('.btn-view-pa-modal').on('click', function() {
        const btn = $(this);
        const name = btn.data('name');
        const posi = btn.data('posi');
        const academic = btn.data('academic');
        const pres = btn.data('pres');
        const presType = btn.data('pres-type');
        const plan = btn.data('plan');
        const planName = btn.data('plan-name');
        const pa1 = btn.data('pa1');
        const pa1Name = btn.data('pa1-name');
        const updated = btn.data('updated');

        $('#modalTeacherName').text(name);
        $('#modalTeacherPosition').text(posi);
        $('#modalTeacherAcademic').text(academic);
        $('#modalUpdatedAt').text(updated ? ('อัปเดตล่าสุด: ' + updated) : '');

        // Presentation
        if (presType === 'local_file') {
            $('#modalPresLinkText').html('<span class="text-warning"><i class="bx bx-error me-1"></i>พาธไฟล์ในเครื่อง: ' + pres + '</span>');
            $('#modalPresAction').html('<span class="badge bg-label-warning text-dark border">ไฟล์ในเครื่อง (เปิดไม่ได้)</span>');
        } else if (presType === 'uploaded_file' && pres) {
            $('#modalPresLinkText').html('<i class="bx bx-file me-1"></i>' + pres);
            $('#modalPresAction').html('<a href="' + pres + '" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill btn-sm px-3 fw-bold" download><i class="bx bx-download me-1"></i>ดาวน์โหลดไฟล์สื่อ</a>');
        } else if (pres && pres.trim() !== '') {
            $('#modalPresLinkText').html('<i class="bx bx-link-external me-1"></i>' + pres);
            $('#modalPresAction').html('<a href="' + pres + '" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill btn-sm px-3 fw-bold"><i class="bx bx-link-external me-1"></i>เปิดสื่อนำเสนอออนไลน์</a>');
        } else {
            $('#modalPresLinkText').text('ยังไม่ได้ระบุลิงก์หรือไฟล์');
            $('#modalPresAction').html('<span class="badge bg-light text-muted border">ยังไม่ส่ง</span>');
        }

        // Lesson Plan
        if (plan && plan.trim() !== '') {
            $('#modalPlanFileText').html('<i class="bx bx-file me-1"></i>' + (planName || 'ไฟล์แผนการสอน'));
            $('#modalPlanAction').html('<a href="' + plan + '" target="_blank" class="btn btn-info text-white rounded-pill btn-sm px-3 fw-bold"><i class="bx bx-download me-1"></i>ดูไฟล์ PDF</a>');
        } else {
            $('#modalPlanFileText').text('ยังไม่มีการอัปโหลดไฟล์');
            $('#modalPlanAction').html('<span class="badge bg-light text-muted border">ยังไม่ส่ง</span>');
        }

        // PA1
        if (pa1 && pa1.trim() !== '') {
            $('#modalPa1FileText').html('<i class="bx bxs-file-pdf me-1"></i>' + (pa1Name || 'ไฟล์แบบข้อตกลง PA1'));
            $('#modalPa1Action').html('<a href="' + pa1 + '" target="_blank" class="btn btn-danger rounded-pill btn-sm px-3 fw-bold"><i class="bx bx-download me-1"></i>ดูไฟล์ PA1</a>');
        } else {
            $('#modalPa1FileText').text('ยังไม่มีการอัปโหลดไฟล์');
            $('#modalPa1Action').html('<span class="badge bg-light text-muted border">ยังไม่ส่ง</span>');
        }

        $('#paAgreementModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?>