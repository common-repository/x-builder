"use strict";

(function ($) {
  $(document).ready(function () {
    $('.x_products_carousel_inner').each(function () {
      var $carousel = $(this);
      var module_id = $(this).attr('data-module');
      var $carousel_prev = $(".".concat(module_id)).find('.prev');
      var $carousel_next = $(".".concat(module_id)).find('.next');
      var style = $(".".concat(module_id)).closest('.x_products_carousel').data('style');
      var carousel_options = {
        loop: false,
        nav: false,
        dots: false,
        autoplay: false,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 2
          },
          768: {
            items: 3
          },
          1024: {
            items: 4
          }
        }
      };

      if (style == 'style_2') {
        carousel_options.loop = true;
        carousel_options.responsive = {
          0: {
            items: 1
          },
          400: {
            items: 2
          },
          768: {
            items: 3
          },
          1024: {
            items: 4
          },
          1200: {
            items: 5
          },
          1500: {
            items: 6
          }
        };
      }

      if ($carousel[0].childElementCount > 1) {
        $carousel.owlCarousel(carousel_options);
        $carousel_prev.click(function () {
          $carousel.trigger('prev.owl.carousel');
        });
        $carousel_next.click(function () {
          $carousel.trigger('next.owl.carousel');
        });
      }
    });
  });
})(jQuery);