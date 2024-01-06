///////////////*****//////////////////
// RESPONSIVE NAVIGATION
// OPEN BTN
var cart = [];
$(document).ready(function() {
  $("#navbar").on("click", function() {
      $(".nveMenu").addClass("is-opened");
      $(".overlay").addClass("is-on");
  });

  $(".overlay").on("click", function() {
      $(this).removeClass("is-on");
      $(".nveMenu").removeClass("is-opened");
  });
});
// CLOSE BTN
$(".overlay").on("click", function() {
  $(this).removeClass("is-on");
  $(".nveMenu").removeClass("is-opened");
});

$(".close-btn-nav").click(function() {
  $(".nveMenu").removeClass("is-opened");
  $(".overlay").removeClass("is-on");
});
// RESPONSIVE NAVIGATION
// 
// ACTIVE JS START
$(document).ready(function() {
  $('ul li span').click(function() {
      $('li span').removeClass("active-class");
      $(this).addClass("active-class");
  });
});
// ACTIVE JS END
// 
// PRELOADER START
$(document).ready(function() {
  setTimeout(function() {
      $('.preloader').fadeOut('slow');
  }, 2000);
});
// PRELOADER END
///////////////*****//////////////////
$('.equipment').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 3
        },
        1000: {
            items: 4
        }
    }
  });
$('.product-main').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
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
  $('.beverage').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 3
        },
        1000: {
            items: 4
        }
    }
  });
$('.about-slider').owlCarousel({
  loop: true,
  margin: 10,
  nav: false,
  dots: true,
  responsive: {
      0: {
          items: 1
      },
      600: {
          items: 5
      },
      1000: {
          items: 6
      }
  }
});

$(document).ready(function() {
  $('.bf-testimonial-slick').slick({
      pauseOnHover: true,
      autoplay: false,
      arrows: false,
      dots: false,
      autoplayspeed: 2000,
      speed: 1000,

      centerMode: true,
      centerPadding: '20%',
      slidesToShow: 1,
      slidesToScroll: 1,

      draggable: true,
      responsive: [{
          breakpoint: 991,
          settings: {
              slidesToShow: 1,
          }

      }]

  });
});

function googleTranslateElementInit() {
  new google.translate.TranslateElement({
          pageLanguage: "en, ar"
      },
      "google_translate_element"
  );
  if (typeof document.querySelector == "function") {
      document
          .querySelector(".goog-logo-link")
          .setAttribute("style", "display: none");
      document
          .querySelector(".goog-te-gadget")
          .setAttribute("style", "font-size: 0");
  }
};
$('.slider-for').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  loop: true,
  fade: true,
  asNavFor: '.slider-nav',
  responsive: [{
          breakpoint: 1024,
          settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
          }
      },
      {
          breakpoint: 600,
          settings: {
              slidesToShow: 3,
              slidesToScroll: 2
          }
      },
  ]
});
$('.slider-nav').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  asNavFor: '.slider-for',
  dots: false,
  loop: true,
  arrows: false,
  centerMode: false,
  focusOnSelect: true,
  responsive: [{
          breakpoint: 1024,
          settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
          }
      },
      {
          breakpoint: 600,
          settings: {
              slidesToShow: 3,
              slidesToScroll: 2
          }
      },
  ]
});
$(document).ready(function() {

  var quantitiy = 0;
  $('.quantity-right-plus').click(function(e) {

      // Stop acting like a button
      e.preventDefault();
      // Get the field name
      var quantity = parseInt($('#quantity').val());

      // If is not undefined

      $('#quantity').val(quantity + 1);


      // Increment

  });

  $('.quantity-left-minus').click(function(e) {
      // Stop acting like a button
      e.preventDefault();
      // Get the field name
      var quantity = parseInt($('#quantity').val());

      // If is not undefined

      // Increment
      if (quantity > 0) {
          $('#quantity').val(quantity - 1);
      }
  });

  setTimeout(() => {

      // alert($('.VIpgJd-ZVi9od-l4eHX-hSRGPd').parent().parent().replace('Powered by ,',''));

      $('.VIpgJd-ZVi9od-l4eHX-hSRGPd').parent().remove();


      $(".goog-te-combo option").each(function() {

          console.log($(this).val())
          // Add $(this).val() to your list
      });




  }, 500);



});