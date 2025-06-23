<?php

class WantedSkill
{
    // Properties
    private $id;
    private $categoryId;
    private $skill;

    // Constructor
    public function __construct($id, $categoryId, $skill)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->skill = $skill;
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

    public function getSkill()
    {
        return $this->skill;
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

    public function setSkill($skill)
    {
        $this->skill = $skill;
    }
}

?>
