<?php

namespace App\Controllers;

class ConAdminPaConfig extends BaseController
{
    public function __construct(){
        $session = session();
        if(!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "ผู้บริหาร"])){
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
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้!');
        }

        $data = $this->DataMain();
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        // Check and auto-add scope_pers_id column if not existing
        if (!$db_pa_evaluation->fieldExists('scope_pers_id', 'tb_assessor_scope')) {
            $forge = \Config\Database::forge('pa_evaluation');
            $fields = [
                'scope_pers_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'scope_lear_id'
                ]
            ];
            $forge->addColumn('tb_assessor_scope', $fields);
        }

        // คำนวณปีการศึกษาปัจจุบัน
        $current_month = (int)date('m');
        $current_year_ad = (int)date('Y');
        $current_fiscal_year_be = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;

        // ดึงปีการศึกษาที่มีในระบบ
        $years_raw = $db_pa_evaluation->table('tb_evaluations')
            ->select('ev_fiscal_year')
            ->distinct()
            ->get()->getResultArray();
        $available_years = array_column($years_raw, 'ev_fiscal_year');
        if (!in_array($current_fiscal_year_be, $available_years)) {
            array_unshift($available_years, $current_fiscal_year_be);
        }
        rsort($available_years);

        $selected_fiscal_year = $this->request->getGet('fiscal_year');
        $fiscal_year_be = !empty($selected_fiscal_year) ? (int)$selected_fiscal_year : $current_fiscal_year_be;

        // Fetch all registered evaluators
        $all_evaluators = $db_pa_evaluation->table('tb_evaluators')
                                  ->orderBy('e_first_name', 'ASC')
                                  ->get()->getResultArray();

        $db_skj = \Config\Database::connect('skj');
        $db_default = \Config\Database::connect();

        // Fetch personnel (filtered for civil servant teachers / ครูข้าราชการ, excluding ครูช่วยปฏิบัติงาน / ครูช่วยปฏิบัติการสอน)
        $personnel = $db_default->table('tb_personnel')
                                ->select('tb_personnel.pers_id, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname, tb_personnel.pers_learning, tb_position.posi_name')
                                ->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left')
                                ->where('pers_status', 'กำลังใช้งาน')
                                ->groupStart()
                                    ->whereIn('tb_position.posi_name', ['ครู', 'ครูผู้ช่วย', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา'])
                                    ->orLike('tb_position.posi_name', 'ครู')
                                    ->orWhere("tb_personnel.pers_position BETWEEN 'posi_003' AND 'posi_006'")
                                ->groupEnd()
                                ->notLike('tb_position.posi_name', 'ช่วยปฏิบัติงาน')
                                ->notLike('tb_position.posi_name', 'ช่วยปฏิบัติการสอน')
                                ->notLike('tb_position.posi_name', 'ช่วยสอน')
                                ->notLike('tb_position.posi_name', 'ช่วยราชการ')
                                ->orderBy('pers_firstname', 'ASC')
                                ->get()->getResultArray();

        // Fetch positions (filtered for civil servant teacher positions)
        $positions = $db_skj->table('tb_position')
                            ->select('posi_id, posi_name')
                            ->groupStart()
                                ->whereIn('posi_name', ['ครู', 'ครูผู้ช่วย', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา'])
                                ->orLike('posi_name', 'ครู')
                                ->orWhere("posi_id BETWEEN 'posi_003' AND 'posi_006'")
                            ->groupEnd()
                            ->notLike('posi_name', 'ช่วยปฏิบัติงาน')
                            ->notLike('posi_name', 'ช่วยปฏิบัติการสอน')
                            ->notLike('posi_name', 'ช่วยสอน')
                            ->notLike('posi_name', 'ช่วยราชการ')
                            ->orderBy('posi_id', 'ASC')
                            ->get()->getResultArray();

        $learningGroups = $db_skj->table('tb_learning')
                                 ->select('lear_id, lear_namethai')
                                 ->orderBy('lear_namethai', 'ASC')
                                 ->get()->getResultArray();

        // Fetch assessor scopes filtered by fiscal year (matching BE & AD formats)
        $fiscal_year_ad = $fiscal_year_be - 543;
        $assessorScopes = $db_pa_evaluation->table('tb_assessor_scope')
                                           ->groupStart()
                                                ->where('scope_fiscal_year', $fiscal_year_be)
                                                ->orWhere('scope_fiscal_year', (string)$fiscal_year_be)
                                                ->orWhere('scope_fiscal_year', $fiscal_year_ad)
                                                ->orWhere('scope_fiscal_year', (string)$fiscal_year_ad)
                                                ->orWhere('scope_fiscal_year IS NULL')
                                                ->orWhere('scope_fiscal_year', '')
                                                ->orWhere('scope_fiscal_year', 0)
                                           ->groupEnd()
                                           ->get()->getResultArray();

        // Get evaluators assigned to this fiscal year
        $assigned_e_ids = array_unique(array_column($assessorScopes, 'assessor_e_id'));
        $year_evaluators = array_values(array_filter($all_evaluators, function($e) use ($assigned_e_ids) {
            return in_array($e['e_id'], $assigned_e_ids);
        }));

        // Fetch all personnel with position and academic info for internal evaluator lookup
        $school_personnel = $db_default->table('tb_personnel')
                                ->select('tb_personnel.pers_id, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname, tb_personnel.pers_academic, tb_personnel.pers_username, tb_position.posi_name')
                                ->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left')
                                ->where('pers_status', 'กำลังใช้งาน')
                                ->orderBy('pers_firstname', 'ASC')
                                ->get()->getResultArray();

        // Count evaluators per teacher and build assessor-teacher mapping for this fiscal year
        $evaluator_counts = [];
        $assessor_teacher_map = [];
        foreach ($assessorScopes as $scope) {
            if (!empty($scope['scope_pers_id'])) {
                $pidStr = (string)$scope['scope_pers_id'];
                $eIdStr = (string)$scope['assessor_e_id'];
                $evaluator_counts[$pidStr] = ($evaluator_counts[$pidStr] ?? 0) + 1;
                $assessor_teacher_map[$eIdStr][] = $pidStr;
            }
        }

        $data = [
            'title' => 'ตั้งค่าผู้ประเมิน PA',
            'description' => 'กำหนดขอบเขตการประเมินสำหรับผู้ประเมินประจำปี ' . $fiscal_year_be,
            'evaluators' => $year_evaluators,
            'all_evaluators' => $all_evaluators,
            'school_personnel' => $school_personnel,
            'personnel' => $personnel,
            'positions' => $positions,
            'learningGroups' => $learningGroups,
            'assessorScopes' => $assessorScopes,
            'evaluator_counts' => $evaluator_counts,
            'assessor_teacher_map' => $assessor_teacher_map,
            'fiscal_year' => $fiscal_year_be,
            'available_years' => $available_years,
            'UrlMenuMain' => 'Admin',
            'UrlMenuSub' => 'PaConfig',
            'uri' => service('uri'),
        ];

        return view('Admin/AdminPaEvaluation/index', $data);
    }

    public function report()
    {
        $session = session();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้!');
        }

        $db_default = \Config\Database::connect(); // Default connection
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $db_skj = \Config\Database::connect('skj');

        // คำนวณปีงบประมาณ/ปีการศึกษาปัจจุบัน (รอบ ต.ค. - ก.ย.)
        $current_month = (int)date('m');
        $current_year_ad = (int)date('Y');
        $current_fiscal_year_be = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;

        // ดึงรายการปีการศึกษาทั้งหมดที่มีในระบบ (ทั้งจาก evaluations และ assessor_scope)
        $years_eval = $db_pa_evaluation->table('tb_evaluations')
            ->select('ev_fiscal_year as yr')
            ->distinct()
            ->get()->getResultArray();

        $years_scope = $db_pa_evaluation->table('tb_assessor_scope')
            ->select('scope_fiscal_year as yr')
            ->where('scope_fiscal_year >', 0)
            ->distinct()
            ->get()->getResultArray();

        $merged_years = array_merge(
            array_column($years_eval, 'yr'),
            array_column($years_scope, 'yr')
        );
        $available_years = array_values(array_unique(array_filter($merged_years)));

        if (!in_array($current_fiscal_year_be, $available_years)) {
            array_unshift($available_years, $current_fiscal_year_be);
        }
        rsort($available_years);

        // รับค่าปีการศึกษาจาก GET (ถ้ามี) มิฉะนั้นใช้ปีปัจจุบัน
        $selected_fiscal_year = $this->request->getGet('fiscal_year');
        $fiscal_year_be = !empty($selected_fiscal_year) ? (int)$selected_fiscal_year : $current_fiscal_year_be;
        $fiscal_year_ad = $fiscal_year_be - 543;

        // 1. Fetch all necessary lookup tables at once (กรอง assessor_scope ตามปีการศึกษาที่เลือกอย่างเคร่งครัด)
        $assessor_scopes = $db_pa_evaluation->table('tb_assessor_scope')
            ->groupStart()
                ->where('scope_fiscal_year', $fiscal_year_be)
                ->orWhere('scope_fiscal_year', (string)$fiscal_year_be)
                ->orWhere('scope_fiscal_year', $fiscal_year_ad)
                ->orWhere('scope_fiscal_year', (string)$fiscal_year_ad)
            ->groupEnd()
            ->get()->getResultArray();

        $all_evaluators = $db_pa_evaluation->table('tb_evaluators')->get()->getResultArray();
        $evaluators_map = array_column($all_evaluators, null, 'e_id');
        $learning_groups_raw = $db_skj->table('tb_learning')->get()->getResultArray();
        $learning_groups = array_column($learning_groups_raw, 'lear_namethai', 'lear_id');
        $positions_raw = $db_skj->table('tb_position')->get()->getResultArray();
        $positions = array_column($positions_raw, 'posi_name', 'posi_id');

        // 2. Get the list of teachers (personnel)
        $personnel_raw = $db_default->table('tb_personnel')
                            ->where('pers_status', 'กำลังใช้งาน')
                            ->groupStart()
                                ->whereIn('pers_position', ['posi_003', 'posi_004', 'posi_005', 'posi_006'])
                                ->orLike('pers_position', 'posi_')
                            ->groupEnd()
                            ->orderBy('pers_learning', 'ASC')
                            ->orderBy('pers_firstname', 'ASC')
                            ->get()->getResultArray();

        $personnel_with_names = [];
        foreach ($personnel_raw as $person) {
            $posName = $positions[$person['pers_position']] ?? '';
            // กรองเฉพาะสายครูผู้สอน/ผู้บริหารสถานศึกษา ไม่รวมช่วยราชการ/ช่วยปฏิบัติงาน/ช่วยปฏิบัติการสอน
            if (str_contains($posName, 'ช่วยปฏิบัติงาน') || str_contains($posName, 'ช่วยปฏิบัติการสอน') || str_contains($posName, 'ช่วยสอน') || str_contains($posName, 'ช่วยราชการ')) {
                continue;
            }
            if (empty($posName) || (!str_contains($posName, 'ครู') && !str_contains($posName, 'ผู้อำนวยการ'))) {
                continue;
            }
            $person['learning_area_name'] = $learning_groups[$person['pers_learning']] ?? '';
            $person['position_name'] = $posName;
            $personnel_with_names[] = $person;
        }

        // 3. Get all completed evaluation submissions for the relevant teachers
        $teacher_ids = array_column($personnel_with_names, 'pers_id');
        $submitted_evals = [];

        if (!empty($teacher_ids)) {
            $submissions = $db_pa_evaluation->table('tb_evaluations as ev')
                ->select('ev.t_id, sc.e_id')
                ->join('tb_evaluator_scores as sc', 'sc.ev_id = ev.ev_id')
                ->groupStart()
                    ->where('ev.ev_fiscal_year', $fiscal_year_be)
                    ->orWhere('ev.ev_fiscal_year', (string)$fiscal_year_be)
                    ->orWhere('ev.ev_fiscal_year', $fiscal_year_ad)
                    ->orWhere('ev.ev_fiscal_year', (string)$fiscal_year_ad)
                ->groupEnd()
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
                $scope_has_pers = !empty($scope['scope_pers_id']);
                $scope_has_posi = !empty($scope['scope_posi_id']);
                $scope_has_lear = !empty($scope['scope_lear_id']);

                if ($scope_has_pers) {
                    if ((string)$scope['scope_pers_id'] === (string)$person['pers_id']) {
                        $is_match = true;
                    }
                } else if ($scope_has_posi && $scope_has_lear) {
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
                }
                // หมายเหตุ: หาก scope_pers_id, scope_posi_id, scope_lear_id เป็น NULL ทั้งหมด 
                // แสดงว่าผู้ประเมินยังไม่ได้ถูกผูกกับครูคนใด จึงไม่จับคู่ ($is_match = false)

                if ($is_match && !empty($scope['assessor_e_id'])) {
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
        $data['available_years'] = $available_years;

        return view('Admin/AdminPaEvaluation/report', $data);
    }

    public function getScores($personId, $evaluatorId, $fiscal_year_be = null)
    {
        $db_default = \Config\Database::connect();
        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        if (empty($fiscal_year_be)) {
            $current_month = (int)date('m');
            $current_year_ad = (int)date('Y');
            $fiscal_year_be = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;
        }

        $fiscal_year_ad = (int)$fiscal_year_be - 543;

        // 2. Find the main evaluation record (tb_evaluations)
        $evaluation = $db_pa_evaluation->table('tb_evaluations')
            ->where('t_id', $personId)
            ->groupStart()
                ->where('ev_fiscal_year', $fiscal_year_be)
                ->orWhere('ev_fiscal_year', (string)$fiscal_year_be)
                ->orWhere('ev_fiscal_year', $fiscal_year_ad)
                ->orWhere('ev_fiscal_year', (string)$fiscal_year_ad)
            ->groupEnd()
            ->get()->getRowArray();

        if (!$evaluation) {
            return $this->response->setBody('<div class="alert alert-warning">ไม่พบข้อมูลการประเมินหลักสำหรับบุคลากรนี้ในปีการศึกษา ' . esc($fiscal_year_be) . '</div>');
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
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $assessor_e_id = $this->request->getPost('assessor_e_id');
        $scope_posi_id = $this->request->getPost('scope_posi_id');
        $scope_lear_id = $this->request->getPost('scope_lear_id');
        $scope_pers_id_post = $this->request->getPost('scope_pers_id');
        $scope_fiscal_year = $this->request->getPost('scope_fiscal_year');

        if (empty($scope_fiscal_year)) {
            $current_month = (int)date('m');
            $current_year_ad = (int)date('Y');
            $scope_fiscal_year = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

        // Check and auto-add scope_pers_id column if not existing
        if (!$db_pa_evaluation->fieldExists('scope_pers_id', 'tb_assessor_scope')) {
            $forge = \Config\Database::forge('pa_evaluation');
            $fields = [
                'scope_pers_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'scope_lear_id'
                ]
            ];
            $forge->addColumn('tb_assessor_scope', $fields);
        }

        // Drop outdated unique index if present (old index lacked scope_pers_id)
        try {
            $db_pa_evaluation->query("ALTER TABLE tb_assessor_scope DROP INDEX idx_assessor_scope_unique");
        } catch (\Throwable $e) {
            // Ignore if index doesn't exist or already dropped
        }

        $table = $db_pa_evaluation->table('tb_assessor_scope');

        $pers_ids = [];
        if (is_array($scope_pers_id_post)) {
            $pers_ids = array_filter($scope_pers_id_post);
        } else if (!empty($scope_pers_id_post)) {
            $pers_ids = [$scope_pers_id_post];
        }

        $insertedCount = 0;

        if (!empty($pers_ids)) {
            foreach ($pers_ids as $pid) {
                $existingScope = $table->where('assessor_e_id', $assessor_e_id)
                                       ->where('scope_posi_id', $scope_posi_id === '' ? null : $scope_posi_id)
                                       ->where('scope_lear_id', $scope_lear_id === '' ? null : $scope_lear_id)
                                       ->where('scope_pers_id', $pid)
                                       ->where('scope_fiscal_year', $scope_fiscal_year)
                                       ->get()->getRowArray();
                if (!$existingScope) {
                    $insertData = [
                        'assessor_e_id' => $assessor_e_id,
                        'scope_posi_id' => $scope_posi_id === '' ? null : $scope_posi_id,
                        'scope_lear_id' => $scope_lear_id === '' ? null : $scope_lear_id,
                        'scope_pers_id' => $pid,
                        'scope_fiscal_year' => $scope_fiscal_year,
                    ];
                    try {
                        if ($table->insert($insertData)) {
                            $insertedCount++;
                        }
                    } catch (\Throwable $e) {
                        log_message('error', 'Failed to insert scope: ' . $e->getMessage());
                    }
                }
            }
        } else {
            $existingScope = $table->where('assessor_e_id', $assessor_e_id)
                                   ->where('scope_posi_id', $scope_posi_id === '' ? null : $scope_posi_id)
                                   ->where('scope_lear_id', $scope_lear_id === '' ? null : $scope_lear_id)
                                   ->where('scope_pers_id', null)
                                   ->where('scope_fiscal_year', $scope_fiscal_year)
                                   ->get()->getRowArray();
            if (!$existingScope) {
                $insertData = [
                    'assessor_e_id' => $assessor_e_id,
                    'scope_posi_id' => $scope_posi_id === '' ? null : $scope_posi_id,
                    'scope_lear_id' => $scope_lear_id === '' ? null : $scope_lear_id,
                    'scope_pers_id' => null,
                    'scope_fiscal_year' => $scope_fiscal_year,
                ];
                try {
                    if ($table->insert($insertData)) {
                        $insertedCount++;
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to insert scope: ' . $e->getMessage());
                }
            }
        }

        if ($insertedCount > 0) {
            $session->setFlashdata('Success', 'บันทึกขอบเขตการประเมินสำเร็จ (' . $insertedCount . ' รายการ) ประจำปี ' . $scope_fiscal_year . '!');
        } else {
            $session->setFlashdata('Error', 'การตั้งค่านี้มีอยู่แล้ว หรือไม่มีรายการใหม่ถูกบันทึก!');
        }

        return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
    }

    public function deleteScope($id)
    {
        $session = session();
        $data = $this->DataMain();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_assessor_scope');

        $scope = $table->where('id', $id)->get()->getRowArray();
        $redirectYear = !empty($scope['scope_fiscal_year']) ? $scope['scope_fiscal_year'] : '';

        if ($table->delete(['id' => $id])) {
            $session->setFlashdata('Success', 'ลบการตั้งค่าสำเร็จ!');
        }

        return redirect()->to(base_url('Admin/PaConfig' . ($redirectYear ? '?fiscal_year=' . $redirectYear : '')));
    }

    public function deleteGroup()
    {
        $session = session();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $ids = $this->request->getGet('ids');
        $fiscal_year = $this->request->getGet('fiscal_year');

        if (!empty($ids)) {
            $idArray = explode(',', $ids);
            $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
            $table = $db_pa_evaluation->table('tb_assessor_scope');
            $table->whereIn('id', $idArray)->delete();
            $session->setFlashdata('Success', 'ลบขอบเขตการประเมินทั้งกลุ่มสำเร็จ!');
        }

        return redirect()->to(base_url('Admin/PaConfig' . ($fiscal_year ? '?fiscal_year=' . $fiscal_year : '')));
    }

    public function addEvaluator()
    {
        $session = session();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $evaluator_type = $this->request->getPost('evaluator_type');
        $scope_fiscal_year = $this->request->getPost('scope_fiscal_year');

        if (empty($scope_fiscal_year)) {
            $current_month = (int)date('m');
            $current_year_ad = (int)date('Y');
            $scope_fiscal_year = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;
        }

        if ($evaluator_type === 'school_personnel') {
            $school_pers_id = $this->request->getPost('school_pers_id');
            if (empty($school_pers_id)) {
                $session->setFlashdata('Error', 'กรุณาเลือกครู/บุคลากรในโรงเรียน');
                $session->setFlashdata('open_evaluator_modal', true);
                return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
            }

            // Find personnel from db
            $db_default = \Config\Database::connect();
            $db_skj = \Config\Database::connect('skj');
            $person = $db_default->table('tb_personnel')
                                 ->select('tb_personnel.*, tb_position.posi_name')
                                 ->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left')
                                 ->where('pers_id', $school_pers_id)
                                 ->get()->getRowArray();

            if (!$person) {
                $session->setFlashdata('Error', 'ไม่พบข้อมูลบุคลากรที่เลือก');
                $session->setFlashdata('open_evaluator_modal', true);
                return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
            }

            $username = !empty($person['pers_username']) ? $person['pers_username'] : strtolower($person['pers_firstname']);
            $evaluatorsTable = $db_pa_evaluation->table('tb_evaluators');

            // Check if evaluator account already exists in tb_evaluators
            $existingEvaluator = $evaluatorsTable->where('e_Username', $username)->get()->getRowArray();

            if ($existingEvaluator) {
                $e_id = $existingEvaluator['e_id'];
            } else {
                // Create evaluator account from personnel info
                $e_id = uniqid('e');
                $password = !empty($person['pers_password']) ? $person['pers_password'] : password_hash('123456', PASSWORD_DEFAULT);
                if (strlen($password) < 40) {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                }

                $evaluatorsTable->insert([
                    'e_id' => $e_id,
                    'e_first_name' => $person['pers_firstname'],
                    'e_last_name' => $person['pers_lastname'],
                    'e_position' => $person['posi_name'] ?: 'ครู',
                    'e_academic_standing' => !empty($person['pers_academic']) ? $person['pers_academic'] : 'ไม่มีวิทยฐานะ',
                    'e_organization' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
                    'e_Username' => $username,
                    'e_Password' => $password,
                ]);
            }

            // Check if scope entry already exists for this fiscal year
            $scopeTable = $db_pa_evaluation->table('tb_assessor_scope');
            $existingScope = $scopeTable->where('assessor_e_id', $e_id)
                                        ->where('scope_fiscal_year', $scope_fiscal_year)
                                        ->get()->getRowArray();

            if (!$existingScope) {
                $scopeTable->insert([
                    'assessor_e_id' => $e_id,
                    'scope_posi_id' => null,
                    'scope_lear_id' => null,
                    'scope_pers_id' => null,
                    'scope_fiscal_year' => $scope_fiscal_year,
                ]);
            }

            $personName = ($person['pers_prefix'] ?? '') . $person['pers_firstname'] . ' ' . $person['pers_lastname'];
            $session->setFlashdata('Success', 'เพิ่ม ' . $personName . ' เป็นผู้ประเมินประจำปีการศึกษา ' . $scope_fiscal_year . ' สำเร็จ!');
            $session->setFlashdata('open_evaluator_modal', true);
            return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
        }

        if ($evaluator_type === 'existing') {
            $existing_e_id = $this->request->getPost('existing_e_id');
            if (empty($existing_e_id)) {
                $session->setFlashdata('Error', 'กรุณาเลือกผู้ประเมินเดิมที่ต้องการเพิ่ม');
                return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
            }

            // Check if scope entry already exists for this fiscal year
            $scopeTable = $db_pa_evaluation->table('tb_assessor_scope');
            $existingScope = $scopeTable->where('assessor_e_id', $existing_e_id)
                                        ->where('scope_fiscal_year', $scope_fiscal_year)
                                        ->get()->getRowArray();

            if (!$existingScope) {
                $scopeTable->insert([
                    'assessor_e_id' => $existing_e_id,
                    'scope_posi_id' => null,
                    'scope_lear_id' => null,
                    'scope_pers_id' => null,
                    'scope_fiscal_year' => $scope_fiscal_year,
                ]);
            }

            $session->setFlashdata('Success', 'เพิ่มผู้ประเมินเดิมเข้าสู่ปีการศึกษา ' . $scope_fiscal_year . ' สำเร็จ!');
            $session->setFlashdata('open_evaluator_modal', true);
            return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
        }

        // Otherwise create new evaluator
        $table = $db_pa_evaluation->table('tb_evaluators');

        // Basic validation to check if username already exists
        $existingUser = $table->where('e_Username', $this->request->getPost('e_Username'))->get()->getRow();
        if ($existingUser) {
            $session->setFlashdata('Error', 'ชื่อผู้ใช้งานนี้มีอยู่แล้วในระบบ!');
            $session->setFlashdata('open_evaluator_modal', true);
            return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
        }

        // Hash the password before saving
        $password = $this->request->getPost('e_Password');
        if (empty($password)) {
            $session->setFlashdata('Error', 'รหัสผ่านห้ามเป็นค่าว่าง');
            $session->setFlashdata('open_evaluator_modal', true);
            return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $newId = uniqid('e');
        $data = [
            'e_id' => $newId,
            'e_first_name' => $this->request->getPost('e_first_name'),
            'e_last_name' => $this->request->getPost('e_last_name'),
            'e_position' => $this->request->getPost('e_position'),
            'e_academic_standing' => $this->request->getPost('e_academic_standing'),
            'e_organization' => $this->request->getPost('e_organization'),
            'e_Username' => $this->request->getPost('e_Username'),
            'e_Password' => $hashedPassword,
        ];

        if ($table->insert($data)) {
            // Assign to current fiscal year
            $scopeTable = $db_pa_evaluation->table('tb_assessor_scope');
            $scopeTable->insert([
                'assessor_e_id' => $newId,
                'scope_posi_id' => null,
                'scope_lear_id' => null,
                'scope_pers_id' => null,
                'scope_fiscal_year' => $scope_fiscal_year,
            ]);
            $session->setFlashdata('Success', 'เพิ่มข้อมูลผู้ประเมินใหม่สำเร็จประจำปี ' . $scope_fiscal_year . '!');
        }

        $session->setFlashdata('open_evaluator_modal', true);
        return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $scope_fiscal_year));
    }

    public function updateEvaluator()
    {
        $session = session();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
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
            $session->setFlashdata('open_evaluator_modal', true);
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

        $session->setFlashdata('open_evaluator_modal', true);
        return redirect()->to(base_url('Admin/PaConfig'));
    }

    public function deleteEvaluator($id)
    {
        $session = session();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return redirect()->to(base_url('Admin/Home'))->with('Error', 'คุณไม่มีสิทธิ์ดำเนินการนี้!');
        }

        $selected_fiscal_year = $this->request->getGet('fiscal_year');

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_assessor_scope');

        if (!empty($selected_fiscal_year)) {
            $table->where('assessor_e_id', $id)
                  ->groupStart()
                       ->where('scope_fiscal_year', $selected_fiscal_year)
                       ->orWhere('scope_fiscal_year IS NULL')
                       ->orWhere('scope_fiscal_year', 0)
                  ->groupEnd()
                  ->delete();

            $session->setFlashdata('Success', 'นำผู้ประเมินออกจากปีการศึกษา ' . $selected_fiscal_year . ' เรียบร้อยแล้ว (ข้อมูลประวัติการประเมินย้อนหลังยังคงอยู่ครบถ้วน)');
            $session->setFlashdata('open_evaluator_modal', true);
            return redirect()->to(base_url('Admin/PaConfig?fiscal_year=' . $selected_fiscal_year));
        } else {
            $table->where('assessor_e_id', $id)->delete();
            $session->setFlashdata('Success', 'นำผู้ประเมินออกจากขอบเขตการประเมินเรียบร้อยแล้ว!');
            $session->setFlashdata('open_evaluator_modal', true);
            return redirect()->to(base_url('Admin/PaConfig'));
        }
    }




    public function rubricItems()
    {
        $session = session();
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
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
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
            return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ดำเนินการนี้!']);
        }

        $db_pa_evaluation = \Config\Database::connect('pa_evaluation');
        $table = $db_pa_evaluation->table('tb_rubric_items');

        $academic_standing = $this->request->getPost('ri_academic_standing');
        $position = $this->request->getPost('ri_position');

        $data = [
            'ri_id' => uniqid('ri'), // Generate a unique ID
            'ri_part' => $this->request->getPost('ri_part'),
            'ri_domain' => $this->request->getPost('ri_domain'),
            'ri_item_number' => $this->request->getPost('ri_item_number'),
            'ri_item_description' => $this->request->getPost('ri_item_description'),
            'ri_expected_level_description' => $this->request->getPost('ri_expected_level_description'),
            'ri_academic_standing' => $academic_standing === '' ? null : $academic_standing,
            'ri_position' => $position,
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
                              ->where('ri_position', $data['ri_position'])
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
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
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
            'ri_position' => $position,
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
        if ($_SESSION['status'] !== 'superadmin' && (!isset($_SESSION['rloes']) || strpos($_SESSION['rloes'], 'งานประเมิน pa') === false)) {
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