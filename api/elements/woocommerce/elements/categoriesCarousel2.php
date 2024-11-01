<?php
add_filter('x_get_elements', 'stm_x_add_categories_carousel_element_2', 10, 2);

function stm_x_add_categories_carousel_element_2($elements, $is_api)
{

    $terms = ($is_api) ? stm_x_get_terms('product_cat') : array();

	$elements[] = array(
		"module" => "x_categories_carousel_2",
		"name"   => "Autoparty Product Categories Carousel",
        "group" => "WooCommerce",
        "show_params" => array(
            'title' => array(
                'pre' => esc_html__('Title: ', 'x-builder')
            ),
        ),
		"params" => array(
			"fields" => array(
				array(
					"id"    => "title",
					"type"  => "text",
					"label" => "Title",
					"value" => "Out hottest categories",
                    "typography" => array('.title')
				),
                array(
                    "id"    => "categories",
                    "type"  => "multiselect",
                    "label" => "Categories",
                    "value" => "",
                    "options" => $terms
                ),
//                array(
//                    "id"    => "items",
//                    "type"  => "select",
//                    "label" => "Items",
//                    "value" => "3",
//                    "options" => array(
//                        "2" => "2",
//                        "3" => "3",
//                        "4" => "4",
//                    )
//                ),
                array(
                    "id"    => "inversed",
                    "type"  => "checkbox",
                    "label" => "Inverse Style",
                ),
			)
		)
	);

	return $elements;
}

add_action('wp_ajax_x_get_product_categories_2', 'x_get_product_categories_2');
add_action('wp_ajax_nopriv_x_get_product_categories_2', 'x_get_product_categories_2');

function x_get_product_categories_2() {
    $r = array();

    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $categories = json_decode($wp_filesystem->get_contents('php://input'), true);

    foreach($categories as $category) {

        $image_id = get_term_meta( $category['term_id'], 'thumbnail_id', true );
        if(!get_post_status( $image_id )) $image_id = '';

        $image_width = 360;
        $placeholder_url = "https://via.placeholder.com/{$image_width}x250.png";
        $image = (!empty($image_id)) ? stm_x_builder_get_cropped_image_url($image_id, $image_width, 250, 1) : $placeholder_url;

        $term = get_term_by('id', $category['term_id'], 'product_cat');

        $r[] = array(
            'title' => $term->name,
            'image' => $image,
            'description' => $term->description,
            'permalink' => get_term_link($term)
        );
    }

    wp_send_json($r);
}