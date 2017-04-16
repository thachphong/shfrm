<?php

namespace Multiple\Models;

use Multiple\Models\DBModel;
//use Phalcon\Mvc\Model\Query;

class Message extends DBModel
{
    public $id;
    public $title;
    public $status;
    public $parent;
    public $no;
    public $sort;
    public function initialize()
    {
        $this->setSource("message_lang");
    }
        
    public function get_all_child($menu_no){
		/*$pql = "select f_get_all_child_menu('xa-hoi') as list_id";
		$query = new Query($pql, $this->getDI());
		$data = $query->execute();
		//$data = $this->modelsManager->executeQuery($pql,array( 'menu_no' => $menu_no));    
        if(count($data)>0){
			return $data[0]->list_id;
		}
        return '';*/
        /*$data = $this->db->fetchAll("select f_get_all_child_menu(:menu_no:) as list_id", Phalcon\Db::FETCH_ASSOC,            array('menu_no' => $menu_no));                
    	return $data[0]->list_id;*/
    	$db = \Phalcon\DI::getDefault()->get('db');
		$stmt = $db->prepare("select f_get_all_child_menu(:menu_no) as list_id ");
		$stmt->execute(['menu_no'=>$menu_no]);
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $data[0]['list_id'];
	}
	public function get_all($screen){
        $sql="select msg_no, des from message_lang t
        where screen=:screen and lang_key =:lang ";
        $sql_param['screen']=$screen;
        $sql_param['lang']=1;
       
		$data = $this->pho_query($sql,$sql_param);
		
        $res = array(); 
        foreach($data as $item){			
			$res[$item['msg_no']] = $item['des'];
		} 
		return $res;
    }
}
