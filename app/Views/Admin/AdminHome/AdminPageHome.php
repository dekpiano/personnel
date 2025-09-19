<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Card: Personnel -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <h5 class="card-title mb-3">บุคลากร</h5>
                        <div class="d-flex align-items-end">
                            <h4 class="mb-0 me-2"><?=$countAllPersonnel;?></h4>
                            <small>คน</small>
                        </div>
                        <small>บุคลากรทั้งหมดในระบบ</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-primary rounded p-2">
                            <i class='bx bx-group bx-sm'></i>
                        </span>
                    </div>
                </div>
                <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="btn btn-sm btn-outline-primary mt-3">จัดการข้อมูล</a>
            </div>
        </div>
    </div>

    <!-- Card: Attendance -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <h5 class="card-title mb-3">ลงเวลาปฏิบัติราชการ</h5>
                        <div class="d-flex align-items-end">
                            <h4 class="mb-0 me-2"><?= $countAttendanceToday ?? 'N/A'; ?></h4>
                            <small>คน</small>
                        </div>
                        <small>ลงเวลาวันนี้</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded p-2">
                            <i class='bx bx-calendar-check bx-sm'></i>
                        </span>
                    </div>
                </div>
                 <a href="<?=base_url('Admin/SaveAttendance');?>" class="btn btn-sm btn-outline-success mt-3">ดูข้อมูล</a>
            </div>
        </div>
    </div>

    <!-- Card: PA Evaluation -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <h5 class="card-title mb-3">การประเมิน PA</h5>
                        <div class="d-flex align-items-end">
                             <h4 class="mb-0 me-2"><?= $countPendingEvaluations ?? 'N/A'; ?></h4>
                             <small>รายการ</small>
                        </div>
                        <small>รอการตรวจสอบ</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-info rounded p-2">
                            <i class='bx bx-book-alt bx-sm'></i>
                        </span>
                    </div>
                </div>
                <a href="<?=base_url('Admin/PaConfig');?>" class="btn btn-sm btn-outline-info mt-3">ตั้งค่า/ดูข้อมูล</a>
            </div>
        </div>
    </div>

    <!-- Card: User Roles -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <h5 class="card-title mb-3">จัดการสิทธิ์ผู้ใช้งาน</h5>
                        <div class="d-flex align-items-end">
                            <h4 class="mb-0 me-2"><?= $countTotalUsers ?? 'N/A'; ?></h4>
                            <small>คน</small>
                        </div>
                        <small>ผู้ใช้งานทั้งหมด</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-warning rounded p-2">
                            <i class='bx bx-user bx-sm'></i>
                        </span>
                    </div>
                </div>
                 <a href="<?=base_url('Admin/Rloes/Setting');?>" class="btn btn-sm btn-outline-warning mt-3">จัดการสิทธิ์</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>