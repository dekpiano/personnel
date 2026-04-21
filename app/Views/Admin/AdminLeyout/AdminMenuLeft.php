<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="index.html" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="" width="40">
                    </span>
                    <span class="app-brand-text menu-text fw-bolder ms-2">สกจ.งานบุคลากร
                        <small>(เจ้าหน้าที่)</small></span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item <?php echo ($uri->getSegment(2) == "Home"?"active":"")?>">
                    <a href="<?=base_url('Admin/Home');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">หน้าแรก </div>
                    </a>
                </li>

                <!-- Layouts -->
                <?php $SubRloes = explode('|',$_SESSION['rloes'] ?? ''); ?>


                <?php if($_SESSION['status'] === "superadmin" || in_array("งานทะเบียนครูและบุคลากร",$SubRloes) || strpos($_SESSION['rloes'] ?? '', 'งานทะเบียนครูและบุคลากร') !== false) :?>
                      <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">งานทะเบียนครูและบุคลากร</span>
                </li>
                <li class="menu-item <?php echo ($uri->getSegment(2) == "WorkPerson" || $uri->getSegment(2) == "Board")?"active open":""?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">จัดการบุคคลกร</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item <?php echo $uri->getSegment(3) == "Personnel"?"active":""?>">
                            <a href="<?=base_url('Admin/WorkPerson/Personnel')?>" class="menu-link">
                                <div data-i18n="Without menu">ทะเบียนครูและบุคลากรทางการศึกษา</div>
                            </a>
                        </li>
                        <li class="menu-item <?php echo $uri->getSegment(2) == "Board"?"active":""?>">
                            <a href="<?=base_url('Admin/Board')?>" class="menu-link">
                                <div data-i18n="Without menu">คณะกรรมการสถานศึกษา</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item <?php echo $uri->getSegment(2) == 'SaveAttendance' ? 'active open' : '' ?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-time-five"></i>
                        <div data-i18n="Layouts">ระบบเวลาทำงาน</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item <?php echo ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) != 'SetupFingerprint') ? 'active' : '' ?>">
                            <a href="<?=base_url('Admin/SaveAttendance')?>" class="menu-link">
                                <div data-i18n="Without menu">บันทึกการมาทำงาน</div>
                            </a>
                        </li>
                        <li class="menu-item <?php echo ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == 'SetupFingerprint') ? 'active' : '' ?>">
                            <a href="<?=base_url('Admin/SaveAttendance/SetupFingerprint')?>" class="menu-link">
                                <div data-i18n="Without menu">ตั้งค่าเครื่องสแกนนิ้ว</div>
                            </a>
                        </li>
                        <li class="menu-item <?php echo ($uri->getSegment(2) == 'SaveAttendance' && $uri->getSegment(3) == 'SetupTime') ? 'active' : '' ?>">
                            <a href="<?=base_url('Admin/SaveAttendance/SetupTime')?>" class="menu-link">
                                <div data-i18n="Without menu">ตั้งค่าเวลามาทำงาน</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item <?= ($uri->getSegment(2) == 'Leave' || $uri->getSegment(2) == 'Holiday') ? 'active open' : '' ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bi bi-calendar-check-fill"></i>
            <div data-i18n="Leave Management">ระบบจัดการการลา</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item <?= ($uri->getSegment(2) == 'Leave' && $uri->getSegment(3) != 'Settings') ? 'active' : '' ?>">
                <a href="<?= base_url('Admin/Leave') ?>" class="menu-link">
                    <div data-i18n="All Leaves">รายการขอลาทั้งหมด</div>
                </a>
            </li>
            <li class="menu-item <?= ($uri->getSegment(2) == 'Leave' && $uri->getSegment(3) == 'Settings') ? 'active' : '' ?>">
                <a href="<?= base_url('Admin/Leave/Settings') ?>" class="menu-link">
                    <div data-i18n="Leave Settings">ตั้งค่าโควตาการลา</div>
                </a>
            </li>
            <li class="menu-item <?= ($uri->getSegment(2) == 'Holiday') ? 'active' : '' ?>">
                <a href="<?= base_url('Admin/Holiday') ?>" class="menu-link">
                    <div data-i18n="Holiday Settings">ตั้งค่าวันหยุดราชการ</div>
                </a>
            </li>
        </ul>
    </li>
                <?php endif; ?>
                <?php if($_SESSION['status'] === "superadmin" || in_array('งานประเมิน pa',$SubRloes) || strpos($_SESSION['rloes'] ?? '', 'งานประเมิน pa') !== false) :?>
            <ul class="menu-inner py-1">
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">งานการประเมิน PA</span>
                </li>
                <li class="menu-item <?php echo (in_array($uri->getSegment(2), ["PaConfig", "PaReport"]) ? "active open" : "")?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Layouts">จัดการประเมิน PA</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item <?php echo ($uri->getSegment(2) == "PaConfig" && $uri->getSegment(3) != "Rubrics") ? "active" : ""?>">
                            <a href="<?=base_url('Admin/PaConfig')?>" class="menu-link">
                                <div data-i18n="Without menu">ตั้งค่าผู้ประเมิน PA</div>
                            </a>
                        </li>
                        <li class="menu-item <?php echo $uri->getSegment(3) == "Rubrics"? "active" : ""?>">
                            <a href="<?=base_url('Admin/PaConfig/Rubrics')?>" class="menu-link">
                                <div data-i18n="Without menu">จัดการหัวข้อการประเมิน</div>
                            </a>
                        </li>
                        <li class="menu-item <?php echo $uri->getSegment(2) == "PaReport"?"active":""?>">
                            <a href="<?=base_url('Admin/PaReport')?>" class="menu-link">
                                <div data-i18n="Without menu">รายงานประเมิน PA</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- New Section for Performance Evaluation -->
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">งานประเมินผลปฏิบัติงาน</span>
                </li>
                <li class="menu-item <?php echo ($uri->getSegment(2) == "TeacherEvaluation" ? "active" : "")?>">
                    <a href="<?=base_url('Admin/TeacherEvaluation')?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-medal"></i>
                        <div data-i18n="Analytics">ตรวจสอบการส่งงาน </div>
                    </a>
                </li>
            </ul>
            <?php endif; ?>
            </ul>

          

            <?php if($_SESSION['status'] === "superadmin") : ?>
            <div>
                <ul class="menu-inner py-1">
                    <li class="menu-item <?php echo $uri->getSegment(2) == "Rloes"?"active":""?>">
                        <a href="<?=base_url('Admin/Rloes/Setting');?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                            <div data-i18n="Analytics">กำหนดสิทธิ์ใช้งาน </div>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </aside>
        <!-- / Menu -->