<?php

class CategoryInterest
{
    // Properties
    private $id;
    private $categoryId;
    private $profileId;
    private $state;

    // Constructor
    public function __construct($id, $categoryId, $profileId, $state)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->profileId = $profileId;
        $this->state = $state;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getProfileId()
    {
        return $this->profileId;
    }

    public function getState()
    {
        return $this->state;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}

?>
