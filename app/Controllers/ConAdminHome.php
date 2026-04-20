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
        $session = session();
        $data = $this->DataMain();
        $data['title'] = "หน้าแรก";
        
        $db = \Config\Database::connect();
        $db_pa = \Config\Database::connect('pa_evaluation');
        
        // 1. จำนวนบุคลากรทั้งหมดที่กำลังใช้งาน
        $data['countAllPersonnel'] = $db->table('tb_personnel')
                                        ->where("pers_status", "กำลังใช้งาน")
                                        ->countAllResults();

        // 2. จำนวนคนลงเวลาวันนี้
        $data['countAttendanceToday'] = $db->table('tb_personnel_attendance')
                                           ->where('att_date', date('Y-m-d'))
                                           ->countAllResults();

        // 3. จำนวนรายการประเมิน PA (ในที่นี้ขอนับรายการประเมินทั้งหมดในปีปัจจุบันเป็นตัวอย่าง)
        $data['countPendingEvaluations'] = $db_pa->table('tb_evaluations')
                                               ->where('ev_fiscal_year', 2568) // ปีปัจจุบันตามระบบ
                                               ->countAllResults();

        // 4. จำนวนผู้ใช้งานที่มีสิทธิ์
        $data['countTotalUsers'] = $db->table('tb_admin_rloes')
                                      ->distinct()
                                      ->select('admin_rloes_userid')
                                      ->countAllResults();

        return view('Admin/AdminHome/AdminPageHome', $data);
    }

    public function User()
    {
        $session = session();
        $data = $this->DataMain();
        $data['title']="หน้าแรก";

        return view('User/UserHome/UserPageHome', $data);
    }    

}