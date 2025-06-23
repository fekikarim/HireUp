<?php

class Notification {

    private $id, $sender_id, $receiver_id, $content, $link, $from_hire_up, $date_time, $seen;

    public function __construct($id, $sender_id, $receiver_id, $content, $link, $from_hire_up, $date_time=null, $seen='not seen'){
        
        if ($date_time === null) {
            $date_time = date("Y-m-d H:i:s");
        }

        $this->id = $id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->content = $content;
        $this->link = $link;
        $this->from_hire_up = $from_hire_up;
        $this->date_time = $date_time;
        $this->seen = $seen;
    }

    public function set_id($val){
        $this->id = $val;
    }

    public function get_id(){
        return $this->id;
    }

    public function set_sender_id($val){
        $this->sender_id = $val;
    }

    public function get_sender_id(){
        return $this->sender_id;
    }

    public function set_receiver_id($val){
        $this->receiver_id = $val;
    }

    public function get_receiver_id(){
        return $this->receiver_id;
    }

    public function set_content($val){
        $this->content = $val;
    }

    public function get_content(){
        return $this->content;
    }

    public function set_link($val){
        $this->link = $val;
    }

    public function get_link(){
        return $this->link;
    }

    public function set_from_hire_up($val){
        $this->from_hire_up = $val;
    }

    public function get_from_hire_up(){
        return $this->from_hire_up;
    }

    public function set_date_time($val){
        $this->date_time = $val;
    }

    public function get_date_time(){
        return $this->date_time;
    }

    public function set_seen($val){
        $this->seen = $val;
    }

    public function get_seen(){
        return $this->seen;
    }
    
}

?>
