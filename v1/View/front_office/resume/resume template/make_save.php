<?php

require_once __DIR__ . '/../../../../Controller/profileController.php';


if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$profileController = new ProfileC();

$user_id = '';
$current_profile_id = '';
$user_profile_id = '';

//get user_profile id
if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
    $profile = $profileController->getProfileById($user_profile_id);
}

//fetch subscription
$subs_type = array(
    "1-ADVANCED-SUBS" => "advanced",
    "1-BASIC-SUBS" => "basic",
    "1-PREMIUM-SUBS" => "premium",
    "else" => "limited"
);

$current_profile_sub = "";
if (array_key_exists($profile['profile_subscription'], $subs_type)) {
    // If it exists, return the corresponding value
    $current_profile_sub = $subs_type[$profile['profile_subscription']];
} else {
    // If not, return 'bb'
    $current_profile_sub = $subs_type['else'];
}

if ($current_profile_sub == "limited") {

    header("Location: ./../../profiles_management/subscription/subscriptionCards.php");
    exit;

}



$block_call_back = 'false';
$access_level = "else";
include ('./../../../../View/callback.php');

?>

<?php
include_once __DIR__ . './../../../../Controller/user_con.php';
require_once __DIR__ . '/../../../../Controller/profileController.php';

//var_dump($_FILES);

$userC = new userCon("user");
$profileController = new ProfileC();

$user_id = null;

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);

    $user_role = $userC->get_user_role_by_id($user_id);

    $user_banned = $userC->get_user_banned_by_id($user_id);

    // Get profile ID from the URL
    $profile_id = $profileController->getProfileIdByUserId($user_id);

    // Fetch profile data from the database
    $profile = $profileController->getProfileById($profile_id);

    // age calculation
    // The user's birthday as a string
    $birthday = $profile['profile_bday'];

    // Parse the birthday into a DateTime object
    $birthDate = new DateTime($birthday);

    // Get the current date
    $currentDate = new DateTime();

    // Calculate the difference between the current date and the birthday
    $age = $currentDate->diff($birthDate);

    // Extract the age in years
    $ageInYears = $age->y;

    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the hidden input field is set
    if (isset($_POST['resume_data'])) {
        // Get the JSON string from the hidden input field
        $jsonString = $_POST['resume_data'];

        // Decode the JSON string to a PHP array
        $myDict = json_decode($jsonString, true);

        // Check if json_decode() worked and the result is an array
        if (is_array($myDict)) {
            $myDict['age'] = $ageInYears;
            // Now you can access the values in the array
            $imageData = file_get_contents($_FILES['resume_picture']['tmp_name']);
            $base64Image = base64_encode($imageData);
            $myDict['profile_image'] = $base64Image;
           //var_dump($myDict);

           $data = $myDict;

        } else {
            // Handle the case where JSON decoding failed
            echo "Failed to decode JSON.";
        }
    } else {
        // Handle the case where the hidden input field is not set
        echo "No data received.";
    }
} else {
    // Handle the case where the request method is not POST
    echo "Invalid request method.";
}

if (isset($data)) {
    
$skills_div = generateSkillsHtml($data['skills']);
$experiences_div = generateTimelineHtml($data['experiences']);
$educations_div = generateEducationTimelineHtml($data['educations']);

}

function generateSkillsHtml($skills)
{
  $numSkills = count($skills);
  $skillsPerColumn = ceil($numSkills / 2);

  $html = '<div class="row">';

  $color = '';

  for ($i = 0; $i < $numSkills; $i++) {
    if ($i % $skillsPerColumn == 0) {
      if ($i > 0) {
        $html .= '</div>'; // Close previous column div
      }
      $html .= '<div class="col-md-6">';
      //$color = 'bg-success';
      $color = $color != '' ? 'bg-success' : 'bg-primary';
    }

    $skill = $skills[$i];
    $name = $skill['name'];
    $progress = $skill['progress'];
    $delay = ($i + 1) * 100;
    //$colorClass = $progress >= 80 ? 'bg-success' : 'bg-primary';
    $colorClass = $color;

    $html .= <<<EOD
              <div class="mb-2"><span>{$name}</span>
                <div class="progress my-1">
                  <div class="progress-bar {$colorClass}" role="progressbar" data-aos="zoom-in-right" data-aos-delay="{$delay}"
                    data-aos-anchor=".skills-section" style="width: {$progress}%" aria-valuenow="{$progress}" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div>
              </div>
EOD;
  }

  $html .= '</div>'; // Close last column div
  $html .= '</div>'; // Close row div

  return $html;
}

function generateTimelineHtml($experiences)
{
  $html = '<div class="timeline">';

  foreach ($experiences as $experience) {
    $title = $experience['job_exp'];
    $company = $experience['company'];
    $duration = $experience['start_date'] . ' - ' . $experience['end_date'];
    $description = $experience['description'];

    $html .= <<<EOD
          <div class="timeline-card timeline-card-primary card shadow-sm">
            <div class="card-body">
              <div class="h5 mb-1">{$title} <span class="text-muted h6">at {$company}</span></div>
              <div class="text-muted text-small mb-2">{$duration}</div>
              <div>{$description}</div>
            </div>
          </div>
EOD;
  }

  $html .= '</div>'; // Close timeline div

  return $html;
}

