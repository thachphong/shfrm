<?php

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Models\Posts;

class PostsController extends Controller
{

    public function initialize()
    {
        /*$auth = $this->session->get('auth');
        if(isset($auth['id'])==FALSE){
            return $this->response->redirect('useradm/login/',TRUE);
        }*/
    }
	public function indexAction($id)
	{
		//$url =  $this->request->getURI();
       
        $db = new Posts();
       // $post_data= Posts::findFirst
        $post_data = $db->get_by_id($id);
        
        $this->view->post = $post_data;
        
	}
}
