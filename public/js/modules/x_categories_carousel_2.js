"use strict";

(function ($) {
  $(document).ready(function () {
    $('.x_categories_carousel_2').each(function () {
      var $this = $(this);
      var module_id = $(this).data('module');
      new VueW3CValid({
        el: ".".concat(module_id)
      });
      new Vue({
        el: $this[0],
        data: function data() {
          return {
            data: window[module_id],
            categories: []
          };
        },
        mounted: function mounted() {
          this.getCategories();
        },
        methods: {
          getCategories: function getCategories() {
            var _this = this;

            _this.loading = true;

            _this.$http.post("".concat(x_ajax_url, "?action=x_get_product_categories_2"), _this.data).then(function (r) {
              _this.$set(_this, 'categories', r.body);

              _this.carousel();
            });
          },
          carousel: function carousel() {
            var _this = this;

            Vue.nextTick().then(function () {
              _this.loading = true;
              var $carousel = $(".".concat(module_id, " .x_categories_carousel_2__items"));

              if (_this.categories.length > 1) {
                $carousel.imagesLoaded(function () {
                  _this.loading = false;
                  $carousel.owlCarousel({
                    loop: true,
                    nav: true,
                    dots: false,
                    margin: 15,
                    autoplay: false,
                    autoplayHoverPause: true,
                    responsive: {
                      0: {
                        items: 1,
                        margin: 0
                      },
                      767: {
                        items: 2,
                        margin: 30
                      },
                      1024: {
                        items: 3,
                        margin: 30
                      }
                    }
                  });
                });
              } else {
                $carousel.removeClass('owl-carousel');
                _this.loading = false;
              }
            });
          }
        }
      });
    });
  });
})(jQuery);