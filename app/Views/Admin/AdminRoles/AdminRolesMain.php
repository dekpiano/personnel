<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('title') ?><?= $title ?? 'จัดการข้อมูลกำหนดสิทธิ์การใช้งาน' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ====================================================
       LUXURY DESIGN SYSTEM FOR ROLE & PERMISSION MANAGEMENT
       Theme: Signature Royal/Ocean Blue & Modern Luxury Cards
       ==================================================== */
    :root {
        --role-blue-grad: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
    }

    /* Hero Banner Card */
    .role-hero-card {
        background: var(--role-blue-grad) !important;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .role-hero-card::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
        top: -80px;
        right: -60px;
        pointer-events: none;
    }

    .role-hero-card::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(56, 189, 248, 0) 70%);
        bottom: -50px;
        left: 25%;
        pointer-events: none;
    }

    .glass-badge {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 30px;
    }

    /* Stat KPI Cards */
    .role-stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .role-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(2, 132, 199, 0.12);
        border-color: #bae6fd;
    }

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        transition: transform 0.3s ease;
    }

    .role-stat-card:hover .stat-icon-wrap {
        transform: scale(1.1) rotate(4deg);
    }

    /* Table Lux */
    .table-lux thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 750;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
    }

    .table-lux tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-lux tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-lux tbody tr:hover {
        background-color: #f0f9ff !important;
    }

    .work-tag-pill {
        display: inline-flex;
        align-items: center;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0.15rem;
    }
</style>

<!-- Hero Banner Card -->
<div class="role-hero-card p-4 mb-4">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="glass-badge px-3 py-1 text-uppercase" style="font-size: 0.75rem;">
                    <i class="bx bx-shield-quarter me-1"></i> ความปลอดภัยและการเข้าถึง
                </span>
                <span class="glass-badge px-3 py-1" style="font-size: 0.75rem;">
                    <i class="bx bx-lock-alt me-1"></i> Role-Based Access Control
                </span>
            </div>
            <h3 class="fw-extrabold text-white mb-2 text-shadow">
                จัดการสิทธิ์การเข้าถึงระบบ (Roles & Permissions)
            </h3>
            <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                กำหนดบทบาท สิทธิ์การใช้งานระบบ และภาระงานที่รับผิดชอบสำหรับคณะผู้บริหาร เจ้าหน้าที่ และผู้ดูแลระบบ
            </p>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-sm btn-white text-primary fw-bold shadow-sm px-4 py-2 bg-white rounded-pill d-inline-flex align-items-center gap-1" id="btnAddUser">
                <i class="bx bx-plus-circle fs-5"></i> เพิ่มผู้ใช้งาน / กำหนดสิทธิ์
            </button>
        </div>
    </div>
</div>

<?php
// Separate users by status
$managers = array_filter($RolesUsers, fn($u) => $u->admin_rloes_status === 'manager');
$superadmins = array_filter($RolesUsers, fn($u) => $u->admin_rloes_status === 'superadmin');
$admins = array_filter($RolesUsers, fn($u) => in_array($u->admin_rloes_status, ['admin', 'superadmin']));
$generalAdmins = array_filter($RolesUsers, fn($u) => $u->admin_rloes_status === 'admin');

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

