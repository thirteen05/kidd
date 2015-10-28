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
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/modernizr-2.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/runtime.js"></script>
        <?php
            if ( is_front_page() ) {
                include '/wp-content/themes/kidd/swiffy-code.php';
            } 
        ?>
        <?php wp_head(); ?>
  </head>
    <body <?php body_class(); ?>>
        <div class="container-full">
            <div class="row">
                    <header>
                        <div class="container">
                            <div class="row">
                                <div class="header-cta-container">
                                    <a href="#" class="reservations"></a>
                                    <a href="#" class="photogallery"></a>
                                </div>
                                <ul class="menu-left menu">
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">Trips</a></li>
                                    <li><a href="#">Boat Specs</a></li>
                                </ul>
                                    <img class="logo" src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Captain Kidd Logo"/>
                                <ul class="menu-right menu">
                                    <li><a href="#">Testimonials</a></li>
                                    <li><a href="#">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </header>
                    <div class="main-contain">
                    <?php
                        if ( is_front_page() ) {
                    ?>
                        <div id="swiffycontainer" style="width: 100%; height: 301px"></div>
                    <?php
                        } 
                    ?>
                        <div class="col-md-12 nopadding inner-contain">
                            <div id="ContentWrapper">
