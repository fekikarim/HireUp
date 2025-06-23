<?php

class Friendship {

    private $id, $profile1, $profile2, $status, $sender_profile, $time;

    public function __construct($id, $profile1, $profile2, $status, $sender_profile, $time){
        $this->id = $id;
        $this->profile1 = $profile1;
        $this->profile2 = $profile2;
        $this->status = $status;
        $this->sender_profile = $sender_profile;
        $this->time = $time;
    }

    public function set_id($val){
        $this->id = $val;
    }

    public function get_id(){
        return $this->id;
    }

    public function set_profile1($val){
        $this->profile1 = $val;
    }

    public function get_profile1(){
        return $this->profile1;
    }

    public function set_profile2($val){
        $this->profile2 = $val;
    }

    public function get_profile2(){
        return $this->profile2;
    }

    public function set_status($val){
        $this->status = $val;
    }

    public function get_status(){
        return $this->status;
    }

    public function set_sender_profile($val){
        $this->sender_profile = $val;
    }

    public function get_sender_profile(){
        return $this->sender_profile;
    }

    public function set_time($val){
        $this->time = $val;
    }

    public function get_time(){
        return $this->time;
    }

}

?>
