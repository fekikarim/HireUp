<?php

class Resume
{
    // Properties
    private $id;
    private $uploadedBy;
    private $applyId;
    private $content;
    private $uploadedAt;
    private $jsonData;

    // Constructor
    public function __construct($id, $uploadedBy, $applyId, $content, $uploadedAt, $jsonData)
    {
        $this->id = $id;
        $this->uploadedBy = $uploadedBy;
        $this->applyId = $applyId;
        $this->content = $content;
        $this->uploadedAt = $uploadedAt;
        $this->jsonData = $jsonData;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }

    public function getApplyId()
    {
        return $this->applyId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    public function getJsonData()
    {
        return $this->jsonData;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUploadedBy($uploadedBy)
    {
        $this->uploadedBy = $uploadedBy;
    }

    public function setApplyId($applyId)
    {
        $this->applyId = $applyId;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;
    }

    public function setJsonData($jsonData)
    {
        $this->jsonData = $jsonData;
    }
}

?>