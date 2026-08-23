<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR PA CONFIGURATION
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --pa-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
    }

    /* Hero Banner Card */
    .paconfig-hero-card {
        background: var(--pa-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .paconfig-hero-card::before {
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

    .paconfig-hero-card::after {
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
    .paconfig-stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .paconfig-stat-card:hover {
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

    .paconfig-stat-card:hover .stat-icon-wrap {
        transform: scale(1.1) rotate(4deg);
    }

    /* Select2 Checkbox Dropdown Styling */
    .select2-container--open {
        z-index: 9999999 !important;
    }

    .select2-checkbox-dropdown .select2-results__option[aria-selected] {
        padding-left: 34px !important;
        position: relative !important;
    }

    .select2-checkbox-dropdown .select2-results__option[aria-selected]::before {
        content: "" !important;
        position: absolute !important;
        left: 10px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 16px !important;
        height: 16px !important;
        border: 2px solid #94a3b8 !important;
        border-radius: 4px !important;
        background-color: #fff !important;
        transition: all 0.15s ease-in-out !important;
    }

    .select2-checkbox-dropdown .select2-results__option[aria-selected="true"]::before {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        background-size: 11px !important;
    }

    .select2-checkbox-dropdown .select2-results__option[aria-selected="true"] {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        font-weight: 600;
    }

    .select2-checkbox-dropdown .select2-results__option[aria-disabled="true"],
    .select2-checkbox-dropdown .select2-results__option--disabled {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        background-color: #f8fafc !important;
        color: #94a3b8 !important;
    }

    /* Scope Teacher Tag */
    .teacher-tag-item {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.15s ease;
    }

    .teacher-tag-item:hover {
        border-color: #93c5fd;
        background-color: #f0f9ff;
    }

    .teacher-tag-item .scope-delete-single {
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        margin-left: 5px;
        transition: background-color 0.15s ease;
    }

    .teacher-tag-item .scope-delete-single:hover {
        background-color: #fee2e2;
    }
</style>

<!-- Hero Banner Card -->
<div class="paconfig-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-7 col-md-6 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-cog me-1"></i> การตั้งค่าระบบประเมิน PA
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-calendar me-1"></i> ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                ตั้งค่าผู้ประเมินและขอบเขตการประเมิน PA
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                กำหนดกรรมการผู้ประเมิน และมอบหมายรายชื่อครูผู้รับการประเมินตามกลุ่มสาระการเรียนรู้ ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>
            </p>
        </div>

        <div class="col-lg-5 col-md-6 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <!-- Fiscal Year Selector Form -->
            <form method="GET" action="<?= base_url('Admin/PaConfig'); ?>" class="d-flex align-items-center">
                <div class="input-group input-group-sm shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <span class="input-group-text bg-white border-0 fw-bold text-primary" style="font-size: 0.82rem;">
                        <i class="bx bx-calendar-event me-1"></i> ปีการศึกษา
                    </span>
                    <select name="fiscal_year" id="fiscal_year" class="form-select form-select-sm border-0 fw-bold bg-white text-dark shadow-none" onchange="this.form.submit()">
                        <?php foreach ($available_years as $y): ?>
                            <option value="<?= $y; ?>" <?= ($y == $fiscal_year) ? 'selected' : ''; ?>>
                                พ.ศ. <?= $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <button class="btn btn-sm btn-white text-primary fw-bold shadow-sm px-3 py-2 bg-white" type="button" data-bs-toggle="modal" data-bs-target="#evaluatorManagerModal" style="border-radius: 10px;">
                <i class="bx bx-user-voice me-1"></i> จัดการผู้ประเมิน
            </button>
        </div>
    </div>
</div>

<!-- Quick Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="paconfig-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ผู้ประเมินที่มอบหมาย</span>
                    <h3 class="fw-bold mb-0 text-dark" data-counter="<?= count($evaluators); ?>"><?= count($evaluators); ?></h3>
                    <div class="x-small text-muted mt-1">ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?></div>
                </div>
                <div class="stat-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bx bx-user-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="paconfig-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ขอบเขตที่ตั้งค่าแล้ว</span>
                    <h3 class="fw-bold mb-0 text-success" data-counter="<?= count($assessorScopes); ?>"><?= count($assessorScopes); ?></h3>
                    <div class="x-small text-success fw-bold mt-1">รายการจับคู่ประเมิน</div>
                </div>
                <div class="stat-icon-wrap" style="background: #dcfce7; color: #16a34a;">
                    <i class="bx bx-slider-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-4">
        <div class="paconfig-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ตำแหน่งครูในระบบ</span>
                    <h3 class="fw-bold mb-0 text-warning" data-counter="<?= count($positions); ?>"><?= count($positions); ?></h3>
                    <div class="x-small text-warning fw-bold mt-1">วิทยฐานะและตำแหน่ง</div>
                </div>
                <div class="stat-icon-wrap" style="background: #fef3c7; color: #d97706;">
                    <i class="bx bx-briefcase"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inline Form Card: Add Scope -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-plus"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">เพิ่มขอบเขตการประเมินใหม่ (ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>)</h5>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?= base_url('Admin/PaConfig/save'); ?>" method="POST">
            <input type="hidden" name="scope_fiscal_year" value="<?= esc($fiscal_year); ?>">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label for="assessor_e_id" class="form-label fw-bold small text-dark"><i class="bx bx-user me-1 text-primary"></i>กรรมการผู้ประเมิน</label>
                    <select class="form-select select2 shadow-none" id="assessor_e_id" name="assessor_e_id" required>
                        <option value="">-- เลือกผู้ประเมิน --</option>
                        <?php foreach ($evaluators as $e): ?>
                            <option value="<?= esc($e['e_id']); ?>">
                                <?= esc($e['e_first_name'] . ' ' . $e['e_last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="scope_posi_id" class="form-label fw-bold small text-dark"><i class="bx bx-briefcase me-1 text-primary"></i>ตำแหน่งที่ประเมิน</label>
                    <select class="form-select select2 shadow-none" id="scope_posi_id" name="scope_posi_id">
                        <option value="">ทั้งหมด (ทุกตำแหน่ง)</option>
                        <?php foreach ($positions as $pos): ?>
                            <option value="<?= esc($pos['posi_id']); ?>">
                                <?= esc($pos['posi_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="scope_lear_id" class="form-label fw-bold small text-dark"><i class="bx bx-book-open me-1 text-primary"></i>กลุ่มสาระที่ประเมิน</label>
                    <select class="form-select select2 shadow-none" id="scope_lear_id" name="scope_lear_id">
                        <option value="">ทั้งหมด (ทุกกลุ่มสาระ)</option>
                        <?php foreach ($learningGroups as $lg): ?>
                            <option value="<?= esc($lg['lear_id']); ?>">
                                <?= esc($lg['lear_namethai']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="scope_pers_id" class="form-label fw-bold small text-dark"><i class="bx bx-user-pin me-1 text-primary"></i>ครูผู้รับการประเมิน (เลือกได้หลายคน)</label>
                    <select class="form-select select2 shadow-none" id="scope_pers_id" name="scope_pers_id[]" multiple="multiple" data-placeholder="-- ติ๊กเลือกครูข้าราชการ --">
                        <?php if (!empty($personnel)): ?>
                            <?php foreach ($personnel as $p): ?>
                                <?php 
                                    $pIdStr = (string)$p['pers_id'];
                                    $cnt = $evaluator_counts[$pIdStr] ?? 0;
                                    $badge = $cnt > 0 ? " (มีผู้ประเมิน {$cnt} ท่าน)" : "";
                                ?>
                                <option value="<?= esc($p['pers_id']); ?>">
                                    <?= esc(($p['pers_prefix'] ?? '') . $p['pers_firstname'] . ' ' . $p['pers_lastname']); ?>
                                    <?= $badge; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm fw-bold py-2" style="border-radius: 10px;">
                        <i class="bx bx-save me-1"></i> บันทึกขอบเขต
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scopes Table Card -->
<?php
    // Group scopes by evaluator and group teachers by learning group
    $groupedScopes = [];
    foreach ($assessorScopes as $scope) {
        if (empty($scope['scope_pers_id'])) continue; // Skip scopes without specific teacher
        $eId = $scope['assessor_e_id'];
        if (!isset($groupedScopes[$eId])) {
            $groupedScopes[$eId] = [
                'assessor_e_id' => $eId,
                'scope_posi_id' => $scope['scope_posi_id'],
                'scope_fiscal_year' => $scope['scope_fiscal_year'],
                'learning_groups' => [],
                'scope_ids' => [],
            ];
        }

        // Find teacher info & learning group name
        $tName = $scope['scope_pers_id'];
        $learId = 'other';
        $learName = 'ไม่ระบุกลุ่มสาระ';

        if (!empty($personnel)) {
            foreach ($personnel as $p) {
                if ($p['pers_id'] === $scope['scope_pers_id']) {
                    $tName = ($p['pers_prefix'] ?? '') . $p['pers_firstname'] . ' ' . $p['pers_lastname'];
                    $learId = !empty($p['pers_learning']) ? $p['pers_learning'] : 'other';
                    break;
                }
            }
        }

        if (!empty($learningGroups) && $learId !== 'other') {
            foreach ($learningGroups as $lg) {
                if ($lg['lear_id'] == $learId) {
                    $learName = $lg['lear_namethai'];
                    break;
                }
            }
        }

        if (!isset($groupedScopes[$eId]['learning_groups'][$learId])) {
            $groupedScopes[$eId]['learning_groups'][$learId] = [
                'name' => $learName,
                'teachers' => []
            ];
        }

        $groupedScopes[$eId]['learning_groups'][$learId]['teachers'][] = [
            'name' => $tName,
            'id' => $scope['id']
        ];
        $groupedScopes[$eId]['scope_ids'][] = $scope['id'];
    }
    $groupedScopes = array_values($groupedScopes);
?>
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-label-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-list-check"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">รายการขอบเขตการประเมินที่ตั้งค่าไว้ (ปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>)</h5>
        </div>
        <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold"><?= count($groupedScopes); ?> ผู้ประเมิน</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="ps-3">#</th>
                    <th style="min-width: 200px;">ผู้ประเมิน</th>
                    <th style="min-width: 150px;">ตำแหน่งที่ประเมิน</th>
                    <th>ครูผู้รับการประเมิน (แยกตามกลุ่มสาระ)</th>
                    <th style="width: 100px;" class="text-center pe-3">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($groupedScopes)): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($groupedScopes as $group): ?>
                        <tr>
                            <td class="ps-3 text-muted fw-bold"><?= $i++; ?></td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 bg-label-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold">
                                        <i class="bx bx-user-check"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            <?php
                                                $assessorName = 'ไม่พบข้อมูล';
                                                foreach ($evaluators as $e) {
                                                    if ($e['e_id'] === $group['assessor_e_id']) {
                                                        $assessorName = esc($e['e_first_name'] . ' ' . $e['e_last_name']);
                                                        break;
                                                    }
                                                }
                                                if ($assessorName === 'ไม่พบข้อมูล' && !empty($all_evaluators)) {
                                                    foreach ($all_evaluators as $ae) {
                                                        if ($ae['e_id'] === $group['assessor_e_id']) {
                                                            $assessorName = esc($ae['e_first_name'] . ' ' . $ae['e_last_name']);
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo $assessorName;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                    if ($group['scope_posi_id']) {
                                        $posName = 'ไม่พบข้อมูล';
                                        foreach ($positions as $pos) {
                                            if ($pos['posi_id'] === $group['scope_posi_id']) {
                                                $posName = esc($pos['posi_name']);
                                                break;
                                            }
                                        }
                                        echo '<span class="badge bg-label-info"><i class="bx bx-briefcase me-1"></i>' . $posName . '</span>';
                                    } else {
                                        echo '<span class="badge bg-label-secondary">ทุกตำแหน่ง</span>';
                                    }
                                ?>
                            </td>
                            <td style="white-space: normal; max-width: 550px;">
                                <div class="d-flex flex-column gap-2 py-1">
                                    <?php foreach ($group['learning_groups'] as $lgGroup): ?>
                                        <div class="p-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                            <div class="fw-bold small text-primary mb-2 d-flex align-items-center">
                                                <i class="bx bx-book me-1"></i><?= esc($lgGroup['name']); ?>:
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php foreach ($lgGroup['teachers'] as $teacher): ?>
                                                    <span class="teacher-tag-item">
                                                        <i class="bx bx-user-pin me-1 text-muted"></i><?= esc($teacher['name']); ?>
                                                        <a href="<?= base_url('Admin/PaConfig/delete/' . $teacher['id']); ?>" class="scope-delete-single" data-teacher-name="<?= esc($teacher['name']); ?>" title="ลบครูคนนี้ออก">
                                                            <i class="bx bx-x"></i>
                                                        </a>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="text-center pe-3">
                                <?php $deleteIds = implode(',', $group['scope_ids']); ?>
                                <a href="<?= base_url('Admin/PaConfig/deleteGroup?ids=' . $deleteIds . '&fiscal_year=' . $fiscal_year); ?>" class="btn btn-icon btn-sm btn-label-danger rounded-pill shadow-none scope-delete-group" title="ลบขอบเขตทั้งหมดของผู้ประเมินท่านนี้">
                                    <i class="bx bx-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bx bx-info-circle fs-2 d-block mb-2 text-secondary"></i>
                            <div class="fw-bold">ยังไม่มีการกำหนดครูผู้รับการประเมินรายบุคคลประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?></div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Evaluator Manager Modal -->
<div class="modal fade" id="evaluatorManagerModal" tabindex="-1" aria-labelledby="evaluatorManagerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="evaluatorManagerModalLabel">
            <i class="bx bx-user-check fs-4"></i> จัดการข้อมูลผู้ประเมิน PA (ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>)
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <span class="text-muted small"><i class="bx bx-info-circle me-1"></i>รายการผู้ประเมินประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?> (<?= count($evaluators); ?> คน)</span>
            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addEvaluatorModal">
                <i class="bx bx-plus me-1"></i> เพิ่มผู้ประเมินใหม่
            </button>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
            <thead class="table-light">
              <tr>
                <th style="width: 40px;" class="text-center">#</th>
                <th style="min-width: 160px;">ชื่อ-นามสกุล</th>
                <th>ตำแหน่ง</th>
                <th>วิทยฐานะ</th>
                <th>หน่วยงาน</th>
                <th>ชื่อผู้ใช้ (Username)</th>
                <th style="width: 130px;" class="text-center">การกระทำ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($evaluators)): ?>
                <?php $i = 1; ?>
                <?php foreach ($evaluators as $e): ?>
                  <tr>
                    <td class="text-center fw-bold text-muted"><?= $i++; ?></td>
                    <td class="text-nowrap">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs me-2 bg-label-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px; font-size:0.8rem;">
                                <i class="bx bx-user-check"></i>
                            </div>
                            <span class="fw-bold text-dark">
                                <?= esc(($e['e_first_name'] ?? '') . ' ' . ($e['e_last_name'] ?? '')); ?>
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-label-secondary" style="font-size: 0.75rem;"><?= esc(empty($e['e_position']) ? '-' : $e['e_position']); ?></span>
                    </td>
                    <td>
                        <span class="badge bg-label-success" style="font-size: 0.75rem;"><?= esc(empty($e['e_academic_standing']) ? 'ไม่มีวิทยฐานะ' : $e['e_academic_standing']); ?></span>
                    </td>
                    <td>
                        <span class="badge bg-label-info" style="font-size: 0.75rem;"><i class="bx bx-building-house me-1"></i><?= esc(empty($e['e_organization']) ? '-' : $e['e_organization']); ?></span>
                    </td>
                    <td>
                        <code class="bg-light text-primary px-2 py-1 rounded border fw-bold" style="font-size: 0.78rem;"><?= esc($e['e_Username'] ?? ''); ?></code>
                    </td>
                    <td class="text-center text-nowrap">
                        <div class="d-flex justify-content-center gap-1">
                            <button type="button" class="btn btn-sm btn-icon btn-label-warning edit-evaluator-btn rounded-pill shadow-none"
                                data-bs-toggle="modal"
                                data-bs-target="#editEvaluatorModal"
                                data-id="<?= esc($e['e_id']); ?>"
                                data-first-name="<?= esc($e['e_first_name'] ?? ''); ?>"
                                data-last-name="<?= esc($e['e_last_name'] ?? ''); ?>"
                                data-position="<?= esc($e['e_position'] ?? ''); ?>"
                                data-academic-standing="<?= esc($e['e_academic_standing'] ?? ''); ?>"
                                data-organization="<?= esc($e['e_organization'] ?? ''); ?>"
                                data-username="<?= esc($e['e_Username'] ?? ''); ?>"
                                title="แก้ไขข้อมูล">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <a href="<?= base_url('Admin/PaConfig/deleteEvaluator/' . $e['e_id'] . '?fiscal_year=' . $fiscal_year); ?>" class="btn btn-sm btn-icon btn-label-danger rounded-pill shadow-none" title="ลบผู้ประเมิน">
                               <i class="bx bx-trash"></i>
                            </a>
                        </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center text-muted py-5">
                      <i class="bx bx-info-circle fs-2 d-block mb-2 text-secondary"></i>
                      ไม่มีข้อมูลผู้ประเมินประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Evaluator Modal -->
<div class="modal fade" id="addEvaluatorModal" tabindex="-1" aria-labelledby="addEvaluatorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="addEvaluatorModalLabel">
            <i class="bx bx-user-plus fs-4"></i> เพิ่มผู้ประเมิน (ประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?>)
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="addEvaluatorForm" action="<?= base_url('Admin/PaConfig/addEvaluator'); ?>" method="POST">
          <input type="hidden" name="scope_fiscal_year" value="<?= esc($fiscal_year); ?>">
          <input type="hidden" name="evaluator_type" id="evaluator_type" value="school_personnel">

          <ul class="nav nav-pills nav-fill mb-3 p-1 rounded-3" style="background: #f1f5f9;" id="evaluatorTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active fw-bold py-2 rounded-3" id="school-tab" data-bs-toggle="tab" data-bs-target="#school-personnel-tab" type="button" role="tab" aria-controls="school-personnel-tab" aria-selected="true" style="font-size: 0.82rem;">
                  <i class="bx bx-building-house me-1"></i> ครูในโรงเรียน
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold py-2 rounded-3" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing-evaluator-tab" type="button" role="tab" aria-controls="existing-evaluator-tab" aria-selected="false" style="font-size: 0.82rem;">
                  <i class="bx bx-search-alt me-1"></i> ผู้ประเมินเดิมในระบบ
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold py-2 rounded-3" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-evaluator-tab" type="button" role="tab" aria-controls="new-evaluator-tab" aria-selected="false" style="font-size: 0.82rem;">
                  <i class="bx bx-user-plus me-1"></i> ผู้ประเมินภายนอกใหม่
              </button>
            </li>
          </ul>

          <div class="tab-content border-0 p-0" id="evaluatorTabContent">
            <!-- Tab 1: School Personnel -->
            <div class="tab-pane fade show active" id="school-personnel-tab" role="tabpanel" aria-labelledby="school-tab">
              <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <label for="school_pers_id" class="form-label fw-bold small text-dark"><i class="bx bx-user-check me-1 text-primary"></i>ค้นหา/เลือกครู หรือ บุคลากรภายในโรงเรียน</label>
                <select class="form-select select2 shadow-none" id="school_pers_id" name="school_pers_id">
                    <option value="">-- พิมพ์ชื่อ หรือ เลือกบุคลากรในโรงเรียน --</option>
                    <?php if (!empty($school_personnel)): ?>
                        <?php foreach ($school_personnel as $sp): ?>
                            <option value="<?= esc($sp['pers_id']); ?>">
                                <?= esc(($sp['pers_prefix'] ?? '') . $sp['pers_firstname'] . ' ' . $sp['pers_lastname']); ?> 
                                (<?= esc($sp['posi_name'] ?: 'ไม่ระบุตำแหน่ง'); ?> <?= !empty($sp['pers_username']) ? '- ' . esc($sp['pers_username']) : ''; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text text-muted mt-2">
                    <i class="bx bx-info-circle me-1"></i>ระบบจะดึงข้อมูลครูท่านนี้จากฐานข้อมูลโรงเรียนมากำหนดเป็นผู้ประเมินประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?> ให้อัตโนมัติ
                </div>
              </div>
            </div>

            <!-- Tab 2: Existing PA Evaluator -->
            <div class="tab-pane fade" id="existing-evaluator-tab" role="tabpanel" aria-labelledby="existing-tab">
              <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <label for="existing_e_id" class="form-label fw-bold small text-dark"><i class="bx bx-search-alt me-1 text-primary"></i>ค้นหา/เลือกผู้ประเมินที่มีอยู่แล้วในระบบ PA</label>
                <select class="form-select select2 shadow-none" id="existing_e_id" name="existing_e_id">
                    <option value="">-- เลือกผู้ประเมินเดิม --</option>
                    <?php if (!empty($all_evaluators)): ?>
                        <?php foreach ($all_evaluators as $ae): ?>
                            <option value="<?= esc($ae['e_id']); ?>">
                                <?= esc($ae['e_first_name'] . ' ' . $ae['e_last_name']); ?> 
                                (<?= esc($ae['e_position'] ?: 'ไม่ระบุตำแหน่ง'); ?> - <?= esc($ae['e_organization'] ?: 'ไม่ระบุหน่วยงาน'); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text text-muted mt-2">
                    <i class="bx bx-info-circle me-1"></i>ระบบจะดึงผู้ประเมินท่านนี้เข้ามาในประจำปีการศึกษา พ.ศ. <?= esc($fiscal_year); ?> โดยไม่ต้องลงทะเบียนซ้ำ
                </div>
              </div>
            </div>

            <!-- Tab 3: New External Evaluator Form -->
            <div class="tab-pane fade" id="new-evaluator-tab" role="tabpanel" aria-labelledby="new-tab">
              <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                  <div class="row g-3">
                      <div class="col-md-6">
                        <label for="e_first_name" class="form-label fw-bold small text-dark"><i class="bx bx-user me-1 text-primary"></i>ชื่อจริง</label>
                        <input type="text" class="form-control shadow-none" id="e_first_name" name="e_first_name" placeholder="กรอกชื่อจริง">
                      </div>
                      <div class="col-md-6">
                        <label for="e_last_name" class="form-label fw-bold small text-dark"><i class="bx bx-user me-1 text-primary"></i>นามสกุล</label>
                        <input type="text" class="form-control shadow-none" id="e_last_name" name="e_last_name" placeholder="กรอกนามสกุล">
                      </div>
                      <div class="col-md-6">
                        <label for="e_position" class="form-label fw-bold small text-dark"><i class="bx bx-briefcase me-1 text-primary"></i>ตำแหน่ง</label>
                        <input type="text" class="form-control shadow-none" id="e_position" name="e_position" placeholder="เช่น ครู, ผู้อำนวยการ">
                      </div>
                      <div class="col-md-6">
                        <label for="e_academic_standing" class="form-label fw-bold small text-dark"><i class="bx bx-award me-1 text-primary"></i>วิทยฐานะ</label>
                        <input type="text" class="form-control shadow-none" id="e_academic_standing" name="e_academic_standing" placeholder="เช่น ชำนาญการพิเศษ">
                      </div>
                      <div class="col-md-6">
                        <label for="e_organization" class="form-label fw-bold small text-dark"><i class="bx bx-building-house me-1 text-primary"></i>หน่วยงาน / โรงเรียน</label>
                        <input type="text" class="form-control shadow-none" id="e_organization" name="e_organization" placeholder="ชื่อโรงเรียน/หน่วยงาน">
                      </div>
                      <div class="col-md-6">
                        <label for="e_Username" class="form-label fw-bold small text-dark"><i class="bx bx-id-card me-1 text-primary"></i>ชื่อผู้ใช้งาน (Username)</label>
                        <input type="text" class="form-control shadow-none" id="e_Username" name="e_Username" placeholder="ตั้งชื่อผู้ใช้สำหรับล็อกอิน">
                      </div>
                      <div class="col-12">
                        <label for="e_Password" class="form-label fw-bold small text-dark"><i class="bx bx-key me-1 text-primary"></i>รหัสผ่าน</label>
                        <input type="password" class="form-control shadow-none" id="e_Password" name="e_Password" placeholder="ตั้งรหัสผ่านสำหรับเข้าสู่ระบบ">
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light border-top py-2 px-4">
        <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-target="#evaluatorManagerModal" data-bs-toggle="modal" data-bs-dismiss="modal">
            <i class="bx bx-arrow-back me-1"></i> ย้อนกลับ
        </button>
        <button type="submit" form="addEvaluatorForm" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm fw-bold">
            <i class="bx bx-save me-1"></i> บันทึกข้อมูล
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Evaluator Modal -->
<div class="modal fade" id="editEvaluatorModal" tabindex="-1" aria-labelledby="editEvaluatorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff;">
        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="editEvaluatorModalLabel">
            <i class="bx bx-edit-alt fs-4"></i> แก้ไขข้อมูลผู้ประเมิน
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editEvaluatorForm" action="<?= base_url('Admin/PaConfig/updateEvaluator'); ?>" method="POST">
          <input type="hidden" id="edit_e_id" name="e_id">
          <div class="row g-3">
              <div class="col-md-6">
                <label for="edit_e_first_name" class="form-label fw-bold small text-dark"><i class="bx bx-user me-1 text-primary"></i>ชื่อจริง</label>
                <input type="text" class="form-control shadow-none" id="edit_e_first_name" name="e_first_name" required>
              </div>
              <div class="col-md-6">
                <label for="edit_e_last_name" class="form-label fw-bold small text-dark"><i class="bx bx-user me-1 text-primary"></i>นามสกุล</label>
                <input type="text" class="form-control shadow-none" id="edit_e_last_name" name="e_last_name" required>
              </div>
              <div class="col-md-6">
                <label for="edit_e_position" class="form-label fw-bold small text-dark"><i class="bx bx-briefcase me-1 text-primary"></i>ตำแหน่ง</label>
                <input type="text" class="form-control shadow-none" id="edit_e_position" name="e_position">
              </div>
              <div class="col-md-6">
                <label for="edit_e_academic_standing" class="form-label fw-bold small text-dark"><i class="bx bx-award me-1 text-primary"></i>วิทยฐานะ</label>
                <input type="text" class="form-control shadow-none" id="edit_e_academic_standing" name="e_academic_standing">
              </div>
              <div class="col-md-6">
                <label for="edit_e_organization" class="form-label fw-bold small text-dark"><i class="bx bx-building-house me-1 text-primary"></i>หน่วยงาน / โรงเรียน</label>
                <input type="text" class="form-control shadow-none" id="edit_e_organization" name="e_organization">
              </div>
              <div class="col-md-6">
                <label for="edit_e_Username" class="form-label fw-bold small text-dark"><i class="bx bx-id-card me-1 text-primary"></i>ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" class="form-control shadow-none" id="edit_e_Username" name="e_Username" required>
              </div>
              <div class="col-12">
                <label for="edit_e_Password" class="form-label fw-bold small text-dark"><i class="bx bx-key me-1 text-primary"></i>รหัสผ่านใหม่ (ไม่บังคับ)</label>
                <input type="password" class="form-control shadow-none" id="edit_e_Password" name="e_Password" placeholder="เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน">
              </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light border-top py-2 px-4">
        <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-target="#evaluatorManagerModal" data-bs-toggle="modal" data-bs-dismiss="modal">
            <i class="bx bx-arrow-back me-1"></i> ย้อนกลับ
        </button>
        <button type="submit" form="editEvaluatorForm" class="btn btn-warning rounded-pill btn-sm px-4 shadow-sm text-dark fw-bold">
            <i class="bx bx-save me-1"></i> บันทึกการเปลี่ยนแปลง
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Handle flashdata messages with Swal2
    <?php if (session()->getFlashdata('Success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '<?= session()->getFlashdata('Success'); ?>',
            confirmButtonText: 'ตกลง'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('Error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด!',
            text: '<?= session()->getFlashdata('Error'); ?>',
            confirmButtonText: 'ตกลง'
        });
    <?php endif; ?>

    // Auto reopen Evaluator Manager Modal if requested after save/delete
    <?php if (session()->getFlashdata('open_evaluator_modal') || isset($_GET['open_evaluator_modal'])): ?>
        const evalManagerModalEl = document.getElementById('evaluatorManagerModal');
        if (evalManagerModalEl) {
            const managerModal = new bootstrap.Modal(evalManagerModalEl);
            managerModal.show();
        }
    <?php endif; ?>

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

    // Handle delete confirmation with Swal2 for single teacher in scope
    document.querySelectorAll('.scope-delete-single').forEach(button => {
        button.removeAttribute('onclick');
        button.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            const deleteUrl = this.href;
            const teacherName = this.getAttribute('data-teacher-name') || 'ครูท่านนี้';

            Swal.fire({
                title: 'ยืนยันนำครูออก?',
                html: `คุณต้องการนำ <strong class="text-primary">${teacherName}</strong> ออกจากขอบเขตการประเมินนี้ใช่หรือไม่?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="bx bx-trash me-1"></i> ใช่, นำออก',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });

    // Handle delete confirmation with Swal2 for group of teachers in scope
    document.querySelectorAll('.scope-delete-group').forEach(button => {
        button.removeAttribute('onclick');
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;

            Swal.fire({
                title: 'ลบขอบเขตการประเมินทั้งหมด?',
                text: "คุณต้องการลบรายชื่อครูผู้ถูกประเมินทั้งหมดของผู้ประเมินท่านนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="bx bx-trash me-1"></i> ใช่, ลบทั้งหมด',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });

    // Handle delete confirmation with Swal2 for evaluators
    document.querySelectorAll('a[href*="deleteEvaluator"]').forEach(button => {
        button.removeAttribute('onclick');
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;

            const managerModal = bootstrap.Modal.getInstance(document.getElementById('evaluatorManagerModal'));
            if (managerModal) {
                managerModal.hide();
            }

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบผู้ประเมินนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });

    // Handle edit evaluator modal
    const editEvaluatorModal = document.getElementById('editEvaluatorModal');
    if (editEvaluatorModal) {
        editEvaluatorModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const modal = this;

            const id = button.getAttribute('data-id');
            const firstName = button.getAttribute('data-first-name');
            const lastName = button.getAttribute('data-last-name');
            const position = button.getAttribute('data-position');
            const academicStanding = button.getAttribute('data-academic-standing');
            const organization = button.getAttribute('data-organization');
            const username = button.getAttribute('data-username');

            modal.querySelector('#edit_e_id').value = id;
            modal.querySelector('#edit_e_first_name').value = firstName;
            modal.querySelector('#edit_e_last_name').value = lastName;
            modal.querySelector('#edit_e_position').value = position;
            modal.querySelector('#edit_e_academic_standing').value = academicStanding;
            modal.querySelector('#edit_e_organization').value = organization;
            modal.querySelector('#edit_e_Username').value = username;
            modal.querySelector('#edit_e_Password').value = '';
        });
    }

    // Logic to disable learning group based on position with Select2 support
    $(document).ready(function() {
        // Initialize Select2 on page load
        $('.select2:not(#existing_e_id):not(#school_pers_id):not(#scope_pers_id)').select2({
            width: '100%'
        });

        $('#existing_e_id, #school_pers_id').select2({
            dropdownParent: $('#addEvaluatorModal'),
            width: '100%'
        });

        // Configure Select2 with Checkbox rendering specifically for scope_pers_id
        $('#scope_pers_id').select2({
            width: '100%',
            closeOnSelect: false,
            placeholder: '-- ติ๊กเลือกครูข้าราชการ --',
            dropdownCssClass: 'select2-checkbox-dropdown'
        }).on('select2:selecting', function(e) {
            if (e.params.args.data.element && e.params.args.data.element.disabled) {
                e.preventDefault();
            }
        });

        const $positionSelect = $('#scope_posi_id');
        const $learningGroupSelect = $('#scope_lear_id');
        const $persSelect = $('#scope_pers_id');
        const allPersonnel = <?= json_encode($personnel ?? []); ?>;
        const evaluatorCounts = <?= json_encode($evaluator_counts ?? (object)[]); ?>;
        const assessorTeacherMap = <?= json_encode($assessor_teacher_map ?? (object)[]); ?>;

        if ($positionSelect.length && $learningGroupSelect.length) {
            const toggleLearningGroup = () => {
                const selectedText = $positionSelect.find('option:selected').text().trim();
                
                if (selectedText === 'ผู้อำนวยการสถานศึกษา' || selectedText === 'รองผู้อำนวยการสถานศึกษา') {
                    $learningGroupSelect.val('').trigger('change.select2');
                    $learningGroupSelect.prop('disabled', true);
                } else {
                    $learningGroupSelect.prop('disabled', false);
                }
            };

            $positionSelect.on('change', toggleLearningGroup);
            toggleLearningGroup();
        }

        // Filter personnel select options based on selected Position, Learning Group, and Evaluator
        const updatePersonnelFilter = () => {
            const selectedAssessorId = $('#assessor_e_id').val();
            const selectedPosiId = $positionSelect.val();
            const selectedLearId = $learningGroupSelect.val();
            const currentValues = $persSelect.val() || [];

            $persSelect.empty();

            let filtered = allPersonnel;

            if (selectedPosiId) {
                filtered = filtered.filter(p => p.pers_position == selectedPosiId);
            }

            if (selectedLearId) {
                filtered = filtered.filter(p => p.pers_learning == selectedLearId);
            }

            const assignedToThisAssessor = (selectedAssessorId && assessorTeacherMap[selectedAssessorId]) ? assessorTeacherMap[selectedAssessorId] : [];

            filtered.forEach(p => {
                const pIdStr = String(p.pers_id);
                const count = evaluatorCounts[pIdStr] || 0;
                const isAssignedToThis = assignedToThisAssessor.includes(pIdStr);

                let name = (p.pers_prefix || '') + p.pers_firstname + ' ' + p.pers_lastname;
                if (isAssignedToThis) {
                    name += ' ⛔ (มีผู้ประเมินท่านนี้แล้ว)';
                } else if (count > 0) {
                    name += ` (มีผู้ประเมิน ${count} ท่าน)`;
                }

                const option = new Option(name, p.pers_id, false, false);
                if (isAssignedToThis) {
                    $(option).prop('disabled', true).attr('disabled', 'disabled');
                }
                $persSelect.append(option);
            });

            $persSelect.val(currentValues).trigger('change.select2');
        };

        $('#assessor_e_id').on('change', updatePersonnelFilter);
        $positionSelect.on('change', updatePersonnelFilter);
        $learningGroupSelect.on('change', updatePersonnelFilter);

        // Tab switching handler for Add Evaluator Modal
        $('#school-tab').on('shown.bs.tab', function () {
            $('#evaluator_type').val('school_personnel');
            $('#e_first_name, #e_last_name, #e_Username, #e_Password').prop('required', false);
            $('#existing_e_id').prop('required', false);
            $('#school_pers_id').prop('required', true);
        });

        $('#existing-tab').on('shown.bs.tab', function () {
            $('#evaluator_type').val('existing');
            $('#e_first_name, #e_last_name, #e_Username, #e_Password').prop('required', false);
            $('#school_pers_id').prop('required', false);
            $('#existing_e_id').prop('required', true);
        });

        $('#new-tab').on('shown.bs.tab', function () {
            $('#evaluator_type').val('new');
            $('#e_first_name, #e_last_name, #e_Username, #e_Password').prop('required', true);
            $('#existing_e_id, #school_pers_id').prop('required', false);
        });

        // Add loading spinner state to submit buttons on form submit
        $('form').on('submit', function() {
            const $form = $(this);
            let $submitBtn = $form.find('button[type="submit"], input[type="submit"]');
            
            if (!$submitBtn.length && this.id) {
                $submitBtn = $(`button[type="submit"][form="${this.id}"]`);
            }

            if ($submitBtn.length) {
                $submitBtn.prop('disabled', true);
                $submitBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> กำลังบันทึก...');
            }
        });
    });
</script>
<?= $this->endSection() ?>
