<!DOCTYPE html>
<html lang="ar">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="">
        <title><?php echo $title; ?></title>
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/favicon-16x16.png">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jcarousel.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jcarousel2.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/modal.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/slider.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/custom.css" media="screen">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css" media="screen">
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <div class="header-top">
                    <div class="wrap-left">
                        <a href="http://soghan.ae" class="switch-lang">العربية</a>
                        <a href="#" class="playstore-link"></a></li>
                    </div>
                    <a href="<?php echo base_url(); ?>" class="logo">Logo</a>
                    <div class="wrap-right">
                        <?php if($this->session->userdata('user_id') == FALSE){ ?>
                            <div class="login-area">
                                <a href="<?php echo base_url().'login'; ?>" class="login">Sign In</a>
                                <a href="<?php echo base_url().'register'; ?>" class="register">Register</a>
                            </div>
                        <?php } else { ?>
                            <div class="logged-in">
                                <span class="user-name"><?php echo 'Welcome! '.ucfirst($this->session->userdata('username')); ?></span>
                                <a href="#" class="logged-link"></a>
                                <ul class="dropdown">
                                    <li class="view-profile"><a href="<?php echo base_url().'profile'; ?>">View Profile</a></li>
                                    <li class="change-pass"><a href="<?php echo base_url().'change_password'; ?>">Change Password</a></li>
                                    <li class="logout"><a href="<?php echo base_url().'logout'; ?>">Logout</a></li>
                                    <li><img src="<?php echo base_url(); ?>assets/images/logo2.png" alt="Logo 2"></li>
                                </ul>
                            </div>
                        <?php } ?>
                        <a href="#" class="appstore-link"></a>
                    </div>
                </div>
                <nav class="main-menu open-close">
                    <a href="#" class="opener">Menu</a>
                    <ul class="navigation">
                        <li class="home"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li><a href="<?php echo base_url().'raceinfo'; ?>">Race Videos</a></li>
                        <li><a href="<?php echo base_url().'market_place'; ?>">Market Place</a></li>
                        <li><a href="<?php echo base_url().'vendors/1'; ?>">Vendor Directory</a></li>
                        <li><a href="<?php echo base_url().'related_links'; ?>">Links</a></li>
                        <li><a href="<?php echo base_url().'calendar'; ?>">Calendar</a></li>
                        <li><a href="<?php echo base_url().'news'; ?>">News</a></li>
                    </ul>
                </nav>
                <?php if($this->uri->segment(1) == ''){ ?>
                    <div id="slideshow">
                        <ul class="bjqs">
                            <li>
                                <a href="http://soghan.ae" target="_blank">
                                    <img src="<?php echo base_url(); ?>assets/images/banner2.jpg" alt="slider2"/>
                                </a>
                            </li>
                            <li>
                                <a href="http://soghan.ae" target="_blank">
                                    <img src="<?php echo base_url(); ?>assets/images/banner3.jpg" alt="slider3" />
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </header>