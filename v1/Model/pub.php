<?php

class Pub{

    private $idpub, $titre, $contenu, $dat, $id_dmd, $pub_link;


    public function __construct($idpub, $titre, $contenu, $dat, $id_dmd, $pub_link){
        $this->idpub = $idpub;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->dat = $dat;
        $this->id_dmd = $id_dmd;
        $this->pub_link = $pub_link;
    }

    public function set_idpub($val){
        $this->idpub = $val;
    }

    public function get_idpub(){
        return $this->idpub;
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


    public function set_dat($val){
        $this->dat = $val;
    }

    public function get_dat(){
        return $this->dat;
    }

    public function set_id_dmd($val){
        $this->id_dmd = $val;
    }

    public function get_id_dmd(){
        return $this->id_dmd;
    }

    public function set_pub_link($val){
        $this->pub_link = $val;
    }

    public function get_pub_link(){
        return $this->pub_link;
    }


   
    

}



?>