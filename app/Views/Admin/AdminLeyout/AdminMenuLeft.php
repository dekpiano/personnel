<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <!-- Modern Brand Header -->
            <div class="app-brand demo d-flex align-items-center justify-content-between">
                <a href="<?= base_url('Admin/Home'); ?>" class="app-brand-link text-decoration-none d-flex align-items-center">
                    <div class="brand-logo-wrap me-2">
                        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo" width="26" height="26" style="object-fit: contain;">
                    </div>
                    <div class="d-flex flex-column">
                        <span class="brand-title">SKJ PERSONNEL</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="live-dot" style="width: 5px; height: 5px; background-color: #34d399;"></span>
                            <span class="brand-subtitle">ระบบงานบุคคล (เจ้าหน้าที่)</span>
                        </div>
                    </div>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none text-white">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <?php 
                // Quick notification badge for pending leave requests
                $badgePendingLeave = 0;
                try {
                    $badgePendingLeave = \Config\Database::connect()->table('tb_leave_requests')->where('leave_status', 'pending')->countAllResults();
                } catch (\Throwable $e) {
                    $badgePendingLeave = 0;
                }
                $SubRloes = explode('|', $_SESSION['rloes'] ?? '');
            ?>

            <ul class="menu-inner py-1">
                <!-- 1. ภาพรวมระบบ -->
                <li class="menu-item <?= ($uri->getSegment(2) == "Home" ? "active" : "") ?>">
                    <a href="<?= base_url('Admin/Home'); ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                        <div data-i18n="Analytics">หน้าแรก (Dashboard)</div>
                    </a>
                </li>

                <!-- 2. ข้อมูลบุคลากร -->
                <?php if ($_SESSION['status'] === "superadmin" || in_array("งานทะเบียนครูและบุคลากร", $SubRloes) || strpos($_SESSION['rloes'] ?? '', 'งานทะเบียนครูและบุคลากร') !== false) : ?>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">งานทะเบียนและเวลาทำงาน</span>
                    </li>

                    <li class="menu-item <?= ($uri->getSegment(2) == "WorkPerson" || $uri->getSegment(2) == "Board") ? "active open" : "" ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-id-card"></i>
                            <div data-i18n="Layouts">จัดการบุคลากร</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item <?= ($uri->getSegment(3) == "Personnel") ? "active" : "" ?>">
                                <a href="<?= base_url('Admin/WorkPerson/Personnel') ?>" class="menu-link">
                                    <div data-i18n="Without menu">ครูและบุคลากรทางการศึกษา</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == "Board") ? "active" : "" ?>">
                                <a href="<?= base_url('Admin/Board') ?>" class="menu-link">
                                    <div data-i18n="Without menu">คณะกรรมการสถานศึกษา</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- เวลาทำงาน -->
                    <li class="menu-item <?= ($uri->getSegment(2) == 'SaveAttendance') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-fingerprint"></i>
                            <div data-i18n="Layouts">บันทึกเวลาทำงาน</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == '') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/SaveAttendance') ?>" class="menu-link">
                                    <div data-i18n="Without menu">บันทึกการมาทำงาน (สแกนนิ้ว)</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == 'OnlineHistory') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/SaveAttendance/OnlineHistory') ?>" class="menu-link">
                                    <div data-i18n="Without menu">ประวัติเช็คชื่อออนไลน์ (Check-In)</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == 'SetupLocation') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/SaveAttendance/SetupLocation') ?>" class="menu-link">
                                    <div data-i18n="Without menu">ตั้งค่าพิกัดเช็คชื่อ</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == 'SetupFingerprint') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/SaveAttendance/SetupFingerprint') ?>" class="menu-link">
                                    <div data-i18n="Without menu">ตั้งค่าเครื่องสแกนนิ้ว</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == 'SetupTime') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/SaveAttendance/SetupTime') ?>" class="menu-link">
                                    <div data-i18n="Without menu">ตั้งค่าเวลามาทำงาน</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 3. จัดการการลา & วันหยุด -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">งานการลาและวันหยุด</span>
                    </li>
                    <li class="menu-item <?= ($uri->getSegment(2) == 'Leave' || $uri->getSegment(2) == 'Holiday') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                            <div data-i18n="Leave Management">ระบบจัดการการลา</div>
                            <?php if ($badgePendingLeave > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-auto px-2 py-1" style="font-size: 0.65rem;"><?= $badgePendingLeave ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($uri->getSegment(2) == 'Leave' && $uri->getSegment(3) != 'Settings') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/Leave') ?>" class="menu-link d-flex justify-content-between align-items-center">
                                    <div data-i18n="All Leaves">รายการขอลาทั้งหมด</div>
                                    <?php if ($badgePendingLeave > 0): ?>
                                        <span class="badge bg-danger rounded-pill px-2 py-0" style="font-size: 0.65rem;"><?= $badgePendingLeave ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == 'Leave' && $uri->getSegment(3) == 'Settings') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/Leave/Settings') ?>" class="menu-link">
                                    <div data-i18n="Leave Settings">ตั้งค่าโควตาการลา</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == 'Holiday') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/Holiday') ?>" class="menu-link">
                                    <div data-i18n="Holiday Settings">ปฏิทินวันหยุดราชการ</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- 4. งานการประเมิน PA -->
                <?php if ($_SESSION['status'] === "superadmin" || in_array('งานประเมิน pa', $SubRloes) || strpos($_SESSION['rloes'] ?? '', 'งานประเมิน pa') !== false) : ?>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">งานการประเมิน PA</span>
                    </li>
                    <li class="menu-item <?= (in_array($uri->getSegment(2), ["PaConfig", "PaAgreement", "PaReport"]) ? "active open" : "") ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-task"></i>
                            <div data-i18n="Layouts">ระบบประเมินผล PA</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item <?= ($uri->getSegment(2) == 'PaAgreement') ? 'active' : '' ?>">
                                <a href="<?= base_url('Admin/PaAgreement') ?>" class="menu-link">
                                    <div data-i18n="Without menu">จัดการข้อตกลง PA (รายคน)</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == "PaConfig" && $uri->getSegment(3) != "Rubrics") ? "active" : "" ?>">
                                <a href="<?= base_url('Admin/PaConfig') ?>" class="menu-link">
                                    <div data-i18n="Without menu">ตั้งค่าผู้ประเมิน PA</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(3) == "Rubrics") ? "active" : "" ?>">
                                <a href="<?= base_url('Admin/PaConfig/Rubrics') ?>" class="menu-link">
                                    <div data-i18n="Without menu">จัดการหัวข้อการประเมิน</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($uri->getSegment(2) == "PaReport") ? "active" : "" ?>">
                                <a href="<?= base_url('Admin/PaReport') ?>" class="menu-link">
                                    <div data-i18n="Without menu">รายงานสรุปผลประเมิน</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item <?= ($uri->getSegment(2) == "TeacherEvaluation" ? "active" : "") ?>">
                        <a href="<?= base_url('Admin/TeacherEvaluation') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-medal"></i>
                            <div data-i18n="Analytics">ตรวจผลการปฏิบัติงาน</div>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- 5. ตั้งค่า & สิทธิ์ระบบ -->
                <?php if ($_SESSION['status'] === "superadmin") : ?>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">ความปลอดภัยและระบบ</span>
                    </li>
                    <li class="menu-item <?= ($uri->getSegment(2) == "Rloes" ? "active" : "") ?>">
                        <a href="<?= base_url('Admin/Rloes/Setting'); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-lock-alt"></i>
                            <div data-i18n="Analytics">กำหนดสิทธิ์ผู้ใช้งาน</div>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </aside>
        <!-- / Menu -->