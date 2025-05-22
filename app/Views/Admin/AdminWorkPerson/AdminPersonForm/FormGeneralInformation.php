<form class="needs-validation" novalidate="" id="FormPersonnalUpdateDataPersonnel" enctype="multipart/form-data">
    <input type="hidden" name="key_update" id="key_update" value="<?=$uri->getSegment(4)?>">
    <div class="row g-3">
        <div class="col-md-9">
            <?php $status = array('กำลังใช้งาน','ย้ายสถานศึกษา','ลาออก','เกษียรอายุ'); ?>
            <div class="form-floating">
                <select class="form-select pers_status" id="pers_status" name="pers_status" required="">
                    <option value="">เลือก...</option>
                    <?php foreach ($status as $key => $value) : ?>
                    <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_status">สถานะผู้ใช้งาน</label>
            </div>
            <div class="invalid-feedback">
                กรุณากรอกเลือกสถานะผู้ใช้งาน
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="pers_id" name="pers_id" placeholder=""
                    value="<?=$Pers->pers_id?>" required="" readonly>
                <label for="pers_id">รหัสประจำตัว</label>
            </div>
        </div>
        <hr>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <?php $prefix = array('นาย','นาง','นางสาว','ว่าที่ร้อยตรี','ว่าที่ร้อยตรีหญิง'); ?>
            <div class="form-floating">
                <select class="form-select pers_prefix" id="pers_prefix" name="pers_prefix" required="">
                    <option value="">เลือก...</option>
                    <?php foreach ($prefix as $key => $value) : ?>
                    <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_prefix">คำนำหน้า</label>
            </div>
            <div class="invalid-feedback">
                กรุณากรอกเลือกคำนำหน้า
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control pers_firstname" id="pers_firstname" name="pers_firstname"
                    placeholder="" value="" required="">
                <label for="pers_firstname">ชื่อจริง</label>
            </div>
            <div class="invalid-feedback">
                กรุณากรอกชื่อจริง...
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control pers_lastname" id="pers_lastname" name="pers_lastname"
                    placeholder="" value="" required="">
                <label for="pers_lastname">นามสกุล</label>
            </div>
            <div class="invalid-feedback">
                กรุณากรอกนามสกุล...
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="tel" class="form-control pers_phone" id="pers_phone" name="pers_phone" placeholder=""
                    value="">
                <label for="pers_phone">เบอร์โทรศัพท์</label>
            </div>
            <div class="invalid-feedback">
                กรุณากรอกเบอร์โทรศัพท์...
            </div>
        </div>
        <div class="col-4">
            <div class="form-floating">
                <input type="email" class="form-control pers_username" id="pers_username"
                    placeholder="ให้ใช้อีเมลของโรงเรียน" name="pers_username" value="">
                <label for="pers_username">Email <span class="text-muted">(Optional)</span></label>
            </div>
            <div class="invalid-feedback">
                กรุณากรอกอีเมล
            </div>
        </div>

        <hr>

        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select pers_position" id="pers_position" name="pers_position" required="">
                    <option value="">เลือก...</option>
                    <?php foreach ($position as $key => $value) : ?>
                    <option value="<?=$value->posi_id;?>"><?=$value->posi_name;?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_position">ตำแหน่งทางการศึกษา</label>
            </div>
            <div class="invalid-feedback">
                กรุณาเลือกตำแหน่งทางการศึกษา
            </div>
        </div>

        <div class="col-md-4" style="display:none;" id="show_position">
            <div class="form-floating">
                <select class="form-select select2Personnel" id="pers_workother_id" name="pers_workother_id" required>
                    <option value="0">เลือกตำแหน่งหลัก</option>
                    <?php foreach ($PosiMain as $key => $value) : ?>
                    <option value="<?=$value->work_id;?>"><?=$value->work_name;?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <label for="pers_workother_id">ตำแหน่งหลัก</label>
            </div>
            <div class="invalid-feedback">
                กรุณาเลือกตำแหน่งหลัก
            </div>
        </div>

        <div class="row g-3 mt-2" style="display:none;" id="show_learning">
            <div class="col-md-4">
                <div class="form-floating">
                    <select class="form-select pers_learning" id="pers_learning" name="pers_learning">
                        <option value="">ไม่มีไม่ต้องเลือก...</option>
                        <?php foreach ($learning as $key => $value) : ?>
                        <option value="<?=$value->lear_id;?>">
                            <?=$value->lear_namethai;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="pers_learning">กลุ่มสาระการเรียนรู้</label>
                </div>
                <div class="invalid-feedback">
                    กรุณาเลือกกลุ่มสาระการเรียนรู้
                </div>
            </div>
            <div class="col-md-4">
                <?php $degee = array('ชำนาญการ','ชำนาญการพิเศษ','เชี่ยวชาญ','เชี่ยวชาญพิเศษ'); ?>
                <div class="form-floating">
                    <select class="form-select pers_academic" id="pers_academic" name="pers_academic">
                        <option value="">ไม่มีไม่ต้องเลือก...</option>
                        <?php foreach ($degee as $key => $value) : ?>
                        <option value="<?=$value;?>"><?=$value;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="pers_academic">วิทยฐานะ</label>
                </div>
                <div class="invalid-feedback">
                    Please provide a valid state.
                </div>
            </div>
            <div class="col-md-4">
                <?php $grouplead = array('หัวหน้ากลุ่มสาระ','รองหัวหน้ากลุ่มสาระ');  ?>
                <div class="form-floating">
                    <select class="form-select pers_groupleade" id="pers_groupleade" name="pers_groupleade">
                        <option value="">ไม่มีไม่ต้องเลือก...</option>
                        <?php foreach ($grouplead as $key => $value) :?>
                        <option value="<?=$value;?>"><?=$value;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="pers_groupleade">หัวหน้าและรองหัวหน้ากลุ่มสาระ</label>
                </div>
                <div class="invalid-feedback">
                    Please provide a valid state.
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <button class="w-100 btn btn-primary btn-lg" type="submit">บันทึก</button>
</form>