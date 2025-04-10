<!-- Layout container -->
<div class="layout-page">
    <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y demo">           
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
            

        </div>



        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
</div>
<!-- / Layout page -->


<!-- เพิ่มหนังสือคำสั่ง Modal -->
<div class="modal right" id="rightModal2" tabindex="-1" aria-labelledby="rightModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md w-100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rightModalLabel">เพิ่มข้อมูลคำสั่ง</h5>

            </div>
            <div class="modal-body">
                <div class="col-md">

                    <form class="needs-validation" novalidate="" id="FromLocationRoomInsert">
                        <div class="mb-3">
                            <label class="form-label" for="location_name">ชื่อห้อง / สถานที่</label>
                            <input type="text" class="form-control" id="location_name" name="location_name"
                                placeholder="ตย.ห้องประชุม 72" required="">
                            <div class="invalid-feedback"> กรุณากรอกชื่อห้อง / สถานที่ </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="location_detail">รายละเอียด</label>
                            <textarea class="form-control" id="location_detail" name="location_detail" rows="3"
                                required=""></textarea>
                            <div class="invalid-feedback"> กรอกรายละเอียด</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="location_number">เลขห้อง อาคาร / สถานที่</label>
                            <input type="text" class="form-control" id="location_number" name="location_number"
                                placeholder="อาคาร 4 ชั้น 1" required="">
                            <div class="invalid-feedback"> กรอกอาคาร / สถานที่ </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="dicta_createdate">จำนวนที่นั่ง</label>
                            <input type="number" class="form-control" id="location_seats" name="location_seats"
                                required="">
                            <div class="invalid-feedback"> กรอกจำนวนที่นั่ง </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="dicta_file">ไพล์แนบรูป</label>
                            <input type="file" class="form-control" id="location_img" name="location_img" required="">
                            <div class="invalid-feedback"> กรุณาแนบไฟล์ เป็น PDF เท่านั้น </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End เพิ่มหนังสือคำสั่ง Modal -->

<!-- เพิ่มหนังสือคำสั่ง Modal -->
<div class="modal right" id="UpdateInstruction" tabindex="-1" aria-labelledby="rightModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md w-100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rightModalLabel">เพิ่มข้อมูลคำสั่ง</h5>

            </div>
            <div class="modal-body">
                <div class="col-md">

                    <form class="needs-validation" novalidate="" id="FromDictationInsert">
                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-name">ชื่อห้อง / สถานที่</label>
                            <input type="text" class="form-control" id="dicta_number" name="dicta_number"
                                placeholder="ศธ/111" required="">
                            <div class="invalid-feedback"> กรุณากรอกชื่อห้อง / สถานที่ </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-name">เลขที่คำสั่ง</label>
                            <input type="text" class="form-control" id="dicta_number" name="dicta_number"
                                placeholder="ศธ/111" required="">
                            <div class="invalid-feedback"> ใส่เลขคำสั่ง </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="dicta_createdate">วันที่คำสั่ง</label>
                            <input type="datetime-local" class="form-control flatpickr-validation flatpickr-input"
                                id="dicta_createdate" name="dicta_createdate" required="">
                            <div class="invalid-feedback"> โปรดเลือกวันที่คำสั่ง </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="dicta_title">ชื่อเรื่อง</label>
                            <textarea class="form-control" id="dicta_title" name="dicta_title" rows="3"
                                required=""></textarea>
                            <div class="invalid-feedback"> กรอกหัวเรื่อง </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="dicta_file">ไพล์แนบ (เป็น PDF เท่านั้น)</label>
                            <input type="file" class="form-control" id="dicta_file" name="dicta_file" required="">
                            <div class="invalid-feedback"> กรุณาแนบไฟล์ เป็น PDF เท่านั้น </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End เพิ่มหนังสือคำสั่ง Modal -->