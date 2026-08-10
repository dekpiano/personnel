<!DOCTYPE html>
<html lang="th" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url() ?>/assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>เข้าสู่ระบบประเมิน PA | SKJ Evaluation System</title>
    <meta name="description" content="ระบบประเมินผลการพัฒนางานตามข้อตกลง (Performance Appraisal - PA) โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์" />
    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/demo.css" />

    <style>
        :root {
            --primary: #0288d1;
            --primary-dark: #01579b;
            --accent: #03c3ec;
            --glass: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        body {
            font-family: 'Kanit', 'Outfit', sans-serif;
            background-color: #0f172a;
            overflow-x: hidden;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Abstract Background Animation */
        .bg-blobs {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
            background: radial-gradient(circle at 50% 30%, #0f172a 0%, #0288d1 60%, #01579b 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.35;
            animation: move 20s infinite alternate;
        }

        .blob-1 {
            width: 550px;
            height: 550px;
            background: #03c3ec;
            top: -15%;
            left: -10%;
        }

        .blob-2 {
            width: 450px;
            height: 450px;
            background: #0288d1;
            bottom: -10%;
            right: -5%;
            animation-duration: 25s;
        }

        .blob-3 {
            width: 350px;
            height: 350px;
            background: #38bdf8;
            top: 40%;
            left: 60%;
            animation-duration: 15s;
        }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(6%, 12%) scale(1.15); }
        }

        /* Modern Login Card */
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 40px 35px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 85px;
            height: 85px;
            background: white;
            padding: 10px;
            border-radius: 22px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo img {
            max-width: 100%;
            height: auto;
        }

        .system-badge {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(2, 136, 209, 0.1);
            color: #0288d1;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .login-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Role Switcher Pills */
        .role-switcher {
            background: #e2e8f0;
            padding: 4px;
            border-radius: 16px;
            display: flex;
            margin-bottom: 25px;
        }

        .role-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 9px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            border-radius: 12px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .role-btn.active {
            background: white;
            color: #0288d1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            font-size: 0.825rem;
            letter-spacing: 0.3px;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 16px;
            border: 1.5px solid #cbd5e1;
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #0288d1;
            box-shadow: 0 0 0 4px rgba(2, 136, 209, 0.15);
            background: white;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.8);
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            color: #64748b;
        }

        .btn-modern {
            border-radius: 14px;
            padding: 13px 24px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #0288d1 0%, #01579b 100%);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(2, 136, 209, 0.4);
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #01579b 0%, #0f172a 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(2, 136, 209, 0.5);
            color: white;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 0.825rem;
            color: #64748b;
        }

        .alert-modern {
            border-radius: 14px;
            padding: 12px 18px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            border: none;
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-logo">
                    <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo">
                </div>
                <span class="system-badge"><i class="bx bx-award me-1"></i>PA EVALUATION SYSTEM</span>
                <h2 class="login-title">ระบบประเมินผล PA</h2>
                <p class="login-subtitle">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
            </div>

            <?php if (session()->getFlashdata('Error')): ?>
                <div class="alert alert-modern bounceIn" role="alert">
                    <i class='bx bx-error-circle me-2'></i> <?= session()->getFlashdata('Error'); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form id="formAuthentication" action="<?= base_url('login-pa-traditional'); ?>" method="POST">
                <input type="hidden" name="role" value="assessor">

                <div class="mb-3">
                    <label for="username" class="form-label"><i class="bx bx-user me-1 text-primary"></i>ชื่อผู้ใช้งาน</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="กรอกชื่อผู้ใช้งาน" required autofocus>
                </div>
                <div class="mb-4 form-password-toggle">
                    <label class="form-label" for="password"><i class="bx bx-lock-alt me-1 text-primary"></i>รหัสผ่าน</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password" placeholder="············" aria-describedby="password" required>
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>

                <div class="mb-2">
                    <button class="btn btn-modern btn-primary-modern w-100" type="submit">
                        <i class='bx bxs-log-in-circle fs-5'></i> เข้าสู่ระบบประเมิน PA
                    </button>
                </div>
            </form>

            <div class="footer-text">
                &copy; <script>document.write(new Date().getFullYear());</script> SKJ Performance Appraisal System
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="<?= base_url() ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/js/menu.js"></script>
    <script src="<?= base_url() ?>/assets/js/main.js"></script>
</body>
</html>
