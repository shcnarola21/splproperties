<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"> 
        <title>LS3 Digital</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <base href="<?php echo base_url(); ?>">
        
       <!--enable mobile device-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--favicon-->
   
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/frontend/images/ls3.png" type="image/x-icon"/>
        
        <!--goolge fonts-->
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600,700,900" rel="stylesheet">
        <!--fontawesome css-->
        <link rel="stylesheet" href="assets/frontend/css/font-awesome.min.css">
        <!--bootstrap css-->
        <link rel="stylesheet" href="assets/frontend/css/bootstrap.min.css">
        <!--owl-carousel css-->
        <link rel="stylesheet" href="assets/frontend/css/owl.carousel.min.css">
        <!--magnific-popup css-->
        <link rel="stylesheet" href="assets/frontend/css/magnific-popup.css">
        <!--superslides css-->
        <link rel="stylesheet" href="assets/frontend/css/superslides.css">
        <!--animate css-->
        <link rel="stylesheet" href="assets/frontend/css/animate.css">
        <!--YouTubePopUp css-->
        <link rel="stylesheet" href="assets/frontend/css/YouTubePopUp.css">
        <!--main css-->
        <link rel="stylesheet" href="assets/frontend/css/style.css">
        <!--responsive css-->
        <link rel="stylesheet" href="assets/frontend/css/responsive.css">
    </head>
    <body data-spy="scroll" data-target=".site-header" data-offset="50">
        <!-- Start preloader -->
        <div class="preloader">
            <div class="status">
                <div class="status-mes"></div>
            </div>
        </div><!--End preloader-->

        <!--Start header section-->
        <header class="site-header">
            <nav id="site-navigation" class="main-navigation navbar">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="logo" href="<?php echo base_url();?>">
                                    <img src="assets/frontend/images/logo.jpg" alt="site logo">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-10 col-sm-10">   
                            <div class="collapse navbar-collapse" id="main-menu">
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a href="#home">Home</a></li>
                                    <li><a href="#about">about</a></li>
                                    <li><a href="#contact">Contact</a></li>
                                    <li><a href="<?php echo base_url(); ?>register">Register</a></li>
                                    <li><a href="<?php echo base_url(); ?>login">Login</a></li>
                                </ul>
                            </div>
                        </div> 
                    </div>
                </div>
            </nav>
        </header><!--End header section-->
        <!-- Start welcome section-->
        <div class="mid-header-area" id="home">
            <div class="home-slider">
                <div id="slides">
                    <div class="slides-container">
                        <img src="assets/frontend/images/slider/slider-1.jpg" alt="">
                        <img src="assets/frontend/images/slider/slider-2.jpg" alt="">
                        <img src="assets/frontend/images/slider/slider-3.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="welcome-overlay"></div>
            <div class="mid-table">
                <div class="mid-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mid-content">
                                    <h3>Digital Sharing Made Easy</h3>
                                    <h3>Family & Friends beyond <span>Social Media</span></h3>
                                    <div class="button-header">
                                        <a href="<?php echo base_url(); ?>register" class="buy-now">Register</a>
                                        <a href="<?php echo base_url(); ?>login">Login</a>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>      
                </div>
            </div>
        </div><!--End welcome section-->

        <!--Start about section-->
        <section class="about-area section-padding" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title section-title-padding text-center">
                            <h2>who we are</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="about-details">
                            <h3>about our toller</h3>
                            <p>creative front end web developer ipsum dolor sit amet, consectetur adipisicing elit. Neque dicta minus sunt necessitatibus eum eaque sequi, inventore consectetur, autem blanditiis impedit! Placeat corporis, aliquam natus rerum, repellat modi repellendus numquam. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit aliquid perferendis possimus eum accusamus earum odio qui, a iure quis vero ut nesciunt distinctio et facilis dolorem ducimus laborum natus quasi ullam praesentium harum, illum tenetur? Eligendi magnam, libero delectus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae repudiandae facere fugit doloremque consequuntur magni ea, eligendi voluptate quaerat beatae repellat mollitia enim culpa itaque ratione voluptatem ipsam aspernatur tenetur.</p>
                            <div class="about-more">
                                <a href="#">read more <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="about-img">
                                <img src="assets/frontend/images/slider-1.jpeg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End about section-->

        <!--Start service section-->
        <section class="service-area bgcolor1 section-padding" id="service">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title section-title-padding text-center">
                            <h2>our awesome services</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="service-wrapper wow fadeInDown" data-wow-delay=".2s">
                            <div class="service-icon">
                                <i class="fa fa-rss" aria-hidden="true"></i>
                            </div>
                            <div class="service-details">
                                <h3>bootstrap development</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae tempora eos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="service-wrapper wow fadeInDown" data-wow-delay=".4s">
                            <div class="service-icon">
                                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                            </div>
                            <div class="service-details">
                                <h3>email marketing</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae tempora eos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="service-wrapper wow fadeInDown" data-wow-delay=".6s">
                            <div class="service-icon">
                                <i class="fa fa-umbrella" aria-hidden="true"></i>
                            </div>
                            <div class="service-details">
                                <h3>logo designing</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae tempora eos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="service-wrapper wow fadeInDown" data-wow-delay=".8s">
                            <div class="service-icon">
                                <i class="fa fa-id-card-o" aria-hidden="true"></i>
                            </div>
                            <div class="service-details">
                                <h3>software management</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae tempora eos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="service-wrapper wow fadeInDown" data-wow-delay="1s">
                            <div class="service-icon">
                                <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                            </div>
                            <div class="service-details">
                                <h3>seo development</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae tempora eos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="service-wrapper wow fadeInDown" data-wow-delay="1.2s">
                            <div class="service-icon">
                                <i class="fa fa-files-o" aria-hidden="true"></i>
                            </div>
                            <div class="service-details">
                                <h3>laravel development</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestiae tempora eos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End service section-->

        <!-- Start wining award section-->
        <section class="winner-area section-padding" id="winner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center section-title-padding">
                            <h2>fun facts</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="single-winner">
                            <div class="counter-icon">
                                <i class="fa fa-trophy" aria-hidden="true"></i>
                            </div>
                            <span class="counter">300</span>
                            <h5> wining award</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="single-winner">
                            <div class="counter-icon">
                                <i class="fa fa-bullhorn" aria-hidden="true"></i>
                            </div>
                            <span class="counter">450</span>
                            <h5>happy client</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="single-winner">
                            <div class="counter-icon">
                                <i class="fa fa-diamond" aria-hidden="true"></i>
                            </div>
                            <span class="counter">500</span>
                            <h5>completed project</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="single-winner">
                            <div class="counter-icon">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                            </div>
                            <span class="counter">700</span>
                            <h5>template released</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End wining section-->

        <!--Start purchase theme section-->
        <section class="purchase-area section-padding bgcolor1">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="purchase-wrapper">
                            <div class="purchase-template">
                                <img src="assets/frontend/images/03.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="purchase-details">
                            <h3>purchase our awesome theme</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor ipsa, non voluptatibus vitae, mollitia nihil repellendus illo deserunt, expedita possimus pariatur accusantium quidem architecto minima ipsum, dolore alias. Tempora ab harum esse itaque fugiat, quae asperiores odio ea quas necessitatibus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat sint natus ut laudantium saepe ipsa nulla aut quae quasi omnis.</p>
                            <a href="#">buy theme</a>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End purchase theme section-->

        <!--Start video section-->
        <section class="video-area">
            <div class="video-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="video-wrapper text-center">
                            <a class="bla-1" href="https://www.youtube.com/watch?v=3qyhgV0Zew0"><i class="fa fa-play" aria-hidden="true"></i></a>
                            <h4>watch theme demo</h4>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End video section-->

        <!--Start portfolio section-->
        <section class="work-area section-padding bgcolor1" id="work">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center section-title-padding">
                            <h2> latest work</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <ul class="work-filter">
                        <li class="filter" data-filter="all">all</li>
                        <li class="filter" data-filter=".webdesign">logo</li>
                        <li class="filter" data-filter=".development">design</li>
                        <li class="filter" data-filter=".javascipt">html</li>
                    </ul>
                </div>
                <div class="portfolio-items">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 mix webdesign">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/1.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/1.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix development">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/2.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/2.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix javascipt">
                            <div class="single-item ">
                                <a href="assets/frontend/images/portfolio/3.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/3.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix webdesign">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/4.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/4.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix development">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/5.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/5.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix javascipt">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/6.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/6.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix development">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/7.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/7.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix javascipt">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/8.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/8.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mix webdesign">
                            <div class="single-item">
                                <a href="assets/frontend/images/portfolio/9.jpg" class="popup">
                                    <img src="assets/frontend/images/portfolio/9.jpg" alt="portfolio item">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End portfolio section-->

        <!--Start team section-->
        <section class="team-area section-padding" id="team">
            <div class="team-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title section-title-padding text-center tst-white">
                            <h2>Our team</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="team-wrapper">
                            <div class="team-image">
                                <img src="assets/frontend/images/team/1.jpg" alt="">
                                <div class="member-content">
                                    <h4>jannathan doe<br><span>ceo & founder</span></h4>
                                    <ul>
                                        <li>
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-behance"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="team-wrapper">
                            <div class="team-image">
                                <img src="assets/frontend/images/team/2.jpg" alt="">
                                <div class="member-content">
                                    <h4>alisha smith<br><span>director</span></h4>
                                    <ul>
                                        <li>
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-behance"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="team-wrapper">
                            <div class="team-image">
                                <img src="assets/frontend/images/team/3.jpg" alt="">
                                <div class="member-content">
                                    <h4>rober broad<br><span>manager</span></h4>
                                    <ul>
                                        <li>
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-behance"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </section><!--End team section-->

        <!--Start get started section-->
        <section class="get-started">
            <div class="get-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="get-wrapper text-center">
                            <h3>want to meet our special group member??</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et laborum veritatis reiciendis, velit cupiditate praesentium expedita iusto atque eveniet fuga.</p>
                            <a href="#">contact us</a>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End get started section-->

        <!--Start pricing section-->
        <section class="pricing-area section-padding  bgcolor1" id="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center section-title-padding color-white">
                            <h2>choose your package</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="price-wrapper wow fadeInDown" data-wow-delay=".2s">
                            <div class="price-title">
                                <h4>basic</h4>
                                <div class="pricing-rate">
                                    <h5>$50</h5>
                                </div>
                            </div>
                            <div class="basic-product">
                                <ul>
                                    <li><a href="#">10 Pages Website Design</a></li>
                                    <li><a href="#">Free 1 Year Domain</a></li>
                                    <li><a href="#">Unlimited My Sql Database</a></li>
                                    <li><a href="#">10 GB Free Hosting</a></li>
                                    <li><a href="#">all time support</a></li>
                                </ul>
                            </div>
                            <div class="purchase-button">
                                <a href="#">sign up</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="price-wrapper second-price wow fadeInDown" data-wow-delay=".4s">
                            <div class="price-title">
                                <h4>standard</h4>
                                <div class="pricing-rate">
                                    <h5>$100</h5>
                                </div>
                            </div>
                            <div class="basic-product">
                                <ul>
                                    <li><a href="#">10 Pages Website Design</a></li>
                                    <li><a href="#">Free 1 Year Domain</a></li>
                                    <li><a href="#">3 Months Support</a></li>
                                    <li><a href="#">200 Email Forwards</a></li>
                                    <li><a href="#">Unlimited Data Transfer</a></li>
                                </ul>
                            </div>
                            <div class="purchase-button">
                                <a href="#">sign up</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="price-wrapper wow fadeInDown" data-wow-delay=".6s">
                            <div class="price-title">
                                <h4>premium</h4>
                                <div class="pricing-rate">
                                    <h5>$150</h5>
                                </div>
                            </div>
                            <div class="basic-product">
                                <ul>
                                    <li><a href="#">10 Pages Website Design</a></li>
                                    <li><a href="#">Free 1 Year Domain</a></li>
                                    <li><a href="#">3 Months Support</a></li>
                                    <li><a href="#">10 GB Free Hosting</a></li>
                                    <li><a href="#">1000 Email Accounts</a></li>
                                </ul>
                            </div>
                            <div class="purchase-button">
                                <a href="#">sign up</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End pricing section-->

        <!--Start home blog section-->
        <section class="home-blog section-padding" id="blog">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center section-title-padding">
                            <h2>our blog</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="home-blog-wrapper">
                            <img src="assets/frontend/images/home-blog/1.jpg" alt="">
                            <div class="home-blog-content">
                                <ul class="admin-date">
                                    <li>february 18, 2018</li>
                                    <li><span>by <a href="#">admin</a></span></li>
                                </ul>
                                <h3><a href="blog.html">step by step follow html tutorials</a></h3>
                                <a href="blog.html">read more <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="home-blog-wrapper">
                            <img src="assets/frontend/images/home-blog/2.jpg" alt="">
                            <div class="home-blog-content">
                                <ul class="admin-date">
                                    <li>january 28, 2018</li>
                                    <li><span>by <a href="#">admin</a></span></li>
                                </ul>
                                <h3><a href="blog.html">how to create a responsive website?</a></h3>
                                <a href="blog.html">read more <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="home-blog-wrapper">
                            <img src="assets/frontend/images/home-blog/3.jpg" alt="">
                            <div class="home-blog-content">
                                <ul class="admin-date">
                                    <li>january 12, 2018</li>
                                    <li><span>by <a href="#">admin</a></span></li>
                                </ul>
                                <h3><a href="blog.html">create your own business website</a></h3>
                                <a href="blog.html">read more <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End home blog section-->

        <!--Start client section-->
        <section class="client-area section-padding bgcolor1" id="client">
            <div class="client-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title text-center section-title-padding white-color">
                            <h2>what client says</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="clients-says owl-carousel">
                            <div class="single-client">
                                <div class="client-details">
                                    <div class="client-image">
                                        <img src="assets/frontend/images/client/1.jpg" alt="">
                                    </div>
                                    <h5>jannathan doe</h5>
                                    <span>designer</span>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis animi, aperiam dolorem esse voluptas ullam distinctio. Ea assumenda, voluptatem velit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam, nam</p>
                                </div>
                            </div>
                            <div class="single-client">
                                <div class="client-details">
                                    <div class="client-image">
                                        <img src="assets/frontend/images/client/2.jpg" alt="">
                                    </div>
                                    <h5>jannathan doe</h5>
                                    <span>developer</span>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis animi, aperiam dolorem esse voluptas ullam distinctio. Ea assumenda, voluptatem velit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam, nam</p>
                                </div>
                            </div>
                            <div class="single-client">
                                <div class="client-details">
                                    <div class="client-image">
                                        <img src="assets/frontend/images/client/3.jpg" alt="">
                                    </div>
                                    <h5>jannathan doe</h5>
                                    <span>programmer</span>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis animi, aperiam dolorem esse voluptas ullam distinctio. Ea assumenda, voluptatem velit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam, nam</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--End client section-->

        <!--Start contact section-->
        <section class="contact-area section-padding" id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title section-title-padding text-center">
                            <h2>contact us</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque at quo, earum saepe perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, laboriosam.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!--Start google map-->
                        <div id="google-map" data-latitude="48.85661" data-longitude="2.35222"></div>
                        <!--End google map-->
                    </div>
                    <div class="col-md-6">
                        <div class="contact-form">
                            <form action="http://formspree.io/baca7950@gmail.com" method="POST">
                                <div class="single col-md-12">
                                    <input type="text"  name="name" class="controler" placeholder="your name" required="">
                                </div>
                                <div class="single col-md-12">
                                    <input type="email" name="email" class="controler" placeholder="your email" required="">
                                </div>
                                <div class="single col-md-12">
                                    <textarea name="textarea" id="message" cols="25" rows="8" placeholder="write message"></textarea>
                                </div>
                                <div class="form-submit">
                                    <input type="submit" name="comment-sub" value="submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section> <!--End contact section-->

        <!--Scroll to top-->
        <div class="scrolltotop"> 
            <i class="fa fa-level-up" aria-hidden="true"></i> 
        </div>

        <!--Start footer section-->
        <footer class="footer-area bgcolor1">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="footer-content wow fadeInUp" data-wow-delay=".2s">
                            <a href="#"> <img src="assets/frontend/images/logo.jpg" alt=""></a>
                            <ul class="social-icons">
                                <li>
                                    <a href="#">facebook</a>
                                </li>
                                <li>
                                    <a href="#">twitter</a>
                                </li>
                                <li>
                                    <a href="#">instragram</a>
                                </li>
                                <li>
                                    <a href="#">pinterest</a>
                                </li>
                                <li>
                                    <a href="#">dribble</a>
                                </li>
                            </ul>
                            <div class="footer-reserved">
                                <p>�2018  LS3 Digital. All Rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer><!--End footer section-->

        <!--main js-->
        <script src="assets/frontend/js/jquery-1.12.4.min.js"></script>
        <!--bootstrap js-->
        <script src="assets/frontend/js/bootstrap.min.js"></script>
        <!--owl-carousel js-->
        <script src="assets/frontend/js/owl.carousel.min.js"></script>
        <!--magnific popup js-->
        <script src="assets/frontend/js/jquery.magnific-popup.min.js"></script>
        <!--mixitup js-->
        <script src="assets/frontend/js/mixitup.min.js"></script>
        <!--counterup js-->
        <script src="assets/frontend/js/jquery.counterup.min.js"></script>
        <!--waypoint js-->
        <script src="assets/frontend/js/waypoint.js"></script>
        <!--superslides js-->
        <script src="assets/frontend/js/jquery.superslides.min.js"></script>
        <!--google map key-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGbZZgmiB1CReNYWObnzNlnVy6iE09na0"></script>
        <!--wow js-->
        <script src="assets/frontend/js/wow.min.js"></script>
        <!--parallax js-->
        <script src="assets/frontend/js/jquery.parallax-1.1.3.js"></script>
        <!--YouTubePopUp js-->
        <script src="assets/frontend/js/YouTubePopUp.jquery.js"></script>
        <!--custom js-->
        <script src="assets/frontend/js/custom.js"></script>
    </body>
</html>
