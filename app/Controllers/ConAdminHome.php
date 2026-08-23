<?php

namespace App\Controllers;

class ConAdminHome extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = session();
        if(!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "manager"])){
            header("Location:".base_url()); exit();
        } 
    }


    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $data['uri'] = service('uri'); 
        return $data;
    }

    public function index()
    {
        helper('thai_date');
        $session = session();
        $data = $this->DataMain();
        $data['title'] = "ศูนย์ควบคุมและบริหารจัดการบุคลากร";
        
        $today = date('Y-m-d');
        $month = (int)date('n');
        $yearAD = (int)date('Y');
        // คำนวณปีงบประมาณไทย (1 ต.ค. - 30 ก.ย.)
        $fiscalYearBE = ($month >= 10) ? ($yearAD + 544) : ($yearAD + 543);
        $data['fiscalYearBE'] = $fiscalYearBE;
        $data['todayDate'] = $today;
        
        $db = \Config\Database::connect();
        $db_pa = null;
        try {
            $db_pa = \Config\Database::connect('pa_evaluation');
        } catch (\Throwable $e) {
            $db_pa = null;
        }

        // 1. จำนวนบุคลากรทั้งหมดที่กำลังใช้งาน
        $data['countAllPersonnel'] = $db->table('tb_personnel')
                                        ->where("pers_status", "กำลังใช้งาน")
                                        ->countAllResults();

        // 2. ข้อมูลการลงเวลาวันนี้
        $attendanceToday = $db->table('tb_personnel_attendance')
                              ->where('att_date', $today)
                              ->get()
                              ->getResultArray();
        
        $data['countAttendanceToday'] = count($attendanceToday);
        
        $onTimeCount = 0;
        $lateCount = 0;
        $leaveAttendanceCount = 0;

        foreach ($attendanceToday as $att) {
            $st = $att['att_status'] ?? '';
            if (mb_strpos($st, 'สาย') !== false) {
                $lateCount++;
            } elseif (mb_strpos($st, 'ลา') !== false) {
                $leaveAttendanceCount++;
            } else {
                $onTimeCount++;
            }
        }

        $data['attOnTime'] = $onTimeCount;
        $data['attLate'] = $lateCount;
        $data['attLeave'] = $leaveAttendanceCount;
        $data['attNotChecked'] = max(0, $data['countAllPersonnel'] - $data['countAttendanceToday']);
        $data['attendanceRate'] = ($data['countAllPersonnel'] > 0) 
            ? round(($data['countAttendanceToday'] / $data['countAllPersonnel']) * 100, 1) 
            : 0;

        // 3. ข้อมูลการลา (Leave Requests)
        $data['countPendingLeave'] = 0;
        $data['recentLeaves'] = [];
        $data['leavesToday'] = [];

        try {
            // คำขอลาที่รอพิจารณา
            $data['countPendingLeave'] = $db->table('tb_leave_requests')
                                           ->where('leave_status', 'pending')
                                           ->countAllResults();

            // รายการคำขอลาล่าสุด 5 รายการ
            $data['recentLeaves'] = $db->table('tb_leave_requests')
                                      ->select('tb_leave_requests.*, tb_leave_types.leave_type_name, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname, tb_personnel.pers_img')
                                      ->join('tb_leave_types', 'tb_leave_types.leave_type_id = tb_leave_requests.leave_type_id', 'left')
                                      ->join('tb_personnel', 'tb_personnel.pers_id = tb_leave_requests.pers_id', 'left')
                                      ->orderBy('tb_leave_requests.created_at', 'DESC')
                                      ->limit(5)
                                      ->get()
                                      ->getResultArray();

            // บุคลากรที่ลาวันนี้ (Approved)
            $data['leavesToday'] = $db->table('tb_leave_requests')
                                      ->select('tb_leave_requests.*, tb_leave_types.leave_type_name, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname, tb_personnel.pers_img')
                                      ->join('tb_leave_types', 'tb_leave_types.leave_type_id = tb_leave_requests.leave_type_id', 'left')
                                      ->join('tb_personnel', 'tb_personnel.pers_id = tb_leave_requests.pers_id', 'left')
                                      ->where('leave_status', 'approved')
                                      ->where('leave_start_date <=', $today)
                                      ->where('leave_end_date >=', $today)
                                      ->get()
                                      ->getResultArray();
        } catch (\Throwable $e) {
            // In case table or columns differ
        }

        // 4. จำนวนรายการประเมิน PA
        $data['countPendingEvaluations'] = 0;
        if ($db_pa) {
            try {
                $data['countPendingEvaluations'] = $db_pa->table('tb_evaluations')
                                                       ->where('ev_fiscal_year', $fiscalYearBE)
                                                       ->countAllResults();
            } catch (\Throwable $e) {
                $data['countPendingEvaluations'] = 0;
            }
        }

        // 5. จำนวนผู้ใช้งานที่มีสิทธิ์
        $data['countTotalUsers'] = 0;
        try {
            $data['countTotalUsers'] = $db->table('tb_admin_rloes')
                                          ->distinct()
                                          ->select('admin_rloes_userid')
                                          ->countAllResults();
        } catch (\Throwable $e) {
            $data['countTotalUsers'] = 0;
        }

        return view('Admin/AdminHome/AdminPageHome', $data);
    }

    public function User()
    {
        $session = session();
        $data = $this->DataMain();
        $data['title'] = "หน้าแรก";

        return view('User/UserHome/UserPageHome', $data);
    }
}