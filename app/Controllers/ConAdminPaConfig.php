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
                            ->orderBy('posi_id', 'ASC')
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

        $db_default = \Config\Database::connect(); // Default connection
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $db_skj = \Config\Database::connect('skj');

        // Get current fiscal year
        $current_month = date('m');
        $current_year_ad = date('Y');
        $fiscal_year = ($current_month >= 10) ? $current_year_ad + 1 : $current_year_ad;
        $fiscal_year_be = $fiscal_year + 543;

        // 1. Fetch all necessary lookup tables at once
        $assessor_scopes = $db_pa_evaluation->table('tb_assessor_scope')->get()->getResultArray();
        $all_evaluators = $db_pa_evaluation->table('tb_evaluators')->get()->getResultArray();
        $evaluators_map = array_column($all_evaluators, null, 'e_id');
        $learning_groups_raw = $db_skj->table('tb_learning')->get()->getResultArray();
        $learning_groups = array_column($learning_groups_raw, 'lear_namethai', 'lear_id');
        $positions_raw = $db_skj->table('tb_position')->get()->getResultArray();
        $positions = array_column($positions_raw, 'posi_name', 'posi_id');

        // 2. Get the list of teachers (personnel)
        $teacher_positions = $db_skj->table('tb_position')
                                     ->select('posi_id')
                                     ->whereIn('posi_name', ['ครู', 'ครูผู้ช่วย', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา'])
                                     ->get()->getResultArray();
        $teacher_position_ids = array_column($teacher_positions, 'posi_id');

        $personnel_with_names = [];
        if (!empty($teacher_position_ids)) {
            $personnel_raw = $db_default->table('tb_personnel')
                                ->where('pers_status', 'กำลังใช้งาน')
                                ->whereIn('pers_position', $teacher_position_ids)
                                ->orderBy('pers_learning', 'ASC')
                                ->orderBy('pers_firstname', 'ASC')
                                ->get()->getResultArray();

            foreach ($personnel_raw as $person) {
                $person['learning_area_name'] = $learning_groups[$person['pers_learning']] ?? '';
                $person['position_name'] = $positions[$person['pers_position']] ?? '';
                $personnel_with_names[] = $person;
            }
        }

        // 3. Get all completed evaluation submissions for the relevant teachers
        $teacher_ids = array_column($personnel_with_names, 'pers_id');
        $submitted_evals = [];

        if (!empty($teacher_ids)) {
            $submissions = $db_pa_evaluation->table('tb_evaluations as ev')
                ->select('ev.t_id, sc.e_id')
                ->join('tb_evaluator_scores as sc', 'sc.ev_id = ev.ev_id')
                ->join('tb_item_scores as it', 'it.es_id = sc.es_id') // JOIN to ensure completion
                ->where('ev.ev_fiscal_year', $fiscal_year_be)
                ->whereIn('ev.t_id', $teacher_ids)
                ->distinct()
                ->get()->getResultArray();

            // Process the flat results into the map we need
            foreach ($submissions as $sub) {
                $submitted_evals[$sub['t_id']][$sub['e_id']] = true;
            }
        }

        // 4. For each teacher, determine their assigned evaluators and their status
        $final_personnel_data = [];
        foreach ($personnel_with_names as $person) {
            $assigned_evaluator_ids = [];
            foreach ($assessor_scopes as $scope) {
                $is_match = false;
                $scope_has_posi = !empty($scope['scope_posi_id']);
                $scope_has_lear = !empty($scope['scope_lear_id']);

                if ($scope_has_posi && $scope_has_lear) {
                    if (($scope['scope_posi_id'] === $person['pers_position']) && ($scope['scope_lear_id'] === $person['pers_learning'])) {
                        $is_match = true;
                    }
                } else if ($scope_has_posi) {
                    if ($scope['scope_posi_id'] === $person['pers_position']) {
                        $is_match = true;
                    }
                } else if ($scope_has_lear) {
                    if ($scope['scope_lear_id'] === $person['pers_learning']) {
                        $is_match = true;
                    }
                } else {
                    $is_match = true;
                }

                if ($is_match) {
                    $assigned_evaluator_ids[$scope['assessor_e_id']] = true;
                }
            }

            $person['evaluators_info'] = [];
            foreach (array_keys($assigned_evaluator_ids) as $e_id) {
                if (isset($evaluators_map[$e_id])) {
                    $has_evaluated = isset($submitted_evals[$person['pers_id']][$e_id]);
                    $person['evaluators_info'][] = [
                        'name' => $evaluators_map[$e_id]['e_first_name'] . ' ' . $evaluators_map[$e_id]['e_last_name'],
                        'id' => $e_id,
                        'has_evaluated' => $has_evaluated
                    ];
                }
            }
            $final_personnel_data[] = $person;
        }

        // 5. Pass data to view
        $data = $this->DataMain();
        $data['title'] = 'รายงานประเมิน PA';
        $data['description'] = 'รายงานผลการประเมิน PA ปีการศึกษา ' . $fiscal_year_be;
        $data['UrlMenuMain'] = 'Admin';
        $data['UrlMenuSub'] = 'PaReport';
        $data['personnel'] = $final_personnel_data;
        $data['fiscal_year'] = $fiscal_year_be;

        return view('Admin/AdminPaEvaluation/report', $data);
    }

    public function getScores($personId, $evaluatorId)
    {
        $db_default = \Config\Database::connect();
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        // Get current fiscal year
        $current_month = date('m');
        $current_year_ad = date('Y');
        $fiscal_year_be = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;

        // 2. Find the main evaluation record (tb_evaluations)
        $evaluation = $db_pa_evaluation->table('tb_evaluations')
            ->where('t_id', $personId)
            ->where('ev_fiscal_year', $fiscal_year_be)
            ->get()->getRowArray();

        if (!$evaluation) {
            return $this->response->setBody('<div class="alert alert-warning">ไม่พบข้อมูลการประเมินหลักสำหรับบุคลากรนี้ในปีการศึกษานี้</div>');
        }

        // 3. Find the specific evaluator's score summary (tb_evaluator_scores)
        $evaluatorScoreSummary = $db_pa_evaluation->table('tb_evaluator_scores')
            ->where('ev_id', $evaluation['ev_id'])
            ->where('e_id', $evaluatorId)
            ->get()->getRowArray();

        if (!$evaluatorScoreSummary) {
            return $this->response->setBody('<div class="alert alert-warning">ผู้ประเมินยังไม่ได้ส่งผลการประเมิน</div>');
        }

        // 4. Get all individual item scores (tb_item_scores) for this evaluation
        $itemScoresRaw = $db_pa_evaluation->table('tb_item_scores')
            ->where('es_id', $evaluatorScoreSummary['es_id'])
            ->get()->getResultArray();

        // Extract ri_id's from the raw item scores to filter rubric items
        $scored_ri_ids = array_column($itemScoresRaw, 'ri_id');

        // 5. Get only the rubric items that actually have scores
        $rubricItems = $db_pa_evaluation->table('tb_rubric_items')
            ->whereIn('ri_id', $scored_ri_ids) // Filter by scored rubric item IDs
            ->orderBy('ri_part', 'ASC')
            ->orderBy('ri_item_number', 'ASC')
            ->get()->getResultArray();
            
        // 6. Process scores and calculate points
        $rubricItemsMap = array_column($rubricItems, null, 'ri_id');
        $itemScoresMap = [];
        $calculatedScoresMap = [];

        foreach ($itemScoresRaw as $scoreRow) {
            $rubricItemId = $scoreRow['ri_id'];
            $rawScore = $scoreRow['is_score'];

            $itemScoresMap[$rubricItemId] = $rawScore;

            $calculatedScore = 0;
            if (isset($rubricItemsMap[$rubricItemId])) {
                $rubricItem = $rubricItemsMap[$rubricItemId];
                $part = $rubricItem['ri_part'];
                $itemNumber = $rubricItem['ri_item_number'];

                if ($part == 1) {
                    $calculatedScore = $rawScore;
                } elseif ($part == 2) {
                    if ($itemNumber === '2.1' || $itemNumber === '2.2') {
                        switch ($rawScore) {
                            case 1: $calculatedScore = 2.50; break;
                            case 2: $calculatedScore = 5.00; break;
                            case 3: $calculatedScore = 7.50; break;
                            case 4: $calculatedScore = 10.00; break;
                        }
                    } else {
                        switch ($rawScore) {
                            case 1: $calculatedScore = 5.00; break;
                            case 2: $calculatedScore = 10.00; break;
                            case 3: $calculatedScore = 15.00; break;
                            case 4: $calculatedScore = 20.00; break;
                        }
                    }
                }
            }
            $calculatedScoresMap[$rubricItemId] = $calculatedScore;
        }

        $data['evaluation_data'] = [
            'rubric_items' => $rubricItems,
            'item_scores' => $itemScoresMap,
            'calculated_scores' => $calculatedScoresMap,
            'summary' => $evaluatorScoreSummary,
        ];

        return view('Admin/AdminPaEvaluation/score_details_modal', $data);
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
        }

        return redirect()->to(base_url('Admin/PaConfig'));
    }

    public function updateEvaluator()
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_evaluators');

        $e_id = $this->request->getPost('e_id');
        $username = $this->request->getPost('e_Username');

        // Basic validation to check if username already exists for a DIFFERENT user
        $existingUser = $table->where('e_Username', $username)
                              ->where('e_id !=', $e_id)
                              ->get()->getRow();
        if ($existingUser) {
            $session->setFlashdata('Error', 'ชื่อผู้ใช้งานนี้มีอยู่แล้วในระบบ!');
            return redirect()->to(base_url('Admin/PaConfig'));
        }

        $data = [
            'e_first_name' => $this->request->getPost('e_first_name'),
            'e_last_name' => $this->request->getPost('e_last_name'),
            'e_position' => $this->request->getPost('e_position'),
            'e_academic_standing' => $this->request->getPost('e_academic_standing'),
            'e_organization' => $this->request->getPost('e_organization'),
            'e_Username' => $username,
        ];

        // Hash the password only if a new one is provided
        $password = $this->request->getPost('e_Password');
        if (!empty($password)) {
            $data['e_Password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($table->where('e_id', $e_id)->update($data)) {
            $session->setFlashdata('Success', 'อัปเดตข้อมูลผู้ประเมินสำเร็จ!');
        } else {
            $session->setFlashdata('Error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล หรือไม่มีการเปลี่ยนแปลง!');
        }

        return redirect()->to(base_url('Admin/PaConfig'));
    }

    public function deleteEvaluator($id)
    {
        $session = session();
        if (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        // Start a transaction
        $db_pa_evaluation->transStart();

        // 1. Find all evaluator score IDs (es_id) linked to this evaluator
        $evaluatorScores = $db_pa_evaluation->table('tb_evaluator_scores')
                                            ->select('es_id')
                                            ->where('e_id', $id)
                                            ->get()->getResultArray();

        if (!empty($evaluatorScores)) {
            $es_ids = array_column($evaluatorScores, 'es_id');

            // 2. Delete all item scores linked to those evaluator scores
            $db_pa_evaluation->table('tb_item_scores')->whereIn('es_id', $es_ids)->delete();
        }

        // 3. Delete the evaluator's score summaries
        $db_pa_evaluation->table('tb_evaluator_scores')->delete(['e_id' => $id]);

        // 4. Finally, delete the evaluator
        $db_pa_evaluation->table('tb_evaluators')->delete(['e_id' => $id]);

        // Complete the transaction
        $db_pa_evaluation->transComplete();

        // Check the transaction status
        if ($db_pa_evaluation->transStatus() === false) {
            // Transaction failed
            $session->setFlashdata('Error', 'เกิดข้อผิดพลาดในการลบข้อมูลผู้ประเมินและข้อมูลที่เกี่ยวข้อง');
        } else {
            // Transaction successful
            $session->setFlashdata('Success', 'ลบผู้ประเมินและข้อมูลการประเมินที่เกี่ยวข้องทั้งหมดสำเร็จ!');
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
            'ri_academic_standing' => $academic_standing === '' ? null : $academic_standing,
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
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล!']);
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
        $position = $this->request->getPost('ri_position');

        $data = [
            'ri_part' => $this->request->getPost('ri_part'),
            'ri_domain' => $this->request->getPost('ri_domain'),
            'ri_item_number' => $this->request->getPost('ri_item_number'),
            'ri_item_description' => $this->request->getPost('ri_item_description'),
            'ri_expected_level_description' => $this->request->getPost('ri_expected_level_description'),
            'ri_academic_standing' => $academic_standing === '' ? null : $academic_standing,
            'ri_position' => $position === '' ? null : $position,
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
                              ->where('ri_position', $data['ri_position'])
                              ->where('ri_id !=', $ri_id) // Exclude current item
                              ->get()->getRow();

        if ($existingItem) {
            return $this->response->setJSON(['success' => false, 'message' => 'หัวข้อการประเมินนี้มีอยู่แล้ว!']);
        }

        if ($table->where('ri_id', $ri_id)->update($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตหัวข้อการประเมินสำเร็จ!']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล หรือไม่มีการเปลี่ยนแปลงข้อมูล!']);
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
        }

        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล!']);
    }
}