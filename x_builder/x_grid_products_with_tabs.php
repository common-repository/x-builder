<?php
/**
 *
 * @var $params
 * @var $name
 * @var $module
 * @var $title
 * @var $categories
 * @var $latest
 * @var $sale
 * @var $last_chance
 * @var $recently_viewed
 * @var $per_row
 * @var $per_row_tablet_horizontal
 * @var $per_row_tablet_vertical
 * @var $per_row_tablet_mobile
 * @var $total
 * @var $product_view
 * @var $carousel
 * @var $custom_css
 */


$params = stm_x_builder_get_params($module, $params);
extract($params);


$classes = array();
$module_id = stm_x_builder_module_id($module, $params);
$classes[] = $module_id;
$classes[] = ($carousel) ? 'x-is-carousel' : '';
$classes[] = $module . "_" . $style;
$inline_styles = (empty($custom_css)) ? '' : $custom_css;

$per_row = (empty($per_row)) ? 4 : $per_row;

$categories = (!empty($categories)) ? $categories : array();

if ($latest) {
    array_unshift($categories, array(
        'name' => sprintf ( esc_html__( '%sLatest%s Products', 'x-builder' ), '<span>', '</span>'),
        'term_id' => 'latest'
    ));
}

if ($sale) {
    array_unshift($categories, array(
        'name' => sprintf ( esc_html__( '%sAll%s', 'x-builder' ), '<span>', '</span>'),
        'term_id' => 'sale'
    ));
}

if ($last_chance) {
    array_unshift($categories, array(
        'name' => sprintf ( esc_html__( '%sLast%s chance to buy', 'x-builder' ), '<span>', '</span>'),
        'term_id' => 'last_chance'
    ));
}

if ($recently_viewed) {
    array_unshift($categories, array(
        'name' => esc_html__('Recently Viewed', 'x-builder'),
        'term_id' => 'recently_viewed'
    ));
}

$product_view = (!empty($product_view) && $product_view === 'horizontal') ? 'x_small_product' : 'x_vertical_product';

$js_deps = array('vue.js', 'vue-resource.js');

if($carousel) {
    $js_deps[] = 'imagesloaded';
    $js_deps[] = 'owl-carousel';
    wp_enqueue_style('owl-carousel');
    wp_enqueue_script('imagesLoaded');
}

$transient = (!empty($categories[0])) ? get_transient("{$module_id}-{$categories[0]['term_id']}") : '';

stm_x_builder_register_style($module, array(), $inline_styles);
stm_x_builder_register_script('timer', array('vue.js'));
stm_x_builder_register_script(
    $module,
    $js_deps,
    '',
    $module_id,
    array(
        'categories' => $categories,
        'total' => $total,
        'carousel' => $carousel,
        'per_row' => $per_row,
        'per_row_tablet_horizontal' => $per_row_tablet_horizontal,
        'per_row_tablet_vertical' => $per_row_tablet_vertical,
        'per_row_tablet_mobile' => $per_row_tablet_mobile,
        'sale' => $sale,
        'last_chance' => $last_chance,
        'transient' => $transient,
        'style' => $style
    )
);

?>

