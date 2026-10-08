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

        $personnel = [];
        $available_fiscal_years = [2569, 2568, 2567];
        $latest_fiscal_year = 2569;
        $selected_fiscal_year = $this->request->getGet('fiscal_year');
        $fiscal_year_be = !empty($selected_fiscal_year) ? (int)$selected_fiscal_year : $latest_fiscal_year;
        $fiscal_year_ad = $fiscal_year_be - 543;

        try {
            $db_personnel = \Config\Database::connect('personnel');
            $db_skj = \Config\Database::connect('skj');
            $db_pa_evaluation = \Config\Database::connect('pa_evaluation');

            // 1. ดึงปีงบประมาณที่มีข้อมูลจาก tb_teacher_pa_agreement, tb_teacher_evaluation และ tb_evaluations
            $db_years = [];
            try {
                $agreementYears = $db_personnel->table('tb_teacher_pa_agreement')
                    ->select('pa_year')
                    ->distinct()
                    ->where('pa_year IS NOT NULL')
                    ->where('pa_year !=', '')
                    ->where('pa_year !=', '0')
                    ->get()->getResultArray();
                foreach ($agreementYears as $dy) {
                    $yr = (int)($dy['pa_year'] ?? 0);
                    if ($yr > 2500) {
                        $db_years[] = $yr;
                    } elseif ($yr > 2000) {
                        $db_years[] = $yr + 543;
                    }
                }

                $teacherEvalYears = $db_personnel->table('tb_teacher_evaluation')
                    ->select('eva_year')
                    ->distinct()
                    ->where('eva_year IS NOT NULL')
                    ->where('eva_year !=', '')
                    ->where('eva_year !=', '0')
                    ->get()->getResultArray();
                foreach ($teacherEvalYears as $ty) {
                    $yr = (int)($ty['eva_year'] ?? 0);
                    if ($yr > 2500) {
                        $db_years[] = $yr;
                    } elseif ($yr > 2000) {
                        $db_years[] = $yr + 543;
                    }
                }

                $evalYears = $db_pa_evaluation->table('tb_evaluations')
                    ->select('ev_fiscal_year')
                    ->distinct()
                    ->where('ev_fiscal_year IS NOT NULL')
                    ->where('ev_fiscal_year !=', '')
                    ->where('ev_fiscal_year !=', '0')
                    ->get()->getResultArray();
                foreach ($evalYears as $ey) {
                    $yr = (int)($ey['ev_fiscal_year'] ?? 0);
                    if ($yr > 2500) {
                        $db_years[] = $yr;
                    } elseif ($yr > 2000) {
                        $db_years[] = $yr + 543;
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', '[paPersonnelList] Fetch years failed: ' . $e->getMessage());
            }

            $db_years = array_values(array_unique(array_filter($db_years)));
            rsort($db_years);

            if (!empty($db_years)) {
                $latest_fiscal_year = $db_years[0];
                foreach ($db_years as $dy) {
                    if (!in_array($dy, $available_fiscal_years)) {
                        $available_fiscal_years[] = $dy;
                    }
                }
                rsort($available_fiscal_years);
                if (empty($selected_fiscal_year)) {
                    $fiscal_year_be = $latest_fiscal_year;
                    $fiscal_year_ad = $fiscal_year_be - 543;
                }
            }

            $builder = $db_personnel->table('tb_personnel');

            // Perform joins with tables from the second database
            $builder->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left');
            $builder->join($db_skj->getDatabase() . '.tb_learning', 'tb_learning.lear_id = tb_personnel.pers_learning', 'left');

            $builder->where('tb_personnel.pers_status', "กำลังใช้งาน");

            // กรองเฉพาะ ครู ข้าราชการ, รอง ผอ. และ ผอ. (ผู้บริหารสถานศึกษา)
            $builder->groupStart()
                ->whereIn('tb_position.posi_id', ['posi_001', 'posi_002', 'posi_003', 'posi_004', 'posi_005'])
                ->orWhereIn('tb_position.posi_name', ['ครู', 'ครูผู้ช่วย', 'ผู้อำนวยการสถานศึกษา', 'รองผู้อำนวยการสถานศึกษา', 'ผู้อำนวยการ', 'รองผู้อำนวยการ'])
                ->orGroupStart()
                    ->groupStart()
                        ->like('tb_position.posi_name', 'ครู')
                        ->orLike('tb_position.posi_name', 'ผู้อำนวยการ')
                        ->orLike('tb_position.posi_name', 'รองผู้อำนวยการ')
                    ->groupEnd()
                    ->notLike('tb_position.posi_name', 'ช่วยปฏิบัติการสอน')
                    ->notLike('tb_position.posi_name', 'ช่วยปฏิบัติงาน')
                    ->notLike('tb_position.posi_name', 'ช่วยสอน')
                    ->notLike('tb_position.posi_name', 'ช่วยราชการ')
                    ->notLike('tb_position.posi_name', 'พนักงาน')
                    ->notLike('tb_position.posi_name', 'ผู้กำกับ')
                ->groupEnd()
            ->groupEnd();

            // --- Start Assessor Scope Filtering ---
            $loggedInUserId = $session->get('id'); // Get the id of the logged-in user
            $loggedInPersId = $session->get('pers_id') ?: $loggedInUserId;
            $loggedInEmail  = $session->get('email');

            // ดึงข้อมูลครู/บุคลากรที่กำลังล็อกอิน เพื่อนำชื่อ-นามสกุล และ username มาค้นหาผู้ประเมินให้ครอบคลุม
            $loggedPerson = null;
            if (!empty($loggedInPersId) || !empty($loggedInUserId) || !empty($loggedInEmail)) {
                $pQuery = $db_personnel->table('tb_personnel');
                if (!empty($loggedInPersId)) {
                    $pQuery->orWhere('pers_id', $loggedInPersId);
                }
                if (!empty($loggedInUserId)) {
                    $pQuery->orWhere('pers_id', $loggedInUserId)->orWhere('pers_username', $loggedInUserId);
                }
                if (!empty($loggedInEmail)) {
                    $pQuery->orWhere('pers_username', $loggedInEmail);
                }
                $loggedPerson = $pQuery->get()->getRowArray();
            }

            $possibleAssessorIds = array_filter([$loggedInUserId, $loggedInPersId, !empty($loggedInPersId) ? 'e_' . $loggedInPersId : null]);

            // ค้นหาผู้ประเมินใน tb_evaluators ทั้งหมดที่ตรงกับผู้ใช้นี้ (รองรับกรณีชื่อซ้ำ / มีหลาย e_id / บันทึกด้วย username หรือ email)
            $hasEvalConditions = false;
            $evalQuery = $db_pa_evaluation->table('tb_evaluators')->groupStart();
            if (!empty($loggedInUserId)) {
                $evalQuery->orWhere('e_id', $loggedInUserId)->orWhere('e_Username', $loggedInUserId);
                $hasEvalConditions = true;
            }
            if (!empty($loggedInPersId)) {
                $evalQuery->orWhere('e_id', $loggedInPersId)->orWhere('e_id', 'e_' . $loggedInPersId)->orWhere('e_Username', $loggedInPersId);
                $hasEvalConditions = true;
            }
            if (!empty($loggedInEmail)) {
                $evalQuery->orWhere('e_Username', $loggedInEmail);
                $hasEvalConditions = true;
                $emailPrefix = explode('@', $loggedInEmail)[0];
                if (!empty($emailPrefix)) {
                    $evalQuery->orWhere('e_Username', $emailPrefix);
                }
            }
            if (!empty($loggedPerson)) {
                if (!empty($loggedPerson['pers_username'])) {
                    $evalQuery->orWhere('e_Username', $loggedPerson['pers_username']);
                    $hasEvalConditions = true;
                }
                if (!empty($loggedPerson['pers_firstname']) && !empty($loggedPerson['pers_lastname'])) {
                    $evalQuery->orGroupStart()
                        ->where('e_first_name', trim($loggedPerson['pers_firstname']))
                        ->where('e_last_name', trim($loggedPerson['pers_lastname']))
                    ->groupEnd();
                    $hasEvalConditions = true;
                }
            }
            $evalQuery->groupEnd();
            $matchedEvaluators = $hasEvalConditions ? $evalQuery->get()->getResultArray() : [];

            foreach ($matchedEvaluators as $evRow) {
                if (!empty($evRow['e_id'])) {
                    $possibleAssessorIds[] = $evRow['e_id'];
                }
            }
            $possibleAssessorIds = array_values(array_unique(array_filter($possibleAssessorIds)));

            // Identify Superadmin / Admin / Manager / งานประเมิน PA
            $userStatus = strtolower((string)$session->get('status'));
            $userRoles  = (string)$session->get('rloes');
            $isSuperOrAdmin = in_array($userStatus, ['superadmin', 'admin', 'manager', 'adminpersonnel', 'managerpersonnel', 'administrator']) 
                              || stripos($userRoles, 'งานประเมิน') !== false
                              || stripos($userRoles, 'admin') !== false
                              || stripos($userRoles, 'superadmin') !== false
                              || stripos($userRoles, 'ผู้ดูแลระบบ') !== false;

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

            // ถ้าเป็น Admin / Superadmin / ผู้ดูแลระบบ ให้มองเห็นบุคลากรทั้งหมด (ไม่จำกัดด้วย scope)
            // ถ้าเป็นกรรมการประเมินทั่วไป ให้กรองเฉพาะครูในขอบเขตการประเมิน (scope) ของตนเอง
            if (!$isSuperOrAdmin) {
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
                    $builder->where('1=0'); 
                }
            }
            $builder->orderBy('tb_position.posi_id', 'ASC')
                    ->orderBy('tb_personnel.pers_learning', 'ASC')
                    ->orderBy('tb_personnel.pers_firstname', 'ASC');

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

                // Fallback: หากคุณครูคนใดยังไม่มีใน tb_teacher_pa_agreement ให้ดึงจาก tb_teacher_evaluation
                $evaRows = $db_personnel->table('tb_teacher_evaluation')
                                        ->groupStart()
                                            ->where('eva_year', $fiscal_year_be)
                                            ->orWhere('eva_year', (string)$fiscal_year_be)
                                            ->orWhere('eva_year', $fiscal_year_ad)
                                            ->orWhere('eva_year', (string)$fiscal_year_ad)
                                        ->groupEnd()
                                        ->orderBy('eva_round', 'DESC')
                                        ->get()->getResultArray();
                foreach ($evaRows as $erow) {
                    $tId = $erow['eva_teacher_id'];
                    if (!isset($paAgreements[$tId]) && (!empty($erow['eva_file']) || !empty($erow['eva_canva_link']))) {
                        $paAgreements[$tId] = [
                            'pa_id' => 'eva_' . $erow['eva_id'],
                            'pa_teacher_id' => $tId,
                            'pa_year' => $erow['eva_year'],
                            'pa_presentation_link' => $erow['eva_canva_link'] ?? '',
                            'pa_file_presentation' => null,
                            'pa_file_lesson_plan' => null,
                            'pa_file_pa1' => $erow['eva_file'] ?? '',
                            'pa_file_pa1_source' => 'evaluation',
                            'pa_round' => $erow['eva_round'] ?? 1,
                            'pa_status' => $erow['eva_status'] ?? 'submitted',
                            'pa_comment' => $erow['eva_comment'] ?? '',
                            'pa_created_at' => $erow['eva_created_at'] ?? null,
                            'pa_updated_at' => $erow['eva_updated_at'] ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', '[paPersonnelList] Fetch PA agreement failed: ' . $e->getMessage());
            }

            foreach ($personnel as &$p) {
                $evaluationExists = false;
                $evaluatorCount = 0;
                $evaluatedByMe = false;
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
                    
                    $allEvaluatorScores = $evalQuery->get()->getResultArray();
                    $evaluatorCount = count($allEvaluatorScores);
                    
                    if (!empty($possibleAssessorIds)) {
                        foreach ($allEvaluatorScores as $sc) {
                            if (in_array($sc['e_id'], $possibleAssessorIds)) {
                                $evaluatedByMe = true;
                                break;
                            }
                        }
                    }

                    // สำหรับ Admin / Superadmin: หากมีผลการประเมินในระบบ ให้แสดงว่าประเมินแล้ว
                    // สำหรับ กรรมการทั่วไป: แสดงว่าประเมินแล้ว เมื่อตนเองได้ทำการประเมินแล้ว
                    if ($isSuperOrAdmin) {
                        $evaluationExists = ($evaluatorCount > 0);
                    } else {
                        $evaluationExists = $evaluatedByMe;
                    }
                } catch (\Throwable $e) {
                    log_message('error', '[paPersonnelList] Check evaluation exists failed: ' . $e->getMessage());
                }
                $p['has_pa_evaluation'] = $evaluationExists;
                $p['evaluator_count'] = $evaluatorCount;
                $p['evaluated_by_me'] = $evaluatedByMe;
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
        $data['available_fiscal_years'] = $available_fiscal_years;
        $data['latest_fiscal_year'] = $latest_fiscal_year;
        $data['pa_upload_baseurl'] = env('upload.server.baseurl.pa_agreement', 'https://skj.nsnpao.go.th/uploads/personnel/teacher/pa_agreement/');
        $data['eva_upload_baseurl'] = env('upload.server.baseurl.evaluation', 'https://skj.nsnpao.go.th/uploads/personnel/teacher/evaluation/');

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

        // ตรวจสอบปีงบประมาณล่าสุดจากฐานข้อมูลหากไม่ได้ระบุใน URL
        $selected_fiscal_year = $this->request->getGet('fiscal_year');
        if (!empty($selected_fiscal_year)) {
            $fiscal_year_be = (int)$selected_fiscal_year;
        } else {
            $latestDbYear = null;
            try {
                $latestRow = $database->table('tb_teacher_pa_agreement')
                    ->select('pa_year')
                    ->where('pa_teacher_id', $personId)
                    ->orderBy('pa_year', 'DESC')
                    ->get()->getRowArray();
                if (!empty($latestRow['pa_year'])) {
                    $yVal = (int)$latestRow['pa_year'];
                    $latestDbYear = ($yVal > 2500) ? $yVal : ($yVal + 543);
                } else {
                    $latestEva = $database->table('tb_teacher_evaluation')
                        ->select('eva_year')
                        ->where('eva_teacher_id', $personId)
                        ->orderBy('eva_year', 'DESC')
                        ->get()->getRowArray();
                    if (!empty($latestEva['eva_year'])) {
                        $yVal = (int)$latestEva['eva_year'];
                        $latestDbYear = ($yVal > 2500) ? $yVal : ($yVal + 543);
                    }
                }
            } catch (\Throwable $e) {}

            $fiscal_year_be = $latestDbYear ?: 2569;
        }
        $fiscal_year_ad = $fiscal_year_be - 543;

        // Fetch PA Agreement from tb_teacher_pa_agreement with fallback to tb_teacher_evaluation
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

            if (empty($paAgreement) || (empty($paAgreement['pa_file_pa1']) && empty($paAgreement['pa_file_lesson_plan']) && empty($paAgreement['pa_file_presentation']) && empty($paAgreement['pa_presentation_link']))) {
                $evaRow = $database->table('tb_teacher_evaluation')
                                   ->where('eva_teacher_id', $personId)
                                   ->groupStart()
                                       ->where('eva_year', $fiscal_year_be)
                                       ->orWhere('eva_year', (string)$fiscal_year_be)
                                       ->orWhere('eva_year', $fiscal_year_ad)
                                       ->orWhere('eva_year', (string)$fiscal_year_ad)
                                   ->groupEnd()
                                   ->orderBy('eva_round', 'DESC')
                                   ->get()->getRowArray();
                if (!empty($evaRow) && (!empty($evaRow['eva_file']) || !empty($evaRow['eva_canva_link']))) {
                    $paAgreement = [
                        'pa_id' => 'eva_' . $evaRow['eva_id'],
                        'pa_teacher_id' => $personId,
                        'pa_year' => $evaRow['eva_year'],
                        'pa_presentation_link' => $evaRow['eva_canva_link'] ?? '',
                        'pa_file_presentation' => null,
                        'pa_file_lesson_plan' => null,
                        'pa_file_pa1' => $evaRow['eva_file'] ?? '',
                        'pa_file_pa1_source' => 'evaluation',
                        'pa_round' => $evaRow['eva_round'] ?? 1,
                        'pa_status' => $evaRow['eva_status'] ?? 'submitted',
                        'pa_comment' => $evaRow['eva_comment'] ?? '',
                        'pa_created_at' => $evaRow['eva_created_at'] ?? null,
                        'pa_updated_at' => $evaRow['eva_updated_at'] ?? null,
                    ];
                }
            }
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
        $isSuperOrAdmin = in_array($userStatus, ['superadmin', 'admin', 'manager', 'adminpersonnel', 'managerpersonnel', 'administrator']) 
                          || stripos($userRoles, 'งานประเมิน') !== false
                          || stripos($userRoles, 'admin') !== false
                          || stripos($userRoles, 'superadmin') !== false
                          || stripos($userRoles, 'ผู้ดูแลระบบ') !== false;

        // 1. Look up all matching evaluator records in tb_evaluators for the current user
        $loggedPerson = null;
        if (!empty($loggedInPersId) || !empty($loggedInUserId) || !empty($loggedInEmail)) {
            $pQuery = $database->table('tb_personnel')
                               ->join($db_skj->getDatabase() . '.tb_position', 'tb_position.posi_id = tb_personnel.pers_position', 'left');
            if (!empty($loggedInPersId)) {
                $pQuery->orWhere('pers_id', $loggedInPersId);
            }
            if (!empty($loggedInUserId)) {
                $pQuery->orWhere('pers_id', $loggedInUserId)->orWhere('pers_username', $loggedInUserId);
            }
            if (!empty($loggedInEmail)) {
                $pQuery->orWhere('pers_username', $loggedInEmail);
            }
            $loggedPerson = $pQuery->get()->getRowArray();
        }

        $myEvaluatorRecords = [];
        $myEvaluatorIds = array_filter([$loggedInUserId, $loggedInPersId, !empty($loggedInPersId) ? 'e_' . $loggedInPersId : null]);

        $hasEvalConditions = false;
        $evalQuery = $db_pa_evaluation->table('tb_evaluators')->groupStart();
        if (!empty($loggedInUserId)) {
            $evalQuery->orWhere('e_id', $loggedInUserId)->orWhere('e_Username', $loggedInUserId);
            $hasEvalConditions = true;
        }
        if (!empty($loggedInPersId)) {
            $evalQuery->orWhere('e_id', $loggedInPersId)->orWhere('e_id', 'e_' . $loggedInPersId)->orWhere('e_Username', $loggedInPersId);
            $hasEvalConditions = true;
        }
        if (!empty($loggedInEmail)) {
            $evalQuery->orWhere('e_Username', $loggedInEmail);
            $hasEvalConditions = true;
            $emailPrefix = explode('@', $loggedInEmail)[0];
            if (!empty($emailPrefix)) {
                $evalQuery->orWhere('e_Username', $emailPrefix);
            }
        }
        if (!empty($loggedPerson)) {
            if (!empty($loggedPerson['pers_username'])) {
                $evalQuery->orWhere('e_Username', $loggedPerson['pers_username']);
                $hasEvalConditions = true;
            }
            if (!empty($loggedPerson['pers_firstname']) && !empty($loggedPerson['pers_lastname'])) {
                $evalQuery->orGroupStart()
                    ->where('e_first_name', trim($loggedPerson['pers_firstname']))
                    ->where('e_last_name', trim($loggedPerson['pers_lastname']))
                ->groupEnd();
                $hasEvalConditions = true;
            }
        }
        $evalQuery->groupEnd();
        $myEvaluatorRecords = $hasEvalConditions ? $evalQuery->get()->getResultArray() : [];
        foreach ($myEvaluatorRecords as $me) {
            if (!empty($me['e_id'])) {
                $myEvaluatorIds[] = $me['e_id'];
            }
        }
        $myEvaluatorIds = array_values(array_unique(array_filter($myEvaluatorIds)));
        $loggedInEvaluator = !empty($myEvaluatorRecords) ? $myEvaluatorRecords[0] : null;

        // If admin/superadmin has no evaluator account, link/create one so they can evaluate as admin
        if ($isSuperOrAdmin && !$loggedInEvaluator && !empty($loggedPerson)) {
            $e_id = 'e_' . $loggedPerson['pers_id'];
            $username = !empty($loggedPerson['pers_username']) ? $loggedPerson['pers_username'] : strtolower($loggedPerson['pers_firstname']);
            $existingByUsername = $db_pa_evaluation->table('tb_evaluators')->where('e_Username', $username)->get()->getRowArray();
            if ($existingByUsername) {
                $loggedInEvaluator = $existingByUsername;
            } else {
                $newEvaluatorData = [
                    'e_id' => $e_id,
                    'e_first_name' => $loggedPerson['pers_firstname'],
                    'e_last_name' => $loggedPerson['pers_lastname'],
                    'e_position' => $loggedPerson['posi_name'] ?? 'ผู้ดูแลระบบ / ผู้ประเมิน',
                    'e_academic_standing' => !empty($loggedPerson['pers_academic']) ? $loggedPerson['pers_academic'] : 'ไม่มีวิทยฐานะ',
                    'e_organization' => 'โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์',
                    'e_Username' => $username,
                    'e_Password' => password_hash('123456', PASSWORD_DEFAULT),
                ];
                $db_pa_evaluation->table('tb_evaluators')->insert($newEvaluatorData);
                $loggedInEvaluator = $newEvaluatorData;
            }
            if (!in_array($loggedInEvaluator['e_id'], $myEvaluatorIds)) {
                $myEvaluatorIds[] = $loggedInEvaluator['e_id'];
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

        // 4. Determine selected active evaluator (รองรับการตรวจจับข้ามทุก alias ID ของผู้ใช้ที่ล็อกอิน)
        $requestedEvalId = $this->request->getGet('evaluator_id');
        $evaluator = null;

        // ตรวจหาว่ามี ID ใดของผู้ใช้นี้ถูกมอบหมายใน scope หรือไม่
        $assignedMatchForUser = null;
        foreach ($myEvaluatorIds as $meId) {
            if (in_array($meId, $assignedEvaluatorIds) && isset($allEvaluatorsMap[$meId])) {
                $assignedMatchForUser = $allEvaluatorsMap[$meId];
                break;
            }
        }

        if (!empty($requestedEvalId) && isset($allEvaluatorsMap[$requestedEvalId])) {
            $evaluator = $allEvaluatorsMap[$requestedEvalId];
        } elseif ($assignedMatchForUser) {
            // ผู้ใช้ล็อกอินมีสิทธิ์ตรงตาม scope (แม้จะมีหลาย e_id ในฐานข้อมูล)
            $evaluator = $assignedMatchForUser;
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
        $data['eva_upload_baseurl'] = env('upload.server.baseurl.evaluation', 'https://skj.nsnpao.go.th/uploads/personnel/teacher/evaluation/');

        return view('User/UserPA/PaForm', $data);
    }

    public function savePaEvaluation()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่อีกครั้ง']);
            }
            return redirect()->to(base_url('pa-login'))->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $method = strtoupper((string)$this->request->getMethod());
        if ($this->request->is('post') || $method === 'POST') {
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
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูลผู้รับการประเมินในระบบบุคลากร.']);
                }
                throw new \Exception('ไม่พบข้อมูลผู้รับการประเมินในระบบบุคลากร.');
            }

            $db = \Config\Database::connect('pa_evaluation');

            $evaluatorId = $this->request->getPost('evaluator_id');
            $loggedInUserId = $session->get('id');
            $loggedInPersId = $session->get('pers_id') ?: $loggedInUserId;
            $loggedInEmail  = $session->get('email');

            // หากไม่ได้รับ evaluator_id จากฟอร์ม ให้สืบค้นจากผู้ใช้งานที่ล็อกอินอยู่
            $loggedPerson = null;
            if (!empty($loggedInPersId) || !empty($loggedInUserId) || !empty($loggedInEmail)) {
                $pQuery = $db_personnel->table('tb_personnel');
                if (!empty($loggedInPersId)) {
                    $pQuery->orWhere('pers_id', $loggedInPersId);
                }
                if (!empty($loggedInUserId)) {
                    $pQuery->orWhere('pers_id', $loggedInUserId)->orWhere('pers_username', $loggedInUserId);
                }
                if (!empty($loggedInEmail)) {
                    $pQuery->orWhere('pers_username', $loggedInEmail);
                }
                $loggedPerson = $pQuery->get()->getRowArray();
            }

            if (empty($evaluatorId)) {
                $hasEvalConditions = false;
                $evalQuery = $db->table('tb_evaluators')->groupStart();
                if (!empty($loggedInUserId)) {
                    $evalQuery->orWhere('e_id', $loggedInUserId)->orWhere('e_Username', $loggedInUserId);
                    $hasEvalConditions = true;
                }
                if (!empty($loggedInPersId)) {
                    $evalQuery->orWhere('e_id', $loggedInPersId)->orWhere('e_id', 'e_' . $loggedInPersId)->orWhere('e_Username', $loggedInPersId);
                    $hasEvalConditions = true;
                }
                if (!empty($loggedInEmail)) {
                    $evalQuery->orWhere('e_Username', $loggedInEmail);
                    $hasEvalConditions = true;
                    $emailPrefix = explode('@', $loggedInEmail)[0];
                    if (!empty($emailPrefix)) {
                        $evalQuery->orWhere('e_Username', $emailPrefix);
                    }
                }
                if (!empty($loggedPerson)) {
                    if (!empty($loggedPerson['pers_username'])) {
                        $evalQuery->orWhere('e_Username', $loggedPerson['pers_username']);
                        $hasEvalConditions = true;
                    }
                    if (!empty($loggedPerson['pers_firstname']) && !empty($loggedPerson['pers_lastname'])) {
                        $evalQuery->orGroupStart()
                            ->where('e_first_name', trim($loggedPerson['pers_firstname']))
                            ->where('e_last_name', trim($loggedPerson['pers_lastname']))
                        ->groupEnd();
                        $hasEvalConditions = true;
                    }
                }
                $evalQuery->groupEnd();
                $foundEv = $hasEvalConditions ? $evalQuery->get()->getRowArray() : null;
                if ($foundEv) {
                    $evaluatorId = $foundEv['e_id'];
                }
            }

            // ตรวจสอบว่า evaluatorId มีอยู่ใน tb_evaluators หรือไม่ ถ้าไม่มีให้ลองจับคู่หรือลงทะเบียนให้อัตโนมัติ
            if (!empty($evaluatorId)) {
                $evRecord = $db->table('tb_evaluators')->where('e_id', $evaluatorId)->get()->getRowArray();
                if (!$evRecord) {
                    $evRecord2 = $db->table('tb_evaluators')->where('e_Username', $evaluatorId)->get()->getRowArray();
                    if ($evRecord2) {
                        $evaluatorId = $evRecord2['e_id'];
                    } else {
                        // ตรวจสอบกรณีเป็นครู/ผู้ดูแลระบบที่ส่ง pers_id เข้ามา
                        $personData = $db_personnel->table('tb_personnel')->where('pers_id', $evaluatorId)->get()->getRowArray();
                        if ($personData) {
                            $uName = !empty($personData['pers_username']) ? $personData['pers_username'] : strtolower($personData['pers_firstname']);
                            
                            // ค้นหาว่ามีอยู่แล้วด้วยชื่อหรือ username หรือไม่
                            $existingMatch = $db->table('tb_evaluators')
                                                ->groupStart()
                                                    ->where('e_Username', $uName)
                                                    ->orWhere('e_id', 'e_' . $personData['pers_id'])
                                                    ->orWhere('e_id', $personData['pers_id'])
                                                    ->orGroupStart()
                                                        ->where('e_first_name', trim($personData['pers_firstname']))
                                                        ->where('e_last_name', trim($personData['pers_lastname']))
                                                    ->groupEnd()
                                                ->groupEnd()
                                                ->get()->getRowArray();
                            if ($existingMatch) {
                                $evaluatorId = $existingMatch['e_id'];
                            } else {
                                $newEid = 'e_' . $personData['pers_id'];
                                $db->table('tb_evaluators')->insert([
                                    'e_id' => $newEid,
                                    'e_first_name' => $personData['pers_firstname'],
                                    'e_last_name' => $personData['pers_lastname'],
                                    'e_position' => 'กรรมการผู้ประเมิน',
                                    'e_Username' => $uName,
                                    'e_Password' => password_hash('123456', PASSWORD_DEFAULT),
                                ]);
                                $evaluatorId = $newEid;
                            }
                        }
                    }
                }
            }

            if (empty($evaluatorId)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูลกรรมการผู้ประเมิน กรุณาระบุหรือเลือกกรรมการผู้ประเมินก่อนบันทึก.']);
                }
                throw new \Exception('ไม่พบข้อมูลกรรมการผู้ประเมิน กรุณาระบุหรือเลือกกรรมการผู้ประเมินก่อนบันทึก.');
            }

            $academicYear = $this->request->getPost('academicYear');
            $evaluationPeriod = $this->request->getPost('evaluationPeriod');
            $strongPoints = $this->request->getPost('challengeDescription');
            $areasForImprovement = $this->request->getPost('challengeResult');
            $comments = $this->request->getPost('comments');
            // คะแนน PA ต้อง "ตัด" ทศนิยม ไม่ใช่ปัดเศษ
            // เช่น 14.359 -> 14.35 (ไม่ใช่ 14.36)
            $truncateScore = static function ($value, int $decimals = 2): float {
                $factor = 10 ** $decimals;
                $number = (float)$value;
                return $number >= 0
                    ? floor($number * $factor + 1e-9) / $factor
                    : ceil($number * $factor - 1e-9) / $factor;
            };

            $totalScore1 = $truncateScore($this->request->getPost('totalScore1'));
            $totalScore2 = $truncateScore($this->request->getPost('totalScore2'));
            $totalScore = $truncateScore($totalScore1 + $totalScore2);

            // คำนวณช่วงวันที่เริ่มต้นและสิ้นสุดของปีงบประมาณไทย (1 ต.ค. - 30 ก.ย.)
            $yearInt = (int)$academicYear;
            $ad_end_year = $yearInt > 2500 ? $yearInt - 543 : $yearInt;
            $ad_start_year = $ad_end_year - 1;
            $startDate = $ad_start_year . '-10-01';
            $endDate = $ad_end_year . '-09-30';

            // Start a transaction
            $db->transStart();

            try {
                // 1. ตรวจสอบว่ามีการประเมินหลักสำหรับบุคลากรคนนี้ในรอบปีนี้แล้วหรือไม่
                $existingEvaluation = $evaluationModel->where('t_id', $personId)
                                                      ->groupStart()
                                                          ->where('ev_fiscal_year', $academicYear)
                                                          ->orWhere('ev_fiscal_year', (string)$academicYear)
                                                          ->orWhere('ev_fiscal_year', $ad_end_year)
                                                          ->orWhere('ev_fiscal_year', (string)$ad_end_year)
                                                      ->groupEnd()
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
                        $is_calculated_score = $truncateScore((float)$value); // ตัดทศนิยม 2 ตำแหน่ง ไม่ปัดเศษ
                        $is_score = $this->request->getPost('raw_score_' . $ri_id); // This is the raw radio button value

                        // Only save if a raw score (radio button) was selected
                        if ($is_score !== null && $is_score !== '') {
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
                        return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูลในฐานข้อมูล']);
                    } else {
                        return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูลในฐานข้อมูล');
                    }
                } else {
                    // Transaction successful
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => true, 'message' => 'บันทึกแบบประเมินสำเร็จเรียบร้อยแล้ว']);
                    } else {
                        return redirect()->to(base_url('user/pa-evaluation/success'))->with('success', 'บันทึกแบบประเมินสำเร็จเรียบร้อยแล้ว');
                    }
                }

            } catch (\Throwable $e) {
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
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method: ' . $this->request->getMethod()]);
        } else {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
    }
}