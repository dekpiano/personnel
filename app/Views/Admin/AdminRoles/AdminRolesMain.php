<!-- Layout container -->
<div class="layout-page">
    <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light"></span> กำหนดสิทธิ์ใช้งานในระบบบริหารทั่วไป
            </h4>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">ผู้อำนวยการ</label>
                            <div class="mt-3">
                                <select
                                    class="select2Rloes form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[0]->admin_rloes_id;?>" Key-nanetype="ผู้อำนวยการ">
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[0]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <label for="">รองผู้อำนวยการบริหารทั่วไป</label>
                            <div class="mt-3">
                                <select
                                    class="select2Rloes form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[1]->admin_rloes_id;?>" Key-nanetype="รองผู้อำนวยการบริหารทั่วไป">
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[1]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">หัวหน้าบริหารทั่วไป</label>
                            <div class="mt-3">
                                <select
                                    class="select2Rloes form-select form-select-lg  SettingGeneralRloes"
                                    rloes-id="<?=$Manager[2]->admin_rloes_id;?>" Key-nanetype="หัวหน้าบริหารทั่วไป">
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[2]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-3">
                <div class="card-header">
                    <h5>งานอาคารสถานที่</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php for($i=3; $i<=5; $i++): ?>
                        <div class="col-md-4 mt-2">
                            <label for="">เจ้าหน้าที่</label>
                            <div class="mt-2">
                                <select class="form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[$i]->admin_rloes_id;?>" Key-nanetype="งานอาคารสถานที่">
                                    <option value="">เลือกเจ้าหน้าที่</option>
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[$i]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>งานเจ้าหน้าที่ทั่วไป</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php for($i=6; $i<=8; $i++): ?>
                        <div class="col-md-4 mt-2">
                            <label for="">เจ้าหน้าที่</label>
                            <div class="mt-2">
                                <select class="form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[$i]->admin_rloes_id;?>" Key-nanetype="เจ้าหน้าที่ทั่วไป">
                                    <option value="">เลือกเจ้าหน้าที่</option>
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[$i]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>งานแจ้งซ่อม</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php for($i=9; $i<=11; $i++): ?>
                        <div class="col-md-4 mt-2">
                            <label for="">เจ้าหน้าที่</label>
                            <div class="mt-2">
                                <select class="form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[$i]->admin_rloes_id;?>" Key-nanetype="งานแจ้งซ่อม">
                                    <option value="">เลือกเจ้าหน้าที่</option>
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[$i]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>งานยานพาหนะ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php for($i=12; $i<=14; $i++): ?>
                        <div class="col-md-4 mt-2">
                            <label for="">เจ้าหน้าที่</label>
                            <div class="mt-2">
                                <select class="form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[$i]->admin_rloes_id;?>" Key-nanetype="งานยานพาหนะ">
                                    <option value="">เลือกเจ้าหน้าที่</option>
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[$i]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>งานบุคคล</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php for($i=15; $i<=17; $i++): ?>
                        <div class="col-md-4 mt-2">
                            <label for="">เจ้าหน้าที่</label>
                            <div class="mt-2">
                                <select class="form-select form-select-lg SettingGeneralRloes"
                                    rloes-id="<?=$Manager[$i]->admin_rloes_id;?>" Key-nanetype="งานบุคคล">
                                    <option value="">เลือกเจ้าหน้าที่</option>
                                    <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                                    <option
                                        <?=$Manager[$i]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                        value="<?=$v_NameTeacher->pers_id?>">
                                        <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

        </div>
        <!-- / Content -->




        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
</div>
<!-- / Layout page -->


<!-- <div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <section class="cta-section theme-bg-light py-5">
                <div class="container text-center">
                    <h2 class="heading">จัดการข้อมูลบทบาทใน<?=$title;?></h2>
                </div>
            </section>
            <div class="container-xl">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">ผู้อำนวยการ</h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3" aria-label=".form-select-lg example" id="set_General_executive"
                            name="set_General_executive">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[0]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>

                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">รองผู้อำนวยการ<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3" aria-label=".form-select-lg example" id="set_General_deputy"
                            name="set_General_deputy">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[1]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>


                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">หัวหน้างาน<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3" aria-label=".form-select-lg example" id="set_General_leader"
                            name="set_General_leader">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[2]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>

                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">เจ้าหน้าที่<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3 set_General_admin" aria-label=".form-select-lg example"
                            id="set_General_adminone" name="set_General_admin"
                            admin-id="<?=$Manager[3]->admin_rloes_id;?>">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[3]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">เจ้าหน้าที่<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3 set_General_admin" aria-label=".form-select-lg example"
                            id="set_General_admintwo" name="set_General_admin"
                            admin-id="<?=$Manager[4]->admin_rloes_id;?>">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[4]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">เจ้าหน้าที่<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3 set_General_admin" aria-label=".form-select-lg example"
                            id="set_General_admintheer" name="set_General_admin"
                            admin-id="<?=$Manager[5]->admin_rloes_id;?>">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[5]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">เจ้าหน้าที่<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3 set_General_admin" aria-label=".form-select-lg example"
                            id="set_General_adminfour" name="set_General_admin"
                            admin-id="<?=$Manager[6]->admin_rloes_id;?>">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[6]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">เจ้าหน้าที่<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3 set_General_admin" aria-label=".form-select-lg example"
                            id="set_General_adminfive" name="set_General_admin"
                            admin-id="<?=$Manager[7]->admin_rloes_id;?>">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[7]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">เจ้าหน้าที่<?=$title;?></h3>
                    </div>
                    <div class="col-12 col-md-8">
                        <select class="mb-3 set_General_admin" aria-label=".form-select-lg example"
                            id="set_General_adminsix" name="set_General_admin"
                            admin-id="<?=$Manager[8]->admin_rloes_id;?>">
                            <option value="">กรุณาเลือกหัวหน้างาน</option>
                            <?php  foreach ($NameTeacher as $key => $v_NameTeacher) : ?>
                            <option <?=$Manager[8]->admin_rloes_userid == $v_NameTeacher->pers_id ? 'selected' : '';?>
                                value="<?=$v_NameTeacher->pers_id?>">
                                <?=$v_NameTeacher->pers_prefix.$v_NameTeacher->pers_firstname." ".$v_NameTeacher->pers_lastname?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>

            </div>
        </div>




    </div>

</div> -->