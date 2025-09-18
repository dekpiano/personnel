<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold">บุคลากรทางการศึกษา</h5>
                <a href="<?=base_url('Admin/WorkPerson/Personnel');?>" class="text-decoration-none">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>ทั้งหมด</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?=$countAllPersonnel;?></h4>
                                <!-- <small class="text-success"></small> -->
                            </div>
                            <small>คน</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class='bx bxs-file-doc bx-sm'></i>
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
<hr>
 <div class="row mt-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold">บันทึกการมาทำงาน</h5>
                <a href="<?=base_url('Admin/SaveAttendance');?>" class="text-decoration-none">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>วันนี้</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2"><?=$countAllPersonnel;?></h4>
                                <!-- <small class="text-success"></small> -->
                            </div>
                            <small>คน</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class='bx bxs-file-doc bx-sm'></i>
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>