<?php

// Start Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("functions/pages_controller.php");
require_once("functions/database.php");
require_once("functions/organization.php");
require_once("functions/functions.php");

// Get the Page
$page = filter_input(INPUT_GET, "p", FILTER_SANITIZE_STRING);
if (empty($page)) {
    $page = 'home';
}

// Allowed Pages
$controllers = array('pages' => ['home', 'about', 'add', 'calendar', 'contact', 'directory', 'faq', 'search', 'who']);

foreach ($controllers['pages'] as $p) {
    if ($p == $page) {
        $active[$page] = "class='active'";
    } else {
        $active[$p] = "";
    }
}

// TEMP
$active['calendar'] = "";

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SWIN Partnership Action Network</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Official website of Southwestern Indiana Partnership Action Network, a network of groups and organizations for local community action."/>
    <meta name="keywords"
          content="community involvement, community action, activism, grassroots, environment, healthcare, issues"/>
    <meta name="author" content="Southwestern Indiana Partnership Action Network"/>

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="SWIN Partnership Action Network"/>
    <meta property="og:image" content="http://www.swinpan.org/images/logo-full.png"/>
    <meta property="og:url" content="http://www.swinpan.org"/>
    <meta property="og:site_name" content="SWIN Partnership Action Network"/>
    <meta property="og:description" content="Find and partner with local volunteer groups in the SWIN region!"/>
    <meta name="twitter:title" content="SWIN Partnership Action Network"/>
    <meta name="twitter:image" content="http://www.swinpan.org/images/logo-full.png"/>
    <meta name="twitter:url" content="http://www.swinpan.org"/>
    <meta name="twitter:card" content=""/>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico"/>

    <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'> -->

    <!-- Animate.css -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="css/icomoon.css">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- Superfish -->
    <link rel="stylesheet" href="css/superfish.css">
    <!-- Chosen -->
    <link rel="stylesheet" href="css/chosen.min.css">

    <link rel="stylesheet" href="css/style.css">


    <!-- Modernizr JS -->
    <script src="js/modernizr-2.6.2.min.js"></script>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div id="fh5co-wrapper">
    <div id="fh5co-page">
        <!-- <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 text-left fh5co-link">
                    </div>
                    <div class="col-md-6 col-sm-6 text-right fh5co-social">
                        <a href="#" class="grow"><i class="icon-facebook2"></i></a>
                        <a href="#" class="grow"><i class="icon-twitter2"></i></a>
                        <a href="#" class="grow"><i class="icon-instagram2"></i></a>
                    </div>
                </div>
            </div>
        </div> -->
        <header id="fh5co-header-section" class="sticky-banner">
            <div class="container">
                <div class="nav-header">
                    <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
                    <img src="images/logo-full.png" style="width:80px; height:80px; float:left; margin-top:4px;"/>
                    <h1 id="fh5co-logo"><a href="/"> Partnership Action Network</a></h1>
                    <!-- START #fh5co-menu-wrap -->
                    <nav id="fh5co-menu-wrap" role="navigation">
                        <ul class="sf-menu" id="fh5co-primary-menu">
                            <li <?php echo $active['home']; ?>>
                                <a href="/">Home</a>
                            </li>
                            <li <?php echo $active['add'] . $active['search']; ?>>
                                <a href="#" class="fh5co-sub-ddown">Groups</a>
                                <ul class="fh5co-sub-menu">
                                    <li <?php echo $active['add']; ?>><a href="index.php?p=add">Submit Your Group</a>
                                    </li>
                                    <li <?php echo $active['search']; ?>><a href="index.php?p=search&s=new">Search for
                                            Groups</a></li>
                                    <li <?php echo $active['directory']; ?>><a href="index.php?p=directory">All
                                            Groups</a></li>
                                </ul>
                            </li>
                            <li <?php echo $active['about'] . $active['faq']; ?>>
                                <a href="#" class="fh5co-sub-ddown">About</a>
                                <ul class="fh5co-sub-menu">
                                    <li <?php echo $active['about']; ?>><a href="index.php?p=about">What Is SWINPAN?</a>
                                    </li>
                                    <li <?php echo $active['faq']; ?>><a href="index.php?p=faq">F.A.Q.</a></li>
                                    <li <?php echo $active['who']; ?>><a href="index.php?p=who">Our Team</a></li>
                                </ul>
                            </li>
                            <li <?php echo $active['calendar']; ?>><a href="index.php?p=calendar">Calendar</a></li>
                            <li <?php echo $active['contact']; ?>><a href="index.php?p=contact">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- start:content -->

        <?php

        // Allowed Controls
        $control = new PagesController();

        // Switch Pages
        if (in_array($page, $controllers['pages'])) {
            $control->{$page}();
        } else {
            $control->error();
        }

        ?>

        <!-- end:content -->

        <footer>
            <div id="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <!-- <p class="fh5co-social-icons">
                                <a href="#"><i class="icon-twitter2"></i></a>
                                <a href="#"><i class="icon-facebook2"></i></a>
                                <a href="#"><i class="icon-instagram"></i></a>
                                <a href="#"><i class="icon-dribbble2"></i></a>
                                <a href="#"><i class="icon-youtube"></i></a>
                            </p> -->
                            <p>&copy; Southwestern Indiana Partnership Action Network 2017. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


    </div>
    <!-- END fh5co-page -->

</div>
<!-- END fh5co-wrapper -->

<!-- jQuery -->


<script src="js/jquery.min.js"></script>
<!-- jQuery Easing -->
<script src="js/jquery.easing.1.3.js"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js"></script>
<!-- Waypoints -->
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/sticky.js"></script>

<!-- Stellar -->
<script src="js/jquery.stellar.min.js"></script>
<!-- Font Awesome -->
<script src="https://use.fontawesome.com/52a752d26c.js"></script>
<!-- Superfish -->
<script src="js/hoverIntent.js"></script>
<script src="js/superfish.js"></script>

<!-- Main JS -->
<script src="js/main.js"></script>
<!-- Chosen -->
<script src="js/chosen.jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#tags").chosen();
    });
</script>


</body>
</html>

