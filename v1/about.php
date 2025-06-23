<!DOCTYPE html>
<html class="no-js">

<head>
    <title>About HireUp</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="./front office assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./front office assets/css/animations.css">
    <link rel="stylesheet" href="./front office assets/css/font-awesome.css">
    <link rel="stylesheet" href="./front office assets/css/main.css" class="color-switcher-link">
    <script src="./front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="./front office assets/images/HireUp_icon.ico" rel="icon" />

    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>


    <style>
        .button-container {
            display: flex;
        }

        .primary-button {
            background-color: #40A2D8;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .transparent-button {
            background-color: transparent;
            color: black;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>

</head>

<?php

include_once __DIR__ . './Controller/user_con.php';
require_once __DIR__ . '/Controller/profileController.php';
require_once __DIR__ . '/Controller/JobC.php';

$userC = new userCon("user");
$profileController = new ProfileC();
$jobC = new JobController();

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
}


$users_list = $userC->listUsers();
$usersArray = $users_list->fetchAll(PDO::FETCH_ASSOC);
$users_nb = count($usersArray);

$jobsArray = $jobC->getAllJobs();
$jobs_nb = count($jobsArray);


?>

<body>

    <?php
    $block_call_back = 'true';
    $access_level = "none";
    include ('./View/callback.php')
        ?>

    <div class="preloader">
        <div class="preloader_image"></div>
    </div>

    <!-- search modal -->
    <div class="modal" tabindex="-1" role="dialog" aria-labelledby="search_modal" id="search_modal">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="widget widget_search">
            <form method="get" class="searchform search-form" action="http://webdesign-finder.com/">
                <div class="form-group">
                    <input type="text" value="" name="search" class="form-control" placeholder="Search keyword"
                        id="modal-search-input">
                </div>
                <button type="submit" class="btn">Search</button>
            </form>
        </div>
    </div>



    <!-- wrappers for visual page editor and boxed version of template -->
    <div id="canvas">
        <div id="box_wrapper">


            <?php
            $active_page = "about";
            include ('./View/front_office/front_header.php');
            ?>

            <section class="page_title cs s-py-25" style="background-color: #395B64 !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>
            </section>

            <section class="page_title cs s-py-25" style="background-color: #395B64 !important;">
                <div class="container">
                    <div class="row">

                        <div class="divider-50"></div>

                        <div class="col-md-12 text-center">
                            <h1 class="">About</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="./index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">
                                    About
                                </li>
                            </ol>
                        </div>

                        <div class="divider-50"></div>

                    </div>
                </div>
            </section>


            <section class="ls about about-padge s-pt-30">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-lg-6 animate" data-animation="slideInLeft">
                            <div class="heading-about">
                                <h2>
                                    HU
                                </h2>
                                <h4>
                                    Welcome to
                                </h4>
                                <h3>
                                    HireUp!
                                </h3>
                                <p>
                                    At HireUp, we're more than just a recruitment and employment platform. We're a
                                    catalyst for career growth and success. Our mission is to empower individuals to
                                    realize their full potential and achieve their professional aspirations. With a
                                    focus on innovation, efficiency, and excellence, we're revolutionizing the way
                                    people connect with opportunities and build meaningful careers.
                                </p>
                            </div>
                            <div class="icons-list">
                                <ul class="list-bordered">
                                    <li class="media media-body">
                                        <i class="teaser-icon fa fa-briefcase"></i>
                                        <h4 class="title">
                                            <span><?php echo $jobs_nb; ?></span>
                                            <?php echo ($jobs_nb == 1) ? "Job Listed" : "Jobs Listed"; ?>
                                        </h4>
                                    </li>
                                    <li class="border-bottom-0 media media-body">
                                        <i class="teaser-icon fa fa-user"></i>
                                        <h4 class="title">
                                            <span><?php echo $users_nb; ?></span>
                                            <?php echo ($users_nb == 1) ? "Active User" : "Active Users"; ?>
                                        </h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 animate" data-animation="slideInRight">
                            <img src="./front office assets/images/person01.jpg" alt="person01.jpg">
                        </div>
                    </div>
                </div>
            </section>


            <section class="icon-boxed teaser-box ls s-py-lg-130 c-my-lg-10 s-parallax">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 animate" data-animation="pullUp">
                            <div class="icon-box text-center hero-bg box-shadow">
                                <div class="teaser-icon icon-styled bg-maincolor3">
                                    <i class="fa fa-unlock-alt"></i>
                                </div>
                                <h3>
                                    <a href="#">Highly Secure</a>
                                </h3>
                                <p>
                                    HireUp prioritizes security to safeguard your information. With cloud-based
                                    services, we provide customers with single-tenant dedicated environments, ensuring
                                    the highest level of data protection.
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 animate" data-animation="pullUp">
                            <div class="icon-box text-center hero-bg box-shadow">
                                <div class="teaser-icon icon-styled bg-maincolor3">
                                    <i class="fa fa-cloud"></i>
                                </div>
                                <h3>
                                    <a href="#">True Cloud Scalability</a>
                                </h3>
                                <p>
                                    HireUp's infrastructure is designed to scale seamlessly, catering to the needs of
                                    customers making anywhere from 100 to 40,000 hires per annum. Our true cloud
                                    scalability ensures reliability and performance under any workload.
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 animate" data-animation="pullUp">
                            <div class="icon-box text-center hero-bg box-shadow">
                                <div class="teaser-icon icon-styled bg-maincolor3">
                                    <i class="fa fa-database"></i>
                                </div>
                                <h3>
                                    <a href="#">Accurate Data Management</a>
                                </h3>
                                <p>
                                    At HireUp, we understand the importance of accurate data. That's why we validate all
                                    customer data, ensuring integrity and reliability. Our meticulous data management
                                    practices result in accurate data banks for comprehensive reporting and analysis.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section class="ls collapse-section about">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="image_cover image_cover_left hireup-gif"
                            style="background-image: url('./front office assets/images/hireup.gif'); background-repeat: none; background-size: cover; width: 800px; height: 800px;">
                            <!-- Image or content here -->
                        </div>
                    </div>
                    <div class="col-lg-6 collapse-table">
                        <div class="contact-header collapse-header heading text-center pt-30">
                            <h5 class="mb-3">
                                Navigating
                            </h5>
                            <h4 class="mb-0">
                                Your Job
                            </h4>
                            <br>
                            <h4>
                                Offer Journey
                            </h4>
                        </div>



                        <div id="accordion01" role="tablist">
                            <div class="card-header" role="tab" id="collapse01_header">
                                <h5>
                                    <a data-toggle="collapse" href="#collapse01" aria-expanded="true"
                                        aria-controls="collapse01">
                                        Be Decisive
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse01" class="collapse show" role="tabpanel"
                                aria-labelledby="collapse01_header" data-parent="#accordion01">
                                <div class="card-body">
                                    When you receive a job offer through HireUp, prompt action is key. Confirming your
                                    acceptance promptly secures the job for you. Remember, there may be other candidates
                                    in consideration, so demonstrating commitment is crucial.
                                </div>
                            </div>
                            <div class="card-header" role="tab" id="collapse02_header">
                                <h5>
                                    <a class="collapsed" data-toggle="collapse" href="#collapse02" aria-expanded="false"
                                        aria-controls="collapse02">
                                        Or Take Your Time
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse02" class="collapse" role="tabpanel" aria-labelledby="collapse02_header"
                                data-parent="#accordion01">
                                <div class="card-body">
                                    While swift decision-making is encouraged, it's also acceptable to take the time
                                    needed to consider a job offer carefully. Remember, this is a significant career
                                    move, so ensure it aligns with your goals and aspirations.
                                </div>
                            </div>
                            <div class="card-header" role="tab" id="collapse03_header">
                                <h5>
                                    <a class="collapsed" data-toggle="collapse" href="#collapse03" aria-expanded="false"
                                        aria-controls="collapse03">
                                        Resigning Gracefully
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse03" class="collapse" role="tabpanel" aria-labelledby="collapse03_header"
                                data-parent="#accordion01">
                                <div class="card-body">
                                    If you decide to accept a job offer through HireUp, resigning from your current
                                    position is the next step. Handle this transition professionally and respectfully,
                                    maintaining positive relationships with your current employer and colleagues.
                                </div>
                            </div>
                            <div class="card-header" role="tab" id="collapse04_header">
                                <h5>
                                    <a class="collapsed" data-toggle="collapse" href="#collapse04" aria-expanded="false"
                                        aria-controls="collapse04">
                                        Handling Counter Offers
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse04" class="collapse" role="tabpanel" aria-labelledby="collapse04_header"
                                data-parent="#accordion01">
                                <div class="card-body">
                                    In some cases, your current employer may present a counter offer to retain you.
                                    Evaluate such offers carefully, considering not only financial incentives but also
                                    factors like career growth and job satisfaction. Ultimately, make the decision that
                                    aligns best with your long-term goals.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section class="ls s-pt-100 s-py-75 c-gutter-50 c-mb-50 services1">
                <div class="container px-30">
                    <div class="row">

                        <div class="d-none d-lg-block divider-80"></div>

                        <!-- Sustainable Development Goal 4: Quality Education -->
                        <!-- <div class="col-md-6 col-lg-4">
                            <div class="vertical-item text-center item-content">
                                <div class="item-media">
                                    <img src="./front office assets/images/odd/odd4.svg" alt="">
                                    <div class="media-links">
                                        <a class="link" title=""
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd4-veiller-a-ce-que-tous-puissent-suivre-une-education-de-qualite-dans-des?"></a>
                                    </div>
                                </div>
                                <div class="item-content box services">
                                    <h4>
                                        <a
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd4-veiller-a-ce-que-tous-puissent-suivre-une-education-de-qualite-dans-des?">Quality
                                            Education</a>
                                    </h4>
                                    <p>
                                        Sustainable Development Goal 4 aims to ensure inclusive and equitable quality
                                        education and promote lifelong learning opportunities for all. By supporting
                                        this goal, HireUp empowers individuals to enhance their skills and knowledge,
                                        fostering personal and professional growth.
                                    </p>
                                </div>
                            </div>
                        </div> -->

                        <!-- Sustainable Development Goal 5: Gender Equality -->
                        <div class="col-md-6 col-lg-4">
                            <div class="vertical-item text-center item-content">
                                <div class="item-media">
                                    <img src="./front office assets/images/odd/odd5.svg" alt="">
                                    <div class="media-links">
                                        <a class="link" title=""
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd5-realiser-l-egalite-des-sexes-et-autonomiser-toutes-les-femmes-et-les?"></a>
                                    </div>
                                </div>
                                <div class="item-content box services">
                                    <h4>
                                        <a
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd5-realiser-l-egalite-des-sexes-et-autonomiser-toutes-les-femmes-et-les?">Gender
                                            Equality</a>
                                    </h4>
                                    <p>
                                        Sustainable Development Goal 5 aims to achieve gender equality and empower all
                                        women and girls. By promoting gender inclusivity and equal opportunities, HireUp
                                        contributes to building a more diverse and equitable workforce, fostering
                                        innovation and creativity.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sustainable Development Goal 8: Decent Work and Economic Growth -->
                        <div class="col-md-6 col-lg-4">
                            <div class="vertical-item text-center item-content">
                                <div class="item-media">
                                    <img src="./front office assets/images/odd/odd8.svg" alt="">
                                    <div class="media-links">
                                        <a class="link" title=""
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd8-promouvoir-une-croissance-economique-soutenue-partagee-et-durable-le-plein?"></a>
                                    </div>
                                </div>
                                <div class="item-content box services">
                                    <h4>
                                        <a
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd8-promouvoir-une-croissance-economique-soutenue-partagee-et-durable-le-plein?">Decent
                                            Work and Economic Growth</a>
                                    </h4>
                                    <p>
                                        Sustainable Development Goal 8 aims to promote sustained, inclusive, and
                                        sustainable economic growth, full and productive employment, and decent work for
                                        all. By connecting job seekers with meaningful employment opportunities, HireUp
                                        supports economic development and poverty reduction.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sustainable Development Goal 9: Industry, Innovation, and Infrastructure -->
                        <!-- <div class="col-md-6 col-lg-4">
                            <div class="vertical-item text-center item-content">
                                <div class="item-media">
                                    <img src="./front office assets/images/odd/odd9.svg" alt="">
                                    <div class="media-links">
                                        <a class="link" title=""
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd9-mettre-en-place-une-infrastructure-resiliente-promouvoir-une?"></a>
                                    </div>
                                </div>
                                <div class="item-content box services">
                                    <h4>
                                        <a
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd9-mettre-en-place-une-infrastructure-resiliente-promouvoir-une?">Industry,
                                            Innovation, and Infrastructure</a>
                                    </h4>
                                    <p>
                                        Sustainable Development Goal 9 aims to build resilient infrastructure, promote
                                        inclusive and sustainable industrialization, and foster innovation. By
                                        facilitating access to job opportunities and training programs, HireUp
                                        contributes to creating a skilled workforce and driving technological
                                        advancements.
                                    </p>
                                </div>
                            </div>
                        </div> -->

                        <!-- Sustainable Development Goal 10: Reduced Inequality -->
                        <div class="col-md-6 col-lg-4">
                            <div class="vertical-item text-center item-content">
                                <div class="item-media">
                                    <img src="./front office assets/images/odd/odd10.svg" alt="">
                                    <div class="media-links">
                                        <a class="link" title=""
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd10-reduire-les-inegalites-entre-les-pays-et-en-leur-sein?"></a>
                                    </div>
                                </div>
                                <div class="item-content box services">
                                    <h4>
                                        <a
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd10-reduire-les-inegalites-entre-les-pays-et-en-leur-sein?">Reduced
                                            Inequality</a>
                                    </h4>
                                    <p>
                                        Sustainable Development Goal 10 aims to reduce inequality within and among
                                        countries. By promoting equal access to employment opportunities and fostering
                                        diversity and inclusion, HireUp works towards creating a more equitable society
                                        where everyone has the chance to succeed.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sustainable Development Goal 17: Partnerships for the Goals -->
                        <!-- <div class="col-md-6 col-lg-4">
                            <div class="vertical-item text-center item-content">
                                <div class="item-media">
                                    <img src="./front office assets/images/odd/odd17.svg" alt="">
                                    <div class="media-links">
                                        <a class="link" title=""
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd17-partenariats-pour-la-realisation-des-objectifs?"></a>
                                    </div>
                                </div>
                                <div class="item-content box services">
                                    <h4>
                                        <a
                                            href="https://www.agenda-2030.fr/17-objectifs-de-developpement-durable/article/odd17-partenariats-pour-la-realisation-des-objectifs?">Partnerships
                                            for the Goals</a>
                                    </h4>
                                    <p>
                                        Sustainable Development Goal 17 emphasizes the importance of global partnerships
                                        for achieving the SDGs. By collaborating with various stakeholders, including
                                        governments, businesses, and civil society organizations, HireUp works towards
                                        collective action and shared responsibility for sustainable development.
                                    </p>
                                </div>
                            </div>
                        </div> -->


                    </div>
                </div>
            </section>

            <hr>

            <section class="ls s-py-75 c-mb-30 ls-p">
                <div class="container">
                    <div class="row">

                        <div class="d-none d-lg-block divider-70"></div>

                        <!-- Manager 1: Nesrine Derouiche -->
                        <div class="team col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="vertical-item box-shadow content-padding text-center">
                                <div class="item-media">
                                    <img src="./front office assets/images/team/nesrine.jpg" width="75" height="100"
                                        alt="">
                                    <div class="media-links">
                                        <a class="abs-link" href="team-single.html"></a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h5>
                                        <a href="team-single.html">Nesrine Derouiche</a>
                                    </h5>
                                    <p class="team-text ls color-main">
                                        Jobs Manager
                                    </p>
                                    <p>
                                        Nesrine manages job postings and ensures that all positions are filled
                                        efficiently. With her extensive experience in recruitment, she strategically
                                        matches candidates with suitable opportunities, contributing to the growth and
                                        success of the organization.
                                    </p>
                                    <p class="social-icon-single">
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-google" title="google"></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Manager 2: Karim Feki -->
                        <div class="team col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="vertical-item box-shadow content-padding text-center">
                                <div class="item-media">
                                    <img src="./front office assets/images/team/karim.png" width="75" height="100"
                                        alt="">
                                    <div class="media-links">
                                        <a class="abs-link" title=""
                                            href="https://www.facebook.com/profile.php?id=100079776822402"></a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h5>
                                        <a href="https://www.facebook.com/profile.php?id=100079776822402">Karim Feki</a>
                                    </h5>
                                    <p class="team-text ls color-main">
                                        Profile Manager
                                    </p>
                                    <p>
                                        Karim oversees user profiles and ensures they are accurately represented on the
                                        platform. His attention to detail and strong communication skills enable him to
                                        build trust with users and maintain a positive online reputation for the
                                        organization.
                                    </p>
                                    <p class="social-icon-single">
                                        <a href="https://www.facebook.com/profile.php?id=100079776822402"
                                            class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-google" title="google"></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Manager 3: Abidi Mohamed -->
                        <div class="team col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="vertical-item box-shadow content-padding text-center">
                                <div class="item-media">
                                    <img src="./front office assets/images/team/abidi.jpg"
                                        style="max-width: 340x; max-height: 445px;" alt="">
                                    <div class="media-links">
                                        <a class="abs-link" title="" href="team-single.html"></a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h5>
                                        <a href="team-single.html">Abidi Mohamed</a>
                                    </h5>
                                    <p class="team-text ls color-main">
                                        Users Manager
                                    </p>
                                    <p>
                                        Abidi focuses on user engagement and satisfaction, ensuring that all users have
                                        a positive experience on the platform. With his expertise in customer service,
                                        he addresses user concerns promptly and implements strategies to enhance user
                                        satisfaction and retention.
                                    </p>
                                    <p class="social-icon-single">
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-google" title="google"></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Manager 4: Salma Laifi -->
                        <div class="team col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="vertical-item box-shadow content-padding text-center">
                                <div class="item-media">
                                    <img src="./front office assets/images/team/salma.jpg"
                                        style="max-width: 340x; width: 340x; height: 445px; max-height: 445px;" alt="">
                                    <div class="media-links">
                                        <a class="abs-link" title="" href="team-single.html"></a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h5>
                                        <a href="team-single.html">Salma Laifi</a>
                                    </h5>
                                    <p class="team-text ls color-main">
                                        Reports Manager
                                    </p>
                                    <p>
                                        Salma oversees the generation of reports and analytics to provide insights into
                                        platform performance. Her analytical skills and attention to detail enable her
                                        to identify trends and opportunities for improvement, ultimately driving
                                        business growth and success.
                                    </p>
                                    <p class="social-icon-single">
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-google" title="google"></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Manager 5: Amin Saadallah -->
                        <div class="team col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="vertical-item box-shadow content-padding text-center">
                                <div class="item-media">
                                    <img src="./front office assets/images/team/amin.jpg"
                                        style="max-width: 340x; width: 340x; height: 445px; max-height: 445px;" alt="">
                                    <div class="media-links">
                                        <a class="abs-link" title="" href="team-single.html"></a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h5>
                                        <a href="team-single.html">Amin Saadallah</a>
                                    </h5>
                                    <p class="team-text ls color-main">
                                        Articles Manager
                                    </p>
                                    <p>
                                        Amin is responsible for managing the publication of articles and content on the
                                        platform. With his creativity and attention to detail, he ensures that all
                                        content is engaging, informative, and aligns with the organization's goals and
                                        values.
                                    </p>
                                    <p class="social-icon-single">
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-google" title="google"></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Manager 6: Amin Hmem -->
                        <div class="team col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="vertical-item box-shadow content-padding text-center">
                                <div class="item-media">
                                    <img src="./front office assets/images/team/hmem.jpg"
                                        style="max-width: 340x; width: 340x; height: 445px; max-height: 445px;" alt="">
                                    <div class="media-links">
                                        <a class="abs-link" title="" href="team-single.html"></a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h5>
                                        <a href="team-single.html">Amin Hmem</a>
                                    </h5>
                                    <p class="team-text ls color-main">
                                        Ads Manager
                                    </p>
                                    <p>
                                        Amin manages advertising campaigns and ensures that they are effectively
                                        reaching the target audience. With his strategic thinking and marketing
                                        expertise, he maximizes the impact of ads and drives user engagement and
                                        conversion on the platform.
                                    </p>
                                    <p class="social-icon-single">
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-google" title="google"></a>
                                    </p>
                                </div>
                            </div>
                        </div>



                        <div class="d-none d-lg-block divider-40"></div>

                    </div>

                </div>
            </section>

            <!-- Footer -->
            <?php include ('./View/front_office/front_footer.php') ?>
            <!-- End Footer -->

        </div>
        <!-- eof #box_wrapper -->
    </div>
    <!-- eof #canvas -->


    <script src="./front office assets/js/compressed.js"></script>
    <script src="./front office assets/js/main.js"></script>
    <script src="./front office assets/js/switcher.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>

    <!-- voice recognation -->
    <script type="text/javascript" src=".\View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


</body>

</html>