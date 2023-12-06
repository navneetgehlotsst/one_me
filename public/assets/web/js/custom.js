/*
Template Name: Osahan Deel - Coupons, Deals & Discounts HTML Template
Author: Askbootstrap
Author URI: https://themeforest.net/user/askbootstrap
Version: 1.0
*/

(function ($) {
    "use strict"; // Start of use strict

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

    $(function () {
        $("body").on("input propertychange", ".floating-label-form-group", function (e) {
            $(this).toggleClass("floating-label-form-group-with-value", !!$(e.target).val());
        }).on("focus", ".floating-label-form-group", function () {
            $(this).addClass("floating-label-form-group-with-focus");
        }).on("blur", ".floating-label-form-group", function () {
            $(this).removeClass("floating-label-form-group-with-focus");
        });
    });

    var oneobjowlcarousel = $(".owl-carousel-one");
    if (oneobjowlcarousel.length > 0) {
        oneobjowlcarousel.owlCarousel({
            items: 1,
            lazyLoad: true,
            pagination: false,
            loop: true,
            dots: false,
            autoPlay: 2000,
            nav: true,
            stopOnHover: true,
            navText: ["<i class='icofont-thin-left'></i>", "<i class='icofont-thin-right'></i>"]
        });
    }

    var objowlcarousel = $(".owl-carousel-two");
    if (objowlcarousel.length > 0) {
        objowlcarousel.owlCarousel({
            items: 2,
            lazyLoad: true,
            pagination: false,
            loop: true,
            dots: false,
            autoPlay: 2000,
            margin: 15,
            nav: true,
            stopOnHover: true,
            navText: ["<i class='icofont-thin-left'></i>", "<i class='icofont-thin-right'></i>"],
            responsive: {
                0: {
                    items: 1
                },
                479: {
                    items: 1
                },
                768: {
                    items: 1
                },
                979: {
                    items: 2
                },
                1199: {
                    items: 2
                }
            }
        });
    }

    var threeobjowlcarousel = $(".owl-carousel-three");
    if (threeobjowlcarousel.length > 0) {
        threeobjowlcarousel.owlCarousel({
            items: 3,
            lazyLoad: true,
            pagination: false,
            loop: true,
            dots: false,
            autoPlay: 2000,
            margin: 15,
            nav: true,
            stopOnHover: true,
            navText: ["<i class='icofont-thin-left'></i>", "<i class='icofont-thin-right'></i>"],

            responsive: {
                0: {
                    items: 1
                },
                479: {
                    items: 1
                },
                768: {
                    items: 2
                },
                979: {
                    items: 3
                },
                1199: {
                    items: 3
                }
            }
        });
    }

    var fiveobjowlcarousel = $(".owl-carousel-four");
    if (fiveobjowlcarousel.length > 0) {
        fiveobjowlcarousel.owlCarousel({
            items: 4,
            lazyLoad: true,
            pagination: false,
            loop: true,
            dots: false,
            margin: 15,
            autoPlay: 2000,
            nav: true,
            stopOnHover: true,
            navText: ["<i class='icofont-thin-left'></i>", "<i class='icofont-thin-right'></i>"],
            responsive: {
                0: {
                    items: 1
                },
                479: {
                    items: 1
                },
                768: {
                    items: 2
                },
                979: {
                    items: 4
                },
                1199: {
                    items: 4
                }
            }

        });
    }

    var fiveobjowlcarousel = $(".owl-carousel-five");
    if (fiveobjowlcarousel.length > 0) {
        fiveobjowlcarousel.owlCarousel({
            items: 5,
            lazyLoad: true,
            pagination: false,
            loop: true,
            dots: false,
            autoPlay: 2000,
            margin: 15,
            nav: true,
            stopOnHover: true,
            navText: ["<i class='icofont-thin-left'></i>", "<i class='icofont-thin-right'></i>"],
            responsive: {
                0: {
                    items: 1
                },
                479: {
                    items: 1
                },
                768: {
                    items: 2
                },
                979: {
                    items: 4
                },
                1199: {
                    items: 5
                }
            }

        });
    }

    var restaurantslider = $(".restaurant-slider");
    if (restaurantslider.length > 0) {
        restaurantslider.owlCarousel({
            items: 1,
            lazyLoad: true,
            pagination: false,
            loop: true,
            dots: false,
            autoPlay: 2000,
            nav: true,
            stopOnHover: true,
            navText: ["<i class='icofont-thin-left'></i>", "<i class='icofont-thin-right'></i>"]
        });
    }

    // Select all links with hashes
    $('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function (event) {
            // On-page links
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                location.hostname == this.hostname
            ) {
                // Figure out element to scroll to
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                // Does a scroll target exist?
                if (target.length) {
                    // Only prevent default if animation is actually gonna happen
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000, function () {
                        // Callback after animation
                        // Must change focus!
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) { // Checking if the target was focused
                            return false;
                        } else {
                            $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                            $target.focus(); // Set focus again
                        };
                    });
                }
            }
        });

    // ===========Select2============
    $('select').select2();

    // ===========Tooltip============
    $('[data-toggle="tooltip"]').tooltip();
    
    
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-120909275-1', 'auto');
ga('send', 'pageview');

})(jQuery); // End of use strict

$(document).ready(function () {
    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');
  
    allWells.hide();
  
    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);
  
        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });
  
    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;
  
        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }
  
        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });
  
    $('div.setup-panel div a.btn-primary').trigger('click');
  });

  const inputs = document.querySelectorAll(".otp-field > input");
const button = document.querySelector(".btn");

window.addEventListener("load", () => inputs[0].focus());
button.setAttribute("disabled", "disabled");

inputs[0].addEventListener("paste", function (event) {
  event.preventDefault();

  const pastedValue = (event.clipboardData || window.clipboardData).getData(
    "text"
  );
  const otpLength = inputs.length;

  for (let i = 0; i < otpLength; i++) {
    if (i < pastedValue.length) {
      inputs[i].value = pastedValue[i];
      inputs[i].removeAttribute("disabled");
      inputs[i].focus;
    } else {
      inputs[i].value = ""; // Clear any remaining inputs
      inputs[i].focus;
    }
  }
});

inputs.forEach((input, index1) => {
  input.addEventListener("keyup", (e) => {
    const currentInput = input;
    const nextInput = input.nextElementSibling;
    const prevInput = input.previousElementSibling;

    if (currentInput.value.length > 1) {
      currentInput.value = "";
      return;
    }

    if (
      nextInput &&
      nextInput.hasAttribute("disabled") &&
      currentInput.value !== ""
    ) {
      nextInput.removeAttribute("disabled");
      nextInput.focus();
    }

    if (e.key === "Backspace") {
      inputs.forEach((input, index2) => {
        if (index1 <= index2 && prevInput) {
          input.setAttribute("disabled", true);
          input.value = "";
          prevInput.focus();
        }
      });
    }

    button.classList.remove("active");
    button.setAttribute("disabled", "disabled");

    const inputsNo = inputs.length;
    if (!inputs[inputsNo - 1].disabled && inputs[inputsNo - 1].value !== "") {
      button.classList.add("active");
      button.removeAttribute("disabled");

      return;
    }
  });
});