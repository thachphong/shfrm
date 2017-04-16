<?php

namespace Multiple\Models;

use Phalcon\Mvc\Model;
use Multiple\Models\TagsPosts;

class Tags extends Model
{
    public $tag_id;
    public $tag_no;
    public $tag_name;
    
        
    public function initialize()
    {
        $this->setSource("tags");
    }
    public function get_by_post($post_id){
        $phql   = "select Tags.* from Multiple\Models\Tags Tags 
					INNER JOIN Multiple\Models\TagsPosts tags_posts   on tags_posts.tag_id = Tags.tag_id
					where tags_posts.post_id= $post_id ";
        //$query = $this->modelsManager->createQuery($phql);
        $tags  = $this->modelsManager->executeQuery($phql);
        //$tags  = $query->execute();
		//$tags  = $query->execute(array(        'post_id' => $post_id    ));
		return $tags;
    }  
    public function get_by_tag_no($tag_no){
		return  Tags::findFirst(array("tag_no = :tag_no: ",'bind' => array('tag_no' => $tag_no) ));
	} 
}
