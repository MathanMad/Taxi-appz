;
(function() {
    'use strict';

    var $body = $('body'),
        $window = $(window);

    /*==============================================
    Back to top
    ===============================================*/
    $body.append("<a href='.html' class='BackToTop BackToTop--hide ScrollTo'><i class='fa fa-angle-up'></i></a>");

    var $liftOff = $('.BackToTop');
    $window.on('scroll', function() {
        if ($window.scrollTop() > 150) {
            $liftOff.addClass('BackToTop--show').removeClass('BackToTop--hide');
        } else {
            $liftOff.addClass('BackToTop--hide').removeClass('BackToTop--show');
        }
    });

    /*==============================================
    Retina support added
    ===============================================*/
    if (window.devicePixelRatio > 1) {
        $(".retina, .navbar-brand img, .logo img").imagesLoaded(function() {
            $(".retina, .navbar-brand img, .logo img").each(function() {
                var src = $(this).attr("src").replace(".", "@2x.");
                var h = $(this).height();
                $(this).attr("src", src).css({ height: h, width: "auto" });
            });
        });
    }

    /*==============================================
    Returns height of browser viewport
    ===============================================*/
    $window.on('resize.windowscreen', function() {
        var height = $(this).height(),
            width = $(this).width(),

            $jsFullHeight = $('.js-FullHeight'),
            $mainNav = $('#mainNav'),

            $mainNavSticky = $mainNav.hasClass('navbar-sticky'),

            isFixedNavbar = $mainNav.hasClass('navbar-fixed'),
            isNoBgNavbar = $mainNav.hasClass('no-background'),
            isTransNavbar = $mainNav.hasClass('navbar-transparent'),

            $mainNav_height = $mainNav.height(),
            $mainNav_nextSection = $mainNav.parent().next();

        // bottom nav
        if ($mainNavSticky) {
            var $_bottom_nav = $mainNav.parent('header'),
                $_bottom_navBanner = $_bottom_nav.prev($jsFullHeight),
                $_bottom_bannerHeight = height - $mainNav_height;

            $_bottom_navBanner.height($_bottom_bannerHeight);
        } else {
            if (isFixedNavbar && !isNoBgNavbar || !isTransNavbar) {
                var _height = height - $mainNav_height;

                $jsFullHeight.height(_height);
                $mainNav_nextSection.css('margin-top', $mainNav_height);
            }

            if (isFixedNavbar && isNoBgNavbar || isTransNavbar) {
                if (width < 1024) {
                    var _xs_height = height - $mainNav_height;

                    $mainNav_nextSection.css('margin-top', $mainNav_height);
                    $jsFullHeight.height(_xs_height);
                } else {
                    $jsFullHeight.height(height);

                    $mainNav_nextSection.css('margin-top', '0');
                }
            }
        }

    });

    $window.trigger('resize.windowscreen');


    /*==============================================
    Switch init
    ===============================================*/
    $('.js-Switch').each(function() {
        new Switchery(this, $(this).data());
    })


    /*==============================================
    Parallax
    ===============================================*/
    $('.ImageBackground').each(function() {
        var $this = $(this),
            $imgHolder = $this.children('.ImageBackground__holder'),
            thisIMG = $imgHolder.children().attr('src'),
            thisURL = 'url(' + thisIMG + ')';

        if ($this.hasClass('js-Parallax')) {
            $imgHolder.attr("data-image-src", thisIMG);
        } else {
            $imgHolder.css('background-image', thisURL);
        }
    });

    $('.js-Parallax .ImageBackground__holder').parallax({
        zIndex: 1,
        speed: 0.4
    });

    $window.trigger('resize').trigger('scroll');



    /*==============================================
    CountTo init
    ===============================================*/
    $('.js-CountTo').each(function() {
        var $this = $(this),
            firstLoadCountEvent = true;

        $this.waypoint(function() {
            if (firstLoadCountEvent) {
                $this.countTo({
                    speed: 2000,
                    refreshInterval: 50,
                    formatter: function(value, options) {
                        value = value.toFixed(options.decimals);
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return value;
                    }
                });
                firstLoadCountEvent = false;
            }
        }, {
            offset: '90%'
        });
    });


    /*==============================================
    Owl carousel init
    ===============================================*/
    $('.js-OwlCarousel').owlCarousel({
        items: 1,
        loop: true,
        margin: 0,
        nav: true,
        navText: ["", ""]
    });

    $('.js-OwlCarousel2').owlCarousel({
        margin: 40,
        nav: true,
        navText: ["", ""],
        slideBy: 1,
        responsive: {
            0: { items: 1 },
            480: { items: 2 }
        }
    });

    $('.js-OwlCarousel3').owlCarousel({
        margin: 40,
        nav: true,
        navText: ["", ""],
        slideBy: 1,
        responsive: {
            0: { items: 1 },
            480: { items: 2 },
            768: { items: 3 }
        }
    });

    $('.js-OwlCarousel4').owlCarousel({
        margin: 40,
        nav: true,
        navText: ["", ""],
        slideBy: 1,
        responsive: {
            0: { items: 1 },
            480: { items: 2 },
            768: { items: 3 },
            992: { items: 4 }
        }
    });

    $('.js-OwlCarousel5').owlCarousel({
        margin: 40,
        nav: true,
        navText: ["", ""],
        slideBy: 1,
        responsive: {
            0: { items: 2 },
            480: { items: 2 },
            768: { items: 3 },
            992: { items: 5 }
        }
    });


    /*==============================================
    Progressbar init
    ===============================================*/
    $('.progress').each(function() {
        var dataParcent = $(this).attr('data-percent'),
            progressTitle,
            progressTitle__outer = $(this).prev('.progress-title'),
            progressTitle__inner = $(this).children('.progress-title');

        if (progressTitle__outer.length > 0) {
            progressTitle = progressTitle__outer.css('width', dataParcent)
        } else if (progressTitle__inner.length > 0) {
            progressTitle = progressTitle__inner.css('width', dataParcent)
        }

        $(this).appear(function() {
            $(this).find('.progress-bar').animate({
                width: dataParcent
            }, 500);
        });
    });


    // /*==============================================
    //  Portfolio grid init
    //  ===============================================*/
    // var $alienPortfolio = $('.js-Portfolio');

    // $alienPortfolio = $('.js-Portfolio').isotope({
    //     itemSelector: '.portfolio-item',
    //     filter: '*'
    // });

    // if ($.fn.imagesLoaded) {
    //     $alienPortfolio.imagesLoaded().progress(function() {
    //         $alienPortfolio.isotope('layout');
    //     });
    // }

    // $('.js-PortfolioFilter').on('click focus', 'a', function(e) {
    //     var $this = $(this);
    //     e.preventDefault();
    //     $this.parent().addClass('active').siblings().removeClass('active');
    //     $alienPortfolio.isotope({ filter: $this.data('filter') });
    // });


    /*==============================================
    Portfolio popup
    ===============================================*/
    $(".portfolio-gallery").each(function() {
        $(this).find(".popup-gallery").magnificPopup({
            type: "image",
            gallery: {
                enabled: true
            }
        });
    });

    $(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
        disableOn: 700,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });


    /*==============================================
    Returns height of browser viewport
    ===============================================*/
    $('.ScrollTo').on('click', function(e) {
        e.preventDefault();
        var element_id = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(element_id).offset().top - 60
        }, 500);
    });

})(jQuery);

