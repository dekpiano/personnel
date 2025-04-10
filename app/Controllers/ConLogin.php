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
        $data['UrlMenuMain'] = '';
        $data['UrlMenuSub'] = '';
        

        $session = session();
        $DB_General = \Config\Database::connect();
        $DBrloes = $DB_General->table('tb_admin_rloes');
        $DB_Personnel = \Config\Database::connect('personnel');
        $DBPers = $DB_Personnel->table('tb_personnel');     
        
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
                    //echo '<pre>';print_r($googleService); exit();          
                    $data = $googleService->userinfo->get();            
                                
                $CheckEmail = $DBPers->where('pers_username', $data['email'])->get()->getRowArray()>0?true:false;
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
                            
                            // if($User2['admin_rloes_status'] == "admin"){
                            //     return redirect()->to(base_url('Admin/Home'));
                            // }else{
                                return redirect()->to("https://".$_SESSION['Return']);
                            // }
                           
                        
                        
                        
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

    public function LogoutOfficerGeneral(){
        $session = session();
        $session->destroy();
        return redirect()->to(base_url());
    }
}
