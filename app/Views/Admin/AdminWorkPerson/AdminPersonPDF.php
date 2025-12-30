<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'thsarabun';
            font-size: 15pt;
            line-height: 1.1;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-container {
            width: 100%;
            margin-bottom: 5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        
        .form-type {
            font-size: 14pt;
            text-align: right;
        }
        .form-title {
            font-size: 20pt;
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
        }
        
        .id-card-box {
            text-align: right;
            margin-bottom: 10px;
        }
        .id-digit {
            display: inline-block;
            width: 15px;
            height: 20px;
            border: 1px solid #000;
            text-align: center;
            line-height: 20px;
            font-size: 12pt;
            margin-left: 1px;
        }

        .main-section {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .photo-area {
            width: 3.5cm;
            height: 4.5cm;
            border: 0.5pt solid #000;
            text-align: center;
            float: right;
            margin-left: 15px;
            margin-bottom: 10px;
        }
        .photo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-text {
            font-size: 12pt;
            padding-top: 1.5cm;
            color: #666;
        }

        .personal-info-table {
            width: 100%;
            border: none;
        }
        .personal-info-table td {
            border: none;
            padding: 3px 0;
            vertical-align: bottom;
        }
        .dotted-line {
            border-bottom: 1pt dotted #000;
            display: inline-block;
            min-width: 50px;
            padding-left: 5px;
        }

        .section-header {
            font-weight: bold;
            font-size: 16pt;
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 1.5pt solid #000;
            padding-bottom: 2px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.data-table th, table.data-table td {
            border: 1pt solid #000;
            padding: 4px;
            font-size: 14pt;
        }
        table.data-table th {
            background-color: #f5f5f5;
            text-align: center;
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box {
            width: 300px;
            float: right;
            text-align: center;
        }

        .clear { clear: both; }
        
        /* Specific adjustments for GP7 Revised */
        .page-number {
            text-align: center;
            font-size: 12pt;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="form-type">แบบ ก.พ. 7</div>
        <div class="form-title">ประวัติข้าราชการ</div>
    </div>

    <div class="id-card-box">
        <span style="font-size: 14pt;">เลขประจำตัวประชาชน </span>
        <?php 
            $id_str = str_replace('-', '', $p->pers_id_card ?? '');
            $chars = str_split(str_pad($id_str, 13, ' ', STR_PAD_RIGHT));
            foreach($chars as $i => $char): 
        ?>
            <span class="id-digit"><?= trim($char) ?: '&nbsp;' ?></span>
            <?php if(in_array($i, [0, 4, 9, 11])): ?> <span style="margin: 0 2px;"></span> <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="main-section">
        <div class="photo-area">
            <?php if (!empty($p->pers_img)): ?>
                <?php 
                $imgPath = FCPATH . 'uploads/admin/Personnal/' . $p->pers_img;
                if(file_exists($imgPath)): ?>
                    <img src="<?= $imgPath ?>">
                <?php else: ?>
                    <div class="photo-text">ติดรูปถ่าย<br>1 นิ้ว</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="photo-text">ติดรูปถ่าย<br>1 นิ้ว</div>
            <?php endif; ?>
        </div>

        <table class="personal-info-table">
            <tr>
                <td width="10%">ชื่อ <span class="dotted-line" style="min-width: 250px;"><?= $p->pers_firstname ?></span></td>
                <td width="15%">นามสกุล <span class="dotted-line" style="min-width: 250px;"><?= $p->pers_lastname ?></span></td>
            </tr>
        </table>
        <table class="personal-info-table">
            <tr>
                <td width="40%">วัน เดือน ปีเกิด <span class="dotted-line" style="min-width: 150px;"><?= $p->pers_britday_display ?></span></td>
                <td width="30%">สัญชาติ <span class="dotted-line" style="min-width: 80px;"><?= $p->pers_nationality ?: '-' ?></span></td>
                <td width="30%">เชื้อชาติ <span class="dotted-line" style="min-width: 80px;"><?= $p->pers_race ?: '-' ?></span></td>
            </tr>
        </table>
        <table class="personal-info-table">
            <tr>
                <td width="30%">ศาสนา <span class="dotted-line" style="min-width: 100px;"><?= $p->pers_religion ?: '-' ?></span></td>
                <td width="30%">หมู่เลือด <span class="dotted-line" style="min-width: 50px;"><?= $p->pers_blood_type ?: '-' ?></span></td>
                <td width="40%">โทรศัพท์ <span class="dotted-line" style="min-width: 150px;"><?= $p->pers_phone ?: '-' ?></span></td>
            </tr>
        </table>
        
        <div style="margin-top: 10px;">
            ที่อยู่ตามทะเบียนบ้าน <span class="dotted-line" style="min-width: 90%;">
                <?php if ($reg = $p->addr_reg): ?>
                    <?= $reg->addr_house_no ?> หมู่ <?= $reg->addr_moo ?> 
                    ต.<?= $reg->addr_subdistrict ?> อ.<?= $reg->addr_district ?> 
                    จ.<?= $reg->addr_province ?> <?= $reg->addr_postcode ?>
                <?php else: ?> - <?php endif; ?>
            </span>
        </div>
        <div style="margin-top: 5px;">
            ที่อยู่ปัจจุบัน <span class="dotted-line" style="min-width: 94%;">
                <?php if ($curr = $p->addr_curr): ?>
                    <?= $curr->addr_house_no ?> หมู่ <?= $curr->addr_moo ?> 
                    ต.<?= $curr->addr_subdistrict ?> อ.<?= $curr->addr_district ?> 
                    จ.<?= $curr->addr_province ?> <?= $curr->addr_postcode ?>
                <?php else: ?> - <?php endif; ?>
            </span>
        </div>
    </div>
    <div class="clear"></div>

    <div class="section-header">1. ประวัติการศึกษา</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">ปีที่สำเร็จ</th>
                <th width="35%">วุฒิที่ได้รับ</th>
                <th width="25%">วิชาเอก/โท</th>
                <th width="28%">สถานศึกษา/แหล่งฝึกอบรม</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($p->education)): ?>
                <?php foreach($p->education as $edu): ?>
                <tr>
                    <td class="text-center"><?= $edu->edu_year ?></td>
                    <td><?= $edu->edu_degree ?></td>
                    <td><?= $edu->edu_major ?></td>
                    <td><?= $edu->edu_institute ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">ไม่มีข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-header">2. ประวัติการรับราชการ (ตำแหน่งและอัตราเงินเดือน)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%" rowspan="2">วัน เดือน ปี</th>
                <th width="35%" rowspan="2">ตำแหน่ง/ระดับ/สังกัด</th>
                <th width="15%" rowspan="2">อัตราเงินเดือน</th>
                <th colspan="2">เอกสารอ้างอิง</th>
            </tr>
            <tr>
                <th width="15%">เลขที่คำสั่ง</th>
                <th width="20%">ลงวันที่</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($p->work_history)): ?>
                <?php foreach($p->work_history as $work): ?>
                <tr>
                    <td class="text-center"><?= $work->date_display ?></td>
                    <td>
                        <div class="bold"><?= $work->work_position ?></div>
                        <div>ระดับ: <?= $work->work_level ?></div>
                        <div style="font-size: 12pt; color: #444;"><?= $work->work_location ?></div>
                        <?php if(!empty($work->work_change_type)): ?>
                            <div style="font-size: 11pt; font-style: italic;">(<?= $work->work_change_type ?>)</div>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?= number_format($work->work_salary, 2) ?></td>
                    <td class="text-center"><?= $work->work_command_no ?></td>
                    <td class="text-center"><?= $work->command_date_display ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">ไม่มีข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-header">3. เครื่องราชอิสริยาภรณ์</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">ปี (พ.ศ.)</th>
                <th width="30%">ชั้นตรา</th>
                <th width="40%">ราชกิจจานุเบกษา (เล่ม/ตอน/หน้า/ลำดับ)</th>
                <th width="18%">ลงวันที่</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($p->decorations)): ?>
                <?php foreach($p->decorations as $deco): ?>
                <tr>
                    <td class="text-center"><?= date('Y', strtotime($deco->deco_date)) + 543 ?></td>
                    <td><?= $deco->deco_name ?></td>
                    <td>เล่ม <?= $deco->deco_gazette_vol ?> ตอน <?= $deco->deco_gazette_part ?> หน้า <?= $deco->deco_gazette_page ?> (ลำดับ <?= $deco->deco_gazette_seq ?>)</td>
                    <td class="text-center"><?= $deco->date_display ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">ไม่มีข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-header">4. ประวัติการฝึกอบรม/ศึกษาดูงาน</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">ช่วงเวลา</th>
                <th width="40%">หลักสูตร/หัวข้อโครงการ</th>
                <th width="30%">สถานที่/หน่วยงานจัด</th>
                <th width="10%">ชม.</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($p->training)): ?>
                <?php foreach($p->training as $train): ?>
                <tr>
                    <td class="text-center"><?= $train->start_display ?> - <?= $train->end_display ?></td>
                    <td><?= $train->train_name ?></td>
                    <td><?= $train->train_location ?></td>
                    <td class="text-center"><?= $train->train_hours ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">ไม่มีข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-header">5. ประวัติการลา</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">ปีการศึกษา</th>
                <th width="25%">ประเภทการลา</th>
                <th width="35%">ช่วงเวลา</th>
                <th width="10%">จำนวนวัน</th>
                <th width="15%">หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($p->leave_history)): ?>
                <?php foreach($p->leave_history as $leave): ?>
                <tr>
                    <td class="text-center"><?= explode('/', $leave->start_display)[2] ?? '-' ?></td>
                    <td><?= $leave->leave_type ?></td>
                    <td class="text-center"><?= $leave->start_display ?> - <?= $leave->end_display ?></td>
                    <td class="text-center"><?= $leave->leave_days ?></td>
                    <td><?= $leave->leave_note ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">ไม่มีข้อมูล</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>รับรองความถูกต้อง</p>
            <br>
            <p>(ลงชื่อ)............................................................</p>
            <p>( <?= $p->fullname ?> )</p>
            <p>เจ้าของประวัติ/ผู้ตรวจสอบ</p>
        </div>
    </div>

    <div class="page-number">หน้า 1</div>
</body>
</html>
