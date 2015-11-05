<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/main.css">
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/modernizr-2.8.3.min.js"></script>
        <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/runtime.js">       </script>
        <?php
            if ( is_front_page() ) {
                include '/wp-content/themes/kidd/swiffy-code.php';
            } 
        ?>
        <?php wp_head(); ?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
  </head>
    <body <?php body_class(); ?>>
        <div class="container-full">
            <div class="row">
                <div class="mobile-bar">
                    <a href="<?php echo site_url(); ?>"><img class="logo-mobile" src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Captain Kidd Logo"/></a>
                    <a href="#" class="mobile-trigger">
                        <span>Menu</span><img src="<?php echo get_template_directory_uri(); ?>/images/hook.svg" />
                    </a>
                </div>
                <div class="show-mobile">
                    <div class="header-cta-container">
                        <a href="<?php echo site_url(); ?>/reservations" class="reservations"></a>
                        <a href="<?php echo site_url(); ?>/photo-gallery" class="photogallery"></a>
                    </div>
                </div>
                    <header>
                        <div class="container">
                            <div class="row">
                                <div class="header-cta-container">
                                    <a href="<?php echo site_url(); ?>/reservations" class="reservations"></a>
                                    <a href="<?php echo site_url(); ?>/photo-gallery" class="photogallery"></a>
                                </div>
                                <div class="menus-container">
                                    <ul class="menu-lefter menu-main">
                                        <li><a href="<?php echo site_url(); ?>/">Home</a></li>
                                        <li><a href="<?php echo site_url(); ?>/charter-boat-trips">Trips</a></li>
                                        <li><a href="<?php echo site_url(); ?>/fishing-boat">Boat Specs</a></li>
                                    </ul>
                                        <a href="<?php echo site_url(); ?>"><img class="logo" src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Captain Kidd Logo"/></a>
                                    <ul class="menu-right menu-main">
                                        <li><a href="<?php echo site_url(); ?>/testimonials">Testimonials</a></li>
                                        <li><a href="<?php echo site_url(); ?>/contact">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="main-contain">
                    <?php
                        if ( is_front_page() ) {
                    ?>
                        <div id="swiffycontainer" style="width: 100%; height: 301px"></div>
                        <div class="show-mobile">
                            <ul class="mobile-planks">
                                <li>
                                    <a href="#" class="brown">Inshore and Offshore Fishing</a>
                                </li>
                                <li>
                                    <a href="#" class="red">Dolphin Encounter</a>
                                </li>
                                <li>
                                    <a href="#" class="brown">Local Sightseeing</a>
                                </li>
                                <li>
                                    <a href="#" class="red">Shelling and Island Picnicking</a>
                                </li>
                            </ul>
                        </div>
                    <?php
                        } 
                    ?>
                        <div class="col-md-12 nopadding inner-contain">
                            <div id="ContentWrapper">
