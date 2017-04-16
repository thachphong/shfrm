<?php

namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;
use Multiple\Models\Posts;
use Multiple\Models\Menu;

class ApprovalController extends Controller
{
	public function initialize()
    {
        $auth = $this->session->get('auth');
        if(isset($auth['id'])==FALSE){            
            return $this->response->redirect('useradm/login/',FALSE);
        }
    }
	public function indexAction()
	{
		$photos=array();
    	$db = new Posts();	
    	
		$total = 20 ;
        $totalpage = 1;
        $page = 1;
        $from = ($page-1)*$total;
        $to = $page*$total;
        
        $totalrow = $db->get_totalrow(2);
        $totalpage = ceil($totalrow/$total);
        $menu = new Menu();
        
        $this->view->page = $page;
        $this->view->totalpage= $totalpage;
        $this->view->listmenu = $menu->get_All();	
		$this->view->datapost = $db->get_by_menu(2,10,$from);	
		
	}
	public function updateAction(){
		$this->view->disable();
        $result['status']='NOT';
        $result['msg']='';
        $result['post'] = $_POST;
        if ($this->request->isPost()) {
        	$id_dl =  $this->request->getPost('id_dl');
        	$status = $this->request->getPost('status');
        	$post = new Posts();
        	foreach($id_dl as $item){        		
        		$db = $post->get_by_id($item);				
				if($status[$item]=='2'){
					$file_name = str_replace("\\",'/', IMG_DATA_PATH.$db->filename);				
					$db->delete();
					unlink($file_name);
				}else{
					$db->status = $status[$item];
					$db->save();
				}
			}
			$result['status']='OK';
			$result['msg']='Cập nhật thành công !';
        }
        
        $this->response->setJsonContent($result);
        
        return $this->response;
	}
	public function listdataAction()
	{
			
		$this->view->disableLevel(View::LEVEL_LAYOUT);
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
		
		if ($this->request->isPost()) {
			$page = 1;
			$search['status'] =  $this->request->getPost('status');
        	$search['add_date'] = $this->request->getPost('search_date');
        	$search['menu_id'] = $this->request->getPost('menu_id');
			$page = $this->request->getPost('page');
	    	$db = new Posts();	
	    	
			$total = 20 ;
	        $totalpage = 1;
	        
	        $from = ($page-1)*$total;
	        
	        $search['limit'] = $total;
	    	$search['offset'] = $from;
	        $totalrow = $db->be_count_posts($search);
	        $totalpage = ceil($totalrow/$total);
	        
	        
	        $this->view->page = $page;
	        $this->view->totalpage= $totalpage;
	    	
			$this->view->datapost = $db->be_get_posts($search);	
		}
		//$this->response->setContent("<h1>Hello!</h1>");
		//return $this->response;
	}
}
