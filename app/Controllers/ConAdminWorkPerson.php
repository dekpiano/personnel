<?php
namespace App\Controllers;

class ConAdminWorkPerson extends BaseController
{
    public function __construct(){
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
        ->groupBy('skjacth_skj.tb_learning.lear_id, skjacth_skj.tb_learning.lear_namethai')
        ->orderBy('lear_id')
        ->get()->getResult();

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
        ->groupBy('skjacth_skj.tb_position.posi_id, skjacth_skj.tb_position.posi_name')
        ->get()->getResult();

        return view('Admin/AdminWorkPerson/AdminPersonMain', $data);
    }

    private function resizeImage($path, $width, $height)
    {
        $fullPath = rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        if (file_exists($fullPath)) {
            $image = \Config\Services::image()
                ->withFile($fullPath)
                ->resize($width, $height, true)
                ->save($fullPath);
        }
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
        $this->checkFactionColumn($DB_Personnel);
       
		$data['pers'] =	$DBPers->orderBy('pers_id','DESC')->get()->getResult();		
		$num = @explode("_", $data['pers'][0]->pers_id);
        $num1 = @sprintf("%03d",$num[1]+1);
        $data['pers_id'] = 'pers_'.$num1;

        return view('Admin/AdminWorkPerson/AdminPersonAdd', $data);
    }
   