;
(function() {
    'use strict';

    var swiper = new Swiper('.swiper-container', {
        // Optional parameters
        effect: 'fade',
        speed: 1000,
        loop: true,
        autoplay: 4000,
        nested: true,
        parallax: true,

        pagination: '.swiper-button-next, .swiper-button-prev',
        paginationClickable: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationType: 'custom',
        paginationCustomRender: function(swiper, current, total) {
            return '<div class="swiperCount">' +
                '<span class="swiperCount-current">' + current + '</span>' +
                '<i class="swiperCount-devider"></i>' +
                '<span class="swiperCount-total">' + total + '</span>' +
                '</div>';
        },
        onSlideChangeStart: function(s) {

            var activeSlide = document.querySelector(".swiper-slide-active");
            var swiperControl = document.querySelectorAll(".swiper-control");
            var colorScheme = activeSlide.dataset.scheme.toLowerCase();


            // animated css via data-animate="aminate-name"
            var currAnimateItems = activeSlide.querySelectorAll("[data-animate]");

            swiperControl.forEach(function(control) {
                control.dataset.scheme = colorScheme;
            });

            Array.prototype.forEach.call(currAnimateItems, function(item) {
                var dataAnimateName = item.getAttribute("data-animate");
                item.classList.add(dataAnimateName);
                item.classList.add("animated");
            });

            // trigger unactive sliders
            // for remove animate
            var unActiveSlide = document.querySelectorAll(".swiper-slide:not(.swiper-slide-active)");

            unActiveSlide.forEach(function(unactive) {
                var _animateItems = unactive.querySelectorAll("[data-animate]");
                Array.prototype.forEach.call(_animateItems, function(_item) {
                    var _dataAnimateName = _item.getAttribute("data-animate");
                    _item.classList.remove(_dataAnimateName);
                });
            });

        }
    });

})();
$(document).ready(function() {


    //  price_table
    $(".bs_btn").hover(
        function() {
            $(".bs").addClass("bs_bg");
        },
        function() {
            $(".bs").removeClass("bs_bg");
        }
    );
    $(".st_btn").hover(
        function() {
            $(".st").addClass("bs_bg");
        },
        function() {
            $(".st").removeClass("bs_bg");
        }
    );
    $(".en_btn").hover(
        function() {
            $(".en").addClass("bs_bg");
        },
        function() {
            $(".en").removeClass("bs_bg");
        }
    );


    function icon_hide() {
        // driver
        $('#driver_screen_1').find('i').hide();
        $('#driver_screen_2').find('i').hide();
        $('#driver_screen_3').find('i').hide();
        $('#driver_screen_4').find('i').hide();
        // user
        $('#user_screen_1').find('i').hide();
        $('#user_screen_2').find('i').hide();
        $('#user_screen_3').find('i').hide();
        $('#user_screen_4').find('i').hide();
    }

    icon_hide();



    // var style = document.createElement('#fade_img');
    // style.innerHTML =
    //     '.some-element {' +
    //     '-webkit-animation-name: fadeInLeft;' +
    //     '-moz-animation-name: fadeInLeft;' +
    //     '-o-animation-name: fadeInLeft;' +
    //     'animation-name: fadeInLeft;' +
    //     '-webkit-animation-fill-mode: both;' +
    //     '-moz-animation-fill-mode: both;' +
    //     ' -o-animation-fill-mode: both;' +
    //     'animation-fill-mode: both;' +
    //     '-webkit-animation-duration: 1s;' +
    //     ' -moz-animation-duration: 1s;' +
    //     '-o-animation-duration: 1s;' +
    //     'animation-duration: 1s;' +
    //     '-webkit-animation-delay: 1s;' +
    //     '-moz-animation-delay: 1s;' + '-o-animation-duration: 1s;' + ' animation-delay: 1s;' +
    //     '}';










    $('#driver_screen_1').hover(
        function() {
            $(this).addClass('clicked_1');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_1.png');
        }
    );
    $('#driver_screen_2').hover(
        function() {
            $(this).addClass('clicked_2');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_2.png');
        }
    );
    $('#driver_screen_3').hover(
        function() {
            $(this).addClass('clicked_3');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_3.png');
        }
    );
    $('#driver_screen_4').hover(
        function() {
            $(this).addClass('clicked_4');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_4.png');
        }
    );

    // user

    $('#user_screen_1').hover(
        function() {
            $(this).addClass('clicked_1');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_1.png');
        }
    );
    $('#user_screen_2').hover(
        function() {
            $(this).addClass('clicked_2');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_2.png');
        }
    );
    $('#user_screen_3').hover(
        function() {
            $(this).addClass('clicked_3');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_3.png');
        }
    );
    $('#user_screen_4').hover(
        function() {
            $(this).addClass('clicked_4');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_4.png');
        }
    );





    // /driver click
    $('#driver_screen_1').click(
        function() {
            $(this).addClass('clicked_1');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_1.png');
        }
    );
    $('#driver_screen_2').click(
        function() {
            $(this).addClass('clicked_2');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_2.png');
        }
    );
    $('#driver_screen_3').click(
        function() {
            $(this).addClass('clicked_3');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_3.png');
        }
    );
    $('#driver_screen_4').click(
        function() {
            $(this).addClass('clicked_4');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/driver_4.png');
        }
    );

    // user

    $('#user_screen_1').click(
        function() {
            $(this).addClass('clicked_1');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_1.png');
        }
    );
    $('#user_screen_2').click(
        function() {
            $(this).addClass('clicked_2');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_2.png');
        }
    );
    $('#user_screen_3').click(
        function() {
            $(this).addClass('clicked_3');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_3.png');
        }
    );
    $('#user_screen_4').click(
        function() {
            $(this).addClass('clicked_4');
            icon_hide();
            $(this).find('i').show();
            $('.box img').attr('src', 'assets/imgs/screen/user_4.png');
        }
    );
    //paste this code under the head tag or in a separate js file.
    // Wait for window load


});

