<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary-blue: #03c3ec;
        --soft-blue: rgba(0, 123, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.5);
    }

    .breadcrumb-custom {
        background: white;
        padding: 12px 24px;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-blue);
        margin-bottom: 2rem;
        padding-left: 15px;
        border-left: 5px solid var(--primary-blue);
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.5px;
    }

    /* Profile Upload Styling */
    .profile-upload-container {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        position: relative;
    }
    .profile-preview-wrapper {
        position: relative;
        width: 160px;
        height: 160px;
        margin: 0 auto 1.5rem;
    }
    .profile-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        background: #f8f9fa;
    }
    .upload-overlay-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 42px;
        height: 42px;
        background: var(--primary-blue);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .upload-overlay-btn:hover {
        transform: scale(1.1);
        background: #0056b3;
    }

    .card-premium-form {
        border: none;
        border-radius: 24px;
        background: white;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
    }

    /* Form Elements */
    .form-floating > .form-control:focus, 
    .form-floating > .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
    }
    
    .status-badge-wrapper {
        background: var(--soft-blue);
        padding: 1.5rem;
        border-radius: 18px;
        border: 1px dashed rgba(0, 123, 255, 0.2);
    }

    .btn-save-personnel {
        background: var(--primary-blue);
        border: none;
        padding: 14px 40px;
        border-radius: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-save-personnel:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(78, 84, 200, 0.4);
        background: #3f44a1;
    }
</style>

<div class="breadcrumb-custom">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('Admin/Home') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('Admin/WorkPerson/Personnel') ?>">Personnel Control</a></li>
            <li class="breadcrumb-item active fw-bold" aria-current="page"><?= $title; ?></li>
        </ol>
    </nav>
</div>

