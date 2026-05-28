<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Include Leaflet.js for interactive mapping -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    :root {
        --primary: #4f46e5;
        --success: #10b981;
        --card-shadow: 0 10px 30px rgba(79, 70, 229, 0.05);
    }
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
        padding: 2rem 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.15);
        color: white;
    }
    .page-header h1 { color: white; font-size: 1.75rem; font-weight: 800; margin-bottom: 0.25rem; }
    .page-header p { color: rgba(255,255,255,0.85); margin-bottom: 0; font-size: 0.95rem; }
    
    .panel-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #f3f4f6;
        box-shadow: var(--card-shadow);
        height: 100%;
    }
    
    #map {
        height: 450px;
        width: 100%;
        border-radius: 15px;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .input-group-text-custom {
        background-color: #f9fafb;
        font-weight: 600;
        color: #4b5563;
    }

    .time-slot-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #f3f4f6;
    }
</style>

<div class="p-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-geo-alt-fill me-2"></i>ตั้งค่าพิกัดเช็คชื่อและช่วงเวลาทำงาน</h1>
            <p>กำหนดจุดพิกัดโรงเรียน รัศมีเช็คชื่อที่อนุญาต และช่วงเวลาสำหรับการลงเวลาปฏิบัติงานผ่าน GPS</p>
        </div>
    </div>

    <!-- Map & Form Grid -->
    <div class="row g-4">
        <!-- Leaflet Map Container -->
        <div class="col-12 col-lg-7">
            <div class="panel-card d-flex flex-column gap-3">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-map-fill text-primary me-2"></i>แผนที่ระบุพิกัดศูนย์กลางโรงเรียน</span>
                    <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill small fw-normal"><i class="bi bi-info-circle me-1"></i>คลิกแผนที่หรือลากหมุดเพื่อปรับพิกัด</span>
                </h5>
                <div id="map"></div>
                <div class="alert alert-light border d-flex align-items-center gap-2 mb-0 py-2.5 px-3" style="border-radius: 10px;">
                    <i class="bi bi-compass text-info fs-5"></i>
                    <div class="small text-secondary">
                        พิกัดปัจจุบัน: <strong id="map-telemetry">กำลังโหลดแผนที่...</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form Container -->
        <div class="col-12 col-lg-5">
            <div class="panel-card">
                <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-sliders me-2 text-primary"></i>รายละเอียดการตั้งค่า</h5>
                
                <form id="formLocationConfig">
                    <!-- System Switch Active Toggle -->
                    <div class="form-check form-switch mb-4 p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="ps-2">
                            <label class="form-check-label fw-bold text-dark d-block" for="is_active" style="cursor: pointer;">
                                <i class="bi bi-power text-danger me-1"></i> สถานะระบบ SKJ Check-In
                            </label>
                            <small class="text-muted small">เปิดเพื่อเปิดใช้งานลงเวลาผ่าน GPS / ปิดชั่วคราวเพื่อปิดระบบ</small>
                        </div>
                        <input class="form-check-input fs-3" type="checkbox" role="switch" name="is_active" id="is_active" 
                               value="1" <?= (empty($location) || !isset($location->is_active) || $location->is_active == 1) ? 'checked' : '' ?> style="cursor: pointer; margin-left: 0;">
                    </div>

                    <!-- Geolocation coordinates -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">ละติจูด (Latitude)</label>
                            <input type="number" step="any" name="lat" id="lat" class="form-control py-2.5" 
                                   value="<?= esc($location ? $location->lat : 15.7060416) ?>" required readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label">ลองจิจูด (Longitude)</label>
                            <input type="number" step="any" name="lng" id="lng" class="form-control py-2.5" 
                                   value="<?= esc($location ? $location->lng : 100.1280556) ?>" required readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">รัศมีที่อนุญาตให้ลงเวลางาน (เมตร)</label>
                            <div class="input-group">
                                <input type="number" name="radius_m" id="radius_m" class="form-control py-2.5" 
                                       value="<?= esc($location ? $location->radius_m : 200) ?>" min="0" max="5000" required>
                                <span class="input-group-text input-group-text-custom">เมตร</span>
                            </div>
                            <span class="small text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>ใส่เลข <strong>0</strong> เพื่อเปิดระบบบันทึกเวลาอิสระ (ไม่จำกัดระยะทาง)</span>
                        </div>
                    </div>

                    <!-- Work check-in time configuration -->
                    <div class="time-slot-card mb-3">
                        <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                            <span class="p-1 rounded bg-success bg-opacity-10 text-success"><i class="bi bi-box-arrow-in-right"></i></span>
                            ช่วงเวลาเช็คอินเข้างาน (Check-In)
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted mb-1">เริ่มลงเวลาได้ตั้งแต่</label>
                                <input type="time" name="check_in_start" class="form-control" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_in_start : '06:00:00')) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">สิ้นสุดช่วงเช็คอิน</label>
                                <input type="time" name="check_in_end" class="form-control" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_in_end : '08:00:00')) ?>" required>
                            </div>
                        </div>
                        <span class="d-block mt-2 small text-muted"><i class="bi bi-info-circle me-1"></i>หากเช็คชื่อหลังสิ้นสุดช่วงเช็คอิน ระบบจะบันทึกสถานะเป็น "มาสาย"</span>
                    </div>

                    <!-- Work check-out time configuration -->
                    <div class="time-slot-card mb-4">
                        <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                            <span class="p-1 rounded bg-warning bg-opacity-10 text-warning"><i class="bi bi-box-arrow-right"></i></span>
                            ช่วงเวลาเช็คเอาต์ออกงาน (Check-Out)
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted mb-1">เริ่มลงเวลาออกได้</label>
                                <input type="time" name="check_out_start" class="form-control" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_out_start : '16:00:00')) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">สิ้นสุดช่วงเช็คเอาต์</label>
                                <input type="time" name="check_out_end" class="form-control" 
                                       value="<?= date('H:i', strtotime($location ? $location->check_out_end : '18:30:00')) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" id="btn-save" style="border-radius: 12px;">
                        <i class="bi bi-save2 me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด
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
            color: '#4f46e5',
            fillColor: '#818cf8',
            fillOpacity: initialRadius === 0 ? 0 : 0.15,
            opacity: initialRadius === 0 ? 0 : 1,
            radius: initialRadius
        }).addTo(map);

        // Update telemetry elements
        function updateTelemetry(lat, lng) {
            $('#lat').val(lat.toFixed(7));
            $('#lng').val(lng.toFixed(7));
            $('#map-telemetry').html(`Latitude: <strong>${lat.toFixed(7)}</strong>, Longitude: <strong>${lng.toFixed(7)}</strong>`);
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
                circle.setStyle({fillOpacity: 0.15, opacity: 1, color: '#4f46e5', fillColor: '#818cf8'});
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
                        btn.prop('disabled', false).html('<i class="bi bi-save2 me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: res.message,
                            confirmButtonText: 'ตกลง'
                        });
                        btn.prop('disabled', false).html('<i class="bi bi-save2 me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถติดต่อกับทางเซิร์ฟเวอร์ได้',
                        confirmButtonText: 'ตกลง'
                    });
                    btn.prop('disabled', false).html('<i class="bi bi-save2 me-1"></i> บันทึกข้อมูลการตั้งค่าทั้งหมด');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
