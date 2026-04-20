<?php

namespace App\Controllers;

class ConLogin extends BaseController
{
    private $GoogleButton = "";

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $config = config('Google');
        $params = [
            'client_id'     => $config->clientId,
            'redirect_uri'  => $config->redirectUri,
            'response_type' => 'code',
            'scope'         => 'email profile openid',
            'access_type'   => 'online',
            'prompt'        => 'select_account'
        ];
        
        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        $this->GoogleButton = '<a href="'.$authUrl.'" class="btn btn-primary me-3 w-auto"><i class="tf-icons bx bxl-google-plus"></i> Login by Google </a>';
    }

    public function DataMain(){
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['GoogleButton'] = $this->GoogleButton;
        $data['uri'] = service('uri'); 
        return $data;
    }

       
    public function LoginOfficerPersonnel(){
      
        $data = $this->DataMain();
        $data['title']="หน้าแรก";
        $data['description']="เข้าสู่ระบบ";
        $data['UrlMenuMain'] = 'LoginOfficerPersonnel';
        $data['UrlMenuSub'] = '';
        

        $session = session();
        $DB_Personnel = \Config\Database::connect();
        $DBrloes = $DB_Personnel->table('tb_admin_rloes');
        $DBPers = $DB_Personnel->table('tb_personnel');     
        
        if($this->request->getVar("return_to") != ""){
            session()->set('Return',$this->request->getVar("return_to"));
        }
        
        $code = $this->request->getVar('code');
        if($code){
            $config = config('Google');
            $curl = \Config\Services::curlrequest();
            
            // Debug: log redirect_uri being used
            log_message('error', '[GoogleLogin] redirect_uri: ' . $config->redirectUri);
            log_message('error', '[GoogleLogin] code: ' . substr($code, 0, 20) . '...');

            try {
                // 1. Exchange code for access_token and id_token
                $response = $curl->post('https://oauth2.googleapis.com/token', [
                    'version' => 1.1,
                    'http_errors' => false,
                    'form_params' => [
                        'code'          => $code,
                        'client_id'     => $config->clientId,
                        'client_secret' => $config->clientSecret,
                        'redirect_uri'  => $config->redirectUri,
                        'grant_type'    => 'authorization_code',
                    ],
                ]);
                $responseBody = $response->getBody();
                $tokens = json_decode($responseBody, true);
                
                // Debug: log Google's response
                log_message('error', '[GoogleLogin] Token response HTTP: ' . $response->getStatusCode());
                log_message('error', '[GoogleLogin] Token response body: ' . $responseBody);

                // Check if Google returned an error
                if (isset($tokens['error'])) {
                     $error_desc = isset($tokens['error_description']) ? $tokens['error_description'] : $tokens['error'];
                     log_message('error', '[GoogleLogin] Google Error: ' . $error_desc);
                     session()->setFlashdata('Error', 'Google Error: ' . $error_desc);
                     return redirect()->to(base_url('LoginOfficerPersonnel'));
                }

                if (isset($tokens['id_token'])) {
                    // Modern way: Decode id_token (JWT) to get user profile directly
                    $jwt_parts = explode('.', $tokens['id_token']);
                    if (count($jwt_parts) < 2) {
                        session()->setFlashdata('Error', 'Invalid Token Format from Google');
                        return redirect()->to(base_url('LoginOfficerPersonnel'));
                    }
                    
                    $payloadJson = base64_decode(strtr($jwt_parts[1], '-_', '+/'));
                    $payload = json_decode($payloadJson, true);
                    log_message('error', '[GoogleLogin] Payload from id_token: ' . $payloadJson);
                    
                    if (isset($payload['email'])) {
                        $email = $payload['email'];
                        $userRow = $DBPers->where('pers_username', $email)->get()->getRowArray();
                        log_message('error', '[GoogleLogin] Checking email: ' . $email . ' | Found: ' . ($userRow ? 'Yes' : 'No'));
                        
                        if($userRow){
                            $UserData = array('login_oauth_uid' => $payload['sub'],
                                                'updated_at' => date('Y-m-d H:i:s'));
                            $DBPers->where('pers_username', $email)->update($UserData);

                            $User = $userRow;
                            $User2 = $DBrloes->select('admin_rloes_status, admin_rloes_nanetype')->where('admin_rloes_userid', $User['pers_id'])->get()->getRowArray();
                            $userStatus = (isset($User2) && $User2['admin_rloes_status'] != "" ? $User2['admin_rloes_status'] : "Member");
                            
                            log_message('error', '[GoogleLogin] Login SUCCESS for ' . $email . ' | Status: ' . $userStatus);

                            // Hardcode pers_021 as superadmin
                            if ($User['pers_id'] === 'pers_021') {
                                $userStatus = 'superadmin';
                            }

                            $newdata = [
                                'username'  => $User['pers_prefix'].$User['pers_firstname'].' '.$User['pers_lastname'],
                                'id'     => $User['pers_id'],
                                'img'    => $User['pers_img'],
                                'fname'  => $User['pers_firstname'],
                                'lname'  => $User['pers_lastname'],
                                'logged_in' => true,
                                'rloes' => (isset($User2['admin_rloes_nanetype']) ? $User2['admin_rloes_nanetype'] : ''),
                                'status' => $userStatus
                            ];                
                            $session->set($newdata);  
                            
                            if(in_array($userStatus, ["superadmin", "admin", "manager"])){
                                return redirect()->to(base_url('Admin/Home'));
                            }else{
                                $ret = session()->get('Return');
                                if ($ret) {
                                    session()->remove('Return');
                                    if (filter_var($ret, FILTER_VALIDATE_URL)) {
                                        return redirect()->to($ret);
                                    }
                                    return redirect()->to(base_url($ret));
                                }
                                return redirect()->to(base_url());
                            }
                        } else {
                            log_message('error', '[GoogleLogin] Email not found in database: ' . $email);
                            session()->setFlashdata('Error', 'ไม่พบอีเมล ' . $email . ' ในฐานข้อมูล! กรุณาติดต่อแอดมิน');
                            return redirect()->to(base_url('LoginOfficerPersonnel'));
                        }  
                    } else {
                        log_message('error', '[GoogleLogin] No email in payload: ' . $payloadJson);
                        session()->setFlashdata('Error', 'ไม่สามารถระบุตัวตนอีเมลจาก Google ได้');
                        return redirect()->to(base_url('LoginOfficerPersonnel'));
                    }
                } else {
                    log_message('error', '[GoogleLogin] No id_token in Google response: ' . $responseBody);
                    session()->setFlashdata('Error', 'ไม่ได้รับข้อมูล ID Token จาก Google');
                    return redirect()->to(base_url('LoginOfficerPersonnel'));
                }
            } catch (\Exception $e) {
                session()->setFlashdata('Error', 'การเชื่อมต่อกับ Google ล้มเหลว: ' . $e->getMessage());
                return redirect()->to(base_url('LoginOfficerPersonnel'));
            }
        }
        
        return view('User/UserLeyout/UserHeader', $data)
        .view('User/UserLeyout/UserMenuLeft')
        .view('Login/LoginGoogle')
        .view('User/UserLeyout/UserFooter');
    }

    public function LogoutOfficerPersonnel(){
        $session = session();
        $session->destroy();
        return redirect()->to(base_url());
    }

    public function paLogin(){
        $session = session();
        if ($session->get('logged_in')) {
            return redirect()->to(base_url('pa-personnel'));
        }

        $data = $this->DataMain();
        $data['title']="เข้าสู่ระบบ PA";
        $data['description']="เข้าสู่ระบบแบบประเมิน PA";
        $data['UrlMenuMain'] = 'pa-login';
        $data['UrlMenuSub'] = '';

        // Store the return_to URL if present
        if($this->request->getVar("return_to")){
            session()->set('Return', $this->request->getVar("return_to"));
        }

        return view('User/UserLeyout/UserHeader',$data)
        .view('User/UserLeyout/UserMenuLeft')
        .view('User/UserPA/PaLogin');
    }

    public function processTraditionalLogin(){
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        $DB_Personnel = \Config\Database::connect(); // Connects to 'default' (personnel) database
        $DB_PA_Evaluation = \Config\Database::connect('pa_evaluation'); // Connects to 'pa_evaluation' database

        $user = null;
        $userRoles = null;
        $loggedInId = null;
        $loggedInUsername = null;
        $loggedInStatus = null;

        if ($role === 'assessor') {
            $DBEvaluators = $DB_PA_Evaluation->table('tb_evaluators');
            $evaluator = $DBEvaluators->where('e_Username', $username)->get()->getRowArray();

            if ($evaluator) {
                if (password_verify($password, $evaluator['e_Password'])) { // Verify hashed password
                    $user = $evaluator;
                    $loggedInId = $evaluator['e_id'];
                    $loggedInUsername = $evaluator['e_first_name'] . ' ' . $evaluator['e_last_name'];
                    $loggedInStatus = 'assessor'; // Assign a status for assessor
                    // For assessors, rloesAll might not be directly from tb_admin_rloes, define it here if needed
                    $userRoles = ['rloesAll' => 'assessor']; // Set a proper role name
                }
            }
        } elseif ($role === 'admin') {
            $DBPers = $DB_Personnel->table('tb_personnel');
            $DBrloes = $DB_Personnel->table('tb_admin_rloes');

            $personnelUser = $DBPers->where('pers_username', $username)->get()->getRowArray();

            if ($personnelUser) {
                if ($password === $personnelUser['pers_password']) { // IMPORTANT: Verify hashed password in real app
                    $user = $personnelUser;
                    $loggedInId = $personnelUser['pers_id'];
                    $loggedInUsername = $personnelUser['pers_prefix'] . $personnelUser['pers_firstname'] . ' ' . $personnelUser['pers_lastname'];
                    $userRoles = $DBrloes->select('admin_rloes_status, admin_rloes_nanetype')
                                        ->where('admin_rloes_userid', $personnelUser['pers_id'])
                                        ->get()->getRowArray();
                    $loggedInStatus = (isset($userRoles) && $userRoles['admin_rloes_status'] != "" ? $userRoles['admin_rloes_status'] : "Member");
                }
            }
        }

        if ($user) {
            $hasRole = false;
            if ($role === 'assessor' && $loggedInStatus === 'assessor') {
                $hasRole = true;
            } elseif ($role === 'admin' && isset($userRoles['admin_rloes_nanetype']) && str_contains($userRoles['admin_rloes_nanetype'], 'งานประเมิน pa')) {
                $hasRole = true;
            }

            if ($hasRole) {
                $newdata = [
                    'username'  => $loggedInUsername,
                    'id'     => $loggedInId,
                    'img'    => (isset($user['pers_img']) ? $user['pers_img'] : ''),
                    'fname'  => (isset($user['pers_firstname']) ? $user['pers_firstname'] : (isset($user['e_first_name']) ? $user['e_first_name'] : '')),
                    'lname'  => (isset($user['pers_lastname']) ? $user['pers_lastname'] : (isset($user['e_last_name']) ? $user['e_last_name'] : '')),
                    'logged_in' => true,
                    'rloes' => (isset($userRoles['admin_rloes_nanetype']) ? $userRoles['admin_rloes_nanetype'] : ''),
                    'status' => $loggedInStatus
                ];
                $session->set($newdata);

                // Specific redirect for Admin or Assessor
                if ($role === 'admin' || $role === 'assessor') {
                    session()->remove('Return'); // Clear the return URL
                    return redirect()->to(base_url('pa-personnel'));
                }

                // Fallback for other roles or if no specific redirect
                $returnUrl = session()->get('Return') ? session()->get('Return') : base_url();
                session()->remove('Return');
                return redirect()->to(base_url($returnUrl));
            } else {
                $session->setFlashdata('Error', 'บทบาทไม่ถูกต้องสำหรับผู้ใช้นี้!');
            }
        } else {
            $session->setFlashdata('Error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!');
        }

        return redirect()->back(); // Redirect back to the login page with error
    }
}
