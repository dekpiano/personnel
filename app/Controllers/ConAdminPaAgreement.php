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
        } catch (\Exception $e) {
            log_message('error', 'Error creating tb_teacher_pa_agreement: ' . $e->getMessage());
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
        $uploadedCount = 0;
        $pendingCount = 0;
        $groupedTeachers = [];

        foreach ($teachers as &$t) {
            $t['pa_agreement'] = $paMap[$t['pers_id']] ?? null;
            if (!empty($t['pa_agreement']['pa_file_pa1'])) {
                $uploadedCount++;
            } else {
                $pendingCount++;
            }

            $learKey = !empty($t['pers_learning']) ? $t['pers_learning'] : 'other';
            $learTitle = !empty($t['lear_namethai']) ? $t['lear_namethai'] : 'ผู้บริหารสถานศึกษา / อื่นๆ';

            if (!isset($groupedTeachers[$learKey])) {
                $groupedTeachers[$learKey] = [
                    'lear_id' => $learKey,
                    'lear_name' => $learTitle,
                    'teachers' => [],
                    'total' => 0,
                    'uploaded' => 0,
                    'pending' => 0
                ];
            }

            $groupedTeachers[$learKey]['teachers'][] = $t;
            $groupedTeachers[$learKey]['total']++;
            if (!empty($t['pa_agreement']['pa_file_pa1'])) {
                $groupedTeachers[$learKey]['uploaded']++;
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
        $data['pending_count'] = $pendingCount;

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
}