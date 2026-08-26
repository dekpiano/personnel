<?php $uri = service('uri'); ?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?=base_url()?>/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= (isset($title) ? esc($title) : 'Admin') ?> | SKJ Personnel Manage</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts: K2D & Outfit (Modern Thai & Number Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,600&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/theme-blue.css?v=2.0"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/select2.css?v=10.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/demo.css?v=1.1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

    <!-- Page CSS -->
    <style>
        /* 🇹🇭 Universal Typography Standard: K2D Font */
        body, html, .layout-wrapper, .menu, .card, .btn, .form-control, .form-select, .table, .modal, .dropdown-menu, .nav, .badge, h1, h2, h3, h4, h5, h6, p, span, a, label, input, select, textarea {
            font-family: 'K2D', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        /* Lozad Fade-in Animation */
        .lozad {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .lozad.loaded {
            opacity: 1;
        }
        
        /* Profile Image Hover Effect for better UX */
        .avatar img {
            transition: transform 0.2s ease-in-out;
        }
        .avatar:hover img {
            transform: scale(1.1);
        }
        
        /* Cropper CSS */
        .cropper-container-wrapper {
            max-height: 500px;
            width: 100%;
        }
        .cropper-preview {
            width: 150px;
            height: 200px;
            overflow: hidden;
            margin-bottom: 1rem;
            border-radius: 8px;
            margin-left: auto;
            margin-right: auto;
        }

        /* PREMIUM THEME OVERRIDE WITH BRAND COLOR #03c3ec */
        :root {
            --bs-primary: #03c3ec;
            --primary-brand: #03c3ec;
            --primary-brand-dark: #029dbd;
        }

        /* ====================================================
           SIGNATURE ROYAL/OCEAN BLUE SIDEBAR DESIGN
           Harmonious Proportions, Balanced Spacing & Sharp UX/UI
           ==================================================== */
        #layout-menu {
            background: linear-gradient(180deg, #1d4ed8 0%, #0284c7 45%, #075985 100%) !important;
            box-shadow: 4px 0 20px rgba(2, 132, 199, 0.25), 0 0 10px rgba(0, 0, 0, 0.1) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.18) !important;
            transition: all 0.3s ease;
        }

        #layout-menu .app-brand {
            background: rgba(255, 255, 255, 0.12) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 1rem 1.15rem !important;
            height: 68px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
            margin-bottom: 0.5rem;
        }

        .brand-logo-wrap {
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 10px;
            background: #ffffff;
            border: 2px solid rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        #layout-menu .app-brand:hover .brand-logo-wrap {
            transform: scale(1.08) rotate(3deg);
        }

        .brand-title {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.98rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff !important;
            line-height: 1.1;
            margin-bottom: 2px;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: #e0f2fe !important;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        #layout-menu .menu-inner {
            padding: 0.25rem 0.5rem 2rem 0.5rem !important;
        }

        #layout-menu .menu-header-text {
            color: rgba(255, 255, 255, 0.65) !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
        }

        #layout-menu .menu-item {
            margin: 2px 0 !important;
        }

        /* Default Menu Link */
        #layout-menu .menu-link {
            border-radius: 10px !important;
            padding: 0.55rem 0.85rem !important;
            color: #f0f9ff !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            line-height: 1.3 !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        #layout-menu .menu-icon {
            color: #ffffff !important;
            font-size: 1.2rem !important;
            margin-right: 0.65rem !important;
            transition: transform 0.2s ease !important;
            opacity: 0.95;
        }

        /* Hover State */
        #layout-menu .menu-item:not(.active) > .menu-link:hover {
            background: rgba(255, 255, 255, 0.16) !important;
            color: #ffffff !important;
            transform: translateX(3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #layout-menu .menu-item:not(.active) > .menu-link:hover .menu-icon {
            transform: scale(1.12);
            opacity: 1;
        }

        /* Active State - High Contrast Solid White Pill */
        #layout-menu .menu-item.active > .menu-link {
            background: #ffffff !important;
            color: #0369a1 !important;
            font-weight: 800 !important;
            box-shadow: 0 6px 16px -2px rgba(0, 0, 0, 0.25) !important;
            border-left: none !important;
        }

        #layout-menu .menu-item.active > .menu-link .menu-icon {
            color: #0284c7 !important;
            opacity: 1;
            filter: drop-shadow(0 2px 4px rgba(2, 132, 199, 0.3));
        }

        #layout-menu .menu-item.open > .menu-link {
            background: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
        }

        /* Sub-Menu Styling & Perfectly Positioned Bullet Dots */
        #layout-menu .menu-sub {
            padding-left: 0 !important;
            margin-left: 1.25rem !important;
            border-left: 2px solid rgba(255, 255, 255, 0.22) !important;
            margin-top: 3px !important;
            margin-bottom: 5px !important;
        }

        #layout-menu .menu-sub .menu-item {
            position: relative !important;
        }

        #layout-menu .menu-sub .menu-link {
            font-size: 0.82rem !important;
            padding: 0.48rem 0.85rem 0.48rem 1.65rem !important; /* Generous left padding to prevent overlapping */
            color: #e0f2fe !important;
            border-radius: 8px !important;
            position: relative !important;
        }

        /* Bullet Dot Placement - Clean & Centered */
        #layout-menu .menu-sub .menu-link::before {
            content: '' !important;
            position: absolute !important;
            left: 0.65rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 5px !important;
            height: 5px !important;
            border-radius: 50% !important;
            background-color: rgba(255, 255, 255, 0.45) !important;
            box-shadow: none !important;
            transition: all 0.2s ease !important;
        }

        #layout-menu .menu-sub .menu-item:not(.active) .menu-link:hover::before {
            background-color: #ffffff !important;
            transform: translateY(-50%) scale(1.3) !important;
        }

        #layout-menu .menu-sub .menu-item:not(.active) .menu-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.14) !important;
            transform: translateX(3px);
        }

        #layout-menu .menu-sub .menu-item.active .menu-link {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            border-left: none !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
        }

        #layout-menu .menu-sub .menu-item.active .menu-link::before {
            background-color: #ffffff !important;
            box-shadow: 0 0 8px #ffffff, 0 0 12px #38bdf8 !important;
            width: 6px !important;
            height: 6px !important;
            transform: translateY(-50%) scale(1.2) !important;
        }

        /* Sleek Sidebar Custom Scrollbar */
        #layout-menu .menu-inner::-webkit-scrollbar {
            width: 4px;
        }
        #layout-menu .menu-inner::-webkit-scrollbar-track {
            background: transparent;
        }
        #layout-menu .menu-inner::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.22);
            border-radius: 10px;
        }
        #layout-menu .menu-inner::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        /* Navbar Enhancement */
        .layout-navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-top: 3px solid var(--primary-brand);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05) !important;
        }
        .layout-navbar .bx-search, .layout-navbar .bx-menu {
            color: var(--primary-brand) !important;
        }
        
        /* ====================================================
           MODERN COMPACT & LUXURY CARDS SYSTEM (UNIVERSAL)
           Clean, sleek, well-proportioned, simple & compact
           ==================================================== */
        .card {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(2, 132, 199, 0.08) !important;
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1rem 1.35rem !important;
        }

        .card-body {
            padding: 1.25rem 1.35rem !important;
        }

        .card-footer {
            background-color: #f8fafc !important;
            border-top: 1px solid #f1f5f9 !important;
            padding: 0.85rem 1.35rem !important;
        }

        /* Section Cards & Compact Dashboard Blocks */
        .section-card {
            background: #ffffff !important;
            border-radius: 16px !important;
            padding: 1.35rem 1.5rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
            margin-bottom: 1.25rem !important;
        }

        /* Stat Cards */
        .stat-card, .stat-card-lux, .card-stat {
            background: #ffffff !important;
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 1.15rem 1.35rem !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .stat-card:hover, .stat-card-lux:hover, .card-stat:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 28px rgba(2, 132, 199, 0.12) !important;
            border-color: #bae6fd !important;
        }

        /* Modern Table inside Cards */
        .table {
            color: #334155 !important;
        }

        .table thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-size: 0.78rem !important;
            font-weight: 750 !important;
            letter-spacing: 0.4px !important;
            text-transform: uppercase !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 0.75rem 1rem !important;
        }

        .table tbody td {
            padding: 0.8rem 1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .table tbody tr:hover {
            background-color: #f0f9ff !important;
        }

        /* Form Inputs & Selects - Clean & Compact */
        .form-control, .form-select {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 0.55rem 0.85rem !important;
            font-size: 0.88rem !important;
            color: #1e293b !important;
            transition: all 0.2s ease !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
        }

        /* Modern Buttons */
        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
            padding: 0.55rem 1.15rem !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .btn:hover {
            transform: translateY(-1px) !important;
        }

        .btn-sm, .btn-group-sm > .btn {
            padding: 0.35rem 0.75rem !important;
            font-size: 0.8rem !important;
            border-radius: 8px !important;
        }

        /* Modern Modal System */
        .modal-content {
            border-radius: 20px !important;
            border: none !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden !important;
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.15rem 1.5rem !important;
        }

        .modal-body {
            padding: 1.5rem !important;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 1rem 1.5rem !important;
            background-color: #f8fafc !important;
        }

        /* Universal Signature Blue Hero Banners */
        .page-header, .hero-premium, .personnel-header, .hero-panel, .leave-hero-header, .settings-hero-header, .holiday-hero-header, .attendance-hero-header {
            background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%) !important;
            color: #ffffff !important;
            border-radius: 20px !important;
            box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        /* ====================================================
           GLOBAL HIGH-CONTRAST BADGES & LABELS (.badge, .bg-label-*)
           Fixes all invisible / blending text across entire system
           ==================================================== */
        .badge {
            font-weight: 700 !important;
            letter-spacing: 0.2px;
        }

        /* Label Primary (Sky Blue) */
        .bg-label-primary {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            border: 1px solid #bae6fd !important;
        }

        /* Label Secondary (Slate / Gray) */
        .bg-label-secondary {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            border: 1px solid #cbd5e1 !important;
        }

        /* Label Success (Emerald Green) */
        .bg-label-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border: 1px solid #a7f3d0 !important;
        }

        /* Label Danger (Red) */
        .bg-label-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
        }

        /* Label Warning (Amber / Orange) */
        .bg-label-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #fde68a !important;
        }

        /* Label Info (Cyan / Blue) */
        .bg-label-info {
            background-color: #e0f7fa !important;
            color: #006064 !important;
            border: 1px solid #b2ebf2 !important;
        }

        /* Label Dark */
        .bg-label-dark {
            background-color: #334155 !important;
            color: #ffffff !important;
            border: 1px solid #1e293b !important;
        }

        /* Solid Badges High-Contrast Fix */
        .badge.bg-primary {
            background-color: #0284c7 !important;
            color: #ffffff !important;
        }
        .badge.bg-secondary {
            background-color: #475569 !important;
            color: #ffffff !important;
        }
        .badge.bg-success {
            background-color: #059669 !important;
            color: #ffffff !important;
        }
        .badge.bg-danger {
            background-color: #dc2626 !important;
            color: #ffffff !important;
        }
        .badge.bg-warning {
            background-color: #d97706 !important;
            color: #ffffff !important;
        }
        .badge.bg-info {
            background-color: #0891b2 !important;
            color: #ffffff !important;
        }
        .badge.bg-light {
            background-color: #f8fafc !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
        }

        /* Avatar Initials with bg-label-* */
        .avatar-initial.bg-label-primary {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
            font-weight: 800;
        }
        .avatar-initial.bg-label-secondary {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 800;
        }
        .avatar-initial.bg-label-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-weight: 800;
        }
        .avatar-initial.bg-label-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            font-weight: 800;
        }
        .avatar-initial.bg-label-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            font-weight: 800;
        }
        .avatar-initial.bg-label-info {
            background-color: #e0f7fa !important;
            color: #006064 !important;
            font-weight: 800;
        }

        /* BODY BACKGROUND */
        body {
            background-color: #f5f7fb !important;
        }
        .content-wrapper {
            background-color: #f5f7fb !important;
        }
        .container-xxl, .container-fluid {
            background-color: transparent !important;
        }
        
    </style>

    <!-- Helpers -->
    <script src="<?=base_url()?>/assets/vendor/js/helpers.js"></script>

    <script src="<?=base_url()?>/assets/js/config.js"></script>
