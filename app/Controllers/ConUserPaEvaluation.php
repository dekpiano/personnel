<?php

namespace App\Controllers;

use App\Models\EvaluationModel;
use App\Models\EvaluatorScoreModel;
use App\Models\ItemScoreModel;

class ConUserPaEvaluation extends BaseController
{  

    function __construct(){
       
    }

    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   
        $data['uri'] = service('uri'); 
        return $data;
    }

    public function paPersonnelList()
    {
        $session = session();
        $data = $this->DataMain();
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('pa-login?return_to=pa-personnel'));
        }
        $db_personnel = \Config\Database::connect('personnel');
        $builder = $db_personnel->table('tb_personnel');

        // Connect to the second database for joins
        $db_skj = \Config\Database::connect('skj');
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        // Perform joins with tables from the second database
        $builder->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left');
        $builder->join($db_skj->getDatabase() . '.tb_learning', 'tb_learning.lear_id = tb_personnel.pers_learning', 'left');

                $builder->where('tb_personnel.pers_status', "กำลังใช้งาน"); // Note: Assuming pers_status stores string "กำลังใช้งาน", if it's an integer (e.g., 1), this might not work as expected.

        // --- Start Assessor Scope Filtering ---
        $loggedInUserId = $session->get('id'); // Get the e_id of the logged-in user (assessor)

        // Fetch scopes for the logged-in assessor
        $assessorScopes = $db_pa_evaluation->table('tb_assessor_scope')
                                           ->where('assessor_e_id', $loggedInUserId)
                                           ->get()->getResultArray();

        if (!empty($assessorScopes)) {
            // Build dynamic WHERE OR conditions based on assessor scopes
            $builder->groupStart(); // Start a group for OR conditions
            foreach ($assessorScopes as $scope) {
                $builder->orGroupStart(); // Start an OR group for each scope
                if ($scope['scope_posi_id'] !== null) {
                    $builder->where('tb_personnel.pers_position', $scope['scope_posi_id']);
                }
                if ($scope['scope_lear_id'] !== null) {
                    $builder->where('tb_personnel.pers_learning', $scope['scope_lear_id']);
                }
                $builder->groupEnd(); // End the OR group
            }
            $builder->groupEnd(); // End the main OR group
        } else {
            // If no scope is defined for the assessor, they should not see any personnel.
            // Admins should see all, Assessors with no scope should see nothing.
            if ($session->get('status') !== 'admin' && $session->get('status') !== 'assessor') {
                $builder->where('1=0'); // Show no results if no scope and not admin/assessor
            }
        }
        // --- End Assessor Scope Filtering ---

        $query = $builder->get();
        $personnel = $query->getResultArray();

        // เพิ่มสถานะการประเมิน (เฉพาะสำหรับผู้ประเมินที่ล็อกอินอยู่)
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $loggedInUserId = $session->get('id'); // e_id from tb_evaluators

        foreach ($personnel as &$p) { // ใช้ & เพื่อแก้ไขค่าใน array โดยตรง
            $evaluationExists = $db_pa_evaluation->table('tb_evaluator_scores')
                                                 ->join('tb_evaluations', 'tb_evaluations.ev_id = tb_evaluator_scores.ev_id')
                                                 ->where('tb_evaluations.t_id', $p['pers_id'])
                                                 ->where('tb_evaluator_scores.e_id', $loggedInUserId)
                                                 ->countAllResults() > 0;
            $p['has_pa_evaluation'] = $evaluationExists;
        }
        unset($p); // ยกเลิก reference หลังจากวนลูปเสร็จ

        $data = $this->DataMain();
        $data['title'] = "เลือกบุคลากรเพื่อประเมิน PA";
        $data['description'] = "รายชื่อบุคลากรสำหรับทำแบบประเมินผลการพัฒนางานตามข้อตกลง (PA)";
        $data['UrlMenuMain'] = 'PA_FORM';
        $data['UrlMenuSub'] = '';
        $data['personnel'] = $personnel;

        return view('User/UserPA/PaPersonnelList', $data);
    }
  
    public function paForm($personId = null)
    {
        $database = \Config\Database::connect(); // Connects to 'default' (personnel) database
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation'); // Connects to 'pa_evaluation' database
        $db_skj = \Config\Database::connect('skj');

        // Fetch person details
        $builder = $database->table('tb_personnel');
        $builder->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left');
        $person = $builder->where('pers_id', $personId)->get()->getRowArray();

        if (!$person) {
            return redirect()->to('pa-personnel')->with('Error', 'ไม่พบข้อมูลบุคลากร');
        }

        // Temporarily fetch all rubric items for debugging purposes
        // ดึงวิทยฐานะของบุคลากร
        $academicStanding = empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $person['pers_academic'];

        // ดึงหัวข้อการประเมินตามวิทยฐานะ
        $rubricBuilder = $db_pa_evaluation->table('tb_rubric_items');
        $rubricBuilder->groupStart();
        $rubricBuilder->where('ri_academic_standing', $academicStanding); // ตรงกับวิทยฐานะของบุคลากร
        $rubricBuilder->orWhere('ri_academic_standing', 'ทั่วไป'); // หัวข้อทั่วไป
        $rubricBuilder->orWhere('ri_academic_standing IS NULL'); // หัวข้อที่ไม่มีการระบุวิทยฐานะ
        $rubricBuilder->orWhere('ri_academic_standing', ''); // หัวข้อที่ไม่มีการระบุวิทยฐานะ (สตริงว่าง)
        $rubricBuilder->groupEnd();
        $rubricBuilder->orderBy('ri_part', 'ASC');
        $rubricBuilder->orderBy('ri_item_number', 'ASC');
        $rubricItems = $rubricBuilder->get()->getResultArray();

        $session = session();
        $evaluatorId = $session->get('id');
        $evaluator = $db_pa_evaluation->table('tb_evaluators')->where('e_id', $evaluatorId)->get()->getRowArray();

        log_message('debug', 'Evaluator ID from session: ' . ($evaluatorId ?? 'NULL'));
        log_message('debug', 'Evaluator data fetched: ' . json_encode($evaluator));

        // ดึงข้อมูลการประเมินล่าสุดสำหรับ personId นี้
        $latestEvaluation = $db_pa_evaluation->table('tb_evaluations')
                                             ->where('t_id', $personId)
                                             ->orderBy('ev_id', 'DESC') // สมมติว่า ev_id ที่สร้างด้วย uniqid จะเรียงตามเวลาได้
                                             ->get()->getRowArray();

        $evaluatorScore = null;
        $itemScores = [];
        $rawItemScores = []; // New array to store raw scores for radio button checks

        if ($latestEvaluation) {
            // ดึงข้อมูลคะแนนรวมของผู้ประเมิน
            $evaluatorScore = $db_pa_evaluation->table('tb_evaluator_scores')
                                               ->where('ev_id', $latestEvaluation['ev_id'])
                                               ->where('e_id', $evaluatorId) // ดึงเฉพาะคะแนนที่ผู้ประเมินคนนี้เคยให้
                                               ->get()->getRowArray();

            if ($evaluatorScore) {
                // ดึงคะแนนรายข้อ
                $itemScoresResult = $db_pa_evaluation->table('tb_item_scores')
                                                     ->where('es_id', $evaluatorScore['es_id'])
                                                     ->get()->getResultArray();
                foreach ($itemScoresResult as $score) {
                    $itemScores[$score['ri_id']] = $score['is_calculated_score'];
                    $rawItemScores[$score['ri_id']] = $score['is_score']; // Store raw score
                }
            }
        }

        $data = $this->DataMain();
        $data['title']="แบบประเมิน PA";
        $data['description']="แบบประเมินผลการพัฒนางานตามข้อตกลง (PA)";
        $data['UrlMenuMain'] = 'PA_FORM';
        $data['UrlMenuSub'] = '';
        $data['person'] = $person;
        $data['rubricItems'] = $rubricItems; // Pass rubric items to the view
        $data['evaluator'] = $evaluator; // Pass evaluator data to the view
        $data['evaluatorScore'] = $evaluatorScore; // ส่งข้อมูลคะแนนรวมของผู้ประเมิน
        $data['itemScores'] = $itemScores;         // ส่งข้อมูลคะแนนรายข้อ
        $data['rawItemScores'] = $rawItemScores;   // ส่งข้อมูลคะแนนดิบสำหรับ radio button

        log_message('debug', 'rawItemScores: ' . json_encode($rawItemScores));

        return view('User/UserPA/PaForm', $data);
    }

    public function savePaEvaluation()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('pa-login'))->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        if ($this->request->getMethod() === 'post') {
            $evaluationModel = new EvaluationModel();
            $evaluatorScoreModel = new EvaluatorScoreModel();
            $itemScoreModel = new ItemScoreModel();

            // Get form data
            $personId = $this->request->getPost('person_id');

            log_message('debug', 'Attempting to save PA evaluation for personId: ' . $personId);

            // ตรวจสอบว่า personId มีอยู่ใน tb_personnel หรือไม่
            $db_personnel = \Config\Database::connect('personnel');
            $personExists = $db_personnel->table('tb_personnel')->where('pers_id', $personId)->countAllResults() > 0;

            log_message('debug', 'Person ID ' . $personId . ' exists in tb_personnel: ' . ($personExists ? 'true' : 'false'));

            if (!$personExists) {
                throw new \Exception('ไม่พบข้อมูลผู้รับการประเมินในระบบบุคลากร.');
            }

            $evaluatorId = $this->request->getPost('evaluator_id');
            $academicYear = $this->request->getPost('academicYear');
            $evaluationPeriod = $this->request->getPost('evaluationPeriod');
            $strongPoints = $this->request->getPost('challengeDescription');
            $areasForImprovement = $this->request->getPost('challengeResult');
            $comments = $this->request->getPost('comments');
            $totalScore1 = $this->request->getPost('totalScore1'); // Assuming these are passed from JS
            $totalScore2 = $this->request->getPost('totalScore2'); // Assuming these are passed from JS
            $totalScore = $this->request->getPost('totalScore');   // Assuming these are passed from JS

            $evaluatorId = $this->request->getPost('evaluator_id');

            // Start a transaction
            $db = \Config\Database::connect('pa_evaluation');
            $db->transStart();

            try {
                // 1. ตรวจสอบว่ามีการประเมินหลักสำหรับบุคลากรคนนี้ในรอบปีนี้แล้วหรือไม่
                $existingEvaluation = $evaluationModel->where('t_id', $personId)
                                                      ->where('ev_fiscal_year', $academicYear)
                                                      // หาก evaluationPeriod มีผลต่อการระบุรอบการประเมินเดียวกัน ให้เพิ่มเงื่อนไขที่นี่
                                                      ->first();

                $ev_id = $existingEvaluation['ev_id'] ?? uniqid('EV_');

                // 1.1. บันทึก/อัปเดต tb_evaluations
                $evaluationData = [
                    'ev_id' => $ev_id,
                    't_id' => $personId,
                    'ev_fiscal_year' => $academicYear,
                    'ev_start_date' => '2024-10-01', // Placeholder, need to get from form or config
                    'ev_end_date' => '2025-09-30',   // Placeholder, need to get from form or config
                ];

                if ($existingEvaluation) {
                    // Update existing evaluation
                    if (!$evaluationModel->update($ev_id, $evaluationData)) {
                        throw new \Exception('ไม่สามารถอัปเดตข้อมูลการประเมินหลักได้.');
                    }
                } else {
                    // Insert new evaluation
                    if (!$evaluationModel->insert($evaluationData)) {
                        throw new \Exception('ไม่สามารถบันทึกข้อมูลการประเมินหลักได้.');
                    }
                }

                // 2. ตรวจสอบว่าผู้ประเมินคนนี้เคยให้คะแนนในรอบนี้แล้วหรือไม่
                $existingEvaluatorScore = $evaluatorScoreModel->where('ev_id', $ev_id)
                                                              ->where('e_id', $evaluatorId)
                                                              ->first();

                $es_id = $existingEvaluatorScore['es_id'] ?? uniqid('ES_');

                // 2.1. บันทึก/อัปเดต tb_evaluator_scores
                $evaluatorScoreData = [
                    'es_id' => $es_id,
                    'ev_id' => $ev_id,
                    'e_id' => $evaluatorId,
                    'es_part1_score' => $totalScore1,
                    'es_part2_score' => $totalScore2,
                    'es_total_score' => $totalScore,
                    'es_strong_points' => $strongPoints,
                    'es_areas_for_improvement' => $areasForImprovement,
                    'es_comments' => $comments,
                ];

                if ($existingEvaluatorScore) {
                    // Update existing evaluator score
                    if (!$evaluatorScoreModel->update($es_id, $evaluatorScoreData)) {
                        throw new \Exception('ไม่สามารถอัปเดตคะแนนผู้ประเมินได้.');
                    }
                } else {
                    // Insert new evaluator score
                    if (!$evaluatorScoreModel->insert($evaluatorScoreData)) {
                        throw new \Exception('ไม่สามารถบันทึกคะแนนผู้ประเมินได้.');
                    }
                }

                // 3. บันทึก/อัปเดต tb_item_scores
                // หากมีการอัปเดต ให้ลบรายการเก่าทั้งหมดที่เกี่ยวข้องกับ es_id นี้ก่อนแล้วค่อย insert ใหม่
                if ($existingEvaluatorScore) {
                    $itemScoreModel->where('es_id', $es_id)->delete();
                }

                foreach ($this->request->getPost() as $key => $value) {
                    if (strpos($key, 'points_') === 0) {
                        $ri_id = str_replace('points_', '', $key);
                        $is_calculated_score = (float)$value; // Cast to float
                        $is_score = $this->request->getPost('raw_score_' . $ri_id); // This is the raw radio button value

                        // Only save if a raw score (radio button) was selected
                        if (!empty($is_score)) {
                            $itemScoreData = [
                                'is_id' => uniqid('IS_'),
                                'es_id' => $es_id,
                                'ri_id' => $ri_id,
                                'is_score' => $is_score,
                                'is_calculated_score' => $is_calculated_score, // Now it will be saved even if 0
                                'is_notes' => null, // No notes field in the current form
                            ];
                            log_message('debug', 'ItemScoreData to insert: ' . json_encode($itemScoreData));
                            if (!$itemScoreModel->insert($itemScoreData)) {
                                log_message('error', 'Failed to insert itemScoreData: ' . json_encode($itemScoreData));
                                throw new \Exception('ไม่สามารถบันทึกคะแนนรายข้อได้.');
                            }
                        }
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    // Transaction failed
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล.']);
                    } else {
                        return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล.');
                    }
                } else {
                    // Transaction successful
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => true, 'message' => 'บันทึกแบบประเมินสำเร็จ!']);
                    } else {
                        return redirect()->to(base_url('user/pa-evaluation/success'))->with('success', 'บันทึกแบบประเมินสำเร็จ!');
                    }
                }

            } catch (\Exception $e) {
                $db->transRollback();
                log_message('error', 'PA Evaluation Save Error: ' . $e->getMessage());
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
                } else {
                    return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
                }
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.']);
        } else {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
    }
    
}