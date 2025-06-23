<?php

class Reponse{

    private $id, $contenu, $date_reponse, $id_user, $id_reclamation;


    public function __construct($id, $contenu, $date_reponse, $id_user, $id_reclamation){
        $this->id = $id;
        $this->contenu = $contenu;
        $this->date_reponse = $date_reponse;
        $this-> id_user = $id_user;
        $this->id_reclamation = $id_reclamation;
        
    }

    public function set_id($val){
        $this->id = $val;
    }

    public function get_id(){
        return $this->id;
    }

    public function set_contenu($val){
        $this->contenu = $val;
    }

    public function get_contenu(){
        return $this->contenu;
    }

    public function set_date_reponse($val){
        $this->date_reponse = $val;
    }

    public function get_date_reponse(){
        return $this->date_reponse;
    }


    public function set_id_user($val){
        $this->id_user = $val;
    }

    public function get_id_user(){
        return $this->id_user;
    }


    public function set_id_reclamation($val){
        $this->id_reclamation = $val;
    }

    public function get_id_reclamation(){
        return $this->id_reclamation;
    }

    

   
    

}



?>