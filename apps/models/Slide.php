<?php

namespace Multiple\Models;

use Multiple\Models\DBModel;
//use Phalcon\Mvc\Model\Query;

class Slide extends DBModel
{
    public $slide_id;
    public $slide_type;
    public $path;
    public $del_flg;
    public $add_date;
    public $add_user;
    public $upd_date;
    public $upd_user;
    public function initialize()
    {
        $this->setSource("slides");
    }        
   
	public function get_all(){
         $usr_data = Slide::find(array('del_flg'=>0));
    }
}
