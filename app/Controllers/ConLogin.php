<?php

namespace App\Controllers;

class ConLogin extends BaseController
{
        //$path = dirname(dirname(dirname(dirname((dirname(__FILE__))))));
	    //require $path . '/skj.ac.th/public_html/librarie_skj/google_sheet/vendor/autoload.php';

    private $googleClient = null;
    private $GoogleButton = "";
    private $ReturnUrl = "";
    function __construct(){
        $path = (dirname(dirname(dirname(dirname((dirname(__FILE__)))))));
		require $path . '/librarie_skj/google_sheet/vendor/autoload.php';

        $redirect_uri = base_url('LoginOfficerPersonnel');
        
        $this->googleClient = new \Google_Client();
        $this->googleClient->setClientId('110650460520-35k7ea69727vjqv11jise3ihm7g3vrah.apps.googleusercontent.com');
		$this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri($redirect_uri);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');

        $this->GoogleButton = '<a href="'.$this->googleClient->createAuthUrl().'" class="btn btn-primary me-3 w-auto"><i class="tf-icons bx bxl-google-plus"></i> Login by Google </a>';
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

        //print_r($this->request->getVar("code"));exit();
        
        if($this->request->getVar("return_to") == ""){

        }else{
            session()->set('Return',$this->request->getVar("return_to"));
        }
        
        
            if($this->request->getVar("code")){

            $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar("code"));
            
                if(!isset($token['error'])){
                
                    $this->googleClient->setAccessToken($token['access_token']);           
                    session()->set('AccessToken', $token['access_token']);
                

                    $googleService = new \Google_Service_Oauth2($this->googleClient);  
                    $data = $googleService->userinfo->get();            
                             
                   

                $CheckEmail = $DBPers->where('pers_username', $data['email'])->get()->getRowArray()>0?true:false;
                //echo '<pre>';print_r("555"); exit();  
                if($CheckEmail){
                        $UserData = array('login_oauth_uid' => $data['id'],
                                            'updated_at' => date('Y-m-d H:i:s'));
                        $DBPers->where('pers_username', $data['email'])->update($UserData);

                            $User = $DBPers->where('pers_username', $data['email'])->get()->getRowArray();
                            $User2 = $DBrloes->select('admin_rloes_status,GROUP_CONCAT(admin_rloes_nanetype) AS rloesAll')->where('admin_rloes_userid', $User['pers_id'])->get()->getRowArray();
                           //print_r($User2); exit();
                            $newdata = [
                                'username'  => $User['pers_prefix'].$User['pers_firstname'].' '.$User['pers_lastname'],
                                'id'     => $User['pers_id'],
                                'logged_in' => true,
                                'rloes' => $User2['rloesAll'],
                                'status' => (isset($User2) != "" ?$User2['admin_rloes_status']:"Member")
                            ];                
                            $session->set($newdata);  
                            
                            // if(in_array('งานประเมิน pa',explode(',',$User2['rloesAll']))){
                            //     return redirect()->to(base_url('pa-personnel'));
                            // }else{
                                return redirect()->to(("https://".$_SESSION['Return']));
                            // }
                          
                } else{
                    
                  
                    $session->setFlashdata('Error', 'Email นี้ไม่สามารถเข้าสู่ระบบได้ กรุณาติดต่อผู้ดูแลระบบ!');
                    return redirect()->back();
                }        

                }else{
                    session()->set('Error', "Something went Wrong!");     
                    
                }
        
            }
        
        return view('User/UserLeyout/UserHeader',$data)
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
                    $userRoles = $DBrloes->select('admin_rloes_status,GROUP_CONCAT(admin_rloes_nanetype) AS rloesAll')
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
            } elseif ($role === 'admin' && isset($userRoles['rloesAll']) && str_contains($userRoles['rloesAll'], 'งานประเมิน pa')) {
                $hasRole = true;
            }

            if ($hasRole) {
                $newdata = [
                    'username'  => $loggedInUsername,
                    'id'     => $loggedInId,
                    'logged_in' => true,
                    'rloes' => (isset($userRoles['rloesAll']) ? $userRoles['rloesAll'] : ''),
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
