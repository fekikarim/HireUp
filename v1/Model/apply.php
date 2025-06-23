<?php

class ApplyModel
{
    // Properties
    private $applyId;
    private $applyProfileId;
    private $applyJobId;
    private $apply_desc;
    private $date;
    private $status;

    // Constructor
    public function __construct($applyProfileId, $applyJobId, $status, $apply_desc)
    {
        $this->applyProfileId = $applyProfileId;
        $this->applyJobId = $applyJobId;
        $this->status = $status;
        $this->apply_desc = $apply_desc;
    }

    // Getters
    public function getApplyProfileId()
    {
        return $this->applyProfileId;
    }

    public function getApplyJobId()
    {
        return $this->applyJobId;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDesc()
    {
        return $this->apply_desc;
    }

    // Setters
    public function setApplyProfileId($applyProfileId)
    {
        $this->applyProfileId = $applyProfileId;
    }

    public function setApplyJobId($applyJobId)
    {
        $this->applyJobId = $applyJobId;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function setDesc($val)
    {
        $this->apply_desc = $val;
    }
}

?>