    public function PersonnelInsert(){
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $image = $this->request->getFile('pers_img');
        
        $faction = $this->request->getPost('pers_faction');
        $factionStr = is_array($faction) ? implode(',', $faction) : ($faction ?? "");

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
                'pers_faction' => $factionStr,
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
                'pers_faction' => $factionStr,
                'pers_dataUpdate' => date('Y-m-d H:i:s'),
                'pers_userEdit' => $session->get('id')
            ];
        }
       
        if ($DBPers->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มข้อมูลสำเร็จ']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถเพิ่มข้อมูลได้']);
        }
    }

    public function PersonneViewGroup($Key){
        $session = session();
        $data = $this->DataMain();
        $data['title']="ข้อมูลตามกลุ่ม";

        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        $sub = explode("_",$Key);
        if($sub[0] == "posi"){
            $DBPers->where('pers_position',$Key);
            $data['Teach'] = false;
        }elseif($Key === "Executive"){
            $DBPers->groupStart()
                   ->where('pers_position', 'posi_001')
                   ->orWhere('pers_position', 'posi_002')
                   ->groupEnd();
            $data['Teach'] = true;
        }
        else{
            $DBPers->where('pers_learning',$Key);
            $data['Teach'] = true;
        }

        $data["Teacher"] = $DBPers
        ->select('pers_id,pers_prefix,pers_firstname,pers_lastname,pers_img,posi_name,pers_academic')
        ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
        ->where('pers_status',"กำลังใช้งาน")
        ->orderBy('pers_numberGroup','ASC')
        ->get()->getResult();

        return view('Admin/AdminWorkPerson/AdminPersonGroup', $data);
    }


    public function SortableTeacher(){

        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        foreach ($this->request->getPost('data') as $key => $value) {
            
            $data = ['pers_numberGroup'=>$key];
            $DBPers->where('pers_id', $value);
            $DBPers->update($data);
        }
       
    }

    public function FormPersonneUpdate($IDPres){
        if (!$this->canEditPersonnel($IDPres)) {
            return redirect()->to(base_url())->with('Error', 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้');
        }

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
        $this->checkFactionColumn($DB_Personnel);
       

        $data['Pers'] = $DBPers->where('pers_id',$IDPres)->get()->getRow();

        if ($data['Pers'] && $data['Pers']->pers_britday) {
            $d = explode("-", $data['Pers']->pers_britday);
            if(count($d) == 3){
                $data['Pers']->pers_britday = $d[2]."/".$d[1]."/".($d[0]+543);
            }
        }

        // Fetch addresses
        $DBAddr = $DB_Personnel->table('tb_personnel_addresses');
        $data['AddrReg'] = $DBAddr->where(['pers_id' => $IDPres, 'addr_type' => 'ทะเบียนบ้าน'])->get()->getRow();
        $data['AddrCurr'] = $DBAddr->where(['pers_id' => $IDPres, 'addr_type' => 'ปัจจุบัน'])->get()->getRow();

        $PosiMain = $DBPosiMain->where('work_id', $data['Pers']?->pers_workother_id ?? "")
        ->get()->getRow();
        $data['PosiMain'] = $DBPosiMain->where('posi_id',$PosiMain->posi_id ?? "")
        ->get()->getResult();

        // Fetch Personnel Documents
        $this->checkDocumentsTable($DB_Personnel);
        $DBDoc = $DB_Personnel->table('tb_personnel_documents');
        
        // Single-type documents (เอกสารประจำตัว)
        $data['DocIdCard'] = $DBDoc->where(['pers_id' => $IDPres, 'doc_type' => 'id_card'])->get()->getRow();
        $DBDoc = $DB_Personnel->table('tb_personnel_documents'); // Reset query
        $data['DocHouseReg'] = $DBDoc->where(['pers_id' => $IDPres, 'doc_type' => 'house_reg'])->get()->getRow();
        $DBDoc = $DB_Personnel->table('tb_personnel_documents'); // Reset query
        $data['DocNameChange'] = $DBDoc->where(['pers_id' => $IDPres, 'doc_type' => 'name_change'])->get()->getRow();
        
        // License documents (ใบประกอบวิชาชีพ)
        $DBDoc = $DB_Personnel->table('tb_personnel_documents'); // Reset query
        $data['DocTeacherLicense'] = $DBDoc->where(['pers_id' => $IDPres, 'doc_type' => 'teacher_license'])->get()->getRow();
        $DBDoc = $DB_Personnel->table('tb_personnel_documents'); // Reset query
        $data['DocAdminLicense'] = $DBDoc->where(['pers_id' => $IDPres, 'doc_type' => 'admin_license'])->get()->getRow();

        return view('Admin/AdminWorkPerson/AdminPersonUpdate', $data);
    }

    public function PersonneUpdateDataPersonnel(){
        $pers_id = $this->request->getVar('pers_id');
        if (!$this->canEditPersonnel($pers_id)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้']);
        }

        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        
        $faction = $this->request->getVar('pers_faction');
        $factionStr = is_array($faction) ? implode(',', $faction) : ($faction ?? "");

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
            'pers_faction' => $factionStr,
            'pers_workother_id' => $this->request->getVar('pers_workother_id') ?? "",
        ];
        $DBPers->where('pers_id', $this->request->getVar('pers_id'));
        if ($DBPers->update($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
        }
    }

    public function PersonneUpdateDataHistory(){
        $pers_id = $this->request->getVar('pers_id');
        if (!$this->canEditPersonnel($pers_id)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์แก้ไขข้อมูลประวัตินี้']);
        }
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');
        $DBAddr = $DB_Personnel->table('tb_personnel_addresses');
        
        $pers_id = $this->request->getVar('pers_id');
        if(!$pers_id) return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรหัสบุคลากร']);

        try {
            $data = $this->request->getPost();
            $updateData = [];
            
            // Loop through all posted data
            foreach ($data as $key => $value) {
                // Skip non-field keys
                if (in_array($key, ['pers_id', 'key_update'])) continue;

                // Handle Date Conversion (DD/MM/YYYY + 543 -> YYYY-MM-DD)
                if ($key == 'pers_britday' && !empty($value)) {
                     $normValue = str_replace('-', '/', $value);
                     $d = explode("/", $normValue);
                     if(count($d) == 3){
                         if ((int)$d[0] > 1000) { // YYYY/MM/DD
                             $year = (int)$d[0]; $month = (int)$d[1]; $day = (int)$d[2];
                         } else { // DD/MM/YYYY
                             $year = (int)$d[2]; $month = (int)$d[1]; $day = (int)$d[0];
                         }
                         if ($year > 2400) $year -= 543;
                         $value = sprintf("%04d-%02d-%02d", $year, $month, $day);
                     }
                }

                // Identify table based on field prefix
                if (str_starts_with($key, 'pers_')) {
                    $updateData[$key] = $value;
                } else if (str_starts_with($key, 'curr_') && str_contains($key, 'addr_')) {
                     // Handle Address Update (Current Address)
                     $fieldAddr = str_replace('curr_', '', $key);
                     
                     // Upsert Address
                     $exists = $DBAddr->where('pers_id', $pers_id)->where('addr_type', 'ปัจจุบัน')->countAllResults();
                     if($exists > 0) {
                         $DBAddr->where('pers_id', $pers_id)->where('addr_type', 'ปัจจุบัน')->update([$fieldAddr => $value]);
                     } else {
                         // Insert new structure if missing (simplified, ideally should insert all fields at once)
                          $DBAddr->insert([
                              'pers_id' => $pers_id,
                              'addr_type' => 'ปัจจุบัน',
                              $fieldAddr => $value
                          ]);
                     }
                }
            }

            // Update Personnel Table
            if (!empty($updateData)) {
                $DBPers->where('pers_id', $pers_id)->update($updateData);
            }

            return $this->response->setJSON(['status' => 'success']);

        } catch (\Exception $e) {
             return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function PersonnelUpdateImg(){ 
        $pers_id = $this->request->getPost('KeyPresID');
        if (!$this->canEditPersonnel($pers_id)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์แก้ไขรูปภาพนี้']);
        }
        try {
            $session = session();
            $DB_Personnel = \Config\Database::connect('personnel');
            $DBPers = $DB_Personnel->table('tb_personnel');

            // Check both 'file' (from crop) and 'pers_img' (standard)
            $image = $this->request->getFile('file');
            if (!$image || !$image->isValid()) {
                $image = $this->request->getFile('pers_img');
            }

            $pers_id = $this->request->getPost('KeyPresID');
            if (!$pers_id) {
                 return $this->response->setStatusCode(400)->setBody("ไม่พบรหัสบุคลากร (KeyPresID)");
            }
           
            if ($image && $image->isValid() && !$image->hasMoved()) {
                // Delete old image first
                $delFile = $DBPers->select('pers_img')->where('pers_id', $pers_id)->get()->getRow();
                if ($delFile && !empty($delFile->pers_img)) {
                    $filePath = ROOTPATH . 'uploads/admin/Personnal/' . $delFile->pers_img;
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }

                $uploadPath = rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'Personnal' . DIRECTORY_SEPARATOR;
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                if (!is_writable($uploadPath)) {
                    throw new \Exception("Upload directory is not writable: " . $uploadPath);
                }

                $newName = $image->getRandomName();
                if (!$image->move($uploadPath, $newName)) {
                    throw new \Exception("Failed to move uploaded file to " . $uploadPath);
                }
        
                $this->resizeImage('uploads/admin/Personnal/' . $newName, 600, 800);

                $data = ['pers_img' => $newName];
                $DBPers->where('pers_id', $pers_id);
                if ($DBPers->update($data)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'เปลี่ยนรูปภาพสำเร็จ']);
                } else {
                    return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกรูปภาพลงฐานข้อมูลได้']);
                }
            } else {
                if ($image && !$image->isValid() && $image->getError() != 4) {
                    return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $image->getErrorString()]);
                } else {
                    return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ไม่ได้เลือกไฟล์']);
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function PersonnelGet($id){ 
        $session = session();
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');

        $data = $DBPers->select('*')       
        ->join('tb_personnel_addresses','tb_personnel_addresses.pers_id = tb_personnel.pers_id','left')
        ->join('skjacth_skj.tb_position_main','tb_position_main.work_id = tb_personnel.pers_workother_id','left')
        ->where('tb_personnel.pers_id',$id)
        ->get()->getResult();

        if ($data) {
            foreach ($data as $row) {
                if (isset($row->pers_britday) && !empty($row->pers_britday)) {
                    $d = explode("-", $row->pers_britday);
                    if (count($d) == 3) {
                        $row->pers_britday = $d[2] . "/" . $d[1] . "/" . ($d[0] + 543);
                    }
                }
            }

            // Fetch Family Data
            try {
                // Ensure table exists
                $this->checkFamilyTable($DB_Personnel);
                
                $DBFamily = $DB_Personnel->table('tb_personnel_family');
                $familyData = $DBFamily->where('pers_id', $id)->get()->getResult();

                // Attach to the first element (assuming main data is for one person)
                if(!empty($data)) {
                    $data[0]->family = $familyData;
                }
            } catch (\Throwable $e) {
                // Ignore errors, default to empty family
                if(!empty($data)) {
                    $data[0]->family = [];
                }
            }

            // Fetch Education Data
            try {
                $this->checkLicenseColumns($DB_Personnel); // Ensure columns exist
                $this->checkEducationTable($DB_Personnel);

                $DBEdu = $DB_Personnel->table('tb_personnel_education');
                $eduData = $DBEdu->where('pers_id', $id)->orderBy('edu_year', 'ASC')->get()->getResult();

                // Attach document info to each education record
                $this->checkDocumentsTable($DB_Personnel);
                foreach ($eduData as &$edu) {
                    $doc = $DB_Personnel->table('tb_personnel_documents')
                        ->where(['pers_id' => $id, 'doc_category' => 'education', 'related_id' => $edu->id])
                        ->get()->getRow();
                    $edu->doc_id = $doc->id ?? null;
                }

                if(!empty($data)) {
                    $data[0]->education = $eduData;
                }

                // Convert License Dates for Display (YYYY-MM-DD -> DD/MM/YYYY + 543)
                 foreach ($data as $row) {
                    $licenseFields = ['pers_license_issue', 'pers_license_exp'];
                    foreach ($licenseFields as $f) {
                        if (isset($row->$f) && !empty($row->$f)) {
                            $d = explode("-", $row->$f);
                            if (count($d) == 3) {
                                $row->$f = $d[2] . "/" . $d[1] . "/" . ($d[0] + 543);
                            }
                        }
                    }
                }

            } catch (\Throwable $e) {
                 if(!empty($data)) {
                    $data[0]->education = [];
                }
            }

            // Fetch Work History Data (Vor.Kor.7)
            try {
                $this->checkWorkHistoryTable($DB_Personnel);
                
                $DBWork = $DB_Personnel->table('tb_personnel_work_history');
                $workData = $DBWork->where('pers_id', $id)->orderBy('work_date', 'ASC')->get()->getResult();

                // Process dates for display
                foreach ($workData as &$w) {
                    if (!empty($w->work_date)) {
                        $parts = explode('-', $w->work_date);
                        if (count($parts) == 3) {
                            $w->work_date_display = $parts[2] . '/' . $parts[1] . '/' . ($parts[0] + 543);
                        } else {
                            $w->work_date_display = $w->work_date;
                        }
                    } else {
                         $w->work_date_display = '-';
                    }

                    if (!empty($w->work_command_date)) {
                        $parts = explode('-', $w->work_command_date);
                        if (count($parts) == 3) {
                            $w->work_command_date_display = $parts[2] . '/' . $parts[1] . '/' . ($parts[0] + 543);
                        } else {
                            $w->work_command_date_display = $w->work_command_date;
                        }
                    } else {
                         $w->work_command_date_display = ''; // Empty string so we can check if exists
                    }
                }

                // Attach document info to each work history record
                foreach ($workData as &$w) {
                    $doc = $DB_Personnel->table('tb_personnel_documents')
                        ->where(['pers_id' => $id, 'doc_category' => 'work_order', 'related_id' => $w->id])
                        ->get()->getRow();
                    $w->doc_id = $doc->id ?? null;
                }

                if(!empty($data)) {
                    $data[0]->work_history = $workData;
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            // Fetch Decoration Data (New)
            try {
                $this->checkDecorationTable($DB_Personnel);
                
                $DBDeco = $DB_Personnel->table('tb_personnel_decorations');
                $decoData = $DBDeco->where('pers_id', $id)->orderBy('deco_date', 'ASC')->get()->getResult();

                // Process dates for display
                foreach ($decoData as &$d) {
                    if (!empty($d->deco_date)) {
                        $parts = explode('-', $d->deco_date);
                        if (count($parts) == 3) {
                            $d->deco_date_display = $parts[2] . '/' . $parts[1] . '/' . ($parts[0] + 543);
                        } else {
                            $d->deco_date_display = $d->deco_date;
                        }
                    } else {
                         $d->deco_date_display = '-';
                    }

                    if (!empty($d->deco_gazette_date)) {
                        $parts = explode('-', $d->deco_gazette_date);
                        if (count($parts) == 3) {
                            $d->deco_gazette_date_display = $parts[2] . '/' . $parts[1] . '/' . ($parts[0] + 543);
                        } else {
                            $d->deco_gazette_date_display = $d->deco_gazette_date;
                        }
                    } else {
                         $d->deco_gazette_date_display = '';
                    }
                }
                // Attach document info to each decoration record
                foreach ($decoData as &$d) {
                    $doc = $DB_Personnel->table('tb_personnel_documents')
                        ->where(['pers_id' => $id, 'doc_category' => 'decoration', 'related_id' => $d->id])
                        ->get()->getRow();
                    $d->doc_id = $doc->id ?? null;
                }
                if(!empty($data)) {
                    $data[0]->decorations = $decoData;
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            // Fetch Training Data (New)
            try {
                $this->checkTrainingTable($DB_Personnel);
                
                $DBTrain = $DB_Personnel->table('tb_personnel_training');
                $trainData = $DBTrain->where('pers_id', $id)->orderBy('train_start_date', 'ASC')->get()->getResult();

                foreach ($trainData as &$t) {
                    if (!empty($t->train_start_date)) {
                        $p1 = explode('-', $t->train_start_date);
                        $t->train_start_display = (count($p1) == 3) ? $p1[2] . '/' . $p1[1] . '/' . ($p1[0] + 543) : $t->train_start_date;
                    } else { $t->train_start_display = '-'; }

                    if (!empty($t->train_end_date)) {
                        $p2 = explode('-', $t->train_end_date);
                        $t->train_end_display = (count($p2) == 3) ? $p2[2] . '/' . $p2[1] . '/' . ($p2[0] + 543) : $t->train_end_date;
                    } else { $t->train_end_display = '-'; }

                    // Attach document info
                    $doc = $DB_Personnel->table('tb_personnel_documents')
                        ->where(['pers_id' => $id, 'doc_category' => 'training', 'related_id' => $t->id])
                        ->get()->getRow();
                    $t->doc_id = $doc->id ?? null;
                }
                if(!empty($data)) {
                    $data[0]->training = $trainData;
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            // Fetch Leave/Attendance Data from Attendance System (NOT from tb_personnel_leave)
            try {
                $DBAttendance = $DB_Personnel->table('tb_personnel_attendance');
                
                // Get current year's leave/absence records
                $currentYear = date('Y');
                $startDate = $currentYear . '-01-01';
                $endDate = $currentYear . '-12-31';
                
                // Get leave records (ลากิจ, ลาป่วย, ไปราชการ, ขาด, อื่นๆ)
                $leaveRecords = $DBAttendance
                    ->where('att_person_id', $id)
                    ->where('att_date >=', $startDate)
                    ->where('att_date <=', $endDate)
                    ->whereIn('att_status', ['ลากิจ', 'ลาป่วย', 'ไปราชการ', 'ขาด', 'อื่นๆ'])
                    ->orderBy('att_date', 'DESC')
                    ->get()->getResult();

                foreach ($leaveRecords as &$l) {
                    if (!empty($l->att_date)) {
                        $p1 = explode('-', $l->att_date);
                        $l->date_display = (count($p1) == 3) ? $p1[2] . '/' . $p1[1] . '/' . ($p1[0] + 543) : $l->att_date;
                    } else { 
                        $l->date_display = '-'; 
                    }
                }

                // Get summary stats for current year
                $DBAttendanceStats = $DB_Personnel->table('tb_personnel_attendance');
                $summaryRaw = $DBAttendanceStats
                    ->select("
                        SUM(CASE WHEN att_status = 'มา' THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN att_status = 'สาย' THEN 1 ELSE 0 END) as late,
                        SUM(CASE WHEN att_status = 'ลาป่วย' THEN 1 ELSE 0 END) as sick,
                        SUM(CASE WHEN att_status = 'ลากิจ' THEN 1 ELSE 0 END) as personal,
                        SUM(CASE WHEN att_status = 'ไปราชการ' THEN 1 ELSE 0 END) as official,
                        SUM(CASE WHEN att_status = 'ขาด' THEN 1 ELSE 0 END) as absent,
                        SUM(CASE WHEN att_status = 'อื่นๆ' THEN 1 ELSE 0 END) as other
                    ")
                    ->where('att_person_id', $id)
                    ->where('att_date >=', $startDate)
                    ->where('att_date <=', $endDate)
                    ->get()->getRow();

                if(!empty($data)) {
                    $data[0]->leave_records = $leaveRecords;
                    $data[0]->attendance_summary = [
                        'year' => $currentYear,
                        'present' => (int)($summaryRaw->present ?? 0) + (int)($summaryRaw->late ?? 0),
                        'sick' => (int)($summaryRaw->sick ?? 0),
                        'personal' => (int)($summaryRaw->personal ?? 0),
                        'official' => (int)($summaryRaw->official ?? 0),
                        'absent' => (int)($summaryRaw->absent ?? 0),
                        'other' => (int)($summaryRaw->other ?? 0),
                    ];
                }
            } catch (\Throwable $e) {
                if(!empty($data)) {
                    $data[0]->leave_records = [];
                    $data[0]->attendance_summary = [
                        'year' => date('Y'),
                        'present' => 0, 'sick' => 0, 'personal' => 0, 
                        'official' => 0, 'absent' => 0, 'other' => 0
                    ];
                }
            }

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

        if (in_array($field, ['pers_britday', 'pers_license_issue', 'pers_license_exp']) && !empty($value)) {
            $normValue = str_replace('-', '/', $value);
            $d = explode("/", $normValue);
            if(count($d) == 3){
                // Logic to handle both DD/MM/YYYY and YYYY/MM/DD
                if ((int)$d[0] > 1000) { // YYYY/MM/DD
                    $year = (int)$d[0];
                    $month = (int)$d[1];
                    $day = (int)$d[2];
                } else { // DD/MM/YYYY (Flatpickr standard)
                    $year = (int)$d[2];
                    $month = (int)$d[1];
                    $day = (int)$d[0];
                }

                if ($year > 2400) $year -= 543; // Convert BE to CE
                $value = sprintf("%04d-%02d-%02d", $year, $month, $day);
            }
        }

        $Ex = explode("_",$field);
        $updated = false;
       
        if($Ex[0] === "curr"){
            $CheckPresID = $DBPersAddr->select('pers_id')
            ->where('addr_type',"ปัจจุบัน")
            ->where('pers_id',$this->request->getVar('PresID'))->get()->getRow();
           
            if (str_starts_with($field, 'curr_') && str_contains($field, 'addr_')) {
                $fieldNew = str_replace('curr_', '', $field);
            }
            
            if($CheckPresID){
                $DBPersAddr->where('pers_id', $this->request->getVar('PresID'));
                $DBPersAddr->where('addr_type',"ปัจจุบัน");
                if ($DBPersAddr->update([$fieldNew => $value])) $updated = true;
            }else{   
                $data = [
                    'pers_id' => $this->request->getVar('PresID'),
                    'addr_type' => "ปัจจุบัน",
                    $fieldNew => $value
                ];
                if ($DBPersAddr->insert($data)) $updated = true;
            }
        }else if($Ex[0] === "addr"){
            $CheckPresID = $DBPersAddr->select('pers_id')
            ->where('addr_type',"ทะเบียนบ้าน")
            ->where('pers_id',$this->request->getVar('PresID'))->get()->getRow();
            if($CheckPresID){
                $DBPersAddr->where('pers_id', $this->request->getVar('PresID'));
                $DBPersAddr->where('addr_type',"ทะเบียนบ้าน");
                if ($DBPersAddr->update([$field => $value])) $updated = true;
            }else{      
                $data = [
                    'pers_id' => $this->request->getVar('PresID'),
                    'addr_type' => "ทะเบียนบ้าน",
                    $field => $value
                ];
                if ($DBPersAddr->insert($data)) $updated = true;
            }
        }
        


        $fieldList = $DB_Personnel->getFieldNames('tb_personnel');
        if (in_array($field, $fieldList)) {
            $DBPers->where('pers_id', $this->request->getVar('PresID'));
            if ($DBPers->update([$field => $value])) $updated = true;
        }

        return $this->response->setJSON(['status' => $updated ? 'success' : 'error', 'data' => $updated ? 1 : 0]);
     }

    public function CleanupImages()
    {
        try {
            $DB_Personnel = \Config\Database::connect('personnel');
            $DBPers = $DB_Personnel->table('tb_personnel');

            // 1. Get all images currently used in the database
            $usedImages = $DBPers->select('pers_img')
                                 ->where('pers_img IS NOT NULL')
                                 ->where('pers_img !=', '')
                                 ->get()
                                 ->getResultArray();
            $usedImageNames = array_column($usedImages, 'pers_img');

            // 2. Scan the directory
            $uploadPath = rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'Personnal' . DIRECTORY_SEPARATOR;
            
            if (!is_dir($uploadPath)) {
                return $this->response->setJSON(['status' => 'success', 'deleted_count' => 0, 'message' => 'โฟลเดอร์เก็บข้อมูลยังไม่ถูกสร้าง']);
            }

            $allFiles = array_diff(scandir($uploadPath), array('.', '..'));
            $deletedCount = 0;

            foreach ($allFiles as $file) {
                // Skip if it's a directory
                if (is_dir($uploadPath . $file)) continue;

                // If file is not in the list of used images, delete it
                if (!in_array($file, $usedImageNames)) {
                    if (@unlink($uploadPath . $file)) {
                        $deletedCount++;
                    }
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'deleted_count' => $deletedCount,
                'message' => "ล้างไฟล์ขยะสำเร็จ ลบไปทั้งหมด {$deletedCount} ไฟล์"
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);

        }
    }

    private function checkFamilyTable($db) {
        if (!$db->tableExists('tb_personnel_family')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'fam_fullname' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'fam_relationship' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'fam_age' => [
                    'type'       => 'INT',
                    'constraint' => 3,
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('tb_personnel_family');
        }
    }

    public function PersonnelFamilyAdd() {
        $db = \Config\Database::connect('personnel');
        $this->checkFamilyTable($db);
        
        $data = [
            'pers_id' => $this->request->getPost('pers_id'),
            'fam_fullname' => $this->request->getPost('name'),
            'fam_relationship' => $this->request->getPost('relation'),
            'fam_age' => $this->request->getPost('age'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('tb_personnel_family')->insert($data)) {
             return $this->response->setJSON(['status' => 'success', 'id' => $db->insertID()]);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelFamilyDelete() {
        $db = \Config\Database::connect('personnel');
        $id = $this->request->getPost('id');
        if ($db->table('tb_personnel_family')->where('id', $id)->delete()) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    private function checkEducationTable($db) {
        if (!$db->tableExists('tb_personnel_education')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'edu_level' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'edu_degree' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100', // Shortened for degree name (e.g. B.Sc.)
                ],
                'edu_major' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255', // New column for Major
                ],
                'edu_institute' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'edu_year' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '10',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('tb_personnel_education');
        } else {
             // Check if column exists, if not add it
             $fields = $db->getFieldNames('tb_personnel_education');
             if (!in_array('edu_major', $fields)) {
                 $forge = \Config\Database::forge('personnel');
                 $forge->addColumn('tb_personnel_education', [
                     'edu_major' => [
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'after' => 'edu_degree'
                     ]
                 ]);
             }
        }
    }

    private function checkLicenseColumns($db) {
        $fields = $db->getFieldNames('tb_personnel');
        $forge = \Config\Database::forge('personnel');
        
        $newFields = [];
        if (!in_array('pers_license_no', $fields)) {
            $newFields['pers_license_no'] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true];
        }
        if (!in_array('pers_license_issue', $fields)) {
            $newFields['pers_license_issue'] = ['type' => 'DATE', 'null' => true];
        }
        if (!in_array('pers_license_exp', $fields)) {
            $newFields['pers_license_exp'] = ['type' => 'DATE', 'null' => true];
        }

        if (!empty($newFields)) {
            $forge->addColumn('tb_personnel', $newFields);
        }
    }

    public function PersonnelEducationAdd() {
        $db = \Config\Database::connect('personnel');
        $this->checkEducationTable($db);
        
        $data = [
            'pers_id' => $this->request->getPost('pers_id'),
            'edu_level' => $this->request->getPost('edu_level'),
            'edu_degree' => $this->request->getPost('edu_degree'),
            'edu_major' => $this->request->getPost('edu_major'),
            'edu_institute' => $this->request->getPost('edu_institute'),
            'edu_year' => $this->request->getPost('edu_year'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('tb_personnel_education')->insert($data)) {
             return $this->response->setJSON(['status' => 'success', 'id' => $db->insertID()]);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function getEducationOptions() {
        $db = \Config\Database::connect('personnel');
        $builder = $db->table('tb_personnel_education');

        // 1. Fetch distinct existing values from DB
        $dbDegrees = $builder->select('edu_degree')->distinct()->where('edu_degree !=', '')->get()->getResultArray();
        $dbMajors = $builder->select('edu_major')->distinct()->where('edu_major !=', '')->get()->getResultArray();
        $dbInstitutes = $builder->select('edu_institute')->distinct()->where('edu_institute !=', '')->get()->getResultArray();

        // 2. Predefined Base Data (Thai Context) - Extensive List
        $defaultDegrees = [
            // ปริญญาตรี
            "ค.บ. (ครุศาสตรบัณฑิต)", "กศ.บ. (การศึกษาบัณฑิต)", "ศษ.บ. (ศึกษาศาสตรบัณฑิต)", 
            "ศศ.บ. (ศิลปศาสตรบัณฑิต)", "วท.บ. (วิทยาศาสตรบัณฑิต)", "บธ.บ. (บริหารธุรกิจบัณฑิต)", 
            "น.บ. (นิติศาสตรบัณฑิต)", "ร.บ. (รัฐศาสตรบัณฑิต)", "รป.บ. (รัฐประศาสนศาสตรบัณฑิต)", 
            "นิ.บ. (นิเทศศาสตรบัณฑิต)", "ว.บ. (วารสารศาสตรบัณฑิต)",
            "บช.บ. (บัญชีบัณฑิต)", "ศ.บ. (เศรษฐศาสตรบัณฑิต)", 
            "วศ.บ. (วิศวกรรมศาสตรบัณฑิต)", "สถ.บ. (สถาปัตยกรรมศาสตรบัณฑิต)", 
            "พย.บ. (พยาบาลศาสตรบัณฑิต)", "ส.บ. (สาธารณสุขศาสตรบัณฑิต)", "ภ.บ. (เภสัชศาสตรบัณฑิต)", 
            "ท.บ. (ทันตแพทยศาสตรบัณฑิต)", "พ.บ. (แพทยศาสตรบัณฑิต)", "สพ.บ. (สัตวแพทยศาสตรบัณฑิต)",
            "ก.บ. (เกษตรศาสตรบัณฑิต)", "วท.บ. (วนศาสตรบัณฑิต)", "ปม.บ. (ประมงบัณฑิต)",
            "อ.บ. (อักษรศาสตรบัณฑิต)", "ภ.ศ.บ. (ภาษาศาตรบัณฑิต)",
            "ศป.บ. (ศิลปบัณฑิต)", "ดศ.บ. (ดุริยางคศาสตรบัณฑิต)", 
            "อุต.บ. (อุตสาหกรรมศาสตรบัณฑิต)", "เทคโน.บ. (เทคโนโลยีบัณฑิต)",

            // ประกาศนียบัตรวิชาชีพ / บัณฑิต
            "ป.วค. (ประกาศนียบัตรวิชาชีพครู)", "ป.บัณฑิต (ประกาศนียบัตรบัณฑิต)", "ป.บ. (ประกาศนียบัตรบัณฑิต)",

            // ปริญญาโท
            "ค.ม. (ครุศาสตรมหาบัณฑิต)", "กศ.ม. (การศึกษามหาบัณฑิต)", "ศษ.ม. (ศึกษาศาสตรมหาบัณฑิต)",
            "ศศ.ม. (ศิลปศาสตรมหาบัณฑิต)", "วท.ม. (วิทยาศาสตรมหาบัณฑิต)", "บธ.ม. (บริหารธุรกิจมหาบัณฑิต)",
            "น.ม. (นิติศาสตรมหาบัณฑิต)", "ร.ม. (รัฐศาสตรมหาบัณฑิต)", "รป.ม. (รัฐประศาสนศาสตรมหาบัณฑิต)",
            "นิ.ม. (นิเทศศาสตรมหาบัณฑิต)", "วศ.ม. (วิศวกรรมศาสตรมหาบัณฑิต)", "สถ.ม. (สถาปัตยกรรมศาสตรมหาบัณฑิต)",
            "ศ.ม. (เศรษฐศาสตรมหาบัณฑิต)", "บช.ม. (บัญชีมหาบัณฑิต)", 
            "พย.ม. (พยาบาลศาสตรมหาบัณฑิต)", "ส.ม. (สาธารณสุขศาสตรมหาบัณฑิต)",

            // ปริญญาเอก
            "ปร.ด. (ปรัชญาดุษฎีบัณฑิต)", 
            "ค.ด. (ครุศาสตรดุษฎีบัณฑิต)", "กศ.ด. (การศึกษาดุษฎีบัณฑิต)", "ศษ.ด. (ศึกษาศาสตรดุษฎีบัณฑิต)",
            "ศศ.ด. (ศิลปศาสตรดุษฎีบัณฑิต)", "วท.ด. (วิทยาศาสตรดุษฎีบัณฑิต)", "บธ.ด. (บริหารธุรกิจดุษฎีบัณฑิต)",
            "น.ด. (นิติศาสตรดุษฎีบัณฑิต)", "ร.ด. (รัฐศาสตรดุษฎีบัณฑิต)", "รป.ด. (รัฐประศาสนศาสตรดุษฎีบัณฑิต)",
            "วศ.ด. (วิศวกรรมศาสตรดุษฎีบัณฑิต)", "ศ.ด. (เศรษฐศาสตรดุษฎีบัณฑิต)"
        ];

        $defaultMajors = [
            // --- สายครู / การศึกษา ---
            "การศึกษาปฐมวัย", "การประถมศึกษา", "การมัธยมศึกษา",
            "คอมพิวเตอร์ศึกษา", "เทคโนโลยีและสื่อสารการศึกษา", "เทคโนโลยีการศึกษา", "นวัตกรรมและเทคโนโลยีการศึกษา",
            "การวัดและประเมินผลการศึกษา", "วิจัยและประเมินผลการศึกษา", "จิตวิทยาและการแนะแนว", "จิตวิทยาการศึกษาและแนะแนว", "จิตวิทยาคลินิก",
            "การบริหารการศึกษา", "การจัดการการศึกษา", "ผู้นำทางการศึกษา",
            "หลักสูตรและการสอน", "การสอนภาษาไทย", "การสอนภาษาอังกฤษ", "การสอนวิทยาศาสตร์", "การสอนคณิตศาสตร์", "การสอนสังคมศึกษา",
            "พลศึกษา", "สุขศึกษา", "สุขศึกษาและพลศึกษา", "วิทยาศาสตร์การกีฬา",
            "ศิลปศึกษา", "ดนตรีศึกษา", "ดนตรีไทย", "ดนตรีสากล", "นาฏศิลป์", "นาฏศิลป์ไทย", "ศิลปะการแสดง",
            "บรรณารักษศาสตร์", "สารสนเทศศาสตร์", "บรรณารักษศาสตร์และสารสนเทศศาสตร์",
            "การศึกษาพิเศษ", "การศึกษานอกระบบ", "การศึกษาตลอดชีวิต",
            "คณิตศาสตร์ศึกษา", "วิทยาศาสตร์ศึกษา", "ฟิสิกส์", "เคมี", "ชีววิทยา", "วิทยาศาสตร์ทั่วไป", "โลกและดาราศาสตร์",
            "ภาษาไทย", "ภาษาอังกฤษ", "ภาษาจีน", "ภาษาญี่ปุ่น", "ภาษาเกาหลี", "ภาษาฝรั่งเศส", "ภาษาเยอรมัน", "ภาษาสเปน", "ภาษารัสเซีย",
            "สังคมศึกษา", "ศาสนาและปรัชญา", "พุทธศาสนา", "ประวัติศาสตร์", "ภูมิศาสตร์", 

            // --- สายวิทยาศาสตร์และเทคโนโลยี ---
            "วิทยาการคอมพิวเตอร์", "เทคโนโลยีสารสนเทศ", "วิศวกรรมซอฟต์แวร์", "วิทยาการข้อมูล", "มัลติมีเดียและแอนิเมชัน",
            "คณิตศาสตร์", "สถิติ", "สถิติประยุกต์", "คณิตศาสตร์ประกันภัย",
            "เคมี", "เคมีอุตสาหกรรม", "เคมีวิเคราะห์",
            "ชีววิทยา", "จุลชีววิทยา", "ชีวเคมี", "พันธุศาสตร์", "เทคโนโลยีชีวภาพ", "วิทยาศาสตร์สิ่งแวดล้อม",
            "ฟิสิกส์", "วัสดุศาสตร์", "ฟิสิกส์ประยุกต์", "ดาราศาสตร์",
            "วิทยาศาสตร์ทางทะเล", "วาริชศาสตร์", "วิทยาศาสตร์การอาหาร", "เทคโนโลยีทางอาหาร",

            // --- สายวิศวกรรมศาสตร์ ---
            "วิศวกรรมโยธา", "วิศวกรรมเครื่องกล", "วิศวกรรมไฟฟ้า", "วิศวกรรมอิเล็กทรอนิกส์", "วิศวกรรมโทรคมนาคม",
            "วิศวกรรมคอมพิวเตอร์", "วิศวกรรมอุตสาหการ", "วิศวกรรมเคมี", "วิศวกรรมสิ่งแวดล้อม",
            "วิศวกรรมเกษตร", "วิศวกรรมชลประทาน", "วิศวกรรมเหมืองแร่", "วิศวกรรมโลหการ",
            "วิศวกรรมยานยนต์", "วิศวกรรมการบินและอวกาศ", "วิศวกรรมเมคาทรอนิกส์", "วิศวกรรมระบบควบคุม",
            "วิศวกรรมความปลอดภัย", "วิศวกรรมชีวการแพทย์", "วิศวกรรมอาหาร", "วิศวกรรมสำรวจ",

            // --- สายเกษตรและประมง ---
            "เกษตรศาสตร์", "พืชไร่", "พืชสวน", "กีฏวิทยา", "โรคพืช", "ปฐพีวิทยา",
            "สัตวบาล", "สัตวศาสตร์", "เทคโนโลยีการผลิตสัตว์",
            "ประมง", "เพาะเลี้ยงสัตว์น้ำ", "ผลิตภัณฑ์ประมง", "ชีววิทยาประมง",
            "วนศาสตร์", "การจัดการทรัพยากรป่าไม้", "วนวัฒนวิทยา",

            // --- สายแพทย์และสาธารณสุข ---
            "แพทยศาสตร์", "ทันตแพทยศาสตร์", "เภสัชศาสตร์", "สัตวแพทยศาสตร์",
            "พยาบาลศาสตร์", "การพยาบาลผู้ใหญ่", "การพยาบาลเวชปฏิบัติชุมชน",
            "สาธารณสุขศาสตร์", "อาชีวอนามัยและความปลอดภัย", "อนามัยสิ่งแวดล้อม", "สุขศึกษาและพฤติกรรมศาสตร์",
            "เทคนิคการแพทย์", "กายภาพบำบัด", "รังสีเทคนิค", "กิจกรรมบำบัด", "ทัศนมาตรศาสตร์",
            "แพทย์แผนไทย", "แพทย์แผนไทยประยุกต์", "การแพทย์แผนจีน",

            // --- สายมนุษยศาสตร์และสังคมศาสตร์ ---
            "จิตวิทยา", "จิตวิทยาอุตสาหกรรมและองค์การ", "จิตวิทยาการปรึกษา",
            "นิติศาสตร์", "กฎหมายมหาชน", "กฎหมายธุรกิจ", "กฎหมายระหว่างประเทศ",
            "รัฐศาสตร์", "การปกครอง", "ความสัมพันธ์ระหว่างประเทศ", "สังคมวิทยาและมานุษยวิทยา",
            "รัฐประศาสนศาสตร์", "การบริหารงานภาครัฐ", "การจัดการสาธารณะ", "พัฒนาชุมชน", "สังคมสงเคราะห์ศาสตร์",
            "ภูมิศาสตร์และภูมิสารสนเทศ", "อาชญาวิทยา",
            "ภาษาศาสตร์", "วรรณคดี", "ไทยศึกษา", "เอเชียศึกษา", "ยุโรปศึกษา", "อเมริกันศึกษา",
            "ปรัชญา", "ศาสนาสากล",

            // --- สายบริหารธุรกิจและเศรษฐศาสตร์ ---
            "บริหารธุรกิจ", "การจัดการ", "การตลาด", "การเงิน", "การธนาคาร", "การบัญชี",
            "การจัดการทรัพยากรมนุษย์", "การบริหารองค์การ", "การจัดการโลจิสติกส์และโซ่อุปทาน",
            "ธุรกิจระหว่างประเทศ", "การประกอบการ", "ธุรกิจอสังหาริมทรัพย์",
            "เศรษฐศาสตร์", "เศรษฐศาสตร์ธุรกิจ", "เศรษฐศาสตร์การเงิน", "เศรษฐศาสตร์ระหว่างประเทศ",
            "การท่องเที่ยว", "การโรงแรม", "อุตสาหกรรมการบริการ", "การจัดการประชุมและนิทรรศการ (MICE)",
            "คหกรรมศาสตร์", "อาหารและโภชนาการ", "การพัฒนาผลิตภัณฑ์อาหาร",

            // --- สายสถาปัตยกรรมและศิลปกรรม ---
            "สถาปัตยกรรม", "สถาปัตยกรรมภายใน", "ภูมิสถาปัตยกรรม", "การออกแบบชุมชนเมือง",
            "ศิลปกรรม", "จิตรกรรม", "ประติมากรรม", "ภาพพิมพ์",
            "การออกแบบนิเทศศิลป์", "การออกแบบผลิตภัณฑ์", "การออกแบบเครื่องประดับ", "การออกแบบแฟชั่น", "เซรามิก",
            "ดุริยางคศิลป์", "ดนตรีบำบัด", "การแสดงขับร้อง",
            "นิเทศศาสตร์", "วารสารศาสตร์", "การสื่อสารมวลชน", "การประชาสัมพันธ์", "ภาพยนตร์และภาพนิ่ง", "สื่อสารการแสดง", "โฆษณา"
        ];
        
        // Use predefined institutes as base but allow merging
        $defaultInstitutes = [
            "จุฬาลงกรณ์มหาวิทยาลัย", "มหาวิทยาลัยมหิดล", "มหาวิทยาลัยเชียงใหม่", 
            "มหาวิทยาลัยธรรมศาสตร์", "มหาวิทยาลัยเกษตรศาสตร์", "มหาวิทยาลัยขอนแก่น", 
            "มหาวิทยาลัยสงขลานครินทร์", "มหาวิทยาลัยศิลปากร", "มหาวิทยาลัยศรีนครินทรวิโรฒ", 
            "มหาวิทยาลัยนเรศวร", "มหาวิทยาลัยบูรพา", "มหาวิทยาลัยมหาสารคาม", 
            "มหาวิทยาลัยอุบลราชธานี", "มหาวิทยาลัยแม่โจ้", "มหาวิทยาลัยแม่ฟ้าหลวง", 
            "มหาวิทยาลัยพะเยา", "มหาวิทยาลัยวลัยลักษณ์", "มหาวิทยาลัยทักษิณ",
            "มหาวิทยาลัยสุรนารี", "มหาวิทยาลัยรังสิต", "มหาวิทยาลัยกรุงเทพ", "มหาวิทยาลัยศรีปทุม",
            "มหาวิทยาลัยหอการค้าไทย", "มหาวิทยาลัยธุรกิจบัณฑิตย์",
            
            "สถาบันเทคโนโลยีพระจอมเกล้าเจ้าคุณทหารลาดกระบัง", 
            "มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี", 
            "มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ",

            "มหาวิทยาลัยราชภัฏเชียงใหม่", "มหาวิทยาลัยราชภัฏเชียงราย", "มหาวิทยาลัยราชภัฏลำปาง", 
            "มหาวิทยาลัยราชภัฏอุตรดิตถ์", "มหาวิทยาลัยราชภัฏพิบูลสงคราม", "มหาวิทยาลัยราชภัฏกำแพงเพชร", 
            "มหาวิทยาลัยราชภัฏนครสวรรค์", "มหาวิทยาลัยราชภัฏเพชรบูรณ์", "มหาวิทยาลัยราชภัฏสวนสุนันทา", 
            "มหาวิทยาลัยราชภัฏสวนดุสิต", "มหาวิทยาลัยราชภัฏพระนคร", "มหาวิทยาลัยราชภัฏจันทรเกษม", 
            "มหาวิทยาลัยราชภัฏบ้านสมเด็จเจ้าพระยา", "มหาวิทยาลัยราชภัฏธนบุรี", "มหาวิทยาลัยราชภัฏวไลยอลงกรณ์", 
            "มหาวิทยาลัยราชภัฏราชนครินทร์", "มหาวิทยาลัยราชภัฏเทพสตรี", "มหาวิทยาลัยราชภัฏรำไพพรรณี", 
            "มหาวิทยาลัยราชภัฏนครปฐม", "มหาวิทยาลัยราชภัฏกาญจนบุรี", "มหาวิทยาลัยราชภัฏหมู่บ้านจอมบึง", 
            "มหาวิทยาลัยราชภัฏเพชรบุรี", "มหาวิทยาลัยราชภัฏราชนครินทร์", "มหาวิทยาลัยราชภัฏร้อยเอ็ด",
            "มหาวิทยาลัยราชภัฏบุรีรัมย์", "มหาวิทยาลัยราชภัฏสุรินทร์", "มหาวิทยาลัยราชภัฏชัยภูมิ",
            "มหาวิทยาลัยราชภัฏนครราชสีมา", "มหาวิทยาลัยราชภัฏอุดรธานี", "มหาวิทยาลัยราชภัฏเลย",
            "มหาวิทยาลัยราชภัฏสกลนคร", "มหาวิทยาลัยราชภัฏกาฬสินธุ์", "มหาวิทยาลัยราชภัฏนครศรีธรรมราช",
            "มหาวิทยาลัยราชภัฏสุราษฎร์ธานี", "มหาวิทยาลัยราชภัฏภูเก็ต", "มหาวิทยาลัยราชภัฏสงขลา",
            "มหาวิทยาลัยราชภัฏยะลา",

            "มหาวิทยาลัยเทคโนโลยีราชมงคลธัญบุรี", "มหาวิทยาลัยเทคโนโลยีราชมงคลกรุงเทพ", 
            "มหาวิทยาลัยเทคโนโลยีราชมงคลตะวันออก", "มหาวิทยาลัยเทคโนโลยีราชมงคลพระนคร", 
            "มหาวิทยาลัยเทคโนโลยีราชมงคลรัตนโกสินทร์", "มหาวิทยาลัยเทคโนโลยีราชมงคลล้านนา", 
            "มหาวิทยาลัยเทคโนโลยีราชมงคลศรีวิชัย", "มหาวิทยาลัยเทคโนโลยีราชมงคลสุวรรณภูมิ", 
            "มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน",

            "มหาวิทยาลัยรามคำแหง", "มหาวิทยาลัยสุโขทัยธรรมาธิราช",
            "สถาบันการพลศึกษา", "วิทยาลัยพยาบาลบรมราชชนนี", 
            "สถาบันบัณฑิตพัฒนบริหารศาสตร์ (NIDA)", "สถาบันเทคโนโลยีไทย-ญี่ปุ่น", "สถาบันการจัดการปัญญาภิวัฒน์"
        ];

        // 3. Merge and Deduplicate
        // Helper util
        $merge = function($dbList, $defaultList, $key) {
            $merged = $defaultList;
            foreach ($dbList as $row) {
                if (!empty($row[$key]) && !in_array($row[$key], $merged)) {
                    $merged[] = $row[$key];
                }
            }
            sort($merged); // Sort alphabetically A-Z / ก-ฮ
            return $merged;
        };

        $finalDegrees = $merge($dbDegrees, $defaultDegrees, 'edu_degree');
        $finalMajors = $merge($dbMajors, $defaultMajors, 'edu_major');
        $finalInstitutes = $merge($dbInstitutes, $defaultInstitutes, 'edu_institute');

        return $this->response->setJSON([
            'degrees' => $finalDegrees,
            'majors' => $finalMajors,
            'institutes' => $finalInstitutes
        ]);
    }

    public function PersonnelEducationDelete() {
        $db = \Config\Database::connect('personnel');
        $id = $this->request->getPost('id');
        if ($db->table('tb_personnel_education')->where('id', $id)->delete()) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    private function checkWorkHistoryTable($db) {
        $forge = \Config\Database::forge('personnel');
        if (!$db->tableExists('tb_personnel_work_history')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                ],
                // วันที่มีผล
                'work_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                // รายการเปลี่ยนแปลง (เช่น บรรจุ, เลื่อนขั้น, ย้าย) - New for G.P.7
                'work_change_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ],
                 // ตำแหน่ง
                'work_position' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ],
                // ระดับ
                'work_level' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100', 
                    'null' => true,
                ],
                // สังกัด / ส่วนราชการ - New for G.P.7
                'work_location' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255', 
                    'null' => true,
                ],
                'work_salary' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'null' => true,
                ],
                 // เลขที่คำสั่ง
                'work_command_no' => [
                     'type' => 'VARCHAR',
                     'constraint' => '100',
                     'null' => true,
                ],
                 // ลงวันที่ (วันที่เซ็นคำสั่ง) - New for G.P.7
                'work_command_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'work_note' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ];
            $forge->addField($fields);
            $forge->addKey('id', true);
            $forge->addKey('pers_id');
            $forge->createTable('tb_personnel_work_history');
        } else {
             // Check for missing columns and add them (Migration-like)
             $fields = $db->getFieldNames('tb_personnel_work_history');
             if (!in_array('work_change_type', $fields)) {
                 $db->query("ALTER TABLE tb_personnel_work_history ADD COLUMN work_change_type VARCHAR(255) NULL AFTER work_date");
             }
             if (!in_array('work_location', $fields)) {
                 $db->query("ALTER TABLE tb_personnel_work_history ADD COLUMN work_location VARCHAR(255) NULL AFTER work_level");
             }
             if (!in_array('work_command_date', $fields)) {
                 $db->query("ALTER TABLE tb_personnel_work_history ADD COLUMN work_command_date DATE NULL AFTER work_command_no");
             }
        }
    }

    private function checkDecorationTable($db) {
        if (!$db->tableExists('tb_personnel_decorations')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'deco_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'deco_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'deco_gazette_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'deco_gazette_vol' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ],
                'deco_gazette_part' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ],
                'deco_gazette_page' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ],
                'deco_gazette_seq' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('pers_id');
            $forge->createTable('tb_personnel_decorations');
        }
    }

    public function PersonnelDecorationAdd() {
        $db = \Config\Database::connect('personnel');
        $this->checkDecorationTable($db);

        $convertDateToDb = function($dateStr) {
            if (empty($dateStr)) return null;
            if (strpos($dateStr, '/') !== false) {
                $parts = explode('/', $dateStr);
                if (count($parts) == 3) {
                    $d = $parts[0]; $m = $parts[1]; $y = (int)$parts[2];
                    if ($y > 2400) $y -= 543;
                    return "$y-$m-$d";
                }
            } elseif (strpos($dateStr, '-') !== false) {
                return $dateStr;
            }
            return null;
        };

        $data = [
            'pers_id' => $this->request->getPost('pers_id'),
            'deco_date' => $convertDateToDb($this->request->getPost('deco_date')),
            'deco_name' => $this->request->getPost('deco_name'),
            'deco_gazette_date' => $convertDateToDb($this->request->getPost('deco_gazette_date')),
            'deco_gazette_vol' => $this->request->getPost('deco_gazette_vol'),
            'deco_gazette_part' => $this->request->getPost('deco_gazette_part'),
            'deco_gazette_page' => $this->request->getPost('deco_gazette_page'),
            'deco_gazette_seq' => $this->request->getPost('deco_gazette_seq'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('tb_personnel_decorations')->insert($data)) {
             return $this->response->setJSON(['status' => 'success', 'id' => $db->insertID()]);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelDecorationDelete() {
        $db = \Config\Database::connect('personnel');
        $id = $this->request->getPost('id');
        if ($db->table('tb_personnel_decorations')->where('id', $id)->delete()) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    private function checkTrainingTable($db) {
        if (!$db->tableExists('tb_personnel_training')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'train_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'train_location' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ],
                'train_start_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'train_end_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'train_hours' => [
                    'type'       => 'INT',
                    'constraint' => 5,
                    'null' => true,
                ],
                'train_certificate' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('pers_id');
            $forge->createTable('tb_personnel_training');
        }
    }

    public function PersonnelTrainingAdd() {
        $db = \Config\Database::connect('personnel');
        $this->checkTrainingTable($db);

        $convertDateToDb = function($dateStr) {
            if (empty($dateStr)) return null;
            if (strpos($dateStr, '/') !== false) {
                $parts = explode('/', $dateStr);
                if (count($parts) == 3) {
                    $d = $parts[0]; $m = $parts[1]; $y = (int)$parts[2];
                    if ($y > 2400) $y -= 543;
                    return "$y-$m-$d";
                }
            } elseif (strpos($dateStr, '-') !== false) {
                return $dateStr;
            }
            return null;
        };

        $data = [
            'pers_id' => $this->request->getPost('pers_id'),
            'train_name' => $this->request->getPost('train_name'),
            'train_location' => $this->request->getPost('train_location'),
            'train_start_date' => $convertDateToDb($this->request->getPost('train_start_date')),
            'train_end_date' => $convertDateToDb($this->request->getPost('train_end_date')),
            'train_hours' => $this->request->getPost('train_hours'),
            'train_certificate' => $this->request->getPost('train_certificate'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('tb_personnel_training')->insert($data)) {
             return $this->response->setJSON(['status' => 'success', 'id' => $db->insertID()]);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelTrainingDelete() {
        $db = \Config\Database::connect('personnel');
        $id = $this->request->getPost('id');
        if ($db->table('tb_personnel_training')->where('id', $id)->delete()) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    private function checkLeaveTable($db) {
        if (!$db->tableExists('tb_personnel_leave')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'leave_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100', // ลาป่วย, ลากิจ, ลาพักผ่อน, etc.
                ],
                'leave_start_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'leave_end_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'leave_days' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,1',
                    'null' => true,
                ],
                'leave_note' => [
                    'type'       => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('pers_id');
            $forge->createTable('tb_personnel_leave');
        }
    }

    public function PersonnelLeaveAdd() {
        $db = \Config\Database::connect('personnel');
        $this->checkLeaveTable($db);

        $convertDateToDb = function($dateStr) {
            if (empty($dateStr)) return null;
            if (strpos($dateStr, '/') !== false) {
                $parts = explode('/', $dateStr);
                if (count($parts) == 3) {
                    $d = $parts[0]; $m = $parts[1]; $y = (int)$parts[2];
                    if ($y > 2400) $y -= 543;
                    return "$y-$m-$d";
                }
            } elseif (strpos($dateStr, '-') !== false) {
                return $dateStr;
            }
            return null;
        };

        $data = [
            'pers_id' => $this->request->getPost('pers_id'),
            'leave_type' => $this->request->getPost('leave_type'),
            'leave_start_date' => $convertDateToDb($this->request->getPost('leave_start_date')),
            'leave_end_date' => $convertDateToDb($this->request->getPost('leave_end_date')),
            'leave_days' => $this->request->getPost('leave_days'),
            'leave_note' => $this->request->getPost('leave_note'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('tb_personnel_leave')->insert($data)) {
             return $this->response->setJSON(['status' => 'success', 'id' => $db->insertID()]);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelLeaveDelete() {
        $db = \Config\Database::connect('personnel');
        $id = $this->request->getPost('id');
        if ($db->table('tb_personnel_leave')->where('id', $id)->delete()) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }



    public function PersonnelWorkHistoryAdd() {
        $db = \Config\Database::connect('personnel');
        $this->checkWorkHistoryTable($db);

        // Helper to convert Thai Date (DD/MM/YYYY) or ISO (YYYY-MM-DD) to DB Format (YYYY-MM-DD)
        $convertDateToDb = function($dateStr) {
            if (empty($dateStr)) return null;
            
            // Case 1: DD/MM/YYYY (Thai BE 25xx or AD 20xx)
            if (strpos($dateStr, '/') !== false) {
                $parts = explode('/', $dateStr);
                if (count($parts) == 3) {
                    $d = $parts[0];
                    $m = $parts[1];
                    $y = (int)$parts[2];
                    // If Year > 2400, assume Thai BE -> Convert to AD
                    if ($y > 2400) $y -= 543;
                    return "$y-$m-$d";
                }
            }
            // Case 2: YYYY-MM-DD (ISO)
            elseif (strpos($dateStr, '-') !== false) {
                return $dateStr;
            }
            
            return null;
        };

        $dateDb = $convertDateToDb($this->request->getPost('work_date'));
        $dateCmdDb = $convertDateToDb($this->request->getPost('work_command_date'));

        $data = [
            'pers_id' => $this->request->getPost('pers_id'),
            'work_date' => $dateDb,
            'work_change_type' => $this->request->getPost('work_change_type'),
            'work_position' => $this->request->getPost('work_position'),
            'work_level' => $this->request->getPost('work_level'),
            'work_location' => $this->request->getPost('work_location'),
            'work_salary' => $this->request->getPost('work_salary') ?: 0,
            'work_command_no' => $this->request->getPost('work_command_no'),
            'work_command_date' => $dateCmdDb,
            'work_note' => $this->request->getPost('work_note'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('tb_personnel_work_history')->insert($data)) {
             return $this->response->setJSON(['status' => 'success', 'id' => $db->insertID(), 'date_display' => $this->request->getPost('work_date')]);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelWorkHistoryUpdate() {
        $db = \Config\Database::connect('personnel');
        $id = $this->request->getPost('work_id');
        
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID missing']);

        $convertDateToDb = function($dateStr) {
            if (empty($dateStr)) return null;
            if (strpos($dateStr, '/') !== false) {
                $parts = explode('/', $dateStr);
                if (count($parts) == 3) {
                    $d = $parts[0];
                    $m = $parts[1];
                    $y = (int)$parts[2];
                    if ($y > 2400) $y -= 543;
                    return "$y-$m-$d";
                }
            } elseif (strpos($dateStr, '-') !== false) {
                return $dateStr;
            }
            return null;
        };

        $dateDb = $convertDateToDb($this->request->getPost('work_date'));
        $dateCmdDb = $convertDateToDb($this->request->getPost('work_command_date'));

        $data = [
            'work_date' => $dateDb,
            'work_change_type' => $this->request->getPost('work_change_type'),
            'work_position' => $this->request->getPost('work_position'),
            'work_level' => $this->request->getPost('work_level'),
            'work_location' => $this->request->getPost('work_location'),
            'work_salary' => $this->request->getPost('work_salary') ?: 0,
            'work_command_no' => $this->request->getPost('work_command_no'),
            'work_command_date' => $dateCmdDb,
            'work_note' => $this->request->getPost('work_note')
        ];

        if ($db->table('tb_personnel_work_history')->where('id', $id)->update($data)) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelLicenseUpdate() {
        $db = \Config\Database::connect('personnel');
        $pers_id = $this->request->getPost('pers_id');

        if (!$pers_id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID missing']);

        $convertDateToDb = function($dateStr) {
            if (empty($dateStr)) return null;
            if (strpos($dateStr, '/') !== false) {
                $parts = explode('/', $dateStr);
                if (count($parts) == 3) {
                    $d = $parts[0];
                    $m = $parts[1];
                    $y = (int)$parts[2];
                    if ($y > 2400) $y -= 543;
                    return "$y-$m-$d";
                }
            } elseif (strpos($dateStr, '-') !== false) {
                return $dateStr;
            }
            return null;
        };

        $data = [
            'pers_license_no' => $this->request->getPost('pers_license_no'),
            'pers_license_issue' => $convertDateToDb($this->request->getPost('pers_license_issue')),
            'pers_license_exp' => $convertDateToDb($this->request->getPost('pers_license_exp')),
        ];

        if ($db->table('tb_personnel')->where('pers_id', $pers_id)->update($data)) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    public function PersonnelWorkHistoryDelete() {
        $db = \Config\Database::connect('personnel');
        // Ensure table exists before trying to delete (though likely exists if we are here)
        $this->checkWorkHistoryTable($db);
        
        $id = $this->request->getPost('id');
        if ($db->table('tb_personnel_work_history')->where('id', $id)->delete()) {
             return $this->response->setJSON(['status' => 'success']);
        } else {
             return $this->response->setJSON(['status' => 'error']);
        }
    }

    private function getPersonnelFullData($id) { 
        $DB_Personnel = \Config\Database::connect('personnel');
        
        $personnel = $DB_Personnel->table('tb_personnel')
            ->select('tb_personnel.*, skjacth_skj.tb_position.posi_name, skjacth_skj.tb_position_main.work_name, skjacth_skj.tb_learning.lear_namethai')
            ->join('skjacth_skj.tb_position', 'skjacth_skj.tb_position.posi_id = tb_personnel.pers_position', 'left')
            ->join('skjacth_skj.tb_position_main', 'skjacth_skj.tb_position_main.work_id = tb_personnel.pers_workother_id', 'left')
            ->join('skjacth_skj.tb_learning', 'skjacth_skj.tb_learning.lear_id = tb_personnel.pers_learning', 'left')
            ->where('tb_personnel.pers_id', $id)
            ->get()->getRow();

        if (!$personnel) return null;

        $personnel->fullname = $personnel->pers_prefix . $personnel->pers_firstname . ' ' . $personnel->pers_lastname;

        $toDisplayDate = function($date) {
            if (empty($date)) return '-';
            $d = explode("-", $date);
            return (count($d) == 3) ? $d[2] . "/" . $d[1] . "/" . ($d[0] + 543) : $date;
        };

        $personnel->pers_britday_display = $toDisplayDate($personnel->pers_britday);

        // Fetch Addresses
        try {
            $personnel->addr_reg = $DB_Personnel->table('tb_personnel_addresses')->where(['pers_id' => $id, 'addr_type' => 'ทะเบียนบ้าน'])->get()->getRow();
            $personnel->addr_curr = $DB_Personnel->table('tb_personnel_addresses')->where(['pers_id' => $id, 'addr_type' => 'ปัจจุบัน'])->get()->getRow();
        } catch(\Exception $e) {
            $personnel->addr_reg = null;
            $personnel->addr_curr = null;
        }

        try { $this->checkFamilyTable($DB_Personnel); $personnel->family = $DB_Personnel->table('tb_personnel_family')->where('pers_id', $id)->get()->getResult(); } catch(\Exception $e) { $personnel->family = []; }
        try { $this->checkEducationTable($DB_Personnel); $personnel->education = $DB_Personnel->table('tb_personnel_education')->where('pers_id', $id)->orderBy('edu_year', 'ASC')->get()->getResult(); } catch(\Exception $e) { $personnel->education = []; }
        try { 
            $this->checkWorkHistoryTable($DB_Personnel); 
            $personnel->work_history = $DB_Personnel->table('tb_personnel_work_history')->where('pers_id', $id)->orderBy('work_date', 'ASC')->get()->getResult();
            foreach($personnel->work_history as &$w) { $w->date_display = $toDisplayDate($w->work_date); $w->command_date_display = $toDisplayDate($w->work_command_date); }
        } catch(\Exception $e) { $personnel->work_history = []; }
        try { 
            $this->checkDecorationTable($DB_Personnel); 
            $personnel->decorations = $DB_Personnel->table('tb_personnel_decorations')->where('pers_id', $id)->orderBy('deco_date', 'ASC')->get()->getResult();
            foreach($personnel->decorations as &$d) { $d->date_display = $toDisplayDate($d->deco_date); }
        } catch(\Exception $e) { $personnel->decorations = []; }
        try { 
            $this->checkTrainingTable($DB_Personnel); 
            $personnel->training = $DB_Personnel->table('tb_personnel_training')->where('pers_id', $id)->orderBy('train_start_date', 'ASC')->get()->getResult();
            foreach($personnel->training as &$t) { $t->start_display = $toDisplayDate($t->train_start_date); $t->end_display = $toDisplayDate($t->train_end_date); }
        } catch(\Exception $e) { $personnel->training = []; }
        try { 
            $this->checkLeaveTable($DB_Personnel); 
            $personnel->leave_history = $DB_Personnel->table('tb_personnel_leave')->where('pers_id', $id)->orderBy('leave_start_date', 'ASC')->get()->getResult();
            foreach($personnel->leave_history as &$l) { $l->start_display = $toDisplayDate($l->leave_start_date); $l->end_display = $toDisplayDate($l->leave_end_date); }
        } catch(\Exception $e) { $personnel->leave_history = []; }

        return $personnel;
    }

    public function PersonnelPDF($id) {
        $personnel = $this->getPersonnelFullData($id);
        if (!$personnel) return "ไม่พบข้อมูลบุคลากร";

        $data = ['p' => $personnel, 'title' => 'แบบ ก.พ. 7 - ' . $personnel->fullname];
        $html = view('Admin/AdminWorkPerson/AdminPersonPDF', $data);

        // mPDF will be loaded automatically by Composer's autoloader
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A4',
            'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 15, 'margin_bottom' => 15,
            'tempDir' => WRITEPATH . 'cache', // กำหนดโฟลเดอร์พักไฟล์ชั่วคราว
            'fontDir' => array_merge($fontDirs, [
                // Path to our fonts (already copied to vendor)
            ]),
            'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                    'BI' => 'THSarabunNew BoldItalic.ttf',
                ]
            ],
            'default_font' => 'thsarabun'
        ]);

        $mpdf->WriteHTML($html);
        $filename = 'GP7_' . $personnel->pers_id . '.pdf';
        return $this->response->setHeader('Content-Type', 'application/pdf')->setBody($mpdf->Output($filename, 'I'));
    }

    // ===== DOCUMENT MANAGEMENT METHODS =====
    
    /**
     * ตรวจสอบและสร้างตาราง tb_personnel_documents ถ้ายังไม่มี
     */
    private function checkDocumentsTable($db) {
        if (!$db->tableExists('tb_personnel_documents')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pers_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'doc_category' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'comment'    => 'personal, license, education, work_order, training, decoration, other',
                ],
                'doc_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'comment'    => 'id_card, house_reg, teacher_license, etc.',
                ],
                'doc_title' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'file_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'file_path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '500',
                ],
                'file_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'file_size' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'related_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'comment'    => 'FK ถ้าเชื่อมกับ education_id, training_id ฯลฯ',
                ],
                'doc_date' => [
                    'type'    => 'DATE',
                    'null'    => true,
                ],
                'doc_reference' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'doc_note' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                ],
                'uploaded_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('pers_id');
            $forge->addKey(['doc_category', 'doc_type']);
            $forge->createTable('tb_personnel_documents');
        }
    }

    /**
     * อัปโหลดเอกสารบุคลากร
     */
    public function PersonnelDocUpload() {
        $session = session();
        $pers_id = $this->request->getPost('pers_id');
        
        if (!$this->canEditPersonnel($pers_id)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์อัปโหลดเอกสารนี้']);
        }

        $DB_Personnel = \Config\Database::connect('personnel');
        $this->checkDocumentsTable($DB_Personnel);
        $DBDoc = $DB_Personnel->table('tb_personnel_documents');

        $file = $this->request->getFile('document');
        $docType = $this->request->getPost('doc_type');
        $docCategory = $this->request->getPost('doc_category') ?? 'personal';
        $docTitle = $this->request->getPost('doc_title') ?? '';
        $docDate = $this->request->getPost('doc_date') ?? null;
        $docReference = $this->request->getPost('doc_reference') ?? '';
        $docNote = $this->request->getPost('doc_note') ?? '';
        $relatedId = $this->request->getPost('related_id') ?? null;

        // Validate file
        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ไม่ได้เลือกไฟล์หรือไฟล์ไม่ถูกต้อง']);
        }

        // Check file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ขนาดไฟล์เกิน 5MB']);
        }

        // Check file type
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ประเภทไฟล์ไม่ถูกต้อง (รองรับ PDF, JPG, PNG)']);
        }

        // Create upload directory
        $uploadPath = ROOTPATH . 'uploads/admin/Personnel/documents/' . $pers_id . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // For single-type documents (id_card, house_reg, etc.), delete existing file first
        $singleTypeDocuments = ['id_card', 'house_reg', 'name_change', 'teacher_license', 'admin_license'];
        if (in_array($docType, $singleTypeDocuments)) {
            $existing = $DBDoc->where('pers_id', $pers_id)->where('doc_type', $docType)->get()->getRow();
            if ($existing) {
                // Delete old file
                $oldPath = ROOTPATH . $existing->file_path;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
                // Delete DB record
                $DBDoc->where('id', $existing->id)->delete();
            }
        }

        // Convert date format if provided
        if ($docDate) {
            $docDate = $this->convertThaiDateToSQL($docDate);
        }

        // Move file
        $newName = $docType . '_' . time() . '_' . $file->getRandomName();
        $file->move($uploadPath, $newName);

        $relativePath = 'uploads/admin/Personnel/documents/' . $pers_id . '/' . $newName;

        // Insert to database
        $data = [
            'pers_id'       => $pers_id,
            'doc_category'  => $docCategory,
            'doc_type'      => $docType,
            'doc_title'     => $docTitle,
            'file_name'     => $file->getClientName(),
            'file_path'     => $relativePath,
            'file_type'     => pathinfo($newName, PATHINFO_EXTENSION),
            'file_size'     => $file->getSize(),
            'related_id'    => $relatedId,
            'doc_date'      => $docDate,
            'doc_reference' => $docReference,
            'doc_note'      => $docNote,
            'uploaded_by'   => $session->get('id'),
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        if ($DBDoc->insert($data)) {
            $insertId = $DB_Personnel->insertID();
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'อัปโหลดเอกสารสำเร็จ',
                'doc_id'  => $insertId,
                'file_name' => $file->getClientName()
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }
    }

    /**
     * ดูเอกสารบุคลากร
     */
    public function PersonnelDocView($docId) {
        $DB_Personnel = \Config\Database::connect('personnel');
        $this->checkDocumentsTable($DB_Personnel);
        $DBDoc = $DB_Personnel->table('tb_personnel_documents');

        $doc = $DBDoc->where('id', $docId)->get()->getRow();
        if (!$doc) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'ไม่พบเอกสาร']);
        }

        $filePath = ROOTPATH . $doc->file_path;
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'ไม่พบไฟล์เอกสาร']);
        }

        // Determine content type
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $contentType = match($extension) {
            'pdf'  => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            default => 'application/octet-stream',
        };

        return $this->response
            ->setHeader('Content-Type', $contentType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $doc->file_name . '"')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * ลบเอกสารบุคลากร
     */
    public function PersonnelDocDelete() {
        $session = session();
        $docId = $this->request->getPost('doc_id');

        $DB_Personnel = \Config\Database::connect('personnel');
        $this->checkDocumentsTable($DB_Personnel);
        $DBDoc = $DB_Personnel->table('tb_personnel_documents');

        $doc = $DBDoc->where('id', $docId)->get()->getRow();
        if (!$doc) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'ไม่พบเอกสาร']);
        }

        // Check permission
        if (!$this->canEditPersonnel($doc->pers_id)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ลบเอกสารนี้']);
        }

        // Delete file
        $filePath = ROOTPATH . $doc->file_path;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        // Delete DB record
        if ($DBDoc->where('id', $docId)->delete()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบเอกสารสำเร็จ']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบเอกสารได้']);
        }
    }

    /**
     * ดึงรายการเอกสารตาม pers_id
     */
    public function PersonnelDocList($persId) {
        $DB_Personnel = \Config\Database::connect('personnel');
        $this->checkDocumentsTable($DB_Personnel);
        $DBDoc = $DB_Personnel->table('tb_personnel_documents');

        $docs = $DBDoc->where('pers_id', $persId)->orderBy('doc_category')->orderBy('created_at', 'DESC')->get()->getResult();
        return $this->response->setJSON($docs);
    }

    /**
     * Helper: แปลงวันที่ไทย (DD/MM/YYYY+543) เป็น SQL Date (YYYY-MM-DD)
     */
    private function convertThaiDateToSQL($dateStr) {
        if (empty($dateStr)) return null;
        $normValue = str_replace('-', '/', $dateStr);
        $d = explode("/", $normValue);
        if (count($d) != 3) return null;
        
        if ((int)$d[0] > 1000) { // YYYY/MM/DD
            $year = (int)$d[0]; $month = (int)$d[1]; $day = (int)$d[2];
        } else { // DD/MM/YYYY
            $year = (int)$d[2]; $month = (int)$d[1]; $day = (int)$d[0];
        }
        if ($year > 2400) $year -= 543;
        return sprintf("%04d-%02d-%02d", $year, $month, $day);
    }

    /**
     * ดึงสรุปการลา/มาทำงานของบุคลากรตามช่วงวันที่ระบุจาก Attendance System
     */
    public function PersonnelAttendanceSummary($persId) {
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBAttendance = $DB_Personnel->table('tb_personnel_attendance');
        
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        // Convert Thai dates to SQL format
        $startDate = $this->convertThaiDateToSQL($start);
        $endDate = $this->convertThaiDateToSQL($end);

        // Fallback to current year if dates are missing
        if (!$startDate) $startDate = date('Y-01-01');
        if (!$endDate) $endDate = date('Y-12-31');
        
        // Get leave records
        $leaveRecords = $DBAttendance
            ->where('att_person_id', $persId)
            ->where('att_date >=', $startDate)
            ->where('att_date <=', $endDate)
            ->whereIn('att_status', ['ลากิจ', 'ลาป่วย', 'ไปราชการ', 'ขาด', 'อื่นๆ'])
            ->orderBy('att_date', 'DESC')
            ->get()->getResult();

        foreach ($leaveRecords as &$l) {
            if (!empty($l->att_date)) {
                $p1 = explode('-', $l->att_date);
                $l->date_display = (count($p1) == 3) ? $p1[2] . '/' . $p1[1] . '/' . ($p1[0] + 543) : $l->att_date;
            } else { 
                $l->date_display = '-'; 
            }
        }

        // Get summary stats
        $DBAttendanceStats = $DB_Personnel->table('tb_personnel_attendance');
        $summaryRaw = $DBAttendanceStats
            ->select("
                SUM(CASE WHEN att_status = 'มา' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN att_status = 'สาย' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN att_status = 'ลาป่วย' THEN 1 ELSE 0 END) as sick,
                SUM(CASE WHEN att_status = 'ลากิจ' THEN 1 ELSE 0 END) as personal,
                SUM(CASE WHEN att_status = 'ไปราชการ' THEN 1 ELSE 0 END) as official,
                SUM(CASE WHEN att_status = 'ขาด' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN att_status = 'อื่นๆ' THEN 1 ELSE 0 END) as other
            ")
            ->where('att_person_id', $persId)
            ->where('att_date >=', $startDate)
            ->where('att_date <=', $endDate)
            ->get()->getRow();

        return $this->response->setJSON([
            'start' => $startDate,
            'end' => $endDate,
            'summary' => [
                'present' => (int)($summaryRaw->present ?? 0) + (int)($summaryRaw->late ?? 0),
                'sick' => (int)($summaryRaw->sick ?? 0),
                'personal' => (int)($summaryRaw->personal ?? 0),
                'official' => (int)($summaryRaw->official ?? 0),
                'absent' => (int)($summaryRaw->absent ?? 0),
                'other' => (int)($summaryRaw->other ?? 0),
            ],
            'records' => $leaveRecords
        ]);
    }

    /**
     * Helper: ตรวจสอบและสร้างคอลัมน์ pers_faction ใน tb_personnel
     */
    private function checkFactionColumn($db) {
        if (!$db->fieldExists('pers_faction', 'tb_personnel')) {
            $forge = \Config\Database::forge('personnel');
            $forge->addColumn('tb_personnel', [
                'pers_faction' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'pers_academic'
                ]
            ]);
        }
    }

}

