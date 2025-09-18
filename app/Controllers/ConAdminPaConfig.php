<?php

namespace App\Controllers;

class ConAdminPaConfig extends BaseController
{
    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $data['uri'] = service('uri'); 
        return $data;
    }

    public function index()
    {
        $session = session();
        // Check if user is admin (using 'rloes' session variable)
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้!');
        }

        $data = $this->DataMain(); // Call DataMain to get common data like full_url

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        // Fetch all evaluators (potential assessors)
        $evaluators = $db_pa_evaluation->table('tb_evaluators')
                                  ->orderBy('e_first_name', 'ASC')
                                  ->get()->getResultArray();

        $db_skj = \Config\Database::connect('skj'); // Added missing connection

        // Fetch all positions
        $positions = $db_skj->table('tb_position')
                            ->select('posi_id, posi_name')
                            ->orderBy('posi_name', 'ASC')
                            ->get()->getResultArray();

        // Fetch all learning groups
        $learningGroups = $db_skj->table('tb_learning')
                                 ->select('lear_id, lear_namethai')
                                 ->orderBy('lear_namethai', 'ASC')
                                 ->get()->getResultArray();

        // Fetch existing assessor scopes
        $assessorScopes = $db_pa_evaluation->table('tb_assessor_scope')
                                           ->get()->getResultArray();

        $data = [
            'title' => 'ตั้งค่าผู้ประเมิน PA',
            'description' => 'กำหนดขอบเขตการประเมินสำหรับผู้ประเมินแต่ละคน',
            'evaluators' => $evaluators,
            'positions' => $positions,
            'learningGroups' => $learningGroups,
            'assessorScopes' => $assessorScopes,
            'UrlMenuMain' => 'Admin', // Assuming this is for admin menu highlighting
            'UrlMenuSub' => 'PaConfig', // Assuming this is for admin menu highlighting
            'uri' => service('uri'), // Pass uri service to view
        ];

        return view('Admin/AdminPaEvaluation/index', $data);
    }

    public function report()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้!');
        }

        $data = $this->DataMain();
        $data['title'] = 'รายงานประเมิน PA';
        $data['description'] = 'รายงานผลการประเมิน PA';
        $data['UrlMenuMain'] = 'Admin';
        $data['UrlMenuSub'] = 'PaReport';

        return view('Admin/AdminPaEvaluation/report', $data);
    }

    public function saveScope()
    {
        $session = session();
         $data = $this->DataMain();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

                $assessor_e_id = $this->request->getPost('assessor_e_id');
        $scope_posi_id = $this->request->getPost('scope_posi_id');
        $scope_lear_id = $this->request->getPost('scope_lear_id');

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_assessor_scope');

        // Check if a similar entry already exists
        $existingScope = $table->where('assessor_e_id', $assessor_e_id)
                               ->where('scope_posi_id', $scope_posi_id === '' ? null : $scope_posi_id)
                               ->where('scope_lear_id', $scope_lear_id === '' ? null : $scope_lear_id)
                               ->get()->getRowArray();

        if ($existingScope) {
            $session->setFlashdata('Error', 'การตั้งค่านี้มีอยู่แล้ว!');
        } else {
            $data = [
                'assessor_e_id' => $assessor_e_id,
                'scope_posi_id' => $scope_posi_id === '' ? null : $scope_posi_id, // Handle empty string for NULL
                'scope_lear_id' => $scope_lear_id === '' ? null : $scope_lear_id, // Handle empty string for NULL
            ];
            if ($table->insert($data)) {
                $session->setFlashdata('Success', 'บันทึกการตั้งค่าสำเร็จ!');
            } else {
                $session->setFlashdata('Error', 'เกิดข้อผิดพลาดในการบันทึก!');
            }
        }

        return redirect()->to(base_url('Admin/PaConfig'));
    }

    public function deleteScope($id)
    {
        $session = session();
         $data = $this->DataMain();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_assessor_scope');

        if ($table->delete(['id' => $id])) {
            $session->setFlashdata('Success', 'ลบการตั้งค่าสำเร็จ!');
        } else {
            $session->setFlashdata('Error', 'เกิดข้อผิดพลาดในการลบ!');
        }

        return redirect()->to(base_url('Admin/PaConfig'));
    }

    public function addEvaluator()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_evaluators');

        // Basic validation to check if username already exists
        $existingUser = $table->where('e_Username', $this->request->getPost('e_Username'))->get()->getRow();
        if ($existingUser) {
            $session->setFlashdata('Error', 'ชื่อผู้ใช้งานนี้มีอยู่แล้วในระบบ!');
            return redirect()->to(base_url('Admin/PaConfig'));
        }

        // Hash the password before saving
        $password = $this->request->getPost('e_Password');
        if (empty($password)) {
            $session->setFlashdata('Error', 'รหัสผ่านห้ามเป็นค่าว่าง');
            return redirect()->to(base_url('Admin/PaConfig'));
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'e_id' => uniqid('e'), // Generate a unique ID
            'e_first_name' => $this->request->getPost('e_first_name'),
            'e_last_name' => $this->request->getPost('e_last_name'),
            'e_position' => $this->request->getPost('e_position'),
            'e_academic_standing' => $this->request->getPost('e_academic_standing'),
            'e_organization' => $this->request->getPost('e_organization'),
            'e_Username' => $this->request->getPost('e_Username'),
            'e_Password' => $hashedPassword,
        ];

        if ($table->insert($data)) {
            $session->setFlashdata('Success', 'เพิ่มข้อมูลผู้ประเมินสำเร็จ!');
        } else {
            $session->setFlashdata('Error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลผู้ประเมิน!');
        }

        return redirect()->to(base_url('Admin/PaConfig'));
    }

    public function rubricItems()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $rubrics = $db_pa_evaluation->table('tb_rubric_items')
                                    ->orderBy('ri_part', 'ASC')
                                    ->orderBy('ri_item_number', 'ASC')
                                    ->get()->getResultArray();

        $data = [
            'title' => 'จัดการหัวข้อการประเมิน PA',
            'description' => 'เพิ่ม ลบ แก้ไข หัวข้อการประเมินสำหรับแบบประเมิน PA',
            'rubrics' => $rubrics,
            'UrlMenuMain' => 'Admin', 
            'UrlMenuSub' => 'PaRubrics', // New identifier for the menu
            'uri' => service('uri'),
        ];

        return view('Admin/AdminPaEvaluation/rubric_items', $data);
    }

    public function addRubricItem()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ดำเนินการนี้!']);
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_rubric_items');

        $academic_standing = $this->request->getPost('ri_academic_standing');

        $data = [
            'ri_id' => uniqid('ri'), // Generate a unique ID
            'ri_part' => $this->request->getPost('ri_part'),
            'ri_domain' => $this->request->getPost('ri_domain'),
            'ri_item_number' => $this->request->getPost('ri_item_number'),
            'ri_item_description' => $this->request->getPost('ri_item_description'),
            'ri_expected_level_description' => $this->request->getPost('ri_expected_level_description'),
            'ri_academic_standing' => $academic_standing === '' ? null : $academic_standing, // Set to null if empty
        ];

        // Basic validation
        if (empty($data['ri_part']) || empty($data['ri_item_number']) || empty($data['ri_item_description'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณากรอกข้อมูลที่จำเป็น (ส่วนที่, ลำดับ, รายละเอียด)']);
        }

        // Check for duplicate entry
        $existingItem = $table->where('ri_part', $data['ri_part'])
                              ->where('ri_domain', $data['ri_domain'])
                              ->where('ri_item_number', $data['ri_item_number'])
                              ->where('ri_academic_standing', $data['ri_academic_standing']) // Check academic standing
                              ->get()->getRow();

        if ($existingItem) {
            return $this->response->setJSON(['success' => false, 'message' => 'หัวข้อการประเมินนี้มีอยู่แล้ว!']);
        }

        if ($table->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'เพิ่มหัวข้อการประเมินสำเร็จ!']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล!']);
        }
    }

    public function updateRubricItem()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ดำเนินการนี้!']);
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_rubric_items');

        $ri_id = $this->request->getPost('ri_id');
        $academic_standing = $this->request->getPost('ri_academic_standing');

        $data = [
            'ri_part' => $this->request->getPost('ri_part'),
            'ri_domain' => $this->request->getPost('ri_domain'),
            'ri_item_number' => $this->request->getPost('ri_item_number'),
            'ri_item_description' => $this->request->getPost('ri_item_description'),
            'ri_expected_level_description' => $this->request->getPost('ri_expected_level_description'),
            'ri_academic_standing' => $academic_standing === '' ? null : $academic_standing,
        ];

        // Basic validation
        if (empty($ri_id) || empty($data['ri_part']) || empty($data['ri_item_number']) || empty($data['ri_item_description'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณากรอกข้อมูลที่จำเป็น (ID, ส่วนที่, ลำดับ, รายละเอียด)']);
        }

        // Check for duplicate entry (excluding the current item being updated)
        $existingItem = $table->where('ri_part', $data['ri_part'])
                              ->where('ri_domain', $data['ri_domain'])
                              ->where('ri_item_number', $data['ri_item_number'])
                              ->where('ri_academic_standing', $data['ri_academic_standing'])
                              ->where('ri_id !=', $ri_id) // Exclude current item
                              ->get()->getRow();

        if ($existingItem) {
            return $this->response->setJSON(['success' => false, 'message' => 'หัวข้อการประเมินนี้มีอยู่แล้ว!']);
        }

        if ($table->where('ri_id', $ri_id)->update($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตหัวข้อการประเมินสำเร็จ!']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล หรือไม่มีการเปลี่ยนแปลงข้อมูล!']);
        }
    }

    public function deleteRubricItem()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ดำเนินการนี้!']);
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_rubric_items');

        $ri_id = $this->request->getPost('ri_id');

        // Basic validation
        if (empty($ri_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบ ID หัวข้อการประเมินที่ต้องการลบ!']);
        }

        if ($table->delete(['ri_id' => $ri_id])) {
            // Also delete related scores from tb_evaluator_scores
            $evaluatorScoresTable = $db_pa_evaluation->table('tb_evaluator_scores');
            $evaluatorScoresTable->where('es_rubric_item_id', $ri_id)->delete();

            return $this->response->setJSON(['success' => true, 'message' => 'ลบหัวข้อการประเมินและข้อมูลที่เกี่ยวข้องสำเร็จ!']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล!']);
        }
    }
}
