<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light"> <a href="#" onclick='javascript:window.history.back()'>ย้อนกลับ</a>  /</span> <?=$title;?>
</h4>

<div id="items" class="sortable">
<?php foreach ($Teacher as $key => $v_Teacher) : ?>
<div class="card mt-3" data-id="<?=$v_Teacher->pers_id;?>">
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4 justify-content-between">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
                <img src="<?=base_url('uploads/admin/Personnal/'.$v_Teacher->pers_img)?>" alt="user-avatar"
                    class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                <p class="mb-0">
                    <?=$v_Teacher->pers_prefix.$v_Teacher->pers_firstname.' '.$v_Teacher->pers_lastname?>
                    <br>
                    ตำแหน่ง : <?=$v_Teacher->posi_name?>
                    <br>
                    <?php if($Teach) :?>
                    วิทยฐานะ : <?=$v_Teacher->pers_academic?>
                    <?php endif; ?>
                </p>
            </div>

            <div class="button-wrapper">                         
                    <a class="btn btn-primary" href="<?=base_url('Admin/WorkPerson/Personnel/Update/'.$v_Teacher->pers_id)?>">อัพเดตข้อมูล</a>   
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
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