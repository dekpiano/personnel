<?php

namespace App\Controllers;

class ConUserHome extends BaseController
{  

    function __construct(){
       
    }


    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   
        $data['uri'] = service('uri'); 
        return $data;
    }

    public function index()
    {
        $session = session();
        $database = \Config\Database::connect();
        $builder = $database->table('tb_personnel');

        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        $data['description']="หน้าแรกระบบบริหารงานบุคคล";
        $data['UrlMenuMain'] = 'Main';
        $data['UrlMenuSub'] = '';

        return view('User/UserHome/UserPageHome', $data);
    }

    
    
}
