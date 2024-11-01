"use strict";

(function ($) {
  $(document).ready(function () {
    $('.x_featured_products_carousel').each(function () {
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
            posts: [],
            "transient": [],
            products: [],
            pages: [],
            rows: {
              1: [0],
              2: [1, 2, 3, 4]
            },
            loading: false,
            hover: false
          };
        },
        mounted: function mounted() {
          this.getProducts();
        },
        methods: {
          getProducts: function getProducts() {
            var _this = this;

            _this.loading = true;

            _this.$http.post("".concat(x_ajax_url, "?action=x_get_featured_products_carousel&module_id=").concat(module_id), _this.posts).then(function (r) {
              _this.$set(_this, 'pages', r.body);

              _this.carousel();

              _this.loading = false;
            });
          },
          carousel: function carousel() {
            var _this = this;

            Vue.nextTick().then(function () {
              _this.loading = true;
              var $carousel = $(".".concat(module_id, " .x_featured_products_carousel__inner"));

              if (_this.pages) {
                $carousel.imagesLoaded(function () {
                  _this.loading = false;
                  $carousel.owlCarousel({
                    loop: false,
                    nav: false,
                    dots: false,
                    autoplay: false,
                    items: 1
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