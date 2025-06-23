<?php

require_once __DIR__ . '/../../../Controller/category_interests_con.php';
require_once __DIR__ . '/../../../model/category_interests.php';

$catInterestsCon = new CategoryInterestController();

// Retrieve the category_id and profile_id from the POST request
$category_id = $_POST['category_id'];
$profile_id = $_POST['profile_id'];

// Check if the user has already liked the category
$cat_intrs = $catInterestsCon->getInterestByCategoryAndProfile($category_id, $profile_id);

if ($cat_intrs == false) {
   
    $cat_intrest = new CategoryInterest(
        $catInterestsCon->generateInterestId(5),
        $category_id,
        $profile_id,
        'liked'
    );

    $catInterestsCon->addInterest($cat_intrest);
    echo 'interest added successfully';

} else {
    if ($cat_intrs['state'] == 'liked') {
        $catInterestsCon->deleteInterest($cat_intrs['id']);
        echo 'interest deleted successfully';
    } else {
        $cat_intrs_id = $cat_intrs['id'];

        $cat_intrest = new CategoryInterest(
            $cat_intrs_id,
            $cat_intrs['category_id'],
            $cat_intrs['profile_id'],
            'liked'
        );
        $catInterestsCon->updateInterest($cat_intrest, $cat_intrs['id']);
        echo 'interest updated successfully';
    }
}

?>
