<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;

class TagsPosts extends Model
{
	public $id;
    public $tag_id;
    public $post_id;
        
    public function initialize()
    {
        $this->setSource("tags_posts");
        $this->hasMany('tag_id', 'tags', 'tag_id');
    }
       
}
