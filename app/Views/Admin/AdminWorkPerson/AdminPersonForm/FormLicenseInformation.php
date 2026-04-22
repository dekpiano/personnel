<style>
    .license-upload-card {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #f8faff;
        cursor: pointer;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .license-upload-card:hover {
        border-color: var(--primary-blue);
        background: rgba(0, 123, 255, 0.05);
    }
    .license-upload-card.has-file {
        border-style: solid;
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.05);
    }
    .license-upload-card .upload-icon {
        font-size: 2.5rem;
        color: #adb5bd;
        margin-bottom: 0.5rem;
    }
    .license-upload-card.has-file .upload-icon {
        color: #28a745;
    }
    .license-upload-card .doc-title {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    .license-upload-card .doc-hint {
        font-size: 0.7rem;
        color: #adb5bd;
    }
    .license-upload-card .file-name {
        font-size: 0.75rem;
        color: #28a745;
        margin-top: 0.5rem;
        word-break: break-all;
        max-width: 100%;
    }
    .license-upload-card .file-actions {
        margin-top: 0.5rem;
        display: flex;
        gap: 0.5rem;
    }
    .license-upload-card .file-actions .btn {
        padding: 0.2rem 0.6rem;
        font-size: 0.7rem;
    }
</style>

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
        </div>
    </div>

    <!-- สำเนาใบประกอบวิชาชีพ Section -->
    <div class="col-12 mt-4">
        <h6 class="fw-bold text-primary mb-4 d-flex align-items-center">
            <i class='bx bx-file fs-4 me-2'></i> สำเนาใบประกอบวิชาชีพ
        </h6>
        <div class="row g-4">
            <!-- ใบประกอบวิชาชีพครู -->
            <div class="col-md-6">
                <div class="license-upload-card <?= !empty($DocTeacherLicense->file_path ?? '') ? 'has-file' : '' ?>" id="card-teacher_license" onclick="triggerDocUpload('teacher_license')">
                    <i class='bx <?= !empty($DocTeacherLicense->file_path ?? '') ? 'bx-check-circle' : 'bx-upload' ?> upload-icon'></i>
                    <div class="doc-title">สำเนาใบอนุญาตประกอบวิชาชีพครู</div>
                    <div class="doc-hint">PDF หรือ รูปภาพ (สูงสุด 5MB)</div>
                    <input type="file" id="file-teacher_license" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadPersonnelDoc('teacher_license', this)">
                    <?php if (!empty($DocTeacherLicense->file_path ?? '')): ?>
                    <div class="file-name"><?= basename($DocTeacherLicense->file_path) ?></div>
                    <div class="file-actions" onclick="event.stopPropagation();">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewPersonnelDoc('<?= $DocTeacherLicense->id ?>')"><i class='bx bx-show'></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePersonnelDoc('<?= $DocTeacherLicense->id ?>', 'teacher_license')"><i class='bx bx-trash'></i></button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- ใบประกอบวิชาชีพผู้บริหาร -->
            <div class="col-md-6">
                <div class="license-upload-card <?= !empty($DocAdminLicense->file_path ?? '') ? 'has-file' : '' ?>" id="card-admin_license" onclick="triggerDocUpload('admin_license')">
                    <i class='bx <?= !empty($DocAdminLicense->file_path ?? '') ? 'bx-check-circle' : 'bx-upload' ?> upload-icon'></i>
                    <div class="doc-title">สำเนาใบอนุญาตประกอบวิชาชีพผู้บริหาร</div>
                    <div class="doc-hint">PDF หรือ รูปภาพ (ถ้ามี)</div>
                    <input type="file" id="file-admin_license" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadPersonnelDoc('admin_license', this)">
                    <?php if (!empty($DocAdminLicense->file_path ?? '')): ?>
                    <div class="file-name"><?= basename($DocAdminLicense->file_path) ?></div>
                    <div class="file-actions" onclick="event.stopPropagation();">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewPersonnelDoc('<?= $DocAdminLicense->id ?>')"><i class='bx bx-show'></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePersonnelDoc('<?= $DocAdminLicense->id ?>', 'admin_license')"><i class='bx bx-trash'></i></button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 text-end mt-4">
        <button class="btn btn-primary btn-update-general shadow-sm" type="submit">
            <i class='bx bx-check-circle fs-5 me-2'></i> บันทึกข้อมูลใบประกอบวิชาชีพ
        </button>
    </div>
</form>


