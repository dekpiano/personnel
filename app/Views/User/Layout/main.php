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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                }, 'image/jpeg', 0.9);
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