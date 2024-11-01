<?php
add_filter('x_get_elements', 'x_add_featured_products_carousel_element', 10, 2);

function x_add_featured_products_carousel_element($elements, $is_api)
{

    $posts = (!$is_api) ? array() : stm_x_get_posts('product');

    $elements[] = array(
        "module" => "x_featured_products_carousel",
        "name" => "Featured Products Carousel",
        "group" => "WooCommerce",
        "show_params" => array(
            'title' => array(
                'pre' => esc_html__('Title: ', 'x-builder')
            ),
        ),
        "params" => array(
            "fields" => array(
                array(
                    "id" => "title",
                    "type" => "text",
                    "label" => "Title",
                    "value" => "Featured Products",
                    "typography" => array('.title')
                ),
                array(
                    "id" => "posts",
                    "type" => "multiselect",
                    "label" => "Posts To Show",
                    "value" => "",
                    "options" => $posts
                )
            )
        )
    );

    return $elements;
}


add_action('wp_ajax_x_get_featured_products_carousel', 'x_get_featured_products_carousel');
add_action('wp_ajax_nopriv_x_get_featured_products_carousel', 'x_get_featured_products_carousel');

function x_get_featured_products_carousel()
{
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    require_once STM_X_BUILDER_DIR . "/builder/template.Class.php";
    $posts = json_decode($wp_filesystem->get_contents('php://input'), true);
    $module_id = (!empty($_GET['module_id'])) ? sanitize_text_field($_GET['module_id']) : '';

    $transient_name = "{$module_id}";

//    if (false === ($r = get_transient($transient_name))) {
        $args = array(
            'post_type' => 'product',
            'post__in' => (!empty(wp_list_pluck($posts, 'term_id'))) ? wp_list_pluck($posts, 'term_id') : wc_get_featured_product_ids(),
            'orderby' => 'post__in',
            'posts_per_page' => -1,
//            'posts_per_page' => count($posts),
        );

        $r = array();

//        if (!empty($bestsellers)) {
//            $args['meta_key'] = 'total_sales';
//            $args['orderby'] = 'meta_value_num';
//            $args['order'] = 'DESC';
//        }

        $images = array(
            array(180, 180),
            array(150, 150),
            array(150, 150),
            array(150, 150),
            array(150, 150),
//            array(315, 355),
//            array(190, 240),
//            array(190, 240),
//            array(190, 240),
//            array(190, 240),
        );

        $q = new WP_Query($args);

        if ($q->have_posts()) {
            $i = 0;
            $ppp = 5;
            $page = 0;
            while ($q->have_posts()) {
                $q->the_post();
                $id = get_the_ID();
                $_product = wc_get_product($id);

                $price = $_product->get_price();
                $regular_price = $_product->get_regular_price();
                $sale_price = $_product->get_sale_price();

                $discount = (!empty($sale_price) && !empty($regular_price)) ? intval(100 - ($sale_price * 100 / $regular_price)) : '';

                $sale_price = (!empty($sale_price)) ? strip_tags(wc_price($_product->get_sale_price())) : '';
                $sale_to = $_product->get_date_on_sale_to();
                if (!empty($sale_to)) {
                    $current_time = time();
                    $sale_to = strtotime($sale_to);
                    $sale_to = ($current_time < $sale_to) ? date('M j, Y G:i:s', $sale_to) : null;
                }

                $gallery = $_product->get_gallery_image_ids();
                $gallery = (!empty($gallery)) ? stm_x_builder_get_cropped_image_url($gallery[0], $images[$i][0], $images[$i][1]) : '';

                /*Brands*/
                $brand = array();
                $brands = wp_get_post_terms($id, 'stmt_brand_taxonomy');
                if (!empty($brands) and !is_wp_error($brands)) {
                    $brand = wp_list_pluck($brands, 'name');
                }

                $page = ($i % $ppp == 0) ? $page + 1 : $page;

                $r[$page][] = array(
                    'id' => $id,
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'price' => strip_tags(wc_price($price)),
                    'regular_price' => strip_tags(wc_price($regular_price)),
                    'sale_price' => $sale_price,
                    'quantity' => $_product->get_stock_quantity(),
                    'sale_to' => $sale_to,
                    'discount' => $discount,
                    'image' => stm_x_builder_get_cropped_image_url(get_post_thumbnail_id(), $images[$i][0], $images[$i][1]),
                    'dims' => $images[$i],
                    'buttons' => STM_X_Templates::load_x_template_legal('global/product_buttons', array('id' => $id)),
                    'gallery' => $gallery,
                    'rating' => $_product->get_average_rating(),
                    'rating_percent' => (floatval($_product->get_average_rating()) / 5) * 100,
                    'brands' => implode(', ', $brand),
                );

                $i++;
            }

            wp_reset_postdata();
        }

        stm_x_set_transient($transient_name, $r);
//    }

    wp_send_json($r);
    exit;
}