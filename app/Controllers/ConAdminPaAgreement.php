<?php

namespace App\Controllers;

class ConAdminPaAgreement extends BaseController
{
    protected $db;
    protected $db_skj;
    protected $session;

    public function __construct()
    {
        helper(['url', 'form']);
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
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$host}{$uri}";
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

        $this->ensureConfigTableExists();
    }

    /**
     * Ensure PA Agreement Config table exists in personnel database
     */
    private function ensureConfigTableExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `tb_teacher_pa_agreement_config` (
            `conf_id` INT(11) NOT NULL AUTO_INCREMENT,
            `conf_year` VARCHAR(10) NOT NULL,
            `conf_status` TINYINT(1) NOT NULL DEFAULT 1,
            `conf_start_datetime` DATETIME NULL,
            `conf_end_datetime` DATETIME NULL,
            `conf_note` TEXT NULL,
            `conf_created_at` DATETIME NULL,
            `conf_updated_at` DATETIME NULL,
            PRIMARY KEY (`conf_id`),
            UNIQUE KEY `idx_conf_year` (`conf_year`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        try {
            $this->db->query($sql);
        } catch (\Exception $e) {
            log_message('error', 'Error creating tb_teacher_pa_agreement_config: ' . $e->getMessage());
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
        $request = $this->request ?? service('request');
        $currentYear = $this->getCurrentFiscalYear();
        $selectedYear = $request->getGet('fiscal_year');
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
        $evaYearsInDb = $this->db->table('tb_teacher_evaluation')
            ->select('eva_year')
            ->distinct()
            ->get()->getResultArray();
        foreach ($evaYearsInDb as $y) {
            if (!in_array((int)$y['eva_year'], $availableYears) && (int)$y['eva_year'] > 2500) {
                $availableYears[] = (int)$y['eva_year'];
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

        // Fallback: ดึงจาก tb_teacher_evaluation สำหรับคุณครูที่ยังไม่มีใน tb_teacher_pa_agreement
        try {
            $evaRows = $this->db->table('tb_teacher_evaluation')
                ->groupStart()
                    ->where('eva_year', $fiscalYear)
                    ->orWhere('eva_year', (string)$fiscalYear)
                ->groupEnd()
                ->orderBy('eva_round', 'DESC')
                ->get()->getResultArray();
            foreach ($evaRows as $erow) {
                $tId = $erow['eva_teacher_id'];
                if (!isset($paMap[$tId]) && (!empty($erow['eva_file']) || !empty($erow['eva_canva_link']))) {
                    $paMap[$tId] = [
                        'pa_id' => 'eva_' . $erow['eva_id'],
                        'pa_teacher_id' => $tId,
                        'pa_year' => $erow['eva_year'],
                        'pa_presentation_link' => $erow['eva_canva_link'] ?? '',
                        'pa_file_presentation' => null,
                        'pa_file_lesson_plan' => null,
                        'pa_file_pa1' => $erow['eva_file'] ?? '',
                        'pa_file_pa1_source' => 'evaluation',
                        'pa_round' => $erow['eva_round'] ?? 1,
                        'pa_status' => $erow['eva_status'] ?? 'submitted',
                        'pa_comment' => $erow['eva_comment'] ?? '',
                        'pa_created_at' => $erow['eva_created_at'] ?? null,
                        'pa_updated_at' => $erow['eva_updated_at'] ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {}

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

        // Fetch submission window configuration for this fiscal year
        $paConfig = $this->db->table('tb_teacher_pa_agreement_config')
            ->where('conf_year', (string)$fiscalYear)
            ->get()
            ->getRowArray();

        $now = date('Y-m-d H:i:s');
        $isSubmissionOpen = false;
        $submissionStatusKey = 'no_config'; // 'open', 'not_started', 'expired', 'closed_manually', 'no_config'
        $submissionStatusText = 'ยังไม่กำหนดเวลาส่ง';

        if ($paConfig) {
            $manualOpen = ((int)$paConfig['conf_status'] === 1);
            $hasStart = !empty($paConfig['conf_start_datetime']);
            $hasEnd = !empty($paConfig['conf_end_datetime']);

            if (!$manualOpen) {
                $submissionStatusKey = 'closed_manually';
                $submissionStatusText = 'ปิดระบบชั่วคราว';
            } elseif ($hasStart && $now < $paConfig['conf_start_datetime']) {
                $submissionStatusKey = 'not_started';
                $submissionStatusText = 'ยังไม่ถึงกำหนดเวลาส่ง';
            } elseif ($hasEnd && $now > $paConfig['conf_end_datetime']) {
                $submissionStatusKey = 'expired';
                $submissionStatusText = 'สิ้นสุดกำหนดส่งแล้ว';
            } else {
                $isSubmissionOpen = true;
                $submissionStatusKey = 'open';
                $submissionStatusText = 'ระบบเปิดรับส่งงาน';
            }
        } else {
            $isSubmissionOpen = true;
            $submissionStatusKey = 'open';
            $submissionStatusText = 'ระบบเปิดรับส่งงาน (ค่าเริ่มต้น)';
        }

        $data['pa_config'] = $paConfig;
        $data['is_submission_open'] = $isSubmissionOpen;
        $data['submission_status_key'] = $submissionStatusKey;
        $data['submission_status_text'] = $submissionStatusText;

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
        $data['eva_upload_baseurl'] = env('upload.server.baseurl.evaluation') ?: 'https://skj.nsnpao.go.th/uploads/personnel/teacher/evaluation/';

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
        $filename = $this->request->getPost('uploaded_filename');
        $fileType = $this->request->getPost('file_type'); // pa1, lesson_plan, presentation
        $presentationLink = $this->request->getPost('presentation_link');

        if (empty($teacherId) || empty($year) || empty($fileType)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน กรุณาลองใหม่อีกครั้ง']);
        }

        if ($fileType !== 'presentation' && empty($filename)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาอัปโหลดไฟล์']);
        }
        if ($fileType === 'presentation' && empty($filename) && empty($presentationLink)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาอัปโหลดไฟล์ หรือระบุลิงก์ผลงาน']);
        }

        $column = '';
        if ($fileType === 'pa1') {
            $column = 'pa_file_pa1';
        } elseif ($fileType === 'lesson_plan') {
            $column = 'pa_file_lesson_plan';
        } elseif ($fileType === 'presentation') {
            $column = 'pa_file_presentation';
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ประเภทไฟล์ไม่ถูกต้อง']);
        }

        $existing = $this->db->table('tb_teacher_pa_agreement')
            ->where('pa_teacher_id', $teacherId)
            ->where('pa_year', $year)
            ->get()->getRowArray();

        $updateData = ['pa_updated_at' => date('Y-m-d H:i:s')];
        if (!empty($filename)) {
            $updateData[$column] = $filename;
        }
        if ($fileType === 'presentation' && isset($presentationLink)) {
            $updateData['pa_presentation_link'] = $presentationLink;
        }

        if ($existing) {
            $this->db->table('tb_teacher_pa_agreement')
                ->where('pa_id', $existing['pa_id'])
                ->update($updateData);
        } else {
            $updateData['pa_teacher_id'] = $teacherId;
            $updateData['pa_year'] = $year;
            $updateData['pa_created_at'] = date('Y-m-d H:i:s');
            $this->db->table('tb_teacher_pa_agreement')->insert($updateData);
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
        $fileType = $this->request->getPost('file_type') ?: 'all'; // pa1, lesson_plan, presentation, all

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

        $filesToDelete = [];
        if ($fileType === 'all' || $fileType === 'pa1') {
            if (!empty($row['pa_file_pa1'])) $filesToDelete['pa1'] = $row['pa_file_pa1'];
        }
        if ($fileType === 'all' || $fileType === 'lesson_plan') {
            if (!empty($row['pa_file_lesson_plan'])) $filesToDelete['lesson_plan'] = $row['pa_file_lesson_plan'];
        }
        if ($fileType === 'all' || $fileType === 'presentation') {
            if (!empty($row['pa_file_presentation'])) $filesToDelete['presentation'] = $row['pa_file_presentation'];
        }

        foreach ($filesToDelete as $type => $filename) {
            try {
                $client = \Config\Services::curlrequest();
                $deleteUrl = env('upload.server.delete.url') ?: 'https://skj.nsnpao.go.th/delete.php';
                $targetPath = 'personnel/teacher/pa_agreement/' . $row['pa_year'] . '/' . $type;
                
                $client->post($deleteUrl, [
                    'json' => [
                        'path'  => $targetPath,
                        'files' => [$filename]
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-Auth-Token' => env('upload.server.token') ?: 'Dekpiano2025!!'
                    ],
                    'http_errors' => false
                ]);
            } catch (\Exception $e) {
                // Ignore remote delete error
            }
        }

        if ($fileType === 'all') {
            $this->db->table('tb_teacher_pa_agreement')->where('pa_id', $row['pa_id'])->delete();
        } else {
            $updateData = ['pa_updated_at' => date('Y-m-d H:i:s')];
            if ($fileType === 'pa1') $updateData['pa_file_pa1'] = null;
            if ($fileType === 'lesson_plan') $updateData['pa_file_lesson_plan'] = null;
            if ($fileType === 'presentation') {
                $updateData['pa_file_presentation'] = null;
                $updateData['pa_presentation_link'] = null;
            }
            
            $this->db->table('tb_teacher_pa_agreement')->where('pa_id', $row['pa_id'])->update($updateData);

            // Check if record is empty
            $checkRow = $this->db->table('tb_teacher_pa_agreement')->where('pa_id', $row['pa_id'])->get()->getRowArray();
            if (empty($checkRow['pa_file_pa1']) && empty($checkRow['pa_file_lesson_plan']) && empty($checkRow['pa_file_presentation']) && empty($checkRow['pa_presentation_link'])) {
                $this->db->table('tb_teacher_pa_agreement')->where('pa_id', $row['pa_id'])->delete();
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'ลบไฟล์เรียบร้อยแล้ว'
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

            $folders = [
                'pa1' => 'pa_file_pa1',
                'lesson_plan' => 'pa_file_lesson_plan',
                'presentation' => 'pa_file_presentation'
            ];

            $totalDeletedCount = 0;
            $client = \Config\Services::curlrequest();
            $deleteUrl = env('upload.server.delete.url') ?: 'https://skj.nsnpao.go.th/delete.php';
            $token = env('upload.server.token') ?: 'Dekpiano2025!!';

            foreach ($folders as $folder => $column) {
                // 1. Get all files currently recorded in DB for this year and folder
                $usedFilesRows = $this->db->table('tb_teacher_pa_agreement')
                    ->select($column)
                    ->where('pa_year', $year)
                    ->where($column . ' IS NOT NULL')
                    ->where($column . ' !=', '')
                    ->get()
                    ->getResultArray();

                $usedFiles = array_filter(array_column($usedFilesRows, $column));
                $targetPath = 'personnel/teacher/pa_agreement/' . $year . '/' . $folder;

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

                // 3. Find orphan files
                $orphanFiles = [];
                foreach ($serverFiles as $sFile) {
                    if (!in_array($sFile, $usedFiles)) {
                        $orphanFiles[] = $sFile;
                    }
                }

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
                    $totalDeletedCount += count($delData['deleted'] ?? $orphanFiles);
                }
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

            $msg = $totalDeletedCount > 0 
                ? "ล้างไฟล์ขยะที่ไม่พบในฐานข้อมูลสำเร็จ ({$totalDeletedCount} ไฟล์)" 
                : "ไม่พบไฟล์ขยะตกค้างในระบบ (ข้อมูลตรงกับฐานข้อมูลแล้ว)";

            return $this->response->setJSON([
                'status'        => 'success',
                'deleted_count' => $totalDeletedCount,
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

    /**
     * Parse Thai BE or standard datetime string to MySQL 'Y-m-d H:i:s' format
     */
    private function parseDateTimeInput($str)
    {
        if (empty($str)) {
            return null;
        }
        $str = trim($str);

        // 1. ISO format: 2026-09-01 08:30:00 or 2026-09-01 08:30
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})(?:[ T](\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?$/', $str, $m)) {
            $year = (int)$m[1];
            $month = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($m[3], 2, '0', STR_PAD_LEFT);
            $hour = isset($m[4]) ? str_pad($m[4], 2, '0', STR_PAD_LEFT) : '00';
            $min = isset($m[5]) ? str_pad($m[5], 2, '0', STR_PAD_LEFT) : '00';
            $sec = isset($m[6]) ? str_pad($m[6], 2, '0', STR_PAD_LEFT) : '00';

            if ($year > 2400) {
                $year -= 543;
            }
            return sprintf('%04d-%s-%s %s:%s:%s', $year, $month, $day, $hour, $min, $sec);
        }

        // 2. Thai DMY format: 01/09/2569 เวลา 08:30 น. or 01/09/2569 08:30:00 or 01-09-2569 08:30
        $clean = preg_replace('/เวลา|น\.?|\s+/u', ' ', $str);
        $clean = trim($clean);
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?$/', $clean, $m)) {
            $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            $year = (int)$m[3];
            $hour = isset($m[4]) ? str_pad($m[4], 2, '0', STR_PAD_LEFT) : '00';
            $min = isset($m[5]) ? str_pad($m[5], 2, '0', STR_PAD_LEFT) : '00';
            $sec = isset($m[6]) ? str_pad($m[6], 2, '0', STR_PAD_LEFT) : '00';

            if ($year > 2400) {
                $year -= 543;
            }
            return sprintf('%04d-%s-%s %s:%s:%s', $year, $month, $day, $hour, $min, $sec);
        }

        $ts = strtotime($str);
        if ($ts !== false) {
            return date('Y-m-d H:i:s', $ts);
        }

        return null;
    }

    /**
     * Save / Update Submission Window Configuration for PA Agreement
     */
    public function saveConfig()
    {
        $this->ensureConfigTableExists();
        $request = $this->request ?? service('request');
        $response = $this->response ?? service('response');

        $year = trim($request->getPost('conf_year') ?? '');
        $status = (int)($request->getPost('conf_status') ?? 1);
        $startDatetime = trim($request->getPost('conf_start_datetime') ?? '');
        $endDatetime = trim($request->getPost('conf_end_datetime') ?? '');
        $note = trim($request->getPost('conf_note') ?? '');

        if (empty($year)) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'กรุณาระบุปีงบประมาณ'
            ]);
        }

        // Validate and format datetime using robust multi-format parser
        $cleanStart = $this->parseDateTimeInput($startDatetime);
        $cleanEnd = $this->parseDateTimeInput($endDatetime);

        if (!empty($startDatetime) && $cleanStart === null) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'รูปแบบวัน-เวลาเริ่มต้นไม่ถูกต้อง: ' . esc($startDatetime)
            ]);
        }

        if (!empty($endDatetime) && $cleanEnd === null) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'รูปแบบวัน-เวลาสิ้นสุดไม่ถูกต้อง: ' . esc($endDatetime)
            ]);
        }

        if ($cleanStart && $cleanEnd && $cleanStart > $cleanEnd) {
            return $response->setJSON([
                'status' => 'error',
                'message' => 'วัน-เวลาเริ่มต้นต้องไม่มากกว่าวัน-เวลาสิ้นสุด'
            ]);
        }

        $existing = $this->db->table('tb_teacher_pa_agreement_config')
            ->where('conf_year', $year)
            ->get()
            ->getRowArray();

        $now = date('Y-m-d H:i:s');
        $saveData = [
            'conf_year'           => $year,
            'conf_status'         => $status,
            'conf_start_datetime' => $cleanStart,
            'conf_end_datetime'   => $cleanEnd,
            'conf_note'           => !empty($note) ? $note : null,
            'conf_updated_at'     => $now
        ];

        if ($existing) {
            $this->db->table('tb_teacher_pa_agreement_config')
                ->where('conf_id', $existing['conf_id'])
                ->update($saveData);
        } else {
            $saveData['conf_created_at'] = $now;
            $this->db->table('tb_teacher_pa_agreement_config')->insert($saveData);
        }

        return $response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกการตั้งค่ากำหนดวัน-เวลาส่งงาน PA ประจำปีงบประมาณ พ.ศ. ' . $year . ' เรียบร้อยแล้ว'
        ]);
    }
}