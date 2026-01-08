<?php
/**
 * View: UserDirectoryMain
 * Version: Ultra Premium - Hierarchical Proportion (Leader Focus)
 */

function getAvatarColor($name) {
    $colors = ['bg-label-primary', 'bg-label-info', 'bg-label-success', 'bg-label-warning', 'bg-label-secondary'];
    $index = crc32($name) % count($colors);
    return $colors[$index];
}

function getInitials($name) {
    return mb_substr($name, 0, 1, 'UTF-8');
}

// Group teachers by learning group id
$teachersByGroup = [];
foreach ($AllTeachers ?? [] as $teacher) {
    $teachersByGroup[$teacher->pers_learning][] = $teacher;
}
?>
<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --premium-blue: #007bff;
        --leader-gold: #ffab00;
        --premium-shadow: 0 10px 30px -12px rgba(0, 123, 255, 0.3);
    }

    body {
        font-family: 'Kanit', 'Sarabun', sans-serif;
        background-color: #f5f5f9;
        color: #566a7f;
    }

    /* --- Hero Section --- */
    .hero-organized {
        background: linear-gradient(135deg, #007bff 0%, #005dab 100%);
        border-radius: 32px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px -15px rgba(105, 108, 255, 0.3);
    }

    /* --- Switcher --- */
    .main-switcher-wrap {
        display: flex;
        justify-content: center;
        margin-top: -35px;
        margin-bottom: 2rem;
        position: relative;
        z-index: 10;
    }
    .switcher-container {
        background: white;
        padding: 0.6rem;
        border-radius: 100px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        display: flex;
        border: 1px solid #eceef1;
        gap: 5px;
    }
    .switch-btn {
        border: none;
        background: transparent;
        padding: 0.8rem 2rem;
        border-radius: 100px;
        font-weight: 700;
        color: #697a8d;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .switch-btn.active {
        background: var(--premium-blue);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    /* --- Filter Groups --- */
    .learning-filter-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 3rem;
        padding: 0.5rem;
        background: rgba(255,255,255,0.5);
        border-radius: 50px;
        border: 1px solid #eceef1;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .learning-filter-container::-webkit-scrollbar { display: none; }

    .l-filter-btn {
        border: 1px solid #d9dee3;
        background: white;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #697a8d;
        cursor: pointer;
        white-space: nowrap;
    }
    .l-filter-btn.active { background-color: var(--premium-blue); color: white; border-color: var(--premium-blue); }

    /* --- Sections Control --- */
    .directory-section { display: none; }
    .directory-section.active { display: block; animation: sectionFade 0.5s ease; }
    @keyframes sectionFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* --- Card Styles --- */
    .p-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        border: 1px solid #f1f5f9;
        position: relative;
    }
    .p-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0, 123, 255, 0.15); border-color: var(--premium-blue); }

    /* --- Leader Specific Card --- */
    .leader-card {
        border: 2px solid var(--leader-gold) !important;
        background: radial-gradient(circle at top right, #fffbf0 0%, #ffffff 100%);
        box-shadow: 0 10px 25px rgba(255, 171, 0, 0.15) !important;
    }
    .leader-card:hover { 
        transform: translateY(-10px) scale(1.02); 
        box-shadow: 0 20px 40px rgba(255, 171, 0, 0.25) !important; 
    }
    
    .leader-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ffab00 0%, #ffcf33 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 4px 10px rgba(255, 171, 0, 0.4);
    }

    .p-img-wrap { position: relative; padding-top: 130%; background: #f8fafc; overflow: hidden; }
    .p-img-wrap img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: top; }
    
    /* Slightly bigger font for leaders but same card size */
    .leader-card .p-name { font-size: 1.1rem; color: #b8860b; }

    .p-info { padding: 1.25rem; text-align: center; }
    .p-name { font-weight: 700; color: #32475c; font-size: 0.95rem; line-height: 1.3; }
    .p-role { font-size: 0.8rem; color: #8e94a9; font-weight: 500; margin-top: 5px; }

    /* --- Grid Layout (Fixed 4 Columns) --- */
    .p-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }

    @media (max-width: 1200px) {
        .p-grid { grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    }

    @media (max-width: 992px) {
        .p-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    }

    @media (max-width: 576px) {
        .p-grid { grid-template-columns: repeat(2, 1fr); gap: 0.8rem; }
        .p-name { font-size: 0.85rem; }
    }
</style>

<!-- Hero -->
<div class="hero-organized text-center">
    <h2 class="text-white fw-bold mb-1">ทำเนียบครูและบุคลากร</h2>
    <p class="text-white opacity-75">โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
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
                ?>
                <div class="p-card <?= $isDirector ? 'leader-card leader-span' : '' ?>">
                    <a href="<?= base_url('directory/detail/' . $exec->pers_id) ?>" class="stretched-link"></a>
                    <?php if($isDirector): ?><div class="leader-badge"><i class='bx bxs-star me-1'></i>สูงสุด</div><?php endif; ?>
                    <div class="p-img-wrap">
                        <?php if (!empty($exec->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $exec->pers_img)): ?>
                            <img class="lozad" data-src="<?= base_url('uploads/admin/Personnal/' . $exec->pers_img) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 bg-label-warning text-warning fw-bold fs-1"><?= getInitials($exec->pers_firstname) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="p-info">
                        <div class="p-name"><?= esc($exec->pers_prefix . $exec->pers_firstname . ' ' . $exec->pers_lastname) ?></div>
                        <div class="p-role"><?= esc($exec->posi_name) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section: Teachers -->
    <div id="section-teachers" class="directory-section">
        <div class="learning-filter-container shadow-sm">
            <button class="l-filter-btn active" data-group="all">ดูทั้งหมด</button>
            <?php foreach ($LearningGroups as $group): ?>
                <button class="l-filter-btn" data-group="<?= esc($group->lear_id) ?>"><?= esc($group->lear_namethai) ?></button>
            <?php endforeach; ?>
        </div>

        <div id="teachersGridContainer">
            <?php foreach ($LearningGroups as $group): ?>
                <?php if (isset($teachersByGroup[$group->lear_id])): ?>
                    <div class="teacher-group-block" id="group-block-<?= esc($group->lear_id) ?>" data-group-id="<?= esc($group->lear_id) ?>">
                        <div class="text-center mb-4">
                            <span class="badge bg-label-primary fs-6 px-4 py-2 rounded-pill shadow-sm">กลุ่มสาระการเรียนรู้<?= esc($group->lear_namethai) ?></span>
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
                            <?php $isHead = ($teacher->pers_groupleade === 'หัวหน้ากลุ่มสาระ'); ?>
                            <div class="p-card <?= $isHead ? 'leader-card leader-span' : '' ?>">
                                <a href="<?= base_url('directory/detail/' . $teacher->pers_id) ?>" class="stretched-link"></a>
                                <?php if($isHead): ?><div class="leader-badge"><i class='bx bxs-crown me-1'></i>หัวหน้ากลุ่ม</div><?php endif; ?>
                                <div class="p-img-wrap">
                                    <?php if (!empty($teacher->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $teacher->pers_img)): ?>
                                        <img class="lozad" data-src="<?= base_url('uploads/admin/Personnal/' . $teacher->pers_img) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center h-100 <?= getAvatarColor($teacher->pers_firstname) ?> fw-bold fs-1"><?= getInitials($teacher->pers_firstname) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-info">
                                    <div class="p-name"><?= esc($teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname) ?></div>
                                    <div class="p-role"><?= esc($isHead ? 'หัวหน้ากลุ่มสาระ' : ($teacher->pers_academic ?: 'ครูผู้สอน')) ?></div>
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
        <div class="p-grid">
            <?php foreach ($SupportStaff as $staff): ?>
            <div class="p-card text-center">
                <a href="<?= base_url('directory/detail/' . $staff->pers_id) ?>" class="stretched-link"></a>
                <div class="p-img-wrap">
                    <?php if (!empty($staff->pers_img) && file_exists(FCPATH . 'uploads/admin/Personnal/' . $staff->pers_img)): ?>
                        <img class="lozad" data-src="<?= base_url('uploads/admin/Personnal/' . $staff->pers_img) ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 <?= getAvatarColor($staff->pers_firstname) ?> fw-bold fs-1"><?= getInitials($staff->pers_firstname) ?></div>
                    <?php endif; ?>
                </div>
                <div class="p-info">
                    <div class="p-name"><?= esc($staff->pers_prefix . $staff->pers_firstname . ' ' . $staff->pers_lastname) ?></div>
                    <div class="p-role"><?= esc($staff->posi_name) ?></div>
                </div>
            </div>
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
});
</script>
<?= $this->endSection() ?>