</head>

<body style="font-family:'Sarabun'">

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            
            <?php echo view('Admin/AdminLeyout/AdminMenuLeft'); ?>

            <div class="layout-page">
                
                <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?= $this->renderSection('content') ?>
                    </div>

                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-end py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://facebook.com/dekpiano" target="_blank" class="footer-link fw-bolder">Dekpiano</a>
                            </div>
                        </div>
                    </footer>

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Image Cropping Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title"><i class='bx bx-crop me-2'></i>ครอบตัดรูปภาพ</h5>
                    <button type="button" class="btn-close no-loading" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="cropper-container-wrapper rounded bg-light overflow-hidden">
                                <img id="cropperImage" src="" style="max-width: 100%;">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="mb-3">ตัวอย่างการแสดงผล</h6>
                            <div class="cropper-preview border shadow-sm"></div>
                            <div class="d-grid gap-2 mt-4">
                                <button type="button" class="btn btn-outline-primary no-loading" onclick="cropper.rotate(-90)">
                                    <i class='bx bx-rotate-left me-1'></i> หมุนซ้าย
                                </button>
                                <button type="button" class="btn btn-outline-primary no-loading" onclick="cropper.rotate(90)">
                                    <i class='bx bx-rotate-right me-1'></i> หมุนขวา
                                </button>
                                <button type="button" class="btn btn-outline-warning no-loading" onclick="cropper.reset()">
                                    <i class='bx bx-reset me-1'></i> รีเซ็ต
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-label-secondary no-loading" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" id="cropButton" class="btn btn-primary no-loading">
                        <i class='bx bx-check me-1'></i> ตกลงและบันทึก
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?=base_url()?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/menu.js"></script>
    <script src="<?=base_url()?>/assets/js/select2.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Ensure SweetAlert2 is always on top */
        .swal2-container {
            z-index: 99999 !important;
        }
    </style>
    <script>
        // Set global SweetAlert2 defaults to always be on top
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                container: 'swal2-container-top'
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <style>
        /* Flatpickr Airbnb Theme & Thai Year Dropdown (แบบเดียวกับ teacher2025 เป๊ะๆ) */
        .flatpickr-calendar {
            font-family: 'Sarabun', sans-serif !important;
            border-radius: 14px !important;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18) !important;
            border: 1.5px solid #cbd5e1 !important;
        }

        /* ตารางตัวเลขวัน - ให้เข้ม คมชัด ไม่ซีดจาง */
        .flatpickr-day {
            color: #0f172a !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            border-radius: 8px !important;
            transition: all 0.15s ease-in-out !important;
        }

        /* เอาเมาส์ชี้วัน (Hover) */
        .flatpickr-day:hover {
            background: #e0f2fe !important;
            color: #0369a1 !important;
            border-color: #7dd3fc !important;
        }

        /* วันปัจจุบัน (Today) */
        .flatpickr-day.today {
            border: 1.5px solid #0284c7 !important;
            color: #0284c7 !important;
            background: #f0f9ff !important;
            font-weight: 800 !important;
        }

        /* วันที่เลือก (Selected / Range) - สีฟ้าเข้ม คมชัด โดดเด่น */
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange {
            background: #0284c7 !important;
            border-color: #0284c7 !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.45) !important;
        }

        /* ช่วงวันที่เลือก (In Range) */
        .flatpickr-day.inRange {
            background: #e0f2fe !important;
            color: #0369a1 !important;
            box-shadow: none !important;
        }

        /* วันของเดือนก่อน/หลัง - ให้มองเห็นแต่ต่างระดับ */
        .flatpickr-day.prevMonthDay, 
        .flatpickr-day.nextMonthDay {
            color: #94a3b8 !important;
            font-weight: 500 !important;
        }

        /* แถบชื่อวัน (จ. อ. พ. ...) */
        span.flatpickr-weekday {
            color: #334155 !important;
            font-weight: 800 !important;
            font-size: 0.85rem !important;
        }

        .flatpickr-months .flatpickr-month {
            color: #1e293b !important;
        }
        .flatpickr-current-month .cur-month {
            font-weight: 800 !important;
            color: #0f172a !important;
        }
        .flatpickr-year-be-select {
            font-family: 'Sarabun', sans-serif !important;
            font-size: 0.95rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            background: #f8fafc !important;
            border: 1.5px solid #94a3b8 !important;
            border-radius: 6px !important;
            padding: 2px 8px !important;
            cursor: pointer !important;
            outline: none !important;
            margin-left: 4px !important;
        }
        .flatpickr-year-be-select:focus {
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.25) !important;
        }
    </style>
    <script>
    (function() {
        if (typeof flatpickr === 'undefined') return;
        flatpickr.localize(flatpickr.l10ns.th);

        const thaiMonthsFull = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        // Global Thai Buddhist Era Flatpickr Dropdown Render Function
        window.applyThaiBE = function(instance) {
            if (!instance || !instance.calendarContainer) return;
            const currentYearAD = instance.currentYear;
            const $container = $(instance.calendarContainer);
            const $numInputWrapper = $container.find('.numInputWrapper');
            
            // Generate Year Options (-80 to +10 years around current year for full birth year support)
            const baseYear = new Date().getFullYear();
            let optionsHtml = '';
            for (let y = baseYear - 80; y <= baseYear + 10; y++) {
                const yBE = y + 543;
                const isSelected = (y === currentYearAD) ? 'selected' : '';
                optionsHtml += `<option value="${y}" ${isSelected}>${yBE}</option>`;
            }

            if ($numInputWrapper.length > 0) {
                $numInputWrapper.hide();
            }

            let $yearSelect = $container.find('.flatpickr-year-be-select');
            if ($yearSelect.length === 0) {
                $yearSelect = $(`<select class="flatpickr-year-be-select" aria-label="เลือกปี พ.ศ.">${optionsHtml}</select>`);
                $container.find('.flatpickr-current-month').append($yearSelect);

                $yearSelect.on('change', function(e) {
                    e.stopPropagation();
                    const chosenYearAD = parseInt($(this).val(), 10);
                    instance.changeYear(chosenYearAD);
                });
            } else {
                $yearSelect.html(optionsHtml);
                $yearSelect.val(currentYearAD);
            }
        };
        window.renderThaiYearDropdown = window.applyThaiBE;

        flatpickr.setDefaults({
            locale: 'th',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
            formatDate: function(date, format, locale) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const yearBE = date.getFullYear() + 543;
                if (format === 'Y-m-d') {
                    return `${date.getFullYear()}-${month}-${day}`;
                }
                return `${day}/${month}/${yearBE}`;
            },
            parseDate: function(dateStr, format) {
                if (!dateStr) return null;
                if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return new Date(dateStr);
                const p = dateStr.split(/[-/]/);
                if (p.length === 3) {
                    let y = parseInt(p[2]);
                    let m = parseInt(p[1]) - 1;
                    let d = parseInt(p[0]);
                    if (y > 2400) y -= 543;
                    return new Date(y, m, d);
                }
                return new Date(dateStr);
            },
            onReady: function(selectedDates, dateStr, instance) {
                window.applyThaiBE(instance);
                if (instance.config && instance.config.onMonthChange) {
                    instance.config.onMonthChange.push(function(s, d, inst) {
                        window.applyThaiBE(inst);
                    });
                }
                if (instance.config && instance.config.onYearChange) {
                    instance.config.onYearChange.push(function(s, d, inst) {
                        window.applyThaiBE(inst);
                    });
                }
            },
            onOpen: function(selectedDates, dateStr, instance) {
                window.applyThaiBE(instance);
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                window.applyThaiBE(instance);
            },
            onYearChange: function(selectedDates, dateStr, instance) {
                window.applyThaiBE(instance);
            }
        });

        // MutationObserver: ช่วยดักทุกครั้งที่ปฏิทินถูกสร้างหรือเปิดใหม่
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.classList && node.classList.contains('flatpickr-calendar')) {
                        const instance = node._flatpickr;
                        if (instance) window.applyThaiBE(instance);
                    }
                });
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script src="<?=base_url()?>/assets/js/main.js"></script>

    <script src="<?=base_url()?>/assets/js/dashboards-analytics.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
    <script>
        $(document).ready(function() {
            // Global Lozad initialization
            const observer = lozad('.lozad', {
                loaded: function(el) {
                    el.classList.add('loaded');
                }
            });
            observer.observe();
            
            // Re-observe on dynamic content changes (like DataTables)
            $(document).on('draw.dt', function() {
                observer.observe();
            });

            // Global Submit Button Loading Handler
            $(document).on('submit', 'form', function() {
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                
                if ($btn.length && !$btn.hasClass('no-loading')) {
                    $btn.data('original-html', $btn.html());
                    $btn.prop('disabled', true);
                    $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังดำเนินการ...');
                }
            });

            // Restore button state after AJAX completion
            $(document).ajaxComplete(function() {
                $('button[type="submit"]:disabled').each(function() {
                    const $btn = $(this);
                    if ($btn.data('original-html')) {
                        $btn.html($btn.data('original-html'));
                        $btn.prop('disabled', false);
                    }
                });
            });

            // Global Cropper Logic
            let cropper;
            let currentInput;
            const $modal = $('#cropperModal');
            const $image = document.getElementById('cropperImage');
            const $cropBtn = $('#cropButton');

            $(document).on('change', 'input[type="file"].crop-target', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    currentInput = e.target;
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $image.src = event.target.result;
                        $modal.modal('show');
                    };
                    reader.readAsDataURL(file);
                }
            });

            $modal.on('shown.bs.modal', function() {
                cropper = new Cropper($image, {
                    aspectRatio: 600/800, // Fix 3:4 (600x800) for personnel profiles
                    viewMode: 2,
                    preview: '.cropper-preview',
                    responsive: true,
                    restore: true,
                    checkCrossOrigin: false,
                    checkOrientation: false,
                });
            }).on('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            $cropBtn.on('click', function() {
                if (!cropper) return;
                
                const canvas = cropper.getCroppedCanvas({
                    width: 600,
                    height: 800
                });

                canvas.toBlob(function(blob) {
                    // Update preview if target ID exists
                    const previewId = $(currentInput).data('preview');
                    if (previewId) {
                        const previewImg = document.getElementById(previewId);
                        if (previewImg) {
                            previewImg.src = canvas.toDataURL();
                            // Handle lozad if present
                            if (previewImg.classList.contains('lozad')) {
                                previewImg.classList.add('loaded');
                                previewImg.style.opacity = 1;
                            }
                        }
                    }

                    // Store the blob to be used during form submission
                    $(currentInput).data('cropped-blob', blob);
                    $modal.modal('hide');
                    
                    // Trigger custom event for special cases
                    $(currentInput).trigger('cropped', [blob]);
                }, 'image/png');
            });

            // Universal Form Submission Handler to include Cropped Images
            $(document).on('submit', 'form', function(e) {
                const $form = $(this);
                const $cropInputs = $form.find('input[type="file"].crop-target');
                
                let hasCropped = false;
                $cropInputs.each(function() {
                    if ($(this).data('cropped-blob')) hasCropped = true;
                });

                if (hasCropped && !$form.data('crop-handled')) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    const formData = new FormData(this);
                    $cropInputs.each(function() {
                        const blob = $(this).data('cropped-blob');
                        if (blob) {
                            const name = $(this).attr('name');
                            const filename = $(this).val().split('\\').pop() || 'image.png';
                            formData.set(name, blob, filename);
                        }
                    });

                    // Prepare for actual submission
                    const ajaxOptions = {
                        url: $form.attr('action') || window.location.href,
                        method: $form.attr('method') || 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            // Try to trigger the original success handler or common success logic
                            if (typeof $form[0].onsubmit === 'function') {
                                // This is tricky for direct AJAX handlers in scripts
                                // Most of our handlers are like $(document).on('submit', '#ID', ...)
                            }
                        }
                    };
                    
                    // Instead of full AJAX here, we warn that handlers need to use the blob
                    // BUT for our specific project, I'll modify the specific AJAX handlers in AdminPersonnalMain.js
                }
            });

            // Expose cropper globally for the rotate buttons
            window.cropper = {
                rotate: (deg) => cropper && cropper.rotate(deg),
                reset: () => cropper && cropper.reset()
            };
        });
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>