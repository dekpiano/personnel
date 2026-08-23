<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'ตั้งค่าพิกัดเช็คชื่อและช่วงเวลาทำงาน' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Include Leaflet.js for interactive mapping -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    #map {
        height: 460px;
        width: 100%;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        z-index: 1;
    }
    
    .time-slot-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.15rem;
        border: 1px solid #e2e8f0;
    }
</style>

<!-- Hero Banner Card -->
<div class="page-header p-4 mb-4">
    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
        <span class="badge bg-white text-primary rounded-pill px-3 py-1 text-uppercase fw-bold" style="font-size: 0.75rem;">
            <i class="bx bx-map-pin me-1"></i> ระบบ GPS Check-In
        </span>
    </div>
    <h3 class="fw-extrabold text-white mb-2 text-shadow">
        ตั้งค่าพิกัดเช็คชื่อและช่วงเวลาทำงาน (Location & GPS)
    </h3>
    <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
        กำหนดจุดพิกัดโรงเรียน รัศมีเช็คชื่อที่อนุญาต และช่วงเวลาสำหรับการลงเวลาปฏิบัติงานผ่าน GPS
    </p>
</div>

<!-- Map & Form Grid -->
<div class="row g-4">
    <!-- Leaflet Map Container -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="fw-bold mb-0 fs-6 text-dark d-flex align-items-center">
                    <i class="bx bx-map text-primary me-2 fs-5"></i> แผนที่ระบุพิกัดศูนย์กลางโรงเรียน
                </h5>
                <span class="badge bg-label-primary px-3 py-1 rounded-pill small fw-bold">
                    <i class="bx bx-info-circle me-1"></i> คลิกแผนที่หรือลากหมุดเพื่อปรับพิกัด
                </span>
            </div>
            <div class="card-body p-3 d-flex flex-column gap-3">
                <div id="map"></div>
                <div class="alert alert-light border d-flex align-items-center gap-2 mb-0 py-2 px-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <i class="bx bx-compass text-primary fs-4"></i>
                    <div class="small text-secondary">
                        พิกัดปัจจุบัน: <strong id="map-telemetry" class="text-dark">กำลังโหลดแผนที่...</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form Container -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="fw-bold mb-0 fs-6 text-dark d-flex align-items-center">
                    <i class="bx bx-slider-alt me-2 text-primary fs-5"></i> รายละเอียดการตั้งค่า
                </h5>
            </div>
            <div class="card-body p-3">
                <form id="formLocationConfig">
                    <!-- System Switch Active Toggle -->
                    <div class="form-check form-switch mb-3 p-3 border rounded-3 d-flex align-items-center justify-content-between" style="background: #f0fdf4; border-color: #bbf7d0 !important; gap: 1rem;">
                        <div class="ps-2">
                            <label class="form-check-label fw-bold text-dark d-block" for="is_active" style="cursor: pointer;">
                                <i class="bx bx-power-off text-success me-1"></i> สถานะระบบ SKJ Check-In
                            </label>
                            <small class="text-muted small">เปิดใช้งานลงเวลาผ่าน GPS / ปิดเพื่อระงับการเช็คชื่อชั่วคราว</small>
                        </div>
                        <input class="form-check-input fs-4" type="checkbox" role="switch" name="is_active" id="is_active" 
                               value="1" <?= (empty($location) || !isset($location->is_active) || $location->is_active == 1) ? 'checked' : '' ?> style="cursor: pointer; margin-left: 0;">
                    </div>

                    <!-- Geolocation coordinates -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-dark mb-1">ละติจูด (Latitude)</label>
                            <input type="number" step="any" name="lat" id="lat" class="form-control shadow-none bg-light" 
                                   value="<?= esc($location ? $location->lat : 15.7060416) ?>" required readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-dark mb-1">ลองจิจูด (Longitude)</label>
                            <input type="number" step="any" name="lng" id="lng" class="form-control shadow-none bg-light" 
                                   value="<?= esc($location ? $location->lng : 100.1280556) ?>" required readonly>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label fw-bold small text-dark mb-1">รัศมีที่อนุญาตให้ลงเวลางาน (เมตร)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="radius_m" id="radius_m" class="form-control shadow-none fw-bold" 
                                       value="<?= esc($location ? $location->radius_m : 200) ?>" min="0" max="5000" required>
                                <span class="input-group-text bg-light fw-bold">เมตร</span>
                            </div>
                            <span class="small text-muted mt-1 d-block" style="font-size: 0.78rem;">
                                <i class="bx bx-info-circle me-1 text-primary"></i>ใส่เลข <strong>0</strong> เพื่อเปิดระบบบันทึกเวลาอิสระ (ไม่จำกัดระยะทาง)
                            </span>
                        </div>
                    </div>

                    <!-- Work check-in time configuration -->
                    <div class="time-slot-card mb-2">
                        <h6 class="fw-bold text-dark d-flex align-items-center gap-1 mb-2 fs-6">
                            <i class="bx bx-log-in-circle text-success fs-5"></i>
                            ช่วงเวลาเช็คอินเข้างาน (Check-In)
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted mb-1">เริ่มลงเวลาได้ตั้งแต่</label>
                                <input type="time" name="check_in_start" class="form-control form-control-sm shadow-none" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_in_start : '06:00:00')) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">สิ้นสุดช่วงเช็คอิน</label>
                                <input type="time" name="check_in_end" class="form-control form-control-sm shadow-none" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_in_end : '08:00:00')) ?>" required>
                            </div>
                        </div>
                        <span class="d-block mt-1 text-muted" style="font-size: 0.75rem;">
                            <i class="bx bx-time me-1"></i>หากเช็คชื่อหลังเวลาสิ้นสุด ระบบจะบันทึกสถานะเป็น "มาสาย"
                        </span>
                    </div>

                    <!-- Work check-out time configuration -->
                    <div class="time-slot-card mb-3">
                        <h6 class="fw-bold text-dark d-flex align-items-center gap-1 mb-2 fs-6">
                            <i class="bx bx-log-out-circle text-warning fs-5"></i>
                            ช่วงเวลาเช็คเอาต์ออกงาน (Check-Out)
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted mb-1">เริ่มลงเวลาออกได้</label>
                                <input type="time" name="check_out_start" class="form-control form-control-sm shadow-none" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_out_start : '16:00:00')) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">สิ้นสุดช่วงเช็คเอาต์</label>
                                <input type="time" name="check_out_end" class="form-control form-control-sm shadow-none" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_out_end : '18:30:00')) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm rounded-pill" id="btn-save">
                        <i class="bx bx-save me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Include SweetAlert2 & Leaflet JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function() {
        // ---- Initialize Map ----
        let initialLat = parseFloat($('#lat').val()) || 15.7060416;
        let initialLng = parseFloat($('#lng').val()) || 100.1280556;
        let initialRadius = parseInt($('#radius_m').val()) || 200;

        // Create leaflet map
        let map = L.map('map').setView([initialLat, initialLng], 16);

        // Load open street map tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create draggable marker
        let marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        // Create circle representing allowed radius
        let circle = L.circle([initialLat, initialLng], {
            color: '#0284c7',
            fillColor: '#38bdf8',
            fillOpacity: initialRadius === 0 ? 0 : 0.18,
            opacity: initialRadius === 0 ? 0 : 1,
            radius: initialRadius
        }).addTo(map);

        // Update telemetry elements
        function updateTelemetry(lat, lng) {
            $('#lat').val(lat.toFixed(7));
            $('#lng').val(lng.toFixed(7));
            $('#map-telemetry').html(`Lat: <strong>${lat.toFixed(7)}</strong>, Lng: <strong>${lng.toFixed(7)}</strong>`);
        }

        // Initialize telemetry view
        updateTelemetry(initialLat, initialLng);

        // Marker drag handler
        marker.on('drag', function(e) {
            let position = marker.getLatLng();
            circle.setLatLng(position);
            updateTelemetry(position.lat, position.lng);
        });

        // Click map handler to reposition pin
        map.on('click', function(e) {
            let position = e.latlng;
            marker.setLatLng(position);
            circle.setLatLng(position);
            updateTelemetry(position.lat, position.lng);
        });

        // Radius input change handler
        $('#radius_m').on('input change', function() {
            let radVal = parseInt($(this).val()) || 0;
            if (radVal === 0) {
                circle.setStyle({fillOpacity: 0, opacity: 0});
            } else {
                circle.setStyle({fillOpacity: 0.18, opacity: 1, color: '#0284c7', fillColor: '#38bdf8'});
                circle.setRadius(radVal);
            }
        });

        // Toggle active status change handler
        $('#is_active').on('change', function() {
            let isActive = $(this).is(':checked') ? 1 : 0;
            $.ajax({
                url: '<?= base_url("Admin/SaveAttendance/SetupLocation/ToggleActive") ?>',
                method: 'POST',
                data: {
                    is_active: isActive,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.is_active === 1 ? 'เปิดระบบเช็คชื่อออนไลน์แล้ว' : 'ปิดระบบเช็คชื่อออนไลน์แล้ว',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถเปลี่ยนสถานะระบบได้',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถติดต่อกับทางเซิร์ฟเวอร์ได้',
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        });

        // ---- AJAX Submit ----
        $('#formLocationConfig').on('submit', function(e) {
            e.preventDefault();
            
            let btn = $('#btn-save');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...');

            $.ajax({
                url: '<?= base_url("Admin/SaveAttendance/SetupLocation/Save") ?>',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ!',
                            text: res.message,
                            confirmButtonText: 'ตกลง'
                        });
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: res.message,
                            confirmButtonText: 'ตกลง'
                        });
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถติดต่อกับทางเซิร์ฟเวอร์ได้',
                        confirmButtonText: 'ตกลง'
                    });
                    btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
