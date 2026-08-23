<?php
$uri = service('uri');
?>
<!DOCTYPE html>
<html lang="th" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?=base_url()?>/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= (isset($title) ? esc($title) : 'SKJ E-Office') ?> | SKJ บริหารงานบุคคล</title>

    <meta name="description" content="<?= (isset($description) ? esc($description) : '') ?>" />
    <meta
        content="ระบบงาน,E-Office,โรงเรียนสวนกุหลาบวิทยาลัย,โรงเรียน,สวนกุหลาบ,จิรประวัติ,นครสวรรค์,สวนกุหลาบจิรประวัติ,โรงเรียนสวนกุหลาบ"
        name="keywords">
    <meta http-equiv="content-language" content="th" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="1 day" />
    <meta name="author" content="Dekpiano" />
    <meta property="og:url" content="<?= (isset($full_url) ? esc($full_url) : base_url()) ?>" />
    <meta property="og:title" content="<?= (isset($title) ? esc($title) : 'SKJ E-Office') ?>" />
    <meta property="og:description" content="<?= (isset($description) ? esc($description) : '') ?>" />
    <meta property="og:type" content="website" />
    <?php if($uri->getSegment(1) == 'Booking') : ?>
    <meta property="og:image" content="<?=base_url();?>uploads/banner/booking/bannerBooking.png" />
    <?php elseif($uri->getSegment(1) == 'Repair'): ?>
    <meta property="og:image" content="<?=base_url();?>uploads/banner/repair/bannerRepair.jpg" />
    <?php else: ?>
    <meta property="og:image" content="<?=base_url();?>uploads/banner/home/bannerHome.png" />
    <?php endif?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/theme-blue.css?v=3"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/demo.css?v=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Page CSS -->
    <style>
        /* Lozad Fade-in Animation */
        .lozad {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .lozad.loaded {
            opacity: 1;
        }
    </style>
    <style>
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

        /* ====================================================
           GLOBAL HIGH-CONTRAST BADGES & LABELS (.badge, .bg-label-*)
           Fixes all invisible / blending text across entire User portal
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
    </style>

    <!-- Helpers -->
    <script src="<?=base_url()?>/assets/vendor/js/helpers.js"></script>

    <script src="<?=base_url()?>/assets/js/config.js"></script>
</head>

<body style="font-family:'Sarabun'">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            
            <?php echo view('User/UserLeyout/UserMenuLeft'); ?>

            <!-- Layout container -->
            <div class="layout-page">
                
                <?php echo view('User/UserLeyout/UserNavbar'); ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?= $this->renderSection('content') ?>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
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
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
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
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    <script src="https://hcaptcha.com/1/api.js" async defer></script>

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <style>
        /* บังคับแสดงลูกศรเปลี่ยนปีเสมอ ไม่ต้องรอเอาเมาส์ชี้ (Hover) */
        .flatpickr-current-month .numInputWrapper span.arrowUp,
        .flatpickr-current-month .numInputWrapper span.arrowDown {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
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
        window.renderThaiYearDropdown = function(instance) {
            if (!instance || !instance.calendarContainer) return;
            const currentYearAD = instance.currentYear;
            const $container = $(instance.calendarContainer);
            const $numInputWrapper = $container.find('.numInputWrapper');
            
            // Generate Year Options (-10 to +5 years around current year)
            const baseYear = new Date().getFullYear();
            let optionsHtml = '';
            for (let y = baseYear - 10; y <= baseYear + 5; y++) {
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

        flatpickr.setDefaults({
            locale: 'th',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'j F Y',
            formatDate: function(date, format, locale) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = thaiMonthsFull[date.getMonth()];
                const yearBE = date.getFullYear() + 543;
                return `${day} ${month} ${yearBE}`;
            },
            onReady: function(selectedDates, dateStr, instance) {
                window.renderThaiYearDropdown(instance);
                if (instance.config && instance.config.onMonthChange) {
                    instance.config.onMonthChange.push(function(s, d, inst) {
                        window.renderThaiYearDropdown(inst);
                    });
                }
                if (instance.config && instance.config.onYearChange) {
                    instance.config.onYearChange.push(function(s, d, inst) {
                        window.renderThaiYearDropdown(inst);
                    });
                }
            },
            onOpen: function(selectedDates, dateStr, instance) {
                window.renderThaiYearDropdown(instance);
            }
        });

        // MutationObserver: ช่วยดักทุกครั้งที่ปฏิทินถูกสร้างหรือเปิดใหม่
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.classList && node.classList.contains('flatpickr-calendar')) {
                        const instance = node._flatpickr;
                        if (instance) window.renderThaiYearDropdown(instance);
                    }
                });
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

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
            timerProgressBar: true
        });
    </script>

    <!-- Vendors JS -->
    <script src="<?=base_url()?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="<?=base_url()?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?=base_url()?>/assets/js/dashboards-analytics.js"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
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
                    aspectRatio: 600/800, 
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
                    const previewId = $(currentInput).data('preview');
                    if (previewId) {
                        const previewImg = document.getElementById(previewId);
                        if (previewImg) {
                            previewImg.src = canvas.toDataURL();
                            if (previewImg.classList.contains('lozad')) {
                                previewImg.classList.add('loaded');
                                previewImg.style.opacity = 1;
                            }
                        }
                    }

                    $(currentInput).data('cropped-blob', blob);
                    $modal.modal('hide');
                    $(currentInput).trigger('cropped', [blob]);
                }, 'image/png');
            });

            // Expose cropper globally for the rotate buttons
            window.cropper = {
                rotate: (deg) => cropper && cropper.rotate(deg),
                reset: () => cropper && cropper.reset()
            };
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js"></script>

    <?php if (session()->getFlashdata('Error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?= session()->getFlashdata("Error") ?>',
            showConfirmButton: false,
            timer: 3000
        })
    </script>
    <?php endif; ?>

    <?= $this->renderSection('scripts') ?>
</body>

</html>