<?php

namespace Multiple\Backend\Controllers;

use Multiple\PHOClass\PHOController;
use Multiple\Models\Slide;
class SlideController extends PHOController
{

    public function initialize()
    {        
        $this->check_login();
    }
	public function indexAction()
	{
		$slide_db = new Slide();
		$data['list'] = $slide_db->get_all();
		$this->set_template_share();
		$this->ViewVAR($data);
	}
}
