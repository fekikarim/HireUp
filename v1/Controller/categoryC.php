<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/category_interests_con.php';

class categoryController
{
    private $conn;

    public function __construct()
    {
        $this->conn = config::getConnexion(); // Get PDO connection
    }
    // Create a new category
    public function createCategory($category_id, $name_category, $description)
    {
        try {
            // Check if the category name already exists
            $stmt = $this->conn->prepare("SELECT id_category FROM category WHERE name_category = ?");
            $stmt->execute([$name_category]);
            $existingCategory = $stmt->fetch();

            if ($existingCategory) {
                return "Category name already exists."; // Return a message indicating the category name already exists
            }

            // Insert the new category
            $stmt = $this->conn->prepare("INSERT INTO category (id_category, name_category, description_category) VALUES (?, ?, ?)");
            $stmt->execute([$category_id, $name_category, $description]);
            return "New category created successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Read all jobs
    public function getAllJobs()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM category ORDER BY name_category");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getAllCategories()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM category");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }




    public function updateCategory($category_id, $name_category, $description)
    {
        try {
            // Check if the category name already exists (excluding the current category being updated)
            $stmt = $this->conn->prepare("SELECT id_category FROM category WHERE name_category = ? AND id_category != ?");
            $stmt->execute([$name_category, $category_id]);
            $existingCategory = $stmt->fetch();

            if ($existingCategory) {

                return "Category name already exists."; // Return a message indicating the category name already exists
            }

            // Update the category
            $stmt = $this->conn->prepare("UPDATE category SET name_category = ?, description_category = ? WHERE id_category = ?");
            $stmt->execute([$name_category, $description, $category_id]);
            return "Category updated successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Delete a job
    public function deletecategory($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM category WHERE id_category=?");
            $stmt->execute([$id]);
            return "Job deleted successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Get all jobs
    public function getCategory()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM category");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getCategoryById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM category WHERE id_category = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
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


    public function categoryExists($id)
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


    public function generateCategoryId($id_length)
    {
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->categoryExists($current_id));

        return $current_id;
    }

    public function choose_random_numbers($my_nb) {
        $max_limit = $my_nb-1;
        $min_limit = 0;
        
        if ($my_nb > 6) {
            $count = 6;
        } elseif ($my_nb < 6 && $my_nb >= 4) {
            $count = 4;
        } elseif ($my_nb < 4 && $my_nb >= 3) {
            $count = 3;
        } else {
            $count = $my_nb;
        }
    
        $numbers = range($min_limit, $max_limit);
        shuffle($numbers);
        
        return array_slice($numbers, 0, $count);
    }


    public function GenerateCategoryIntrestedSection($profile_id)
    {
        $catInterestsCon = new CategoryInterestController();

        $categories_prime = $this->getAllCategories();

        $categories = array();
        foreach ($categories_prime as $category) {
            
            $current_intrest = $catInterestsCon->getInterestByCategoryAndProfile($category['id_category'], $profile_id);
            if ($current_intrest == false) {
                $categories[] = $category;
            }
        }
        $categories_nb = count($categories);

        $random_numbers = $this->choose_random_numbers($categories_nb);

        if (count($random_numbers) > 0) {
        
            echo '<section class="ls s-py-lg-50 main_blog">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">';
            for ($i=0; $i<count($random_numbers); $i++) {
                $name = $categories[$random_numbers[$i]]['name_category'];
                echo                '<article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                                        <div class="item-content" style="min-height: 280px !important;">
                                            <header class="blog-header ">
                                                <a href="javascript:void(0)" rel="bookmark">
                                                    <h4>' . $name . '</h4>
                                                </a>
                                            </header>
                                            <div class="blog-item-icons" id="blog-item-icons-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                <div class="col-sm-4 pr-5" onclick="like_category(\'' . $categories[$random_numbers[$i]]['id_category'] . '\', \'' . $profile_id . '\')">
                                                    <a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                        <i class="fa-solid fa-heart Interested-btns-like" id="like-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Interested
                                                    </a>
                                                </div>
                                                <div class="col-sm-4 pr-5" onclick="dislike_category(\'' . $categories[$random_numbers[$i]]['id_category'] . '\', \'' . $profile_id . '\')">
                                                    <a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                        <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Skip
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>';
            }


            echo                '</div>
                            </div>
                        </div>
                    </div>
                </section>';
        
        }


    }

    public function getCategoriesThatUserIntrestedIn($profile_id, $shuffle = false) {
        $catInterestsCon = new CategoryInterestController();

        $disired_cat = array();
        $not_disired_cat = array();

        $all_cats_intrest = $catInterestsCon->getInterestByProfileId($profile_id);

        $categories_name = array();
        $categories_not_intrested_in_names = array();
        if ($all_cats_intrest != false) {
            foreach ($all_cats_intrest as $cats_intrest) {
                $current_cat_id = $cats_intrest['category_id'];
                $current_cat = $this->getCategoryById($current_cat_id);
                $name = $current_cat['name_category'];
                if ($cats_intrest['state'] == 'liked') {
                    $categories_name[] = $name;
                } else if ($cats_intrest['state'] == 'disliked') {
                    $categories_not_intrested_in_names[] = $name;
                }
            }
            // shuflle the array if needed
            if ($shuffle == true) {
                shuffle($categories_name);
            }
            $disired_cat = $categories_name;
            $not_disired_cat = $categories_not_intrested_in_names;
        }

        $categories_prime = $this->getAllCategories();

        // shuflle the array if needed
        if ($shuffle == true) {
            shuffle($categories_prime);
        }

        if (count($categories_prime) == 0 || $categories_prime == false) {
            //do nothing
        } else {
            foreach ($categories_prime as $cats_prime) {
                $current_cat_id = $cats_prime['id_category'];
                $current_cat = $this->getCategoryById($current_cat_id);
                $name = $current_cat['name_category'];
                if ( !in_array($name, $categories_name) && !in_array($name, $categories_not_intrested_in_names) ) {
                    $categories_name[] = $name;
                }
            }
        }

        // shuflle the array if needed
        if ($shuffle == true) {
            shuffle($categories_not_intrested_in_names);
        }
        if ($categories_not_intrested_in_names != false) {
        
            foreach ($categories_not_intrested_in_names as $cats_not_intrest_name) {
                $categories_name[] = $cats_not_intrest_name;
            }
        }

        //return $categories_name;


        $dictionary = array(
            'table' => $categories_name,
            'disired_cat' => $disired_cat,
            'not_disired_cat' => $not_disired_cat
        );

        return $dictionary;

    }


    public function GenerateCategoryAlreadyIntrestedOrNotSection($profile_id, $liked=null)
    {
        $catInterestsCon = new CategoryInterestController();

        $categories_prime = $this->getAllCategories();

        $categories = array();
        foreach ($categories_prime as $category) {
            
            $current_intrest = $catInterestsCon->getInterestByCategoryAndProfile($category['id_category'], $profile_id);
            if ($current_intrest != false) {
                $categories[] = $category;
            }
        }
        $categories_nb = count($categories);

        $random_numbers = $this->choose_random_numbers($categories_nb);

        if (count($random_numbers) > 0) {
        
            echo '<section class="ls s-py-lg-50 main_blog">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">';

                            if ($liked == 'true') {
                                echo '<div class="contact-header text-center">
								<h5>
                                    Interests 
								</h5>
								<h4>
                                    That Excite You
								</h4>
							</div>
                            <div class="d-none d-lg-block divider-20"></div>';
                            } else if ($liked == 'false') {
                                echo '<div class="contact-header text-center">
								<h5>
                                    Not Your 
								</h5>
								<h4>
                                    Cup of Tea
								</h4>
							</div>
                            <div class="d-none d-lg-block divider-20"></div>';
                            }

                            echo  '<div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">';
            for ($i=0; $i<count($random_numbers); $i++) {
                $name = $categories[$random_numbers[$i]]['name_category'];
                if ($catInterestsCon->interestExistsByCategoryAndProfile($categories[$random_numbers[$i]]['id_category'], $profile_id)){
                    $state = $catInterestsCon->getInterestByCategoryAndProfile($categories[$random_numbers[$i]]['id_category'], $profile_id)['state'];
                    if ( ($liked == 'true' && $state ==='liked') || ($liked === 'false' && $state == 'disliked') || ($liked == null) ){
                
                        echo                '<article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                                                <div class="item-content" style="min-height: 280px !important;">
                                                    <header class="blog-header ">
                                                        <a href="javascript:void(0)" rel="bookmark">
                                                            <h4>' . $name . '</h4>
                                                        </a>
                                                    </header>
                                                    <div class="blog-item-icons" id="blog-item-icons-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                        <div class="col-sm-4 pr-5" onclick="like_category(\'' . $categories[$random_numbers[$i]]['id_category'] . '\', \'' . $profile_id . '\')">';
                                                    if ($state == 'liked') {
                                                    echo        '<a href="javascript:void(0)" class="Interested-btns-like-active" id="like-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                                    <i class="fa-solid fa-heart Interested-btns-like-active" id="like-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Interested
                                                                </a>';
                                                    } else {
                                                        echo        '<a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                                        <i class="fa-solid fa-heart Interested-btns-like" id="like-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Interested       
                                                                    </a>';
                                                    }
                                                        echo '</div>
                                                        <div class="col-sm-4 pr-5" onclick="dislike_category(\'' . $categories[$random_numbers[$i]]['id_category'] . '\', \'' . $profile_id . '\')">';
                                                    if ($state == 'disliked') {
                                                    echo '<a href="javascript:void(0)" class="Interested-btns-dislike-active" id="dislike-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                                <i class="fa-solid fa-circle-xmark Interested-btns-dislike-active" id="dislike-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Skip
                                                            </a>';
                                                    } else {
                                                        echo '<a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                                <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Skip
                                                            </a>';
                                                    }
                                                    
                                                    echo '</div>
                                                    </div>
                                                </div>
                                            </article>';
                    }
                }
            }


            echo                '</div>
                            </div>
                        </div>
                    </div>
                </section>';
        
        }


    }

    public function GenerateCategoryIntrestedSuggestionsSection($profile_id)
    {
        $catInterestsCon = new CategoryInterestController();

        $categories_prime = $this->getAllCategories();

        $categories = array();
        foreach ($categories_prime as $category) {
            
            $current_intrest = $catInterestsCon->getInterestByCategoryAndProfile($category['id_category'], $profile_id);
            if ($current_intrest == false) {
                $categories[] = $category;
            }
        }
        $categories_nb = count($categories);

        $random_numbers = $this->choose_random_numbers($categories_nb);

        if (count($random_numbers) > 0) {
        
            echo '<section class="ls s-py-lg-50 main_blog">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="contact-header text-center">
                                    <h5>
                                        Things 
                                    </h5>
                                    <h4>
                                        You Might Love
                                    </h4>
                                </div>
                                <div class="d-none d-lg-block divider-20"></div>
                                <div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">';
            for ($i=0; $i<count($random_numbers); $i++) {
                $name = $categories[$random_numbers[$i]]['name_category'];
                echo                '<article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                                        <div class="item-content" style="min-height: 280px !important;">
                                            <header class="blog-header ">
                                                <a href="javascript:void(0)" rel="bookmark">
                                                    <h4>' . $name . '</h4>
                                                </a>
                                            </header>
                                            <div class="blog-item-icons" id="blog-item-icons-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                <div class="col-sm-4 pr-5" onclick="like_category(\'' . $categories[$random_numbers[$i]]['id_category'] . '\', \'' . $profile_id . '\')">
                                                    <a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                        <i class="fa-solid fa-heart Interested-btns-like" id="like-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Interested
                                                    </a>
                                                </div>
                                                <div class="col-sm-4 pr-5" onclick="dislike_category(\'' . $categories[$random_numbers[$i]]['id_category'] . '\', \'' . $profile_id . '\')">
                                                    <a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '">
                                                        <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-'. $categories[$random_numbers[$i]]['id_category']. '"></i> Skip
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </article>';
            }


            echo                '</div>
                            </div>
                        </div>
                    </div>
                </section>';
        
        }


    }

    
    
}


?>