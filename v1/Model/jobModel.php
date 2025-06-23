<?php

class JobModel 
{
    // Properties
    private $jobTitle;
    private $company;
    private $location;
    private $description;
    private $salary;
    private $datePosted;
    private $category;
    private $lng;
    private $lat;

    // Constructor
    public function __construct($jobTitle, $company, $location, $description, $salary, $datePosted,$category,$lng,$lat) {
        $this->jobTitle = $jobTitle;
        $this->company = $company;
        $this->location = $location;
        $this->description = $description;
        $this->salary = $salary;
        $this->datePosted = $datePosted;
        $this->$category = $category;
        $this->$lng = $lng;
        $this->$lat = $lat;
    }

    // Getters
    public function getJobTitle() {
        return $this->jobTitle;
    }
    
    public function getcategory() {
        return $this->category;
    }

    public function getCompany() {
        return $this->company;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getSalary() {
        return $this->salary;
    }
 


    public function getDatePosted() {
        return $this->datePosted;
    }

    public function getLng() {
        return $this->lng;
    }

    public function getLat() {
        return $this->lat;
    }

    // Setters
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }

    public function setCompany($company) {
        $this->company = $company;
    }
    
    public function setcategory($category) {
        $this->category = $category;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setSalary($salary) {
        $this->salary = $salary;
    }

    public function setDatePosted($datePosted) {
        $this->datePosted = $datePosted;
    }

    public function setLng($lng) {
        $this->lng = $lng;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }
}
?>
