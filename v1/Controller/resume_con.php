<?php


require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/wanted_skill_con.php';
require_once __DIR__ . '/categoryC.php';
require_once __DIR__ . '/JobC.php';
require_once __DIR__ . '/applyController.php';

require_once __DIR__ . '/vendor/autoload.php';

class ResumeController
{
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

    public function resumeExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM resumes WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function generateResumeId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->resumeExists($current_id, $db));

        return $current_id;
    }

    public function listResumes()
    {
        $sql = "SELECT * FROM resumes";

        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addResume($resume)
    {
        $sql = "INSERT INTO resumes(id, uploaded_by, apply_id, content, uploaded_at, json_data) 
                VALUES (:id, :uploaded_by, :apply_id, :content, :uploaded_at, :json_data)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $resume->getId(),
                'uploaded_by' => $resume->getUploadedBy(),
                'apply_id' => $resume->getApplyId(),
                'content' => $resume->getContent(),
                'uploaded_at' => $resume->getUploadedAt(),
                'json_data' => json_encode($resume->getJsonData()), // Convert to JSON format
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function addResumeNoJson($resume)
    {
        $sql = "INSERT INTO resumes(id, uploaded_by, apply_id, content, uploaded_at) 
                VALUES (:id, :uploaded_by, :apply_id, :content, :uploaded_at)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $resume->getId(),
                'uploaded_by' => $resume->getUploadedBy(),
                'apply_id' => $resume->getApplyId(),
                'content' => $resume->getContent(),
                'uploaded_at' => $resume->getUploadedAt(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateResume($resume, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE resumes 
                                   SET uploaded_by = :uploaded_by, apply_id = :apply_id, 
                                       content = :content, uploaded_at = :uploaded_at, 
                                       json_data = :json_data 
                                   WHERE id = :id");
            $query->execute([
                'id' => $id,
                'uploaded_by' => $resume->getUploadedBy(),
                'apply_id' => $resume->getApplyId(),
                'content' => $resume->getContent(),
                'uploaded_at' => $resume->getUploadedAt(),
                'json_data' => $resume->getJsonData()
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteResume($id)
    {
        $sql = "DELETE FROM resumes WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getResume($id)
    {
        $sql = "SELECT * FROM resumes WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $resume = $query->fetch();
            return $resume;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getResumeByapplyId($id)
    {
        $sql = "SELECT * FROM resumes WHERE apply_id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $resume = $query->fetch();
            return $resume;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function makeResumeJsonDataByResumeId($resume_id, $resume_file_name = null)
    {
        if ($resume_file_name == null) {
            $resume_file_name = "temp_resume.pdf";
        }

        $resume_data = $this->getResume($resume_id);
        $resume_pdf_data = $resume_data['content'];

        // Save the PDF data to a file (temporarily)
        $file = fopen($resume_file_name, "w"); // Open file for writing
        fwrite($file, $resume_pdf_data); // Write the PDF data to the file
        fclose($file); // Close the file

        // Get the PDF JSON data
        $param1 = escapeshellarg($resume_file_name);
        $output = exec("python " . __DIR__ . "/py_script/make_json_data.py $param1");

        if ($output != "done") {
            return null;
        } else {
            $jsonData = file_get_contents("output.json");
            $arrayData = json_decode($jsonData, true);

            return $arrayData;
        }

    }

    public function makeResumeJsonDataByData($resume_data, $resume_file_name = null)
    {
        if ($resume_file_name == null) {
            $resume_file_name = "temp_resume.pdf";
        }

        $resume_pdf_data = $resume_data;

        // Save the PDF data to a file (temporarily)
        $file = fopen(__DIR__ . "/py_script/" . $resume_file_name, "w"); // Open file for writing
        fwrite($file, $resume_pdf_data); // Write the PDF data to the file
        fclose($file); // Close the file

        // Get the PDF JSON data
        $param1 = escapeshellarg($resume_file_name);
        $output = exec("python " . __DIR__ . "/py_script/make_json_data.py $param1");

        if ($output != "done") {
            echo "Error from python: " . $output;

            // delete the temp resume file if it exists
            $fileToDelete = __DIR__ . "/py_script/" . $resume_file_name;
            $fileToDelete2 = __DIR__ . "/py_script/" . "output.json";

            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }

            if (file_exists($fileToDelete2)) {
                unlink($fileToDelete2);
            }

            return null;
        } else {
            $jsonData = file_get_contents(__DIR__ . "/py_script/" . "output.json");
            $arrayData = json_decode($jsonData, true);

            // delete the temp resume file if it exists
            $fileToDelete = __DIR__ . "/py_script/" . $resume_file_name;
            $fileToDelete2 = __DIR__ . "/py_script/" . "output.json";

            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }

            if (file_exists($fileToDelete2)) {
                unlink($fileToDelete2);
            }

            return $arrayData;
        }

    }

    public function getResumesJsonAsArray($json_data)
    {
        return json_decode($json_data, true);
    }

    public function getResumesJsonAsArrayByResumeId($resume_id)
    {
        $resume_data = $this->getResume($resume_id);
        $resume_json_data = $resume_data['json_data'];
        return $this->getResumesJsonAsArray($resume_json_data);
    }

    public function findMatchingSkills($category_skills, $json_skills)
    {
        $matching_skills = [];
        foreach ($category_skills as $skill_info) {
            $skill = strtolower($skill_info["skill"]);
            foreach ($json_skills as $json_skill) {
                if (stripos(strtolower($json_skill), $skill) !== false) {
                    $matching_skills[] = $skill;
                }
            }
        }
        return $matching_skills;
    }

    public function getResumesSkillsRankingByCategory($json_array, $category_id)
    {
        $wantedSkillsCon = new WantedSkillController();
        $categoryC = new categoryController();

        $category = $categoryC->getCategoryById($category_id);

        $category_name = $category['name_category'];

        $category_skills = $wantedSkillsCon->getCetagorySkills($category_id);

        //all the skills name in the category
        $category_skill_names = array_map(function ($skill_info) {
            return strtolower($skill_info['skill']);
        }, $category_skills);


        $json_skills = $json_array['skills'];

        $cat_skill_ranking = array();

        $cat_skill_ranking['category_id'] = $category_id;
        $cat_skill_ranking['category_name'] = $category_name;
        $cat_skill_ranking['skills_found'] = $this->findMatchingSkills($category_skills, $json_skills);
        $cat_skill_ranking['skills_not_found'] = array_diff($category_skill_names, $cat_skill_ranking['skills_found']);
        $cat_skill_ranking['nb_of_skills_found'] = count($cat_skill_ranking['skills_found']);
        $cat_skill_ranking['nb_of_all_skills_needed'] = count($category_skills);

        return $cat_skill_ranking;


    }

    public function getResumesSkillsRankingByAllCategory($json_array)
    {
        $wantedSkillsCon = new WantedSkillController();
        $categoryC = new categoryController();

        $categories = $categoryC->getAllCategories();

        $ranking = array();
        foreach ($categories as $category) {
            $category_id = $category['id_category'];

            $category_rank_data = $this->getResumesSkillsRankingByCategory($json_array, $category_id);

            $ranking[] = $category_rank_data;

        }

        return $ranking;

    }

    public function getResumesSkillsRankingByCategoryValue($json_array, $category_id)
    {
        $data = $this->getResumesSkillsRankingByCategory($json_array, $category_id);
        //var_dump($data);

        //calculate the value of the ranking
        $skills_found = $data['nb_of_skills_found'];
        $total_skills = $data['nb_of_all_skills_needed'];

        // Calculate the percentage
        $percentage = ($skills_found / $total_skills) * 100;

        // Round the percentage to two decimal places
        $percentage = round($percentage, 2);

        if ($percentage > 100) {
            $percentage = 100;
        }

        return $percentage;
    }

    public function getApplyRank($apply_id)
    {
        $jobC = new JobController();
        $ApplyC = new ApplyController();

        $apply_data = $ApplyC->getApplyById($apply_id);
        $resume = $this->getResumeByapplyId($apply_data['apply_id']);
        $apply_job_id = $apply_data['apply_job_id'];
        $job = $jobC->getJobById($apply_job_id);
        $job_category_id = $job['id_category'];
        $resume_json_data = $resume['json_data'];
        $resume_json = $this->getResumesJsonAsArray($resume_json_data);

        $rank = $this->getResumesSkillsRankingByCategoryValue($resume_json, $job_category_id);

        return $rank;

    }

    public function getApplyRankInfos($apply_id)
    {
        $jobC = new JobController();
        $ApplyC = new ApplyController();

        $apply_data = $ApplyC->getApplyById($apply_id);
        $resume = $this->getResumeByapplyId($apply_data['apply_id']);
        $apply_job_id = $apply_data['apply_job_id'];
        $job = $jobC->getJobById($apply_job_id);
        $job_category_id = $job['id_category'];
        $resume_json_data = $resume['json_data'];
        $resume_json = $this->getResumesJsonAsArray($resume_json_data);

        $rank_infos = $this->getResumesSkillsRankingByCategory($resume_json, $job_category_id);

        return $rank_infos;

    }

    public function sortResumesByRank($resumes)
    {
        // Custom sorting function based on "rank" attribute
        usort($resumes, function ($a, $b) {
            return $b['rank'] - $a['rank'];
        });

        return $resumes;
    }

    


}







?>