<?php

namespace App\Controllers;

class ConAdminSaveAttendance extends BaseController
{
    public function __construct(){
        $session = session();
        if(!$session->get('username') && $session->get('status') != "AdminGeneral" && $session->get('status') != "ManagerGeneral"){
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

       
        return view('Admin/AdminLeyout/AdminHeader',$data)
                .view('Admin/AdminLeyout/AdminMenuLeft')
                .view('Admin/AdminSaveAttendance/AdminSaveAttendanceHome')
                .view('Admin/AdminLeyout/AdminFooter');
    }

    public function GetPersonnalData(){
        $data = $this->DataMain();        
        $data['title']="บันทึกการมาทำงาน";        
        $DBPers = $data['database']->table('tb_personnel');
        $DBPosi = $data['databaseSKJ']->table('tb_position');
       // print_r($DBPers->get()->getResult());
       // exit();
        $DBPers->select('pers_id,tb_personnel.pers_prefix,tb_personnel.pers_firstname,tb_personnel.pers_lastname,tb_position.posi_name');
        $DBPers->join('skjacth_skj.tb_position','tb_position.posi_id = tb_personnel.pers_position','left');
        $DBPers->where('tb_personnel.pers_status','กำลังใช้งาน');
        $DBPers->orderBy('tb_personnel.pers_position','ASC');
        $DBPers->orderBy('tb_personnel.pers_learning','ASC');
        $query = $DBPers->get();
        return $this->response->setJSON($query->getResult());
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
                'att_reason' => $remark[$person_id] ?? null
            ];
            // REPLACE หรือ UPSERT (ถ้ามี UNIQUE KEY att_person_id, att_date)
            $DBPers->replace($data);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function DashboardAttendance(){

        $data = $this->DataMain();    
        $DBPersAttendance = $data['database']->table('tb_personnel_attendance a');

        $type = $this->request->getGet('type');    // day/month/year
        $value = $this->request->getGet('value');  // 2025-05-01 หรือ 2025-05 หรือ 2025

        // กำหนดช่วงวันที่
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

        // 1. Query ข้อมูล attendance ตามช่วงวันที่
        $builder = $DBPersAttendance
            ->join('tb_personnel p', 'a.att_person_id = p.pers_id')
            ->select('a.att_date, p.pers_prefix,p.pers_firstname,p.pers_lastname, a.att_status, a.att_reason')
            ->where('a.att_date >=', $start)
            ->where('a.att_date <=', $end)
            ->orderBy('a.att_date', 'asc')
            ->orderBy('p.pers_position', 'asc')
            ->orderBy('p.pers_learning', 'asc');

        $rows = $builder->get()->getResultArray();

        // 2. รวมข้อมูลสำหรับตาราง
        $table = [];
        foreach ($rows as $r) {
            $table[] = [
                'date'   => $r['att_date'],
                'name'   => $r['pers_prefix'] . $r['pers_firstname'] . ' ' . $r['pers_lastname'],
                'status' => $r['att_status'],
                'remark' => $r['att_reason']
            ];
        }

        // 3. คำนวณสถิติ (stats)
        $statCount = [
            'present' => 0,
            'absent' => 0,
            'sick' => 0,
            'official' => 0,
            'personal' => 0, // ลากิจ
            'other' => 0,    // ลาอื่น ๆ
            'total' => 0
        ];
        foreach ($rows as $r) {
            if ($r['att_status'] == 'มา') $statCount['present']++;
            else if ($r['att_status'] == 'ขาด') $statCount['absent']++;
            else if ($r['att_status'] == 'ลาป่วย') $statCount['sick']++;
            else if ($r['att_status'] == 'ไปราชการ') $statCount['official']++;
            else if ($r['att_status'] == 'ลากิจ') $statCount['personal']++;
            else $statCount['other']++; // เช่น "ลาอื่น ๆ" หรือกรณี status ที่ไม่ได้ระบุข้างบน
            $statCount['total']++;
        }
        // คิด % (กันหาร 0)
        $stats = [
            'present' => $statCount['present'],
            'absent' => $statCount['absent'],
            'sick' => $statCount['sick'],
            'official' => $statCount['official'],
            'personal' => $statCount['personal'],
            'other' => $statCount['other'],
            'present_percent' => $statCount['total'] ? round($statCount['present']*100/$statCount['total'], 1) : 0,
            'absent_percent' => $statCount['total'] ? round($statCount['absent']*100/$statCount['total'], 1) : 0,
            'sick_percent' => $statCount['total'] ? round($statCount['sick']*100/$statCount['total'], 1) : 0,
            'official_percent' => $statCount['total'] ? round($statCount['official']*100/$statCount['total'], 1) : 0,
            'personal_percent' => $statCount['total'] ? round($statCount['personal']*100/$statCount['total'], 1) : 0,
            'other_percent' => $statCount['total'] ? round($statCount['other']*100/$statCount['total'], 1) : 0,
        ];

        // 4. คืนค่า JSON
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

        $data = $DBPers->select("p.pers_id, p.pers_prefix, p.pers_firstname, p.pers_lastname,posi_name,
                COUNT(a.att_id) as total_days,
                SUM(CASE WHEN a.att_status = 'มา' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN a.att_status = 'สาย' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN a.att_status = 'ขาด' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN a.att_status = 'ลากิจ' THEN 1 ELSE 0 END) as personal_leave,
                SUM(CASE WHEN a.att_status = 'ลาป่วย' THEN 1 ELSE 0 END) as sick_leave,
                SUM(CASE WHEN a.att_status = 'ไปราชการ' THEN 1 ELSE 0 END) as official_leave,
                SUM(CASE WHEN a.att_status = 'อื่นๆ' THEN 1 ELSE 0 END) as other_leave")
            ->join('tb_personnel_attendance a', "p.pers_id = a.att_person_id AND a.att_date BETWEEN '{$start}' AND '{$end}'", 'left')
            ->join('skjacth_skj.tb_position posi', 'p.pers_position = posi.posi_id', 'left')
            ->where('p.pers_status', 'กำลังใช้งาน')
            ->groupBy('p.pers_id')   
            ->orderBy('p.pers_position', 'asc')
            ->orderBy('p.pers_learning', 'asc')                    
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

}