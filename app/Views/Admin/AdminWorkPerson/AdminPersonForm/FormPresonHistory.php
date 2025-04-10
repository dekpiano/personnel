<form class="row g-3 needs-validation" id="FormPersonnalHistory" method="post" novalidate>
    <div class="row g-3">
        <h6 class="mb-0">ข้อมูลพื้นฐาน</h6>
        <div class="col-md-3 form-floating">
            <select class="form-select pers_prefix" id="pers_prefix" name="pers_prefix" aria-label="เลือกคำนำหน้า">
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
            <input type="text" class="form-control pers_firstname" id="pers_firstname" name="pers_firstname"
                placeholder="ชื่อจริง">
            <label for="pers_firstname">ชื่อจริง</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_lastname" id="pers_lastname" name="pers_lastname"
                placeholder="ชื่อ-สกุล">
            <label for="pers_lastname">ชื่อสกุล</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_nickname" id="pers_nickname" name="pers_nickname"
                placeholder="ชื่อเล่น">
            <label for="pers_nickname">ชื่อเล่น</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control pers_id_card" id="pers_id_card" name="pers_id_card"
                placeholder="เลขบัตรประชาชน">
            <label for="pers_id_card">เลขบัตรประชาชน</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control selectorEdit pers_britday" id="pers_britday" name="pers_britday"
                placeholder="" value="<?=$Pers->pers_britday?>" autocomplete="off">
            <label for="pers_britday">วันเกิด</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="number" class="form-control pers_age" id="pers_age" name="pers_age" placeholder="อายุ"
                readonly>
            <label for="pers_age">อายุ</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_nationality" id="pers_nationality" name="pers_nationality"
                placeholder="สัญชาติ">
            <label for="pers_nationality">สัญชาติ</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_race" id="pers_race" name="pers_race" placeholder="เชื้อชาติ">
            <label for="pers_race">เชื้อชาติ</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_religion" id="pers_religion" name="pers_religion"
                placeholder="ศาสนา">
            <label for="pers_religion">ศาสนา</label>
        </div>

        <div class="col-md-4 form-floating">
            <select class="form-select pers_blood_type" id="pers_blood_type" name="pers_blood_type"
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
            <input type="email" class="form-control pers_email" id="pers_email" name="pers_email" placeholder="อีเมล">
            <label for="pers_email">อีเมล</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control pers_phone" id="pers_phone" name="pers_phone"
                placeholder="เบอร์โทรศัพท์">
            <label for="pers_phone">เบอร์โทรศัพท์</label>
        </div>
        <hr>
        <h6 class="mb-0 font-weight-bold">ที่อยู่ตามทะเบียนบ้าน</h6>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control" id="addr_house_no" name="addr_house_no" placeholder="บ้านเลขที่">
            <label for="addr_house_no">บ้านเลขที่</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control" id="addr_moo" name="addr_moo" placeholder="หมู่ที่">
            <label for="addr_moo">หมู่ที่</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="addr_village" name="addr_village"
                placeholder="หมู่บ้าน / อาคาร">
            <label for="addr_village">หมู่บ้าน / อาคาร</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="addr_soi" name="addr_soi" placeholder="ซอย">
            <label for="addr_soi">ซอย</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="addr_road" name="addr_road" placeholder="ถนน">
            <label for="addr_road">ถนน</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="addr_subdistrict" name="addr_subdistrict"
                placeholder="ตำบล / แขวง">
            <label for="addr_subdistrict">ตำบล / แขวง</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="addr_district" name="addr_district" placeholder="อำเภอ / เขต">
            <label for="addr_district">อำเภอ / เขต</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="addr_province" name="addr_province" placeholder="จังหวัด">
            <label for="addr_province">จังหวัด</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="addr_postcode" name="addr_postcode" placeholder="รหัสไปรษณีย์">
            <label for="addr_postcode">รหัสไปรษณีย์</label>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="same_address">
            <label class="form-check-label" for="same_address">
                ที่อยู่ปัจจุบันตรงกับทะเบียนบ้าน
            </label>
        </div>

        <h6 class="m-0">ที่อยู่ปัจจุบัน</h6>
        <div class="row g-3 mt-0" id="current_address_section">
            <div class="col-md-3 form-floating">
                <input type="text" class="form-control" id="curr_addr_house_no" name="curr_addr_house_no"
                    placeholder="บ้านเลขที่">
                <label for="curr_addr_house_no">บ้านเลขที่</label>
            </div>
            <div class="col-md-3 form-floating">
                <input type="text" class="form-control" id="curr_addr_moo" name="curr_addr_moo" placeholder="หมู่ที่">
                <label for="curr_addr_moo">หมู่ที่</label>
            </div>
            <div class="col-md-6 form-floating">
                <input type="text" class="form-control" id="curr_addr_village" name="curr_addr_village"
                    placeholder="หมู่บ้าน / อาคาร">
                <label for="curr_addr_village">หมู่บ้าน / อาคาร</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control" id="curr_addr_soi" name="curr_addr_soi" placeholder="ซอย">
                <label for="curr_addr_soi">ซอย</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control" id="curr_addr_road" name="curr_addr_road" placeholder="ถนน">
                <label for="curr_addr_road">ถนน</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control" id="curr_addr_subdistrict" name="curr_addr_subdistrict"
                    placeholder="ตำบล / แขวง">
                <label for="curr_addr_subdistrict">ตำบล / แขวง</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control" id="curr_addr_district" name="curr_addr_district"
                    placeholder="อำเภอ / เขต">
                <label for="curr_addr_district">อำเภอ / เขต</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control" id="curr_addr_province" name="curr_addr_province"
                    placeholder="จังหวัด">
                <label for="curr_addr_province">จังหวัด</label>
            </div>
            <div class="col-md-4 form-floating">
                <input type="text" class="form-control" id="curr_addr_postcode" name="curr_addr_postcode"
                    placeholder="รหัสไปรษณีย์">
                <label for="curr_addr_postcode">รหัสไปรษณีย์</label>
            </div>
        </div>

        <hr>


        <h6 class="m-0">โซลเชียล</h6>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_facebook" id="pers_facebook" name="pers_facebook"
                placeholder="Facebook">
            <label for="pers_facebook">Facebook</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_instagram" id="pers_instagram" name="pers_instagram"
                placeholder="Facebook">
            <label for="pers_instagram">Instagram</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_youtube" id="pers_youtube" name="pers_youtube"
                placeholder="Facebook">
            <label for="pers_youtube">Youtube</label>
        </div>
        <div class="col-md-3 form-floating">
            <input type="text" class="form-control pers_line" id="pers_line" name="pers_line" placeholder="Facebook">
            <label for="pers_line">Line</label>
        </div>
        <hr>
        <h6 class="m-0">ครอบครัว</h6>
        <div class="col-md-6 form-floating">
            <select class="form-select pers_marital_status" id="pers_marital_status" name="pers_marital_status"
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

        <div id="family-section">
            <div class="row g-3 family-member mb-3">
                <div class="col-md-4 form-floating">
                    <input type="text" class="form-control" id="fam_name_1" name="fam_name[]" placeholder="ชื่อ">
                    <label for="fam_name_1">ชื่อสมาชิกในครอบครัว</label>
                </div>
                <div class="col-md-4 form-floating">
                    <select class="form-select" id="fam_relation_1" name="fam_relation[]" aria-label="ความสัมพันธ์">
                        <option value="">เลือก...</option>
                        <option value="บิดา">บิดา</option>
                        <option value="มารดา">มารดา</option>
                        <option value="คู่สมรส">คู่สมรส</option>
                        <option value="บุตร">บุตร</option>
                    </select>
                    <label for="fam_relation_1">ความสัมพันธ์</label>
                </div>
                <div class="col-md-2 form-floating">
                    <input type="date" class="form-control selectorEdit" id="fam_birth_date_1" name="fam_birth_date[]"
                        placeholder="วันเกิด">
                    <label for="fam_birth_date_1">วันเกิด</label>
                </div>
                <div class="col-md-2 form-floating">
                    <input type="text" class="form-control" id="fam_occupation_1" name="fam_occupation[]"
                        placeholder="อาชีพ">
                    <label for="fam_occupation_1">อาชีพ</label>
                </div>
            </div>
        </div>

        <div id="family-section"></div>

        <div class="mb-4 mt-0">
            <button type="button" class="btn btn-outline-secondary" id="add-family-member">+
                 เพิ่มสมาชิกครอบครัว</button>
        </div>



        <div class="col-12">
            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
        </div>
    </div>
</form>