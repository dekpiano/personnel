<?php

namespace App\Controllers;

class ConAdminSaveAttendance extends BaseController
{
    public function __construct(){
        $session = session();
        if(!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "ผู้บริหาร"])){
            header("Location:".base_url()); exit();
        } 
    }


    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $data['uri'] = service('uri'); 
        try {
            $data['database'] = \Config\Database::connect();
        } catch (\Throwable $e) {
            $data['database'] = null;
        }

        try {
            $data['databaseSKJ'] = \Config\Database::connect('skj');
        } catch (\Throwable $e) {
            $data['databaseSKJ'] = null;
        }
        
        return $data;
    }

    public function index()
    {
        $data = $this->DataMain();        
        $data['title']="บันทึกการมาทำงาน";        
        
        // Auto-create/modify columns if not exist
        $columns = $data['database']->getFieldNames('tb_personnel_attendance');
        if (!in_array('att_time_in', $columns)) {
            $data['database']->query("ALTER TABLE tb_personnel_attendance ADD COLUMN att_time_in TIME DEFAULT NULL");
        }
        if (!in_array('att_time_out', $columns)) {
            $data['database']->query("ALTER TABLE tb_personnel_attendance ADD COLUMN att_time_out TIME DEFAULT NULL");
        }

        return view('Admin/AdminSaveAttendance/AdminSaveAttendanceHome', $data);
    }

    public function GetPersonnalData(){
        $data = $this->DataMain();        
        $data['title']="บันทึกการมาทำงาน";        
        $DBPers = $data['database']->table('tb_personnel');
        $DBPosi = $data['databaseSKJ']->table('tb_position');

        // Ensure pers_finger_id column exists
        $columns = $data['database']->getFieldNames('tb_personnel');
        if (!in_array('pers_finger_id', $columns)) {
            $data['database']->query("ALTER TABLE tb_personnel ADD COLUMN pers_finger_id VARCHAR(50) DEFAULT NULL");
        }

        $DBPers->select('pers_id,tb_personnel.pers_prefix,tb_personnel.pers_firstname,tb_personnel.pers_lastname,tb_position.posi_name,tb_personnel.pers_finger_id');
        $DBPers->join('skjacth_skj.tb_position','tb_position.posi_id = tb_personnel.pers_position','left');
        $DBPers->where('tb_personnel.pers_status','กำลังใช้งาน');
        $DBPers->orderBy('tb_personnel.pers_position','ASC');
        $DBPers->orderBy('tb_personnel.pers_learning','ASC');
        $query = $DBPers->get();
        return $this->response->setJSON($query->getResult());
    }

    public function SetupFingerprint()
    {
        $data = $this->DataMain();        
        $data['title'] = "ตั้งค่ารหัสเครื่องสแกนนิ้ว";        
        
        // Use GetPersonnalData logic or just let the view call API to get data
        $columns = $data['database']->getFieldNames('tb_personnel');
        if (!in_array('pers_finger_id', $columns)) {
            $data['database']->query("ALTER TABLE tb_personnel ADD COLUMN pers_finger_id VARCHAR(50) DEFAULT NULL");
        }

        return view('Admin/AdminSaveAttendance/AdminSetupFingerprint', $data);
    }

    public function SaveFingerprint()
    {
        $data = $this->DataMain();    
        $DBPers = $data['database']->table('tb_personnel');

        $pers_id = $this->request->getPost('pers_id');
        $pers_finger_id = $this->request->getPost('pers_finger_id');

        if ($DBPers->where('pers_id', $pers_id)->update(['pers_finger_id' => $pers_finger_id])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }
    }

    public function SetupTime()
    {
        $data = $this->DataMain();        
        $data['title'] = "ตั้งค่าเวลามาทำงาน";        
        
        $dbSKJ = $data['databaseSKJ'];
        $columns = $dbSKJ->getFieldNames('tb_position');
        if (!in_array('late_time', $columns)) {
            $dbSKJ->query("ALTER TABLE tb_position ADD COLUMN late_time TIME DEFAULT '08:00:00'");
        }

        return view('Admin/AdminSaveAttendance/AdminSetupTime', $data);
    }

    public function SetupLocation()
    {
        $data = $this->DataMain();        
        $data['title'] = "ตั้งค่าพิกัดและเวลาเช็คชื่อ";        
        
        $db = $data['database'];
        
        // Auto-create table if not exists
        $db->query("CREATE TABLE IF NOT EXISTS tb_attendance_location (
            loc_id INT(11) AUTO_INCREMENT PRIMARY KEY,
            loc_name VARCHAR(255) DEFAULT 'โรงเรียน',
            lat DECIMAL(10, 7) DEFAULT 15.7060,
            lng DECIMAL(10, 7) DEFAULT 100.1280,
            radius_m INT(11) DEFAULT 200,
            check_in_start TIME DEFAULT '06:00:00',
            check_in_end TIME DEFAULT '08:00:00',
            check_out_start TIME DEFAULT '16:00:00',
            check_out_end TIME DEFAULT '18:30:00',
            is_active TINYINT(1) DEFAULT 1
        )");

        // Make sure column exists if table was already created
        $columns = $db->getFieldNames('tb_attendance_location');
        if (!in_array('is_active', $columns)) {
            $db->query("ALTER TABLE tb_attendance_location ADD COLUMN is_active TINYINT(1) DEFAULT 1");
        }

        // Check if row 1 exists, if not insert default
        $exists = $db->table('tb_attendance_location')->where('loc_id', 1)->get()->getRow();
        if (!$exists) {
            $db->table('tb_attendance_location')->insert([
                'loc_id' => 1,
                'loc_name' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
                'lat' => 15.7060416,
                'lng' => 100.1280556,
                'radius_m' => 200,
                'check_in_start' => '06:00:00',
                'check_in_end' => '08:00:00',
                'check_out_start' => '16:00:00',
                'check_out_end' => '18:30:00',
                'is_active' => 1
            ]);
        }

        $data['location'] = $db->table('tb_attendance_location')->where('loc_id', 1)->get()->getRow();

        return view('Admin/AdminSaveAttendance/AdminSetupLocation', $data);
    }

    public function SaveLocationConfig()
    {
        $data = $this->DataMain();
        $db = $data['database'];

        $updateData = [
            'lat'             => $this->request->getPost('lat'),
            'lng'             => $this->request->getPost('lng'),
            'radius_m'        => $this->request->getPost('radius_m'),
            'check_in_start'  => $this->request->getPost('check_in_start'),
            'check_in_end'    => $this->request->getPost('check_in_end'),
            'check_out_start' => $this->request->getPost('check_out_start'),
            'check_out_end'   => $this->request->getPost('check_out_end'),
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($db->table('tb_attendance_location')->where('loc_id', 1)->update($updateData)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกตั้งค่าพิกัดเช็คชื่อสำเร็จ']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }
    }

    public function ToggleActive()
    {
        $data = $this->DataMain();
        $db = $data['database'];
        
        $is_active = $this->request->getPost('is_active') ? 1 : 0;
        
        if ($db->table('tb_attendance_location')->where('loc_id', 1)->update(['is_active' => $is_active])) {
            return $this->response->setJSON(['status' => 'success', 'is_active' => $is_active]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error']);
        }
    }

    public function OnlineHistory()
    {
        $data = $this->DataMain();
        $data['title'] = "รายละเอียดการเช็คชื่อ (SKJ Check-In)";
        
        $db = $data['database'];
        
        // Auto-create table if not exists (in case it wasn't visited before)
        $db->query("CREATE TABLE IF NOT EXISTS tb_attendance (
            att_id INT(11) AUTO_INCREMENT PRIMARY KEY,
            pers_id VARCHAR(20) NOT NULL,
            att_date DATE NOT NULL,
            check_in TIME DEFAULT NULL,
            check_in_lat DECIMAL(10, 7) DEFAULT NULL,
            check_in_lng DECIMAL(10, 7) DEFAULT NULL,
            check_in_photo TEXT DEFAULT NULL,
            check_out TIME DEFAULT NULL,
            check_out_lat DECIMAL(10, 7) DEFAULT NULL,
            check_out_lng DECIMAL(10, 7) DEFAULT NULL,
            check_out_photo TEXT DEFAULT NULL,
            status VARCHAR(20) DEFAULT 'ปกติ'
        )");

        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $data['selectedDate'] = $date;

        $builder = $db->table('tb_attendance a');
        $builder->select('a.*, p.pers_prefix, p.pers_firstname, p.pers_lastname, pos.posi_name');
        $builder->join('tb_personnel p', 'a.pers_id = p.pers_id', 'left');
        $dbSKJName = $data['databaseSKJ']->getDatabase();
        $builder->join($dbSKJName . '.tb_position pos', 'p.pers_position = pos.posi_id', 'left');
        $builder->where('a.att_date', $date);
        $builder->orderBy('a.check_in', 'ASC');
        
        $data['records'] = $builder->get()->getResultArray();

        return view('Admin/AdminSaveAttendance/AdminOnlineAttendance', $data);
    }

    public function GetTimeConfigs()
    {
        $data = $this->DataMain();    
        $dbSKJ = $data['databaseSKJ']->table('tb_position');

        $columns = $data['databaseSKJ']->getFieldNames('tb_position');
        if (!in_array('late_time', $columns)) {
            $data['databaseSKJ']->query("ALTER TABLE tb_position ADD COLUMN late_time TIME DEFAULT '08:00:00'");
        }

        $query = $dbSKJ->select('posi_id, posi_name, late_time')->orderBy('posi_name', 'ASC')->get();
        return $this->response->setJSON($query->getResult());
    }

    public function SaveTimeConfig()
    {
        $data = $this->DataMain();    
        $dbSKJ = $data['databaseSKJ']->table('tb_position');

        $posi_id = $this->request->getPost('posi_id');
        $late_time = $this->request->getPost('late_time');

        if ($dbSKJ->where('posi_id', $posi_id)->update(['late_time' => $late_time])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกเวลาที่สายสำเร็จ']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }
    }

    public function GetAttendanceToDate()
    { 
        $data = $this->DataMain();    
        $DBPers = $data['database']->table('tb_personnel_attendance');
        $date = $this->request->getGet('date');
        $data = $DBPers->where('att_date', $date)
            ->select('att_person_id as person_id, att_status as status, att_reason as remark, att_time_in as time_in, att_time_out as time_out')
            ->get()->getResultArray();
        return $this->response->setJSON($data);
    }

    public function SaveAttendanceToDB()
    {
        $data = $this->DataMain();    
        $DBPers = $data['database']->table('tb_personnel_attendance');

        $date = $this->request->getPost('att_date');
        $status = $this->request->getPost('status');
        $remark = $this->request->getPost('remark');
        $time_in = $this->request->getPost('time_in');
        $time_out = $this->request->getPost('time_out');

        foreach ($status as $person_id => $val) {
            $t_in = !empty($time_in[$person_id]) ? str_replace('.', ':', trim($time_in[$person_id])) : null;
            $t_out = !empty($time_out[$person_id]) ? str_replace('.', ':', trim($time_out[$person_id])) : null;

            if ($t_in && strlen($t_in) <= 5) $t_in .= ':00';
            if ($t_out && strlen($t_out) <= 5) $t_out .= ':00';

            $data = [
                'att_person_id' => $person_id,
                'att_date' => $date,
                'att_status' => $val,
                'att_reason' => $remark[$person_id] ?? null,
                'att_time_in' => $t_in,
                'att_time_out' => $t_out,
                'att_adminid' => session()->get('id')
            ];
            $DBPers->replace($data);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function DashboardAttendance(){

        $data = $this->DataMain();    
        $DBPersAttendance = $data['database']->table('tb_personnel_attendance a');

        $type = $this->request->getGet('type');
        $value = $this->request->getGet('value');

        if ($type === 'day') {
            $start = $end = $value;
        } elseif ($type === 'month') {
            $start = date('Y-m-01', strtotime($value));
            $end = date('Y-m-t', strtotime($value));
        } elseif ($type === 'year') {
            $start = $value . '-01-01';
            $end = $value . '-12-31';
        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid type']);
        }

        $builder = $DBPersAttendance
            ->join('tb_personnel p', 'a.att_person_id = p.pers_id')
            ->select('a.att_date, p.pers_prefix,p.pers_firstname,p.pers_lastname, a.att_status, a.att_reason')
            ->where('a.att_date >=', $start)
            ->where('a.att_date <=', $end)
            ->orderBy('a.att_date', 'asc')
            ->orderBy('p.pers_position', 'asc')
            ->orderBy('p.pers_learning', 'asc');

        $rows = $builder->get()->getResultArray();

        $table = [];
        foreach ($rows as $r) {
            $table[] = [
                'date'   => $r['att_date'],
                'name'   => $r['pers_prefix'] . $r['pers_firstname'] . ' ' . $r['pers_lastname'],
                'status' => $r['att_status'],
                'remark' => $r['att_reason']
            ];
        }

        $statCount = [
            'present' => 0,
            'absent' => 0,
            'sick' => 0,
            'official' => 0,
            'personal' => 0,
            'other' => 0, 
            'late' => 0,
            'total' => 0
        ];
        foreach ($rows as $r) {
            if ($r['att_status'] == 'มา') $statCount['present']++;
            else if ($r['att_status'] == 'ขาด') $statCount['absent']++;
            else if ($r['att_status'] == 'ลาป่วย') $statCount['sick']++;
            else if ($r['att_status'] == 'ไปราชการ') $statCount['official']++;
            else if ($r['att_status'] == 'ลากิจ') $statCount['personal']++;
            else if ($r['att_status'] == 'อื่นๆ') $statCount['other']++;
           else if ($r['att_status'] == 'สาย') $statCount['late']++;
            $statCount['total']++;
        }

        $stats = [
            'present' => $statCount['present'],
            'absent' => $statCount['absent'],
            'sick' => $statCount['sick'],
            'official' => $statCount['official'],
            'personal' => $statCount['personal'],
            'other' => $statCount['other'],
            'late' => $statCount['late'],
            'present_percent' => $statCount['total'] ? round($statCount['present']*100/$statCount['total'], 1) : 0,
            'absent_percent' => $statCount['total'] ? round($statCount['absent']*100/$statCount['total'], 1) : 0,
            'sick_percent' => $statCount['total'] ? round($statCount['sick']*100/$statCount['total'], 1) : 0,
            'official_percent' => $statCount['total'] ? round($statCount['official']*100/$statCount['total'], 1) : 0,
            'personal_percent' => $statCount['total'] ? round($statCount['personal']*100/$statCount['total'], 1) : 0,
            'other_percent' => $statCount['total'] ? round($statCount['other']*100/$statCount['total'], 1) : 0,
            'late_percent' => $statCount['total'] ? round($statCount['late']*100/$statCount['total'], 1) : 0,
        ];

        return $this->response->setJSON([
            'stats' => $stats,
            'table' => $table
        ]);
    }

    public function GetLeaveSummary(){
        $data = $this->DataMain();    
        $DBPers = $data['database']->table('tb_personnel p');
        $DBLeave = $data['database']->table('tb_personnel_attendance');

        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        $dbSKJName = $data['databaseSKJ']->getDatabase();

        $data = $DBPers->select("p.pers_id, p.pers_prefix, p.pers_firstname, p.pers_lastname, posi.posi_name,
                COUNT(a.att_id) as total_days,
                SUM(CASE WHEN a.att_status = 'มา' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN a.att_status = 'สาย' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN a.att_status = 'ขาด' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN a.att_status = 'ลากิจ' THEN 1 ELSE 0 END) as personal_leave,
                SUM(CASE WHEN a.att_status = 'ลาป่วย' THEN 1 ELSE 0 END) as sick_leave,
                SUM(CASE WHEN a.att_status = 'ไปราชการ' THEN 1 ELSE 0 END) as official_leave,
                SUM(CASE WHEN a.att_status = 'อื่นๆ' THEN 1 ELSE 0 END) as other_leave")
            ->join('tb_personnel_attendance a', "p.pers_id = a.att_person_id AND a.att_date BETWEEN '{$start}' AND '{$end}'", 'left')
            ->join($dbSKJName.'.tb_position posi', 'p.pers_position = posi.posi_id', 'left')
            ->where('p.pers_status', 'กำลังใช้งาน')
            ->groupBy('p.pers_id, p.pers_prefix, p.pers_firstname, p.pers_lastname, posi.posi_name')   
            ->orderBy('posi.posi_name', 'asc')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }


    public function leaveSummaryByPositionDay(){
        $data = $this->DataMain();    
        $DBPers = $data['database']->table('tb_personnel p');
        $DBLeave = $data['database']->table('tb_personnel_attendance');
        $DBPosi = $data['databaseSKJ']->table('tb_position');

         $date = $this->request->getGet('date') ?: date('Y-m-d');
  

        $dbSKJName = $data['databaseSKJ']->getDatabase();

        $builder = $DBPers
            ->select(
                'pos.posi_name,
             COUNT(DISTINCT p.pers_id) AS total_person,
             COUNT(DISTINCT CASE WHEN a.att_status IN ("มา", "สาย") AND a.att_date = "'.$date.'" THEN p.pers_id END) AS attend_person,
             SUM(CASE WHEN a.att_status = "ลากิจ" AND a.att_date = "'.$date.'" THEN 1 ELSE 0 END) AS personal_leave,
             SUM(CASE WHEN a.att_status = "ลาป่วย" AND a.att_date = "'.$date.'" THEN 1 ELSE 0 END) AS sick_leave,
             SUM(CASE WHEN a.att_status = "ไปราชการ" AND a.att_date = "'.$date.'" THEN 1 ELSE 0 END) AS official_leave,
             SUM(CASE WHEN a.att_status IN ("อื่นๆ", "ขาด") AND a.att_date = "'.$date.'" THEN 1 ELSE 0 END) AS other_leave'
            )
            ->join($dbSKJName.'.tb_position pos', 'p.pers_position = pos.posi_id', 'left')
            ->join('tb_personnel_attendance a', "p.pers_id = a.att_person_id", 'left')
            ->where('p.pers_status', 'กำลังใช้งาน')
            ->groupBy('pos.posi_name')
            ->orderBy('pos.posi_name', 'asc');

        $query = $builder->get();
        $result = $query->getResultArray();

        return $this->response->setJSON($result);

    }

    public function UploadExcel()
    {
        try {
            $data = $this->DataMain();
            if (!$data['database'] || !$data['databaseSKJ']) {
                throw new \RuntimeException('ไม่สามารถเชื่อมต่อฐานข้อมูลได้ (ตรวจสอบ Config/Database.php)');
            }

            $DBPers = $data['database']->table('tb_personnel');
            
            $file = $this->request->getFile('excel_file');
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์']);
            }

            $att_date = $this->request->getPost('att_date');

            // Check if class exists to avoid fatal error
            if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                throw new \RuntimeException('Library PhpSpreadsheet ไม่ได้ถูกติดตั้งบน Server (ตรวจสอบโฟลเดอร์ vendor)');
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            
            // Optimization: Fetch all personnel with finger_id once
            try {
                $dbSKJ_name = $data['databaseSKJ']->getDatabase();
            } catch (\Throwable $e) {
                throw new \RuntimeException('ไม่สามารถเข้าถึงฐานข้อมูล SKJ ได้: ' . $e->getMessage());
            }
            $allPersonnel = $DBPers->select('tb_personnel.pers_id, tb_personnel.pers_finger_id, COALESCE('.$dbSKJ_name.'.tb_position.late_time, "08:00:00") as late_time')
                                ->join($dbSKJ_name.'.tb_position', $dbSKJ_name.'.tb_position.posi_id = tb_personnel.pers_position', 'left')
                                ->where('tb_personnel.pers_finger_id IS NOT NULL')
                                ->get()->getResultArray();
            $personnelMap = [];
            foreach ($allPersonnel as $p) {
                $finger_id = trim($p['pers_finger_id']);
                $personnelMap[$finger_id] = $p;
                // Add padded/int variations for better matching
                $personnelMap[(string)(int)$finger_id] = $p;
                $personnelMap[str_pad($finger_id, 5, "0", STR_PAD_LEFT)] = $p;
            }

            $headerSkipped = false;
            $parsedData = [];
            $debugRows = [];

            foreach ($rows as $index => $row) {
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }

                $finger_id = trim($row['A'] ?? '');
                $row_date = trim($row['C'] ?? '');
                $time_in_str = trim($row['D'] ?? '');
                $time_out_str = trim($row['E'] ?? '');

                if (empty($finger_id) || empty($row_date) || empty($time_in_str)) {
                    continue;
                }

                // If a selected date was passed, filter by it
                if (!empty($att_date)) {
                    $norm_row_date = date('Y-m-d', strtotime(str_replace('/', '-', $row_date)));
                    $norm_att_date = date('Y-m-d', strtotime($att_date));
                    if ($norm_row_date !== $norm_att_date) {
                        continue;
                    }
                }

                $personnel = $personnelMap[$finger_id] ?? null;
                
                if ($personnel) {
                    // Replace dot with colon if format is 07.55 instead of 07:55
                    $time_in_normalized = str_replace('.', ':', $time_in_str);
                    if (strlen($time_in_normalized) == 4 || strlen($time_in_normalized) == 5) {
                        $time_in_normalized = date('H:i:s', strtotime($time_in_normalized));
                    }

                    $scanTime = strtotime($time_in_normalized);
                    $lateTime = strtotime($personnel['late_time']);

                    $status = ($scanTime > $lateTime) ? 'สาย' : 'ma';
                    if ($status === 'ma') {
                        $status = 'มา';
                    }
                    
                    $time_out_val = '';
                    if (!empty($time_out_str)) {
                        $time_out_normalized = str_replace('.', ':', $time_out_str);
                        $time_out_val = date('H:i', strtotime($time_out_normalized));
                    }

                    $parsedData[$personnel['pers_id']] = [
                        'status' => $status,
                        'remark' => '',
                        'time_in' => date('H:i', $scanTime),
                        'time_out' => $time_out_val
                    ];
                }

                $debugRows[] = [
                    'row' => $index,
                    'finger_id' => $finger_id,
                    'found' => $personnel ? true : false
                ];
            }

            // die('Reached the end of processing');
            return $this->response->setJSON([
                'status' => 'success', 
                'data' => $parsedData,
                'count' => count($parsedData)
            ]);

        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error', 
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine()
            ]);
        }
    }

    public function DownloadTemplate()
    {
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return $this->response->setBody("ไม่พบโมดูล PhpSpreadsheet กรุณาติดต่อผู้พัฒนาระบบ");
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Headers
        $sheet->setCellValue('A1', 'เลขสแกนนิ้ว/รหัสพนักงาน (Finger ID)');
        $sheet->setCellValue('B1', 'ชื่อ - นามสกุล');
        $sheet->setCellValue('C1', 'วันที่บันทึก (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'เวลาสแกนเข้างาน (HH.MM)');
        $sheet->setCellValue('E1', 'เวลาสแกนออกงาน (HH.MM)');

        // Add Sample Data
        $sheet->setCellValue('A2', '120');
        $sheet->setCellValue('B2', 'สมชาย ใจดี');
        $sheet->setCellValue('C2', date('Y-m-d'));
        $sheet->setCellValue('D2', '07.45');
        $sheet->setCellValue('E2', '16.30');

        $sheet->setCellValue('A3', '121');
        $sheet->setCellValue('B3', 'สมศรี รักเรียน');
        $sheet->setCellValue('C3', date('Y-m-d'));
        $sheet->setCellValue('D3', '08.15');
        $sheet->setCellValue('E3', '16.45');

        // Style the headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1e40af'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Auto size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output to response
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="template_attendance.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
