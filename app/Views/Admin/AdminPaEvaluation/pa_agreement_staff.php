<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .stat-card-lux {
        border-radius: 12px;
        border: 1px solid #e7e7e8;
        background: #fff;
        padding: 1.25rem;
        transition: all 0.2s ease-in-out;
    }
    .stat-card-lux:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .dropzone-box-modal {
        border: 2px dashed #d9dee3;
        border-radius: 10px;
        padding: 28px 16px;
        text-align: center;
        background-color: #fafbfc;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .dropzone-box-modal:hover, .dropzone-box-modal.dragover {
        border-color: #696cff;
        background-color: #f8f9ff;
    }
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible !important;
    }
    .learning-section-card {
        border: 1px solid #e0e4ec;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(67, 89, 113, 0.04);
        overflow: hidden;
    }
    .learning-section-header {
        background: #f8f9fb;
        border-bottom: 1px solid #e0e4ec;
        padding: 0.85rem 1.25rem;
        cursor: pointer;
        user-select: none;
    }
    .learning-section-header:hover {
        background: #f1f3f7;
    }
</style>

<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold m-0"><i class="bx bx-file text-primary me-2"></i>จัดการข้อตกลง PA (รายคน)</h4>
        <small class="text-muted">สำหรับเจ้าหน้าที่งานบุคลากร จัดการไฟล์บันทึกข้อตกลง PA ข้าราชการครู (แยกตามกลุ่มสาระการเรียนรู้)</small>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <form method="GET" action="<?= base_url('Admin/PaAgreement'); ?>" class="d-flex align-items-center me-2">
            <label for="fiscal_year" class="me-2 fw-semibold text-nowrap mb-0"><i class="bx bx-calendar me-1 text-primary"></i>ปีงบประมาณ:</label>
            <select name="fiscal_year" id="fiscal_year" class="form-select form-select-sm fw-bold shadow-none" onchange="this.form.submit()">
                <?php foreach ($available_years as $y): ?>
                    <option value="<?= $y; ?>" <?= ($y == $fiscal_year) ? 'selected' : ''; ?>>
                        ปีงบประมาณ <?= $y; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <button type="button" class="btn btn-sm btn-outline-danger shadow-none" id="btnCleanJunkFiles">
            <i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ
        </button>
    </div>
</div>

