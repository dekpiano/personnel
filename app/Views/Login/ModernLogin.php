<!DOCTYPE html>
<html lang="th" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url() ?>/assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>เข้าสู่ระบบ | SKJ Personnel Hub</title>
    <meta name="description" content="ระบบบริหารงานบุคคล โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์" />
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
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --accent: #3b82f6;
            --glass: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            font-family: 'Kanit', 'Outfit', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
            height: 100vh;
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
            background: radial-gradient(circle at 0% 0%, #e0f2fe 0%, #f8fafc 50%, #eff6ff 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: move 20s infinite alternate;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: #3b82f6;
            top: -10%;
            left: -10%;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: #0ea5e9;
            bottom: -5%;
            right: -5%;
            animation-duration: 25s;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: #6366f1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-duration: 15s;
        }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(5%, 10%) scale(1.1); }
        }

        /* Modern Login Card */
        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: white;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo img {
            max-width: 100%;
            height: auto;
        }

        .login-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 0.95rem;
            color: #64748b;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 18px;
            border: 1.5px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: white;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.5);
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
        }

        .btn-modern {
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
        }

        .btn-primary-modern {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .btn-primary-modern:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
        }

        .btn-google {
            background: white;
            color: #1e293b;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .divider {
            margin: 25px 0;
            display: flex;
            align-items: center;
            text-align: center;
            color: #94a3b8;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider:not(:empty)::before { margin-right: .75rem; }
        .divider:not(:empty)::after { margin-left: .75rem; }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .alert-modern {
            border-radius: 14px;
            padding: 12px 18px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            border: none;
            background: #fee2e2;
            color: #991b1b;
        }
        .google-btn-wrapper a {
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            transition: all 0.3s;
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 1.5px solid #e2e8f0 !important;
            background: white !important;
            color: #1e293b !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
            width: 100% !important;
            margin: 0 !important;
        }

        .google-btn-wrapper a:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
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
                <h2 class="login-title">Personnel Hub</h2>
                <p class="login-subtitle">ระบบบริหารงานบุคคล SKJ</p>
            </div>

            <?php if (session()->getFlashdata('Error')): ?>
                <div class="alert alert-modern bounceIn" role="alert">
                    <i class='bx bx-error-circle me-2'></i> <?= session()->getFlashdata('Error'); ?>
                </div>
            <?php endif; ?>

            <!-- Google Login Section (Staff) -->
            <div class="mb-2 text-center google-btn-wrapper">
                <?= $GoogleButton ?>
            </div>

            <div class="divider">หรือใช้งานสำหรับผู้ประเมิน</div>

            <!-- Traditional Login Section (Assessor) -->
            <form id="formAuthentication" action="<?= base_url('login-pa-traditional'); ?>" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
                </div>
                <div class="mb-4 form-password-toggle">
                    <label class="form-label" for="password">รหัสผ่าน</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password" placeholder="············" aria-describedby="password" required>
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
                
                <!-- Hidden Role for PA Login (always assessor in this form) -->
                <input type="hidden" name="role" value="assessor">

                <div class="mb-2">
                    <button class="btn btn-modern btn-primary-modern w-100" type="submit">
                        <i class='bx bxs-key'></i> เข้าสู่ระบบผู้ประเมิน
                    </button>
                </div>
            </form>

            <div class="footer-text">
                &copy; <script>document.write(new Date().getFullYear());</script> SKJ Personnel System
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
