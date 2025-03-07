"use strict";

(function ($) {
  $(document).ready(function () {
    $('.x_autoparty_products_carousel_inner').each(function () {
      var $carousel = $(this);
      var module_id = $(this).attr('data-module');
      var $carousel_prev = $(".".concat(module_id)).find('.prev');
      var $carousel_next = $(".".concat(module_id)).find('.next');

      if ($carousel[0].childElementCount > 1) {
        $carousel.owlCarousel({
          loop: false,
          nav: false,
          dots: false,
          autoplay: false,
          items: 1,
          margin: 15
        });
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