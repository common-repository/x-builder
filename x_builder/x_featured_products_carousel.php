<?php
/**
 *
 * @var $params
 * @var $name
 * @var $module
 * @var $title
 * @var $posts
 * @var $custom_css
 */

$params = stm_x_builder_get_params($module, $params);
extract($params);
$classes = array();
$module_id = stm_x_builder_module_id($module, $params);
$classes[] = $module_id;
$inline_styles = (empty($custom_css)) ? '' : $custom_css;

$transient = get_transient($module_id);

wp_enqueue_script('imagesloaded');
stm_x_builder_register_style('timer', array());
stm_x_builder_register_style($module, array(), $inline_styles);
stm_x_builder_register_script('timer', array('vue.js'));

stm_x_builder_register_script($module, array('vue.js', 'vue-resource.js'), '', $module_id);

?>

<div class="x_featured_products_carousel <?php echo esc_attr(implode(' ', $classes)); ?>"
     data-v-on_mouseover="hover=true"
     data-module="<?php echo esc_attr($module_id); ?>">
    <h3 class="title"><?php echo wp_kses_post($title); ?></h3>
    <div class="x_loader_wrapper" data-v-if="loading">
        <div class="x_loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="x_featured_products_carousel__inner owl-carousel" data-v-if="pages" data-module="<?php echo esc_attr($module_id); ?>">
        <div class="x_featured_products_carousel__page" data-v-for="(page, page_index) in pages">

            <div class="x_featured_products_carousel__rows" data-v-if="page">
                <div class="x_featured_products_carousel__row" data-v-for="(row, row_index) in rows"
                     data-v-bind_class="'x_featured_products_carousel__row' + row_index">
                    <div class="x_featured_products_carousel__products">

                        <div
                           class="x_featured_products_carousel__single x_product_buttons_wrapper x_woo_image_wrapper"
                           data-v-for="product_index in row" data-v-if="page[product_index]">

                            <div class="x_featured_products_carousel__single_inner">

                                <a data-v-bind_href="page[product_index].permalink" class="x_featured_products_carousel__single_image">

                                    <div class="x_woo_image_hover" data-v-if="page[product_index].gallery">
                                        <img src="#"
                                             alt="<?php esc_attr_e('Product Image', 'x-builder'); ?>"
                                             data-v-bind_src="page[product_index].image"
                                             data-v-bind_alt="page[product_index].title"/>
                                        <img src="#"
                                             alt="<?php esc_attr_e('Product Image', 'x-builder'); ?>"
                                             data-v-bind_src="page[product_index].gallery"
                                             data-v-if="hover"
                                             data-v-bind_alt="page[product_index].title"/>
                                    </div>

                                    <img src="#"
                                         alt="<?php esc_attr_e('Product Image', 'x-builder'); ?>"
                                         data-v-else
                                         data-v-bind_src="page[product_index].image"
                                         data-v-bind_alt="page[product_index].title"/>

                                </a>

                                <div class="x_featured_products_carousel__single_content">

                                    <a data-v-bind_href="page[product_index].permalink">
                                        <h6 class="x_featured_products_carousel__single_title"
                                            data-v-html="page[product_index].title"></h6>
                                    </a>

                                    <div class="x_average_rating_unit">
                                        <div class="x_star-rating">
                                            <span data-v-bind_style="'width:' + page[product_index].rating_percent + '%'">Rated <strong class="rating">{{ page[product_index].rating }}</strong> out of 5</span>
                                        </div>
                                    </div>

                                    <div class="x_featured_products_carousel__single_brand" data-v-if="page[product_index].brands">{{ page[product_index]['brands'] }}</div>

                                    <div class="x_featured_products_carousel__single_price">
                                        <span class="price" data-v-html="page[product_index].price"></span>
                                        <span class="regular_price" data-v-html="page[product_index].regular_price"
                                              data-v-if="page[product_index].sale_price"></span>
                                    </div>

                                </div>

                                <a data-v-bind_href="'?add-to-cart=' + page[product_index].id"
                                   data-quantity="1"
                                   data-v-bind_data-product_id="page[product_index].id"
                                   rel="nofollow"
                                   class="button product_type_simple add_to_cart_button ajax_add_to_cart"><i class="lnricons-cart"></i>Add to cart</a>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
