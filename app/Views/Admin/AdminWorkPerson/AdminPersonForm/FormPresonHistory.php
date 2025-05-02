<input type="text" id="pers_id" name="pers_id" value="<?=$Pers->pers_id?>" hidden>
<form class="row g-3 needs-validation" id="FormPersonnalHistory" method="post" novalidate>
    <div class="row g-3">
        <h6 class="mb-0">ข้อมูลพื้นฐาน <span id="status"></span></h6>
        <div class="col-md-3 form-floating">
            <select class="form-select pers_prefix auto-save" id="pers_prefix" name="pers_prefix" aria-label="เลือกคำนำหน้า">
                <option value="">เลือก...</option>
                <option value="นาย">นาย</option>
                <option value="นาง">นาง</option>
                <option value="นางสาว">นางสาว</option>
                <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                <option value="ว่าที่ร้อยตรีหญิง">ว่าที่ร้อยตรีหญิง</option>
            </select>
            <label for="pers_prefix">คำนำหน้า</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_firstname auto-save" id="pers_firstname" name="pers_firstname"
                placeholder="ชื่อจริง">
            <label for="pers_firstname">ชื่อจริง</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_lastname auto-save" id="pers_lastname" name="pers_lastname"
                placeholder="ชื่อ-สกุล">
            <label for="pers_lastname">ชื่อสกุล</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_nickname auto-save" id="pers_nickname" name="pers_nickname"
                placeholder="ชื่อเล่น">
            <label for="pers_nickname">ชื่อเล่น</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control pers_id_card auto-save" id="pers_id_card" name="pers_id_card"
                placeholder="เลขบัตรประชาชน">
            <label for="pers_id_card">เลขบัตรประชาชน</label>
            <div id="cid-error" style="color: red; display: none;"></div>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control selectorEdit pers_britday auto-save" id="pers_britday" name="pers_britday"
                placeholder="" value="<?=$Pers->pers_britday?>" autocomplete="off">
            <label for="pers_britday">วันเกิด</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_age auto-save" id="pers_age" name="pers_age" placeholder="อายุ"
                readonly>
            <label for="pers_age">อายุ</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_nationality auto-save" id="pers_nationality" name="pers_nationality"
                placeholder="สัญชาติ">
            <label for="pers_nationality">สัญชาติ</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_race auto-save" id="pers_race" name="pers_race" placeholder="เชื้อชาติ">
            <label for="pers_race">เชื้อชาติ</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_religion auto-save" id="pers_religion" name="pers_religion"
                placeholder="ศาสนา">
            <label for="pers_religion">ศาสนา</label>
        </div>

        <div class="col-md-4 form-floating">
            <select class="form-select pers_blood_type auto-save" id="pers_blood_type" name="pers_blood_type"
                aria-label="เลือกกรุ๊ปเลือด">
                <option value="">เลือก...</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>
            <label for="pers_blood_type">กรุ๊ปเลือด</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="email" class="form-control pers_username auto-save" id="pers_username" name="pers_username" placeholder="อีเมล">
            <label for="pers_username">อีเมล</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_phone auto-save" id="pers_phone" name="pers_phone"
                placeholder="เบอร์โทรศัพท์">
            <label for="pers_phone">เบอร์โทรศัพท์</label>
        </div>
        <hr>
        <h6 class="mb-0 font-weight-bold">ที่อยู่ตามทะเบียนบ้าน</h6>
        <div class="col-md-2 form-floating">
            <input type="text" class="form-control auto-save" id="addr_house_no" name="addr_house_no" placeholder="บ้านเลขที่">
            <label for="addr_house_no">บ้านเลขที่</label>
        </div>
        <div class="col-md-2 form-floating">
            <input type="text" class="form-control auto-save" id="addr_moo" name="addr_moo" placeholder="หมู่ที่">
            <label for="addr_moo">หมู่ที่</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control auto-save" id="addr_village" name="addr_village"
                placeholder="หมู่บ้าน / อาคาร">
            <label for="addr_village">หมู่บ้าน / อาคาร</label>
        </div>
        <div class="col-md-2 form-floating">
            <input type="text" class="form-control auto-save" id="addr_soi" name="addr_soi" placeholder="ซอย">
            <label for="addr_soi">ซอย</label>
        </div>
        <div class="col-md-2 form-floating">
            <input type="text" class="form-control auto-save" id="addr_road" name="addr_road" placeholder="ถนน">
            <label for="addr_road">ถนน</label>
        </div>
        <div class="col-md-3 form-floating">
            <select class="form-select province auto-save" id="addr_province" name="addr_province" aria-label="เลือกจังหวัด">
                <option selected disabled>เลือกจังหวัด</option>
            </select>
            <label for="province">จังหวัด</label>
        </div>
        <div class="col-md-3 form-floating">
            <select class="form-select district auto-save" id="addr_district" name="addr_district" aria-label="เลือกอำเภอ"
                disabled>
                <option selected disabled>เลือกอำเภอ</option>
            </select>
            <label for="district">อำเภอ</label>
        </div>
        <div class="col-md-3 form-floating">
            <select class="form-select subdistrict auto-save" id="addr_subdistrict" name="addr_subdistrict" aria-label="เลือกตำบล"
                disabled>
                <option selected disabled>เลือกตำบล</option>
            </select>
            <label for="subdistrict">ตำบล</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control auto-save" id="addr_postcode" name="addr_postcode"
                placeholder="รหัสไปรษณีย์">
            <label for="addr_postcode">รหัสไปรษณีย์</label>
        </div>

        <!-- <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="same_address">
            <label class="form-check-label" for="same_address">
                ที่อยู่ปัจจุบันตรงกับทะเบียนบ้าน
            </label>
        </div> -->

        <h6 class="m-0 mt-4">ที่อยู่ปัจจุบัน</h6>
        <div class="row g-3 mt-0" id="current_address_section">
            <div class="col-md-2 form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_house_no" name="curr_addr_house_no"
                    placeholder="บ้านเลขที่">
                <label for="curr_addr_house_no">บ้านเลขที่</label>
            </div>
            <div class="col-md-2 form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_moo" name="curr_addr_moo" placeholder="หมู่ที่">
                <label for="curr_addr_moo">หมู่ที่</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_village" name="curr_addr_village"
                    placeholder="หมู่บ้าน / อาคาร">
                <label for="curr_addr_village">หมู่บ้าน / อาคาร</label>
            </div>
            <div class="col-md-2 form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_soi" name="curr_addr_soi" placeholder="ซอย">
                <label for="curr_addr_soi">ซอย</label>
            </div>
            <div class="col-md-2 form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_road" name="curr_addr_road" placeholder="ถนน">
                <label for="curr_addr_road">ถนน</label>
            </div>
            <div class="col-md-3 form-floating">
                <select class="form-select curr_province auto-save" id="curr_addr_province" name="curr_addr_province"
                    aria-label="เลือกจังหวัด">
                    <option selected disabled>เลือกจังหวัด</option>
                </select>
                <label for="curr_province">จังหวัด</label>
            </div>
            <div class="col-md-3 form-floating">
                <select class="form-select curr_district auto-save" id="curr_addr_district" name="curr_addr_district"
                    aria-label="เลือกอำเภอ" disabled>
                    <option selected disabled>เลือกอำเภอ</option>
                </select>
                <label for="curr_district">อำเภอ</label>
            </div>
            <div class="col-md-3 form-floating">
                <select class="form-select curr_subdistrict auto-save" id="curr_addr_subdistrict" name="curr_addr_subdistrict"
                    aria-label="เลือกตำบล" disabled>
                    <option selected disabled>เลือกตำบล</option>
                </select>
                <label for="curr_subdistrict">ตำบล</label>
            </div>
            <div class="col-md-3 form-floating">
                <input type="text" class="form-control auto-save" id="curr_addr_postcode" name="curr_addr_postcode"
                    placeholder="รหัสไปรษณีย์">
                <label for="curr_addr_postcode">รหัสไปรษณีย์</label>
            </div>
        </div>

        <hr>


        <h6 class="m-0">โซลเชียล</h6>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_facebook auto-save" id="pers_facebook" name="pers_facebook"
                placeholder="Facebook">
            <label for="pers_facebook">Facebook</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_instagram auto-save" id="pers_instagram" name="pers_instagram"
                placeholder="Facebook">
            <label for="pers_instagram">Instagram</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_youtube auto-save" id="pers_youtube" name="pers_youtube"
                placeholder="Facebook">
            <label for="pers_youtube">Youtube</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_line auto-save auto-save" id="pers_line" name="pers_line" placeholder="Facebook">
            <label for="pers_line">Line</label>
        </div>
        <hr>

        <h5 class="m-0">สถาบันครอบครัว</h5>
        <div class="col-md-6 form-floating">
            <select class="form-select pers_marital_status auto-save" id="pers_marital_status" name="pers_marital_status"
                aria-label="เลือกสถานภาพสมรส">
                <option value="">เลือก...</option>
                <option value="โสด">โสด</option>
                <option value="สมรส">สมรส</option>
                <option value="หม้าย">หม้าย</option>
                <option value="หย่า">หย่า</option>
                <option value="แยกกันอยู่">แยกกันอยู่</option>
            </select>
            <label for="pers_marital_status">สถานภาพสมรส</label>
        </div>

        <div class="">
            <!-- ปุ่มเพิ่มสมาชิก -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>รายการสมาชิก</h5>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#familyModal">เพิ่มสมาชิก</a>
            </div>

            <!-- ตารางแสดงสมาชิก -->
            <table class="table table-bordered" id="familyTable">
                <thead>
                    <tr>
                        <th>ชื่อ</th>
                        <th>ความสัมพันธ์</th>
                        <th>อายุ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- สมาชิกจะถูกเพิ่มที่นี่ -->
                </tbody>
            </table>
        </div>


        <!-- <div class="col-12">
            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
        </div> -->
    </div>
</form>

<!-- Modal แบบฟอร์มกรอกสมาชิก -->
<div class="modal fade" id="familyModal" tabindex="-1" aria-labelledby="familyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="familyForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="familyModalLabel">เพิ่มสมาชิกในครอบครัว</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">ชื่อ</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="relation" class="form-label">ความสัมพันธ์</label>
                        <input type="text" class="form-control" id="relation" required>
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">อายุ</label>
                        <input type="number" class="form-control" id="age" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="save-status" style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #f0f0f0;
    color: #333;
    padding: 8px 16px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    display: none;
    z-index: 9999;
    font-size: 14px;
">
    กำลังบันทึก...
</div>