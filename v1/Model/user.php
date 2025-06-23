<?php

class User{

    private $id, $user_name, $email, $password, $role, $verified, $banned, $date, $account_type, $need_password_change;


    public function __construct($id, $user_name, $email, $password, $role, $verified, $banned, $date, $account_type='hireup', $need_password_change='false'){
        $this->id = $id;
        $this->user_name = $user_name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->verified = $verified;
        $this->banned = $banned;
        $this->date = $date;
        $this->account_type = $account_type;
        $this->need_password_change = $need_password_change;
    }

    public function set_id($val){
        $this->id = $val;
    }

    public function get_id(){
        return $this->id;
    }

    public function set_user_name($val){
        $this->user_name = $val;
    }

    public function get_user_name(){
        return $this->user_name;
    }

    public function set_email($val){
        $this->email = $val;
    }

    public function get_email(){
        return $this->email;
    }


    public function set_password($val){
        $this->password = $val;
    }

    public function get_password(){
        return $this->password;
    }


    public function set_role($val){
        $this->role = $val;
    }

    public function get_role(){
        return $this->role;
    }

    public function set_verified($val){
        $this->verified = $val;
    }

    public function get_verified(){
        return $this->verified;
    }

    public function set_banned($val){
        $this->banned = $val;
    }

    public function get_banned(){
        return $this->banned;
    }

    public function set_date($val){
        $this->date = $val;
    }

    public function get_date(){
        return $this->date;
    }

    public function set_account_type($val){
        $this->account_type = $val;
    }

    public function get_account_type(){
        return $this->account_type;
    }
    

    public function set_need_password_change($val){
        $this->need_password_change = $val;
    }

    public function get_need_password_change(){
        return $this->need_password_change;
    }

}



?>