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
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #025588 0%, #01345b 100%); color: white; border-radius: 16px;">
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
                        <th class="text-nowrap fw-bold text-dark" style="width: 220px;">ชื่อ-นามสกุล</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 130px;">กลุ่มสาระ</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 110px;">ตำแหน่ง</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 120px;">วิทยฐานะ</th>
                        <th class="text-nowrap fw-bold text-dark" style="width: 130px;">สถานะ</th>
                        <th style="width: 120px;" class="text-center text-nowrap fw-bold text-dark">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($personnel) && count($personnel) > 0): ?>
                        <?php $index = 1; ?>
                        <?php foreach ($personnel as $person): ?>
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
                                <td class="text-nowrap">
                                    <?php if ($person['has_pa_evaluation']): ?>
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1"><i class="bx bx-check-circle me-1"></i>ประเมินแล้ว</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-1"><i class="bx bx-time me-1"></i>ยังไม่ประเมิน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <?php if ($person['has_pa_evaluation']): ?>
                                        <a href="<?= base_url('pa-form/' . $person['pers_id']); ?>" class="btn btn-sm btn-success text-white fw-bold rounded-pill px-3 shadow-sm">
                                            <i class="bx bx-edit me-1"></i> ดู / แก้ไข
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('pa-form/' . $person['pers_id']); ?>" class="btn btn-sm btn-primary text-white fw-bold rounded-pill px-3 shadow-sm">
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
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
});
</script>
<?= $this->endSection() ?>