<form class="needs-validation" novalidate="" id="FormPersonnalAdd">
    <div class="row g-4">
        <!-- Profile Sidebar -->
        <div class="col-lg-3">
            <div class="profile-upload-container mb-4">
                <h6 class="fw-bold mb-4 text-muted small uppercase">รูปถ่ายบุคลากร</h6>
                <div class="profile-preview-wrapper">
                    <img class="profile-preview" id="output" src="<?= base_url('assets/img/avatars/1.png') ?>" alt="Preview">
                    <label for="pers_img" class="upload-overlay-btn">
                        <i class='bx bx-camera fs-5'></i>
                    </label>
                    <input type="file" name="pers_img" id="pers_img" class="crop-target d-none" data-preview="output">
                </div>
                <p class="text-muted small mb-0">รองรับไฟล์ JPG, PNG<br>ขนาดไม่เกิน 5MB</p>
            </div>

            <div class="card card-premium-form p-2">
                <div class="card-body">
                    <h6 class="fw-bold mb-4 text-muted small uppercase">ตั้งค่าสถานะ</h6>
                    <div class="status-badge-wrapper mb-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-primary">ID ประจำตัว (อัตโนมัติ)</label>
                            <input type="text" class="form-control bg-white fw-bold text-center border-0 fs-5" id="pers_id" name="pers_id" value="<?= $pers_id; ?>" readonly style="color: var(--primary-blue);">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small">สถานะการทำงาน</label>
                            <select class="form-select border-0 shadow-none bg-white fw-semibold" id="pers_status" name="pers_status" required="">
                                <option value="กำลังใช้งาน">กำลังใช้งาน</option>
                                <option value="ย้ายสถานศึกษา">ย้ายสถานศึกษา</option>
                                <option value="ลาออก">ลาออก</option>
                                <option value="เกษียรอายุ">เกษียรอายุ</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-lg-9">
            <div class="card card-premium-form p-4 mb-4">
                <div class="card-body">
                    <div class="form-section-title">
                        <i class='bx bx-user-circle fs-3'></i> ข้อมูลส่วนตัวพื้นฐาน
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-floating mb-1">
                                <select class="form-select" id="pers_prefix" name="pers_prefix" required="">
                                    <option value="">เลือกประเภท...</option>
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                    <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                                    <option value="ว่าที่ร้อยตรีหญิง">ว่าที่ร้อยตรีหญิง</option>
                                </select>
                                <label for="pers_prefix">คำนำหน้าชื่อ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" id="pers_firstname" name="pers_firstname" placeholder="ชื่อจริง" required="">
                                <label for="pers_firstname">ชื่อจริง</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" id="pers_lastname" name="pers_lastname" placeholder="นามสกุล" required="">
                                <label for="pers_lastname">นามสกุล</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-1">
                                <input type="tel" class="form-control pers_phone" id="pers_phone" name="pers_phone" placeholder="08x-xxx-xxxx">
                                <label for="pers_phone">เบอร์โทรศัพท์ติดต่อ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-1">
                                <input type="email" class="form-control" id="pers_username" placeholder="name@skj.ac.th" name="pers_username">
                                <label for="pers_username">อีเมลโรงเรียน (Login)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title mt-5">
                        <i class='bx bx-id-card fs-3'></i> ข้อมูลตำแหน่งและสังกัด
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating mb-1">
                                <select class="form-select select2Personnel" id="pers_position" name="pers_position" required="">
                                    <option value="">เลือกตำแหน่งหลัก...</option>
                                    <?php foreach ($position as $key => $value) : ?>
                                    <option value="<?= $value->posi_id; ?>"><?= $value->posi_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="pers_position">ตำแหน่งงานหลัก</label>
                            </div>
                        </div>
                        <div class="col-md-6" style="display:none;" id="show_position">
                            <div class="form-floating mb-1">
                                <select class="form-select select2Personnel" id="pers_workother_id" name="pers_workother_id">
                                    <option value="0">เลือกตำแหน่งสายสายงาน</option>
                                </select>
                                <label for="pers_workother_id">ตำแหน่งย่อย/สายงาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-4" style="display:none;" id="show_learning">
                            <div class="form-floating mb-1">
                                <select class="form-select select2Personnel" id="pers_learning" name="pers_learning">
                                    <option value="">เลือกกลุ่มสาระ...</option>
                                    <?php foreach ($learning as $key => $value) : ?>
                                    <option value="<?= $value->lear_id; ?>"><?= $value->lear_namethai; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="pers_learning">กลุ่มสาระการเรียนรู้</label>
                            </div>
                        </div>
                        <div class="col-md-4" style="display:none;" id="show_academic">
                            <?php $degee = array('ชำนาญการ', 'ชำนาญการพิเศษ', 'เชี่ยวชาญ', 'เชี่ยวชาญพิเศษ'); ?>
                            <div class="form-floating mb-1">
                                <select class="form-select select2Personnel" id="pers_academic" name="pers_academic">
                                    <option value="">ระบุวิทยฐานะ (ถ้ามี)</option>
                                    <?php foreach ($degee as $key => $value) : ?>
                                    <option value="<?= $value; ?>"><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="pers_academic">วิทยฐานะ</label>
                            </div>
                        </div>
                        <div class="col-md-4" style="display:none;" id="show_groupleade">
                            <div class="form-floating mb-1">
                                <select class="form-select select2Personnel" id="pers_groupleade" name="pers_groupleade">
                                    <option value="">ระบุตำแหน่งภายใน...</option>
                                    <option value="หัวหน้ากลุ่มสาระ">หัวหน้ากลุ่มสาระ</option>
                                    <option value="รองหัวหน้ากลุ่มสาระ">รองหัวหน้ากลุ่มสาระ</option>
                                </select>
                                <label for="pers_groupleade">หัวหน้า/รองหัวหน้า</label>
                            </div>
                        </div>
                        <div class="col-md-4" style="display:none;" id="show_faction">
                            <?php $factions = array('กลุ่มบริหารวิชาการ', 'กลุ่มบริหารงบประมาณ', 'กลุ่มบริหารงานบุคคล', 'กลุ่มบริหารทั่วไป'); ?>
                            <div class="form-floating mb-1">
                                <select class="form-select select2Personnel" id="pers_faction" name="pers_faction[]" multiple="multiple" data-placeholder="ระบุกลุ่มงานฝ่าย...">
                                    <?php foreach ($factions as $value) : ?>
                                    <option value="<?= $value; ?>"><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="pers_faction">กลุ่มงานฝ่าย</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pb-3 text-end">
                        <button class="btn btn-primary btn-save-personnel shadow-sm" type="submit">
                            <i class='bx bx-check-circle fs-3 me-2'></i> บันทึกบุคลากรใหม่
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.3"></script>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>
<?= $this->endSection() ?>

