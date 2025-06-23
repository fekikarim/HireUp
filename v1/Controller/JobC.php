<?php

require_once __DIR__ . '/../config.php';
require_once 'profileController.php';
require_once 'categoryC.php';

class JobController
{
    private $conn;

    public function __construct()
    {
        $this->conn = config::getConnexion(); // Get PDO connection
    }

    // Function to get education level for a given category
    /*
// Function to retrieve user profile education based on user ID
public function getUserProfileEducation($userId) {
    try {
        // Prepare and execute query to fetch user profile education
        $stmt = $this->conn->prepare("SELECT profile_education FROM profile WHERE profile_userid = ?");
        $stmt->execute([$userId]);
        $userProfileEducation = $stmt->fetchColumn(); // Assuming only one education per user
        
        return $userProfileEducation;
    } catch (PDOException $e) {
        // Handle any errors, such as database connection errors
        return "Error: " . $e->getMessage();
    }
}
*/

    public function fetchJobsByCategory($categoryId, $limit = null)
    {
        try {
            // Prepare SQL query to fetch jobs based on category ID and join with the categories table to get category name
            $sql = "SELECT j.*, c.name_category AS category_name FROM jobs j
                INNER JOIN categories c ON j.category = c.id_category
                WHERE j.category = ?";

            // If $limit is specified, add LIMIT clause to the SQL query
            if ($limit !== null) {
                $sql .= " LIMIT $limit";
            }

            // Prepare and execute SQL query
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$categoryId]);
            $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $jobs; // Return fetched jobs
        } catch (PDOException $e) {
            // Handle database error
            return false;
        }
    }

    // Define the function to fetch jobs by education level
    function fetchJobsByEducationLevel($profileId)
    {
        // Create instances of ProfileController and JobController
        $profileController = new ProfileC();
        $jobController = new JobController();

        // Fetch profile data to get the education level
        $profileData = $profileController->getProfileById($profileId);

        // Check if profile data was fetched successfully
        if ($profileData) {
            // Get the education level from the profile data
            $educationLevel = $profileController->getProfileEducation($profileId); // Use the ProfileController method

            // Fetch jobs based on education level
            $jobs = $jobController->fetchJobsByCategory($educationLevel); // Assuming you have a method to fetch jobs by category

            // Return the fetched jobs
            return $jobs;
        } else {
            // Handle the case where profile data could not be fetched
            return false;
        }
    }
    // Create a new job
    // Create a new job
    public function createJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image, $profile_id, $lng = '', $lat = '')
    {
        try {
            // Fetch the id_category based on the selected category
            $stmt = $this->conn->prepare("SELECT id_category FROM category WHERE name_category = ?");
            $stmt->execute([$category]);
            $categoryResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $categoryID = $categoryResult['id_category'];

            // Get the current date and time
            $date_posted = date("Y-m-d H:i:s");

            // Insert the job into the database with the provided profile ID
            $stmt = $this->conn->prepare("INSERT INTO jobs (id, title, company, location, description, salary, date_posted, job_image, id_category, jobs_profile, lng, lat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$job_id, $title, $company, $location, $description, $salary, $date_posted, $job_image, $categoryID, $profile_id, $lng, $lat]);

            return "New job created successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    // Update a job
    public function updateJob($id, $title, $company, $location, $description, $salary, $category, $job_image, $lng = '', $lat = '')
    {
        try {

            // Fetch the id_category based on the selected category
            $stmt = $this->conn->prepare("SELECT id_category FROM category WHERE name_category = ?");
            $stmt->execute([$category]);
            $categoryResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $categoryID = $categoryResult['id_category'];

            $stmt = $this->conn->prepare("UPDATE jobs SET title=?, company=?, location=?, description=?, salary=? , id_category=? , job_image=? , lng=? , lat=? WHERE id=?");
            $stmt->execute([$title, $company, $location, $description, $salary, $categoryID, $job_image, $lng, $lat, $id]);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function updateJobWithoutImage($id, $title, $company, $location, $description, $salary, $category, $lat = '', $lng = '')
    {
        try {

            // Fetch the id_category based on the selected category
            $stmt = $this->conn->prepare("SELECT id_category FROM category WHERE name_category = ?");
            $stmt->execute([$category]);
            $categoryResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $categoryID = $categoryResult['id_category'];

            $stmt = $this->conn->prepare("UPDATE jobs SET title=?, company=?, location=?, description=?, salary=? , id_category=? , lng=? , lat=? WHERE id=?");
            $stmt->execute([$title, $company, $location, $description, $salary, $categoryID, $lng, $lat, $id]);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function updateJobImage($id, $job_image_data)
    {
        try {

            $tableName = "jobs";
            // Prepare SQL statement to update profile picture
            $sql = "UPDATE $tableName SET job_image = :job_image WHERE id = :id";
            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':job_image', $job_image_data, PDO::PARAM_LOB);

            // Execute the query
            $stmt->execute();

            // Check if the update was successful
            if ($stmt->rowCount() > 0) {
                return true; // Return true if update successful
            } else {
                return false; // Return false if update failed
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
            return false; // Return false if an error occurred
        }
    }


    // Read all jobs
    public function getAllJobs()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM jobs ORDER BY date_posted ");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    // Delete a job
    public function deleteJob($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM jobs WHERE id=?");
            $stmt->execute([$id]);
            return "Job deleted successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /*
    // Get all jobs
    public function getJobs()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM jobs");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    */
    /*
    // Read a job by ID
    public function readJob($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM jobs WHERE id=?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return $result ? $result : "Job not found";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
*/
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


    public function jobExists($id)
    {
        $tableName = "jobs";

        $sql = "SELECT COUNT(*) as count FROM $tableName WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }


    public function generateJobId($id_length)
    {
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->jobExists($current_id));

        return $current_id;
    }


    public function generateCategoryOptions()
    {
        // Fetching the category IDs from the database
        $sql = "SELECT id_category, name_category FROM category";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['name_category'] . '">' . $row['name_category'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }
    // Fetch job data including category information
    public function getAllJobsWithCategory()
    {
        try {
            $stmt = $this->conn->prepare("SELECT jobs.*, category.name_category AS category_name FROM jobs INNER JOIN category ON jobs.id_category = category.id_category ORDER BY jobs.date_posted");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /*
    public function generateCategoryOptionsselected($nom)
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT id_category, name_category FROM category";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($nom == $row['name_category']) {
                    $options .= '<option selected value="' . $row['name_category'] . '">' . $row['name_category'] . '</option>';
                } else {

                    $options .= '<option value="' . $row['name_category'] . '">' . $row['name_category'] . '</option>';
                }
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }
    */

    /*
    // Get company id by category
    public function getCompanyIdByCategory($category)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id_company FROM company WHERE category = ?");
            $stmt->execute([$category]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['id_company'] ?? null;
        } catch (PDOException $e) {
            return false;
        }
    }
*/

    /*
    public function getAllJobsSortedByProfileEducation()
    {
        try {
            // Query to fetch jobs sorted by profile education
            $sql = "SELECT j.*, c.name AS category_name 
                FROM job j
                INNER JOIN category c ON j.category_id = c.id_category
                INNER JOIN profile p ON c.name_category = p.profile_education
                ORDER BY p.profile_education DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }*/


    public function generateProfileOptions()
    {
        // Fetching the profile IDs from the database
        $sql = "SELECT profile_id FROM profile";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['profile_id'] . '">' . $row['profile_id'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }


    public function getAllJobsSortedByProfileEducation($profile_id)
    {
        try {
            // Prepare and execute SQL query to fetch jobs sorted by profile education
            $stmt = $this->conn->prepare("
            SELECT jobs.*, category.name_category 
            FROM jobs 
            INNER JOIN category ON jobs.id_category = category.id_category
            INNER JOIN profile ON category.name_category = profile.profile_education
            WHERE profile.profile_id = ?
            ORDER BY 
                CASE 
                    WHEN profile.profile_education = 'Web Designing' THEN 1
                    WHEN profile.profile_education = 'Graphic Design' THEN 2
                    WHEN profile.profile_education = 'Software Engineering' THEN 3
                    ELSE 4
                END,
                jobs.id ASC
        ");
            $stmt->execute([$profile_id]);
            $sortedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Now fetch the remaining unsorted jobs
            $stmt = $this->conn->prepare("
            SELECT jobs.*, category.name_category 
            FROM jobs 
            LEFT JOIN category ON jobs.id_category = category.id_category
            WHERE jobs.id NOT IN (SELECT id FROM jobs WHERE id_category IN (
                SELECT id_category FROM category WHERE name_category IN (
                    SELECT profile_education FROM profile WHERE profile_id = ?
                )
            ))
        ");
            $stmt->execute([$profile_id]);
            $unsortedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Combine and return both sorted and unsorted jobs
            $allJobs = array_merge($sortedJobs, $unsortedJobs);
            return $allJobs;
        } catch (PDOException $e) {
            // Handle exception
            return [];
        }
    }

    public function getJobById($jobId)
    {
        try {
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("SELECT * FROM jobs WHERE id = :job_id");

            // Bind parameters
            $stmt->bindParam(':job_id', $jobId);

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

    public function getAllRecentJobs()
    {
        try {
            $stmt = $this->conn->prepare("SELECT jobs.id_category, jobs.date_posted, jobs.title, jobs.company, jobs.location, jobs.description, jobs.salary, jobs.jobs_profile FROM jobs 
                                        INNER JOIN category ON jobs.id_category = category.id_category 
                                        WHERE DATE(jobs.date_posted) = CURDATE() 
                                        ORDER BY jobs.date_posted");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getAllJobsWhereProfileId($profile_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM jobs WHERE jobs_profile = :profile_id ORDER BY date_posted");
            $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getUserLocation($link="http://ip-api.com/json/")
    {
        // Get user's IP address
        $userIP = $this->getUserIP();
    
        // Call an IP geolocation API to get location details
        //$apiURL = "http://ip-api.com/json/$userIP";
        $apiURL = "http://ip-api.com/json/";
        //$ip = $_SERVER['REMOTE_ADDR'];
        //$api_url = "https://freegeoip.app/json/";
        $api_url = $apiURL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirections
        $response = curl_exec($ch);
        //var_dump($response);
        curl_close($ch);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function getUserIP() {
        // Initialize IP variable
        $ip = '';
    
        // Check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check for IP behind a proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Check for a public IP address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    
        // Return the IP address
        return $ip;
    }
    
    public function haversineCalculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius of the Earth in kilometers
        $R = 6371.0;

        // Convert latitude and longitude from strings to floats
        $lat1 = floatval($lat1);
        $lon1 = floatval($lon1);
        $lat2 = floatval($lat2);
        $lon2 = floatval($lon2);

        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Compute the differences in coordinates
        $dlon = $lon2 - $lon1;
        $dlat = $lat2 - $lat1;

        // Haversine formula
        $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance calculation
        $distance = $R * $c;
        return $distance;
    }

    public function SortJobsByCategoryAndDistance0()
    {

        $categoryC = new categoryController();

        $user_infos_string = $this->getUserLocation();
        $user_infos = json_decode($user_infos_string, true);
        //var_dump($user_infos);
        if ($user_infos != false) {
            $user_latitude = $user_infos['latitude'];
            $user_longitude = $user_infos['longitude'];
            $all_jobs = $this->getAllJobs();
            $jobs_by_category = array();
            foreach ($all_jobs as $job) {
                $job_category_data = $categoryC->getCategoryById($job['id_category']);
                $job_category_name = $job_category_data['name_category'];
                if (!isset($jobs_by_category[$job_category_name])) {
                    $jobs_by_category[$job_category_name] = array();
                }
                $jobs_by_category[$job_category_name][] = $job;
            }

            // Sort jobs within each category by distance
            foreach ($jobs_by_category as $category => $jobs) {
                foreach ($jobs as &$job) { // Note the '&' to reference the original array element
                    $job['distance'] = $this->haversineCalculateDistance($user_latitude, $user_longitude, $job['lat'], $job['lng']);
                }

                //var_dump($job['distance']);
                usort($jobs, function ($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });
                $jobs_by_category[$category] = $jobs;
            }

            // Sort categories by importance (if applicable)
            // For now, let's assume categories are sorted by importance already

            // Combine jobs from all categories
            $sorted_jobs = array();
            $categories = array_keys($jobs_by_category);
            rsort($categories);
            foreach ($categories as $category) {
                $sorted_jobs = array_merge($sorted_jobs, $jobs_by_category[$category]);
            }
            return $sorted_jobs;
        } else {
            return $all_jobs = $this->getAllJobs();
        }
    }

    public function SortJobsByCategoryAndDistance1($desired_category)
    {
        $categoryC = new categoryController();

        $user_infos_string = $this->getUserLocation();
        $user_infos = json_decode($user_infos_string, true);

        if ($user_infos != false) {
            $user_latitude = $user_infos['latitude'];
            $user_longitude = $user_infos['longitude'];
            $all_jobs = $this->getAllJobs();
            $jobs_by_category = array();

            // Sort jobs within the desired category by distance first
            $desired_jobs = array();
            foreach ($all_jobs as $job) {
                $job_category_data = $categoryC->getCategoryById($job['id_category']);
                $job_category_name = $job_category_data['name_category'];
                if ($job_category_name == $desired_category) {
                    $job['distance'] = $this->haversineCalculateDistance($user_latitude, $user_longitude, $job['lat'], $job['lng']);
                    $desired_jobs[] = $job;
                } else {
                    if (!isset($jobs_by_category[$job_category_name])) {
                        $jobs_by_category[$job_category_name] = array();
                    }
                    $jobs_by_category[$job_category_name][] = $job;
                }
            }

            // Sort the desired category's jobs by distance
            usort($desired_jobs, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            // Sort jobs within each category by distance
            foreach ($jobs_by_category as $category => $jobs) {
                foreach ($jobs as &$job) {
                    $job['distance'] = $this->haversineCalculateDistance($user_latitude, $user_longitude, $job['lat'], $job['lng']);
                }

                usort($jobs, function ($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });
                $jobs_by_category[$category] = $jobs;
            }

            // Define the importance order of categories
            $category_importance = array(
                $desired_category => 0, // Desired category has the highest importance (0)
                // Define other categories and their importance levels here
                // For example:
                // 'SecondCategory' => 1,
                // 'ThirdCategory' => 2,
                // ...
            );

            // Sort categories by importance
            uksort($jobs_by_category, function ($a, $b) use ($category_importance) {
                if (!isset($category_importance[$a])) {
                    return 1; // If importance is not defined, consider $a higher
                } elseif (!isset($category_importance[$b])) {
                    return -1; // If importance is not defined, consider $b higher
                } else {
                    return $category_importance[$a] <=> $category_importance[$b];
                }
            });

            // Combine jobs from all categories, with desired category first
            $sorted_jobs = $desired_jobs;
            foreach ($jobs_by_category as $category => $jobs) {
                $sorted_jobs = array_merge($sorted_jobs, $jobs);
            }
            return $sorted_jobs;
        } else {
            return $this->getAllJobs();
        }
    }

    public function SortJobsByDistance()
    {
        $categoryC = new categoryController();

        $user_infos_string = $this->getUserLocation();
        $user_infos = json_decode($user_infos_string, true);

        if ($user_infos == false) {
            return $this->getAllJobs();
        } else {
            if (isset($user_infos['latitude']) && isset($user_infos['longitude']) ) {
                $user_latitude = $user_infos['latitude'];
                $user_longitude = $user_infos['longitude'];
            } else {
                $user_latitude = $user_infos['lat'];
                $user_longitude = $user_infos['lon'];
            }
            $all_jobs = $this->getAllJobs();
            $sorted_jobs = [];

            foreach ($all_jobs as $job) {
                $current_job_distance = $this->haversineCalculateDistance($user_latitude, $user_longitude, $job['lat'], $job['lng']);
                $job['distance'] = $current_job_distance; // Store the distance in the job array
                $sorted_jobs[] = $job; // Add job to array for sorting
            }

            // Custom sorting function to sort by distance
            usort($sorted_jobs, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            return $sorted_jobs;
        }
    }

    // old
    public function SortJobsByCategory0($desired_categories)
    {
        $categoryC = new categoryController();
        $all_jobs = $this->getAllJobs();
        $sorted_jobs = [];

        // Initialize arrays for each desired category
        foreach ($desired_categories as $category) {
            $sorted_jobs[$category] = [];
        }

        // Group jobs by category
        foreach ($all_jobs as $job) {
            $job_category_data = $categoryC->getCategoryById($job['id_category']);
            $job_category_name = $job_category_data['name_category'];

            // Check if job category matches any desired category
            if (in_array($job_category_name, $desired_categories)) {
                $sorted_jobs[$job_category_name][] = $job;
            }
        }

        // Concatenate jobs in desired category order
        foreach ($desired_categories as $category) {
            $sorted_jobs[$category] = array_merge($sorted_jobs[$category]); // Merge the arrays of each category
        }

        // Flatten the array
        $sorted_jobs = array_merge(...array_values($sorted_jobs));

        return $sorted_jobs;
    }

    public function SortJobsByCategory($desired_categories_dict, $shuffle = false)
    {

        $desired_categories = $desired_categories_dict['table'];
        $disired_cat = $desired_categories_dict['disired_cat'];
        $not_disired_cat = $desired_categories_dict['not_disired_cat'];

        $categoryC = new categoryController();
        $all_jobs = $this->getAllJobs();
        $sorted_jobs = [];

        // Initialize arrays for each desired category
        foreach ($desired_categories as $category) {
            $sorted_jobs[$category] = [];
        }

        // Group jobs by category
        foreach ($all_jobs as $job) {
            $job_category_data = $categoryC->getCategoryById($job['id_category']);
            $job_category_name = $job_category_data['name_category'];

            // Check if job category matches any desired category
            if (in_array($job_category_name, $desired_categories)) {
                $sorted_jobs[$job_category_name][] = $job;
            }
        }

        // Concatenate jobs in desired category order
        foreach ($desired_categories as $category) {
            $sorted_jobs[$category] = array_merge($sorted_jobs[$category]); // Merge the arrays of each category
        }

        // Flatten the array
        $sorted_jobs = array_merge(...array_values($sorted_jobs));

        // do the shuffle prosses
        if ($shuffle) {
            
            $current_tab = 'tab 1';
            $tab1 = array();
            $tab2 = array();
            $tab3 = array();
            foreach ($sorted_jobs as $item) {

                $current_cat = $categoryC->getCategoryById($item['id_category']);
                $current_cat_name = $current_cat['name_category'];

                if (in_array($current_cat_name, $disired_cat)) {
                    $current_tab = 'tab 1';
                } else if (in_array($current_cat_name, $not_disired_cat)) {
                    $current_tab = 'tab 3';
                } else {
                    $current_tab = 'tab 2';
                }

                if ($current_tab == 'tab 1') {
                    $tab1[] = $item;
                } else if ($current_tab == 'tab 2') {
                    $tab2[] = $item;
                } else {
                    $tab3[] = $item;
                }

            }

            shuffle($tab1);
            shuffle($tab2);
            shuffle($tab3);


            //merge the 3 tables
            $sorted_jobs_final = array();
            $sorted_jobs_final = array_merge($tab1, $tab2, $tab3);

            return $sorted_jobs_final;
    
        } else {
            return $sorted_jobs;
        }
    }

    //failed
    public function displayJobs($jobs, $user_profile_id, $categoryC, $applyController, $userId, $resumeController)
    {
        foreach ($jobs as $job) {
            $job_category_data = $categoryC->getCategoryById($job['id_category']);
            $job_category_name = $job_category_data['name_category'];
    
            if (!empty($job['job_image'])) {
                echo '<div class="item-media post-thumbnail embed-responsive-3by2">';
                echo '<a href="#" onclick="openFullScreenImage(\''.base64_encode($job['job_image']).'\')">';
                echo '<img src="data:image/jpeg;base64,'.base64_encode($job['job_image']).'" alt="Job Image">';
                echo '</a>';
                echo '</div>';
            }
    
            echo '<article class="text-center text-md-left vertical-item content-padding bordered post type-post status-publish format-standard has-post-thumbnail sticky position-relative">';
    
            if ($user_profile_id == $job['jobs_profile']) {
                echo '<div class="dropdow mr-3" style="position: absolute; top: 10px; right: 10px;">';
                echo '<span class="dropdown" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer; color: #000; font-size: 35px;">...</span>';
                echo '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">';
                echo '<button type="button" class="dropdown-item" onclick="window.location.href = \'myJobs_list.php#job-'.$job['id'].'\'">Check It</button>';
                echo '</div>';
                echo '</div>';
            }
    
            echo '<div class="item-content">';
            echo '<header class="entry-header">';
            echo '<h3 class="entry-title">';
            echo '<a href="#link" rel="bookmark">'.$job['title'].'</a>';
            echo '</h3>';
            echo '</header>';
            echo '<div class="entry-content">';
            echo '<p>'.$job['description'].'</p>';
            echo '</div>';
            echo '<div class="entry-footer">';
            echo '<i class="color-main fa fa-user"></i>';
            echo '<a href="#"> '.$job['company'].'</a>';
            echo '<i class="color-main fa fa-calendar"></i>';
            echo '<a href="#"> '.$job['date_posted'].'</a>';
            echo '<i class="color-main fa fa-map"></i>';
            echo '<a href="#" onclick="mapStaticMapPopUp(\''.$job['lng'].'\', \''.$job['lat'].'\', \''.$job['location'].'\')">'.$job['location'].'</a>';
            echo '<i class="color-main fa fa-money"></i>';
            echo '<a href="#"> '.$job['salary'].'</a>';
            echo '<i class="color-main fa fa-tag"></i>';
            echo '<a href="#"> '.$job_category_name.'</a>';
    
            $status = $applyController->getApplyStatusFromPrfIdJobId($userId, $job['id']);
            $current_apply_id = $applyController->getApplyIdByJobIdAndProfileId($job['id'], $userId);
            $current_apply = $applyController->getApplyById($current_apply_id);
    
            if ($user_profile_id != $job['jobs_profile']) {
                if ($status == "pending") {
                    echo '<div class="text-end mx-4">';
                    echo '<form id="pendingForm" action="./pendingJob.php" method="post">';
                    echo '<input type="hidden" id="jobId" name="jobId" value="'.$job['id'].'">';
                    echo '<input type="hidden" id="userId" name="userId" value="'.$userId.'">';
                    echo '<button type="submit" id="pendingButton" class="btn btn-outline-secondary">Pending</button>';
                    echo '</form>';
                    echo '</div>';
                } elseif ($status == "HiredUp") {
                    echo '<div class="text-end mx-4">';
                    echo '<button type="submit" disabled id="hiredupButton" class="btn btn-outline-success">HiredUp</button>';
                    echo '</div>';
                } elseif ($status == "interview") {
                    echo '<div class="text-end mx-4">';
                    echo '<button type="submit" disabled id="hiredupButton" class="btn btn-outline-success" onclick="togglePopup1()">Interview</button>';
                    echo '</div>';
                } else {
                    echo '<div class="text-end mx-4">';
                    echo '<button type="button" id="applyButton" class="btn btn-outline-info" onclick="togglePopup(\''.$job['id'].'\', \''.$userId.'\')">Apply</button>';
                    echo '</div>';
                }
            } else {
                echo '<div class="text-end mx-4">';
                echo '<button type="submit" id="applyButton" class="btn btn-outline-info" onclick="window.location.href=\'career_explorers.php\'">Check Appliers</button>';
                echo '</div>';
            }
    
            if ($status == "pending") {
                echo '<div>';
                echo '<p class="mt-5">Chance of Success <a href="javascript:void(0)" onclick="show_success_data(\''.$current_apply['apply_id'].'\')">View more</a></p>';
                echo '<progress id="progressBar" max="100" value="'.$resumeController->getApplyRank($current_apply['apply_id']).'"></progress>';
                echo '</div>';
            }
    
            echo '</div>';
            echo '</article>';
            echo '<br>';
        }
    }

    //failed
    public function generateJobs($jobs, $user_profile_id, $categoryC, $applyController, $userId, $resumeController) {
        foreach ($jobs as $job):

            $job_category_data = $categoryC->getCategoryById($job['id_category']);
            $job_category_name = $job_category_data['name_category'];

           // Display job image if exists -->
            if (!empty($job['job_image'])): 
                echo '<div class="item-media post-thumbnail embed-responsive-3by2">
                    <a href="#"
                        onclick="openFullScreenImage(' . "'" . base64_encode($job['job_image']) . "'" . ')">
                        <img src="data:image/jpeg;base64,'. base64_encode($job['job_image']) . '"
                            alt="Job Image">
                    </a>
                </div> '; 
            endif;
            echo '<article
                class="text-center text-md-left vertical-item content-padding bordered post type-post status-publish format-standard has-post-thumbnail sticky position-relative">';
                //<!-- Dropdown menu -->
                if ($user_profile_id == $job['jobs_profile']) { 

                    echo '<div class="dropdow mr-3" style="position: absolute; top: 10px; right: 10px;">
                        <span class="dropdown" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"
                            style="cursor: pointer; color: #000; font-size: 35px;">...</span>
                        <div class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="dropdownMenuButton">' .
                            /*<!-- <button class="dropdown-item edit-btn" data-job-id="` .  $job['id'] . `"
                                data-job-title="` . $job['title'] . `"
                                data-company="` . $job['company'] . `"
                                data-location="` . $job['location'] . `"
                                data-description="` . $job['description'] . `"
                                data-salary="` . $job['salary'] . `"
                                data-category="` . $job['name_category'] . `"
                                data-jobImg="` . base64_encode($job['job_image']) . `">Edit</button>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="job_id" value="` . $job['id'] . `">
                                <button type="submit" class="dropdown-item"
                                    onclick="return confirm('Are you sure you want to delete this job?')">Delete</button> -->*/
                            '<button type="button" class="dropdown-item"
                                onclick="window.location.href = ' . "'myJobs_list.php#job-" . $job['id'] . "'" . '>Check
                                It</button>
                            </form>
                        </div>
                    </div> ';

                }

                //<!-- Job content -->
                echo '<div class="item-content">
                    <header class="entry-header">
                        <h3 class="entry-title">
                            <a href="#link" rel="bookmark">' .
                                $job['title']. 
                            '</a>
                        </h3>
                    </header>
                    <!-- Job description -->
                    <div class="entry-content">
                        <p>' .
                            $job['description'] .
                        '</p>
                    </div>
                    <!-- Job attributes -->
                    <div class="entry-footer">
                        <i class="color-main fa fa-user"></i>
                        <a href="#">' . $job['company'] . '</a>
                        <i class="color-main fa fa-calendar"></i>
                        <a href="#">' . $job['date_posted'] . '</a>
                        <i class="color-main fa fa-map"></i>
                        <a href="#"
                            onclick="mapStaticMapPopUp(' ."'" . $job['lng'] . "', '" . $job['lat'] . "', '" . $job['location'] . "'" . ')">' .
                            $job['location'] . '</a>
                        <i class="color-main fa fa-money"></i>
                        <a href="#">' .  $job['salary'] . '</a>
                        <i class="color-main fa fa-tag"></i>
                        <a href="#">' . $job_category_name . '</a>
                        <!-- Display category here -->
                        <!-- Apply form based on status -->';


                        // Assuming $applyController is already instantiated
                        $status = $applyController->getApplyStatusFromPrfIdJobId($userId, $job['id']);
                        $current_apply_id = $applyController->getApplyIdByJobIdAndProfileId($job['id'], $userId);
                        $current_apply = $applyController->getApplyById($current_apply_id);

                        if ($user_profile_id != $job['jobs_profile']) { 
                            if ($status == "pending"): 
                                //<!-- Pending form -->
                                echo '<div class="text-end mx-4">
                                    <form id="pendingForm" action="./pendingJob.php" method="post">
                                        <input type="hidden" id="jobId" name="jobId"
                                            value="' . $job['id'] . '">
                                        <input type="hidden" id="userId" name="userId"
                                            value="' . $userId . '">
                                        <button type="submit" id="pendingButton"
                                            class="btn btn-outline-secondary">Pending</button>
                                    </form>
                                </div>';
                            elseif ($status == "HiredUp"): 
                                //<!-- HiredUp form -->
                                echo '<div class="text-end mx-4">
                                    <button type="submit" disabled id="hiredupButton"
                                        class="btn btn-outline-success">HiredUp</button>
                                </div>';
                            elseif ($status == "interview"):
                                //<!-- HiredUp form -->
                                echo '<div class="text-end mx-4">
                                    <button type="submit" disabled id="hiredupButton"
                                        class="btn btn-outline-success"
                                        onclick="togglePopup1()">Interview</button>
                                </div>';
                            else:
                                //<!-- Apply job form -->
                                echo '<div class="text-end mx-4">
                                    <button type="button" id="applyButton" class="btn btn-outline-info"
                                        onclick="togglePopup(' . "'" . $job['id'] . "', '" . $userId . "'" . ')">Apply</button>
                                </div>';

                            endif;
                        } else { 

                            echo '<div class="text-end mx-4">

                                <button type="submit" id="applyButton" class="btn btn-outline-info"
                                    onclick="window.location.href=' . "'career_explorers.php'" . '">Check
                                    Appliers</button>
                            </div>';

                        }
                    echo '</div>';

                   if ($status == "pending") { 
                        echo '<div>
                            <p class="mt-5">Chance of Success <a href="javascript:void(0)"
                                    onclick="show_success_data(' . "'" . $current_apply['apply_id'] . "'" . ')">View
                                    more</a></p>
                            <!-- progress bar -->';
                            $value = $resumeController->getApplyRank($current_apply['apply_id']);
                            echo '<progress id="progressBar" max="100"
                                value="' . $value . '"></progress>
                            <!-- end progress bar -->
                        </div>';
                    }
                echo '</div>
            </article>
            <br>';
        endforeach;
    }


}

?>
