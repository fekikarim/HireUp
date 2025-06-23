<?php

class Meeting 
{
    // Properties
    private $id;
    private $roomName;
    private $creationDate;
    private $meetingDesc;
    private $meetingAt;
    private $meetingJobId;

    // Constructor
    public function __construct($id, $roomName, $creationDate, $meetingDesc, $meetingAt, $meetingJobId) {
        $this->id = $id;
        $this->roomName = $roomName;
        $this->creationDate = $creationDate;
        $this->meetingDesc = $meetingDesc;
        $this->meetingAt = $meetingAt;
        $this->meetingJobId = $meetingJobId;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getRoomName() {
        return $this->roomName;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getmeetingDesc() {
        return $this->meetingDesc;
    }

    public function getmeetingAt() {
        return $this->meetingAt;
    }
    public function getmeetingJobId() {
        return $this->meetingJobId;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setRoomName($roomName) {
        $this->roomName = $roomName;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setmeetingDesc($val) {
        $this->meetingDesc = $val;
    }

    public function setmeetingAt($val) {
        $this->meetingAt = $val;
    }

    public function setmeetingJobId($val) {
        $this->meetingJobId = $val;
    }
}

?>
