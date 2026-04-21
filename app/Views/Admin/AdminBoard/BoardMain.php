<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    .row-container {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        background: #f8fafc;
        position: relative;
    }
    .row-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .member-grid {
        display: grid;
        gap: 20px;
        min-height: 100px;
    }
    /* Dynamic Grid Columns */
    .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
    .grid-cols-5 { grid-template-columns: repeat(5, 1fr); }
    .grid-cols-6 { grid-template-columns: repeat(6, 1fr); }

    .member-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        text-align: center;
        cursor: grab;
        transition: all 0.2s;
        position: relative;
    }
    .member-card:active { cursor: grabbing; }
    .member-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .member-img {
        width: 80px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .member-name { font-weight: 700; font-size: 0.9rem; margin-bottom: 2px; display: block; }
    .member-pos { font-size: 0.75rem; color: #64748b; }
    
    .row-handle { cursor: move; color: #94a3b8; }
    .btn-row-action { padding: 4px 8px; font-size: 0.75rem; }
    
    .unassigned-section {
        background: #fff1f2;
        border: 2px dashed #fecaca;
        border-radius: 12px;
        padding: 20px;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y" id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">จัดการ /</span> โครงสร้างคณะกรรมการ
        </h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="showAddRowModal()">
                <i class='bx bx-layer-plus me-1'></i> เพิ่มแถวรายชื่อ
            </button>
            <button type="button" class="btn btn-primary" onclick="showAddMemberModal()">
                <i class='bx bx-user-plus me-1'></i> เพิ่มกรรมการ
            </button>
        </div>
    </div>

    <!-- Rows List -->
    <div id="row-master-list">
        <?php foreach($rows as $row): ?>
        <div class="row-container" data-row-id="<?= $row['row_id'] ?>">
            <div class="row-header">
                <div class="d-flex align-items-center gap-2">
                    <i class='bx bx-grid-vertical row-handle fs-4' title="ลากเพื่อเรียงลำดับแถว"></i>
                    <h5 class="mb-0 fw-bold"><?= $row['row_title'] ?: 'แถวที่ระบุ' ?></h5>
                    <span class="badge bg-label-secondary"><?= $row['row_cols'] ?> คอลัมน์</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-row-action btn-outline-warning" onclick="editRow(<?= $row['row_id'] ?>)">
                        <i class='bx bx-edit-alt'></i> แก้ไขแถว
                    </button>
                    <button class="btn btn-row-action btn-outline-danger" onclick="deleteRow(<?= $row['row_id'] ?>)">
                        <i class='bx bx-trash'></i> ลบแถว
                    </button>
                </div>
            </div>

            <div class="member-grid grid-cols-<?= $row['row_cols'] ?> member-sortable" data-row-id="<?= $row['row_id'] ?>">
                <?php foreach($row['members'] as $m): ?>
                <div class="member-card" data-member-id="<?= $m['board_id'] ?>">
                    <div class="dropdown position-absolute" style="top: 5px; right: 5px;">
                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="editMember(<?= $m['board_id'] ?>)">
                                <i class="bx bx-edit-alt me-1"></i> แก้ไข
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteMember(<?= $m['board_id'] ?>)">
                                <i class="bx bx-trash me-1"></i> ลบ
                            </a>
                        </div>
                    </div>
                    <img src="<?= !empty($m['board_img']) ? base_url('uploads/admin/Board/'.$m['board_img']) : base_url('assets/img/avatars/1.png') ?>" class="member-img">
                    <span class="member-name"><?= $m['board_prefix'].$m['board_firstname'] ?></span>
                    <span class="member-pos text-truncate d-block" title="<?= $m['board_position'] ?>"><?= $m['board_position'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Unassigned / Buffer -->
    <div class="unassigned-section mt-4 mb-5">
        <h6 class="text-danger fw-bold mb-3"><i class='bx bx-archive me-1'></i> รายชื่อที่ยังไม่ได้ระบุแถว (ลากไปวางในแถวที่ต้องการ)</h6>
        <div class="member-grid grid-cols-6 member-sortable" data-row-id="0">
            <?php foreach($unassigned as $m): ?>
            <div class="member-card" data-member-id="<?= $m['board_id'] ?>" style="border-color: #fca5a5;">
                 <div class="dropdown position-absolute" style="top: 5px; right: 5px;">
                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                             <a class="dropdown-item" href="javascript:void(0);" onclick="editMember(<?= $m['board_id'] ?>)">
                                <i class="bx bx-edit-alt me-1"></i> แก้ไข
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteMember(<?= $m['board_id'] ?>)">
                                <i class="bx bx-trash me-1"></i> ลบ
                            </a>
                        </div>
                    </div>
                <img src="<?= !empty($m['board_img']) ? base_url('uploads/admin/Board/'.$m['board_img']) : base_url('assets/img/avatars/1.png') ?>" class="member-img">
                <span class="member-name"><?= $m['board_prefix'].$m['board_firstname'] ?></span>
                <span class="member-pos"><?= $m['board_position'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal: Row Management -->
<div class="modal fade" id="rowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="rowForm">
            <input type="hidden" name="row_id" id="row_id_field">
            <div class="modal-header">
                <h5 class="modal-title" id="rowModalTitle">จัดการแถวรายชื่อ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">ชื่อแถว (ถ้ามี)</label>
                    <input type="text" name="row_title" id="row_title" class="form-control" placeholder="เช่น ผู้บริหารระดับสูง">
                </div>
                <div class="mb-3">
                    <label class="form-label">จำนวนคอลัมน์ในแถวนี้</label>
                    <select name="row_cols" id="row_cols" class="form-select">
                        <option value="1">1 คน (เต็มกึ่งกลาง)</option>
                        <option value="2">2 คน</option>
                        <option value="3">3 คน</option>
                        <option value="4">4 คน</option>
                        <option value="5">5 คน</option>
                        <option value="6">6 คน</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">บันทึกแถว</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Member Management -->
<div class="modal fade" id="boardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" id="boardForm" enctype="multipart/form-data">
            <input type="hidden" name="board_id" id="board_id">
            <div class="modal-header">
                <h5 class="modal-title" id="memberModalTitle">จัดการข้อมูลกรรมการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-center mb-4">
                    <div class="col-md-4 text-center">
                         <div class="mb-3">
                            <img id="preview-img" src="<?= base_url('assets/img/avatars/1.png') ?>" alt="preview" class="rounded shadow-sm" style="width: 120px; height: 160px; object-fit: cover;">
                        </div>
                        <input type="file" name="board_img" id="board_img" class="form-control crop-target" accept="image/*" data-preview="preview-img">
                    </div>
                    <div class="col-md-8">
                        <div class="row g-2 mb-3">
                            <div class="col-md-3">
                                <label class="form-label">คำนำหน้า</label>
                                <select name="board_prefix" id="board_prefix" class="form-select" required>
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                    <option value="พระครู">พระครู</option>
                                    <option value="ว่าที่ ร.ต.">ว่าที่ ร.ต.</option>
                                    <option value="ดร.">ดร.</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ชื่อ</label>
                                <input type="text" name="board_firstname" id="board_firstname" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" name="board_lastname" id="board_lastname" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ตำแหน่ง</label>
                            <input type="text" name="board_position" id="board_position" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">ระบุให้อยู่แถวที่...</label>
                            <select name="row_id" id="row_id_assign" class="form-select">
                                <option value="0">--- ยังไม่ระบุแถว ---</option>
                                <?php foreach($rows as $r): ?>
                                <option value="<?= $r['row_id'] ?>"><?= $r['row_title'] ?: 'แถว ID: '.$r['row_id'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    $(function() {
        // 1. Initialize Member Sortable (Drag between rows)
        $('.member-sortable').each(function() {
            Sortable.create(this, {
                group: 'members',
                animation: 150,
                onEnd: function(evt) {
                    const rowId = evt.to.dataset.rowId;
                    const order = [];
                    $(evt.to).children('.member-card').each(function() {
                        order.push($(this).data('memberId'));
                    });
                    
                    $.post('<?= base_url('Admin/Board/UpdateOrder') ?>', { 
                        order: order,
                        row_id: rowId 
                    }, function(res) {
                        Toast.fire({ icon: 'success', title: 'ย้ายตำแหน่งสำเร็จ' });
                    });
                }
            });
        });

        // 2. Initialize Row Master Sortable (Move entire rows)
        const rowMasterEl = document.getElementById('row-master-list');
        if (rowMasterEl) {
            Sortable.create(rowMasterEl, {
                handle: '.row-handle',
                animation: 150,
                onEnd: function() {
                    const order = [];
                    $('#row-master-list .row-container').each(function() {
                        order.push($(this).data('rowId'));
                    });
                    
                    $.post('<?= base_url('Admin/Board/UpdateRowOrder') ?>', { order: order }, function(res) {
                        Toast.fire({ icon: 'success', title: 'จัดเรียงแถวสำเร็จ' });
                    });
                }
            });
        }

        // Form Submit: Member
        $('#boardForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const croppedBlob = $('#board_img').data('cropped-blob');
            if (croppedBlob) formData.set('board_img', croppedBlob, 'cropped_image.png');

            $.ajax({
                url: '<?= base_url('Admin/Board/Save') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') location.reload();
                    else Swal.fire('Error', res.message, 'error');
                },
                error: function() { Swal.fire('Error', 'Server error', 'error'); }
            });
        });

        $('#rowForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...');
            
            $.ajax({
                url: '<?= base_url('Admin/Board/SaveRow') ?>',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        location.reload();
                    } else {
                        Swal.fire('ข้อผิดพลาด', res.message, 'error');
                        btn.prop('disabled', false).html('บันทึกแถว');
                    }
                },
                error: function(xhr) {
                    Swal.fire('ระบบขัดข้อง', xhr.responseJSON?.message || 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้', 'error');
                    btn.prop('disabled', false).html('บันทึกแถว');
                }
            });
        });
    });

    // Row Actions
    function showAddRowModal() {
        $('#rowForm')[0].reset();
        $('#row_id_field').val('');
        $('#rowModalTitle').text('เพิ่มแถวรายชื่อใหม่');
        $('#rowModal').modal('show');
    }

    function editRow(id) {
        $.get('<?= base_url('Admin/Board/GetRow') ?>/' + id, function(res) {
            $('#row_id_field').val(res.row_id);
            $('#row_title').val(res.row_title);
            $('#row_cols').val(res.row_cols);
            $('#rowModalTitle').text('แก้ไขข้อมูลแถว');
            $('#rowModal').modal('show');
        });
    }

    function deleteRow(id) {
        Swal.fire({
            title: 'ยืนยันการลบแถว?',
            text: "รายชื่อในแถวนี้จะถูกย้ายไปยังกลุ่มยังไม่ระบุแถว (ไม่หายไปจากระบบ)",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบแถว'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('Admin/Board/DeleteRow') ?>', { row_id: id }, function(res) {
                    if (res.status === 'success') location.reload();
                });
            }
        });
    }

    // Member Actions
    function showAddMemberModal() {
        $('#boardForm')[0].reset();
        $('#board_id').val('');
        $('#board_img').removeData('cropped-blob');
        $('#preview-img').attr('src', '<?= base_url('assets/img/avatars/1.png') ?>');
        $('#row_id_assign').val('0');
        $('#memberModalTitle').text('เพิ่มคณะกรรมการใหม่');
        $('#boardModal').modal('show');
    }

    function editMember(id) {
        $.get('<?= base_url('Admin/Board/Get') ?>/' + id, function(res) {
            $('#board_id').val(res.board_id);
            $('#board_prefix').val(res.board_prefix);
            $('#board_firstname').val(res.board_firstname);
            $('#board_lastname').val(res.board_lastname);
            $('#board_position').val(res.board_position);
            $('#row_id_assign').val(res.row_id || 0);
            
            if (res.board_img) {
                $('#preview-img').attr('src', '<?= base_url('uploads/admin/Board/') ?>/' + res.board_img);
            } else {
                $('#preview-img').attr('src', '<?= base_url('assets/img/avatars/1.png') ?>');
            }
            $('#memberModalTitle').text('แก้ไขข้อมูลคณะกรรมการ');
            $('#boardModal').modal('show');
        });
    }

    function deleteMember(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('Admin/Board/Delete') ?>', { board_id: id }, function(res) {
                    if (res.status === 'success') location.reload();
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
