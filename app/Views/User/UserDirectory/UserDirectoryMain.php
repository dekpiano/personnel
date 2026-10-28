<?php
/**
 * View: UserDirectoryMain
 * Version: Modern Profile Card (Blue Template Edition - High Clarity)
 */

function getInitials($name) {
    return mb_substr($name, 0, 1, 'UTF-8');
}

// Group teachers by learning group id
$teachersByGroup = [];
foreach ($AllTeachers ?? [] as $teacher) {
    if (is_object($teacher) && isset($teacher->pers_learning)) {
        $teachersByGroup[$teacher->pers_learning][] = $teacher;
    }
}

// Group support staff by position
$staffByPosition = [];
$positionList = [];
foreach ($SupportStaff ?? [] as $staff) {
    if (is_object($staff)) {
        $posKey = !empty($staff->pers_position) ? $staff->pers_position : 'other';
        $staffByPosition[$posKey][] = $staff;
        if (!isset($positionList[$posKey])) {
            $positionList[$posKey] = [
                'posi_id' => $posKey,
                'posi_name' => !empty($staff->posi_name) ? $staff->posi_name : 'ตำแหน่งอื่นๆ'
            ];
        }
    }
}
?>
<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<link href="https://fonts.googleapis.com/css2?family=K2D:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-blue: #0284c7;
        --secondary-blue: #0369a1;
        --royal-blue: #1d4ed8;
        --deep-blue: #075985;
        --leader-gold: #f59e0b;
    }

    body, h1, h2, h3, h4, h5, h6, .p-card-name, .p-card-role, .switch-btn, .l-filter-btn, .s-filter-btn, .badge {
        font-family: 'K2D', sans-serif !important;
    }

    body {
        background-color: #f8fafc;
        color: #334155;
    }

    /* --- Hero Section --- */
    .hero-organized {
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
        border-radius: 28px;
        padding: 3.5rem 2rem 3.2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 20px 45px -12px rgba(2, 132, 199, 0.4);
        position: relative;
        overflow: hidden;
    }
    .hero-organized::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-organized::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 8%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56,189,248,0.22) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255,255,255,0.25);
    }

    /* --- Switcher --- */
    .main-switcher-wrap {
        display: flex;
        justify-content: center;
        margin-top: -38px;
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 10;
        padding: 0 1rem;
        width: 100%;
    }
    .switcher-container {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 6px;
        border-radius: 100px;
        box-shadow: 0 12px 35px -8px rgba(15, 23, 42, 0.12), 0 2px 6px rgba(15, 23, 42, 0.04);
        display: flex;
        border: 1px solid rgba(226, 232, 240, 0.9);
        gap: 6px;
        max-width: 100%;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .switcher-container::-webkit-scrollbar {
        display: none;
    }
    .switch-btn {
        border: none;
        background: transparent;
        padding: 0.7rem 1.6rem;
        border-radius: 100px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    .switch-btn:hover {
        color: #0284c7;
        background: rgba(2, 132, 199, 0.06);
    }
    .switch-btn.active {
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%);
        color: white;
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
    }

    /* --- Filter Groups --- */
    .learning-filter-container {
        display: flex;
        justify-content: flex-start;
        flex-wrap: nowrap;
        gap: 8px;
        margin-bottom: 2.5rem;
        padding: 0.75rem 1rem;
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px -4px rgba(15, 23, 42, 0.05);
        max-width: 100%;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .learning-filter-container::-webkit-scrollbar { display: none; }

    .l-filter-btn {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 0.55rem 1.35rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.25s ease;
    }
    .l-filter-btn:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #cbd5e1;
    }
    .l-filter-btn.active {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: white;
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }

    .s-filter-btn {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 0.55rem 1.35rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }
    .s-filter-btn:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #cbd5e1;
    }
    .s-filter-btn.active { 
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); 
        color: white; 
        border-color: #0284c7; 
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }
    .s-filter-btn.active .badge {
        background-color: rgba(255,255,255,0.25) !important;
        color: #ffffff !important;
    }

    .badge-position-header {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        color: #0369a1 !important;
        border: 1px solid #bae6fd;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    /* --- Sections Control --- */
    .directory-section { display: none; }
    .directory-section.active { display: block; animation: sectionFade 0.5s ease; }
    @keyframes sectionFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* ========================================================
       MODERN PROFILE CARD - BLUE THEME & HIGH IMAGE CLARITY
       ======================================================== */
    .p-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 575px) {
        .p-grid {
            grid-template-columns: repeat(1, 1fr);
            max-width: 340px;
            margin: 0 auto;
            gap: 1.25rem;
        }
    }
    @media (min-width: 576px) and (max-width: 899px) {
        .p-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
    }
    @media (min-width: 900px) and (max-width: 1199px) {
        .p-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
    }
    @media (min-width: 1200px) {
        .p-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 1.75rem;
        }
    }

    .p-card {
        height: 460px;
        border-radius: 26px;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
        box-shadow: 0 12px 28px -5px rgba(2, 132, 199, 0.28);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        border: 1px solid rgba(255, 255, 255, 0.2);
        isolation: isolate;
    }

    .p-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 45px -8px rgba(2, 132, 199, 0.48);
        border-color: rgba(255, 255, 255, 0.45);
    }

    /* Full Background Image - Ultra Crisp, No Blur Filter */
    .p-card-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        z-index: 1;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: auto;
        transform: translateZ(0);
        backface-visibility: hidden;
        transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .p-card:hover .p-card-bg {
        transform: scale(1.06);
    }

    /* Avatar Fallback with School Signature Blue Gradient */
    .p-avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        position: relative;
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%);
    }
    .p-avatar-placeholder::before {
        content: '';
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, transparent 70%);
        pointer-events: none;
    }
    .p-avatar-initials {
        font-size: 5rem;
        font-weight: 800;
        color: #ffffff;
        text-shadow: 0 4px 15px rgba(0,0,0,0.25);
    }

    /* Signature Blue Gradient Overlay - Only Covers Lower Info Section, Face Remains 100% Crisp */
    .p-card-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        pointer-events: none;
        background: linear-gradient(to top, 
            #075985 0%, 
            rgba(7, 89, 133, 0.95) 24%, 
            rgba(2, 132, 199, 0.65) 44%, 
            rgba(2, 132, 199, 0.15) 58%, 
            transparent 72%
        );
    }

    /* Card Content Area */
    .p-card-content {
        position: relative;
        z-index: 3;
        padding: 1.25rem 1.15rem 1.15rem;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        color: #ffffff;
    }

    .p-card-header {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .p-card-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        line-height: 1.35;
        text-shadow: 0 2px 6px rgba(0,0,0,0.5);
    }

    .p-card-nick {
        font-size: 0.85rem;
        color: #e0f2fe;
        font-weight: 500;
    }

    .verified-icon {
        color: #38bdf8;
        font-size: 1.25rem;
        display: inline-flex;
        align-items: center;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
    }

    .p-card-role {
        font-size: 0.83rem;
        color: #e0f2fe;
        font-weight: 500;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
        text-shadow: 0 1px 3px rgba(0,0,0,0.4);
    }

    /* 2-Column Balanced Quick Info Bar */
    .p-stats-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 14px;
        padding: 0.5rem 0.85rem;
        margin-top: 0.15rem;
    }
    .p-stat-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-width: 0;
    }
    .p-stat-val {
        font-size: 0.82rem;
        font-weight: 700;
        color: #ffffff;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }
    .p-stat-lbl {
        font-size: 0.7rem;
        color: #bae6fd;
        font-weight: 500;
        margin-top: 2px;
    }
    .p-stat-divider {
        width: 1px;
        height: 22px;
        background: rgba(255, 255, 255, 0.22);
        margin: 0 0.65rem;
        flex-shrink: 0;
    }

    /* Full-Width Luxury Glowing Action Button */
    .p-actions-row {
        display: flex;
        width: 100%;
        margin-top: 0.3rem;
    }

    .glow-btn-main {
        width: 100%;
        background: #ffffff;
        color: #0369a1 !important;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 0.68rem 1rem;
        border-radius: 100px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.4),
                    0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .glow-btn-main:hover {
        background: #ffffff;
        color: #1d4ed8 !important;
        box-shadow: 0 0 18px rgba(255, 255, 255, 0.8),
                    0 0 28px rgba(56, 189, 248, 0.7),
                    0 6px 16px rgba(2, 132, 199, 0.4);
        transform: translateY(-2px);
    }

    /* Top Leadership Badges */
    .leader-top-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 4px 14px rgba(217, 119, 6, 0.45);
        display: flex;
        align-items: center;
        gap: 4px;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }
    .deputy-top-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.45);
        display: flex;
        align-items: center;
        gap: 4px;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }
    .head-top-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.45);
        display: flex;
        align-items: center;
        gap: 4px;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .leader-glow {
        border-color: rgba(245, 158, 11, 0.5) !important;
        box-shadow: 0 12px 30px -5px rgba(245, 158, 11, 0.35) !important;
    }
    .leader-glow:hover {
        border-color: #f59e0b !important;
        box-shadow: 0 24px 45px -8px rgba(245, 158, 11, 0.5) !important;
    }

    .head-glow {
        border-color: rgba(99, 102, 241, 0.5) !important;
        box-shadow: 0 12px 30px -5px rgba(99, 102, 241, 0.35) !important;
    }
    .head-glow:hover {
        border-color: #6366f1 !important;
        box-shadow: 0 24px 45px -8px rgba(99, 102, 241, 0.5) !important;
    }
