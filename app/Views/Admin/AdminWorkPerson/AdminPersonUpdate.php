<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary-blue: #03c3ec;
        --secondary-blue: #029dbd;
        --soft-bg: #f8faff;
        --card-sh: 0 10px 40px rgba(0,0,0,0.03);
    }

    .breadcrumb-custom {
        background: white;
        padding: 12px 24px;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    /* Modern Tabs Navigation */
    .tabs-wrapper {
        background: white;
        padding: 8px;
        border-radius: 16px;
        box-shadow: var(--card-sh);
        margin-bottom: 2.5rem;
    }
    .nav-pills-premium {
        border: none;
        gap: 8px;
    }
    .nav-pills-premium .nav-link {
        border-radius: 12px;
        padding: 12px 24px;
        color: #6c757d;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .nav-pills-premium .nav-link i {
        font-size: 1.25rem;
    }
    .nav-pills-premium .nav-link:hover {
        background: rgba(0, 123, 255, 0.05);
        color: var(--primary-blue);
    }
    .nav-pills-premium .nav-link.active {
        background: var(--primary-blue);
        color: white;
        box-shadow: 0 8px 20px rgba(0, 123, 255, 0.25);
    }

    /* Profile Side Card */
    .profile-side-card {
        border: none;
        border-radius: 24px;
        background: white;
        box-shadow: var(--card-sh);
        overflow: hidden;
    }
    .profile-img-header {
        height: 100px;
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    }
    .profile-img-container {
        margin-top: -60px;
        margin-bottom: 1.5rem;
        position: relative;
        display: inline-block;
    }
    .profile-img-update {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        background: #f8f9fa;
    }
    .btn-change-photo {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        bottom: 5px;
        right: 5px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        color: var(--primary-blue);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-change-photo:hover {
        background: var(--primary-blue);
        color: white;
        transform: scale(1.1);
    }

    /* Content Area */
    .premium-tab-content {
        background: white;
        border-radius: 24px;
        box-shadow: var(--card-sh);
        border: none;
    }

    /* Modal Styling */
    .modal-content-premium {
        border: none;
        border-radius: 24px;
        overflow: hidden;
    }
    .modal-header-premium {
        background: var(--soft-bg);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem 2rem;
    }
</style>

<div class="breadcrumb-custom">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('Admin/Home') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('Admin/WorkPerson/Personnel') ?>">Personnel</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page"><?= $title; ?></li>
        </ol>
    </nav>
</div>

<!-- Horizontal Tabs Navigation -->
<div class="tabs-wrapper">
    <div class="nav nav-pills nav-pills-premium nav-fill" id="v-pills-tab" role="tablist">
        <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab">
            <i class="bx bx-user-pin"></i> ข้อมูลทั่วไป
        </button>
        <button class="nav-link" id="v-pills-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-history" type="button" role="tab">
            <i class="bx bx-history"></i> ประวัติส่วนตัว
        </button>
        <button class="nav-link" id="v-pills-license-tab" data-bs-toggle="pill" data-bs-target="#v-pills-license" type="button" role="tab">
            <i class="bx bx-badge-check"></i> ใบประกอบวิชาชีพ
        </button>
        <button class="nav-link" id="v-pills-decoration-tab" data-bs-toggle="pill" data-bs-target="#v-pills-decoration" type="button" role="tab">
            <i class="bx bx-medal"></i> เครื่องราชอิสริยาภรณ์
        </button>
        <button class="nav-link" id="v-pills-training-tab" data-bs-toggle="pill" data-bs-target="#v-pills-training" type="button" role="tab">
            <i class="bx bx-book-open"></i> ประวัติการอบรม
        </button>
        <button class="nav-link" id="v-pills-leave-tab" data-bs-toggle="pill" data-bs-target="#v-pills-leave" type="button" role="tab">
            <i class="bx bx-calendar-x"></i> ประวัติการลา
        </button>
        <button class="nav-link" id="v-pills-edu-tab" data-bs-toggle="pill" data-bs-target="#v-pills-edu" type="button" role="tab">
            <i class="bx bx-book-reader"></i> ข้อมูลการศึกษา
        </button>
        <button class="nav-link" id="v-pills-work-tab" data-bs-toggle="pill" data-bs-target="#v-pills-work" type="button" role="tab">
            <i class="bx bx-briefcase"></i> ประวัติการทำงาน
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Sidebar -->
    <div class="col-lg-3">
        <div class="profile-side-card text-center">
            <div class="profile-img-header"></div>
            <div class="card-body pt-0">
                <div class="profile-img-container">
                    <img class="lozad profile-img-update" id="output" data-src="<?= base_url('uploads/admin/Personnal/' . ($Pers->pers_img ?: 'default.png')); ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" loading="lazy" alt="Profile">
                    <form id="ChangeImgPersonnal" enctype="multipart/form-data">
                        <label for="pers_img_update" class="btn-change-photo">
                            <i class='bx bx-camera fs-5'></i>
                        </label>
                        <input type="file" name="pers_img" id="pers_img_update" class="crop-target d-none" data-preview="output" key-persid="<?= $Pers->pers_id ?>">
                    </form>
                </div>
                <h5 class="fw-bold mb-1 text-dark"><?= $Pers->pers_prefix . $Pers->pers_firstname . ' ' . $Pers->pers_lastname ?></h5>
                <p class="text-muted small mb-3 uppercase tracking-wider">ID: <?= $Pers->pers_id ?></p>
                
                <div class="pt-3 border-top mt-2">
                    <div class="d-flex justify-content-between px-3 mb-2">
                        <span class="text-muted small">สถานะ</span>
                        <span class="badge bg-label-success small"><?= $Pers->pers_status ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-9">
        <div class="tab-content premium-tab-content p-4" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormGeneralInformation.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-history" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormPresonHistory.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-license" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormLicenseInformation.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-decoration" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormDecoration.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-training" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormTraining.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-leave" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormLeaveHistory.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-edu" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormEducationInformation.php'); ?>
            </div>
            <div class="tab-pane fade" id="v-pills-work" role="tabpanel">
                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormWorkHistory.php'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modals with Premium Styling -->
<div class="modal fade" id="workHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="workHistoryForm" class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title fw-bold" id="workHistoryTitle"><i class='bx bx-briefcase me-2'></i>เพิ่มประวัติการทำงาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="work_date" name="work_date" placeholder="วันที่มีผล" required>
                            <label for="work_date">วัน เดือน ปี (ที่มีผล)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="work_change_type" name="work_change_type">
                                <option value="" selected disabled>เลือกรายการ</option>
                                <option value="บรรจุกลับเข้ารับราชการ">บรรจุกลับเข้ารับราชการ</option>
                                <option value="เริ่มรับราชการ">เริ่มรับราชการ</option>
                                <option value="เลื่อนขั้นเงินเดือน">เลื่อนขั้นเงินเดือน</option>
                                <option value="เลื่อนระดับ">เลื่อนระดับ / วิทยฐานะ</option>
                                <option value="ย้าย">ย้าย</option>
                                <option value="โอน">โอน</option>
                                <option value="ช่วยราชการ">ช่วยราชการ</option>
                                <option value="ลาศึกษาต่อ">ลาศึกษาต่อ</option>
                                <option value="กลับจากลาศึกษาต่อ">กลับจากลาศึกษาต่อ</option>
                                <option value="ออกจากราชการ">ออกจากราชการ</option>
                                <option value="อื่นๆ">อื่นๆ</option>
                            </select>
                            <label for="work_change_type">รายการเปลี่ยนแปลง</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select select2-tags" id="work_position" name="work_position" required>
                                <option value="" selected disabled>เลือกตำแหน่ง</option>
                                <option value="ครูผู้ช่วย">ครูผู้ช่วย</option>
                                <option value="ครู">ครู</option>
                                <option value="รองผู้อำนวยการสถานศึกษา">รองผู้อำนวยการสถานศึกษา</option>
                                <option value="ผู้อำนวยการสถานศึกษา">ผู้อำนวยการสถานศึกษา</option>
                                <option value="ศึกษานิเทศก์">ศึกษานิเทศก์</option>
                                <option value="บุคลากรทางการศึกษาอื่น (38 ค.(2))">บุคลากรทางการศึกษาอื่น (38 ค.(2))</option>
                                <option value="พนักงานราชการ">พนักงานราชการ</option>
                                <option value="ครูอัตราจ้าง">ครูอัตราจ้าง</option>
                                <option value="ลูกจ้างประจำ">ลูกจ้างประจำ</option>
                            </select>
                            <label for="work_position">ตำแหน่ง</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select select2-tags" id="work_level" name="work_level">
                                <option value="" selected disabled>เลือกระดับ / วิทยฐานะ</option>
                                <option value="-">- (ไม่มี)</option>
                                <option value="ปฏิบัติการ">ปฏิบัติการ</option>
                                <option value="คศ.1">คศ.1</option>
                                <option value="ชำนาญการ (คศ.2)">ชำนาญการ (คศ.2)</option>
                                <option value="ชำนาญการพิเศษ (คศ.3)">ชำนาญการพิเศษ (คศ.3)</option>
                                <option value="เชี่ยวชาญ (คศ.4)">เชี่ยวชาญ (คศ.4)</option>
                                <option value="เชี่ยวชาญพิเศษ (คศ.5)">เชี่ยวชาญพิเศษ (คศ.5)</option>
                            </select>
                            <label for="work_level">ระดับ / วิทยฐานะ</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="work_location" name="work_location" placeholder="สังกัด">
                            <label for="work_location">สังกัด / ส่วนราชการ / สถานศึกษา</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="work_salary" name="work_salary" placeholder="เงินเดือน" step="0.01">
                            <label for="work_salary">เงินเดือน (บาท)</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="work_command_no" name="work_command_no" placeholder="เลขที่คำสั่ง">
                            <label for="work_command_no">อ้างอิงเลขที่คำสั่ง</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="work_command_date" name="work_command_date" placeholder="ลงวันที่">
                            <label for="work_command_date">ลงวันที่ (ในคำสั่ง)</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" id="work_note" name="work_note" placeholder="หมายเหตุ" style="height: 100px"></textarea>
                            <label for="work_note">หมายเหตุ (ถ้ามี)</label>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="work_id" name="work_id">
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Education -->
<div class="modal fade" id="educationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-premium">
            <form id="educationForm">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold"><i class='bx bx-book-reader me-2'></i>ประวัติการศึกษา</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select" id="edu_level" required>
                                    <option value="" selected disabled>เลือกระดับการศึกษา</option>
                                    <option value="ต่ำกว่าปริญญาตรี">ต่ำกว่าปริญญาตรี</option>
                                    <option value="ปริญญาตรี">ปริญญาตรี</option>
                                    <option value="ปริญญาโท">ปริญญาโท</option>
                                    <option value="ปริญญาเอก">ปริญญาเอก</option>
                                </select>
                                <label for="edu_level">ระดับการศึกษา</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select select2-tags" id="edu_degree" required>
                                    <option value="" selected disabled>เลือกหรือพิมพ์วุฒิ (เช่น ค.บ.)</option>
                                </select>
                                <label for="edu_degree">วุฒิการศึกษา (ตัวย่อ)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select select2-tags" id="edu_major" required>
                                    <option value="" selected disabled>เลือกหรือพิมพ์สาขาวิชา</option>
                                </select>
                                <label for="edu_major">สาขาวิชาเอก</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select select2-tags" id="edu_institute" required>
                                    <option value="" selected disabled>เลือกหรือพิมพ์สถาบัน</option>
                                </select>
                                <label for="edu_institute">สถาบันการศึกษา</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="edu_year" required placeholder="ปี พ.ศ. ที่จบ">
                                <label for="edu_year">ปี พ.ศ. ที่จบ</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Decoration -->
<div class="modal fade" id="decorationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="decorationForm" class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title fw-bold"><i class='bx bx-medal me-2'></i>เพิ่มประวัติการได้รับเครื่องราชอิสริยาภรณ์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="pers_id" value="<?= $Pers->pers_id ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="deco_name" name="deco_name" placeholder="ชั้นตรา / ชนิดเครื่องราชฯ" required>
                            <label for="deco_name">ชั้นตรา / ชนิดเครื่องราชอิสริยาภรณ์</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="deco_date" name="deco_date" placeholder="วันที่ได้รับ" required>
                            <label for="deco_date">ปี/วันที่ได้รับ (ตามประกาศ)</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr class="my-2 opacity-50">
                        <small class="text-muted fw-bold d-block mb-3">รายละเอียดการลงในราชกิจจานุเบกษา</small>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="deco_gazette_date" name="deco_gazette_date" placeholder="วันที่ลงประกาศ">
                            <label for="deco_gazette_date">วันที่ลงประกาศ</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="deco_gazette_vol" name="deco_gazette_vol" placeholder="เล่มที่">
                            <label for="deco_gazette_vol">เล่มที่</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="deco_gazette_part" name="deco_gazette_part" placeholder="ตอนที่">
                            <label for="deco_gazette_part">ตอนที่</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="deco_gazette_page" name="deco_gazette_page" placeholder="หน้าที่">
                            <label for="deco_gazette_page">หน้าที่</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="deco_gazette_seq" name="deco_gazette_seq" placeholder="ลำดับที่">
                            <label for="deco_gazette_seq">ลำดับที่</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Training -->
<div class="modal fade" id="trainingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="trainingForm" class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title fw-bold"><i class='bx bx-book-open me-2'></i>เพิ่มประวัติการฝึกอบรมและสัมมนา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="pers_id" value="<?= $Pers->pers_id ?>">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="train_name" name="train_name" placeholder="ชื่อหลักสูตร / โครงการ" required>
                            <label for="train_name">ชื่อหลักสูตร / โครงการการอบรม</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="train_location" name="train_location" placeholder="สถานที่ / หน่วยงานที่จัด">
                            <label for="train_location">สถานที่ / หน่วยงานที่จัด</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="train_start_date" name="train_start_date" placeholder="วันที่เริ่ม" required>
                            <label for="train_start_date">วันที่เริ่มอบรม</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="train_end_date" name="train_end_date" placeholder="วันที่สิ้นสุด">
                            <label for="train_end_date">วันที่สิ้นสุด</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="train_hours" name="train_hours" placeholder="จำนวนชั่วโมง">
                            <label for="train_hours">จำนวนชั่วโมง</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="train_certificate" name="train_certificate" placeholder="วุฒิบัตร / ผลการอบรม">
                            <label for="train_certificate">วุฒิบัตรที่ได้รับ / ผลการอบรม</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Leave -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="leaveForm" class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title fw-bold"><i class='bx bx-calendar-x me-2'></i>เพิ่มประวัติการลา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="pers_id" value="<?= $Pers->pers_id ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="" selected disabled>เลือกประเภทการลา</option>
                                <option value="ลาป่วย">ลาป่วย</option>
                                <option value="ลากิจส่วนตัว">ลากิจส่วนตัว</option>
                                <option value="ลาพักผ่อน">ลาพักผ่อน</option>
                                <option value="ลาคลอดบุตร">ลาคลอดบุตร</option>
                                <option value="ลาไปช่วยเหลือภริยาที่คลอดบุตร">ลาไปช่วยเหลือภริยาที่คลอดบุตร</option>
                                <option value="ลาอุปสมบทหรือลาไปประกอบพิธีฮัจญ์">ลาอุปสมบทหรือลาไปประกอบพิธีฮัจญ์</option>
                                <option value="ลาเข้ารับการตรวจเลือกหรือเข้ารับการเตรียมพล">ลาเข้ารับการตรวจเลือกหรือเข้ารับการเตรียมพล</option>
                                <option value="ลาไปศึกษา ฝึกอบรม ดูงาน หรือปฏิบัติการวิจัย">ลาไปศึกษา ฝึกอบรม ดูงาน หรือปฏิบัติการวิจัย</option>
                                <option value="ลาไปปฏิบัติงานในองค์การระหว่างประเทศ">ลาไปปฏิบัติงานในองค์การระหว่างประเทศ</option>
                                <option value="ลาติดตามคู่สมรส">ลาติดตามคู่สมรส</option>
                                <option value="ลาไปฟื้นฟูสมรรถภาพด้านอาชีพ">ลาไปฟื้นฟูสมรรถภาพด้านอาชีพ</option>
                            </select>
                            <label for="leave_type">ประเภทการลา</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.5" class="form-control" id="leave_days" name="leave_days" placeholder="จำนวนวัน" required>
                            <label for="leave_days">จำนวนวันลา</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="leave_start_date" name="leave_start_date" placeholder="วันที่เริ่ม" required>
                            <label for="leave_start_date">ตั้งแต่วันที่</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control selectorEdit" id="leave_end_date" name="leave_end_date" placeholder="ถึงวันที่" required>
                            <label for="leave_end_date">ถึงวันที่</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="หมายเหตุ" id="leave_note" name="leave_note" style="height: 100px"></textarea>
                            <label for="leave_note">หมายเหตุ</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.4"></script>
<script src="<?= base_url() ?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>
<?= $this->endSection() ?>
