<?php

class Face
{
    // Properties
    private $id;
    private $userId;
    private $content;
    private $name;

    // Constructor
    public function __construct($id, $userId, $content, $name)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->content = $content;
        $this->name = $name;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getName()
    {
        return $this->name;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

?>
