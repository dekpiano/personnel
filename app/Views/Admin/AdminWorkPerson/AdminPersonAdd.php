<!-- Layout container -->
<div class="layout-page">
    <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>


    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y demo ">
            <div class="card">
                <div class="card-header d-flex justify-content-between flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0"><?=$title;?></h5>
                    </div>
                </div>
            </div>

            <style>
            .btn-file {
                position: relative;
                overflow: hidden;
            }

            .btn-file input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                min-width: 100%;
                min-height: 100%;
                font-size: 100px;
                text-align: right;
                filter: alpha(opacity=0);
                opacity: 0;
                outline: none;
                cursor: inherit;
                display: block;
            }
            </style>
            <!--  -->
            <form class="needs-validation" novalidate="" id="FormPersonnalAdd">
                <div class="row mt-3">
                    <div class="col-lg-3">
                        <!-- Profile picture card-->
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header">Profile Picture</div>
                            <div class="card-body text-center">
                                <!-- Profile picture image-->
                                <script>
                                var loadFile = function(event) {
                                    var output = document.getElementById('output');
                                    output.src = URL.createObjectURL(event.target.files[0]);
                                    output.onload = function() {
                                        URL.revokeObjectURL(output.src) // free memory
                                    }
                                };
                                </script>
                                <img class="mb-2 img-fluid" id="output"
                                    src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                <!-- Profile picture help block-->
                                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                <!-- Profile picture upload button-->
                                <span class="btn btn-primary btn-file">
                                    อัพโหลดรูปภาพ <input type="file" name="pers_img" id="pers_img"
                                        onchange="loadFile(event)">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <!-- Account details card-->
                        <div class="card mb-4">
                            <div class="card-header">ลงทะเบียนบุคลากรใหม่</div>
                            <div class="card-body">

                                <div class="row g-3">
                                    <div class="col-md-9">
                                        <div class="form-floating">
                                            <select class="form-select" id="pers_status" name="pers_status" required="">
                                                <option value="กำลังใช้งาน">กำลังใช้งาน</option>
                                                <option value="ย้ายสถานศึกษา">ย้ายสถานศึกษา</option>
                                                <option value="ลาออก">ลาออก</option>
                                                <option value="เกษียรอายุ">เกษียรอายุ</option>
                                            </select>
                                            <label for="pers_status">สถานะผู้ใช้งาน</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกเลือกสถานะผู้ใช้งาน
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="pers_id" name="pers_id"
                                                placeholder="" value="<?=$pers_id;?>" required="" readonly>
                                            <label for="pers_id">รหัสประจำตัว</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกชื่อจริง...
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="pers_prefix" name="pers_prefix" required="">
                                                <option value="">เลือก...</option>
                                                <option value="นาย">นาย</option>
                                                <option value="นาง">นาง</option>
                                                <option value="นางสาว">นางสาว</option>
                                                <option value="ว่าที่ร้อยตรี">ว่าที่ร้อยตรี</option>
                                                <option value="ว่าที่ร้อยตรีหญิง">ว่าที่ร้อยตรีหญิง</option>
                                            </select>
                                            <label for="pers_prefix">คำนำหน้า</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกเลือกคำนำหน้า
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="pers_firstname"
                                                name="pers_firstname" placeholder="" value="" required="">
                                            <label for="pers_firstname">ชื่อจริง</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกชื่อจริง...
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="pers_lastname"
                                                name="pers_lastname" placeholder="" value="" required="">
                                            <label for="pers_lastname">นามสกุล</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกนามสกุล...
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control pers_phone" id="pers_phone" name="pers_phone"
                                                placeholder="" value="">
                                            <label for="pers_phone">เบอร์โทรศัพท์</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกเบอร์โทรศัพท์...
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="pers_username"
                                                placeholder="ให้ใช้อีเมลของโรงเรียน" name="pers_username">
                                            <label for="pers_username">Email <span
                                                    class="text-muted">(Optional)</span></label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณากรอกอีเมล
                                        </div>
                                    </div>

                                    <hr>
                                
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select select2Personnel" id="pers_position"
                                                name="pers_position" required="">
                                                <option value="">เลือก...</option>
                                                <?php foreach ($position as $key => $value) : ?>
                                                <option value="<?=$value->posi_id;?>"><?=$value->posi_name;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="pers_position">ตำแหน่งทางการศึกษา</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณาเลือกตำแหน่งทางการศึกษา
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="display:none;" id="show_position">
                                        <div class="form-floating">
                                            <select class="form-select select2Personnel" id="pers_workother_id"
                                                name="pers_workother_id" required>
                                                <option value="0">เลือกตำแหน่งหลัก</option>
                                            </select>
                                            <label for="pers_workother_id">ตำแหน่งหลัก</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณาเลือกกลุ่มสาระการเรียนรู้
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2" style="display:none;" id="show_learning">
                                    <p class="mt-2 mb-0">เฉพาะตำแหน่งครูผู้สอน</p>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select select2Personnel" id="pers_learning"
                                                name="pers_learning" required>
                                                <option value="0">เลือกกลุ่มสาระ...</option>
                                                <?php foreach ($learning as $key => $value) : ?>
                                                <option value="<?=$value->lear_id;?>"><?=$value->lear_namethai;?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="pers_learning">กลุ่มสาระการเรียนรู้</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            กรุณาเลือกกลุ่มสาระการเรียนรู้
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php $degee = array('ชำนาญการ','ชำนาญการพิเศษ','เชี่ยวชาญ','เชี่ยวชาญพิเศษ'); ?>
                                        <div class="form-floating">
                                            <select class="form-select select2Personnel" id="pers_academic"
                                                name="pers_academic">
                                                <option value="">ไม่มีไม่ต้องเลือก...</option>
                                                <?php foreach ($degee as $key => $value) : ?>
                                                <option value="<?=$value;?>"><?=$value;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="pers_academic">วิทยฐานะ</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide a valid state.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select select2Personnel" id="pers_groupleade"
                                                name="pers_groupleade">
                                                <option value="">ไม่มีไม่ต้องเลือก...</option>
                                                <option value="หัวหน้ากลุ่มสาระ">หัวหน้ากลุ่มสาระ</option>
                                                <option value="รองหัวหน้ากลุ่มสาระ">รองหัวหน้ากลุ่มสาระ</option>
                                            </select>
                                            <label for="pers_groupleade">หัวหน้าและรองหัวหน้ากลุ่มสาระ</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide a valid state.
                                        </div>
                                    </div>
                                </div>


                                <hr class="my-4">

                                <button class="w-100 btn btn-primary btn-lg" type="submit">บันทึก</button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>








        </div>



        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
</div>
<!-- / Layout page -->