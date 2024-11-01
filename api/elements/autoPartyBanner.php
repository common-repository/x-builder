<?php
add_filter('x_get_elements', 'x_add_autoparty_banner_element');

function x_add_autoparty_banner_element($elements)
{

	$elements[] = array(
		"module" => "x_autoparty_banner",
		"name"   => "Autoparty Banner",
        "element_color" => "#6f87ff",
        "show_params" => array(
            'title' => array(
                'pre' => esc_html__('Title: ', 'x-builder')
            ),
        ),
		"params" => array(
			"fields" => array(
				array(
					"id"         => "title",
					"type"       => "text",
					"label"      => "Banner Title",
					"typography" => array('.title')
				),
				array(
					"id"         => "subtitle",
					"type"       => "text",
					"label"      => "Banner Subtitle",
					"typography" => array('.subtitle')
				),
                array(
                    "id"    => "min_height",
                    "type"  => "number",
                    "label" => "Banner Min Height",
                ),
                array(
                    "id"         => "price",
                    "type"       => "text",
                    "label"      => "Price",
                    "typography" => array('.x_autoparty__price')
                ),
                array(
                    "id"         => "price_label",
                    "type"       => "text",
                    "label"      => "Price Label",
                    "typography" => array('.x_autoparty__price_label')
                ),
                array(
                    "id"         => "price_style",
                    "type"       => "select",
                    "label"      => "Price Style",
                    "value" => "1",
                    "options" => array(
                        "standard" => "Standard",
                        "top_right" => "Top Right",
                        "top_left" => "Top Left",
                    )
                ),
                array(
                    "id"         => "button_title",
                    "type"       => "text",
                    "label"      => "Button Title",
//                    "typography" => array('.button_title')
                ),
                array(
                    "id"         => "button_position",
                    "type"       => "select",
                    "label"      => "Button Position",
                    "value" => "1",
                    "options" => array(
                        "left" => "Left",
                        "center" => "Center",
                        "right" => "Right",
                    )
                ),
                array(
                    "id"         => "link",
                    "type"       => "text",
                    "label"      => "Button Link",
                ),
                array(
                    "id"         => "background_image",
                    "type"       => "image",
                    "label"      => "Background Image",
                ),
				array(
					"id"      => "positions",
					"type"    => "select",
					"label"   => "Position order",
					"options" => array(
						'title|content|subtitle' => "1. Title. 2. Content. 3. Sub Title",
						'title|subtitle|content' => "1. Title. 2. Sub Title. 3. Content",
						'content|title|subtitle' => "1. Content. 2. Title. 3. Sub Title",
						'subtitle|content|title' => "1. Sub Title. 2. Content. 3. Title",
						'subtitle|title|content' => "1. Sub Title. 2. Title. 3. Content",
					),
					"value" => ""
				),
                array(
                    "id"         => "banner_overlay",
                    "type"       => "color",
                    "label"      => "Banner Overlay",
                ),
			)
		)
	);

	return $elements;
}