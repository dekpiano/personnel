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
        $data['title']="ทะเบียนครูและบุคคลทางการศึกษา";
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
        $data['title']="เพิ่มข้อมูลครูและบุคคลทางการศึกษา";
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
        //print_r($this->request->getVar()); exit();
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
                'pers_workother_id' => $this->request->getPost('pers_workother_id') ?? "",
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
                'pers_workother_id' => $this->request->getPost('pers_workother_id') ?? "",
                'pers_learning' => $this->request->getPost('pers_learning'),
                'pers_academic' => $this->request->getPost('pers_academic'),
                'pers_groupleade' => $this->request->getPost('pers_groupleade'),
                'pers_dataUpdate' => date('Y-m-d H:i:s'),
                'pers_userEdit' => $session->get('id')
            ];
        }
        //print_r($data); exit();
       
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
        $data['title']="อัพเดตข้อมูลครูและบุคคลทางการศึกษา";    
        $DB_SKJ = \Config\Database::connect('skj');
        $DBPosi = $DB_SKJ->table('tb_position');
        $DBLear = $DB_SKJ->table('tb_learning');
        $DBPosiMain = $DB_SKJ->table('tb_position_main');
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data['position'] = $DBPosi->get()->getResult();
        $data['learning'] = $DBLear->get()->getResult();
       

        $data['Pers'] = $DBPers->where('pers_id',$IDPres)->get()->getRow();

         $PosiMain = $DBPosiMain->where('work_id',$data['Pers']->pers_workother_id)
         ->get()->getRow();
         $data['PosiMain'] = $DBPosiMain->where('posi_id',$PosiMain->posi_id ?? "")
         ->get()->getResult();

        //$fieldList = $DB_Personnel->getFieldNames('tb_personnel');
        //echo '<pre>'; print_r($data['PosiMain']); exit();

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
            'pers_groupleade' => $this->request->getVar('pers_groupleade'),
            'pers_workother_id' => $this->request->getVar('pers_workother_id') ?? "",
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
       
        $delFile = $DBPers->select('pers_img')->where('pers_id',$this->request->getPost('KeyPresID'))->get()->getRow();
         
        $filePath = ROOTPATH . 'uploads/admin/Personnal/'.@$delFile->pers_img;
        //print_r(file_exists($filePath)); exit();
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
        } else {
                // เกิดข้อผิดพลาด!
                $errorMsg = '';
                if (empty($image)) {
                    $errorMsg = 'ไม่ได้เลือกไฟล์';
                } elseif (!$image->isValid()) {
                    $errorMsg = $image->getErrorString();
                } elseif ($image->hasMoved()) {
                    $errorMsg = 'ไฟล์นี้ถูกอัปโหลดไปแล้ว';
                }
                // แสดง error ตามต้องการ
                echo '<div class="alert alert-danger">'.$errorMsg.'</div>';
            }
    }

    public function PersonnelGet($id){ 
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data = $DBPers->select('*')       
        ->join('tb_personnel_addresses','tb_personnel_addresses.pers_id = tb_personnel.pers_id','left')
        //->join('tb_position','tb_position.posi_id = tb_personnel.pers_position','left')
        ->join('skjacth_skj.tb_position_main','tb_position_main.work_id = tb_personnel.pers_workother_id','left')
        ->where('tb_personnel.pers_id',$id)
        ->get()->getResult();

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
     
     public function PersonnelUpdateAlone(){
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        $DBPersAddr = $DB_Personnel->table('tb_personnel_addresses');

        $field = $this->request->getVar('field');
        $value = $this->request->getVar('value');

        $Ex = explode("_",$field);
       
        if($Ex[0] === "curr"){
            $CheckPresID = $DBPersAddr->select('pers_id')
            ->where('addr_type',"ปัจจุบัน")
            ->where('pers_id',$this->request->getVar('PresID'))->get()->getRow();
           
            if (str_starts_with($field, 'curr_') && str_contains($field, 'addr_')) {
                $fieldNew = str_replace('curr_', '', $field);
            }
            
            if($CheckPresID){
                //print_r(($fieldNew)); exit();
                $DBPersAddr->where('pers_id', $this->request->getVar('PresID'));
                $DBPersAddr->where('addr_type',"ปัจจุบัน");
                $DBPersAddr->update([$fieldNew => $value]);
                echo 1;
                
            }else{   
                $data = [
                    'pers_id' => $this->request->getVar('PresID'),
                    'addr_type' => "ปัจจุบัน",
                    $fieldNew => $value
                ];
                echo $DBPersAddr->insert($data);
            }
        }else if($Ex[0] === "addr"){
            $CheckPresID = $DBPersAddr->select('pers_id')
            ->where('addr_type',"ทะเบียนบ้าน")
            ->where('pers_id',$this->request->getVar('PresID'))->get()->getRow();
            if($CheckPresID){
                $DBPersAddr->where('pers_id', $this->request->getVar('PresID'));
                $DBPersAddr->where('addr_type',"ทะเบียนบ้าน");
                $DBPersAddr->update([$field => $value]);
                echo 1;
               
            }else{      
                $data = [
                    'pers_id' => $this->request->getVar('PresID'),
                    'addr_type' => "ทะเบียนบ้าน",
                    $field => $value
                ];
                echo $DBPersAddr->insert($data);
                
            }
        }
        


        $fieldList = $DB_Personnel->getFieldNames('tb_personnel');
        if (in_array($field, $fieldList)) {
            $DBPers->where('pers_id', $this->request->getVar('PresID'));
            echo $DBPers->update([$field => $value]);
        }else{
            echo 0;
        }
            
       
       
     }

}