<?=

    include_once __DIR__ . '/../../Controller/user_con.php';

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

?>


<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a title="#" href="./../../../index.php" class="text-nowrap logo-img">
                <img class="logo-img" alt=""></img>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <!-- <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li> -->
                <!-- <li class="sidebar-item <?//= ($active_page == 'main_dashboard') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?//= $userC->generateNavLink($nb_adds_for_link, 'View\back_office\main dashboard\index.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li> -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Managements</span>
                </li>
                <li class="sidebar-item <?= ($active_page == 'user') ? 'selected' : ''; ?> ">
                    <!-- <a class="sidebar-link" href="./../../../View/back_office/users managment/users_management.php" aria-expanded="false"> -->
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View/back_office/users managment/users_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <span class="hide-menu">User</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'profile') ? 'selected' : ''; ?>">
                    <!-- <a class="sidebar-link" href="var(--link-profile-mg)" aria-expanded="false"> -->
                    <!-- <a id="sidebar-link-profile" class="sidebar-link" aria-expanded="false"> -->
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View\back_office\profiles_management\profile_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="far fa-address-card"></i>
                        </span>
                        <span class="hide-menu">Profile</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'jobs') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View\back_office\jobs_management\job_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-tie"></i>
                        </span>
                        <span class="hide-menu">Jobs</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'dmd') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View\back_office\dmd and pub management\dmd_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="far fa-address-card"></i>
                        </span>
                        <span class="hide-menu">Ads Requests</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'ads') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View\back_office\dmd and pub management\pub_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-ad"></i>
                        </span>
                        <span class="hide-menu">Ads</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'articles') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View\back_office\articals management\articles_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="fa-regular fa-newspaper"></i>
                        </span>
                        <span class="hide-menu">Article</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'reclamations') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View/back_office/reclamations managment/recs_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <span class="hide-menu">Reclamations</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($active_page == 'reponses') ? 'selected' : ''; ?>">
                    <a class="sidebar-link"
                        href="./<?= $userC->generateNavLink($nb_adds_for_link, 'View/back_office/reponse management/reps_management.php'); ?>"
                        aria-expanded="false">
                        <span>
                            <i class="far fa-comment-dots"></i>
                        </span>
                        <span class="hide-menu">Reponses</span>
                    </a>
                </li>
            </ul>
            <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
                <div class="d-flex">
                    <div class="unlimited-access-title me-3">
                        <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
                        <a title="#" href="#" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy
                            Pro</a>
                    </div>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

<script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