function generateEducationTimelineHtml($educations)
{
  $html = '<div class="timeline">';

  foreach ($educations as $education) {
    $degree = $education['degree'];
    $institution = $education['inst'];
    $duration = $education['start_date'] . ' - ' . $education['end_date'];
    $description = $education['description'];

    $html .= <<<EOD
          <div class="timeline-card timeline-card-success card shadow-sm">
            <div class="card-body">
              <div class="h5 mb-1">{$degree} <span class="text-muted h6">from {$institution}</span></div>
              <div class="text-muted text-small mb-2">{$duration}</div>
              <div>{$description}</div>
            </div>
          </div>
EOD;
  }

  $html .= '</div>'; // Close timeline div

  return $html;
}

$currentURL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];




?>


<!DOCTYPE html>
<html lang="en-US">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ResumeUp</title>
  <link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="crossorigin" />
  <link rel="preload" as="style"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap"
    media="print" onload="this.media='all'" />
  <noscript>
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap" />
  </noscript>
  <link href="css/font-awesome/css/all.min.css?ver=1.2.0" rel="stylesheet">
  <link href="css/bootstrap.min.css?ver=1.2.0" rel="stylesheet">
  <link href="css/aos.css?ver=1.2.0" rel="stylesheet">
  <link href="css/main.css?ver=1.2.0" rel="stylesheet">
  <noscript>
    <style type="text/css">
      [data-aos] {
        opacity: 1 !important;
        transform: translate(0) scale(1) !important;
      }
    </style>
  </noscript>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

</head>

<body id="top">
  <header class="d-print-none">
    <div class="container text-center text-lg-left">
      <div class="py-3 clearfix">

      </div>
    </div>
  </header>
  <div class="page-content">
    <div class="container">
      <div class="cover shadow-lg bg-white">
        <div class="cover-bg p-3 p-lg-4 text-white">
          <div class="row">
            <div class="col-lg-4 col-md-5">
              <div class="avatar hover-effect bg-white shadow-sm p-1"><img
                  src="data:image/jpeg;base64,<?php echo $data['profile_image']; ?>" width="200" height="200" /></div>
            </div>
            <div class="col-lg-8 col-md-7 text-center text-md-start">
              <h2 class="h1 mt-2" data-aos="fade-left" data-aos-delay="0"><?= $data['first_name'] ?>
                <?= $data['last_name'] ?>
              </h2>
              <p data-aos="fade-left" data-aos-delay="100"><?= $data['title'] ?></p>
            </div>
          </div>
        </div>
        <div class="about-section pt-4 px-3 px-lg-4 mt-1">
          <div class="row">
            <div class="col-md-6">
              <h2 class="h3 mb-3">About Me</h2>
              <p><?= $data['about_me'] ?></p>
            </div>
            <div class="col-md-5 offset-md-1">
              <div class="row mt-2">
                <div class="col-sm-4">
                  <div class="pb-1">Age</div>
                </div>
                <div class="col-sm-8">
                  <div class="pb-1 text-secondary"><?= $data['age'] ?></div>
                </div>
                <div class="col-sm-4">
                  <div class="pb-1">Email</div>
                </div>
                <div class="col-sm-8">
                  <div class="pb-1 text-secondary"><?= $data['email'] ?></div>
                </div>
                <div class="col-sm-4">
                  <div class="pb-1">Phone</div>
                </div>
                <div class="col-sm-8">
                  <div class="pb-1 text-secondary"><?= $data['phone'] ?></div>
                </div>
                <div class="col-sm-4">
                  <div class="pb-1">Address</div>
                </div>
                <div class="col-sm-8">
                  <div class="pb-1 text-secondary"><?= $data['address'] ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if (count($data['skills']) > 0) { ?>
        <hr class="d-print-none" />
        <div class="skills-section px-3 px-lg-4">
          <h2 class="h3 mb-3">Professional Skills</h2>
          <!-- [SKILLS] -->
          <?= $skills_div; ?>
        </div>
        <?php } ?>
        <?php if (count($data['experiences']) > 0) { ?>
        <hr class="d-print-none" />
        <div class="work-experience-section px-3 px-lg-4">
          <h2 class="h3 mb-4">Work Experience</h2>
          <!-- [EXPERIENCES] -->
          <?= $experiences_div; ?>
        </div>
        <?php } ?>
        <?php if (count($data['educations']) > 0) { ?>
        <hr class="d-print-none" />
        <div class="page-break"></div>
        <div class="education-section px-3 px-lg-4 pb-4">
          <h2 class="h3 mb-4">Education</h2>
          <!-- [EDUCATION] -->
          <?= $educations_div; ?>
        </div>
        <?php } ?>
        <hr class="d-print-none">
        <div class="page-break"></div>
      </div>
    </div>
  </div>
  <footer class="pt-4 pb-4 text-muted text-center d-print-none">
    <div class="container">
      <div class="text-small">
        <div class="mb-1">&copy; HireUp Resume. All rights reserved.</div>
      </div>
    </div>
  </footer>

  <script src="scripts/bootstrap.bundle.min.js?ver=1.2.0"></script>
  <script src="scripts/aos.js?ver=1.2.0"></script>
  <script src="scripts/main.js?ver=1.2.0"></script>

</body>

</html>

