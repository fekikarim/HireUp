<?php

class Stats{

    private $date, $accounts_created;

    public function __construct($date, $accounts_created){
        $this->date = $date;
        $this->accounts_created = $accounts_created;
    }

    public function set_date($val){
        $this->date = $val;
    }

    public function get_date(){
        return $this->date;
    }

    public function set_accounts_created($val){
        $this->accounts_created = $val;
    }

    public function get_accounts_created(){
        return $this->accounts_created;
    }

}



?>