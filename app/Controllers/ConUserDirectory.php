<?php
namespace App\Controllers;

/**
 * Controller สำหรับทำเนียบครูและบุคลากรทางการศึกษา (Public facing)
 */
class ConUserDirectory extends BaseController
{  
    protected $db_personnel;
    protected $db_skj;

    public function __construct(){
        $this->db_personnel = \Config\Database::connect('personnel');
        $this->db_skj = \Config\Database::connect('skj');
    }

    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri'); 
        return $data;
    }

    /**
     * หน้าหลักทำเนียบครู - แสดงครูทั้งหมดแบ่งตามกลุ่ม
     */
    public function index()
    {
        $data = $this->DataMain();
        $data['title'] = "ทำเนียบครูและบุคลากร";
        $data['description'] = "ทำเนียบครูและบุคลากรทางการศึกษา โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์";
        $data['UrlMenuMain'] = 'Directory';
        $data['UrlMenuSub'] = '';

        $DBPers = $this->db_personnel->table('tb_personnel');
        $DBLear = $this->db_skj->table('tb_learning');
        $DBPosi = $this->db_skj->table('tb_position');

        // ดึงข้อมูลผู้บริหาร
        $data['Executive'] = $DBPers
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_img, pers_academic, posi_name, pers_position, pers_groupleade')
            ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
            ->where('pers_status', "กำลังใช้งาน")
            ->where('pers_position <=', 'posi_002')
            ->orderBy('pers_position', 'ASC')
            ->orderBy('pers_numberGroup', 'ASC')
            ->get()->getResult();

        // ดึงข้อมูลกลุ่มสาระการเรียนรู้พร้อมจำนวนครู
        $data['LearningGroups'] = $DBLear
            ->select('skjacth_skj.tb_learning.lear_id, skjacth_skj.tb_learning.lear_namethai, COUNT(skjacth_personnel.tb_personnel.pers_id) AS teacher_count')
            ->join('skjacth_personnel.tb_personnel', 'skjacth_skj.tb_learning.lear_id = skjacth_personnel.tb_personnel.pers_learning', 'left')
            ->where('skjacth_personnel.tb_personnel.pers_status', "กำลังใช้งาน")
            ->groupBy('skjacth_skj.tb_learning.lear_id, skjacth_skj.tb_learning.lear_namethai')
            ->orderBy('lear_id')
            ->get()->getResult();

        // ดึงข้อมูลครูทั้งหมด (กรองเฉพาะครูที่กำลังใช้งาน)
        $data['AllTeachers'] = $DBPers
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_img, pers_academic, posi_name, pers_learning, lear_namethai, pers_groupleade, pers_position')
            ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
            ->join('skjacth_skj.tb_learning','skjacth_skj.tb_learning.lear_id = skjacth_personnel.tb_personnel.pers_learning', 'left')
            ->where('pers_status', "กำลังใช้งาน")
            ->where('pers_position >=', 'posi_003')
            ->where('pers_position <=', 'posi_006')
            ->orderBy('pers_learning', 'ASC')
            ->orderBy('pers_numberGroup', 'ASC')
            ->get()->getResult();

        // ดึงข้อมูลบุคลากรสนับสนุน
        $data['SupportStaff'] = $DBPers
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_img, posi_name, pers_groupleade')
            ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
            ->where('pers_status', "กำลังใช้งาน")
            ->where('pers_position >=', 'posi_007')
            ->orderBy('pers_position', 'ASC')
            ->orderBy('pers_numberGroup', 'ASC')
            ->get()->getResult();

        return view('User/UserDirectory/UserDirectoryMain', $data);
    }

    /**
     * หน้ารายละเอียดครูแต่ละคน
     */
    public function detail($pers_id)
    {
        $data = $this->DataMain();
        $data['UrlMenuMain'] = 'Directory';
        $data['UrlMenuSub'] = 'Detail';

        $DBPers = $this->db_personnel->table('tb_personnel');

        // ดึงข้อมูลครู
        $teacher = $DBPers
            ->select('tb_personnel.*, posi_name, lear_namethai')
            ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
            ->join('skjacth_skj.tb_learning','skjacth_skj.tb_learning.lear_id = skjacth_personnel.tb_personnel.pers_learning', 'left')
            ->where('pers_id', $pers_id)
            ->where('pers_status', "กำลังใช้งาน")
            ->get()->getRow();

        if (!$teacher) {
            return redirect()->to(base_url('directory'))->with('Error', 'ไม่พบข้อมูลบุคลากร');
        }

        $data['teacher'] = $teacher;
        $data['title'] = $teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname . " | ทำเนียบครู";
        $data['description'] = "ข้อมูล" . $teacher->posi_name . " " . $teacher->pers_prefix . $teacher->pers_firstname . ' ' . $teacher->pers_lastname;

        // ดึงข้อมูลการศึกษา
        try {
            $data['education'] = $this->db_personnel->table('tb_personnel_education')
                ->where('pers_id', $pers_id)
                ->orderBy('edu_year', 'DESC')
                ->get()->getResult();
        } catch (\Exception $e) {
            $data['education'] = [];
        }

        return view('User/UserDirectory/UserDirectoryDetail', $data);
    }

    /**
     * กรองครูตามกลุ่มสาระ (AJAX)
     */
    public function getByLearning($lear_id = null)
    {
        $DBPers = $this->db_personnel->table('tb_personnel');

        $query = $DBPers
            ->select('pers_id, pers_prefix, pers_firstname, pers_lastname, pers_img, pers_academic, posi_name, lear_namethai')
            ->join('skjacth_skj.tb_position','skjacth_skj.tb_position.posi_id = skjacth_personnel.tb_personnel.pers_position')
            ->join('skjacth_skj.tb_learning','skjacth_skj.tb_learning.lear_id = skjacth_personnel.tb_personnel.pers_learning', 'left')
            ->where('pers_status', "กำลังใช้งาน")
            ->where('pers_position >=', 'posi_003')
            ->where('pers_position <=', 'posi_006');

        if ($lear_id && $lear_id !== 'all') {
            $query->where('pers_learning', $lear_id);
        }

        $teachers = $query
            ->orderBy('pers_numberGroup', 'ASC')
            ->get()->getResult();

        return $this->response->setJSON(['status' => 'success', 'data' => $teachers]);
    }

    /**
     * บันทึกข้อมูลส่วนตัวพื้นฐาน (AJAX)
     */
    public function saveProfile()
    {
        $pers_id = $this->request->getPost('pers_id');

        // ตรวจสอบสิทธิ์เจ้าของข้อมูล
        if (!$this->canEditPersonnel($pers_id)) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error', 
                'message' => 'คุณไม่มีสิทธิ์แก้ไขข้อมูลชุดนี้'
            ]);
        }

        $DBPers = $this->db_personnel->table('tb_personnel');

        $data = [
            'pers_prefix'    => $this->request->getPost('pers_prefix'),
            'pers_firstname' => $this->request->getPost('pers_firstname'),
            'pers_lastname'  => $this->request->getPost('pers_lastname'),
            'pers_nickname'  => $this->request->getPost('pers_nickname'),
            'pers_britday'   => $this->request->getPost('pers_britday'),
            'pers_phone'     => $this->request->getPost('pers_phone'),
            'pers_facebook'  => $this->request->getPost('pers_facebook'),
            'pers_line'      => $this->request->getPost('pers_line'),
            'pers_instagram' => $this->request->getPost('pers_instagram'),
            'pers_youtube'   => $this->request->getPost('pers_youtube'),
            'pers_nationality' => $this->request->getPost('pers_nationality'),
            'pers_race'      => $this->request->getPost('pers_race'),
            'pers_religion'  => $this->request->getPost('pers_religion'),
            'pers_marital_status' => $this->request->getPost('pers_marital_status'),
            'pers_blood_type' => $this->request->getPost('pers_blood_type'),
            'pers_address'   => $this->request->getPost('pers_address'),
        ];

        if ($DBPers->where('pers_id', $pers_id)->update($data)) {
            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error', 
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ภายหลัง'
            ]);
        }
    }
}
