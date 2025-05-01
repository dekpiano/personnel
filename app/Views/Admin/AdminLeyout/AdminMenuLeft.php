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
                    <span class="app-brand-text menu-text fw-bolder ms-2">สกจ.งานบุคลากร <small>(เจ้าหน้าที่)</small></span>
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
                <?php $SubRloes = explode(',',$_SESSION['rloes']); ?>

                
                <?php if(in_array("งานทะเบียนครูและบุคลากร",$SubRloes)) :?>
                <li class="menu-item <?php echo $uri->getSegment(2) == "WorkPerson"?"active open":""?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">งานบุคลากร</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item <?php echo $uri->getSegment(3) == "Personnel"?"active":""?>">
                            <a href="<?=base_url('Admin/WorkPerson/Personnel')?>" class="menu-link">
                                <div data-i18n="Without menu">ทะเบียนครูและบุคลากรทางการศึกษา</div>
                            </a>
                        </li>

                    </ul>
                </li>
                <?php endif; ?>
            </ul>

            <?php if($_SESSION['id'] == "pers_021") : ?>
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