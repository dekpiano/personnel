<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary: #4f46e5;
        --success: #10b981;
        --warning: #f59e0b;
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
        margin-bottom: 1.5rem;
    }
    
    .selfie-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #e5e7eb;
    }
    .selfie-thumb:hover {
        transform: scale(1.12);
        border-color: var(--primary);
    }
    
    .status-badge {
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .badge-checkin { background-color: #ecfdf5; color: #059669; }
    .badge-checkout { background-color: #fef3c7; color: #d97706; }
    
    .coordinate-link {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .coordinate-link:hover {
        text-decoration: underline;
    }
</style>

<div class="p-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-person-check-fill me-2"></i>รายละเอียดการลงเวลา (SKJ Check-In)</h1>
            <p>ตรวจสอบและประเมินภาพถ่ายใบหน้าพร้อมตำแหน่งที่ตั้ง GPS จริงของบุคลากรที่ทำการสแกนเวลางานผ่านมือถือ</p>
        </div>
    </div>

    <!-- Date Filter & Quick Switcher -->
    <div class="panel-card">
        <form method="get" action="<?= base_url('Admin/SaveAttendance/OnlineHistory') ?>" id="formFilter" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-bold text-dark"><i class="bi bi-calendar3 me-1 text-primary"></i>เลือกวันที่ต้องการตรวจสอบ</label>
                <div class="input-group">
                    <button type="button" class="btn btn-outline-secondary" id="btn-prev-day"><i class="bi bi-chevron-left"></i> วันก่อนหน้า</button>
                    <input type="date" name="date" id="search-date" class="form-control text-center" value="<?= esc($selectedDate) ?>" required>
                    <button type="button" class="btn btn-outline-secondary" id="btn-next-day">วันถัดไป <i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold"><i class="bi bi-search me-1"></i> ดึงรายงานรายละเอียด</button>
            </div>
        </form>
    </div>

    <!-- Attendance Details List -->
    <div class="panel-card">
        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-table me-2 text-primary"></i>ผลการลงเวลา วันที่ <?= date('d/m/', strtotime($selectedDate)) . (date('Y', strtotime($selectedDate)) + 543) ?></h5>
        
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-bordered w-100" id="tbOnlineAttendance">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="30%">ชื่อ-นามสกุล</th>
                        <th width="20%">ตำแหน่ง</th>
                        <th width="15%" class="text-center">เวลาเข้างาน (Check-In)</th>
                        <th width="15%" class="text-center">เวลาออกงาน (Check-Out)</th>
                        <th width="15%" class="text-center">พิกัด GPS</th>
                        <th width="10%" class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                ไม่พบข้อมูลการลงเวลา SKJ Check-In ในวันที่เลือก
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $index => $r): 
                            $name = esc($r['pers_prefix'] . $r['pers_firstname'] . ' ' . $r['pers_lastname']);
                        ?>
                            <tr class="align-middle">
                                <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $name ?></div>
                                    <small class="text-muted">ID: <?= esc($r['pers_id']) ?></small>
                                </td>
                                <td><?= esc($r['posi_name'] ?? 'ไม่มีระบุ') ?></td>
                                <td class="text-center">
                                    <?php if (!empty($r['check_in'])): ?>
                                        <div class="fw-bold text-primary mb-1"><i class="bi bi-clock me-1"></i><?= date('H:i:s', strtotime($r['check_in'])) ?> น.</div>
                                        <?php if (!empty($r['check_in_photo'])): ?>
                                            <img src="<?= esc($r['check_in_photo']) ?>" class="selfie-thumb" 
                                                 onclick="showSelfieModal('<?= esc($r['check_in_photo']) ?>', 'รูปสแกนเข้า: <?= $name ?>')" 
                                                 alt="Check-in Face">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($r['check_out'])): ?>
                                        <div class="fw-bold text-success mb-1"><i class="bi bi-clock me-1"></i><?= date('H:i:s', strtotime($r['check_out'])) ?> น.</div>
                                        <?php if (!empty($r['check_out_photo'])): ?>
                                            <img src="<?= esc($r['check_out_photo']) ?>" class="selfie-thumb" 
                                                 onclick="showSelfieModal('<?= esc($r['check_out_photo']) ?>', 'รูปสแกนออก: <?= $name ?>')" 
                                                 alt="Check-out Face">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center small">
                                    <?php if (!empty($r['check_in_lat'])): ?>
                                        <a href="https://www.google.com/maps?q=<?= $r['check_in_lat'] ?>,<?= $r['check_in_lng'] ?>" 
                                           target="_blank" class="coordinate-link">
                                            <i class="bi bi-geo-alt-fill"></i> แผนที่
                                        </a>
                                        <div class="text-muted mt-1 text-xs"><?= number_format($r['check_in_lat'], 5) ?>, <?= number_format($r['check_in_lng'], 5) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($r['status'] === 'มาสาย'): ?>
                                        <span class="status-badge bg-warning bg-opacity-10 text-white"><i class="bi bi-exclamation-triangle-fill me-1"></i>มาสาย</span>
                                    <?php else: ?>
                                        <span class="status-badge bg-success bg-opacity-10 text-white"><i class="bi bi-check-circle-fill me-1"></i>ปกติ</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Init DataTable if records exist
        <?php if (!empty($records)): ?>
        $('#tbOnlineAttendance').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json'
            },
            columnDefs: [
                { orderable: false, targets: [3, 4, 5] }
            ]
        });
        <?php endif; ?>

        // Quick Day Switcher Handlers
        $('#btn-prev-day').on('click', function() {
            let dateVal = new Date($('#search-date').val());
            dateVal.setDate(dateVal.getDate() - 1);
            let formatted = dateVal.toISOString().split('T')[0];
            $('#search-date').val(formatted);
            $('#formFilter').submit();
        });

        $('#btn-next-day').on('click', function() {
            let dateVal = new Date($('#search-date').val());
            dateVal.setDate(dateVal.getDate() + 1);
            let formatted = dateVal.toISOString().split('T')[0];
            $('#search-date').val(formatted);
            $('#formFilter').submit();
        });
    });

    // Display image selfie inside standard sweetalert2 modal frame
    function showSelfieModal(src, title) {
        Swal.fire({
            title: title,
            imageUrl: src,
            imageWidth: 420,
            imageHeight: 315,
            imageAlt: 'Selfie Face scan details',
            confirmButtonText: 'ปิดหน้านี้',
            customClass: {
                confirmButton: 'btn btn-primary px-4 py-2'
            }
        });
    }
</script>
<?= $this->endSection() ?>
