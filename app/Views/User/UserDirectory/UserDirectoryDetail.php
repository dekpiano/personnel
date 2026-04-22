<?php
/**
 * View: UserDirectoryDetail
 * Version: Ultra Premium (Sneat Theme Enhanced)
 */

function getInitials($name) {
    return mb_substr($name, 0, 1, 'UTF-8');
}
?>
<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --premium-blue: #03c3ec;
        --premium-blue-light: #e7f3ff;
        --text-heading: #32475c;
        --text-body: #566a7f;
        --text-muted: #a1acb8;
        --glass-white: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    body {
        font-family: 'Kanit', 'Sarabun', sans-serif;
        background-color: #f5f5f9;
        color: var(--text-body);
    }

    /* Fix Footer Position */
    .container-p-y {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 12rem);
    }

    /* --- Back Button --- */
    .btn-back-premium {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        background: white;
        padding: 0.6rem 1.25rem;
        border-radius: 100px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-back-premium:hover {
        color: var(--premium-blue);
        transform: translateX(-5px);
        box-shadow: 0 4px 10px rgba(105, 108, 255, 0.15);
    }

    /* --- Luxury Page Header (Hero) --- */
    .detail-hero-section {
        background: linear-gradient(135deg, #03c3ec 0%, #029dbd 100%);
        border-radius: 40px;
        padding: 5rem 2.5rem 8rem;
        position: relative;
        overflow: hidden;
        margin-bottom: -5rem;
    }

    .detail-hero-section::after {
        content: '';
        position: absolute;
        width: 350px;
        height: 350px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -150px;
        right: -100px;
        filter: blur(80px);
    }

    /* --- Main Profile Container --- */
    .profile-master-card {
        background: var(--glass-white);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 32px;
        padding: 2.5rem;
        display: flex;
        align-items: flex-end;
        gap: 2.5rem;
        box-shadow: 0 20px 50px -15px rgba(67, 89, 113, 0.25);
        position: relative;
        z-index: 5;
    }

    .master-photo-wrap {
        width: 200px;
        height: 260px;
        flex-shrink: 0;
        border-radius: 24px;
        overflow: hidden;
        border: 6px solid white;
        box-shadow: 0 15px 35px -5px rgba(0,0,0,0.15);
        background: #f8fafc;
        position: relative;
        margin-top: -80px;
    }

    .master-photo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top;
    }

    .master-info-content {
        flex: 1;
        padding-bottom: 0.5rem;
    }

    .master-name-h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
    }

    .master-role-p {
        font-size: 1.2rem;
        font-weight: 500;
        color: var(--premium-blue);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0;
    }

    /* --- Section Cards --- */
    .premium-info-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px -5px rgba(67, 89, 113, 0.08);
        border: 1px solid #f1f5f9;
        height: 100%;
    }

    .card-title-premium {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8fafc;
    }

    .card-title-premium i {
        color: var(--premium-blue);
        background: var(--premium-blue-light);
        padding: 0.6rem;
        border-radius: 12px;
    }

    .p-info-row {
        display: flex;
        margin-bottom: 1.25rem;
        align-items: baseline;
    }
    .p-label {
        width: 140px;
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 500;
        flex-shrink: 0;
    }
    .p-value {
        font-weight: 600;
        color: var(--text-heading);
    }

    /* --- Timeline --- */
    .edu-timeline-p {
        position: relative;
        padding-left: 2.5rem;
    }
    .edu-timeline-p::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 2px;
        background: #eceef1;
    }
    .edu-item-p {
        position: relative;
        padding-bottom: 2rem;
    }
    .edu-item-p:last-child { padding-bottom: 0; }
    .edu-item-p::after {
        content: '';
        position: absolute;
        left: calc(-2.5rem + 0.35rem);
        top: 0.5rem;
        width: 12px;
        height: 12px;
        background: white;
        border: 3px solid var(--premium-blue);
        border-radius: 50%;
        box-shadow: 0 0 0 4px var(--premium-blue-light);
    }

    .edu-year-p {
        font-weight: 700;
        color: var(--premium-blue);
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
        display: block;
    }
    .edu-degree-p {
        font-weight: 700;
        color: var(--text-heading);
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }
    .edu-school-p {
        font-size: 0.9rem;
        color: var(--text-body);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* --- Contact Chips --- */
    .contact-card-link {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.25rem;
        background: #f8fafc;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
    .contact-card-link:hover {
        background: var(--premium-blue-light);
        transform: translateX(10px);
    }
    .contact-icon-circle {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--premium-blue);
        font-size: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .contact-val-text {
        font-weight: 700;
        color: var(--text-heading);
        font-size: 0.95rem;
        display: block;
    }
    .contact-lab-text {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .initials-detail {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        font-weight: 800;
    }

    @media (max-width: 991px) {
        .profile-master-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1.5rem;
        }
        .master-photo-wrap {
            margin-top: -100px;
        }
        .master-name-h1 {
            font-size: 2rem;
        }
        .p-info-row {
            flex-direction: column;
            align-items: center;
        }
        .p-label { width: 100%; margin-bottom: 0.25rem; }
    }
</style>

<!-- Hidden Header spacing -->
<div class="mb-4"></div>

<a href="<?= base_url('directory') ?>" class="btn-back-premium">
    <i class='bx bx-chevron-left fs-4'></i> กลับหน้าทำเนียบครู
</a>

<!-- Ultra Hero Background -->
<div class="detail-hero-section shadow-sm"></div>

<!-- Master Profile Card -->
<div class="container-xxl p-0">
    <div class="profile-master-card mx-auto" style="max-width: 1000px;">
        <div class="master-photo-wrap">
            <?php if (!empty($teacher->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $teacher->pers_img)): ?>
                <img class="lozad" 
                     data-src="<?= base_url('uploads/admin/Personnal/' . $teacher->pers_img) ?>"
                     src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                     alt="<?= esc($teacher->pers_firstname) ?>">
            <?php else: ?>
                <div class="initials-detail bg-label-primary text-primary">
                    <?= getInitials($teacher->pers_firstname) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="master-info-content">
            <h1 class="master-name-h1">
                <?= esc($teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname) ?>
                <?php if (!empty($teacher->pers_nickname)): ?>
                    <span class="badge bg-label-primary fs-6 fw-bold ms-2" style="vertical-align: middle;">(<?= esc($teacher->pers_nickname) ?>)</span>
                <?php endif; ?>
                <?php if (session()->get('logged_in') && session()->get('id') == $teacher->pers_id): ?>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill ms-3 mb-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class='bx bxs-edit-alt me-1'></i> แก้ไขข้อมูลส่วนตัว
                    </button>
                <?php endif; ?>
            </h1>
            <div class="master-role-p">
                <i class='bx bxs-check-shield fs-4'></i>
                <?= esc($teacher->posi_name) ?>
                <?php if (!empty($teacher->pers_academic)): ?>
                    <span class="text-muted mx-2">•</span>
                    <span class="text-muted fw-normal"><?= esc($teacher->pers_academic) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container-xxl px-0 mt-5">
    <div class="row g-4 justify-content-center" style="max-width: 1000px; margin: 0 auto;">
        <!-- Left Side: Info -->
        <div class="col-lg-7">
            <!-- General Info -->
            <div class="premium-info-card">
                <h3 class="card-title-premium">
                    <i class='bx bxs-user-pin'></i> ข้อมูลบุคลากร
                </h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-info-row">
                            <div class="p-label">ชื่อภาษาไทย</div>
                            <div class="p-value"><?= esc($teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname) ?></div>
                        </div>
                        <div class="p-info-row">
                            <div class="p-label">ส่วนงาน</div>
                            <div class="p-value">
                                <?php if(!empty($teacher->lear_namethai)): ?>
                                    กลุ่มสาระการเรียนรู้<?= esc($teacher->lear_namethai) ?>
                                <?php else: ?>
                                    ฝ่ายสำนักงาน / สนับสนุน
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($teacher->pers_groupleade)): ?>
                        <div class="p-info-row">
                            <div class="p-label">หน้าที่พิเศษ</div>
                            <div class="p-value">
                                <span class="badge bg-label-info"><?= esc($teacher->pers_groupleade) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="p-info-row">
                            <div class="p-label">อีเมล</div>
                            <div class="p-value"><?= esc($teacher->pers_username ?: '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Contact -->
        <div class="col-lg-5">
            <div class="premium-info-card">
                <h3 class="card-title-premium">
                    <i class='bx bxs-contact'></i> ช่องทางติดต่อ
                </h3>
                
                <?php if (!empty($teacher->pers_phone)): ?>
                <a href="tel:<?= esc($teacher->pers_phone) ?>" class="contact-card-link">
                    <div class="contact-icon-circle"><i class='bx bxs-phone'></i></div>
                    <div>
                        <span class="contact-lab-text">โทรศัพท์</span>
                        <span class="contact-val-text"><?= esc($teacher->pers_phone) ?></span>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (!empty($teacher->pers_facebook)): ?>
                <a href="<?= esc($teacher->pers_facebook) ?>" target="_blank" class="contact-card-link">
                    <div class="contact-icon-circle" style="color: #1877f2; background: #e7f2ff;"><i class='bx bxl-facebook'></i></div>
                    <div>
                        <span class="contact-lab-text">Facebook</span>
                        <span class="contact-val-text"><?= esc($teacher->pers_facebook) ?></span>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (!empty($teacher->pers_line)): ?>
                <div class="contact-card-link">
                    <div class="contact-icon-circle" style="color: #00c300; background: #e6f9e6;"><i class='bx bxl-line'></i></div>
                    <div>
                        <span class="contact-lab-text">LINE ID</span>
                        <span class="contact-val-text"><?= esc($teacher->pers_line) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($teacher->pers_instagram)): ?>
                <div class="contact-card-link">
                    <div class="contact-icon-circle" style="color: #e4405f; background: #fff5f7;"><i class='bx bxl-instagram'></i></div>
                    <div>
                        <span class="contact-lab-text">Instagram</span>
                        <span class="contact-val-text"><?= esc($teacher->pers_instagram) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($teacher->pers_youtube)): ?>
                <a href="<?= esc($teacher->pers_youtube) ?>" target="_blank" class="contact-card-link">
                    <div class="contact-icon-circle" style="color: #ff0000; background: #fff1f1;"><i class='bx bxl-youtube'></i></div>
                    <div>
                        <span class="contact-lab-text">YouTube Channel</span>
                        <span class="contact-val-text">เข้าชมช่อง</span>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Full Width: Education -->
        <?php if (!empty($education)): ?>
        <div class="col-12" style="max-width: 1000px;">
            <div class="premium-info-card">
                <h3 class="card-title-premium">
                    <i class='bx bxs-graduation'></i> ประวัติการศึกษา
                </h3>
                <div class="edu-timeline-p">
                    <?php foreach ($education as $edu): ?>
                    <div class="edu-item-p">
                        <span class="edu-year-p"><?= esc($edu->edu_year ?: '-') ?></span>
                        <div class="edu-degree-p"><?= esc($edu->edu_degree ?: '-') ?></div>
                        <div class="edu-school-p">
                            <i class='bx bxs-school'></i> <?= esc($edu->edu_institute ?: '-') ?>
                        </div>
                        <?php if(!empty($edu->edu_major)): ?>
                            <small class="text-muted d-block mt-1"><?= esc($edu->edu_major) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal & Scripts -->
