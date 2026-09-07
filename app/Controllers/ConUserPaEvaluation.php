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
        $data['full_url'] = current_url();
   
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

        try {
            $db_personnel = \Config\Database::connect('personnel');
            $db_skj = \Config\Database::connect('skj');
            $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

            $builder = $db_personnel->table('tb_personnel');

            // Perform joins with tables from the second database
            $builder->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left');
            $builder->join($db_skj->getDatabase() . '.tb_learning', 'tb_learning.lear_id = tb_personnel.pers_learning', 'left');

            $builder->where('tb_personnel.pers_status', "กำลังใช้งาน");
            $builder->notLike('tb_position.posi_name', 'ช่วยปฏิบัติงาน');
            $builder->notLike('tb_position.posi_name', 'ช่วยปฏิบัติการสอน');
            $builder->notLike('tb_position.posi_name', 'ช่วยสอน');
            $builder->notLike('tb_position.posi_name', 'ช่วยราชการ');

            // --- Start Assessor Scope Filtering ---
            $loggedInUserId = $session->get('id'); // Get the id of the logged-in user
            $loggedInPersId = $session->get('pers_id');
            $loggedInEmail  = $session->get('email');

            $possibleAssessorIds = array_filter([$loggedInUserId, $loggedInPersId]);

            // Look up matching evaluator record in tb_evaluators by id or username/email
            if (!empty($loggedInEmail) || !empty($loggedInUserId)) {
                $evalQuery = $db_pa_evaluation->table('tb_evaluators');
                if (!empty($loggedInUserId)) {
                    $evalQuery->orWhere('e_id', $loggedInUserId);
                }
                if (!empty($loggedInEmail)) {
                    $evalQuery->orWhere('e_Username', $loggedInEmail);
                }
                $evalRow = $evalQuery->get()->getRowArray();
                if ($evalRow) {
                    $possibleAssessorIds[] = $evalRow['e_id'];
                }
            }
            $possibleAssessorIds = array_values(array_unique(array_filter($possibleAssessorIds)));

            // คำนวณปีการศึกษาปัจจุบัน (รอบ ต.ค. - ก.ย.)
            $current_month = (int)date('m');
            $current_year_ad = (int)date('Y');
            $current_fiscal_year_be = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;

            $selected_fiscal_year = $this->request->getGet('fiscal_year');
            $fiscal_year_be = !empty($selected_fiscal_year) ? (int)$selected_fiscal_year : $current_fiscal_year_be;
            $fiscal_year_ad = $fiscal_year_be - 543;

            // Fetch scopes for the logged-in assessor filtered by selected fiscal year
            $assessorScopes = [];
            if (!empty($possibleAssessorIds)) {
                $rawScopes = $db_pa_evaluation->table('tb_assessor_scope')
                                                   ->whereIn('assessor_e_id', $possibleAssessorIds)
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

                // กรองเอาเฉพาะ scope ที่มีการผูกครู/ตำแหน่ง/กลุ่มสาระจริง ๆ
                foreach ($rawScopes as $s) {
                    if (!empty($s['scope_pers_id']) || !empty($s['scope_posi_id']) || !empty($s['scope_lear_id'])) {
                        $assessorScopes[] = $s;
                    }
                }
            }

            if (!empty($assessorScopes)) {
                // Build dynamic WHERE OR conditions based on assessor scopes
                $builder->groupStart();
                foreach ($assessorScopes as $scope) {
                    $builder->orGroupStart();
                    if (!empty($scope['scope_pers_id'])) {
                        $builder->where('tb_personnel.pers_id', $scope['scope_pers_id']);
                    } else {
                        if ($scope['scope_posi_id'] !== null) {
                            $builder->where('tb_personnel.pers_position', $scope['scope_posi_id']);
                        }
                        if ($scope['scope_lear_id'] !== null) {
                            $builder->where('tb_personnel.pers_learning', $scope['scope_lear_id']);
                        }
                    }
                    $builder->groupEnd();
                }
                $builder->groupEnd();
            } else {
                // If no specific scope is defined for the assessor in this fiscal year:
                // Superadmin, Admin, and Manager can view all personnel for testing & evaluation oversight.
                $userStatus = strtolower((string)$session->get('status'));
                $userRoles = (string)$session->get('rloes');
                $isSuperOrAdmin = in_array($userStatus, ['superadmin', 'admin', 'manager', 'adminpersonnel', 'managerpersonnel']) 
                                  || str_contains($userRoles, 'งานประเมิน pa');

                if (!$isSuperOrAdmin) {
                    $builder->where('1=0'); 
                }
            }
            // --- End Assessor Scope Filtering ---

            $query = $builder->get();
            $personnel = $query ? $query->getResultArray() : [];

            // Query PA Agreements for all personnel for the selected fiscal year
            $paAgreements = [];
            try {
                $agreementRows = $db_personnel->table('tb_teacher_pa_agreement')
                                              ->groupStart()
                                                  ->where('pa_year', $fiscal_year_be)
                                                  ->orWhere('pa_year', (string)$fiscal_year_be)
                                                  ->orWhere('pa_year', $fiscal_year_ad)
                                                  ->orWhere('pa_year', (string)$fiscal_year_ad)
                                              ->groupEnd()
                                              ->get()->getResultArray();
                foreach ($agreementRows as $arow) {
                    $paAgreements[$arow['pa_teacher_id']] = $arow;
                }
            } catch (\Throwable $e) {
                log_message('error', '[paPersonnelList] Fetch PA agreement failed: ' . $e->getMessage());
            }

            foreach ($personnel as &$p) {
                $evaluationExists = false;
                try {
                    $evalQuery = $db_pa_evaluation->table('tb_evaluator_scores')
                                                  ->join('tb_evaluations', 'tb_evaluations.ev_id = tb_evaluator_scores.ev_id')
                                                  ->where('tb_evaluations.t_id', $p['pers_id'])
                                                  ->groupStart()
                                                      ->where('tb_evaluations.ev_fiscal_year', $fiscal_year_be)
                                                      ->orWhere('tb_evaluations.ev_fiscal_year', (string)$fiscal_year_be)
                                                      ->orWhere('tb_evaluations.ev_fiscal_year', $fiscal_year_ad)
                                                      ->orWhere('tb_evaluations.ev_fiscal_year', (string)$fiscal_year_ad)
                                                  ->groupEnd();
                    if (!$isSuperOrAdmin) {
                        $evalQuery->whereIn('tb_evaluator_scores.e_id', $possibleAssessorIds);
                    }
                    $evaluationExists = $evalQuery->countAllResults() > 0;
                } catch (\Throwable $e) {
                    log_message('error', '[paPersonnelList] Check evaluation exists failed: ' . $e->getMessage());
                }
                $p['has_pa_evaluation'] = $evaluationExists;
                $p['pa_agreement'] = $paAgreements[$p['pers_id']] ?? null;
            }
            unset($p);

        } catch (\Throwable $e) {
            log_message('error', '[paPersonnelList] Exception: ' . $e->getMessage());
            $personnel = [];
        }

        $data = $this->DataMain();
        $data['title'] = "เลือกบุคลากรเพื่อประเมิน PA";
        $data['description'] = "รายชื่อบุคลากรสำหรับทำแบบประเมินผลการพัฒนางานตามข้อตกลง (PA)";
        $data['UrlMenuMain'] = 'PA_FORM';
        $data['UrlMenuSub'] = '';
        $data['personnel'] = $personnel;
        $data['selected_fiscal_year'] = $fiscal_year_be;
        $data['pa_upload_baseurl'] = env('upload.server.baseurl.pa_agreement', 'https://skj.nsnpao.go.th/uploads/personnel/teacher/pa_agreement/');

        return view('User/UserPA/PaPersonnelList', $data);
    }
  
    public function paForm($personId = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('pa-login?return_to=pa-form/' . $personId));
        }

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

        // คำนวณปีการศึกษาปัจจุบัน (รอบ ต.ค. - ก.ย.)
        $current_month = (int)date('m');
        $current_year_ad = (int)date('Y');
        $current_fiscal_year_be = ($current_month >= 10) ? $current_year_ad + 544 : $current_year_ad + 543;
        $selected_fiscal_year = $this->request->getGet('fiscal_year');
        $fiscal_year_be = !empty($selected_fiscal_year) ? (int)$selected_fiscal_year : $current_fiscal_year_be;
        $fiscal_year_ad = $fiscal_year_be - 543;

        // Fetch PA Agreement from tb_teacher_pa_agreement
        $paAgreement = null;
        try {
            $paAgreement = $database->table('tb_teacher_pa_agreement')
                                   ->where('pa_teacher_id', $personId)
                                   ->groupStart()
                                       ->where('pa_year', $fiscal_year_be)
                                       ->orWhere('pa_year', (string)$fiscal_year_be)
                                       ->orWhere('pa_year', $fiscal_year_ad)
                                       ->orWhere('pa_year', (string)$fiscal_year_ad)
                                   ->groupEnd()
                                   ->orderBy('pa_id', 'DESC')
                                   ->get()->getRowArray();
        } catch (\Throwable $e) {
            log_message('error', '[paForm] Fetch PA Agreement failed: ' . $e->getMessage());
        }

        // Temporarily fetch all rubric items for debugging purposes
        // ดึงวิทยฐานะของบุคลากร
        $academicStanding = empty($person['pers_academic']) ? 'ไม่มีวิทยฐานะ' : $person['pers_academic'];

        // ดึงหัวข้อการประเมินตามวิทยฐานะและตำแหน่ง
        $personPosition = $person['posi_name'];

        // --- Query for Part 1 with specific filters ---
        $part1Builder = $db_pa_evaluation->table('tb_rubric_items');
        $part1Builder->where('ri_part', 1);

        // Filter by Academic Standing for Part 1
        $part1Builder->groupStart();
        $part1Builder->where('ri_academic_standing', $academicStanding);
        $part1Builder->orWhere('ri_academic_standing', 'ทั่วไป');
        $part1Builder->orWhere('ri_academic_standing IS NULL');
        $part1Builder->orWhere('ri_academic_standing', '');
        $part1Builder->groupEnd();

        // Filter by Position for Part 1
        $part1Builder->groupStart();
        if (strpos($personPosition, 'ผู้อำนวยการ') !== false) {
            $part1Builder->like('ri_position', 'ผู้อำนวยการ', 'after');
        } else {
            $part1Builder->where('ri_position', $personPosition);
        }
        // $part1Builder->orWhere('ri_position IS NULL');
        // $part1Builder->orWhere('ri_position', '');
        $part1Builder->groupEnd();
        
        $part1Items = $part1Builder->get()->getResultArray();

        // --- Query for Part 2 (generic for all) ---
        $part2Builder = $db_pa_evaluation->table('tb_rubric_items');
        $part2Builder->where('ri_part', 2);
        $part2Items = $part2Builder->get()->getResultArray();

        // --- Merge and Sort Results ---
        $rubricItems = array_merge($part1Items, $part2Items);
        usort($rubricItems, function($a, $b) {
            if ($a['ri_part'] == $b['ri_part']) {
                return version_compare($a['ri_item_number'], $b['ri_item_number']);
            }
            return $a['ri_part'] < $b['ri_part'] ? -1 : 1;
        });

       // print_r($rubricItems); exit(); // Debug: Show the last executed query

        $loggedInUserId = $session->get('id');
        $loggedInPersId = $session->get('pers_id') ?: $loggedInUserId;
        $loggedInEmail  = $session->get('email');
        $userStatus     = strtolower((string)$session->get('status'));
        $userRoles      = (string)$session->get('rloes');
        $isSuperOrAdmin = in_array($userStatus, ['superadmin', 'admin', 'manager', 'adminpersonnel', 'managerpersonnel']) 
                          || str_contains($userRoles, 'งานประเมิน pa');

        // 1. Look up matching evaluator record in tb_evaluators
        $loggedInEvaluator = null;
        if (!empty($loggedInUserId) || !empty($loggedInPersId) || !empty($loggedInEmail)) {
            $evalQuery = $db_pa_evaluation->table('tb_evaluators');
            $evalQuery->groupStart();
            if (!empty($loggedInUserId)) {
                $evalQuery->orWhere('e_id', $loggedInUserId)->orWhere('e_Username', $loggedInUserId);
            }
            if (!empty($loggedInPersId)) {
                $evalQuery->orWhere('e_id', $loggedInPersId)->orWhere('e_Username', $loggedInPersId);
            }
            if (!empty($loggedInEmail)) {
                $evalQuery->orWhere('e_Username', $loggedInEmail);
            }
            $evalQuery->groupEnd();
            $loggedInEvaluator = $evalQuery->get()->getRowArray();
        }

        // If admin/superadmin has no evaluator account, link/create one so they can evaluate as admin
        if ($isSuperOrAdmin && !$loggedInEvaluator && !empty($loggedInPersId)) {
            $adminPerson = $database->table('tb_personnel')
                                    ->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left')
                                    ->where('pers_id', $loggedInPersId)
                                    ->get()->getRowArray();
            if ($adminPerson) {
                $e_id = 'e_' . $adminPerson['pers_id'];
                $username = !empty($adminPerson['pers_username']) ? $adminPerson['pers_username'] : strtolower($adminPerson['pers_firstname']);
                $existingByUsername = $db_pa_evaluation->table('tb_evaluators')->where('e_Username', $username)->get()->getRowArray();
                if ($existingByUsername) {
                    $loggedInEvaluator = $existingByUsername;
                } else {
                    $newEvaluatorData = [
                        'e_id' => $e_id,
                        'e_first_name' => $adminPerson['pers_firstname'],
                        'e_last_name' => $adminPerson['pers_lastname'],
                        'e_position' => $adminPerson['posi_name'] ?? 'ผู้ดูแลระบบ / ผู้ประเมิน',
                        'e_academic_standing' => !empty($adminPerson['pers_academic']) ? $adminPerson['pers_academic'] : 'ไม่มีวิทยฐานะ',
                        'e_organization' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
                        'e_Username' => $username,
                        'e_Password' => password_hash('123456', PASSWORD_DEFAULT),
                    ];
                    $db_pa_evaluation->table('tb_evaluators')->insert($newEvaluatorData);
                    $loggedInEvaluator = $newEvaluatorData;
                }
            }
        }

        // 2. Fetch all registered evaluators
        $allEvaluators = $db_pa_evaluation->table('tb_evaluators')->orderBy('e_first_name', 'ASC')->get()->getResultArray();
        $allEvaluatorsMap = array_column($allEvaluators, null, 'e_id');
        if ($loggedInEvaluator && !isset($allEvaluatorsMap[$loggedInEvaluator['e_id']])) {
            $allEvaluatorsMap[$loggedInEvaluator['e_id']] = $loggedInEvaluator;
            $allEvaluators[] = $loggedInEvaluator;
        }

        // 3. Fetch assigned scopes for this teacher in this fiscal year
        $rawScopes = $db_pa_evaluation->table('tb_assessor_scope')
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

        $assignedEvaluatorIds = [];
        foreach ($rawScopes as $scope) {
            $isMatch = false;
            if (!empty($scope['scope_pers_id'])) {
                if ((string)$scope['scope_pers_id'] === (string)$personId) {
                    $isMatch = true;
                }
            } else {
                $posMatch = empty($scope['scope_posi_id']) || ($scope['scope_posi_id'] === $person['pers_position']);
                $learMatch = empty($scope['scope_lear_id']) || ($scope['scope_lear_id'] === $person['pers_learning']);
                if (($scope['scope_posi_id'] !== null || $scope['scope_lear_id'] !== null) && $posMatch && $learMatch) {
                    $isMatch = true;
                }
            }
            if ($isMatch && !empty($scope['assessor_e_id'])) {
                $assignedEvaluatorIds[] = $scope['assessor_e_id'];
            }
        }
        $assignedEvaluatorIds = array_values(array_unique(array_filter($assignedEvaluatorIds)));

        // 4. Determine selected active evaluator
        $requestedEvalId = $this->request->getGet('evaluator_id');
        $evaluator = null;

        if (!empty($requestedEvalId) && isset($allEvaluatorsMap[$requestedEvalId])) {
            $evaluator = $allEvaluatorsMap[$requestedEvalId];
        } elseif ($loggedInEvaluator && in_array($loggedInEvaluator['e_id'], $assignedEvaluatorIds)) {
            // Logged-in user is one of the assigned evaluators for this teacher
            $evaluator = $loggedInEvaluator;
        } elseif ($isSuperOrAdmin) {
            // Admin: default to first assigned evaluator if exists, or logged in admin, or first in system
            if (!empty($assignedEvaluatorIds) && isset($allEvaluatorsMap[$assignedEvaluatorIds[0]])) {
                $evaluator = $allEvaluatorsMap[$assignedEvaluatorIds[0]];
            } elseif ($loggedInEvaluator) {
                $evaluator = $loggedInEvaluator;
            } elseif (!empty($allEvaluators)) {
                $evaluator = $allEvaluators[0];
            }
        } elseif ($loggedInEvaluator) {
            $evaluator = $loggedInEvaluator;
        }

        // 5. Query evaluation record and existing scores for this fiscal year
        $latestEvaluation = $db_pa_evaluation->table('tb_evaluations')
                                             ->where('t_id', $personId)
                                             ->groupStart()
                                                 ->where('ev_fiscal_year', $fiscal_year_be)
                                                 ->orWhere('ev_fiscal_year', (string)$fiscal_year_be)
                                                 ->orWhere('ev_fiscal_year', $fiscal_year_ad)
                                                 ->orWhere('ev_fiscal_year', (string)$fiscal_year_ad)
                                             ->groupEnd()
                                             ->orderBy('ev_id', 'DESC')
                                             ->get()->getRowArray();

        $existingScoresMap = [];
        if ($latestEvaluation) {
            $allScores = $db_pa_evaluation->table('tb_evaluator_scores')
                                          ->where('ev_id', $latestEvaluation['ev_id'])
                                          ->get()->getResultArray();
            foreach ($allScores as $sc) {
                $existingScoresMap[$sc['e_id']] = $sc;
            }
        }

        // 6. Build available evaluators list for admin dropdown
        $availableEvaluators = [];
        // First, add assigned evaluators
        foreach ($assignedEvaluatorIds as $aId) {
            if (isset($allEvaluatorsMap[$aId])) {
                $ev = $allEvaluatorsMap[$aId];
                $ev['is_assigned'] = true;
                $ev['has_evaluated'] = isset($existingScoresMap[$aId]);
                $ev['score_info'] = $existingScoresMap[$aId] ?? null;
                $availableEvaluators[$aId] = $ev;
            }
        }
        // Then, add logged-in admin if not already in list
        if ($loggedInEvaluator && !isset($availableEvaluators[$loggedInEvaluator['e_id']])) {
            $ev = $loggedInEvaluator;
            $ev['is_assigned'] = in_array($ev['e_id'], $assignedEvaluatorIds);
            $ev['has_evaluated'] = isset($existingScoresMap[$ev['e_id']]);
            $ev['score_info'] = $existingScoresMap[$ev['e_id']] ?? null;
            $availableEvaluators[$ev['e_id']] = $ev;
        }
        // Finally, for admin, also append other evaluators in system
        if ($isSuperOrAdmin) {
            foreach ($allEvaluators as $ev) {
                if (!isset($availableEvaluators[$ev['e_id']])) {
                    $ev['is_assigned'] = false;
                    $ev['has_evaluated'] = isset($existingScoresMap[$ev['e_id']]);
                    $ev['score_info'] = $existingScoresMap[$ev['e_id']] ?? null;
                    $availableEvaluators[$ev['e_id']] = $ev;
                }
            }
        }
        $availableEvaluators = array_values($availableEvaluators);

        // 7. Get scores for active evaluator
        $evaluatorScore = null;
        $itemScores = [];
        $rawItemScores = [];

        if ($latestEvaluation && $evaluator) {
            $evaluatorScore = $existingScoresMap[$evaluator['e_id']] ?? null;

            if ($evaluatorScore) {
                $itemScoresResult = $db_pa_evaluation->table('tb_item_scores')
                                                     ->where('es_id', $evaluatorScore['es_id'])
                                                     ->get()->getResultArray();
                foreach ($itemScoresResult as $score) {
                    $itemScores[$score['ri_id']] = $score['is_calculated_score'];
                    $rawItemScores[$score['ri_id']] = $score['is_score'];
                }
            }
        }

        $data = $this->DataMain();
        $data['title'] = "แบบประเมิน PA";
        $data['description'] = "แบบประเมินผลการพัฒนางานตามข้อตกลง (PA)";
        $data['UrlMenuMain'] = 'PA_FORM';
        $data['UrlMenuSub'] = '';
        $data['person'] = $person;
        $data['rubricItems'] = $rubricItems;
        $data['evaluator'] = $evaluator;
        $data['evaluatorScore'] = $evaluatorScore;
        $data['itemScores'] = $itemScores;
        $data['rawItemScores'] = $rawItemScores;
        $data['paAgreement'] = $paAgreement;
        $data['fiscal_year_be'] = $fiscal_year_be;
        $data['fiscal_year_ad'] = $fiscal_year_ad;
        $data['isSuperOrAdmin'] = $isSuperOrAdmin;
        $data['assignedEvaluatorIds'] = $assignedEvaluatorIds;
        $data['availableEvaluators'] = $availableEvaluators;
        $data['selected_evaluator_id'] = $evaluator['e_id'] ?? null;
        $data['pa_upload_baseurl'] = env('upload.server.baseurl.pa_agreement', 'https://skj.nsnpao.go.th/uploads/personnel/teacher/pa_agreement/');

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
            if (empty($evaluatorId)) {
                throw new \Exception('ไม่พบข้อมูลกรรมการผู้ประเมิน กรุณาระบุหรือเลือกกรรมการผู้ประเมินก่อนบันทึก.');
            }

            $academicYear = $this->request->getPost('academicYear');
            $evaluationPeriod = $this->request->getPost('evaluationPeriod');
            $strongPoints = $this->request->getPost('challengeDescription');
            $areasForImprovement = $this->request->getPost('challengeResult');
            $comments = $this->request->getPost('comments');
            $totalScore1 = $this->request->getPost('totalScore1');
            $totalScore2 = $this->request->getPost('totalScore2');
            $totalScore = $this->request->getPost('totalScore');

            // คำนวณช่วงวันที่เริ่มต้นและสิ้นสุดของปีงบประมาณไทย (1 ต.ค. - 30 ก.ย.)
            $yearInt = (int)$academicYear;
            $ad_end_year = $yearInt > 2500 ? $yearInt - 543 : $yearInt;
            $ad_start_year = $ad_end_year - 1;
            $startDate = $ad_start_year . '-10-01';
            $endDate = $ad_end_year . '-09-30';

            // Start a transaction
            $db = \Config\Database::connect('pa_evaluation');
            $db->transStart();

            try {
                // 1. ตรวจสอบว่ามีการประเมินหลักสำหรับบุคลากรคนนี้ในรอบปีนี้แล้วหรือไม่
                $existingEvaluation = $evaluationModel->where('t_id', $personId)
                                                      ->where('ev_fiscal_year', $academicYear)
                                                      ->first();

                $ev_id = $existingEvaluation['ev_id'] ?? uniqid('EV_');

                // 1.1. บันทึก/อัปเดต tb_evaluations
                $evaluationData = [
                    'ev_id' => $ev_id,
                    't_id' => $personId,
                    'ev_fiscal_year' => $academicYear,
                    'ev_start_date' => $startDate,
                    'ev_end_date' => $endDate,
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