$(window).load(function() {
    // Animate loader off screen
    $(".se-pre-con").fadeOut("slow");
});
$(window).ready(function() {
    $(".animate_1").addClass("in-right");
    $(".animate_2").addClass("in-right_1");
    $(".animate_3").addClass("in-right_2");
    $(".animate_left").addClass("in-left");


});
var TxtType = function(el, toRotate, period) {
    this.toRotate = toRotate;
    this.el = el;
    this.loopNum = 0;
    this.period = parseInt(period, 10) || 2000;
    this.txt = '';
    this.tick();
    this.isDeleting = false;
};

TxtType.prototype.tick = function() {
    var i = this.loopNum % this.toRotate.length;
    var fullTxt = this.toRotate[i];

    if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
    } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
    }

    this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

    var that = this;
    var delta = 200 - Math.random() * 100;

    if (this.isDeleting) { delta /= 2; }

    if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
    } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
    }

    setTimeout(function() {
        that.tick();
    }, delta);
};

window.onload = function() {
    var elements = document.getElementsByClassName('typewrite');
    for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute('data-type');
        var period = elements[i].getAttribute('data-period');
        if (toRotate) {
            new TxtType(elements[i], JSON.parse(toRotate), period);
        }
    }
    // INJECT CSS
    var css = document.createElement("style");
    css.type = "text/css";
    css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
    document.body.appendChild(css);
};