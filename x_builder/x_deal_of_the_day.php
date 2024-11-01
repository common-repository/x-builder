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

$template = (empty($template)) ? 1 : intval($template);
if($template != 1) $classes[] = 'x-style-'.$template;

$transient = get_transient($module_id);
if($transient) {
    foreach( $transient as $key => $item ) {
        $_product = wc_get_product($item['id']);
        if($template == 2) {
            $transient[$key]['image'] = esc_url( stm_x_builder_get_cropped_image_url( get_post_thumbnail_id($item['id']), 300, 300 ) );
        }
        $transient[$key]['rating'] = $_product->get_average_rating();
        $transient[$key]['rating_percent'] = (floatval($_product->get_average_rating()) / 5) * 100;
    }
}


wp_enqueue_style('owl-carousel');
wp_enqueue_script('imagesloaded');
stm_x_builder_register_style($module, array(), $inline_styles);
stm_x_builder_register_script('timer', array('vue.js'));
stm_x_builder_register_script($module, array('vue.js', 'vue-resource.js', 'owl-carousel'), '', $module_id, array(
    'posts' => $posts,
    'transient' => $transient,
));

if($template == 1):
?>
<div class="x_deal_of_the_day <?php echo esc_attr(implode(' ', $classes)); ?>"
     data-module="<?php echo esc_attr($module_id); ?>">
    <h3 class="text-center title"><?php echo sanitize_text_field($title); ?></h3>
    <div class="sep"></div>
    <div class="x_loader_wrapper" data-v-if="!products.length">
        <div class="x_loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="x_deal_of_the_day__posts owl-carousel" data-v-if="products.length">

        <a data-v-bind_href="product.permalink" class="x_deal_of_the_day__single" data-v-for="product in products">
            <div class="x_deal_of_the_day__single_inner">

                <div class="x_deal_of_the_day__single_timer heading_font" data-v-if="product.sale_to">
                    <div data-vue-role="Timer" data-v-bind_starttime="product.sale_to"
                         data-v-bind_endtime="product.sale_to"
                         data-vue-trans='{"day":"Day","hours":"Hours","minutes":"Minutes","seconds":"Seconds"}'>
                    </div>
                </div>
                <div class="timer_holder" data-v-else></div>

                <div class="x_deal_of_the_day__single_image">
                    <img src="#" data-v-bind_src="product.image" data-v-bind_alt="product.title"
                         alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"/>

                </div>

                <div class="x_deal_of_the_day__single_content">

                    <div class="x_deal_of_the_day__single_brand" data-v-if="product.brands">{{product['brands']}}</div>

                    <h6 class="x_deal_of_the_day__single_title" data-v-html="product.title"></h6>

                    <div class="x_deal_of_the_day__single_price">
                        <span class="regular_price" data-v-html="product.regular_price"
                              data-v-if="product.sale_price"></span>
                        <span class="price" data-v-html="product.price"></span>
                    </div>

                </div>

            </div>
        </a>

    </div>
</div>

<?php elseif($template == 2): ?>

    <div class="x_deal_of_the_day <?php echo esc_attr(implode(' ', $classes)); ?>"
         data-module="<?php echo esc_attr($module_id); ?>">
        <h3 class="title"><?php echo sanitize_text_field($title); ?></h3>

        <div class="x_loader_wrapper" data-v-if="!products.length">
            <div class="x_loader">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <div class="x_owl_nav_wrap" data-v-if="products.length">
            <div class="x_owl_nav"><span class="prev"></span> <span class="next"></span></div>
        </div>

        <div class="x_deal_of_the_day__posts owl-carousel" data-v-if="products.length">

            <div data-v-bind_href="product.permalink" class="x_deal_of_the_day__single" data-v-for="product in products">
                <div class="x_deal_of_the_day__single_inner">

                    <div class="x_deal_of_the_day__single_image">
                        <img src="#" data-v-bind_src="product.image" data-v-bind_alt="product.title"
                             alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"/>

                    </div>

                    <div class="x_deal_of_the_day__single_content">

                        <h6 class="x_deal_of_the_day__single_title" data-v-html="product.title"></h6>

                        <div class="x_deal_of_the_day__single_brand" data-v-if="product.brands">{{product['brands']}}</div>

                    </div>

                    <div class="x_average_rating_unit">
                        <div class="x_star-rating">
                            <span data-v-bind_style="'width:' + product.rating_percent + '%'">Rated <strong class="rating">{{ product.rating }}</strong> out of 5</span>
                        </div>
                    </div>

                    <div class="x_deal_of_the_day__single_timer heading_font" data-v-if="product.sale_to">
                        <div class="x_deal_of_the_day__single_timer_label">Limited time</div>
                        <div data-vue-role="Timer" data-v-bind_starttime="product.sale_to"
                             data-v-bind_endtime="product.sale_to"
                             data-vue-trans='{"day":"Day","hours":"Hours","minutes":"Minutes","seconds":"Seconds"}'>
                        </div>
                    </div>
                    <div class="timer_holder" data-v-else></div>

                    <div class="x_deal_of_the_day__price_holder">
                        <div class="x_deal_of_the_day__single_price">
                            <span class="regular_price" data-v-html="product.regular_price"
                            data-v-if="product.sale_price"></span>
                            <span class="price" data-v-html="product.price"></span>
                        </div>

                        <a data-v-bind_href="'?add-to-cart=' + product.id"
                           data-quantity="1"
                           data-v-bind_data-product_id="product.id"
                           rel="nofollow"
                           class="btn btn-primary button product_type_simple add_to_cart_button ajax_add_to_cart">Add to cart</a>

                    </div>

                </div>
            </div>

        </div>
    </div>

<?php endif; ?>