<div class="x_grid_products_with_tabs <?php echo esc_attr(implode(' ', $classes)); ?>"
     data-v-on_mouseover="hover=true"
     data-module="<?php echo esc_attr($module_id); ?>" data-v-if="categories.length">
    <div class="x_grid_products_with_tabs__header">
        <?php if(!empty($title)): ?>
        <h3 class="x_grid_products_with_tabs__title">
            <?php echo wp_kses_post($title); ?>
        </h3>
        <?php endif; ?>
    
        <div class="x_grid_products__tabs" data-v-bind_class="'x_grid_products__tabs_' + categories.length">
            <div class="x_grid_products__tab heading_font" data-v-for="category in categories"
                 data-v-bind_class="{'active' : active_category == category.term_id}">
                <a href="#" data-v-html="category.name" data-v-on_click.prevent="getProducts(category)" data-v-if="categories.length > 1"></a>
                <span data-v-html="category.name" data-v-else></span>
            </div>
            <div class="x_owl_nav" data-v-if="carousel">
                <span class="prev"></span>
                <span class="next"></span>
            </div>
        </div>
    </div>

    <div data-v-if="typeof products[active_category] !== 'undefined' && products[active_category].message">
        <h4>{{products[active_category].message}}</h4>
    </div>

    <div data-v-else>

        <div class="x_loader_wrapper"
             data-v-if="typeof products[active_category] === 'undefined' || !products[active_category].length">
            <div class="x_loader">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <div class="<?php echo esc_attr($product_view); ?>s <?php echo esc_attr($product_view); ?>s_<?php echo esc_attr($per_row); ?> <?php if ($carousel) echo esc_attr('owl-carousel'); ?> x_grid_products"
         data-v-if="products[active_category]">
        <?php if ($style == 'style_2'): ?> 
        <div class="<?php echo esc_attr($product_view); ?> x_product_buttons_wrapper x_woo_image_wrapper"
           data-v-for="product in products[active_category]">

            <div class="<?php echo esc_attr($product_view); ?>__rating"><i class="fa fa-star"></i><span data-v-html="product.rating"></span></div>

            <div class="<?php echo esc_attr($product_view); ?>__sale <?php echo esc_attr($product_view); ?>__label heading_font"
                 data-v-if="product.sale_price">
                <?php esc_html_e('Sale', 'x-builder') ?>
            </div>

            <div class="<?php echo esc_attr($product_view); ?>__stock <?php echo esc_attr($product_view); ?>__label heading_font"
                 data-v-if="product.quantity === 0">
                <?php esc_html_e('Out of stock', 'x-builder') ?>
            </div>

            <a data-v-bind_href="product.permalink" class="<?php echo esc_attr($product_view); ?>__image">
                <span class="x_woo_image_hover" data-v-if="product.gallery">
                    <img src="#"
                         alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"
                         data-v-bind_src="product.image" data-v-bind_alt="product.title" />
                    <img src="#"
                         data-v-if="hover"
                         alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"
                         data-v-bind_src="product.gallery"
                         data-v-bind_alt="product.title" />
                </span>
                <img data-v-else
                     src="#"
                     alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"
                     data-v-bind_src="product.image" data-v-bind_alt="product.title"/>
                <span class="<?php echo esc_attr($product_view); ?>__timer heading_font" data-v-if="product.sale_to">
                    <span data-vue-role="Timer" data-v-bind_starttime="product.sale_to"
                         data-v-bind_endtime="product.sale_to"
                         data-vue-trans='{"day":"<?php esc_attr_e('Day', 'x-builder') ?>","hours":"<?php esc_attr_e('Hours', 'x-builder') ?>","minutes":"<?php esc_attr_e('Minutes', 'x-builder') ?>","seconds":"<?php esc_attr_e('Seconds', 'x-builder') ?>"}'>
                    </span>
                </span>
            </a>

            <div class="<?php echo esc_attr($product_view); ?>__content">
                <a data-v-bind_href="product.permalink" class="<?php echo esc_attr($product_view); ?>__title" data-v-html="product.title"></a>
                <div class="<?php echo esc_attr($product_view); ?>__price">
                    <span class="regular_price" data-v-html="product.regular_price" data-v-if="product.sale_price"></span>
                    <span class="price" data-v-html="product.price"></span>
                </div>
                <div class="x_product_buttons" data-v-html="product.buttons"></div>
            </div>
        </div>
        <?php else: ?>
        <a data-v-bind_href="product.permalink" class="<?php echo esc_attr($product_view); ?> x_product_buttons_wrapper x_woo_image_wrapper"
           data-v-for="product in products[active_category]">
            <div class="x_product_buttons" data-v-html="product.buttons"></div>

            <div class="<?php echo esc_attr($product_view); ?>__sale <?php echo esc_attr($product_view); ?>__label heading_font"
                 data-v-if="product.sale_price">
                <?php esc_html_e('Sale', 'x-builder') ?>
            </div>

            <div class="<?php echo esc_attr($product_view); ?>__stock <?php echo esc_attr($product_view); ?>__label heading_font"
                 data-v-if="product.quantity === 0">
                <?php esc_html_e('Out of stock', 'x-builder') ?>
            </div>

            <div class="<?php echo esc_attr($product_view); ?>__image">

                <div class="x_woo_image_hover" data-v-if="product.gallery">
                    <img src="#"
                         alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"
                         data-v-bind_src="product.image" data-v-bind_alt="product.title" />
                    <img src="#"
                         data-v-if="hover"
                         alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"
                         data-v-bind_src="product.gallery"
                         data-v-bind_alt="product.title" />
                </div>

                <img data-v-else
                     src="#"
                     alt="<?php esc_attr_e('Product Image', 'STM_X_Builder_Front'); ?>"
                     data-v-bind_src="product.image" data-v-bind_alt="product.title"/>

                <div class="<?php echo esc_attr($product_view); ?>__timer heading_font" data-v-if="product.sale_to">
                    <div data-vue-role="Timer" data-v-bind_starttime="product.sale_to"
                         data-v-bind_endtime="product.sale_to"
                         data-vue-trans='{"day":"<?php esc_attr_e('Day', 'x-builder') ?>","hours":"<?php esc_attr_e('Hours', 'x-builder') ?>","minutes":"<?php esc_attr_e('Minutes', 'x-builder') ?>","seconds":"<?php esc_attr_e('Seconds', 'x-builder') ?>"}'>
                    </div>
                </div>

            </div>

            <div class="<?php echo esc_attr($product_view); ?>__content">

                <div class="<?php echo esc_attr($product_view); ?>__brand" data-v-if="product.brands">{{product['brands']}}
                </div>

                <h6 class="<?php echo esc_attr($product_view); ?>__title" data-v-html="product.title"></h6>

                <div class="<?php echo esc_attr($product_view); ?>__price">
                    <span class="regular_price" data-v-html="product.regular_price" data-v-if="product.sale_price"></span>
                    <span class="price" data-v-html="product.price"></span>
                </div>

            </div>
        </a>
        <?php endif; ?>
    </div>

    </div>

</div>