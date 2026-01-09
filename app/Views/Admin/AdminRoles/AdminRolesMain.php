<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    .role-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .role-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .user-role-item {
        border-left: 3px solid #696cff;
        transition: all 0.2s ease;
    }
    .user-role-item:hover {
        background-color: #f5f5f9;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .work-tag {
        display: inline-block;
        background: linear-gradient(135deg, #696cff22, #696cff11);
        border: 1px solid #696cff44;
        color: #696cff;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
        margin: 0.1rem;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white overflow-hidden shadow-sm">
            <div class="card-body p-4 position-relative">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg me-3 bg-white text-primary rounded shadow">
                        <i class='bx bx-shield-quarter' style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-white fw-bold">กำหนดสิทธิ์การเข้าถึงระบบ</h4>
                        <p class="mb-0 opacity-75">จัดการผู้ใช้งานและงานที่รับผิดชอบในระบบ</p>
                    </div>
                </div>
                <i class='bx bx-user-check position-absolute end-0 bottom-0 opacity-10' style="font-size: 10rem; transform: translate(20%, 20%);"></i>
            </div>
        </div>
    </div>
</div>

<!-- Add User Button -->
<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary" id="btnAddUser">
        <i class='bx bx-plus me-1'></i> เพิ่มผู้ใช้งาน
    </button>
</div>

<?php
// Separate users by status
$managers = array_filter($RolesUsers, fn($u) => $u->admin_rloes_status === 'manager');
$admins = array_filter($RolesUsers, fn($u) => in_array($u->admin_rloes_status, ['admin', 'superadmin']));

// Sort managers by position priority: ผอ -> รอง -> หัวหน้า
$positionOrder = [
    'ผู้อำนวยการโรงเรียน' => 1,
    'รองผู้อำนวยการบริหารงานบุคคลกร' => 2,
    'หัวหน้าบริหารทั่วไป' => 3
];
usort($managers, function($a, $b) use ($positionOrder) {
    $orderA = $positionOrder[$a->admin_rloes_nanetype] ?? 99;
    $orderB = $positionOrder[$b->admin_rloes_nanetype] ?? 99;
    return $orderA - $orderB;
});
?>

<!-- Managers Table -->
<div class="card role-card mb-4 border-top border-warning border-3">
    <div class="card-header d-flex align-items-center bg-light border-bottom py-3">
        <div class="avatar avatar-sm bg-label-warning me-2">
            <i class='bx bx-crown fs-4'></i>
        </div>
        <h5 class="mb-0 fw-bold">ผู้บริหาร</h5>
        <span class="badge bg-warning ms-2"><?= count($managers) ?> คน</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 30%">ชื่อผู้ใช้</th>
                        <th style="width: 45%">ตำแหน่ง</th>
                        <th style="width: 20%" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($managers as $user): ?>
                        <?php 
                            $userName = $user->admin_rloes_userid;
                            foreach ($NameTeacher as $teacher) {
                                if ($teacher->pers_id === $user->admin_rloes_userid) {
                                    $userName = $teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname;
                                    break;
                                }
                            }
                            $works = explode('|', $user->admin_rloes_nanetype ?? '');
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <strong><?= $userName ?></strong>
                                <br><small class="text-muted"><?= $user->admin_rloes_userid ?></small>
                            </td>
                            <td>
                                <?php foreach ($works as $work): ?>
                                    <?php if (trim($work)): ?>
                                        <span class="badge bg-warning text-dark"><?= trim($work) ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit-user" 
                                    data-id="<?= $user->admin_rloes_id ?>"
                                    data-userid="<?= $user->admin_rloes_userid ?>"
                                    data-works="<?= htmlspecialchars($user->admin_rloes_nanetype ?? '') ?>"
                                    data-status="<?= $user->admin_rloes_status ?>">
                                    <i class='bx bx-edit-alt'></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-user" 
                                    data-id="<?= $user->admin_rloes_id ?>"
                                    data-name="<?= $userName ?>">
                                    <i class='bx bx-trash-alt'></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($managers)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class='bx bx-user-x fs-1'></i>
                                <p class="mb-0 mt-2">ยังไม่มีผู้บริหารในระบบ</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Admins Table -->
<div class="card role-card border-top border-primary border-3">
    <div class="card-header d-flex align-items-center bg-light border-bottom py-3">
        <div class="avatar avatar-sm bg-label-primary me-2">
            <i class='bx bx-user-circle fs-4'></i>
        </div>
        <h5 class="mb-0 fw-bold">เจ้าหน้าที่ระบบ</h5>
        <span class="badge bg-primary ms-2"><?= count($admins) ?> คน</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 25%">ชื่อผู้ใช้</th>
                        <th style="width: 35%">งานที่รับผิดชอบ</th>
                        <th style="width: 15%">สถานะ</th>
                        <th style="width: 20%" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($admins as $user): ?>
                        <?php 
                            $userName = $user->admin_rloes_userid;
                            foreach ($NameTeacher as $teacher) {
                                if ($teacher->pers_id === $user->admin_rloes_userid) {
                                    $userName = $teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname;
                                    break;
                                }
                            }
                            $works = explode('|', $user->admin_rloes_nanetype ?? '');
                            $statusColor = $user->admin_rloes_status === 'superadmin' ? 'danger' : 'info';
                            $statusText = $user->admin_rloes_status === 'superadmin' ? 'Super Admin' : 'Admin';
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <strong><?= $userName ?></strong>
                                <br><small class="text-muted"><?= $user->admin_rloes_userid ?></small>
                            </td>
                            <td>
                                <?php foreach ($works as $work): ?>
                                    <?php if (trim($work)): ?>
                                        <span class="work-tag"><?= trim($work) ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $statusColor ?> status-badge"><?= $statusText ?></span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit-user" 
                                    data-id="<?= $user->admin_rloes_id ?>"
                                    data-userid="<?= $user->admin_rloes_userid ?>"
                                    data-works="<?= htmlspecialchars($user->admin_rloes_nanetype ?? '') ?>"
                                    data-status="<?= $user->admin_rloes_status ?>">
                                    <i class='bx bx-edit-alt'></i>
                                </button>
                                <?php if ($user->admin_rloes_status !== 'superadmin'): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-user" 
                                    data-id="<?= $user->admin_rloes_id ?>"
                                    data-name="<?= $userName ?>">
                                    <i class='bx bx-trash-alt'></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class='bx bx-user-x fs-1'></i>
                                <p class="mb-0 mt-2">ยังไม่มีเจ้าหน้าที่ในระบบ</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">เพิ่มผู้ใช้งาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="editRloesId" name="RloesID">
                    <input type="hidden" id="hiddenUserId" name="UserID">
                    
                    <div class="mb-3">
                        <label class="form-label">เลือกบุคลากร</label>
                        <select class="form-select" id="selectUser" required>
                            <option value="">-- เลือกบุคลากร --</option>
                            <?php foreach ($NameTeacher as $teacher): ?>
                                <option value="<?= $teacher->pers_id ?>">
                                    <?= $teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">งานที่รับผิดชอบ</label>
                        <div class="border rounded p-2">
                            <?php foreach ($AvailableWorks as $work): ?>
                                <div class="form-check">
                                    <input class="form-check-input work-checkbox" type="checkbox" 
                                        name="Works[]" value="<?= $work ?>" id="work_<?= md5($work) ?>">
                                    <label class="form-check-label" for="work_<?= md5($work) ?>">
                                        <?= $work ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">สถานะ</label>
                        <select class="form-select" id="selectStatus" name="Status" required>
                            <option value="admin">Admin (เจ้าหน้าที่)</option>
                            <option value="manager">Manager (ผู้บริหาร)</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="btnSaveUser">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    let isEditMode = false;

    // Initialize Select2 for user dropdown
    $('#selectUser').select2({
        dropdownParent: $('#userModal'),
        placeholder: 'เลือกบุคลากร',
        width: '100%'
    }).on('change', function() {
        // Sync hidden field with select value
        $('#hiddenUserId').val($(this).val());
    });

    // Add User Button
    $('#btnAddUser').on('click', function() {
        isEditMode = false;
        $('#userModalTitle').text('เพิ่มผู้ใช้งาน');
        $('#userForm')[0].reset();
        $('#editRloesId').val('');
        $('#hiddenUserId').val('');
        $('#selectUser').val('').trigger('change');
        $('#selectUser').prop('disabled', false);
        $('.work-checkbox').prop('checked', false);
        userModal.show();
    });

    // Edit User Button
    $(document).on('click', '.btn-edit-user', function() {
        isEditMode = true;
        const id = $(this).data('id');
        const userId = $(this).data('userid');
        const works = $(this).data('works').toString().split('|');
        const status = $(this).data('status');

        $('#userModalTitle').text('แก้ไขข้อมูลผู้ใช้');
        $('#editRloesId').val(id);
        $('#hiddenUserId').val(userId); // Set hidden field for disabled select
        $('#selectUser').val(userId).trigger('change');
        $('#selectUser').prop('disabled', true); // Can't change user when editing
        $('#selectStatus').val(status);

        // Check the work checkboxes
        $('.work-checkbox').prop('checked', false);
        works.forEach(function(work) {
            $(`.work-checkbox[value="${work.trim()}"]`).prop('checked', true);
        });

        userModal.show();
    });

    // Save User
    $('#btnSaveUser').on('click', function() {
        const formData = new FormData($('#userForm')[0]);
        const url = isEditMode ? 
            '<?= base_url("Admin/Rloes/UpdateUser") ?>' : 
            '<?= base_url("Admin/Rloes/AddUser") ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.message || 'ไม่สามารถบันทึกข้อมูลได้'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาดเครือข่าย',
                    text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้'
                });
            }
        });
    });

    // Delete User
    $(document).on('click', '.btn-delete-user', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบ?',
            html: `คุณต้องการลบสิทธิ์ของ <strong>${name}</strong> ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("Admin/Rloes/DeleteUser") ?>', { RloesID: id }, function(res) {
                    if (res.status === 'success') {
                        Swal.fire('ลบสำเร็จ!', '', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', '', 'error');
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
