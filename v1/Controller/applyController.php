<?php

require_once __DIR__ . '/../config.php';
require_once 'profileController.php';
require_once 'JobC.php';

class ApplyController
{
    private $conn;

    public function __construct()
    {
        $this->conn = config::getConnexion(); // Get PDO connection
    }

    // Update Apply
    public function updateApply($applyId, $applyProfileId, $applyJobId, $status, $apply_desc)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("UPDATE apply SET apply_profile_id = :applyProfileId, apply_job_id = :applyJobId, status = :status, apply_desc = :apply_desc WHERE apply_id = :applyId");

            // Bind parameters
            $stmt->bindParam(':applyProfileId', $applyProfileId);
            $stmt->bindParam(':applyJobId', $applyJobId);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':applyId', $applyId);
            $stmt->bindParam(':apply_desc', $apply_desc);

            // Execute the statement
            $stmt->execute();

            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                return true; // Update successful
            } else {
                return false; // No rows were affected
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function updateApplyDesc($applyId, $apply_desc)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("UPDATE apply SET apply_desc = :apply_desc WHERE apply_id = :applyId");

            // Bind parameters
            $stmt->bindParam(':applyId', $applyId);
            $stmt->bindParam(':apply_desc', $apply_desc);

            // Execute the statement
            $stmt->execute();

            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                return true; // Update successful
            } else {
                return false; // No rows were affected
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    // Add Apply
    public function addApply($apply_id, $applyProfileId, $applyJobId, $date, $status, $apply_desc)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("INSERT INTO apply (apply_id, apply_profile_id, apply_job_id, date, status, apply_desc) VALUES (:apply_id, :applyProfileId, :applyJobId, :date, :status, :apply_desc)");

            // Bind parameters
            $stmt->bindParam(':apply_id', $apply_id);
            $stmt->bindParam(':applyProfileId', $applyProfileId);
            $stmt->bindParam(':applyJobId', $applyJobId);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':apply_desc', $apply_desc);

            // Execute the statement
            $stmt->execute();

            // Check if the row was inserted successfully
            if ($stmt->rowCount() > 0) {
                return true; // Insert successful
            } else {
                return false; // Insert failed
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Delete Apply
    public function deleteApply($applyId)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("DELETE FROM apply WHERE apply_id = :applyId");

            // Bind parameters
            $stmt->bindParam(':applyId', $applyId);

            // Execute the statement
            $stmt->execute();

            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                return true; // Delete successful
            } else {
                return false; // No rows were affected
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Apply List
    public function applyList()
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT * FROM apply");

            // Execute the statement
            $stmt->execute();

            // Fetch all apply records
            $applyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $applyList;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function applyListForProfileID($profile_id)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT apply.* FROM apply INNER JOIN jobs ON apply.apply_job_id = jobs.id WHERE jobs.jobs_profile = :profile_id");

            // Bind the profile_id parameter
            $stmt->bindParam(':profile_id', $profile_id);

            // Execute the statement
            $stmt->execute();

            // Fetch all apply records
            $applyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $applyList;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Get Apply By ID
    public function getApplyById($applyId)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT * FROM apply WHERE apply_id = :applyId");

            // Bind parameters
            $stmt->bindParam(':applyId', $applyId);

            // Execute the statement
            $stmt->execute();

            // Fetch the apply record
            $apply = $stmt->fetch(PDO::FETCH_ASSOC);

            return $apply;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Get Apply By Profile ID
    public function getApplyByProfileId($applyProfileId)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT * FROM apply WHERE apply_profile_id = :applyProfileId");

            // Bind parameters
            $stmt->bindParam(':applyProfileId', $applyProfileId);

            // Execute the statement
            $stmt->execute();

            // Fetch all apply records for the given profile ID
            $applyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $applyList;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Get Apply By Job ID
    public function getApplyByJobId($applyJobId)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT * FROM apply WHERE apply_job_id = :applyJobId");

            // Bind parameters
            $stmt->bindParam(':applyJobId', $applyJobId);

            // Execute the statement
            $stmt->execute();

            // Fetch all apply records for the given job ID
            $applyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $applyList;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Fetch Profile Names By Apply Profile ID
    public function fetchProfileNamesByApplyProfileId($applyProfileId)
    {
        // Assuming you have a method in your profile controller to fetch profile names
        $profileController = new ProfileC();
        $profileNames = [];

        // Fetch profiles associated with the given apply profile ID
        $applyList = $this->getApplyByProfileId($applyProfileId);

        // Iterate through apply records and fetch profile names
        foreach ($applyList as $apply) {
            $profileId = $apply['apply_profile_id'];
            $profile = $profileController->getProfileById($profileId);
            if ($profile) {
                $profileNames[] = $profile['name']; // Assuming 'name' is the column for profile names
            }
        }

        return $profileNames;
    }

    // Fetch Job Title By Apply Job ID
    public function fetchJobTitleByApplyJobId($applyJobId)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT title FROM jobs WHERE id = :applyJobId");

            // Bind parameters
            $stmt->bindParam(':applyJobId', $applyJobId);

            // Execute the statement
            $stmt->execute();

            // Fetch the job title
            $jobTitle = $stmt->fetchColumn();

            return $jobTitle;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public function generateId($id_length)
    {
        $numbers = '0123456789';
        $numbers_length = strlen($numbers);
        $random_id = '';

        // Generate random ID
        for ($i = 0; $i < $id_length; $i++) {
            $random_id .= $numbers[rand(0, $numbers_length - 1)];
        }

        return (string) $random_id; // Ensure the return value is a string
    }

    public function applyExists($id)
    {
        $tableName = "apply";

        $sql = "SELECT COUNT(*) as count FROM $tableName WHERE apply_id = :apply_id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':apply_id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateApplyId($id_length)
    {
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->applyExists($current_id));

        return $current_id;
    }

    public function getApplyIdByJobIdAndProfileId($jobId, $profileId)
    {
        try {
            // Prepare SQL statement
            $query = "SELECT apply_id FROM apply WHERE apply_job_id = :jobId AND apply_profile_id = :profileId";

            // Prepare the statement
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':jobId', $jobId);
            $stmt->bindParam(':profileId', $profileId);

            // Execute the query
            $stmt->execute();

            // Fetch apply ID
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If apply ID exists, return it, otherwise return null
            if ($result !== false) {
                return $result['apply_id'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Handle database errors (optional)
            echo "Error: " . $e->getMessage();
            return null;
        }
    }



    public function getApplyStatusFromPrfIdJobId($profileId, $jobId)
    {
        try {
            // Prepare SQL statement
            $query = "SELECT status FROM apply WHERE apply_profile_id = :profileId AND apply_job_id = :jobId";

            // Prepare the statement
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':profileId', $profileId);
            $stmt->bindParam(':jobId', $jobId);

            // Execute the query
            $stmt->execute();

            // Fetch status
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If status exists, return it, otherwise return 'none'
            if ($result !== false) {
                return $result['status'];
            } else {
                return 'none';
            }
        } catch (PDOException $e) {
            // Handle database errors (optional)
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getApplyFromPrfIdJobId($profileId, $jobId)
    {
        try {
            // Prepare SQL statement
            $query = "SELECT * FROM apply WHERE apply_profile_id = :profileId AND apply_job_id = :jobId";

            // Prepare the statement
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':profileId', $profileId);
            $stmt->bindParam(':jobId', $jobId);

            // Execute the query
            $stmt->execute();

            // Fetch status
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If status exists, return it, otherwise return 'none'
            if ($result !== false) {
                return $result;
            } else {
                return 'none';
            }
        } catch (PDOException $e) {
            // Handle database errors (optional)
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Get Applied Jobs By Profile ID
    public function getAppliedJobsByProfileId($profileId)
    {
        try {
            // Prepare the SQL statement with status filtering
            $stmt = $this->conn->prepare("SELECT jobs.*, category.name_category  AS name_category 
                                      FROM jobs 
                                      INNER JOIN category ON jobs.id_category  = category.id_category  
                                      WHERE jobs.id IN 
                                          (SELECT apply_job_id 
                                           FROM apply 
                                           WHERE apply_profile_id = :profileId AND status IN ('pending', 'HiredUp', 'interview'))");

            // Bind parameters
            $stmt->bindParam(':profileId', $profileId);

            // Execute the statement
            $stmt->execute();

            // Fetch all applied jobs
            $appliedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $appliedJobs;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    

    public function updateApplyStatus($applyId, $status)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("UPDATE apply SET status = :status WHERE apply_id = :applyId");

            // Bind parameters
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':applyId', $applyId);

            // Execute the statement
            $stmt->execute();

            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                return true; // Update successful
            } else {
                return false; // No rows were affected
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
