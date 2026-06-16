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

    /* Sidebar Animated Background SVG */
    .sidebar-bg-decor {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        width: 140px;
        height: 140px;
        opacity: 0.12;
        pointer-events: none;
        z-index: 1;
        animation: float-sidebar 10s ease-in-out infinite alternate;
    }

    @keyframes float-sidebar {
        0% {
            transform: translateX(-50%) translateY(0) rotate(0deg);
        }
        100% {
            transform: translateX(-50%) translateY(-12px) rotate(4deg);
        }
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

            <!-- Sidebar bottom animated SVG decoration -->
            <svg class="sidebar-bg-decor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <!-- Outer Ring -->
                <circle cx="50" cy="50" r="45" stroke="#2563eb" stroke-width="0.8" fill="none" stroke-dasharray="3 3"/>
                <!-- Inner Tech Rings -->
                <circle cx="50" cy="50" r="35" stroke="#3b82f6" stroke-width="0.5" fill="none"/>
                <circle cx="50" cy="50" r="28" stroke="#06b6d4" stroke-width="1.2" stroke-dasharray="10 5" fill="none"/>
                <circle cx="50" cy="50" r="20" stroke="#6366f1" stroke-width="0.8" fill="none"/>
                <!-- Tech lines/nodes -->
                <line x1="50" y1="5" x2="50" y2="95" stroke="#3b82f6" stroke-width="0.5" opacity="0.5"/>
                <line x1="5" y1="50" x2="95" y2="50" stroke="#3b82f6" stroke-width="0.5" opacity="0.5"/>
                <!-- Pulsing Core -->
                <circle cx="50" cy="50" r="8" fill="#60efff" opacity="0.8"/>
                <circle cx="50" cy="50" r="4" fill="#2563eb"/>
                <!-- Orbiting Nodes -->
                <circle cx="50" cy="15" r="3.5" fill="#3b82f6"/>
                <circle cx="85" cy="50" r="2.5" fill="#06b6d4"/>
                <circle cx="50" cy="85" r="3.5" fill="#6366f1"/>
                <circle cx="15" cy="50" r="2.5" fill="#3b82f6"/>
            </svg>
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