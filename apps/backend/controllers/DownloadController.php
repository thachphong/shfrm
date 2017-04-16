<?php

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Multiple\Models\DownloadStructure;
use Multiple\Models\DownloadTemp;
use Multiple\Library\AutoDownload;
use Multiple\Models\Menu;
use Multiple\Models\Posts;
use Multiple\Models\Tags;
use Multiple\Models\TagsPosts;
use Multiple\Models\DownloadCategory;
//require __DIR__.'/../../library/AutoDownload.php';

class DownloadController extends Controller
{
	public function initialize()
    {
        $auth = $this->session->get('auth');
        if(isset($auth['id'])==FALSE){
            return $this->response->redirect('useradm/login/',false);
        }
    }
	public function indexAction()
	{
		//$this->view->disable();
		$menu = new Menu();
		$list_menu = $menu->get_All();
		$this->view->listmenu = $list_menu;
		$ctg = new DownloadCategory();
		$this->view->ctglist = $ctg->get_All();
		$this->view->date_out =date('H:i');
	}
    public function exeAction(){
        $this->view->disable();
        $result['status']='NOT';
        $result['msg']='';
        if ($this->request->isPost()) {

            $url = $this->request->getPost('link_dl');
            $menu_id = $this->request->getPost('menu_id');
          
            /*$dl = new AutoDownload();
            $structure= new DownloadStructure();
                        
            preg_match('/^(http:\/\/).+?\//',$url,$match);
            if(count($match)==0){
				preg_match('/^(https:\/\/).+?\//',$url,$match);
			}
            $data_st = $structure->get_by_ref_link($match[0]);
            $dl->set_URL($url);
            //$title = "div.artshow h1";
            //$content = "div.artbody";
            //$img = "div.artbody img";
            $title ='';
            $file_name ='';
            $content ='';
            $tags = array();
            foreach($data_st as $row){
                if($row->key=='title'){
                	//$this->logger->info('title');
                    $title = $dl->GetTitle($row->xpath);
                }else if($row->key =='image'){   
                	//$this->logger->info('image');             
                    $file_name = $dl->get_img($row->xpath,$match[0]);
                }else if($row->key=='del'){
                	//$this->logger->info('del'); 
                    $dl->remove_element($row->xpath,$row->element_remove); 
                }else if($row->key=='replace'){ 
                	//$this->logger->info('replace');    
                    $dl->replaceString($row->xpath,$row->from_string,$row->to_string);
                }else if($row->key=='des'){ 
                	//$this->logger->info('des');    
                	$des = $dl->get_text($row->xpath);
                }else if($row->key=='tag'){
                	$this->logger->info('tag');  
                	$this->logger->info($row->xpath);     
                	$tags = $dl->get_tag($row->xpath,$row->from_string,'');
                	
                }else if($row->key=='content'){
                	//$this->logger->info('content'); 
                    //foreach($data_st as $item){
                    //    if($item->key=='del'){
                    //        $dl->remove_element($item->xpath,$item->element_remove);
                    //    }
                    //}
                    $content = $dl->get_content($row->xpath,$match[0]);
                    //$this->logger->info('content2'); 
                }
            }

            //$title .= $dl->GetTitle($title) ;
            //$html .= $dl->get_innerHTML($content) ;
            //$html .= $dl->get_img($img) ;
            //$dl->remove_element('ins;script');
            //$html .= $dl->get_content($content);
           //$this->logger->info($content);
           	if(strlen($des)==0){
				$des = $this->get_des($content);
				//$content = str_replace($des,'',$content);
				
			}
			//$this->logger->info('----------------------------');
			//$this->logger->info($content);
    		$resource = $match[0];
    		
    	    
    	    $file_ext=	pathinfo($file_name,PATHINFO_EXTENSION);
    	    $title = html_entity_decode($title);
    		$post = new Posts();
    	    $post->caption = $title;
    	    $post->caption_url = $this->to_slug($title);
            $post->filename = $file_name;       
    	    $post->size = 0;
    	    $post->type = 'blog';
    	    $post->resource = $resource;
    	    $post->adduser= 1;
    	    $post->des = $des ;
    	    $post->content = $content;
            $post->menu_id = $menu_id;
            $post->add_date = date('Y-m-d');
            $post->add_time = date('H:i');
    	    $post->save(); 
    	    
    	    $tags_model = new Tags();
    	    if(count($tags) >0){
				foreach($tags as $tag){
					$tags_model = new Tags();
					$tag_data = Tags::findFirst(array("tag_no = :tag_no: ",'bind' => array('tag_no' => $tag['tag_no']) ));
					if($tag_data == FALSE)
					{
						$tag_data = new Tags();
						$tag_data->tag_no = $tag['tag_no'];
						$tag_data->tag_name = $tag['tag_name'];
						$tag_data->save();
					}
					
                	$this->logger->info($tag['tag_no']);
                	
					$tagpost = new TagsPosts();
					$tagpost->tag_id = $tag_data->tag_id;
					$tagpost->post_id = $post->id ;
					$tagpost->save();
				}
			}*/
			$this->download_by_link($url,$menu_id);
            $result['status']='OK';
            $result['msg']='Download thành công !';
        }
        $this->response->setJsonContent($result);
        return $this->response;
    }
    public function dlallAction(){
    	$this->view->disable();
    	$result['status']='NOT';
        $result['msg']='';
        $menu_id = $this->request->getPost('menu_id');
        if(strlen($menu_id)>0){
        	$ctg = new DownloadCategory();
			$category = $ctg->get_All($menu_id);
		}else{
			$category = DownloadCategory::find();
		}
		
		$dl = new AutoDownload();
		$structure= new DownloadStructure();
		foreach($category as $item)
		{
			preg_match('/^(http:\/\/).+?\//',$item->link,$match);
            if(count($match)==0){
				preg_match('/^(https:\/\/).+?\//',$item->link,$match);
			}
            $data_st = $structure->get_by_ctg_link($match[0],$item->id);            
			if($dl->Set_URL($item->link)== FALSE){
				continue;
			}
			$link_get = array();
			//$this->logger->info('---------1');
			foreach($data_st as $row){
                if($row->key=='category_link'){
                	$link_get = $dl->get_link($row->xpath,$match[0]);
                }else if($row->key=='del'){
                	//$this->logger->info('del'); 
                	//$this->logger->info($row->xpath); 
                    $dl->remove_element($row->xpath,$row->element_remove); 
                }
            }
            //$this->logger->info('---------2');
            foreach($link_get as $link){
            	$dltemp = new DownloadTemp();
            	//$this->logger->info('---------3');
            	$this->logger->info('link: '.$link['link']);
            	
            	if(isset($link['title']) && strlen($link['title'])>0 && $dltemp->check_exists($link['link'])){
            		//$this->logger->info('---------4');
					/*if($this->download_by_link($link['link'],$item->menu_id))
	            	{*/
						//$this->logger->info('---------5');
						$dltemp->link_dl = $link['link'];
						$dltemp->status = 0;
						$dltemp->caption = $link['title'];
						$dltemp->img_link = $link['img_link'];
						$dltemp->menu_id = $item->menu_id;
						$this->logger->info('dl_link: '.$dltemp->link_dl);
						$dltemp->save();
					//}
				}          	
            }
            $temp = new DownloadTemp();
            $list=$temp->get_All(0,$item->menu_id);
            foreach($list as $row){
				if($this->download_by_link($row->link_dl,$item->menu_id,$row->img_link))
	            {
						//$this->logger->info('---------5:'.$row->img_link);
						//$dltemp->link_dl = $link['link'];
						$row->status = 1;
						//$dltemp->caption = $link['title'];
						$row->save();
				}
			}
		}
		$result['status']='OK';
        $result['msg']='Download thành công !';
        $this->response->setJsonContent($result);
        return $this->response;
	}
	public function dlagainAction(){
    	$this->view->disable();
    	$result['status']='NOT';
        $result['msg']='';
        $menu_id = $this->request->getPost('menu_id');
        if(strlen($menu_id)>0){
        	$ctg = new DownloadCategory();
			$category = $ctg->get_All($menu_id);
		}else{
			$category = DownloadCategory::find();
		}
				
		foreach($category as $item)
		{			
            $temp = new DownloadTemp();
            $list=$temp->get_All(0,$item->menu_id);
            foreach($list as $row){
				if($this->download_by_link($row->link_dl,$item->menu_id,$row->img_link))
	            {
						//$this->logger->info('---------5');
						//$dltemp->link_dl = $link['link'];
						$row->status = 1;
						//$dltemp->caption = $link['title'];
						$row->save();
				}
			}
		}
		$result['status']='OK';
        $result['msg']='Download thành công !';
        $this->response->setJsonContent($result);
        return $this->response;
	}
	public function download_by_link($url,$menu_id,$img_link = ''){
		$dl = new AutoDownload();
            $structure= new DownloadStructure();
                        
            preg_match('/^(http:\/\/).+?\//',$url,$match);
            if(count($match)==0){
				preg_match('/^(https:\/\/).+?\//',$url,$match);
			}
            $data_st = $structure->get_by_ref_link($match[0]);
            if($dl->set_URL($url)== FALSE){
				return ;
			}
            //$title = "div.artshow h1";
            //$content = "div.artbody";
            //$img = "div.artbody img";
            $title ='';
            $file_name ='';
            $content ='';
            $datetime = '';
            $tags = array();
            foreach($data_st as $row){
                if($row->key=='title'){
                	//$this->logger->info('title');
                    $title = $dl->GetTitle($row->xpath);
                }else if($row->key =='image'){   
                	//$this->logger->info('image'); 
                	if($img_link ==''){
						$file_name = $dl->get_img($row->xpath,$match[0]);
					}else{
						$file_name = $dl->get_img_byurl($img_link);
					}
                }else if($row->key=='del'){
                	//$this->logger->info('del'); 
                    $dl->remove_element($row->xpath,$row->element_remove); 
                }else if($row->key=='replace'){ 
                	//$this->logger->info('replace');    
                    $dl->replaceString($row->xpath,$row->from_string,$row->to_string);
                }else if($row->key=='des'){ 
                	//$this->logger->info('des');    
                	$des = $dl->get_text($row->xpath);
                }else if($row->key=='tag'){
                	//$this->logger->info('tag');  
                	//$this->logger->info($row->xpath);     
                	$tags = $dl->get_tag($row->xpath,$row->from_string,'');
                }else if($row->key=='datetime'){
                	//$this->logger->info('datetime');  
                	//$this->logger->info($row->xpath);     
                	$datetime = $dl->get_text($row->xpath);
                	//$this->logger->info($datetime);  
                }else if($row->key=='content'){
                	//$this->logger->info('content'); 
                    /*foreach($data_st as $item){
                        if($item->key=='del'){
                            $dl->remove_element($item->xpath,$item->element_remove);
                        }
                    }*/
                    $content = $dl->get_content($row->xpath,$match[0]);
                    //$this->logger->info('content2'); 
                }
            }

            //$title .= $dl->GetTitle($title) ;
            //$html .= $dl->get_innerHTML($content) ;
            //$html .= $dl->get_img($img) ;
            //$dl->remove_element('ins;script');
            //$html .= $dl->get_content($content);
           //$this->logger->info($content);
           	if(strlen($des)==0){
				$des = $this->get_des($content);
				//$content = str_replace($des,'',$content);
				
			}
			//$this->logger->info('----------------------------');
			//$this->logger->info($content);
    		$resource = $match[0];
    		$add_date = date('Y-m-d');
    		$add_time = date('H:i');
    	    if(strlen($datetime)> 0){
				preg_match('/([0-9\/]){8,10}/',$datetime,$match);	
				$date_format = date_create_from_format('d/m/Y', $match[0]);			 
				$add_date = $date_format->format('Y-m-d');
				preg_match('/([0-9\:]){5}/',$datetime,$match2);
				$add_time = $match2[0];
				//$this->logger->info($add_date);
				//$this->logger->info($add_time);
			}
			$file_size = filesize(IMG_DATA_PATH.'/'.$file_name);
			$status = 0 ;
			if($file_size > 0){
				$status =1 ;
			}
    	    $file_ext=	pathinfo($file_name,PATHINFO_EXTENSION);
    	    $title = html_entity_decode($title);
    		$post = new Posts();
    	    $post->caption = $title;
    	    $post->caption_url = $this->to_slug($title);
            $post->filename = $file_name;       
    	    $post->size = $file_size;
    	    $post->type = 'blog';
    	    $post->resource = $resource;
    	    $post->adduser= 1;
    	    $post->des = $des ;
    	    $post->content = $content;
            $post->menu_id = $menu_id;
            $post->add_date = $add_date;
            $post->add_time = $add_time;
            $post->status = $status;
    	    $post->save(); 
    	    
    	    $tags_model = new Tags();
    	    if(count($tags) >0){
				foreach($tags as $tag){
					$tags_model = new Tags();
					$tag_data = Tags::findFirst(array("tag_no = :tag_no: ",'bind' => array('tag_no' => $tag['tag_no']) ));
					if($tag_data == FALSE)
					{
						$tag_data = new Tags();
						$tag_data->tag_no = $tag['tag_no'];
						$tag_data->tag_name = $tag['tag_name'];
						$tag_data->save();
					}
					
                	//$this->logger->info($tag['tag_no']);
                	
					$tagpost = new TagsPosts();
					$tagpost->tag_id = $tag_data->tag_id;
					$tagpost->post_id = $post->id ;
					$tagpost->save();
				}
			}
		return TRUE;
	}
    function to_slug($str) {
    	//$str = mb_convert_encoding($str, "UTF-8" );
    	$str = html_entity_decode($str );
	    $str = trim(mb_strtolower($str,'UTF-8'));
	    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
	    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
	    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
	    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
	    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
	    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);	
	    $str = preg_replace('/(đ)/', 'd', $str);
	    /*$str = str_replace(array('à','á','ạ','ả','ã','â','ầ','ấ','ậ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'), 'a', $str);
	    $str = str_replace(array('è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'), 'e', $str);
	    $str = str_replace(array('ì','ị','ỉ','ĩ'), 'i', $str);
	    $str = str_replace(array('ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ'), 'o', $str);
	    $str = str_replace(array('ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'), 'u', $str);	    
	    $str = str_replace(array('ỳ','ý','ỵ','ỷ','ỹ'), 'y', $str);*/
	    
	       
	    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
	    $str = preg_replace('/([\s]+)/', '-', $str);
	    
	    //$str = str_replace('đ', 'd', $str);
	    $str = str_replace(array('"',':',"'",'?'), '', $str);
	    return $str;
	}
	public function get_des(&$trg){
		preg_match('/[\>][A-z]/',$trg,$match);
		//var_dump($match);
		$vt1 = strpos($trg,$match[0]);
		$vt2 = strpos($trg,'<',$vt1 );
		$des =  substr($trg,$vt1+1,($vt2-$vt1-1));
		$trg=substr_replace($trg, '', $vt1+1,($vt2-$vt1-1));
		return $des;
		//echo $vt1;
		//echo '<br/>';
		
		//echo $vt2;
		//echo '<br/>';
		
	}
}
