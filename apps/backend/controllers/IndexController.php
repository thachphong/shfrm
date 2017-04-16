<?php

namespace Multiple\Backend\Controllers;

use Multiple\PHOClass\PHOController;

class IndexController extends PHOController
{

    public function initialize()
    {        
        $this->check_login();
    }
	public function indexAction()
	{
		//return $this->response->forward('login');
       // $this->view->name= 'aaaa';
	}
}