</style>

<!-- Hero -->
<div class="hero-organized text-center">
    <div class="mb-2">
        <span class="hero-tag"><i class='bx bxs-id-card'></i> SKJ DIRECTORY</span>
    </div>
    <h2 class="text-white fw-bold mb-1 fs-2">ทำเนียบครูและบุคลากร</h2>
    <p class="text-white opacity-80 mb-0 fs-6">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
</div>

<!-- Switcher -->
<div class="main-switcher-wrap">
    <div class="switcher-container">
        <button class="switch-btn active" data-target="executives">
            <i class='bx bxs-crown'></i> คณะผู้บริหาร
        </button>
        <button class="switch-btn" data-target="teachers">
            <i class='bx bxs-group'></i> คณาจารย์
        </button>
        <button class="switch-btn" data-target="staff">
            <i class='bx bxs-user-detail'></i> บุคลากร
        </button>
    </div>
</div>

<div class="container-fluid px-lg-5">
    
    <!-- Section: Executives -->
    <div id="section-executives" class="directory-section active">
        <div class="p-grid justify-content-center">
            <?php foreach ($Executive as $exec): ?>
                <?php 
                    $isDirector = (strpos($exec->posi_name, 'ผู้อำนวยการ') !== false && strpos($exec->posi_name, 'รอง') === false);
                    $shortGroup = 'ฝ่ายบริหาร';
                    $shortRank = $isDirector ? 'ผู้อำนวยการ' : 'รอง ผอ.';
                ?>
                <div class="p-card <?= $isDirector ? 'leader-glow' : '' ?>">
                    <?php if($isDirector): ?>
                        <div class="leader-top-badge"><i class='bx bxs-crown'></i> ผู้อำนวยการ</div>
                    <?php else: ?>
                        <div class="deputy-top-badge"><i class='bx bxs-star'></i> รองผู้อำนวยการ</div>
                    <?php endif; ?>

                    <?php if (!empty($exec->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $exec->pers_img)): ?>
                        <img class="p-card-bg lozad" data-src="<?= base_url('uploads/admin/Personnal/' . $exec->pers_img) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="<?= esc($exec->pers_firstname) ?>">
                    <?php else: ?>
                        <div class="p-card-bg p-avatar-placeholder">
                            <span class="p-avatar-initials"><?= getInitials($exec->pers_firstname) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="p-card-overlay"></div>

                    <div class="p-card-content">
                        <div class="p-card-header">
                            <h3 class="p-card-name">
                                <?= esc($exec->pers_prefix . $exec->pers_firstname . ' ' . $exec->pers_lastname) ?>
                                <?php if (!empty($exec->pers_nickname)): ?>
                                    <span class="p-card-nick">(<?= esc($exec->pers_nickname) ?>)</span>
                                <?php endif; ?>
                                <i class='bx bxs-badge-check verified-icon' title="ผู้บริหารสถานศึกษา"></i>
                            </h3>
                            <p class="p-card-role"><?= esc($exec->posi_name) ?></p>
                        </div>

                        <div class="p-stats-bar">
                            <div class="p-stat-col">
                                <span class="p-stat-val text-truncate" title="ฝ่ายบริหาร"><?= esc($shortGroup) ?></span>
                                <span class="p-stat-lbl">กลุ่มงาน</span>
                            </div>
                            <div class="p-stat-divider"></div>
                            <div class="p-stat-col">
                                <span class="p-stat-val text-truncate" title="<?= esc($exec->posi_name) ?>"><?= esc($shortRank) ?></span>
                                <span class="p-stat-lbl">ตำแหน่ง</span>
                            </div>
                        </div>

                        <div class="p-actions-row">
                            <a href="<?= base_url('directory/detail/' . $exec->pers_id) ?>" class="glow-btn-main">
                                <i class='bx bx-id-card fs-5'></i> ดูข้อมูลประวัติ
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section: Teachers -->
    <div id="section-teachers" class="directory-section">
        <div class="learning-filter-container shadow-sm">
            <button class="l-filter-btn active" data-group="all"><i class='bx bx-grid-alt me-1'></i> ดูทั้งหมด</button>
            <?php foreach ($LearningGroups as $group): ?>
                <button class="l-filter-btn" data-group="<?= esc($group->lear_id) ?>"><?= esc($group->lear_namethai) ?></button>
            <?php endforeach; ?>
        </div>

        <div id="teachersGridContainer">
            <?php foreach ($LearningGroups as $group): ?>
                <?php if (isset($teachersByGroup[$group->lear_id])): ?>
                    <div class="teacher-group-block" id="group-block-<?= esc($group->lear_id) ?>" data-group-id="<?= esc($group->lear_id) ?>">
                        <div class="text-center mb-4">
                            <span class="badge badge-position-header fs-6 px-4 py-2 rounded-pill shadow-sm">
                                <i class='bx bx-book-reader me-1.5'></i> กลุ่มสาระการเรียนรู้<?= esc($group->lear_namethai) ?> (<?= count($teachersByGroup[$group->lear_id]) ?> คน)
                            </span>
                        </div>

                        <div class="p-grid">
                            <?php 
                                // Sort to bring Head of group to front
                                $sortedTeachers = $teachersByGroup[$group->lear_id];
                                usort($sortedTeachers, function($a, $b) {
                                    return ($b->pers_groupleade === 'หัวหน้ากลุ่มสาระ') - ($a->pers_groupleade === 'หัวหน้ากลุ่มสาระ');
                                });
                            ?>
                            <?php foreach ($sortedTeachers as $teacher): ?>
                            <?php 
                                $isHead = ($teacher->pers_groupleade === 'หัวหน้ากลุ่มสาระ'); 
                                $shortGroup = $group->lear_namethai;
                                $rankName = !empty($teacher->pers_academic) ? $teacher->pers_academic : ($teacher->posi_name ?: 'ครูผู้สอน');
                            ?>
                            <div class="p-card <?= $isHead ? 'head-glow' : '' ?>">
                                <?php if($isHead): ?>
                                    <div class="head-top-badge"><i class='bx bxs-crown'></i> หัวหน้ากลุ่ม</div>
                                <?php endif; ?>

                                <?php if (!empty($teacher->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $teacher->pers_img)): ?>
                                     <img class="p-card-bg lozad" data-src="<?= base_url('uploads/admin/Personnal/' . $teacher->pers_img) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="<?= esc($teacher->pers_firstname) ?>">
                                <?php else: ?>
                                    <div class="p-card-bg p-avatar-placeholder">
                                        <span class="p-avatar-initials"><?= getInitials($teacher->pers_firstname) ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="p-card-overlay"></div>

                                <div class="p-card-content">
                                    <div class="p-card-header">
                                        <h3 class="p-card-name">
                                            <?= esc($teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname) ?>
                                            <?php if (!empty($teacher->pers_nickname)): ?>
                                                <span class="p-card-nick">(<?= esc($teacher->pers_nickname) ?>)</span>
                                            <?php endif; ?>
                                            <i class='bx bxs-badge-check verified-icon' title="ครูผู้สอน"></i>
                                        </h3>
                                        <p class="p-card-role"><?= esc($isHead ? 'หัวหน้ากลุ่มสาระการเรียนรู้' . $group->lear_namethai : ($teacher->pers_academic ?: $teacher->posi_name)) ?></p>
                                    </div>

                                    <div class="p-stats-bar">
                                        <div class="p-stat-col">
                                            <span class="p-stat-val text-truncate" title="กลุ่มสาระฯ <?= esc($shortGroup) ?>"><?= esc($shortGroup) ?></span>
                                            <span class="p-stat-lbl">กลุ่มสาระฯ</span>
                                        </div>
                                        <div class="p-stat-divider"></div>
                                        <div class="p-stat-col">
                                            <span class="p-stat-val text-truncate" title="<?= esc($rankName) ?>"><?= esc($rankName) ?></span>
                                            <span class="p-stat-lbl">ตำแหน่ง</span>
                                        </div>
                                    </div>

                                    <div class="p-actions-row">
                                        <a href="<?= base_url('directory/detail/' . $teacher->pers_id) ?>" class="glow-btn-main">
                                            <i class='bx bx-id-card fs-5'></i> ดูข้อมูลประวัติ
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="my-5"></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section: Staff -->
    <div id="section-staff" class="directory-section">
        <div class="learning-filter-container staff-filter-container shadow-sm">
            <button class="s-filter-btn active" data-position="all">
                <i class='bx bx-grid-alt'></i> ดูทั้งหมด
            </button>
            <?php foreach ($positionList as $pos): ?>
                <button class="s-filter-btn" data-position="<?= esc($pos['posi_id']) ?>">
                    <?= esc($pos['posi_name']) ?> <span class="badge bg-label-primary rounded-pill ms-1 px-2 py-0.5"><?= count($staffByPosition[$pos['posi_id']] ?? []) ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="staffGridContainer">
            <?php foreach ($positionList as $pos): ?>
                <?php $staffGroup = $staffByPosition[$pos['posi_id']] ?? []; ?>
                <?php if (!empty($staffGroup)): ?>
                    <div class="staff-position-block" id="staff-block-<?= esc($pos['posi_id']) ?>" data-position-id="<?= esc($pos['posi_id']) ?>">
                        <div class="text-center mb-4">
                            <span class="badge badge-position-header fs-6 px-4 py-2 rounded-pill shadow-sm">
                                <i class='bx bx-briefcase-alt-2 me-1.5'></i> <?= esc($pos['posi_name']) ?> (<?= count($staffGroup) ?> คน)
                            </span>
                        </div>
                        <div class="p-grid">
                            <?php foreach ($staffGroup as $staff): ?>
                            <?php 
                                $roleName = !empty($staff->work_name) ? $staff->work_name : $staff->posi_name;
                                $shortGroup = 'สายสนับสนุน';
                            ?>
                            <div class="p-card">
                                <?php if (!empty($staff->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $staff->pers_img)): ?>
                                    <img class="p-card-bg lozad" data-src="<?= base_url('uploads/admin/Personnal/' . $staff->pers_img) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="<?= esc($staff->pers_firstname) ?>">
                                <?php else: ?>
                                    <div class="p-card-bg p-avatar-placeholder">
                                        <span class="p-avatar-initials"><?= getInitials($staff->pers_firstname) ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="p-card-overlay"></div>

                                <div class="p-card-content">
                                    <div class="p-card-header">
                                        <h3 class="p-card-name">
                                            <?= esc($staff->pers_prefix . $staff->pers_firstname . ' ' . $staff->pers_lastname) ?>
                                            <?php if (!empty($staff->pers_nickname)): ?>
                                                <span class="p-card-nick">(<?= esc($staff->pers_nickname) ?>)</span>
                                            <?php endif; ?>
                                            <i class='bx bxs-badge-check verified-icon' title="บุคลากรทางการศึกษา"></i>
                                        </h3>
                                        <p class="p-card-role"><?= esc($roleName) ?></p>
                                    </div>

                                    <div class="p-stats-bar">
                                        <div class="p-stat-col">
                                            <span class="p-stat-val text-truncate" title="ฝ่ายสนับสนุน"><?= esc($shortGroup) ?></span>
                                            <span class="p-stat-lbl">กลุ่มงาน</span>
                                        </div>
                                        <div class="p-stat-divider"></div>
                                        <div class="p-stat-col">
                                            <span class="p-stat-val text-truncate" title="<?= esc($roleName) ?>"><?= esc($roleName) ?></span>
                                            <span class="p-stat-lbl">ตำแหน่ง</span>
                                        </div>
                                    </div>

                                    <div class="p-actions-row">
                                        <a href="<?= base_url('directory/detail/' . $staff->pers_id) ?>" class="glow-btn-main">
                                            <i class='bx bx-id-card fs-5'></i> ดูข้อมูลประวัติ
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="my-5"></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<div class="py-5"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const observer = lozad('.lozad', {
        loaded: function(el) {
            el.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 500, fill: 'forwards' });
        }
    });
    observer.observe();

    $('.switch-btn').on('click', function() {
        $('.switch-btn').removeClass('active');
        $(this).addClass('active');
        $('.directory-section').removeClass('active');
        $(`#section-${$(this).data('target')}`).addClass('active');
        observer.observe();
        window.scrollTo({ top: $('.hero-organized').height() + 20, behavior: 'smooth' });
    });

    $('.l-filter-btn').on('click', function() {
        const groupId = $(this).data('group');
        $('.l-filter-btn').removeClass('active');
        $(this).addClass('active');
        if (groupId === 'all') {
            $('.teacher-group-block').show();
        } else {
            $('.teacher-group-block').hide();
            $(`#group-block-${groupId}`).fadeIn(400);
        }
        observer.observe();
        window.scrollTo({ top: $('.learning-filter-container').offset().top - 100, behavior: 'smooth' });
    });

    $('.s-filter-btn').on('click', function() {
        const posId = $(this).data('position');
        $('.s-filter-btn').removeClass('active');
        $(this).addClass('active');
        if (posId === 'all') {
            $('.staff-position-block').show();
        } else {
            $('.staff-position-block').hide();
            $(`#staff-block-${posId}`).fadeIn(400);
        }
        observer.observe();
        window.scrollTo({ top: $('.staff-filter-container').offset().top - 100, behavior: 'smooth' });
    });
});
</script>
<?= $this->endSection() ?>
