<!DOCTYPE html>
<html lang="th" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url() ?>/assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Admin Login | SKJ Personnel</title>
    <meta name="description" content="ระบบจัดการข้อมูลบุคลากร โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์" />
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
            --primary: #0f172a;
            --accent: #3498db;
            --glass: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            z-index: 10;
        }

        /* Decorative circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, #3498db, #2980b9);
            filter: blur(50px);
            opacity: 0.3;
            z-index: -1;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
        }

        .login-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 50px 40px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.3);
            text-align: center;
            transform: translateY(0);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-section {
            margin-bottom: 40px;
        }

        .logo-box {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 24px;
            padding: 12px;
            margin: 0 auto 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            animation: bounce 3s infinite ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .logo-box img {
            max-width: 100%;
            height: auto;
        }

        .admin-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .admin-subtitle {
            font-size: 1rem;
            color: #64748b;
        }

        .instruction-text {
            color: #475569;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        /* Custom Google Button Wrapper */
        .google-btn-container {
            margin-top: 20px;
        }

        .google-btn-container a {
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            padding: 16px 24px !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
            text-decoration: none !important;
            width: 100% !important;
            margin: 0 !important;
        }

        .google-btn-container a:hover {
            transform: scale(1.03) translateY(-3px) !important;
            border-color: #3498db !important;
            box-shadow: 0 20px 40px rgba(52, 152, 219, 0.2) !important;
        }

        .google-btn-container a i {
            font-size: 1.5rem !important;
            color: #4285F4 !important;
        }

        .error-alert {
            background: #fff1f2;
            color: #e11d48;
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            border: 1px solid #ffe4e6;
        }

        .back-link {
            display: inline-block;
            margin-top: 35px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-section">
                <div class="logo-box">
                    <img src="https://skj.ac.th/uploads/logoSchool/LogoSKJ_4.png" alt="SKJ Logo">
                </div>
                <h1 class="admin-title">ADMIN PANEL</h1>
                <p class="admin-subtitle">Personnel Management System</p>
            </div>

            <?php if (session()->getFlashdata('Error')): ?>
                <div class="error-alert">
                    <i class='bx bx-error-circle me-1'></i> <?= session()->getFlashdata('Error'); ?>
                </div>
            <?php endif; ?>

            <p class="instruction-text">กรุณาเข้าสู่ระบบด้วยอีเมลโรงเรียนเพื่อจัดการข้อมูล</p>

            <div class="google-btn-container">
                <?= $GoogleButton ?>
            </div>

            <a href="<?= base_url(); ?>" class="back-link">
                <i class='bx bx-arrow-back'></i> กลับหน้าหลัก
            </a>
        </div>
    </div>

    <!-- Core JS -->
    <script src="<?= base_url() ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= base_url() ?>/assets/vendor/js/bootstrap.js"></script>
</body>
</html>
