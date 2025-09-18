<?php

namespace App\Controllers;

class ConAdminHome extends BaseController
{
    public function __construct(){
        $session = session();
        if(!$session->get('username') && $session->get('status') != "admin" && $session->get('status') != "manager"){
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
        $data['title']="หน้าแรก";
        $database = \Config\Database::connect();
        $DBPers = $database->table('tb_personnel');
      
        $data['countAllPersonnel'] = $DBPers->where("pers_status","กำลังใช้งาน")->countAllResults();

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