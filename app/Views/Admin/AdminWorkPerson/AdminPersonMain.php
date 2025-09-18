<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between flex-column flex-md-row">
        <div class="head-label text-center">
            <h5 class="card-title mb-0"><?=$title;?></h5>
        </div>
        <div class="dt-action-buttons text-end pt-3 pt-md-0">
            <div class="dt-buttons btn-group flex-wrap">

                <a class="btn btn-secondary create-new btn-primary"
                    href="<?=base_url('Admin/WorkPerson/Personnel/Add')?>">
                    <span><i class="bx bx-plus me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">
                            เพิ่มข้อมูล
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <h5>ผู้บริหาร</h5>
    <div class="col-sm-6 col-xl-3">
    <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-normal">Total <?=$Executive[0]->NumAll;?> users</h6>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        <?php 
                        $sub = explode(',',$Executive[0]->AllImg);
                        foreach ($sub as $key => $value): 
                     
                            ?>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                            class="avatar avatar-sm pull-up" >
                            <img class="rounded-circle" src="<?=base_url('uploads/admin/Personnal/'.$value)?>" alt="Avatar">
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h4 class="mb-1">ผู้บริหารโรงเรียน</h4>
                        <a href="<?=base_url('Admin/WorkPerson/Personnel/Group/Executive')?>"
                            class="role-edit-modal"><small>ดูทั้งหมด</small></a>
                    </div>
                    <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row g-4 mt-3">
    <h5>ครูผู้สอน</h5>
    <?php foreach ($Learning as $key => $v_Lear): ?>
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-normal">Total <?=$v_Lear->NumAll;?> users</h6>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        <?php 
                        $sub = explode(',',$v_Lear->AllImg);
                        foreach ($sub as $key => $value): 
                            ?>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                            class="avatar avatar-sm pull-up" >
                            <img class="rounded-circle" src="<?=base_url('uploads/admin/Personnal/'.$value)?>" alt="Avatar">
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h4 class="mb-1"><?=$v_Lear->lear_namethai?></h4>
                        <a href="<?=base_url('Admin/WorkPerson/Personnel/Group/'.$v_Lear->lear_id)?>"
                            class="role-edit-modal"><small>ดูทั้งหมด</small></a>
                    </div>
                    <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
                </div>
            </div>
        </div>

    </div>
    <?php endforeach; ?>
</div>
<hr>
<div class="row g-4 mt-3">
    <h5>สายสนับสนุน</h5>
    <?php foreach ($Support as $key => $v_Support) : ?>

    <div class="col-sm-6 col-xl-4">
    <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-normal">Total <?=$v_Support->NumAll?> users</h6>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        <?php 
                        $sub = explode(',',$v_Support->AllImg);
                        foreach ($sub as $key => $value): 
                     
                            ?>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                            class="avatar avatar-sm pull-up" >
                            <img class="rounded-circle" src="<?=base_url('uploads/admin/Personnal/'.$value)?>" alt="Avatar">
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h4 class="mb-1"><?=$v_Support->posi_name?></h4>
                        <a href="<?=base_url('Admin/WorkPerson/Personnel/Group/'.$v_Support->posi_id)?>"
                            class="role-edit-modal"><small>ดูทั้งหมด</small></a>
                    </div>
                    <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
                </div>
            </div>
        </div>
    </div>


    <?php endforeach ;?>
</div>

<!-- Modals -->
<div class="modal right" id="rightModal2" tabindex="-1" aria-labelledby="rightModalLabel" aria-hidden="true">
    ...
</div>
<div class="modal right" id="UpdateInstruction" tabindex="-1" aria-labelledby="rightModalLabel" aria-hidden="true">
    ...
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?=base_url()?>/assets/js/Admin/AdminPersonnal/AdminPersonnalMain.js?v=16.2"></script>
<script src="<?=base_url()?>/assets/js/Admin/AdminPersonnal/AdminPersonnelApiProvince.js?v=1.2"></script>
<?= $this->endSection() ?>