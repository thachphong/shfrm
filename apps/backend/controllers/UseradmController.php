<?php

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Models\Users;

class UserAdmController extends Controller
{

	public function indexAction()
	{
		//$this->view->disable();
        /*return $this->dispatcher->forward(array(  
                    "controller" => "useradm",                 
                    "action"     => "login"
                ));*/
        return $this->response->redirect('useradm/login/',TRUE);
        /*return $this->dispatcher->forward(array(
                  //  'namespace' => ' Multiple\Backend\Controllers',
                    //'module' => 'backend',
				    'controller' => 'useradm',                    
                    'action' => 'login'
                ));*/
        //return $this->forward('useradm/login/');
	}
    public function loginAction()
	{
		//$this->view->disable();
        $this->view->setVar('pass', sha1('admin'));
        //$this->view->pass= sha1('admin');
	}
    public function logoutAction()
	{
		$this->session->set('auth', null);
        return $this->response->redirect('useradm/login/');
	}
    public function authAction()
    {
        $this->view->disable();
        $result['status']='NOT';
        $result['msg']='';
        $result['post'] = $_POST;
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
           // $dd = $this->db->getdbname();
            //$us = new Users();
           //$usr_data = $us->get_all();
            $user = Users::findFirst(array(
                "(email = :email: OR user_no = :email:) AND password = :pass: AND del_flg = 0 ",
                'bind' => array('email' => $email, 'password' => sha1($password))
            ));
            $result['pass'] = sha1($password);
            $result['msg'] = 'Tên đăng nhập hoặc mật khẩu không đúng !';
            if ($user != false) {
                $this->_registerSession($user);
                $result['status'] ='OK';
                $result['msg'] = 'Đăng nhập thành công !';
                /*$this->flash->success('Welcome ' . $user->username);
                
                return $this->dispatcher->forward(array(
                    "controller" => "admin",
                    "action"     => "index"
                ));*/
            }

            //$this->flash->error('Wrong email/password');
        }

        /*return $this->dispatcher->forward(array(                   
                    "action"     => "login"
                ));*/
        
        $this->response->setJsonContent($result);
        return $this->response;
    }
    private function _registerSession(Users $user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->username
        ));
    }
}
