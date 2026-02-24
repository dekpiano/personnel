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
        $data['database'] = \Config\Database::connect();
        $data['databaseSKJ'] = \Config\Database::connect('skj');
        return $data;
    }

    public function index()
    {
        $data = $this->DataMain();        
        $data['title']="บันทึกการมาทำงาน";        
        $DBPers = $data['database']->table('tb_personnel');

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
            ->select('att_person_id as person_id, att_status as status, att_reason as remark')
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


        foreach ($status as $person_id => $val) {
            $data = [
                'att_person_id' => $person_id,
                'att_date' => $date,
                'att_status' => $val,
                'att_reason' => $remark[$person_id] ?? null,
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
        $data = $this->DataMain();
        $DBPers = $data['database']->table('tb_personnel');

        if (defined('SHARED_LIB_PATH')) {
            require_once SHARED_LIB_PATH .DIRECTORY_SEPARATOR. 'spreadsheet'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
        }

        $file = $this->request->getFile('excel_file');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์']);
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            
            $headerSkipped = false;
            $parsedData = [];
            $debugRows = []; // To help debug

            foreach ($rows as $index => $row) {
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }

                $finger_id = trim($row['A'] ?? '');
                $datetime_str = trim($row['E'] ?? '');

                if (empty($finger_id) || empty($datetime_str)) {
                    continue;
                }

                $finger_id_clean = trim($finger_id);
                $finger_id_int = (string)(int)$finger_id_clean;
                $finger_id_padded = str_pad($finger_id_clean, 5, "0", STR_PAD_LEFT);

                // Find user by trying exact, int matched (no leading zeros), and padded (5 digits with leading zeros)
                // Also join tb_position from databaseSKJ to get late_time
                $databaseSKJ_name = clone $data['databaseSKJ'];
                $dbSKJ_name = $databaseSKJ_name->getDatabase();

                $personnel = $DBPers->select('tb_personnel.pers_id, COALESCE('.$dbSKJ_name.'.tb_position.late_time, "08:00:00") as late_time')
                                    ->join($dbSKJ_name.'.tb_position', $dbSKJ_name.'.tb_position.posi_id = tb_personnel.pers_position', 'left')
                                    ->groupStart()
                                        ->where('tb_personnel.pers_finger_id', $finger_id_clean)
                                        ->orWhere('tb_personnel.pers_finger_id', $finger_id_int)
                                        ->orWhere('tb_personnel.pers_finger_id', $finger_id_padded)
                                    ->groupEnd()
                                    ->get()->getRow();
                
                $debugRows[] = [
                    'scan_row' => $index,
                    'excel_finger_id' => $finger_id,
                    'excel_datetime' => $datetime_str,
                    'found_pers_id' => $personnel ? $personnel->pers_id : null,
                    'found_late_time' => $personnel ? $personnel->late_time : null,
                    'searched_for' => [$finger_id_clean, $finger_id_int, $finger_id_padded]
                ];

                if ($personnel) {
                    $dateParts = explode(' ', $datetime_str);
                    $timePortion = $dateParts[1] ?? '00:00';

                    $isLate = false;
                    $scanTime = strtotime($timePortion);
                    // use position's late_time
                    $lateTime = strtotime($personnel->late_time);

                    if ($scanTime > $lateTime) {
                         $isLate = true;
                    }

                    $status = $isLate ? 'สาย' : 'มา';
                    $remark = "สแกนเมื่อ ".$timePortion;

                    $parsedData[$personnel->pers_id] = [
                        'status' => $status,
                        'remark' => $remark
                    ];
                }
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'data' => $parsedData,
                'debug' => $debugRows
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error', 
                'message' => 'เกิดข้อผิดพลาดในการประมวลผลไฟล์ Excel: ' . $e->getMessage()
            ]);
        }
    }
}
