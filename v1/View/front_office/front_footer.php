<?php

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

?>


<footer class="page_footer ds s-py-sm-20 s-pt-md-75 s-pb-md-50 s-py-lg-130 c-gutter-60 pb-20 half-section">
  <div class="container">
    <div class="row">
      <div class="footer col-md-4 text-center animate" data-animation="fadeInUp">
        <div class="footer widget text-center">
          <h3 class="widget-title title-menu">Explore</h3>
          <ul class="footer-menu">
            <li>
              <a href="<?php echo $current_url . "/View/front_office/jobs management/jobs_list.php" ?>">Job Search</a>
            </li>
            <li class="menu1">
              <a href="<?php echo $current_url . "/View/front_office/profiles_management/subscription/subscriptionCards.php" ?>">Try Premium</a>
            </li>
            <li>
              <a href="<?php echo $current_url . "/View/front_office/ads/view_ads.php" ?>">Ads</a>
            </li>
            <li class="menu1">
              <a href="<?php echo $current_url . "/View/front_office/profiles_management/profile-settings-privacy.php" ?>">Settings</a>
            </li>
            <li>
              <a href="<?php echo $current_url . "/View/front_office/resume/resume_form.php" ?>">ResumeUp</a>
            </li>
            <li class="menu1">
              <a href="<?php echo $current_url . "/View/front_office/profiles_management/profile.php" ?>">Careers</a>
            </li>
            <li class="border-bottom-0">
              <a href="<?php echo $current_url . "/View/front_office/reclamation/rec_list.php" ?>">Report</a>
            </li>
            <li class="menu1 border-bottom-0">
              <a href="<?php echo $current_url . "/about.php" ?>">About</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer col-md-4 text-center animate" data-animation="fadeInUp">
        <div class="text-center">
          <div class="header_logo_center footer-logo-ds">
            <a href="<?php echo $current_url . "/index.php" ?>" class="logo">
              <!-- <span class="logo_text">Hire</span> -->
              <span class="logo-img-front-footer">
                <img class="img-front" alt="" />
              </span>
              <!-- <span class="logo_subtext">Up</span> -->
            </a>
          </div>
          <!-- eof .header_left_logo -->
        </div>
        <div class="widget pt-20">
          At HireUp, we are committed to revolutionizing the way professionals connect and thrive in their careers. Our platform offers a comprehensive suite of tools and resources designed to empower individuals and businesses alike.
        </div>

        <div class="widget">
          <div class="media">
            <i class="mx-10 color-main fa fa-map-marker"></i>
            Pole Technique, El Ghazela
          </div>

          <div class="media">
            <i class="mx-10 color-main fa fa-phone"></i>
            +216 93 213 636
          </div>

          <div class="media text-center link">
            <i class="mx-10 text-center color-main fa fa-envelope"></i>
            <a href="#">contact@hireup.com</a>
          </div>
        </div>

        <div class="author-social">
          <a title="#" href="https://www.facebook.com/profile.php?id=61557532202485" class="fa fa-facebook color-bg-icon rounded-icon" target="_blanck"></a>
          <a title="#" href="https://www.instagram.com/hire.up.tn/" class="fa fa-twitter color-bg-icon rounded-icon" target="_blanck"></a>
          <a title="#" href="https://www.instagram.com/hire.up.tn/" class="fa fa-google color-bg-icon rounded-icon" target="_blanck"></a>
        </div>
      </div>
      <div class="footer col-md-4 text-center animate" data-animation="fadeInUp">
        <div class="widget widget_mailchimp">
          <h3 class="widget-title">Newsletter</h3>

          <p>
            Enter your email address here always to be updated. We
            promise not to spam!
          </p>

          <form class="signup">
            <label for="mailchimp_email">
              <span class="screen-reader-text">Subscribe:</span>
            </label>

            <input id="mailchimp_email" name="email" type="email" class="form-control mailchimp_email" placeholder="Email Address" />

            <button type="submit" class="search-submit">
              Subscribe
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</footer>

<section class="page_copyright ds s-py-30">
  <div class="container">
    <div class="row align-items-center">
      <div class="divider-20 d-none d-lg-block"></div>
      <div class="col-md-12 text-center">
        <p>
          &copy; Copyright <span class="copyright_year">2024</span> All
          Rights Reserved
        </p>
      </div>
      <div class="divider-20 d-none d-lg-block"></div>
    </div>
  </div>
</section>