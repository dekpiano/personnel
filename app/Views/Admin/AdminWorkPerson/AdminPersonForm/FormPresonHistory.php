<style>
    .history-form-container {
        padding: 0.5rem;
    }
    .form-group-subtitle {
        font-weight: 800;
        color: var(--primary-blue);
        margin: 2.5rem 0 1.5rem 0;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        padding-left: 15px;
    }
    .form-group-subtitle::before {
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
    .form-group-subtitle:first-of-type {
        margin-top: 0;
    }
</style>

<form class="history-form-container needs-validation" id="FormPersonnalHistory" method="post" novalidate>
    <input type="text" id="pers_id" name="pers_id" value="<?= $Pers->pers_id ?>" hidden>
    
    <div class="form-group-subtitle">
        <i class='bx bx-id-card me-2'></i> ข้อมูลพื้นฐานและตัวตน <span id="status" class="ms-2"></span>
    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select pers_prefix auto-save" id="pers_prefix_hist" name="pers_prefix">
                    <option value="">เลือกเค้าโครง...</option>
                    <option value="นาย" <?= $Pers->pers_prefix == 'นาย' ? 'selected' : '' ?>>นาย</option>
                    <option value="นาง" <?= $Pers->pers_prefix == 'นาง' ? 'selected' : '' ?>>นาง</option>
                    <option value="นางสาว" <?= $Pers->pers_prefix == 'นางสาว' ? 'selected' : '' ?>>นางสาว</option>
                    <option value="ว่าที่ร้อยตรี" <?= $Pers->pers_prefix == 'ว่าที่ร้อยตรี' ? 'selected' : '' ?>>ว่าที่ร้อยตรี</option>
                    <option value="ว่าที่ร้อยตรีหญิง" <?= $Pers->pers_prefix == 'ว่าที่ร้อยตรีหญิง' ? 'selected' : '' ?>>ว่าที่ร้อยตรีหญิง</option>
                </select>
                <label for="pers_prefix_hist">คำนำหน้า</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control pers_firstname auto-save" id="pers_firstname_hist" name="pers_firstname" placeholder="ชื่อจริง" value="<?= $Pers->pers_firstname ?>">
                <label for="pers_firstname_hist">ชื่อจริง</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-floating">
                <input type="text" class="form-control pers_lastname auto-save" id="pers_lastname_hist" name="pers_lastname" placeholder="นามสกุล" value="<?= $Pers->pers_lastname ?>">
                <label for="pers_lastname_hist">นามสกุล</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_nickname" name="pers_nickname" placeholder="ชื่อเล่น" value="<?= $Pers->pers_nickname ?>">
                <label for="pers_nickname">ชื่อเล่น</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_id_card" name="pers_id_card" placeholder="เลขบัตรประชาชน" value="<?= $Pers->pers_id_card ?>">
                <label for="pers_id_card">เลขประจำตัวประชาชน (13 หลัก)</label>
                <div id="cid-error" style="color: red; font-size: 0.75rem; margin-top: 5px; display: none;"></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control selectorEdit auto-save" id="pers_britday" name="pers_britday" placeholder="วันเกิด" value="<?= $Pers->pers_britday ?>" autocomplete="off">
                <label for="pers_britday">วัน/เดือน/ปี เกิด</label>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-floating">
                <input type="text" class="form-control bg-light" id="pers_age" placeholder="อายุ" readonly>
                <label for="pers_age">อายุ</label>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select select2Personnel auto-save" id="pers_nationality" name="pers_nationality">
                    <option value="">เลือกสัญชาติ...</option>
                    <option value="ไทย" <?= $Pers->pers_nationality == 'ไทย' ? 'selected' : '' ?>>ไทย</option>
                    <option value="อื่นๆ" <?= ($Pers->pers_nationality != 'ไทย' && $Pers->pers_nationality != '') ? 'selected' : '' ?>>อื่นๆ</option>
                </select>
                <label for="pers_nationality">สัญชาติ</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select select2Personnel auto-save" id="pers_race" name="pers_race">
                    <option value="">เลือกเชื้อชาติ...</option>
                    <option value="ไทย" <?= $Pers->pers_race == 'ไทย' ? 'selected' : '' ?>>ไทย</option>
                    <option value="จีน" <?= $Pers->pers_race == 'จีน' ? 'selected' : '' ?>>จีน</option>
                    <option value="อื่นๆ" <?= ($Pers->pers_race != 'ไทย' && $Pers->pers_race != 'จีน' && $Pers->pers_race != '') ? 'selected' : '' ?>>อื่นๆ</option>
                </select>
                <label for="pers_race">เชื้อชาติ</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select select2Personnel auto-save" id="pers_religion" name="pers_religion">
                    <option value="">เลือกศาสนา...</option>
                    <option value="พุทธ" <?= $Pers->pers_religion == 'พุทธ' ? 'selected' : '' ?>>พุทธ</option>
                    <option value="อิสลาม" <?= $Pers->pers_religion == 'อิสลาม' ? 'selected' : '' ?>>อิสลาม</option>
                    <option value="คริสต์" <?= $Pers->pers_religion == 'คริสต์' ? 'selected' : '' ?>>คริสต์</option>
                    <option value="สิข" <?= $Pers->pers_religion == 'สิข' ? 'selected' : '' ?>>สิข</option>
                    <option value="อื่นๆ" <?= (!in_array($Pers->pers_religion, ['พุทธ', 'อิสลาม', 'คริสต์', 'สิข']) && $Pers->pers_religion != '') ? 'selected' : '' ?>>อื่นๆ</option>
                </select>
                <label for="pers_religion">ศาสนา</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select auto-save" id="pers_blood_type" name="pers_blood_type">
                    <option value="">เลือก...</option>
                    <option value="A" <?= $Pers->pers_blood_type == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $Pers->pers_blood_type == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="AB" <?= $Pers->pers_blood_type == 'AB' ? 'selected' : '' ?>>AB</option>
                    <option value="O" <?= $Pers->pers_blood_type == 'O' ? 'selected' : '' ?>>O</option>
                </select>
                <label for="pers_blood_type">กรุ๊ปเลือด</label>
            </div>
        </div>
    </div>

    <div class="form-group-subtitle">
        <i class='bx bx-mobile-alt me-2'></i> ช่องทางติดต่อ
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control auto-save" id="pers_username_hist" name="pers_username" placeholder="อีเมล" value="<?= $Pers->pers_username ?>">
                <label for="pers_username_hist">ที่อยู่อีเมลสำรอง/ส่วนตัว</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_phone_hist" name="pers_phone" placeholder="เบอร์โทรศัพท์" value="<?= $Pers->pers_phone ?>">
                <label for="pers_phone_hist">เบอร์โทรศัพท์มือถือ</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_facebook" name="pers_facebook" placeholder="Facebook" value="<?= $Pers->pers_facebook ?>">
                <label for="pers_facebook">Facebook</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_instagram" name="pers_instagram" placeholder="Instagram" value="<?= $Pers->pers_instagram ?>">
                <label for="pers_instagram">Instagram</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_line" name="pers_line" placeholder="Line" value="<?= $Pers->pers_line ?>">
                <label for="pers_line">Line ID</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="pers_youtube" name="pers_youtube" placeholder="Youtube Channel" value="<?= $Pers->pers_youtube ?>">
                <label for="pers_youtube">Youtube</label>
            </div>
        </div>
    </div>

    <div class="form-group-subtitle">
        <i class='bx bx-map-pin me-2'></i> ที่อยู่ตามทะเบียนบ้าน
    </div>
    <div class="row g-4">
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="addr_house_no" name="addr_house_no" placeholder="บ้านเลขที่" value="<?= $AddrReg->addr_house_no ?? '' ?>">
                <label for="addr_house_no">เลขที่</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="addr_moo" name="addr_moo" placeholder="หมู่ที่" value="<?= $AddrReg->addr_moo ?? '' ?>">
                <label for="addr_moo">หมู่ที่</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="addr_village" name="addr_village" placeholder="หมู่บ้าน / อาคาร" value="<?= $AddrReg->addr_village ?? '' ?>">
                <label for="addr_village">หมู่บ้าน / อาคาร</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="addr_soi" name="addr_soi" placeholder="ซอย" value="<?= $AddrReg->addr_soi ?? '' ?>">
                <label for="addr_soi">ซอย</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="addr_road" name="addr_road" placeholder="ถนน" value="<?= $AddrReg->addr_road ?? '' ?>">
                <label for="addr_road">ถนน</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select province auto-save" id="addr_province" name="addr_province">
                    <option selected disabled>เลือกจังหวัด</option>
                </select>
                <label for="addr_province">จังหวัด</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select district auto-save" id="addr_district" name="addr_district" disabled>
                    <option selected disabled>เลือกอำเภอ</option>
                </select>
                <label for="addr_district">อำเภอ/เขต</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select subdistrict auto-save" id="addr_subdistrict" name="addr_subdistrict" disabled>
                    <option selected disabled>เลือกตำบล</option>
                </select>
                <label for="addr_subdistrict">ตำบล/แขวง</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="addr_postcode" name="addr_postcode" placeholder="รหัสไปรษณีย์" value="<?= $AddrReg->addr_postcode ?? '' ?>">
                <label for="addr_postcode">รหัสไปรษณีย์</label>
            </div>
        </div>
    </div>

    <div class="form-group-subtitle">
        <i class='bx bx-map me-2'></i> ที่อยู่ปัจจุบัน <small class="text-muted fw-normal fs-tiny ms-2">(ถ้าต่างจากทะเบียนบ้าน)</small>
    </div>
    <div class="row g-4">
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_house_no" name="curr_addr_house_no" placeholder="บ้านเลขที่" value="<?= $AddrCurr->addr_house_no ?? '' ?>">
                <label for="curr_addr_house_no">เลขที่</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_moo" name="curr_addr_moo" placeholder="หมู่ที่" value="<?= $AddrCurr->addr_moo ?? '' ?>">
                <label for="curr_addr_moo">หมู่ที่</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_village" name="curr_addr_village" placeholder="หมู่บ้าน / อาคาร" value="<?= $AddrCurr->addr_village ?? '' ?>">
                <label for="curr_addr_village">หมู่บ้าน / อาคาร</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_postcode" name="curr_addr_postcode" placeholder="รหัสไปรษณีย์" value="<?= $AddrCurr->addr_postcode ?? '' ?>">
                <label for="curr_addr_postcode">รหัสไปรษณีย์</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select curr_province auto-save" id="curr_addr_province" name="curr_addr_province">
                    <option selected disabled>เลือกจังหวัด</option>
                </select>
                <label for="curr_addr_province">จังหวัด</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select curr_district auto-save" id="curr_addr_district" name="curr_addr_district" disabled>
                    <option selected disabled>เลือกอำเภอ</option>
                </select>
                <label for="curr_addr_district">อำเภอ/เขต</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select curr_subdistrict auto-save" id="curr_addr_subdistrict" name="curr_addr_subdistrict" disabled>
                    <option selected disabled>เลือกตำบล</option>
                </select>
                <label for="curr_addr_subdistrict">ตำบล/แขวง</label>
            </div>
        </div>
    </div>

    <div class="form-group-subtitle">
        <i class='bx bx-group me-2'></i> สถาบันครอบครัว
    </div>
    <div class="row g-4 align-items-end">
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select auto-save" id="pers_marital_status" name="pers_marital_status">
                    <option value="">เลือกสถานภาพ...</option>
                    <option value="โสด" <?= $Pers->pers_marital_status == 'โสด' ? 'selected' : '' ?>>โสด</option>
                    <option value="สมรส" <?= $Pers->pers_marital_status == 'สมรส' ? 'selected' : '' ?>>สมรส</option>
                    <option value="หม้าย" <?= $Pers->pers_marital_status == 'หม้าย' ? 'selected' : '' ?>>หม้าย</option>
                    <option value="หย่า" <?= $Pers->pers_marital_status == 'หย่า' ? 'selected' : '' ?>>หย่า</option>
                    <option value="แยกกันอยู่" <?= $Pers->pers_marital_status == 'แยกกันอยู่' ? 'selected' : '' ?>>แยกกันอยู่</option>
                </select>
                <label for="pers_marital_status">สถานภาพสมรส</label>
            </div>
        </div>
        <div class="col-md-6 text-end">
             <button type="button" class="btn btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#familyModal">
                <i class='bx bx-plus-circle me-1'></i> เพิ่มสมาชิกครอบครัว
            </button>
        </div>
        <div class="col-12">
            <div class="table-responsive rounded-4 border overflow-hidden mt-2">
                <table class="table table-hover mb-0" id="familyTable">
                    <thead class="bg-light">
                        <tr class="text-uppercase small fw-bold">
                            <th class="ps-3 py-3">ชื่อ-นามสกุล</th>
                            <th class="py-3">ความสัมพันธ์</th>
                            <th class="py-3">อายุ (ปี)</th>
                            <th class="text-center py-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ดึงข้อมูลอัตโนมัติจากคอนโทรลเลอร์ -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Save Button for Personal History Form -->
    <div class="mt-5 pt-4 border-top text-end">
        <button class="btn btn-primary btn-update-general shadow-sm" type="button" id="btnSaveHistory">
            <i class='bx bx-check-circle fs-5 me-2'></i> บันทึกประวัติส่วนตัว
        </button>
    </div>
</form>

<!-- Modal Family -->
<div class="modal fade" id="familyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-premium">
            <form id="familyForm">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold"><i class='bx bx-group me-2'></i>เพิ่มข้อมูลสมาชิกครอบครัว</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" required placeholder="ระบุชื่อจริงและนามสกุล">
                            <label for="name">ชื่อจริง - นามสกุล</label>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="relation" required placeholder="เช่น บิดา, มารดา, บุตร">
                                <label for="relation">ความสัมพันธ์</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="age" required placeholder="ระบุอายุ">
                                <label for="age">อายุ (ปี)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">บันทึกสมาชิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

