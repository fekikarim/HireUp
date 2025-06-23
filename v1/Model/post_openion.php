<?php

class PostOpenion {
    private $id, $post_openion, $id_post, $id_profile;

    public function __construct($id, $post_openion, $id_post, $id_profile) {
        $this->id = $id;
        $this->post_openion = $post_openion;
        $this->id_post = $id_post;
        $this->id_profile = $id_profile;
    }

    // Setters and getters for id
    public function set_id($val) {
        $this->id = $val;
    }

    public function get_id() {
        return $this->id;
    }

    // Setters and getters for post_openion
    public function set_post_openion($val) {
        $this->post_openion = $val;
    }

    public function get_post_openion() {
        return $this->post_openion;
    }

    // Setters and getters for id_post
    public function set_id_post($val) {
        $this->id_post = $val;
    }

    public function get_id_post() {
        return $this->id_post;
    }

    // Setters and getters for id_profile
    public function set_id_profile($val) {
        $this->id_profile = $val;
    }

    public function get_id_profile() {
        return $this->id_profile;
    }
}

?>
