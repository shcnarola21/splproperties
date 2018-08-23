/* ================================================================
    Author       : WPxpart
    Template Name: Toller - One Page HTML Template
    Tags: Corporate, business, landing, one page, multipurpose Template, HTML 
    Version      : 1.0
=================================================================*/
(function ($) {
    "use strict";
    //   Active mixiup
    $(".portfolio-items").mixItUp();
    // Active wow
    new WOW().init();
    $(".video-area").parallax("50%", -0.5);
    $(".get-started").parallax("50%", -0.5);
    // activate counter up
    $('.counter').counterUp({
        delay: 50,
        time: 4000
    });
    /*
        slider add
    */
    $("#slides").superslides({
        animation: 'fade',
        play: 3000
    });
    /*=========================================================
        client Owl-carousel active
=========================================================*/
    $(".clients-says").owlCarousel({
        loop: true,
        autoplay: false,
        autoplayTimeout: 3000,
        dots: false,
        nav: true,
        navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    /*==================================================
            magnific Popup js
 ===================================================*/
    var magnifPopup = function () {
        $(".popup").magnificPopup({
            type: 'image',
            removalDelay: 300,
            mainClass: 'mfp-with-zoom',
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true, // By default it's false, so don't forget to enable it
                duration: 300, // duration of the effect, in milliseconds
                easing: 'ease-in-out', // CSS transition easing function
                // The "opener" function should return the element from which popup will be zoomed in
                // and to which popup will be scaled down
                // By defailt it looks for an image tag:
                opener: function (openerElement) {
                    // openerElement is the element on which popup was initialized, in this case its <a> tag
                    // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }
        });
    };
    // Call the functions 
    magnifPopup();
    /*===========================================
    Add smooth scrolling to all links
=============================================*/
    $("a").on('click', function (event) {
        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();
            // Store hash
            var hash = this.hash;
            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 1200, function () {
                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
        } // End if
    });
    /*============================================== 
        youtube video popup active 
===============================================*/
    $("a.bla-1").YouTubePopUp();
    /*=================================================
      scroll to top 
=======================================================*/
    $('.scrolltotop').fadeOut(1000);
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 500) {
            $('.scrolltotop').fadeIn(1000);
        } else {
            $('.scrolltotop').fadeOut(1000);
        }
    });
    $('.scrolltotop').on('click', function () {
        $('html,body').animate({
            scrollTop: 0
        }, 1000);
    });
    /*======================================================
            Google Map
======================================================*/
    var get_latitude = $('#google-map').data('latitude');
    var get_longitude = $('#google-map').data('longitude');

    function initialize_google_map() {
        var myLatlng = new google.maps.LatLng(get_latitude, get_longitude);
        var mapOptions = {
            zoom: 14,
            scrollwheel: false,
            center: myLatlng
        };
        var map = new google.maps.Map(document.getElementById('google-map'), mapOptions);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize_google_map);
    /*============================================
            add sticky class
==================================================*/
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 0) {
            $('.site-header').addClass('sticky');
        } else {
            $('.site-header').removeClass('sticky');
        }
    });
    /*==============================================
            responsive menu
========================================================*/
    $(document).on('click', '.navbar-collapse.in', function (e) {
        if ($(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle') {
            $(this).collapse('hide');
        }
    });
    $(".toggle-btn").on("click", function () {
        $(this).toggleClass("active");
        $(".site-header").toggleClass("active");
    });
    /*=====================================
           PRELOADER JS
==========================================*/
    var prealoaderOption = $(window);
    prealoaderOption.on("load", function () {
        var preloader = jQuery('.preloader');
        var preloaderArea = jQuery('.preloader-area');
        preloader.fadeOut();
        preloaderArea.delay(500).fadeOut('slow');
    });
})(jQuery);