<!-- Quick Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card-lux" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">ข้าราชการครูทั้งหมด</span>
                    <h3 class="fw-bold mb-0 text-primary"><?= number_format($total_teachers); ?> <span class="fs-6 text-muted fw-normal">คน</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-group fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-card-lux" style="background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">นำเข้าไฟล์ PA แล้ว</span>
                    <h3 class="fw-bold mb-0 text-success"><?= number_format($uploaded_count); ?> <span class="fs-6 text-muted fw-normal">คน</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-check-double fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-card-lux" style="background: linear-gradient(135deg, #fff3e0 0%, #ffffff 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small d-block mb-1">ยังไม่มีไฟล์ PA (รอดำเนินการ)</span>
                    <h3 class="fw-bold mb-0 text-warning"><?= number_format($pending_count); ?> <span class="fs-6 text-muted fw-normal">คน</span></h3>
                </div>
                <div class="avatar avatar-md bg-white text-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bx bx-time-five fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาชื่อ - สกุล หรือตำแหน่ง...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="filterLearning" class="form-select form-select-sm">
                    <option value="">-- แสดงทุกกลุ่มสาระฯ --</option>
                    <?php foreach ($grouped_teachers as $g): ?>
                        <option value="<?= esc($g['lear_id']); ?>"><?= esc($g['lear_name']); ?> (<?= $g['total'] ?> คน)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select form-select-sm">
                    <option value="">-- สถานะไฟล์ PA ทั้งหมด --</option>
                    <option value="uploaded">มีไฟล์แล้ว</option>
                    <option value="pending">ยังไม่มีไฟล์</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Teachers Grouped by Learning Area -->
<div id="learningGroupsContainer">
    <?php if (empty($grouped_teachers)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bx bx-user-x fs-1 mb-2"></i>
                <div>ไม่พบข้อมูลข้าราชการครู</div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($grouped_teachers as $grpKey => $grp): ?>
            <div class="learning-section-card" data-lear-id="<?= esc($grp['lear_id']) ?>">
                <div class="learning-section-header d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseGrp_<?= $grpKey ?>">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bxs-folder-open text-primary fs-4"></i>
                        <span class="fw-bold text-dark fs-6"><?= esc($grp['lear_name']); ?></span>
                        <span class="badge bg-label-secondary rounded-pill px-2 py-1 small"><?= $grp['total'] ?> คน</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-success rounded-pill px-2 py-1 small">
                            <i class="bx bx-check me-1"></i>ส่งแล้ว: <span class="grp-uploaded-cnt"><?= $grp['uploaded'] ?></span>
                        </span>
                        <span class="badge bg-label-warning rounded-pill px-2 py-1 small">
                            <i class="bx bx-time me-1"></i>รอส่ง: <span class="grp-pending-cnt"><?= $grp['pending'] ?></span>
                        </span>
                        <i class="bx bx-chevron-down fs-4 text-muted transition-arrow"></i>
                    </div>
                </div>
                <div id="collapseGrp_<?= $grpKey ?>" class="collapse show">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">#</th>
                                    <th>ชื่อ - นามสกุล</th>
                                    <th>ตำแหน่ง / วิทยฐานะ</th>
                                    <th class="text-center" style="width: 170px;">สถานะไฟล์ PA</th>
                                    <th class="text-center pe-3" style="width: 170px;">จัดการไฟล์</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $idx = 1; foreach ($grp['teachers'] as $t): ?>
                                    <?php 
                                        $hasFile = !empty($t['pa_agreement']['pa_file_pa1']);
                                        $fileName = $t['pa_agreement']['pa_file_pa1'] ?? '';
                                        $paId = $t['pa_agreement']['pa_id'] ?? '';
                                        $fullName = trim(($t['pers_prefix'] ?? '') . $t['pers_firstname'] . ' ' . $t['pers_lastname']);
                                    ?>
                                    <tr class="teacher-row" 
                                        data-lear-id="<?= esc($grp['lear_id']) ?>" 
                                        data-status="<?= $hasFile ? 'uploaded' : 'pending' ?>">
                                        <td class="ps-3 text-muted"><?= $idx++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-label-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold">
                                                    <?= mb_substr($t['pers_firstname'], 0, 1, 'UTF-8') ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark teacher-name"><?= esc($fullName) ?></div>
                                                    <div class="x-small text-muted">รหัส: <?= esc($t['pers_id']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-secondary small"><?= esc($t['posi_name'] ?? 'ครู') ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($hasFile): ?>
                                                <span class="badge bg-label-success rounded-pill px-3 py-1">
                                                    <i class="bx bx-check-circle me-1"></i> มีไฟล์แล้ว
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-label-warning rounded-pill px-3 py-1">
                                                    <i class="bx bx-time-five me-1"></i> ยังไม่มีไฟล์
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                <?php if ($hasFile): ?>
                                                    <a href="<?= env('upload.server.baseurl.pa_agreement') . $fiscal_year . '/pa1/' . $fileName ?>" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-icon btn-label-primary rounded-pill" 
                                                       title="เปิดดูไฟล์ PA">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-icon btn-label-info rounded-pill btn-open-upload-modal" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-has-file="1" 
                                                            data-file-name="<?= esc($fileName) ?>" 
                                                            title="อัปโหลดไฟล์ใหม่แทนที่">
                                                        <i class="bx bx-upload"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-icon btn-label-danger rounded-pill btn-delete-pa" 
                                                            data-pa-id="<?= esc($paId) ?>" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            title="ลบไฟล์ PA">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary rounded-pill px-3 btn-open-upload-modal" 
                                                            data-teacher-id="<?= esc($t['pers_id']) ?>" 
                                                            data-teacher-name="<?= esc($fullName) ?>" 
                                                            data-has-file="0">
                                                        <i class="bx bx-cloud-upload me-1"></i> อัปโหลด
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadPaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold" id="uploadModalTitle">
                    <i class="bx bx-cloud-upload text-primary me-2"></i>อัปโหลดไฟล์ข้อตกลง PA
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 bg-label-primary rounded-3 mb-3 border border-primary border-opacity-25">
                    <div class="small text-muted mb-1">คุณครูผู้รับการประเมิน:</div>
                    <h6 class="fw-bold text-primary mb-0" id="modalTeacherName">-</h6>
                    <div class="x-small text-muted mt-1">ประจำปีงบประมาณ: <span class="fw-bold text-dark"><?= $fiscal_year ?></span></div>
                </div>

                <div id="existingFileAlert" class="alert alert-warning py-2 px-3 small d-none">
                    <i class="bx bx-info-circle me-1"></i> มีไฟล์เดิมในระบบแล้ว หากอัปโหลดใหม่ไฟล์เดิมจะถูกเขียนทับ
                </div>

                <input type="file" id="modalFileInput" accept=".pdf" class="d-none">
                
                <div class="dropzone-box-modal" id="modalDropzone">
                    <i class="bx bxs-file-pdf text-danger fs-1 mb-2"></i>
                    <div class="small fw-bold text-dark">ลากและวางไฟล์ PDF ข้อตกลง PA ที่นี่</div>
                    <div class="x-small text-muted">หรือคลิกเพื่อเลือกไฟล์ (PDF สูงสุด 20MB)</div>
                </div>

                <div class="file-selected-card mt-3 d-none p-3 border rounded-3 bg-white" id="modalFileIndicator">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center text-truncate me-2">
                            <i class="bx bxs-file-pdf text-danger fs-3 me-2"></i>
                            <div class="text-truncate">
                                <div class="small fw-bold text-truncate" id="selectedFileName">-</div>
                                <div class="x-small text-muted" id="selectedFileSize">-</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-xs btn-icon btn-label-secondary" id="modalRemoveFile">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>

                <div class="progress mt-3 d-none" id="modalProgress" style="height: 8px;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-4" id="modalSubmitBtn" disabled>
                    <i class="bx bx-save me-1"></i> บันทึกไฟล์ PA
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const year = '<?= $fiscal_year ?>';
        const CHUNK_SIZE = 512 * 1024; // 512KB per chunk (bypass 1MB server limit)
        let currentTargetTeacherId = null;
        let selectedFile = null;

        // Filter Functionality
        function filterRows() {
            const search = $('#searchInput').val().toLowerCase().trim();
            const selectedLearId = $('#filterLearning').val();
            const status = $('#filterStatus').val();

            $('.learning-section-card').each(function() {
                const grpCard = $(this);
                const grpLearId = grpCard.data('lear-id');
                let visibleRowsInGroup = 0;

                let matchGroupFilter = !selectedLearId || String(grpLearId) === String(selectedLearId);

                if (!matchGroupFilter) {
                    grpCard.hide();
                    return;
                }

                grpCard.find('.teacher-row').each(function() {
                    const row = $(this);
                    const name = row.find('.teacher-name').text().toLowerCase();
                    const rowStatus = row.data('status');

                    let matchSearch = !search || name.includes(search);
                    let matchStatus = !status || rowStatus === status;

                    if (matchSearch && matchStatus) {
                        row.show();
                        visibleRowsInGroup++;
                    } else {
                        row.hide();
                    }
                });

                if (visibleRowsInGroup > 0) {
                    grpCard.show();
                } else {
                    grpCard.hide();
                }
            });
        }

        $('#searchInput').on('keyup', filterRows);
        $('#filterLearning, #filterStatus').on('change', filterRows);

        // Open Upload Modal
        $('.btn-open-upload-modal').on('click', function() {
            currentTargetTeacherId = $(this).data('teacher-id');
            const teacherName = $(this).data('teacher-name');
            const hasFile = $(this).data('has-file');

            $('#modalTeacherName').text(teacherName);
            if (hasFile == 1) {
                $('#existingFileAlert').removeClass('d-none');
            } else {
                $('#existingFileAlert').addClass('d-none');
            }

            resetModalForm();
            $('#uploadPaModal').modal('show');
        });

        function resetModalForm() {
            selectedFile = null;
            $('#modalFileInput').val('');
            $('#modalFileIndicator').addClass('d-none');
            $('#modalDropzone').show();
            $('#modalProgress').addClass('d-none').find('.progress-bar').css('width', '0%');
            $('#modalSubmitBtn').prop('disabled', true).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
        }

        // Dropzone & File Selection
        const dropzone = $('#modalDropzone');
        const fileInput = $('#modalFileInput');

        dropzone.on('click', () => fileInput.trigger('click'));

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone[0].addEventListener(eventName, (e) => { e.preventDefault(); e.stopPropagation(); }, false);
        });

        dropzone.on('dragenter dragover', () => dropzone.addClass('dragover'));
        dropzone.on('dragleave drop', () => dropzone.removeClass('dragover'));

        dropzone[0].addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) handleSelectedFile(files[0]);
        });

        fileInput.on('change', function() {
            if (this.files.length > 0) handleSelectedFile(this.files[0]);
        });

        function handleSelectedFile(file) {
            if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
                Swal.fire('ข้อผิดพลาด', 'กรุณาเลือกเฉพาะไฟล์ PDF เท่านั้น', 'warning');
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                Swal.fire('ข้อผิดพลาด', 'ขนาดไฟล์ต้องไม่เกิน 20MB', 'warning');
                return;
            }

            selectedFile = file;
            $('#selectedFileName').text(file.name);
            $('#selectedFileSize').text((file.size / (1024 * 1024)).toFixed(2) + ' MB');
            $('#modalDropzone').hide();
            $('#modalFileIndicator').removeClass('d-none');
            $('#modalSubmitBtn').prop('disabled', false);
        }

        $('#modalRemoveFile').on('click', resetModalForm);

        // Upload Helper
        async function uploadChunked(file, teacherId) {
            const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
            const timestamp = Math.floor(Date.now() / 1000);
            const targetFilename = `PA_${year}_${teacherId}_${timestamp}.pdf`;
            const targetPath = `personnel/teacher/pa_agreement/${year}/pa1`;

            $('#modalProgress').removeClass('d-none');
            const progressBar = $('#modalProgress').find('.progress-bar');

            let finalSavedName = targetFilename;

            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('path', targetPath);
                formData.append('filename', targetFilename);
                formData.append('chunk', chunkIndex);
                formData.append('chunks', totalChunks);

                const res = await $.ajax({
                    url: '<?= base_url('Admin/PaAgreement/upload-chunk') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json'
                });

                if (res.status !== 'success' && res.status !== 'chunk_saved') {
                    throw new Error(res.message || 'อัปโหลดไฟล์ไม่สำเร็จ');
                }

                if (chunkIndex === totalChunks - 1 && res.filename) {
                    finalSavedName = res.filename;
                }

                const percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);
                progressBar.css('width', percent + '%').text(percent + '%');
            }

            return finalSavedName;
        }

        // Submit Upload
        $('#modalSubmitBtn').on('click', async function() {
            if (!selectedFile || !currentTargetTeacherId) return;

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังอัปโหลด...');

            try {
                const uploadedFilename = await uploadChunked(selectedFile, currentTargetTeacherId);

                $.ajax({
                    url: '<?= base_url('Admin/PaAgreement/save-file') ?>',
                    type: 'POST',
                    data: {
                        teacher_id: currentTargetTeacherId,
                        pa_year: year,
                        uploaded_pa1_filename: uploadedFilename
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1500, showConfirmButton: false })
                                .then(() => location.reload());
                        } else {
                            btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
                            Swal.fire('ผิดพลาด', res.message, 'error');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
                        Swal.fire('ผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                    }
                });
            } catch (err) {
                btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกไฟล์ PA');
                $('#modalProgress').addClass('d-none');
                Swal.fire('ผิดพลาด', err.message, 'error');
            }
        });

        // Delete PA File
        $('.btn-delete-pa').on('click', function() {
            const paId = $(this).data('pa-id');
            const teacherId = $(this).data('teacher-id');
            const teacherName = $(this).data('teacher-name');

            Swal.fire({
                title: 'ยืนยันการลบไฟล์ PA?',
                text: `คุณต้องการลบไฟล์บันทึกข้อตกลง PA ของ "${teacherName}" ประจำปีงบประมาณ ${year} ใช่หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('Admin/PaAgreement/delete-file') ?>',
                        type: 'POST',
                        data: { pa_id: paId, teacher_id: teacherId, pa_year: year },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message, timer: 1200, showConfirmButton: false })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('ผิดพลาด', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('ผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
                        }
                    });
                }
            });
        });

        // Clean Junk & Orphan Files
        $('#btnCleanJunkFiles').on('click', function() {
            const btn = $(this);
            Swal.fire({
                title: 'ยืนยันการล้างไฟล์ขยะ?',
                text: 'ระบบจะล้างไฟล์ชั่วคราว (Chunks) ที่อัปโหลดตกค้างบนเซิร์ฟเวอร์',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e6381a',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'ล้างไฟล์ขยะทันที',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังล้าง...');
                    $.ajax({
                        url: '<?= base_url('Admin/PaAgreement/clean-junk') ?>',
                        type: 'POST',
                        data: { pa_year: year },
                        dataType: 'json',
                        success: function(res) {
                            btn.prop('disabled', false).html('<i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ');
                            if (res.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.message });
                            } else {
                                Swal.fire('ผิดพลาด', res.message, 'error');
                            }
                        },
                        error: function() {
                            btn.prop('disabled', false).html('<i class="bx bx-trash me-1"></i> ล้างไฟล์ขยะ');
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>