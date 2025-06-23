<?php

class Dmd{

    private $iddemande, $titre, $contenu, $objectif, $dure, $budget, $image, $user_id, $status, $paid;


    public function __construct($iddemande, $titre, $contenu, $objectif, $dure, $budget, $image, $user_id, $status='', $paid=''){
        $this->iddemande = $iddemande;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->objectif = $objectif;
        $this->dure = $dure;
        $this->budget = $budget;
        $this->image = $image;
        $this->user_id = $user_id;
        $this->status = $status;
        $this->paid = $paid;
    }


    public function set_iddemande($val){
        $this->iddemande = $val;
    }

    public function get_iddemande(){
        return $this->iddemande;
    }

    public function set_titre($val){
        $this->titre = $val;
    }

    public function get_titre(){
        return $this->titre;
    }

    public function set_contenu($val){
        $this->contenu = $val;
    }

    public function get_contenu(){
        return $this->contenu;
    }


    public function set_objectif($val){
        $this->objectif = $val;
    }

    public function get_objectif(){
        return $this->objectif;
    }


    public function set_dure($val){
        $this->dure = $val;
    }

    public function get_dure(){
        return $this->dure;
    }

    public function set_budget($val){
        $this->budget = $val;
    }

    public function get_budget(){
        return $this->budget;
    }

    public function set_image($val){
        $this->image = $val;
    }

    public function get_image(){
        return $this->image;
    }

    public function set_user_id($val){
        $this->user_id = $val;
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function set_status($val){
        $this->status = $val;
    }

    public function get_status(){
        return $this->status;
    }

    public function set_paid($val){
        $this->paid = $val;
    }

    public function get_paid(){
        return $this->paid;
    }

   
    

}



?>