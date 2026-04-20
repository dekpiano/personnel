<style>
    /* Custom Sidebar Animations & Blue Theme */
    #layout-menu {
        background: #ffffff !important;
        border-right: 1px solid rgba(0, 0, 0, 0.05);
    }

    .menu-inner .menu-item {
        opacity: 0;
        transform: translateX(-20px);
        animation: menuItemSlideIn 0.5s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    @keyframes menuItemSlideIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Staggered animation for menu items */
    .menu-inner .menu-item:nth-child(1) { animation-delay: 0.1s; }
    .menu-inner .menu-item:nth-child(2) { animation-delay: 0.15s; }
    .menu-inner .menu-item:nth-child(3) { animation-delay: 0.2s; }
    .menu-inner .menu-item:nth-child(4) { animation-delay: 0.25s; }
    .menu-inner .menu-item:nth-child(5) { animation-delay: 0.3s; }
    .menu-inner .menu-item:nth-child(6) { animation-delay: 0.35s; }
    .menu-inner .menu-item:nth-child(7) { animation-delay: 0.4s; }

    .menu-inner .menu-item.active > .menu-link {
        background: linear-gradient(90deg, #3b82f6 0%, #60efff 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        border-radius: 0 50px 50px 0;
        margin-right: 1rem;
    }

    .menu-inner .menu-item.active .menu-icon {
        color: #fff !important;
    }

    .menu-link {
        transition: all 0.3s ease !important;
    }

    .menu-item:not(.active) .menu-link:hover {
        background-color: #f0f7ff !important;
        color: #2563eb !important;
        transform: translateX(5px);
    }

    .menu-item:not(.active) .menu-link:hover .menu-icon {
        color: #2563eb !important;
        animation: pulseBlue 1.5s infinite;
    }

    @keyframes pulseBlue {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .app-brand {
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        margin-bottom: 0.5rem;
    }

    .menu-header-text {
        color: #94a3b8 !important;
        font-weight: 600;
        letter-spacing: 0.05em;
    }
</style>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="<?=base_url()?>" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="" width="40">
                    </span>
                    <span class="app-brand-text menu-text fw-bolder ms-2" style="color: #1e293b;">สกจ. บริหารงานบุคคล</span>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item <?php echo $UrlMenuMain == "Main"?"active":""?>">
                    <a href="<?=base_url();?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">หน้าแรก</div>
                    </a>
                </li>
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">งานบุคคล</span>
                </li>
                <li class="menu-item <?php echo @$UrlMenuMain == "Directory"?"active":""?>">
                    <a href="<?=base_url('directory');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-graduation"></i>
                        <div data-i18n="Analytics">ทำเนียบครูและบุคลากร</div>
                    </a>
                </li>
                <li class="menu-item <?php echo @$UrlMenuMain == "PA_FORM"?"active":""?>">
                    <a href="<?=base_url('pa-login');?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Analytics">แบบประเมิน PA</div>
                    </a>
                </li>
                 <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">โหลดเอกสาร</span>
                </li>
                <li class="menu-item <?php echo $UrlMenuMain == ""?"active":""?>">
                    <a href="https://documentcenter.skj.ac.th/category/dictation-person" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-receipt"></i>
                        <div data-i18n="Analytics">คำสั่ง</div>
                    </a>
                </li>
                <li class="menu-item <?php echo $UrlMenuMain == ""?"active":""?>">
                    <a href="https://documentcenter.skj.ac.th/category/form-person" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-file-blank"></i>
                        <div data-i18n="Analytics">แบบฟอร์ม</div>
                    </a>
                </li>
                
            </ul>
            <div>
                <?php if(isset($_SESSION['username']) && @$_SESSION['status'] == "AdminPersonnel" || @$_SESSION['status'] == 'ManagerPersonnel'): ?>
                <ul class="menu-inner py-1">
                    <li class="menu-item">
                        <!-- data-bs-toggle="modal" data-bs-target="#modalToggle" -->
                        <a href="<?=base_url('Admin/Home');?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-key"></i>
                            <div data-i18n="Analytics">จัดการข้อมูลระบบ</div>
                        </a>
                    </li>
                </ul>
                <?php elseif(isset($_SESSION['username'])) :?>
                    <ul class="menu-inner py-1">
                    <li class="menu-item <?php echo $UrlMenuMain == "LoginOfficerPersonnel"?"active":""?>">
                        <!-- data-bs-toggle="modal" data-bs-target="#modalToggle" -->
                        <a class="dropdown-item" href="<?=base_url('/LogoutOfficerPersonnel')?>">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">ออกจากระบบ</span>
                            </a>
                    </li>
                </ul>
                <?php else: ?>
                <ul class="menu-inner py-1">
                    <li class="menu-item <?php echo $UrlMenuMain == "LoginOfficerPersonnel"?"active":""?>">
                        <!-- data-bs-toggle="modal" data-bs-target="#modalToggle" -->
                        <a href="<?=base_url('LoginOfficerPersonnel?return_to='.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>"
                            class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-key"></i>
                            <div data-i18n="Analytics">เข้าสู่ระบบ </div>
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </aside>
        <!-- / Menu -->

        <!-- Modal 1-->
        <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="authentication-inner">
                            <!-- Register -->

                            <h4 class="mb-2">Welcome to Login SKJ E-Office 👋</h4>
                            <p class="mb-4">สำหรับเจ้าหน้าที่</p>


                            <div class="d-flex justify-content-center">
                                <?php //echo $GoogleButton; ?>
                            </div>

                            <!-- /Register -->
                        </div>
                    </div>
                </div>
            </div>
        </div>