<!-- Layout container -->
<div class="layout-page">
    <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>


    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y demo ">

            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light"> <a href="#" onclick='javascript:window.history.back()'>ย้อนกลับ</a>
                    /</span> <?=$title;?>
            </h4>

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


            <div class="row mt-3">
                <div class="col-lg-2">
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                       
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
                                src="<?=base_url('uploads/admin/Personnal/'.$Pers->pers_img);?>" alt="">
                            <!-- Profile picture help block-->
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                            <!-- Profile picture upload button-->
                            <form id="ChangeImgPersonnal" enctype="multipart/form-data">
                                <span class="btn btn-primary btn-file">
                                    เปลี่ยนรูปภาพ <input type="file" name="pers_img" id="pers_img"
                                        onchange="loadFile(event)" key-persid="<?=$Pers->pers_id?>">
                                </span>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-lg-10">

                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-justified-home"
                                    aria-controls="navs-pills-justified-home" aria-selected="true"
                                    fdprocessedid="c2enjh"><i class="tf-icons bx bx-home me-1"></i> ข้อมูลทั่วไป
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link " role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-justified-profile"
                                    aria-controls="navs-pills-justified-profile" aria-selected="false" tabindex="-1"><i
                                        class="tf-icons bx bx-user me-1"></i> ประวัติส่วนตัว</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-justified-messages"
                                    aria-controls="navs-pills-justified-messages" aria-selected="false" tabindex="-1"><i
                                        class="tf-icons bx bx-message-square me-1"></i>
                                    การศึกษา</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade  active show" id="navs-pills-justified-home" role="tabpanel">

                                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormGeneralInformation.php'); ?>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                                <?php echo $this->include('Admin/AdminWorkPerson/AdminPersonForm/FormPresonHistory.php'); ?>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-justified-messages" role="tabpanel">
                                <p>
                                    Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans
                                    macaroon gummies cupcake gummi
                                    bears
                                    cake chocolate.
                                </p>
                                <p class="mb-0">
                                    Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie
                                    brownie cake. Sweet roll icing
                                    sesame snaps caramels danish toffee. Brownie biscuit dessert dessert.
                                    Pudding jelly jelly-o tart brownie
                                    jelly.
                                </p>
                            </div>
                        </div>
                    </div>



                </div>
            </div>









        </div>



        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
</div>
<!-- / Layout page -->