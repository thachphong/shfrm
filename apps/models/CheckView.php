<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;
use Multiple\Models\TagsPosts;

class CheckView extends Model
{
    public $view_id;
    public $postid;
    public $user_ip;
    public $date_view;
    public $time_view;
    
    
        
    public function initialize()
    {
        $this->setSource("check_view");
    }
    
    
}
