<?php

namespace App\Controllers;

class ConAdminRoles extends BaseController
{
    public function __construct(){
        $session = session();
        if(!$session->get('username') || $_SESSION['status'] !== 'superadmin'){
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
        $data = $this->DataMain();
        $data['title']="จัดการข้อมูลกำหนดสิทธิ์การใช้งาน";
        $DB_Personnel = \Config\Database::connect();
        $tb_admin_rloes = $DB_Personnel->table('tb_admin_rloes');
        $DBPers = $DB_Personnel->table('tb_personnel');

        // Fetch all users with roles
        $data['RolesUsers'] = $tb_admin_rloes->select('admin_rloes_id, admin_rloes_userid, admin_rloes_nanetype, admin_rloes_status')       
        ->get()->getResult();

        // Fetch all personnel for the dropdown
        $data['NameTeacher'] = $DBPers->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_position, pers_learning')
        ->where('pers_status','กำลังใช้งาน')
        ->orderBy('pers_position','ASC')
        ->get()->getResult();

        // Define available work sections
        $data['AvailableWorks'] = [
            'งานทะเบียนครูและบุคลากร',
            'งานประเมิน pa',
            'ผู้อำนวยการโรงเรียน',
            'รองผู้อำนวยการบริหารงานบุคคลกร',
            'หัวหน้าบริหารทั่วไป'
        ];

        return view('Admin/AdminRoles/AdminRolesMain', $data);
    }

    public function RloesUpdateUser() {      
        $DB_Personnel = \Config\Database::connect();
        $DBrloes = $DB_Personnel->table('tb_admin_rloes');
        
        $rloesId = $this->request->getPost('RloesID');
        $userId = $this->request->getPost('UserID');
        $works = $this->request->getPost('Works'); // Array of selected works
        $status = $this->request->getPost('Status');

        $nanetype = is_array($works) ? implode('|', $works) : $works;

        $data = [
            'admin_rloes_userid' => $userId,
            'admin_rloes_nanetype' => $nanetype,
            'admin_rloes_status' => $status
        ];

        $DBrloes->where('admin_rloes_id', $rloesId);
        $result = $DBrloes->update($data);
        return $this->response->setJSON(['status' => $result ? 'success' : 'error']);
    }

    public function RloesAddUser() {
        $DB_Personnel = \Config\Database::connect();
        $DBrloes = $DB_Personnel->table('tb_admin_rloes');
        
        $userId = $this->request->getPost('UserID');
        $works = $this->request->getPost('Works'); // Array of selected works
        $status = $this->request->getPost('Status') ?? 'admin';

        // Check if user already exists
        $existing = $DBrloes->where('admin_rloes_userid', $userId)->get()->getRow();
        if ($existing) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ผู้ใช้นี้มีสิทธิ์อยู่แล้ว']);
        }

        $nanetype = is_array($works) ? implode('|', $works) : $works;

        $data = [
            'admin_rloes_userid' => $userId,
            'admin_rloes_nanetype' => $nanetype,
            'admin_rloes_status' => $status
        ];

        $result = $DBrloes->insert($data);
        return $this->response->setJSON(['status' => $result ? 'success' : 'error']);
    }

    public function RloesDeleteUser() {
        $DB_Personnel = \Config\Database::connect();
        $DBrloes = $DB_Personnel->table('tb_admin_rloes');
        $id = $this->request->getPost('RloesID');
        
        $result = $DBrloes->where('admin_rloes_id', $id)->delete();
        return $this->response->setJSON(['status' => $result ? 'success' : 'error']);
    }
 

}