<!-- Stat KPI Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Managers -->
    <div class="col-12 col-sm-6 col-md-4">
        <div class="role-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">คณะผู้บริหาร (Managers)</span>
                    <h3 class="fw-bold mb-0 text-dark" data-counter="<?= count($managers) ?>"><?= count($managers) ?></h3>
                    <div class="x-small text-warning fw-bold mt-1">ผู้บังคับบัญชา / ผู้อนุมัติ</div>
                </div>
                <div class="stat-icon-wrap" style="background: #fef3c7; color: #d97706;">
                    <i class="bx bx-crown"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Super Admins -->
    <div class="col-12 col-sm-6 col-md-4">
        <div class="role-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">ผู้ดูแลระบบหลัก (Super Admins)</span>
                    <h3 class="fw-bold mb-0 text-danger" data-counter="<?= count($superadmins) ?>"><?= count($superadmins) ?></h3>
                    <div class="x-small text-danger fw-bold mt-1">สิทธิ์สูงสุดทั้งระบบ</div>
                </div>
                <div class="stat-icon-wrap" style="background: #fee2e2; color: #dc2626;">
                    <i class="bx bx-shield-quarter"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Admins -->
    <div class="col-12 col-sm-12 col-md-4">
        <div class="role-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold small d-block mb-1">เจ้าหน้าที่งานระบบ (Admins)</span>
                    <h3 class="fw-bold mb-0 text-primary" data-counter="<?= count($generalAdmins) ?>"><?= count($generalAdmins) ?></h3>
                    <div class="x-small text-primary fw-bold mt-1">ทะเบียนครู / ประเมิน PA</div>
                </div>
                <div class="stat-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                    <i class="bx bx-user-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Managers Table Card -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-label-warning text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-crown"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">คณะผู้บริหาร (Managers)</h5>
        </div>
        <span class="badge bg-label-warning rounded-pill px-3 py-1 fw-bold"><?= count($managers) ?> ท่าน</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-lux align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="ps-4">#</th>
                        <th style="min-width: 220px;">ชื่อ-นามสกุลผู้บริหาร</th>
                        <th style="min-width: 250px;">ตำแหน่งบริหาร / ขอบเขตงาน</th>
                        <th style="width: 120px;" class="text-center pe-4">จัดการ</th>
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
                            <td class="ps-4 text-muted fw-bold"><?= $i++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 bg-label-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold">
                                        <i class="bx bx-crown fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($userName) ?></div>
                                        <div class="x-small text-muted">ID: <?= esc($user->admin_rloes_userid) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($works as $work): ?>
                                        <?php if (trim($work)): ?>
                                            <span class="badge bg-label-warning fw-bold px-2.5 py-1 rounded-pill">
                                                <i class="bx bx-check me-1"></i><?= esc(trim($work)) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-icon btn-sm btn-label-warning rounded-pill shadow-none btn-edit-user" 
                                        data-id="<?= $user->admin_rloes_id ?>"
                                        data-userid="<?= $user->admin_rloes_userid ?>"
                                        data-works="<?= htmlspecialchars($user->admin_rloes_nanetype ?? '') ?>"
                                        data-status="<?= $user->admin_rloes_status ?>"
                                        title="แก้ไขสิทธิ์">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-sm btn-label-danger rounded-pill shadow-none btn-delete-user" 
                                        data-id="<?= $user->admin_rloes_id ?>"
                                        data-name="<?= esc($userName) ?>"
                                        title="ลบสิทธิ์">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($managers)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bx bx-crown fs-2 d-block mb-2 text-secondary"></i>
                                <div class="fw-bold">ยังไม่มีผู้บริหารในระบบ</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Admins & Staff Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar avatar-xs bg-label-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                <i class="bx bx-user-check"></i>
            </div>
            <h5 class="card-title mb-0 fw-bold fs-6">เจ้าหน้าที่และผู้ดูแลระบบ (Admins & Staff)</h5>
        </div>
        <span class="badge bg-label-primary rounded-pill px-3 py-1 fw-bold"><?= count($admins) ?> คน</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-lux align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="ps-4">#</th>
                        <th style="min-width: 220px;">ชื่อ-นามสกุลเจ้าหน้าที่</th>
                        <th style="min-width: 250px;">ภาระงานที่รับผิดชอบ</th>
                        <th style="width: 140px;">สถานะสิทธิ์</th>
                        <th style="width: 120px;" class="text-center pe-4">จัดการ</th>
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
                            $isSuper = $user->admin_rloes_status === 'superadmin';
                        ?>
                        <tr>
                            <td class="ps-4 text-muted fw-bold"><?= $i++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 <?= $isSuper ? 'bg-label-danger' : 'bg-label-primary' ?> rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold">
                                        <i class="bx <?= $isSuper ? 'bx-shield-quarter' : 'bx-user' ?> fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($userName) ?></div>
                                        <div class="x-small text-muted">ID: <?= esc($user->admin_rloes_userid) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($works as $work): ?>
                                        <?php if (trim($work)): ?>
                                            <span class="work-tag-pill">
                                                <i class="bx bx-briefcase-alt-2 me-1"></i><?= esc(trim($work)) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (empty(array_filter($works))): ?>
                                        <span class="text-muted small">- ดูแลภาพรวมทั้งระบบ -</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($isSuper): ?>
                                    <span class="badge bg-label-danger rounded-pill px-3 py-1 fw-bold">
                                        <i class="bx bx-shield-quarter me-1"></i>Super Admin
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-label-info rounded-pill px-3 py-1 fw-bold">
                                        <i class="bx bx-user-check me-1"></i>Admin
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-icon btn-sm btn-label-warning rounded-pill shadow-none btn-edit-user" 
                                        data-id="<?= $user->admin_rloes_id ?>"
                                        data-userid="<?= $user->admin_rloes_userid ?>"
                                        data-works="<?= htmlspecialchars($user->admin_rloes_nanetype ?? '') ?>"
                                        data-status="<?= $user->admin_rloes_status ?>"
                                        title="แก้ไขสิทธิ์">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <?php if (!$isSuper): ?>
                                    <button type="button" class="btn btn-icon btn-sm btn-label-danger rounded-pill shadow-none btn-delete-user" 
                                        data-id="<?= $user->admin_rloes_id ?>"
                                        data-name="<?= esc($userName) ?>"
                                        title="ลบสิทธิ์">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bx bx-user-x fs-2 d-block mb-2 text-secondary"></i>
                                <div class="fw-bold">ยังไม่มีเจ้าหน้าที่ในระบบ</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="userModalTitle">
                    <i class="bx bx-user-plus fs-4"></i> เพิ่มผู้ใช้งานและกำหนดสิทธิ์
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="userForm">
                    <input type="hidden" id="editRloesId" name="RloesID">
                    <input type="hidden" id="hiddenUserId" name="UserID">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1"><i class="bx bx-user me-1 text-primary"></i>เลือกบุคลากร</label>
                        <select class="form-select shadow-none select2" id="selectUser" required>
                            <option value="">-- ค้นหาและเลือกบุคลากร --</option>
                            <?php foreach ($NameTeacher as $teacher): ?>
                                <option value="<?= $teacher->pers_id ?>">
                                    <?= $teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname ?> (<?= $teacher->pers_id ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1"><i class="bx bx-briefcase-alt-2 me-1 text-primary"></i>งานที่รับผิดชอบ / ขอบเขตสิทธิ์</label>
                        <div class="border rounded-3 p-3" style="background: #f8fafc;">
                            <?php foreach ($AvailableWorks as $work): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input work-checkbox shadow-none" type="checkbox" 
                                        name="Works[]" value="<?= $work ?>" id="work_<?= md5($work) ?>">
                                    <label class="form-check-label small fw-semibold text-dark" for="work_<?= md5($work) ?>" style="cursor: pointer;">
                                        <?= $work ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-dark mb-1"><i class="bx bx-badge-check me-1 text-primary"></i>ระดับสิทธิ์ (Role Level)</label>
                        <select class="form-select shadow-none fw-bold" id="selectStatus" name="Status" required>
                            <option value="admin">Admin (เจ้าหน้าที่ปฏิบัติงาน)</option>
                            <option value="manager">Manager (ผู้บริหาร)</option>
                            <option value="superadmin">Super Admin (ผู้ดูแลระบบหลัก)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top py-2 px-4">
                <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm fw-bold" id="btnSaveUser">
                    <i class="bx bx-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Animated KPI Counters
    $('[data-counter]').each(function() {
        const $this = $(this);
        const target = parseInt($this.attr('data-counter'), 10) || 0;
        if (target === 0) return;
        $({ countNum: 0 }).animate({ countNum: target }, {
            duration: 800,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum).toLocaleString());
            },
            complete: function() {
                $this.text(target.toLocaleString());
            }
        });
    });

    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    let isEditMode = false;

    // Initialize Select2 for user dropdown
    $('#selectUser').select2({
        dropdownParent: $('#userModal'),
        placeholder: 'ค้นหาและเลือกบุคลากร',
        width: '100%'
    }).on('change', function() {
        // Sync hidden field with select value
        $('#hiddenUserId').val($(this).val());
    });

    // Add User Button
    $('#btnAddUser').on('click', function() {
        isEditMode = false;
        $('#userModalTitle').html('<i class="bx bx-user-plus fs-4"></i> เพิ่มผู้ใช้งานและกำหนดสิทธิ์');
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

        $('#userModalTitle').html('<i class="bx bx-edit fs-4"></i> แก้ไขสิทธิ์ผู้ใช้งาน');
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

    // Save User via AJAX
    $('#btnSaveUser').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...');

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
                    userModal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'บันทึกข้อมูลสิทธิ์เรียบร้อยแล้ว',
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
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> บันทึกข้อมูล');
            }
        });
    });

    // Delete User
    $(document).on('click', '.btn-delete-user', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบสิทธิ์?',
            html: `คุณต้องการลบสิทธิ์ของ <strong>${name}</strong> ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("Admin/Rloes/DeleteUser") ?>', { RloesID: id }, function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ!',
                            showConfirmButton: false,
                            timer: 1200
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', res.message || 'ไม่สามารถลบข้อมูลได้', 'error');
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
