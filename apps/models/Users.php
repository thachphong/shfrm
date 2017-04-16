<?php

namespace Multiple\Models;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;
use Multiple\Models\DBModel;

class Users extends DBModel
{
    public $user_id;
    public $user_no;
    public $user_name;
    public $pass;    
    public $email;
    //public $avata;
    public function initialize()
    {
        $this->setSource("user");
    }
    public function get_All(){
        $usr_data = Users::query()->execute();
        return $usr_data;
    }
    public function get_user($user_no,$pass){
		return $user = Users::findFirst(array(
                "(email = :email: OR user_no = :email:)  AND del_flg = 0 ",
                'bind' => array('email' => $user_no)
        ));
	}
	public function get_row($user_no,$pass){
        $sql="select * from user t
        where ( user_no = :email)  AND del_flg = 0 ";
                
        
		$data = $this->pho_query($sql,array('email' => $user_no));
		//return $data[0]['list_id'];
		
        if(count($data) > 0){
			return TRUE;
		}
		return FALSE;
    }
    /*public function validation()
    {
        $this->validate(new EmailValidator(array(
            'field' => 'email'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'email',
            'message' => 'Sorry, The email was registered by another user'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'username',
            'message' => 'Sorry, That username is already taken'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }*/
}
