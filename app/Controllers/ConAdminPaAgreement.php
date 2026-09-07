<?php

namespace App\Controllers;

class ConAdminPaAgreement extends BaseController
{
    protected $db;
    protected $db_skj;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        if (!$this->session->get('username') || !in_array($this->session->get('status'), ["superadmin", "admin", "manager", "ผู้ดูแลระบบ"])) {
            header("Location:" . base_url());
            exit();
        }

        $this->db = \Config\Database::connect(); // skjacth_personnel
        $this->db_skj = \Config\Database::connect('skj'); // skjacth_skj
    }

    private function DataMain()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri');
        return $data;
    }

    /**
     * Ensure table exists in personnel database
     */
    private function ensureTableExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `tb_teacher_pa_agreement` (
            `pa_id` INT(11) NOT NULL AUTO_INCREMENT,
            `pa_teacher_id` VARCHAR(50) NOT NULL,
            `pa_year` VARCHAR(10) NOT NULL,
            `pa_presentation_link` TEXT NULL,
            `pa_file_presentation` VARCHAR(255) NULL,
            `pa_file_lesson_plan` VARCHAR(255) NULL,
            `pa_file_pa1` VARCHAR(255) NULL,
            `pa_status` VARCHAR(50) NULL DEFAULT 'submitted',
            `pa_comment` TEXT NULL,
            `pa_created_at` DATETIME NULL,
            `pa_updated_at` DATETIME NULL,
            PRIMARY KEY (`pa_id`),
            KEY `idx_teacher_year` (`pa_teacher_id`, `pa_year`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        try {
            $this->db->query($sql);
            // Check and add column pa_file_presentation if not exists
            $cols = $this->db->getFieldNames('tb_teacher_pa_agreement');
            if (!in_array('pa_file_presentation', $cols)) {
                $this->db->query("ALTER TABLE `tb_teacher_pa_agreement` ADD COLUMN `pa_file_presentation` VARCHAR(255) NULL AFTER `pa_presentation_link`");
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating/updating tb_teacher_pa_agreement: ' . $e->getMessage());
        }
    }

    /**
     * Get Current Fiscal Year
     */
    private function getCurrentFiscalYear()
    {
        $month = (int)date('n');
        $year = (int)date('Y') + 543;
        if ($month >= 10) {
            return $year + 1;
        }
        return $year;
    }

    /**
     * Main Index View for PA1 Agreement Management
     */
    public function index()
    {
        $this->ensureTableExists();

        $data = $this->DataMain();
        $data['title'] = "จัดการข้อตกลงในการพัฒนางาน (PA) รายบุคคล";

        // Fiscal Year Filter
        $currentYear = $this->getCurrentFiscalYear();
        $selectedYear = $this->request->getGet('fiscal_year');
        $fiscalYear = !empty($selectedYear) ? (int)$selectedYear : $currentYear;

        // Generate Available Years
        $availableYears = [$currentYear, $currentYear - 1, $currentYear - 2, $currentYear - 3];
        $yearsInDb = $this->db->table('tb_teacher_pa_agreement')
            ->select('pa_year')
            ->distinct()
            ->get()->getResultArray();
        foreach ($yearsInDb as $y) {
            if (!in_array((int)$y['pa_year'], $availableYears) && (int)$y['pa_year'] > 2500) {
                $availableYears[] = (int)$y['pa_year'];
            }
        }
        rsort($availableYears);

        // Fetch Positions and Learning Groups for Filters
        $data['positions'] = $this->db_skj->table('tb_position')->orderBy('posi_id', 'ASC')->get()->getResultArray();
        $data['learningGroups'] = $this->db_skj->table('tb_learning')->orderBy('lear_id', 'ASC')->get()->getResultArray();

        // Fetch All Government Teachers (excluding ครูช่วยปฏิบัติงาน / ครูช่วยปฏิบัติการสอน / ครูอัตราจ้าง)
        $teacherBuilder = $this->db->table('tb_personnel')
            ->select('tb_personnel.*, ' . $this->db_skj->getDatabase() . '.tb_position.posi_name, ' . $this->db_skj->getDatabase() . '.tb_learning.lear_namethai')
            ->join($this->db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left')
            ->join($this->db_skj->getDatabase() . '.tb_learning', 'tb_learning.lear_id = tb_personnel.pers_learning', 'left')
            ->where('tb_personnel.pers_status', 'กำลังใช้งาน')
            ->groupStart()
                ->whereIn('tb_position.posi_name', [
                    'ครูผู้ช่วย', 'ครู', 'ครูชำนาญการ', 'ครูชำนาญการพิเศษ', 'ครูเชี่ยวชาญ', 'ครูเชี่ยวชาญพิเศษ',
                    'ผู้อำนวยการโรงเรียน', 'รองผู้อำนวยการโรงเรียน', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา'
                ])
                ->orLike('tb_position.posi_name', 'ครู')
                ->orWhere("tb_personnel.pers_position BETWEEN 'posi_003' AND 'posi_006'")
            ->groupEnd()
            ->notLike('tb_position.posi_name', 'ช่วยปฏิบัติงาน')
            ->notLike('tb_position.posi_name', 'ช่วยปฏิบัติการสอน')
            ->notLike('tb_position.posi_name', 'ช่วยสอน')
            ->notLike('tb_position.posi_name', 'ช่วยราชการ')
            ->notLike('tb_position.posi_name', 'อัตราจ้าง')
            ->orderBy('tb_personnel.pers_learning', 'ASC')
            ->orderBy('tb_personnel.pers_firstname', 'ASC');

        $teachers = $teacherBuilder->get()->getResultArray();

        // Fetch PA Agreements for the selected fiscal year
        $paAgreements = $this->db->table('tb_teacher_pa_agreement')
            ->where('pa_year', $fiscalYear)
            ->get()->getResultArray();

        $paMap = [];
        foreach ($paAgreements as $pa) {
            $paMap[$pa['pa_teacher_id']] = $pa;
        }

        // Stats Counter & Grouping by Learning Area
        $totalTeachers = count($teachers);
        $completeCount = 0;   // มีทั้ง PA1, แผน, สื่อครบ
        $partialCount = 0;    // มีบางส่วน
        $uploadedCount = 0;   // มีไฟล์ PA1
        $pendingCount = 0;    // ยังไม่มีไฟล์/งานใดๆ
        $hasPresCount = 0;
        $hasPlanCount = 0;
        $groupedTeachers = [];

        foreach ($teachers as &$t) {
            $t['pa_agreement'] = $paMap[$t['pers_id']] ?? null;
            $hasPa1  = !empty($t['pa_agreement']['pa_file_pa1']);
            $hasPlan = !empty($t['pa_agreement']['pa_file_lesson_plan']);
            $hasPres = !empty($t['pa_agreement']['pa_file_presentation']) || !empty($t['pa_agreement']['pa_presentation_link']);

            $filesCount = ($hasPa1 ? 1 : 0) + ($hasPlan ? 1 : 0) + ($hasPres ? 1 : 0);

            if ($hasPa1) $uploadedCount++;
            if ($hasPres) $hasPresCount++;
            if ($hasPlan) $hasPlanCount++;

            if ($filesCount >= 2 || ($hasPa1 && $hasPlan)) {
                $completeCount++;
            } elseif ($filesCount > 0) {
                $partialCount++;
            } else {
                $pendingCount++;
            }

            $t['has_pa1'] = $hasPa1;
            $t['has_plan'] = $hasPlan;
            $t['has_pres'] = $hasPres;
            $t['submission_status'] = ($filesCount === 0) ? 'none' : ($hasPa1 ? 'complete' : 'partial');

            $learKey = !empty($t['pers_learning']) ? $t['pers_learning'] : 'other';
            $learTitle = !empty($t['lear_namethai']) ? $t['lear_namethai'] : 'ผู้บริหารสถานศึกษา / อื่นๆ';

            if (!isset($groupedTeachers[$learKey])) {
                $groupedTeachers[$learKey] = [
                    'lear_id' => $learKey,
                    'lear_name' => $learTitle,
                    'teachers' => [],
                    'total' => 0,
                    'uploaded' => 0,       // มีไฟล์ PA1
                    'has_pres' => 0,       // มีสื่อนำเสนอ
                    'has_plan' => 0,       // มีแผนการสอน
                    'all_complete' => 0,   // ครบทั้ง 3 รายการ (สื่อ + แผน + PA1)
                    'complete' => 0,       // ส่งงานแล้วอย่างน้อย 1 รายการ
                    'partial' => 0,
                    'pending' => 0         // ยังไม่ส่งงานใดๆ
                ];
            }

            $groupedTeachers[$learKey]['teachers'][] = $t;
            $groupedTeachers[$learKey]['total']++;
            if ($hasPa1) {
                $groupedTeachers[$learKey]['uploaded']++;
            }
            if ($hasPres) {
                $groupedTeachers[$learKey]['has_pres']++;
            }
            if ($hasPlan) {
                $groupedTeachers[$learKey]['has_plan']++;
            }
            if ($hasPa1 && $hasPlan && $hasPres) {
                $groupedTeachers[$learKey]['all_complete']++;
            }

            if ($filesCount > 0) {
                $groupedTeachers[$learKey]['complete']++;
            } else {
                $groupedTeachers[$learKey]['pending']++;
            }
        }

        $data['teachers'] = $teachers;
        $data['grouped_teachers'] = $groupedTeachers;
        $data['fiscal_year'] = $fiscalYear;
        $data['available_years'] = $availableYears;
        $data['total_teachers'] = $totalTeachers;
        $data['uploaded_count'] = $uploadedCount;
        $data['complete_count'] = $completeCount;
        $data['partial_count'] = $partialCount;
        $data['pending_count'] = $pendingCount;
        $data['has_pres_count'] = $hasPresCount;
        $data['has_plan_count'] = $hasPlanCount;
        $data['pa_upload_baseurl'] = env('upload.server.baseurl.pa_agreement') ?: 'https://skj.nsnpao.go.th/uploads/personnel/teacher/pa_agreement/';

        return view('Admin/AdminPaEvaluation/pa_agreement_staff', $data);
    }

    /**
     * Upload File in Chunks to Remote File Server
     */
    public function uploadChunk()
    {
        $file = $this->request->getFile('file');
        $path = $this->request->getPost('path');
        $filename = $this->request->getPost('filename');
        $chunk = (int)$this->request->getPost('chunk');
        $chunks = (int)$this->request->getPost('chunks');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไฟล์ที่อัปโหลดไม่ถูกต้องหรือเสียหาย']);
        }

        try {
            $client = \Config\Services::curlrequest();
            $uploadUrl = env('upload.server.url') ?: 'https://skj.nsnpao.go.th/upload.php';

            $postData = [
                'file'             => new \CURLFile($file->getTempName(), $file->getClientMimeType(), $file->getClientName()),
                'path'             => $path,
                'desired_filename' => $filename,
                'filename'         => $filename,
                'chunk_index'      => (string)$chunk,
                'total_chunks'     => (string)$chunks,
            ];

            $headers = [
                'X-Auth-Token' => env('upload.server.token') ?: 'Dekpiano2025!!'
            ];

            $response = $client->post($uploadUrl, [
                'multipart'   => $postData,
                'headers'     => $headers,
                'http_errors' => false
            ]);

            return $this->response->setContentType('application/json')
                ->setStatusCode($response->getStatusCode())
                ->setBody($response->getBody());
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save PA File in Database
     */
    public function savePaFile()
    {
        $teacherId = $this->request->getPost('teacher_id');
        $year = $this->request->getPost('pa_year');
        $filename = $this->request->getPost('uploaded_pa1_filename');

        if (empty($teacherId) || empty($year) || empty($filename)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน กรุณาลองใหม่อีกครั้ง']);
        }

        $existing = $this->db->table('tb_teacher_pa_agreement')
            ->where('pa_teacher_id', $teacherId)
            ->where('pa_year', $year)
            ->get()->getRowArray();

        if ($existing) {
            $this->db->table('tb_teacher_pa_agreement')
                ->where('pa_id', $existing['pa_id'])
                ->update([
                    'pa_file_pa1'   => $filename,
                    'pa_updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            $this->db->table('tb_teacher_pa_agreement')->insert([
                'pa_teacher_id' => $teacherId,
                'pa_year'       => $year,
                'pa_file_pa1'   => $filename,
                'pa_created_at' => date('Y-m-d H:i:s'),
                'pa_updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'บันทึกไฟล์ข้อตกลง PA ให้คุณครูเรียบร้อยแล้ว'
        ]);
    }

    /**
     * Delete PA File
     */
    public function deletePaFile()
    {
        $paId = $this->request->getPost('pa_id');
        $teacherId = $this->request->getPost('teacher_id');
        $year = $this->request->getPost('pa_year');

        $builder = $this->db->table('tb_teacher_pa_agreement');
        if (!empty($paId)) {
            $builder->where('pa_id', $paId);
        } else if (!empty($teacherId) && !empty($year)) {
            $builder->where('pa_teacher_id', $teacherId)->where('pa_year', $year);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการลบ']);
        }

        $row = $builder->get()->getRowArray();
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลไฟล์ในระบบ']);
        }

        // Optionally send delete request to remote server
        if (!empty($row['pa_file_pa1'])) {
            try {
                $client = \Config\Services::curlrequest();
                $deleteUrl = env('upload.server.delete.url') ?: 'https://skj.nsnpao.go.th/delete.php';
                $targetPath = 'personnel/teacher/pa_agreement/' . $row['pa_year'] . '/pa1';
                
                $client->post($deleteUrl, [
                    'json' => [
                        'path'  => $targetPath,
                        'files' => [$row['pa_file_pa1']]
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-Auth-Token' => env('upload.server.token') ?: 'Dekpiano2025!!'
                    ],
                    'http_errors' => false
                ]);
            } catch (\Exception $e) {
                // Ignore remote delete error to allow db update
            }
        }

        // If no other data, delete record or update pa_file_pa1 to null
        if (empty($row['pa_presentation_link']) && empty($row['pa_file_lesson_plan'])) {
            $this->db->table('tb_teacher_pa_agreement')->where('pa_id', $row['pa_id'])->delete();
        } else {
            $this->db->table('tb_teacher_pa_agreement')->where('pa_id', $row['pa_id'])->update([
                'pa_file_pa1'   => null,
                'pa_updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'ลบไฟล์ข้อตกลง PA เรียบร้อยแล้ว'
        ]);
    }

    /**
     * Clean Orphan/Junk Files from Remote Server (files not in tb_teacher_pa_agreement + chunks)
     */
    public function cleanupOrphanFiles()
    {
        $year = $this->request->getPost('pa_year') ?: $this->getCurrentFiscalYear();

        try {
            $this->ensureTableExists();

            // 1. Get all PA files currently recorded in DB for this year
            $usedFilesRows = $this->db->table('tb_teacher_pa_agreement')
                ->select('pa_file_pa1')
                ->where('pa_year', $year)
                ->where('pa_file_pa1 IS NOT NULL')
                ->where('pa_file_pa1 !=', '')
                ->get()
                ->getResultArray();

            $usedFiles = array_filter(array_column($usedFilesRows, 'pa_file_pa1'));

            $client = \Config\Services::curlrequest();
            $deleteUrl = env('upload.server.delete.url') ?: 'https://skj.nsnpao.go.th/delete.php';
            $token = env('upload.server.token') ?: 'Dekpiano2025!!';
            $targetPath = 'personnel/teacher/pa_agreement/' . $year . '/pa1';

            // 2. Ask remote server to list all existing files in this year's folder
            $scanRes = $client->post($deleteUrl, [
                'json' => [
                    'action' => 'scan_files',
                    'path'   => $targetPath
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Auth-Token' => $token
                ],
                'http_errors' => false
            ]);

            $scanData = json_decode($scanRes->getBody(), true);
            $serverFiles = $scanData['files'] ?? [];

            // 3. Find orphan files (files on server that are NOT in DB)
            $orphanFiles = [];
            foreach ($serverFiles as $sFile) {
                if (!in_array($sFile, $usedFiles)) {
                    $orphanFiles[] = $sFile;
                }
            }

            $deletedCount = 0;
            if (!empty($orphanFiles)) {
                $deleteFilesRes = $client->post($deleteUrl, [
                    'json' => [
                        'path'  => $targetPath,
                        'files' => $orphanFiles
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-Auth-Token' => $token
                    ],
                    'http_errors' => false
                ]);
                $delData = json_decode($deleteFilesRes->getBody(), true);
                $deletedCount = count($delData['deleted'] ?? $orphanFiles);
            }

            // 4. Also clean temporary chunks
            $client->post($deleteUrl, [
                'json' => [
                    'action' => 'clean_chunks',
                    'force'  => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Auth-Token' => $token
                ],
                'http_errors' => false
            ]);

            $msg = $deletedCount > 0 
                ? "ล้างไฟล์ขยะที่ไม่พบในฐานข้อมูลสำเร็จ ({$deletedCount} ไฟล์)" 
                : "ไม่พบไฟล์ขยะตกค้างในระบบ (ข้อมูลตรงกับฐานข้อมูลแล้ว)";

            return $this->response->setJSON([
                'status'        => 'success',
                'deleted_count' => $deletedCount,
                'message'       => $msg
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาดในการล้างไฟล์ขยะ: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export PA Agreement & Submission Report to Excel (.xlsx)
     */
    public function exportExcel($fiscalYear = null)
    {
        $this->ensureTableExists();

        if (empty($fiscalYear)) {
            $fiscalYear = $this->request->getGet('fiscal_year') ?? $this->getCurrentFiscalYear();
        }

        // Fetch teachers using same criteria as index (joining skj database)
        $skjDbName = $this->db_skj->getDatabase();
        $teacherBuilder = $this->db->table('tb_personnel')
            ->select('tb_personnel.*, ' . $skjDbName . '.tb_position.posi_name, ' . $skjDbName . '.tb_learning.lear_namethai')
            ->join($skjDbName . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left')
            ->join($skjDbName . '.tb_learning', 'tb_learning.lear_id = tb_personnel.pers_learning', 'left')
            ->where('tb_personnel.pers_status', 'กำลังใช้งาน')
            ->groupStart()
                ->whereIn($skjDbName . '.tb_position.posi_name', [
                    'ครูผู้ช่วย', 'ครู', 'ครูชำนาญการ', 'ครูชำนาญการพิเศษ', 'ครูเชี่ยวชาญ', 'ครูเชี่ยวชาญพิเศษ',
                    'ผู้อำนวยการโรงเรียน', 'รองผู้อำนวยการโรงเรียน', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา'
                ])
                ->orLike($skjDbName . '.tb_position.posi_name', 'ครู')
                ->orWhere("tb_personnel.pers_position BETWEEN 'posi_003' AND 'posi_006'")
            ->groupEnd()
            ->notLike($skjDbName . '.tb_position.posi_name', 'ช่วยปฏิบัติงาน')
            ->notLike($skjDbName . '.tb_position.posi_name', 'ช่วยปฏิบัติการสอน')
            ->notLike($skjDbName . '.tb_position.posi_name', 'ช่วยสอน')
            ->notLike($skjDbName . '.tb_position.posi_name', 'ช่วยราชการ')
            ->notLike($skjDbName . '.tb_position.posi_name', 'อัตราจ้าง')
            ->orderBy('tb_personnel.pers_learning', 'ASC')
            ->orderBy('tb_personnel.pers_firstname', 'ASC');

        $teachers = $teacherBuilder->get()->getResultArray();

        // Fetch agreements
        $paAgreements = $this->db->table('tb_teacher_pa_agreement')
            ->where('pa_year', $fiscalYear)
            ->get()
            ->getResultArray();

        $paMap = [];
        foreach ($paAgreements as $pa) {
            $paMap[$pa['pa_teacher_id']] = $pa;
        }

        $paUploadBaseUrl = env('upload.server.baseurl.pa_agreement') ?: 'https://skj.nsnpao.go.th/uploads/personnel/teacher/pa_agreement/';

        // Initialize PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('ระบบบริหารงานบุคคล โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์')
            ->setTitle('รายงานสรุปการส่งงานและข้อตกลง PA ปีงบประมาณ พ.ศ. ' . $fiscalYear);

        // --- Sheet 1: รายงานแยกตามรายชื่อครู ---
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('รายชื่อการส่งงานรายบุคคล');

        // Header Title
        $sheet1->mergeCells('A1:J1');
        $sheet1->setCellValue('A1', 'รายงานการส่งงานและข้อตกลงในการพัฒนางาน (PA) ข้าราชการครู');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E40AF'));
        $sheet1->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet1->mergeCells('A2:J2');
        $sheet1->setCellValue('A2', 'ประจำปีงบประมาณ พ.ศ. ' . $fiscalYear . ' (ข้อมูล ณ วันที่ ' . date('d/m/') . (date('Y')+543) . ')');
        $sheet1->getStyle('A2')->getFont()->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet1->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Table Column Headers
        $headers = [
            'A4' => '#',
            'B4' => 'รหัส',
            'C4' => 'ชื่อ - นามสกุล',
            'D4' => 'กลุ่มสาระการเรียนรู้',
            'E4' => 'ตำแหน่ง',
            'F4' => 'วิทยฐานะ',
            'G4' => '1. สื่อนำเสนอ (Canva/PPT)',
            'H4' => '2. แผนการจัดการเรียนรู้',
            'I4' => '3. แบบข้อตกลง PA1',
            'J4' => 'สรุปสถานะความครบถ้วน'
        ];

        foreach ($headers as $cell => $val) {
            $sheet1->setCellValue($cell, $val);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0284C7']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]]
        ];
        $sheet1->getStyle('A4:J4')->applyFromArray($headerStyle);
        $sheet1->getRowDimension(4)->setRowHeight(28);

        $rowNum = 5;
        $idx = 1;
        $totalPres = 0;
        $totalPlan = 0;
        $totalPa1  = 0;
        $totalAllComplete = 0;

        foreach ($teachers as $t) {
            $ag = $paMap[$t['pers_id']] ?? null;
            $hasPresFile = !empty($ag['pa_file_presentation']);
            $hasPresLink = !empty($ag['pa_presentation_link']);
            $hasPres = $hasPresFile || $hasPresLink;
            $hasPlan = !empty($ag['pa_file_lesson_plan']);
            $hasPa1  = !empty($ag['pa_file_pa1']);

            if ($hasPres) $totalPres++;
            if ($hasPlan) $totalPlan++;
            if ($hasPa1)  $totalPa1++;
            if ($hasPres && $hasPlan && $hasPa1) $totalAllComplete++;

            $fullName = trim(($t['pers_prefix'] ?? '') . $t['pers_firstname'] . ' ' . $t['pers_lastname']);
            $learName = $t['lear_namethai'] ?? 'ผู้บริหาร/อื่นๆ';
            $posiName = $t['posi_name'] ?? 'ครู';
            $academic = empty($t['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $t['pers_academic'];

            // Status text (ส่งแล้ว / ยังไม่ส่ง)
            $presText = $hasPres ? 'ส่งแล้ว' : 'ยังไม่ส่ง';
            $planText = $hasPlan ? 'ส่งแล้ว' : 'ยังไม่ส่ง';
            $pa1Text  = $hasPa1  ? 'ส่งแล้ว' : 'ยังไม่ส่ง';

            // Summary Status
            $itemCount = ($hasPres ? 1 : 0) + ($hasPlan ? 1 : 0) + ($hasPa1 ? 1 : 0);
            $statusText = $itemCount === 3 ? 'ครบถ้วน (3/3)' : ($itemCount > 0 ? "ส่งบางส่วน ({$itemCount}/3)" : 'ยังไม่ส่งงาน');

            $sheet1->setCellValue('A' . $rowNum, $idx++);
            $sheet1->setCellValue('B' . $rowNum, $t['pers_id']);
            $sheet1->setCellValue('C' . $rowNum, $fullName);
            $sheet1->setCellValue('D' . $rowNum, $learName);
            $sheet1->setCellValue('E' . $rowNum, $posiName);
            $sheet1->setCellValue('F' . $rowNum, $academic);
            $sheet1->setCellValue('G' . $rowNum, $presText);
            $sheet1->setCellValue('H' . $rowNum, $planText);
            $sheet1->setCellValue('I' . $rowNum, $pa1Text);
            $sheet1->setCellValue('J' . $rowNum, $statusText);

            // Row styles - Align center for codes, position, academic, attachments and summary
            $sheet1->getStyle("A{$rowNum}:B{$rowNum}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle("E{$rowNum}:J{$rowNum}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Highlight attachment columns (G, H, I)
            if ($hasPres) {
                $sheet1->getStyle("G{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('166534'))->setBold(true);
            } else {
                $sheet1->getStyle("G{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'));
            }

            if ($hasPlan) {
                $sheet1->getStyle("H{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('166534'))->setBold(true);
            } else {
                $sheet1->getStyle("H{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'));
            }

            if ($hasPa1) {
                $sheet1->getStyle("I{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('166534'))->setBold(true);
            } else {
                $sheet1->getStyle("I{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'));
            }

            // Highlight summary column (J)
            if ($itemCount === 3) {
                $sheet1->getStyle("J{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('166534'))->setBold(true);
            } elseif ($itemCount === 0) {
                $sheet1->getStyle("J{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'));
            } else {
                $sheet1->getStyle("J{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0284C7'))->setBold(true);
            }

            $sheet1->getStyle("A{$rowNum}:J{$rowNum}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E2E8F0'));

            $rowNum++;
        }

        // Summary Row
        $sheet1->mergeCells("A{$rowNum}:F{$rowNum}");
        $sheet1->setCellValue("A{$rowNum}", "สรุปรวมทั้งหมด (" . count($teachers) . " คน)");
        $sheet1->setCellValue("G{$rowNum}", "ส่ง {$totalPres} คน");
        $sheet1->setCellValue("H{$rowNum}", "ส่ง {$totalPlan} คน");
        $sheet1->setCellValue("I{$rowNum}", "ส่ง {$totalPa1} คน");
        $sheet1->setCellValue("J{$rowNum}", "ครบ 3 อย่าง: {$totalAllComplete} คน");

        $sheet1->getStyle("A{$rowNum}:J{$rowNum}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '0F172A'], 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '94A3B8']]]
        ]);
        $sheet1->getRowDimension($rowNum)->setRowHeight(24);

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Output to browser ---
        $spreadsheet->setActiveSheetIndex(0);
        $filename = 'รายงานการส่งงาน_PA_ปีงบประมาณ_' . $fiscalYear . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}