<?php

namespace Multiple\Models;

use Multiple\Models\TagsPosts;
use Phalcon\Mvc\Model;
//use Phalcon\Mvc\Model\Query;

class Posts extends Model
{
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;
    public $des;
    public $content;
    public $status;
    public $adduser;
    public $youtube_key;
    public $dl_temp_id;
    public $total_view;
    public $total_vote;
    public $add_date;
    public $add_time;
    public $total_comment;
    public $total_like;
    public $resource;
    public $menu_id;
    public $caption_url;
    
        
    public function initialize()
    {
        $this->setSource("posts");
    }
    public function get_All(){
        $usr_data = Menu::find(array('status'=>1));
        return $usr_data;
    }
    public function get_by_id($id){
		return Posts::findFirst(array("id = :id:  ",'bind' => array('id' => $id) ));
	}
    public function get_new($limit = 6){
        /*$data = Posts::find(array(//'ref_link'=>$reflink,
        						  "order" => "id desc",
        						  "limit" => $limit
        						  ));*/
        $data = Posts::query()
                ->where("status = 1")  
                ->order("add_date desc,add_time desc")
                ->limit($limit)
                ->execute();
        return $data;
    }
    public function get_by_menu($menu_id,$limit = 6,$offet=0){        
        $data = Posts::query()
                ->where("status = 1")  
                ->addwhere("menu_id in ( $menu_id )") 
               // ->bind(array("menu_id" => $menu_id)) 
                ->order("add_date desc,add_time desc")
                ->limit(array($limit,$offet))
               // ->offset(1)                 
                ->execute();
        return $data;
    }
    public function get_realtion_old($id,$type,$menu_id){
        /*$data = Posts::find(array('type'=>$type,
        						  ''
        						  "order" => "id desc",
        						  "limit" => $limit
        						  ));*/
        $data = Posts::query()
                ->where("menu_id = $menu_id ")
                ->addwhere("type = :type:")    
                ->addwhere("id < $id")  
                ->bind(array("type" => $type))
                ->order("add_date desc,add_time desc")
                ->limit(5)
                ->execute();
        return $data;
    }
    public function get_realtion_new($menu_id,$id){        
        $data = Posts::query()
                ->where("menu_id = $menu_id ")               
                ->addwhere("id <> $id") 
                ->order("add_date desc,add_time desc")
                ->limit(9)
                ->execute();
        return $data;
    }
    public function get_countpost($type='',$menu_id =''){
        $sql="select  count(*) cnt
				from  posts 				
				where (status <> 3)				
				";
        if($type != ''){
            $sql.=" and posts.type = '".$type."'";
        }
        if($menu_id != ''){
            $sql.=" and posts.menu_id = ".$menu_id;
        }
        $result = static::getarray_by_sql($sql);
        if(count($result) > 0){
            return $result[0]['cnt'];
        }
        return 0;
    }
    public function get_totalrow($menu_id){
    	$pql = "SELECT count(*) cnt FROM Multiple\Models\Posts Posts
    			where status = 1 and menu_id in ( $menu_id )";
		$total = $this->modelsManager->executeQuery($pql);
		//$total = $manager->executeQuery($phql);
		/*foreach($total as $row){
			return $row->cnt;
		}*/
		return $total[0]->cnt;
	}
    public function get_by_tag($tag_id,$limit = 6,$offet=0){    
    	$pql = "select p.* from Multiple\Models\TagsPosts t
				INNER JOIN Multiple\Models\Posts p
						on t.post_id = p.id and p.status =1
				WHERE t.tag_id= :tag_id:
				ORDER BY p.id DESC
				limit $limit
				OFFSET $offet";
		$data = $this->modelsManager->executeQuery($pql,array( 'tag_id' => $tag_id));    
        
        return $data;
    }
    public function get_totalrow_bytag($tag_id){    
    	$pql = "select count(p.id) cnt from Multiple\Models\TagsPosts t
				INNER JOIN Multiple\Models\Posts p
						on t.post_id = p.id and p.status =1
				WHERE t.tag_id= :tag_id:";
				;
		$data = $this->modelsManager->executeQuery($pql,array( 'tag_id' => $tag_id));    
        
        return $data[0]->cnt;
    }
    public function search($keysearch,$limit = 6,$offet=0){
    	$pql = "select *  from Multiple\Models\Posts
				where  (REPLACE(caption_url,'-',' ') like :keysearch:  or 
				caption like :keysearch:)
				and status=1
				ORDER BY add_date desc,add_time desc
				limit $limit
				OFFSET $offet";
		$data = $this->modelsManager->executeQuery($pql,array( 'keysearch' => '%'.$keysearch.'%'));    
        
        return $data;
	}
	public function search_totalrow($keysearch){    
    	$pql = "select count(*) cnt  from Multiple\Models\Posts
				where  (REPLACE(caption_url,'-',' ') like :keysearch:  or 
				caption like :keysearch:)
				and status=1 ";
		$data = $this->modelsManager->executeQuery($pql,array( 'keysearch' => '%'.$keysearch.'%'));    
        
        return $data[0]->cnt;
    }
    public function be_get_posts($param){
		$pql = "select *  from Multiple\Models\Posts
				where  status=:status:
				";
		$sql_param['status']=$param['status'];
		if(isset($param['add_date']) && strlen($param['add_date'])>0){
			$sql_param['add_date']=$param['add_date'];
			$pql .=" and add_date = :add_date: ";
		}
		if(isset($param['menu_id']) && strlen($param['menu_id'])>0){
			$sql_param['menu_id']=$param['menu_id'];
			$pql .=" and menu_id = :menu_id: ";
		}
		$pql .=" ORDER BY id DESC ";
		if(isset($param['limit'])){
			$pql .=" limit ".$param['limit'];
		}
		if(isset($param['offset'])){
			$pql .=" OFFSET ".$param['offset'];
		}
		$data = $this->modelsManager->executeQuery($pql,$sql_param);    
        
        return $data;
	}
	public function be_count_posts($param){
		$pql = "select count(*) cnt  from Multiple\Models\Posts
				where  status=:status:
				";
		$sql_param['status']=$param['status'];
		if(isset($param['add_date']) && strlen($param['add_date'])>0){
			$sql_param['add_date']=$param['add_date'];
			$pql .=" and add_date = :add_date: ";
		}
		if(isset($param['menu_id']) && strlen($param['menu_id'])>0){
			$sql_param['menu_id']=$param['menu_id'];
			$pql .=" and menu_id = :menu_id: ";
		}
		/*$pql .=" ORDER BY id DESC ";
		if(isset($param['limit'])){
			$pql .=" limit ".$param['limit'];
		}
		if(isset($param['offset'])){
			$pql .=" OFFSET ".$param['offset'];
		}*/
		$data = $this->modelsManager->executeQuery($pql,$sql_param);    
		return $data[0]->cnt;
	}
}
