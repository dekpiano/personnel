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
        $db_personnel = \Config\Database::connect('personnel');
        
        $total_personnel = $db_personnel->table('tb_personnel')
            ->where('pers_status', 'กำลังใช้งาน')
            ->countAllResults();
            
        $total_executives = $db_personnel->table('tb_personnel')
            ->where('pers_status', 'กำลังใช้งาน')
            ->where('pers_position <=', 'posi_002')
            ->countAllResults();
            
        $total_teachers = $db_personnel->table('tb_personnel')
            ->where('pers_status', 'กำลังใช้งาน')
            ->where('pers_position >=', 'posi_003')
            ->where('pers_position <=', 'posi_006')
            ->countAllResults();
            
        $total_support = $db_personnel->table('tb_personnel')
            ->where('pers_status', 'กำลังใช้งาน')
            ->where('pers_position >=', 'posi_007')
            ->countAllResults();

        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        $data['description']="หน้าแรกระบบบริหารงานบุคคล";
        $data['UrlMenuMain'] = 'Main';
        $data['UrlMenuSub'] = '';
        
        $data['stats'] = [
            'total' => $total_personnel,
            'executives' => $total_executives,
            'teachers' => $total_teachers,
            'support' => $total_support
        ];

        return view('User/UserHome/UserPageHome', $data);
    }

    
    
}