<?php if (session()->get('logged_in') && session()->get('id') == $teacher->pers_id): ?>
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
            <div class="modal-header bg-primary py-4 px-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-3 me-3">
                        <i class='bx bxs-user-detail text-primary fs-3'></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">แก้ไขประวัติส่วนตัว</h5>
                        <small class="text-white opacity-75">อัปเดตข้อมูลให้เป็นปัจจุบันเพื่อแสดงในทำเนียบครู</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditProfile" class="modal-body p-4 bg-light">
                <input type="hidden" name="pers_id" value="<?= $teacher->pers_id ?>">
                
                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                    <i class='bx bxs-id-card me-2'></i> ข้อมูลพื้นฐาน
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pers_prefix" name="pers_prefix">
                                <?php foreach(['นาย', 'นาง', 'นางสาว', 'ว่าที่ร้อยตรี', 'ว่าที่ร้อยโท', 'ว่าที่ร้อยเอก', 'ดร.'] as $prefix): ?>
                                    <option value="<?= $prefix ?>" <?= ($teacher->pers_prefix == $prefix) ? 'selected' : '' ?>><?= $prefix ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pers_prefix">คำนำหน้า</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_firstname" name="pers_firstname" placeholder="ชื่อ" value="<?= esc($teacher->pers_firstname) ?>" required>
                            <label for="pers_firstname">ชื่อ</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_lastname" name="pers_lastname" placeholder="นามสกุล" value="<?= esc($teacher->pers_lastname) ?>" required>
                            <label for="pers_lastname">นามสกุล</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_nickname" name="pers_nickname" placeholder="ชื่อเล่น" value="<?= esc($teacher->pers_nickname) ?>">
                            <label for="pers_nickname">ชื่อเล่น</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <?php $bday = !empty($teacher->pers_britday) ? $teacher->pers_britday : ''; ?>
                            <input type="text" class="form-control" id="pers_britday" name="pers_britday" placeholder="วันเกิด" value="<?= $bday ?>">
                            <label for="pers_britday">วัน/เดือน/ปี เกิด</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pers_blood_type" name="pers_blood_type">
                                <option value="" <?= empty($teacher->pers_blood_type) ? 'selected' : '' ?>>ไม่ระบุ</option>
                                <?php foreach(['A', 'B', 'AB', 'O'] as $blood): ?>
                                    <option value="<?= $blood ?>" <?= ($teacher->pers_blood_type == $blood) ? 'selected' : '' ?>><?= $blood ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pers_blood_type">หมู่โลหิต</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pers_nationality" name="pers_nationality">
                                <?php foreach(['ไทย', 'ลาว', 'กัมพูชา', 'พม่า', 'จีน', 'อังกฤษ', 'อเมริกัน'] as $nation): ?>
                                    <option value="<?= $nation ?>" <?= ($teacher->pers_nationality == $nation) ? 'selected' : '' ?>><?= $nation ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pers_nationality">สัญชาติ</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pers_race" name="pers_race">
                                <?php foreach(['ไทย', 'ลาว', 'กัมพูชา', 'พม่า', 'จีน', 'อังกฤษ', 'อเมริกัน'] as $race): ?>
                                    <option value="<?= $race ?>" <?= ($teacher->pers_race == $race) ? 'selected' : '' ?>><?= $race ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pers_race">เชื้อชาติ</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pers_religion" name="pers_religion">
                                <?php foreach(['พุทธ', 'คริสต์', 'อิสลาม', 'ซิกข์', 'ฮินดู', 'ไม่ระบุ'] as $rel): ?>
                                    <option value="<?= $rel ?>" <?= ($teacher->pers_religion == $rel) ? 'selected' : '' ?>><?= $rel ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pers_religion">ศาสนา</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pers_marital_status" name="pers_marital_status">
                                <option value="" <?= empty($teacher->pers_marital_status) ? 'selected' : '' ?>>ไม่ระบุ</option>
                                <?php foreach(['โสด', 'สมรส', 'หย่าร้าง', 'หม้าย'] as $status): ?>
                                    <option value="<?= $status ?>" <?= ($teacher->pers_marital_status == $status) ? 'selected' : '' ?>><?= $status ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pers_marital_status">สถานภาพสมรส</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control" id="pers_phone" name="pers_phone" placeholder="เบอร์โทรศัพท์" value="<?= esc($teacher->pers_phone) ?>">
                            <label for="pers_phone">เบอร์โทรศัพท์</label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="pers_address" name="pers_address" placeholder="ที่อยู่ปัจจุบัน" style="height: 100px"><?= esc($teacher->pers_address) ?></textarea>
                            <label for="pers_address">ที่อยู่ปัจจุบัน</label>
                        </div>
                    </div>
                </div>

                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                    <i class='bx bxl-meta me-2'></i> โซเชียลมีเดีย
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_line" name="pers_line" placeholder="ID Line" value="<?= esc($teacher->pers_line) ?>">
                            <label for="pers_line">ID Line</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_instagram" name="pers_instagram" placeholder="Username Instagram" value="<?= esc($teacher->pers_instagram) ?>">
                            <label for="pers_instagram">Instagram</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_facebook" name="pers_facebook" placeholder="ลิงก์ Facebook" value="<?= esc($teacher->pers_facebook) ?>">
                            <label for="pers_facebook">ลิงก์ Facebook (URL)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pers_youtube" name="pers_youtube" placeholder="ลิงก์ YouTube" value="<?= esc($teacher->pers_youtube) ?>">
                            <label for="pers_youtube">ลิงก์ YouTube (URL)</label>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 mt-3 mb-0 d-flex align-items-center">
                    <i class='bx bx-info-circle me-3 fs-3'></i>
                    <small>ข้อมูลการทำงานและตำแหน่งทางราชการ สามารถแจ้งแก้ไขได้ที่งานบุคคล (Admin)</small>
                </div>
            </form>
            <div class="modal-footer border-0 p-4 bg-light">
                <button type="button" class="btn btn-label-secondary px-4 py-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" form="formEditProfile" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                    <i class='bx bx-save me-1'></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const observer = lozad('.lozad', {
        loaded: function(el) {
            el.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 500, fill: 'forwards' });
        }
    });
    observer.observe();

    // Initialize Flatpickr if exists
    if ($('#pers_britday').length > 0) {
        flatpickr("#pers_britday", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            locale: "th"
        });
    }

    $('#formEditProfile').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).closest('.modal-content').find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> กำลังบันทึก...');
        
        $.ajax({
            url: '<?= base_url('directory/save-profile') ?>',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาดระบบ',
                    text: 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาลองใหม่'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
