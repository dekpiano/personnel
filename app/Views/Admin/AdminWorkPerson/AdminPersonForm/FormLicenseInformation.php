<form id="FormLicenseInfo" class="row g-4">
    <!-- Professional License Section -->
    <div class="col-12">
        <h6 class="fw-bold text-primary mb-4 d-flex align-items-center">
            <i class='bx bx-award fs-4 me-2'></i> ข้อมูลใบประกอบวิชาชีพทางการศึกษา
        </h6>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="pers_license_no" name="pers_license_no" placeholder="เลขที่ใบประกอบวิชาชีพ" value="<?= $Pers->pers_license_no ?? '' ?>">
                    <label for="pers_license_no">เลขที่ใบประกอบวิชาชีพ</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" class="form-control selectorEdit" id="pers_license_issue" name="pers_license_issue" placeholder="วันที่ออกบัตร" value="<?= $Pers->pers_license_issue ?? '' ?>">
                    <label for="pers_license_issue">วันที่ออกบัตร</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" class="form-control selectorEdit" id="pers_license_exp" name="pers_license_exp" placeholder="วันหมดอายุ" value="<?= $Pers->pers_license_exp ?? '' ?>">
                    <label for="pers_license_exp">วันที่ใบประกอบหมดอายุ</label>
                </div>
            </div>
            <div class="col-12 text-end mt-4">
                <button class="btn btn-primary btn-update-general shadow-sm" type="submit">
                    <i class='bx bx-check-circle fs-5 me-2'></i> บันทึกข้อมูลใบประกอบวิชาชีพ
                </button>
            </div>
        </div>
    </div>
</form>
