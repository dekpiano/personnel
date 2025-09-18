<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner mx-auto" style="max-width: 400px;">
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="mb-2">เข้าสู่ระบบแบบประเมิน PA</h4> -->

                    <!-- Traditional Login Section -->
                    <h5 class="text-center">เข้าสู่ระบบสำหรับผู้ประเมิน PA</h5>
                    <form id="formAuthentication" class="mb-3" action="<?= base_url('login-pa-traditional'); ?>"
                        method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Enter your username" autofocus />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">บทบาท</label>
                            <select id="role" name="role" class="form-select">
                                <option value="assessor">ผู้ประเมิน</option>
                                <!-- <option value="admin">ผู้ดูแลระบบ</option> -->
                            </select>
                        </div>
                        
                    <?php if (session()->getFlashdata('Error')): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <?= session()->getFlashdata('Error'); ?>
                    </div>
                    <?php endif; ?>
                        <div class="mb-3">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="menu-icon tf-icons bx bxs-key"></i> เข้าสู่ระบบ ผู้ประเมิน
                            </button>
                        </div>
                    </form>
                    <div class="divider my-4">
                        <div class="divider-text">หรือ</div>
                    </div>


                    <!-- Google Login Section -->
                    <div class="mb-3">
                        <h6 class="text-center">เข้าสู่ระบบสำหรับเจ้าหน้าที่</h6>
                       <a href="<?=base_url('LoginOfficerPersonnel?return_to='.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>"
                            class="btn btn-primary w-100">
                        <i class="menu-icon tf-icons bx bxs-key"></i>
                            เข้าสู่ระบบ เจ้าหน้าที่
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>