<?php
add_filter('x_get_elements', 'x_add_autoparty_products_carousel', 10, 2);

function x_add_autoparty_products_carousel($elements, $is_api)
{

    $terms = ($is_api) ? stm_x_get_terms('product_cat') : array();

    $elements[] = array(
        "module" => "x_autoparty_products_carousel",
        "name" => esc_html__("Autoparty Products Carousel", 'x-builder'),
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
                    "value" => "",
                    "typography" => array('.title'),
                ),
                array(
                    "id" => "categories",
                    "type" => "multiselect",
                    "label" => "Categories",
                    "value" => "",
                    "options" => $terms
                ),
                array(
                    "id" => "posts_per_page",
                    "type" => "number",
                    "label" => "Products count",
                    "value" => "",
                ),
                array(
                    "id"    => "per_row",
                    "type"  => "select",
                    "label" => "Items Per Row",
                    "value" => "3",
                    "options" => array(
                        "2" => "2",
                        "3" => "3",
                        "4" => "4",
                    )
                ),
                array(
                    "id"    => "rows",
                    "type"  => "select",
                    "label" => "Rows",
                    "value" => "2",
                    "options" => array(
                        "1" => "1",
                        "2" => "2",
                        "3" => "3",
                    )
                ),
            )
        )
    );

    return $elements;
}