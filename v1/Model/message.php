<?php

class Message {

    private $id, $sender_id, $receiver_id, $message_content, $date_time, $seen;

    public function __construct($id, $sender_id, $receiver_id, $message_content, $date_time, $seen='not seen'){
        $this->id = $id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->message_content = $message_content;
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

    public function set_message_content($val){
        $this->message_content = $val;
    }

    public function get_message_content(){
        return $this->message_content;
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
