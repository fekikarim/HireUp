<!DOCTYPE html>
<html lang="en">

<head>
  <title>HireUp</title>
  <meta charset="utf-8" />

  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <link rel="stylesheet" href="./front office assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="./front office assets/css/animations.css" />
  <link rel="stylesheet" href="./front office assets/css/font-awesome.css" />
  <link rel="stylesheet" href="./front office assets/css/main.css" class="color-switcher-link" />
  <script src="./front office assets/js/vendor/modernizr-2.6.2.min.js"></script>

  <link href="./front office assets/images/HireUp_icon.ico" rel="icon" />

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

  <style>
    .team-quote-image {
      width: 200px;
      max-width: 200px;
      /* Adjust the width as needed */
      height: 200px;
      max-height: 200px;
      /* Adjust the height as needed */
      overflow: hidden;
      border-radius: 50%;
      /* Creates a rounded shape */
      margin: 0 auto;
      /* Centers the image horizontally */
    }

    .team-quote-image img {
      width: 100%;
      /* Makes the image fill its container */
      height: auto;
      /* Maintains the aspect ratio */
      display: block;
      /* Ensures proper alignment */
    }
  </style>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<?php

include_once __DIR__ . './Controller/user_con.php';
require_once __DIR__ . '/Controller/profileController.php';

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
}


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
            id="modal-search-input" />
        </div>
        <button type="submit" class="btn">Search</button>
      </form>
    </div>
  </div>

  <!-- wrappers for visual page editor and boxed version of template -->
  <div id="canvas">
    <div id="box_wrapper">
      <!-- template sections -->

      <!--eof topline-->

      <?php
      $active_page = 'index';
      include ('./View/front_office/front_header.php')
        ?>

      <section class="page_slider">
        <div class="flexslider" data-nav="true" data-dots="false">
          <ul class="slides">
            <li class="ds text-center">
              <img src="./front office assets/images/index/5.png" alt="" />
              <div class="container">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="intro_layers_wrapper">
                      <div class="intro_layers">
                        <div class="intro_layer" data-animation="fadeInLeft">
                          <h3 class="intro_before_featured_word">
                            We are hiring
                          </h3>
                        </div>
                        <div class="intro_layer" data-animation="fadeInRight">
                          <h2 class="text-uppercase intro_featured_word">
                            people like you.
                          </h2>
                        </div>
                        <div class="intro_layer" data-animation="fadeIn">
                          <div class="d-inline-block">
                            <button type="button" class="btn btn-outline-maincolor center-block" data-animation="fadeIn"
                              onclick="window.location.href='./about.php';">
                              Learn More
                            </button>
                          </div>
                        </div>
                      </div>
                      <!-- eof .intro_layers -->
                    </div>
                    <!-- eof .intro_layers_wrapper -->
                  </div>
                  <!-- eof .col-* -->
                </div>
                <!-- eof .row -->
              </div>
              <!-- eof .container -->
            </li>
            <li class="ds text-center">
              <img src="./front office assets/images/index/2 (2).jpg" alt="" />

              <!-- eof .container -->
            </li>
            <li class="ds text-center">
              <img src="./front office assets/images/index/3 (1).png" alt="" />

              <!-- eof .container -->
            </li>
          </ul>
          <ul class="flex-direction-nav">
            <li class="flex-nav-prev">
              <a class="flex-prev" href="#">&gt;</a>
            </li>
            <li class="flex-nav-next">
              <a class="flex-next" href="#">&lt;</a>
            </li>
          </ul>
        </div>
        <!-- eof flexslider -->
      </section>

      <section class="ds slider-bottomline d-none d-xl-block py-50">
        <div class="container">
          <div class="row">
            <div class="col-md-4 text-center">
              <div class="info-block">
                <p>Call Us 24/7</p>
                <h3>+216 93 213 636</h3>
              </div>
            </div>
            <div class="col-md-4 text-center">
              <div class="info-block">
                <p>Email Address</p>
                <h3>contact@hireup.com</h3>
              </div>
            </div>
            <div class="col-md-4 text-center">
              <div class="info-block">
                <p>Open Hours</p>
                <h3>Daily 9:00-20:00</h3>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- <section class="ls about s-pt-25">
          <div class="container">
            <div class="row">
              <div
                class="col-md-12 col-lg-6 animate"
                data-animation="slideInLeft"
              >
                <div class="heading-about">
                  <h2>HU</h2>
                  <h4>Welcome to</h4>
                  <h3>Invenir!</h3>
                  <p>
                    We believe in the value that our functions add to a
                    business. We feel that this specialist part of HireUp is often
                    unrecognised for its contribution to the profitability and
                    success of a business.
                  </p>
                </div>
                <div class="icons-list">
                  <ul class="list-bordered">
                    <li class="media media-body">
                      <i class="teaser-icon fa fa-rocket"></i>
                      <h4 class="title">
                        <span>638</span> Companies We Helped
                      </h4>
                    </li>
                    <li class="media media-body">
                      <i class="teaser-icon fa fa-briefcase"></i>
                      <h4 class="title"><span>12</span> Corporate Programs</h4>
                    </li>
                    <li class="media media-body">
                      <i class="teaser-icon fa fa-graduation-cap"></i>
                      <h4 class="title"><span>28</span> Trainings Courses</h4>
                    </li>
                    <li class="border-bottom-0 media media-body">
                      <i class="teaser-icon fa fa-user"></i>
                      <h4 class="title"><span>125</span> Strategic Partners</h4>
                    </li>
                  </ul>
                </div>
              </div>
              <div
                class="col-md-12 col-lg-6 animate"
                data-animation="slideInRight"
              >
                <img src="./front office assets/images/person01.jpg" alt="person01.jpg" />
              </div>
            </div>
          </div>
        </section> 
      -->

      <section class="ls s-pt-50 s-pb-75 c-gutter-60 c-mb-lg-50 service-item">
        <div class="container">
          <div class="row">

            <div class="d-none d-lg-block divider-80"></div>

            <div class="col-md-4 col-sm-6">
              <div class="icon-box text-center">
                <div class="icon-styled fs-56">
                  <i class="color-main2 fa fa-search"></i>
                </div>
                <h4>
                  <a href="./View/front_office/jobs management/jobs_list.php">Job Search
                    <br>Assistance</a>
                </h4>
                <p>
                  Advanced search options to find the perfect job based on your skills and preferences.
                </p>
              </div>
            </div>
            <!-- .col-* -->

            <div class="col-md-4 col-sm-6">
              <div class="icon-box text-center">
                <div class="icon-styled fs-56">
                  <i class="color-main2 fa fa-user"></i>
                </div>
                <h4>
                  <a href="./View/front_office/profiles_management/profile.php">Profile
                    <br>Management</a>
                </h4>
                <p>
                  Create and manage your professional profile to attract potential employers.
                </p>
              </div>
            </div>
            <!-- .col-* -->

            <div class="col-md-4 col-sm-6">
              <div class="icon-box text-center">
                <div class="icon-styled fs-56">
                  <i class="color-main2 fa fa-envelope"></i>
                </div>
                <h4>
                  <a href="./View/front_office/messenger/messaging.php">Messaging
                    <br>System</a>
                </h4>
                <p>
                  Communicate with employers and applicants directly through our integrated messaging platform.
                </p>
              </div>
            </div>
            <!-- .col-* -->

            <div class="col-md-4 col-sm-6">
              <div class="icon-box text-center">
                <div class="icon-styled fs-56">
                  <i class="color-main2 fa fa-file-text"></i>
                </div>
                <h4>
                  <a href="./View/front_office/jobs management/jobs_list.php">Resource
                    <br>Library</a>
                </h4>
                <p>
                  Access articles, guides, and tips to help you navigate the job market and enhance your career.
                </p>
              </div>
            </div>
            <!-- .col-* -->

            <div class="col-md-4 col-sm-6">
              <div class="icon-box text-center">
                <div class="icon-styled fs-56">
                  <i class="color-main2 fa fa-bullhorn"></i>
                </div>
                <h4>
                  <a href="./View/front_office/jobs management/jobs.php">Job
                    <br>Postings</a>
                </h4>
                <p>
                  Post job openings to attract top talent and manage applications with ease.
                </p>
              </div>
            </div>
            <!-- .col-* -->

            <div class="col-md-4 col-sm-6">
              <div class="icon-box text-center">
                <div class="icon-styled fs-56">
                  <i class="color-main2 fa fa-line-chart"></i>
                </div>
                <h4>
                  <a href="./View/front_office/reclamation/reclamation.php">Analytics
                    <br>and Reporting</a>
                </h4>
                <p>
                  Track your application progress and get insights to improve your job search strategy.
                </p>
              </div>
            </div>
            <!-- .col-* -->

            <div class="d-none d-lg-block divider-20"></div>

          </div>
        </div>
      </section>


      <section class="icon-boxed teaser-box ls s-py-lg-130 c-my-lg-10 s-parallax">
        <div class="container">
          <div class="row">
            <div class="col-lg-4">
              <div class="icon-box text-center hero-bg box-shadow animate" data-animation="fadeInRight">
                <div class="teaser-icon icon-styled bg-maincolor3">
                  <i class="fa fa-robot"></i> <!-- Changed icon to a bot icon -->
                </div>
                <h3>
                  <a href="./chatbot_article.php">Introducing the HireUp Chat Bot</a>
                  <!-- <a href="./chatbot_article.php">Introducing HireUp Bot</a> -->
                  <!-- Changed heading to highlight the bot -->
                </h3>
                <!-- <p>
                  Our innovative chatbot feature, HireUp Bot, streamlines the recruitment process, providing instant
                  assistance and guidance to both employers and job seekers.
                </p> -->
                <p>
                  Your ultimate recruitment companion, simplifying the process for both employers and job seekers. 
                  With its advanced algorithms, HireUp Bot streamlines every step. 
                  Say hello to efficiency and effectiveness like never before!
                </p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="icon-box text-center hero-bg box-shadow animate" data-animation="fadeInDown">
                <div class="teaser-icon icon-styled bg-maincolor3">
                  <i class="fa fa-lightbulb-o"></i> <!-- Changed icon to a lightbulb for future improvements -->
                </div>
                <h3>
                  <a href="./future.php">Pioneering Future Improvements</a>
                  <!-- Changed heading to emphasize future enhancements -->
                </h3>
                <p>
                  At HireUp, we're committed to continuous innovation. Our vision extends beyond the present, driving us
                  to develop cutting-edge solutions that anticipate and meet the evolving needs of the job market.
                </p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="icon-box text-center hero-bg box-shadow animate" data-animation="fadeInLeft">
                <div class="teaser-icon icon-styled bg-maincolor3">
                  <i class="fa fa-heart"></i> <!-- Changed icon to a heart for the win-win-win situation -->
                </div>
                <h3>
                  <a href="./www.php">Creating a Win-Win-Win Situation</a>
                  <!-- Changed heading to highlight the win-win-win scenario -->
                </h3>
                <p>
                  HireUp's ultimate goal is to create a win for us, a win for the user, and a win for society. By
                  facilitating meaningful employment connections, we strive to foster mutual benefit, satisfaction, and
                  positive societal impact.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>



      <section class="ls s-py-75 s-py-lg-130">
        <div class="container">
          <div class="d-none d-lg-block divider-30"></div>
          <div class="row">
            <div class="col-lg-6 animate" data-animation="fadeInLeft">
              <img src="./front office assets/images/index/7.png" alt="Job Assistance">
            </div>
            <div class="single-service divider-30 col-lg-6 text-left animate" data-animation="fadeInRight">
              <div class="content mx-30">
                <h4 class="single-service">Job Search and Profile Management</h4>
                <p>
                  Discover job opportunities tailored to your skills and manage your professional profile with ease on
                  HireUp. Our platform offers a comprehensive suite of tools to streamline your job search process.
                </p>
                <ul class="list-styled">
                  <li>
                    Advanced job search options
                  </li>
                  <li>
                    Professional profile creation and management
                  </li>
                  <li>
                    Direct messaging with employers
                  </li>
                  <li>
                    Access to a rich resource library
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <p class="divider-sm-50 animate" data-animation="fadeInUp">
                Our platform is designed to enhance your job search experience. From personalized job recommendations to
                managing applications, HireUp offers a user-friendly interface to help you find the right job quickly
                and efficiently. Additionally, our new chatbot feature is here to assist you 24/7, providing instant
                support and guidance throughout your job search and profile management process.
              </p>
            </div>
          </div>
          <div class="d-none d-lg-block divider-20"></div>
        </div>
      </section>


      <hr>

      <section class="ls s-py-50 c-gutter-60">
        <div class="container">
          <div class="row animate" data-animation="slideInDown">
            <div class="d-none d-lg-block divider-70"></div>

            <main class="offset-lg-1 col-lg-10">
              <article class="vertical-item post type-post status-publish format-standard has-post-thumbnail box">

                <!-- .post-thumbnail -->
                <div class="item-media post-thumbnail">
                  <div class="embed-responsive embed-responsive-3by2">
                    <a href="https://www.youtube.com/embed/kzvzG6LkQSA?si=zm6rfHFYsPg6xBM6" class="embed-placeholder">
                      <!-- Your trailer video or image -->
                      <img src="./front office assets/images/index/8 (1).png" alt="">
                    </a>
                  </div>
                </div>


                <div class="item-content">
                  <div class="entry-content">
                    <h4 class="entry-title">
                      Empowering Your Future with HireUp
                    </h4>
                    <p>
                      In a world where traditional employment methods no longer suffice, HireUp emerges as a beacon of
                      innovation and progress, revolutionizing the way individuals and businesses connect.
                    </p>

                    <p>
                      HireUp is not just a recruitment platform; it's a catalyst for change, fostering creativity,
                      efficiency, and excellence on a global scale. Our commitment to simplifying the hiring process
                      transcends borders, making employment opportunities accessible with just a click.
                    </p>

                    <blockquote class="divider-0 text-center">
                      <p>
                        "Working with HireUp has transformed our approach to talent acquisition. It's not just about
                        finding candidates; it's about finding the right fit for our vision and mission."
                      </p>
                      <span>
                        - Karim Feki, CEO, be.net -
                      </span>
                    </blockquote>

                    <p>
                      Join us in our mission to redefine employment and shape the future of work. Together, we can build
                      a world where talent knows no boundaries and opportunities abound.
                    </p>

                  </div>
                  <!-- .entry-content -->
                  <footer class="entry-footer">
                    <i class="color-main fa fa-user "></i>
                    <a href="javascript:void(0)">
                      Abidi Mohamed
                    </a>
                    <i class="color-main fa fa-calendar"></i>
                    <a href="javascript:void(0)">
                      May 19, 2024
                    </a>
                    <i class="color-main fa fa-tag"></i>
                    <a href="javascript:void(0)">
                      Thread
                    </a>
                  </footer>
                  <!-- .entry-footer -->

                </div>
                <!-- .item-content -->
              </article>
            </main>
          </div>
        </div>
      </section>


      <section class="ds half-section collapse-section">
        <div class="row">
          <div class="col-lg-6">
            <div class="image_cover image_cover_left half-image"></div>
          </div>
          <div class="col-lg-6 collapse-table">
            <div class="contact-header collapse-header heading pt-30">
              <h5>Empowering Growth</h5>
              <h4>Economic </h4>
            </div>
            <div id="accordion01" role="tablist" aria-multiselectable="true">
              <div class="card">
                <div class="card-header" role="tab" id="collapse01_header">
                  <h5 class="mb-0">
                    <a data-toggle="collapse" href="#collapse01" aria-expanded="true" aria-controls="collapse01">
                      Simplified Hiring Process
                    </a>
                  </h5>
                </div>
                <div id="collapse01" class="collapse show" role="tabpanel" aria-labelledby="collapse01_header"
                  data-parent="#accordion01">
                  <div class="card-body">
                    HireUp revolutionizes the hiring process by making it simple and efficient. With just one click,
                    employers can connect with qualified candidates, eliminating the need for traditional paper-based
                    processes and saving valuable time.
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" role="tab" id="collapse02_header">
                  <h5 class="mb-0">
                    <a class="collapsed" data-toggle="collapse" href="#collapse02" aria-expanded="false"
                      aria-controls="collapse02">
                      Streamlined Job Management
                    </a>
                  </h5>
                </div>
                <div id="collapse02" class="collapse" role="tabpanel" aria-labelledby="collapse02_header"
                  data-parent="#accordion01">
                  <div class="card-body">
                    HireUp offers an intuitive platform for managing job postings and applications. Employers can easily
                    organize and track candidates, ensuring a seamless recruitment process from start to finish.
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" role="tab" id="collapse03_header">
                  <h5 class="mb-0">
                    <a class="collapsed" data-toggle="collapse" href="#collapse03" aria-expanded="false"
                      aria-controls="collapse03">
                      Economic Empowerment
                    </a>
                  </h5>
                </div>
                <div id="collapse03" class="collapse" role="tabpanel" aria-labelledby="collapse03_header"
                  data-parent="#accordion01">
                  <div class="card-body">
                    By facilitating efficient hiring processes, HireUp contributes to economic growth by connecting
                    businesses with talented individuals. This leads to increased productivity, innovation, and job
                    creation, driving overall economic prosperity.
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" role="tab" id="collapse04_header">
                  <h5 class="mb-0">
                    <a class="collapsed" data-toggle="collapse" href="#collapse04" aria-expanded="false"
                      aria-controls="collapse04">
                      Sustainable Growth
                    </a>
                  </h5>
                </div>
                <div id="collapse04" class="collapse" role="tabpanel" aria-labelledby="collapse04_header"
                  data-parent="#accordion01">
                  <div class="card-body">
                    HireUp fosters sustainable growth by facilitating matches between employers and candidates based on
                    skills, qualifications, and cultural fit. This results in long-term employment relationships that
                    benefit both parties and contribute to the stability of the economy.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>


      <section id="testimonials" class="s-pt-75 s-pb-50">
        <div class="container">
          <div class="row">
            <div class="divider-50 d-none d-lg-block"></div>
            <div class="col-md-12">
              <div class="testimonials-slider owl-carousel" data-autoplay="true" data-responsive-lg="1"
                data-responsive-md="1" data-responsive-sm="1" data-nav="false" data-dots="true">
                <div class="quote-item">
                  <div class="team-quote-image">
                    <img class="img-fluid" src="./front office assets/images/team/karim.png"
                      alt="Karim's Profile Picture" />
                  </div>
                  <p class="small-text color-darkgrey">
                    Karim
                    <br />
                    <span>Feki</span>
                  </p>
                  <p class="testimonials">
                    <em class="big text-muted">
                      "HireUp has transformed the way we approach recruitment at our company. Its innovative platform
                      and tools have enabled us to challenge assumptions and gain valuable external insights, ultimately
                      leading to better hiring decisions."
                    </em>
                  </p>
                </div>
                <div class="quote-item">
                  <div class="team-quote-image">
                    <img src="./front office assets/images/team/nesrine.jpg" alt="" />
                  </div>
                  <p class="small-text color-darkgrey">
                    Nesrine
                    <span>Derouiche</span>
                  </p>
                  <p class="testimonials">
                    <em class="big text-muted">
                      "Working with HireUp has been a game-changer for us. Their platform makes the recruitment process
                      seamless, and their team has been incredibly helpful and supportive throughout. It's a pleasure to
                      collaborate with such dedicated professionals."
                    </em>
                  </p>
                </div>
                <div class="quote-item">
                  <div class="team-quote-image">
                    <img src="./front office assets/images/team/abidi.jpg" alt="" />
                  </div>
                  <p class="small-text color-darkgrey">
                    Mohamed
                    <span>Abidi</span>
                  </p>
                  <p class="testimonials">
                    <em class="big text-muted">
                      "HireUp's platform has significantly simplified our recruitment efforts. Its intuitive interface
                      and comprehensive features have made hiring a breeze. We've been able to attract top talent and
                      streamline our processes thanks to HireUp."
                    </em>
                  </p>
                </div>
                <div class="quote-item">
                  <div class="team-quote-image">
                    <img class="img-fluid" src="./front office assets/images/team/salma.jpg"
                      alt="Salma Laifi's Profile Picture" />
                  </div>
                  <p class="small-text color-darkgrey">
                    Salma
                    <br />
                    <span>Laifi</span>
                  </p>
                  <p class="testimonials">
                    <em class="big text-muted">
                      "Using HireUp has streamlined our recruitment process immensely. The platform's features and the
                      team's support have been exceptional. It's a powerful tool for any organization looking to enhance
                      their hiring strategies."
                    </em>
                  </p>
                </div>
                <div class="quote-item">
                  <div class="team-quote-image">
                    <img class="img-fluid" src="./front office assets/images/team/hmem.jpg"
                      alt="Amin Hmem's Profile Picture" />
                  </div>
                  <p class="small-text color-darkgrey">
                    Amin
                    <br />
                    <span>Hmem</span>
                  </p>
                  <p class="testimonials">
                    <em class="big text-muted">
                      "HireUp's innovative approach to recruitment has made a significant impact on our hiring process.
                      The platform is user-friendly, and the insights we gain are invaluable. It's been a fantastic tool
                      for our team."
                    </em>
                  </p>
                </div>
                <div class="quote-item">
                  <div class="team-quote-image">
                    <img class="img-fluid" src="./front office assets/images/team/amin.jpg"
                      alt="Amin Saadallah's Profile Picture" />
                  </div>
                  <p class="small-text color-darkgrey">
                    Amin
                    <br />
                    <span>Saadallah</span>
                  </p>
                  <p class="testimonials">
                    <em class="big text-muted">
                      "HireUp has been instrumental in improving our recruitment outcomes. The ease of use and the
                      comprehensive data analytics have provided us with a clear advantage in attracting and hiring top
                      talent."
                    </em>
                  </p>
                </div>
              </div>
              <!-- .testimonials-slider -->
            </div>
            <div class="divider-50 d-none d-lg-block"></div>
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

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
  <!-- <script src="js/switcher.js"></script> -->

  <!-- Google Map Script -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?"></script>
  
  <!-- voice recognation -->
  <script type="text/javascript" src=".\View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>
</body>

</html>