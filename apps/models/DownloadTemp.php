<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;

class DownloadTemp extends Model
{
    public $id_dl;
    public $status;
    public $link_dl;
    public $caption;
    public $menu_id;
    public $img_link;
    public $addtime;
        
    public function initialize()
    {
        $this->setSource("download_temp");
    }
    public function get_All($status,$menu_id){
    	$data = DownloadTemp::query()
                ->where("status = :status:")   
                ->addwhere("menu_id = :menu_id:")              
                ->bind(array("status" => $status,'menu_id'=>$menu_id))
                ->order("id_dl desc")
                ->execute();
        return $data;
        /*$usr_data = DownloadTemp::find(array('status'=>1));
        return $usr_data;*/
    }
    public function check_exists($url){    
    	//$res = DownloadTemp::find(array('link_dl'=>$url));
    	$pql = "SELECT count(*) cnt FROM Multiple\Models\DownloadTemp 
    			where link_dl = :link_dl:";
		$total = $this->modelsManager->executeQuery($pql,array( 'link_dl' => $url));
		if($total[0]->cnt == 0){
			return TRUE;
		}
		/*return $total[0]->cnt;
        if( count($res) == 0){
			return TRUE;
		}*/
		return FALSE;
    }   
}
