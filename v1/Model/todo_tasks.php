<?php

class TodoTask
{
    // Properties
    private $id;
    private $profileId;
    private $task;
    private $status;
    private $addedDate;

    // Constructor
    public function __construct($id, $profileId, $task, $status, $addedDate)
    {
        $this->id = $id;
        $this->profileId = $profileId;
        $this->task = $task;
        $this->status = $status;
        $this->addedDate = $addedDate;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getProfileId()
    {
        return $this->profileId;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getAddedDate()
    {
        return $this->addedDate;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;
    }
}

?>
