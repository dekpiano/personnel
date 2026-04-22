    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
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
        </div>
    </footer>
    <!-- / Footer -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <!-- <script src="<?=base_url()?>/assets/vendor/libs/jquery/jquery.js"></script> -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?=base_url()?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?=base_url()?>/assets/vendor/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    <!-- endbuild -->
    <script src="https://hcaptcha.com/1/api.js" async defer></script>

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <!-- moment -->
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

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

    </html>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js"></script>

    <?php if($uri->getSegment(1) == 'Booking') : ?>       
    <script src="<?=base_url()?>/assets/js/User/UserBooking/UserBooking.js?v=22.5"></script>
    <script src="<?=base_url()?>/assets/js/User/UserBooking/UserBookingSignature.js?v=1.3"></script>
    
    <?php elseif($uri->getSegment(1) == 'Repair') : ?>
    <script src="<?=base_url()?>/assets/js/User/UserRepair/UserRepair.js?v=18"></script>
    <?php elseif($uri->getSegment(1) == 'CarBooking') : ?>
        <script src="<?=base_url()?>/assets/js/User/UserCarReservation/UserCarReservation.js?v=2"></script>
    <?php endif; ?>

    
    <?php if (session()->getFlashdata('Error')): ?>

<?= session()->getFlashdata('Error') ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?= session()->getFlashdata('Error') ?>',
            showConfirmButton: false,
            timer: 3000
        })
    </script>
    <?php endif; ?>

    <script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()

$(function() {
    'use strict';

});
    </script>

    <style>
        /* บังคับแสดงลูกศรเปลี่ยนปีเสมอ ไม่ต้องรอเอาเมาส์ชี้ (Hover) */
        .flatpickr-current-month .numInputWrapper span.arrowUp,
        .flatpickr-current-month .numInputWrapper span.arrowDown {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
    <script>
    (function() {
        if (typeof flatpickr === 'undefined') return;
        flatpickr.localize(flatpickr.l10ns.th);

        // ฟังก์ชันหลักสำหรับแปลงปีเป็น พ.ศ. (Global Standard)
        window.applyThaiBE = function(instance) {
            if (!instance || !instance.calendarContainer) return;
            const yearInput = instance.calendarContainer.querySelector(".cur-year");
            if (!yearInput) return;
            
            yearInput.style.color = "transparent";
            let beWrap = yearInput.parentElement.querySelector(".be-year-display");
            if (!beWrap) {
                beWrap = document.createElement("span");
                beWrap.className = "be-year-display";
                beWrap.style.cssText = "position:absolute; left:0; width:100%; top:50%; transform:translateY(-50%); text-align:center; padding-right:15px; box-sizing:border-box; pointer-events:none; color:inherit; font-family:inherit; font-weight:bold;";
                yearInput.parentElement.appendChild(beWrap);
                yearInput.parentElement.style.position = "relative";
            }
            
            let y = instance.currentYear;
            beWrap.innerText = (y > 2400) ? y : (y + 543);
        };

        flatpickr.setDefaults({
            dateFormat: "Y-m-d", // ลง DB
            altInput: true,
            altFormat: "d-m-Y", // พ.ศ. โชว์กรอก
            allowInput: true,
            formatDate: (date) => {
                const d = date.getDate().toString().padStart(2, '0');
                const m = (date.getMonth() + 1).toString().padStart(2, '0');
                const y = date.getFullYear() + 543;
                return `${d}-${m}-${y}`;
            },
            parseDate: (dateStr) => {
                if (!dateStr || /^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return new Date(dateStr);
                const p = dateStr.split('-');
                if (p.length === 3) {
                    let y = parseInt(p[2]);
                    if (y > 2400) y -= 543;
                    return new Date(y, parseInt(p[1]) - 1, parseInt(p[0]));
                }
                return new Date(dateStr);
            },
            onReady: (d, s, i) => window.applyThaiBE(i),
            onMonthChange: (d, s, i) => window.applyThaiBE(i),
            onYearChange: (d, s, i) => window.applyThaiBE(i),
            onOpen: (d, s, i) => window.applyThaiBE(i)
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

        $(".selector").flatpickr();
        $(".selectorEdit").flatpickr();
        $(".selectorTime").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i"
        });
    })();
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
      var lazyLoadImages = document.querySelectorAll('.lazy-load');

      var lazyLoad = function () {
        lazyLoadImages.forEach(function (img) {
          if (img.getBoundingClientRect().top < window.innerHeight && img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
        });
      };

      // Initial load
      lazyLoad();

      // Lazy load on scroll
      document.addEventListener('scroll', lazyLoad);
    });

    
  </script>