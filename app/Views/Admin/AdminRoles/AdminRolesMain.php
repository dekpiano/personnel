<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light"></span> กำหนดสิทธิ์ใช้งานในระบบบริหารทั่วไป
</h4>

<div class="card mt-3">
    <div class="card-header">
        <h5>ผู้บริหาร</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($Manager as $key => $v_Manager) : ?>
                <?php if (in_array($v_Manager->admin_rloes_nanetype, ["ผู้อำนวยการโรงเรียน", "รองผู้อำนวยการบริหารงานบุคคลกร", "หัวหน้าบริหารทั่วไป"])) : ?>
                    <div class="col-md-4">
                        <label for=""><?php echo $v_Manager->admin_rloes_nanetype; ?></label>
                        <div class="mt-3">
                            <select class="select2Rloes form-select form-select-lg SettingPersonnelRloes"
                                rloes-id="<?= $v_Manager->admin_rloes_id; ?>"
                                rloes-level="<?= $v_Manager->admin_rloes_level; ?>"
                                Key-nanetype="<?= $v_Manager->admin_rloes_nanetype; ?>">
                                <?php foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?= $v_Manager->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : ''; ?>
                                        value="<?= $v_NameTeacher->pers_id?>">
                                        <?= $v_NameTeacher->pers_prefix . $v_NameTeacher->pers_firstname . " " . $v_NameTeacher->pers_lastname ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
$grouped_roles = [];
$executive_nanetypes = ["ผู้อำนวยการโรงเรียน", "รองผู้อำนวยการบริหารงานบุคคลกร", "หัวหน้าบริหารทั่วไป"];
foreach ($Manager as $v_Manager) {
    // Exclude specific executive roles
    $isExecutiveRole = in_array($v_Manager->admin_rloes_nanetype, $executive_nanetypes);

    if (!$isExecutiveRole) {
        $nanetype = $v_Manager->admin_rloes_nanetype;
        if (!isset($grouped_roles[$nanetype])) {
            $grouped_roles[$nanetype] = [];
        }
        $grouped_roles[$nanetype][] = $v_Manager;
    }
}

foreach ($grouped_roles as $nanetype => $roles_in_group) :
?>
    <div class="card mt-3">
        <div class="card-header">
            <h5><?= $nanetype; ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($roles_in_group as $v_Role) : ?>
                    <div class="col-md-4 mt-2">
                        <label for=""><?php
                            // Check if admin_rloes_level contains a slash, then split
                            if (strpos($v_Role->admin_rloes_level, '/') !== false) {
                                $SubLevel = explode("/", $v_Role->admin_rloes_level);
                                echo $SubLevel[1];
                            } else {
                                echo $v_Role->admin_rloes_level;
                            }
                        ?></label>
                        <div class="mt-2">
                            <select class="select2Rloes form-select form-select-lg SettingPersonnelRloes"
                                rloes-id="<?= $v_Role->admin_rloes_id; ?>"
                                rloes-level="<?= $v_Role->admin_rloes_level; ?>"
                                Key-nanetype="<?= $v_Role->admin_rloes_nanetype; ?>">
                                <option value="">เลือกเจ้าหน้าที่</option>
                                <?php foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?= $v_Role->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : ''; ?>
                                        value="<?= $v_NameTeacher->pers_id ?>">
                                        <?= $v_NameTeacher->pers_prefix . $v_NameTeacher->pers_firstname . " " . $v_NameTeacher->pers_lastname ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/js/Admin/AdminRoles/AdminRolesMain.js?v=3"></script>
<?= $this->endSection() ?>