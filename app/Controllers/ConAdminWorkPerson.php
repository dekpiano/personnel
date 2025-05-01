<?php

namespace App\Controllers;

class ConAdminWorkPerson extends BaseController
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
        $data['title']="ทะเบียนครูและบุคลากรทางการศึกษา";
        $DB_SKJ = \Config\Database::connect('skj');
        $DBLear = $DB_SKJ->table('tb_learning');
        $DBPosi = $DB_SKJ->table('tb_position');
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data['Learning'] = $DBLear
        ->select('skjacth_skj.tb_learning.lear_namethai,
        skjacth_skj.tb_learning.lear_id,
        COUNT(skjacth_personnel.tb_personnel.pers_id) AS NumAll,
        (GROUP_CONCAT(skjacth_personnel.tb_personnel.pers_img)) AS AllImg')
        ->join('skjacth_personnel.tb_personnel','skjacth_skj.tb_learning.lear_id = skjacth_personnel.tb_personnel.pers_learning')
        ->where('pers_status',"กำลังใช้งาน")
        ->groupBy('skjacth_personnel.tb_personnel.pers_learning')
        ->orderBy('lear_id')
        ->get()->getResult();

        //$test = $DBPers->select('json_array(GROUP_CONCAT(pers_img)) AS json_data')->get()->getResult();

        $data['Executive'] = $DBPers
        ->select('(GROUP_CONCAT(skjacth_personnel.tb_personnel.pers_img)) AS AllImg,
        count(skjacth_personnel.tb_personnel.pers_id) AS NumAll')
        ->where('pers_status',"กำลังใช้งาน")
        ->where('pers_position <=','posi_002')->get()->getResult();

        $data['Support']= $DBPosi
        ->select('skjacth_skj.tb_position.posi_id,
        skjacth_skj.tb_position.posi_name,
        count(skjacth_personnel.tb_personnel.pers_id) AS NumAll,
        (GROUP_CONCAT(skjacth_personnel.tb_personnel.pers_img)) AS AllImg')
        ->join('skjacth_personnel.tb_personnel','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
        ->where('pers_status',"กำลังใช้งาน")
        ->where('posi_id >=',"posi_007")
        ->groupBy('skjacth_skj.tb_position.posi_id')
        ->get()->getResult();
        //echo '<pre>'; print_r($data['Support']); exit();

        return view('Admin/AdminLeyout/AdminHeader',$data)
                .view('Admin/AdminLeyout/AdminMenuLeft')
                .view('Admin/AdminWorkPerson/AdminPersonMain')
                .view('Admin/AdminLeyout/AdminFooter');
    }

    private function resizeImage($path, $width, $height)
    {
        $image = \Config\Services::image()
            ->withFile(ROOTPATH . $path)
            ->resize($width, $height, true) // ให้สมส่วน
            ->save(ROOTPATH . $path);
    }

    public function FormAdd(){
        $session = session();
        $data = $this->DataMain();
        $data['title']="เพิ่มข้อมูลครูและบุคลากรทางการศึกษา";
        $DB_SKJ = \Config\Database::connect('skj');
        $DBPosi = $DB_SKJ->table('tb_position');
        $DBLear = $DB_SKJ->table('tb_learning');
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data['position'] = $DBPosi->get()->getResult();
        $data['learning'] = $DBLear->get()->getResult();
       
		$data['pers'] =	$DBPers->orderBy('pers_id','DESC')->get()->getResult();		
		$num = @explode("_", $data['pers'][0]->pers_id);
        $num1 = @sprintf("%03d",$num[1]+1);
        $data['pers_id'] = 'pers_'.$num1;


        return view('Admin/AdminLeyout/AdminHeader',$data)
                .view('Admin/AdminLeyout/AdminMenuLeft')
                .view('Admin/AdminWorkPerson/AdminPersonAdd')
                .view('Admin/AdminLeyout/AdminFooter');
    }
   
    public function PersonnelInsert(){
        //print_r($this->request->getVar());
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $image = $this->request->getFile('pers_img');
        
        if (!empty($image) && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'uploads/admin/Personnal/', $newName);
    
            $this->resizeImage('uploads/admin/Personnal/' . $newName, 600, 800);
    
            $data = [
                'pers_status' => $this->request->getPost('pers_status'),
                'pers_id' => $this->request->getPost('pers_id'),
                'pers_prefix' => $this->request->getPost('pers_prefix'),
                'pers_firstname' => $this->request->getPost('pers_firstname'),
                'pers_lastname' => $this->request->getPost('pers_lastname'),
                'pers_phone' => $this->request->getPost('pers_phone'),
                'pers_username' => $this->request->getPost('pers_username'),
                'pers_position' => $this->request->getPost('pers_position'),
                'pers_learning' => $this->request->getPost('pers_learning'),
                'pers_academic' => $this->request->getPost('pers_academic'),
                'pers_groupleade' => $this->request->getPost('pers_groupleade'),
                'pers_img'  => $newName,
                'pers_dataUpdate' => date('Y-m-d H:i:s'),
                'pers_userEdit' => $session->get('id')
            ];
        } else {
            $data = [
                'pers_status' => $this->request->getPost('pers_status'),
                'pers_id' => $this->request->getPost('pers_id'),
                'pers_prefix' => $this->request->getPost('pers_prefix'),
                'pers_firstname' => $this->request->getPost('pers_firstname'),
                'pers_lastname' => $this->request->getPost('pers_lastname'),
                'pers_phone' => $this->request->getPost('pers_phone'),
                'pers_username' => $this->request->getPost('pers_username'),
                'pers_position' => $this->request->getPost('pers_position'),
                'pers_learning' => $this->request->getPost('pers_learning'),
                'pers_academic' => $this->request->getPost('pers_academic'),
                'pers_groupleade' => $this->request->getPost('pers_groupleade'),
                'pers_dataUpdate' => date('Y-m-d H:i:s'),
                'pers_userEdit' => $session->get('id')
            ];
        }

        echo $DBPers->insert($data);

    }

    public function PersonneViewGroup($Key){
        $session = session();
        $data = $this->DataMain();
        $data['title']="ข้อมูลตามกลุ่ม";

        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        $sub = explode("_",$Key);
        if($sub[0] == "posi"){
            $Where = ['pers_position'=>$Key];
            $data['Teach'] = false;
        }elseif($Key === "Executive"){
            $Where = 'pers_position = "posi_001" || pers_position= "posi_002"';
            $data['Teach'] = true;
        }
        else{
            $Where = ['pers_learning'=>$Key];
            $data['Teach'] = true;
        }

        $data["Teacher"] = $DBPers
        ->select('pers_id,pers_prefix,pers_firstname,pers_lastname,pers_img,posi_name,pers_academic')
        ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
        ->where('pers_status',"กำลังใช้งาน")
        ->where($Where)
        ->orderBy('pers_numberGroup','ASC')
        ->get()->getResult();
        //echo '<pre>'; print_r($data['Teacher']); exit();


        return view('Admin/AdminLeyout/AdminHeader',$data)
                .view('Admin/AdminLeyout/AdminMenuLeft')
                .view('Admin/AdminWorkPerson/AdminPersonGroup')
                .view('Admin/AdminLeyout/AdminFooter');
    }


    public function SortableTeacher(){

        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        foreach ($this->request->getPost('data') as $key => $value) {
            
            $data = ['pers_numberGroup'=>$key];
            $DBPers->where('pers_id', $value);
            $DBPers->update($data);
           // echo $key;
        }
       
    }

    public function FormPersonneUpdate($IDPres){
        $session = session();
        $data = $this->DataMain();
        $data['title']="อัพเดตข้อมูลครูและบุคลากรทางการศึกษา";    
        $DB_SKJ = \Config\Database::connect('skj');
        $DBPosi = $DB_SKJ->table('tb_position');
        $DBLear = $DB_SKJ->table('tb_learning');
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data['position'] = $DBPosi->get()->getResult();
        $data['learning'] = $DBLear->get()->getResult();

        $data['Pers'] = $DBPers->where('pers_id',$IDPres)->get()->getRow();
        //echo '<pre>'; print_r($data['Pers']); exit();

        return view('Admin/AdminLeyout/AdminHeader',$data)
                .view('Admin/AdminLeyout/AdminMenuLeft')
                .view('Admin/AdminWorkPerson/AdminPersonUpdate')
                .view('Admin/AdminLeyout/AdminFooter');
    }

    public function PersonneUpdateDataPersonnel(){
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        $data = [
            'pers_status' => $this->request->getVar('pers_status'),
            'pers_prefix' => $this->request->getVar('pers_prefix'),
            'pers_firstname' => $this->request->getVar('pers_firstname'),
            'pers_lastname' => $this->request->getVar('pers_lastname'),
            'pers_username' => $this->request->getVar('pers_username'),
            'pers_phone' => $this->request->getVar('pers_phone'),
            'pers_position' => $this->request->getVar('pers_position'),
            'pers_learning' => $this->request->getVar('pers_learning'),
            'pers_academic' => $this->request->getVar('pers_academic'),
            'pers_groupleade' => $this->request->getVar('pers_groupleade')
        ];
        $DBPers->where('pers_id', $this->request->getVar('pers_id'));
        echo $DBPers->update($data);
        //echo $this->request->getVar('pers_britday');
    }

    public function PersonneUpdateDataHistory(){
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        $data = [
            'pers_id' => $this->request->getVar('pers_id'),
            'pers_history' => $this->request->getVar('pers_history'),
            'pers_date' => date('Y-m-d H:i:s')
        ];
        //echo $DBPers->insert($data);
        print_r($this->request->getVar());
    }

    public function PersonnelUpdateImg(){ 
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $image = $this->request->getFile('file');
        //print_r($image);
        $delFile = $DBPers->select('pers_img')->where('pers_id',$this->request->getPost('KeyPresID'))->get()->getRow();
        $filePath = ROOTPATH . 'uploads/admin/Personnal/'.@$delFile->pers_img;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

        if (!empty($image) && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'uploads/admin/Personnal/', $newName);
    
            $this->resizeImage('uploads/admin/Personnal/' . $newName, 600, 800);

            $data = [
                'pers_img' => $newName
            ];
            $DBPers->where('pers_id',$this->request->getPost('KeyPresID'));
            echo $DBPers->update($data);
        }
    }

    public function PersonnelGet($id){ 
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data = $DBPers->where('pers_id',$id)->get()->getRow();

        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }
     }
       
     public function GetPositionData(){
        $session = session();
        $DB_SKJ = \Config\Database::connect('skj');
        $DBPosi = $DB_SKJ->table('tb_position');

        $positionId = $this->request->getPost('position_id');
        $data = $DBPosi->select('work_id,work_name')
        ->where('tb_position.posi_id',$positionId)
        ->join('skjacth_skj.tb_position_main','skjacth_skj.tb_position_main.posi_id = skjacth_skj.tb_position.p_id')
        ->get()->getResult();
        return $this->response->setJSON($data);
     }
     
    

}