<?php

namespace Multiple\Models;
use Phalcon\Mvc\Model;
//use Phalcon\Mvc\Model\Query;

class DBModel extends Model
{      
    protected function pho_query($sql,$param){        
        $db = \Phalcon\DI::getDefault()->get('db');
		$stmt = $db->prepare($sql);
		$stmt->execute($param);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);		
    }
   
}
