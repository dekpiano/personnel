<style>
    .form-group-title {
        font-weight: 800;
        color: var(--primary-blue);
        margin: 2rem 0 1.5rem 0;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        padding-left: 15px;
    }
    .form-group-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 18px;
        background: var(--primary-blue);
        border-radius: 4px;
    }
    .form-group-title i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    .update-form-container {
        padding: 0.5rem;
    }
    .status-section-highlight {
        background: var(--soft-bg);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px dashed rgba(0, 123, 255, 0.2);
        margin-bottom: 1rem;
    }
    .btn-update-general {
        background: var(--primary-blue);
        border: none;
        padding: 12px 35px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-update-general:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(78, 84, 200, 0.3);
        background: #3f44a1;
    }
</style>

<form class="needs-validation update-form-container" novalidate="" id="FormPersonnalUpdateDataPersonnel" enctype="multipart/form-data">
    <input type="hidden" name="key_update" id="key_update" value="<?= $uri->getSegment(4) ?>">
    
    <div class="form-group-title mt-0">
        <i class='bx bx-cog'></i> สถานะและรหัสประจำตัว
    </div>
    <div class="status-section-highlight">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="form-floating">
                    <?php $status = array('กำลังใช้งาน', 'ย้ายสถานศึกษา', 'ลาออก', 'เกษียรอายุ'); ?>
                    <select class="form-select border-0 shadow-none fw-semibold" id="pers_status" name="pers_status" required="">
                        <option value="">เลือกสถานะ...</option>
                        <?php foreach ($status as $key => $value) : ?>
                        <option value="<?= $value ?>" <?= ($Pers->pers_status == $value) ? 'selected' : '' ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="pers_status">สถานะปัจจุบัน</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" class="form-control border-0 shadow-none fw-bold text-primary" id="pers_id" name="pers_id" placeholder=""
                        value="<?= $Pers->pers_id ?>" required="" readonly style="background: white !important;">
                    <label for="pers_id">รหัสประจำตัวบุคลากร</label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group-title">
        <i class='bx bx-user-circle'></i> ข้อมูลประจำตัว
    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="form-floating">
                <?php $prefix = array('นาย', 'นาง', 'นางสาว', 'ว่าที่ร้อยตรี', 'ว่าที่ร้อยตรีหญิง'); ?>
                <select class="form-select" id="pers_prefix" name="pers_prefix" required="">
                    <option value="">เลือก...</option>
                    <?php foreach ($prefix as $key => $value) : ?>
                    <option value="<?= $value ?>" <?= ($Pers->pers_prefix == $value) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_prefix">คำนำหน้า</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control" id="pers_firstname" name="pers_firstname"
                    placeholder="ชื่อจริง" value="<?= $Pers->pers_firstname ?>" required="">
                <label for="pers_firstname">ชื่อจริง</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-floating">
                <input type="text" class="form-control" id="pers_lastname" name="pers_lastname"
                    placeholder="นามสกุล" value="<?= $Pers->pers_lastname ?>" required="">
                <label for="pers_lastname">นามสกุล</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="tel" class="form-control pers_phone" id="pers_phone" name="pers_phone" placeholder="0xx-xxx-xxxx"
                    value="<?= $Pers->pers_phone ?>">
                <label for="pers_phone">เบอร์โทรศัพท์</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control" id="pers_username"
                    placeholder="name@skj.ac.th" name="pers_username" value="<?= $Pers->pers_username ?>">
                <label for="pers_username">อีเมลโรงเรียน (Login)</label>
            </div>
        </div>
    </div>

    <div class="form-group-title">
        <i class='bx bx-id-card'></i> ข้อมูลตำแหน่งและวิทยฐานะ
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_position" name="pers_position" required="">
                    <option value="">เลือกตำแหน่งหลัก...</option>
                    <?php foreach ($position as $key => $value) : ?>
                    <option value="<?= $value->posi_id; ?>" <?= ($Pers->pers_position == $value->posi_id) ? 'selected' : '' ?>>
                        <?= $value->posi_name; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_position">ตำแหน่งทางการศึกษา</label>
            </div>
        </div>

        <div class="col-md-6" style="display: <?= ($Pers->pers_workother_id) ? 'block' : 'none' ?>" id="show_position">
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_workother_id" name="pers_workother_id">
                    <option value="0">เลือกสายงาน...</option>
                    <?php foreach ($PosiMain as $key => $value) : ?>
                    <option value="<?= $value->work_id; ?>" <?= ($Pers->pers_workother_id == $value->work_id) ? 'selected' : '' ?>>
                        <?= $value->work_name; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_workother_id">สายงาน/ตำแหน่งย่อย</label>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-4" style="display: <?= ($Pers->pers_learning && !in_array($Pers->pers_position, ['posi_001', 'posi_002'])) ? 'block' : 'none' ?>" id="show_learning">
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_learning" name="pers_learning">
                    <option value="">เลือกกลุ่มสาระ...</option>
                    <?php foreach ($learning as $key => $value) : ?>
                    <option value="<?= $value->lear_id; ?>" <?= ($Pers->pers_learning == $value->lear_id) ? 'selected' : '' ?>>
                        <?= $value->lear_namethai; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_learning">กลุ่มสาระการเรียนรู้</label>
            </div>
        </div>
        <div class="col-md-4" style="display: <?= ($Pers->pers_academic || in_array($Pers->pers_position, ['posi_001', 'posi_002'])) ? 'block' : 'none' ?>" id="show_academic">
            <?php $degee = array('ชำนาญการ', 'ชำนาญการพิเศษ', 'เชี่ยวชาญ', 'เชี่ยวชาญพิเศษ'); ?>
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_academic" name="pers_academic">
                    <option value="">ระบุวิทยฐานะ...</option>
                    <?php foreach ($degee as $key => $value) : ?>
                    <option value="<?= $value; ?>" <?= ($Pers->pers_academic == $value) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_academic">วิทยฐานะ</label>
            </div>
        </div>
        <div class="col-md-4" style="display: <?= ($Pers->pers_groupleade && !in_array($Pers->pers_position, ['posi_001', 'posi_002'])) ? 'block' : 'none' ?>" id="show_groupleade">
            <?php $grouplead = array('หัวหน้ากลุ่มสาระ', 'รองหัวหน้ากลุ่มสาระ'); ?>
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_groupleade" name="pers_groupleade">
                    <option value="">ระบุตำแหน่งภายใน...</option>
                    <?php foreach ($grouplead as $key => $value) : ?>
                    <option value="<?= $value; ?>" <?= ($Pers->pers_groupleade == $value) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_groupleade">หัวหน้า/รองหัวหน้า</label>
            </div>
        </div>
        <div class="col-md-4" style="display: <?= ($Pers->pers_position == 'posi_002') ? 'block' : 'none' ?>" id="show_faction">
            <?php 
            $factions = array('กลุ่มบริหารวิชาการ', 'กลุ่มบริหารงบประมาณ', 'กลุ่มบริหารงานบุคคล', 'กลุ่มบริหารทั่วไป'); 
            $selectedFactions = isset($Pers->pers_faction) ? explode(',', $Pers->pers_faction) : [];
            ?>
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_faction" name="pers_faction[]" multiple="multiple" data-placeholder="ระบุกลุ่มงานฝ่าย...">
                    <?php foreach ($factions as $value) : ?>
                    <option value="<?= $value; ?>" <?= in_array($value, $selectedFactions) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_faction">กลุ่มงานฝ่าย</label>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-4 border-top text-end">
        <button class="btn btn-primary btn-update-general shadow-sm" type="submit">
            <i class='bx bx-check-circle fs-5 me-2'></i> ยืนยันการเปลี่ยนแปลงข้อมูล
        </button>
    </div>
</form>

