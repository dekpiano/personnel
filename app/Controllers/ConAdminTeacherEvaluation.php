<?php

namespace App\Controllers;

use App\Models\TeacherEvaluationModel;

class ConAdminTeacherEvaluation extends BaseController
{
    public function __construct()
    {
        $session = session();
        if (!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "ผู้บริหาร"])) {
            header("Location:" . base_url());
            exit();
        }
    }

    private function DataMain()
    {
        $data['uri'] = service('uri');
        $data['db'] = \Config\Database::connect();
        $data['db_skj'] = \Config\Database::connect('skj');
        return $data;
    }

    public function index()
    {
        $data = $this->DataMain();
        
        // Ensure config table exists
        $data['db']->query("CREATE TABLE IF NOT EXISTS tb_teacher_evaluation_config (
            conf_id INT AUTO_INCREMENT PRIMARY KEY,
            conf_year VARCHAR(4) NOT NULL,
            conf_round INT(1) NOT NULL,
            conf_status TINYINT(1) DEFAULT 0,
            conf_start_date DATE DEFAULT NULL,
            conf_end_date DATE DEFAULT NULL,
            UNIQUE KEY (conf_year, conf_round)
        )");

        // Check and add columns if they don't exist (Fix for existing tables)
        if (!$data['db']->fieldExists('conf_start_date', 'tb_teacher_evaluation_config')) {
            $data['db']->query("ALTER TABLE tb_teacher_evaluation_config ADD COLUMN conf_start_date DATE DEFAULT NULL AFTER conf_status");
        }
        if (!$data['db']->fieldExists('conf_end_date', 'tb_teacher_evaluation_config')) {
            $data['db']->query("ALTER TABLE tb_teacher_evaluation_config ADD COLUMN conf_end_date DATE DEFAULT NULL AFTER conf_start_date");
        }

        $data['title'] = "ติดตามการส่งผลการปฏิบัติงาน (เลื่อนเงินเดือน)";
        
        $year = $this->request->getGet('year') ?? date('Y') + 543;
        $round = $this->request->getGet('round') ?? '1';

        // ดึงรายการปีที่มีข้อมูลในระบบ
        $years_in_db = $data['db']->table('tb_teacher_evaluation')
            ->select('eva_year')
            ->distinct()
            ->orderBy('eva_year', 'DESC')
            ->get()->getResultArray();
        
        $data['years'] = array_column($years_in_db, 'eva_year');
        
        // ดึง URL จาก .env
        $data['upload_url'] = env('upload.server.baseurl.evaluation');
        
        // ถ้าไม่มีข้อมูลเลย ให้ใช้ปีปัจจุบันเป็นพื้นฐาน
        if (empty($data['years'])) {
            $data['years'] = [date('Y') + 543];
        }

        // ตรวจสอบว่าปีที่ส่งมาทาง GET อยู่ในรายการหรือไม่ ถ้าไม่อยู่ให้เลือกปีล่าสุดที่มี
        if (!in_array($year, $data['years'])) {
            $year = $data['years'][0];
        }
        
        $data['sel_year'] = $year;
        $data['sel_round'] = $round;

        // ดึงสถานะการเปิด/ปิด
        $config = $data['db']->table('tb_teacher_evaluation_config')
            ->where('conf_year', $year)
            ->where('conf_round', $round)
            ->get()->getRowArray();
        
        $data['is_open'] = $config ? $config['conf_status'] : 0;
        $data['conf_start_date'] = $config ? $config['conf_start_date'] : '';
        $data['conf_end_date'] = $config ? $config['conf_end_date'] : '';

        // Check if currently open based on dates
        $today = date('Y-m-d');
        $data['is_active_by_date'] = false;
        if ($data['is_open'] && $data['conf_start_date'] && $data['conf_end_date']) {
            if ($today >= $data['conf_start_date'] && $today <= $data['conf_end_date']) {
                $data['is_active_by_date'] = true;
            }
        } elseif ($data['is_open']) {
            // If no dates set but status is open, we assume it's open
            $data['is_active_by_date'] = true;
        }

        // ดึงข้อมูลตำแหน่งที่ต้องรับการประเมิน (ครู)
        $posi_ids = $data['db_skj']->table('tb_position')
            ->whereIn('posi_name', ['ครู', 'ครูผู้ช่วย', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา'])
            ->get()->getResultArray();
        $target_posi = array_column($posi_ids, 'posi_id');

        // ดึงข้อมูลครูทั้งหมด
        $builder = $data['db']->table('tb_personnel p');
        $builder->select('p.pers_id, p.pers_prefix, p.pers_firstname, p.pers_lastname, pos.posi_name, ev.eva_status, ev.eva_file, ev.eva_canva_link, ev.eva_updated_at');
        $builder->join('skjacth_skj.tb_position pos', 'p.pers_position = pos.posi_id', 'left');
        
        // Join กับตารางส่งงาน โดยกรองปีและรอบ
        $builder->join('tb_teacher_evaluation ev', "p.pers_id = ev.eva_teacher_id AND ev.eva_year = '{$year}' AND ev.eva_round = '{$round}'", 'left');
        
        $builder->where('p.pers_status', 'กำลังใช้งาน');
        if (!empty($target_posi)) {
            $builder->whereIn('p.pers_position', $target_posi);
        }
        
        $builder->orderBy('pos.posi_id', 'ASC');
        $builder->orderBy('p.pers_firstname', 'ASC');
        
        $data['teachers'] = $builder->get()->getResultArray();

        // สรุปยอด
        $data['stats'] = [
            'total' => count($data['teachers']),
            'sent' => 0,
            'pending' => 0
        ];

        foreach ($data['teachers'] as $t) {
            if ($t['eva_status'] == 'ส่งแล้ว') {
                $data['stats']['sent']++;
            } else {
                $data['stats']['pending']++;
            }
        }

        return view('Admin/AdminTeacherEvaluation/index', $data);
    }

    public function saveConfig()
    {
        $db = \Config\Database::connect();
        $year = $this->request->getPost('conf_year');
        $round = $this->request->getPost('conf_round');
        $status = $this->request->getPost('conf_status');
        $start_date = $this->request->getPost('conf_start_date');
        $end_date = $this->request->getPost('conf_end_date');

        $data = [
            'conf_year' => $year,
            'conf_round' => $round,
            'conf_status' => $status,
            'conf_start_date' => !empty($start_date) ? $start_date : null,
            'conf_end_date' => !empty($end_date) ? $end_date : null
        ];

        $db->table('tb_teacher_evaluation_config')->replace($data);

        return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกการตั้งค่าเรียบร้อยแล้ว']);
    }
}
