<?php

namespace Multiple\Backend\Controllers;

//use Phalcon\Mvc\Controller;
use Multiple\Models\Users;
use Multiple\Models\Message;
use Multiple\PHOClass\PHOController;

class LoginAdmController extends PHOController
{

	public function indexAction()
	{
		//$db= new Message();
		//$msg_lang =$db->get_all('LOGIN');
		//$this->ViewVAR(array('lang'=> $msg_lang));
	}
    
    public function logoutAction()
	{
		$this->session->set('auth', null);
        return $this->_redirect('loginadm/index/');
	}
    public function authAction()
    {
        
        $result['status']='NOT';
        $result['msg']='';
        $result['post'] = $_POST;
        if ($this->request->isPost()) {
			$param = $this->get_param(array('email','password'));
			
            $email =$param['email'];
            $password =$param['password'];  
           
            $us = new Users();
            $user = $us->get_user($email,$password);
            //$user = $us->get_row($email,$password);
            
            $result['pass'] = md5($password);
            $result['msg'] = 'Tên đăng nhập hoặc mật khẩu không đúng !';
            if ($user != false) {
                $this->_registerSession($user);                
                $result['status'] ='OK';
                $result['msg'] = 'Đăng nhập thành công !';                
            }

            //$this->flash->error('Wrong email/password');
        }

        
        return $this->ViewJSON($result);
    }
    private function _registerSession(Users $user)
    {
        $this->session->set('auth', $user);
    }
}
