<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;

class DownloadStructure extends Model
{
    public $id;
    public $key;
    public $description;
    public $xpath;
    public $ref_link;
    public $element_remove;
    public $from_string;
    public $to_string;
    public $sort;
    public $ctg_flg;
    public $dl_category_id;
    public $stop_flg;
        
    public function initialize()
    {
        $this->setSource("download_structure");
    }
    public function get_by_ref_link($reflink,$ctg_flg = 0){
        //$data = DownloadStructure::find(array('ref_link'=>$reflink,  "order" => "sort"));
        $data = DownloadStructure::query()
                ->where("ref_link = :ref_link:")  
                ->addwhere("ctg_flg = :ctg_flg:") 
                ->addwhere("stop_flg = 0 ")              
                ->bind(array("ref_link" => $reflink,'ctg_flg'=>$ctg_flg))
                ->order("sort")
                ->execute();
        return $data;
    }
    public function get_by_ctg_link($reflink,$dl_category_id = 0){
        //$data = DownloadStructure::find(array('ref_link'=>$reflink,  "order" => "sort"));
        $data = DownloadStructure::query()
                ->where("ref_link = :ref_link:")  
                ->addwhere("ctg_flg = 1 ")
                ->addwhere("stop_flg = 0 ")  
                ->addwhere("dl_category_id = :dl_category_id:")
                ->bind(array("ref_link" => $reflink,'dl_category_id' =>$dl_category_id))
                ->order("sort")
                ->execute();
        return $data;
    }
    
}
