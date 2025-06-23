<?php

class MeetingParticipants 
{
    // Properties
    private $profileId;
    private $meetingId;
    private $profileRole;
    private $addedAt;
    private $id_sch;

    // Constructor
    public function __construct($profileId, $meetingId, $profileRole, $addedAt, $id_sch) {
        $this->profileId = $profileId;
        $this->meetingId = $meetingId;
        $this->profileRole = $profileRole;
        $this->addedAt = $addedAt;
        $this->id_sch = $id_sch;
    }

    // Getters
    public function getProfileId() {
        return $this->profileId;
    }

    public function getMeetingId() {
        return $this->meetingId;
    }

    public function getProfileRole() {
        return $this->profileRole;
    }

    public function getAddedAt() {
        return $this->addedAt;
    }

    public function getid_sch() {
        return $this->id_sch;
    }

    // Setters
    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    public function setMeetingId($meetingId) {
        $this->meetingId = $meetingId;
    }

    public function setProfileRole($profileRole) {
        $this->profileRole = $profileRole;
    }

    public function setAddedAt($addedAt) {
        $this->addedAt = $addedAt;
    }

    public function setid_sch($val) {
        $this->id_sch = $val;
    }
}

?>
