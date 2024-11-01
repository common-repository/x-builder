<?php
$params = stm_x_builder_get_params( $module, $params );
extract( $params );
$classes = array();
$module_id = stm_x_builder_module_id( $module, $params );
$classes[] = $module_id;
$inline_styles = ( empty( $custom_css ) ) ? '' : $custom_css;

stm_x_builder_register_style( $module, array(), $inline_styles );
stm_x_builder_register_script( $module, array( 'jquery', 'owl-carousel' ), array() );

if( empty( $posts_per_page ) ) {
//    $posts_per_page = '10';
    $posts_per_page = '-1';
}

$per_row = (isset($per_row)) ? intval($per_row) : 3;
$rows_count = (isset($rows)) ? intval($rows) : 2;

$per_slide = $per_row * $rows_count;

$terms = array();
if( !empty( $categories ) ) {
    foreach( $categories as $category ) {
        $terms[] = $category[ 'term_id' ];
    }
}
$args = array(
    'post_type' => 'product',
    'posts_per_page' => $posts_per_page,
);

if( !empty( $terms ) ) {
    $args[ 'tax_query' ] = array(
        array(
            'field' => 'term_id',
            'taxonomy' => 'product_cat',
            'terms' => $terms
        )
    );
}
$q = new WP_Query( $args );
?>
<div class="x_autoparty_products_carousel <?php echo implode( ' ', $classes ); ?>">
    <?php if( !empty( $title ) ): ?>
        <h2 class="title">
            <?php echo esc_html( $title ); ?>
        </h2>
    <?php endif; ?>

    <?php if( $q->have_posts() ): ?>
        <div class="x_owl_nav_wrap">
            <div class="x_owl_nav"><span class="prev"></span> <span class="next"></span></div>
        </div>
    <?php endif; ?>

    <div class="x_autoparty_products_carousel_inner owl-carousel" data-module="<?php echo esc_attr( $module_id ); ?>">
        <?php
//        $per_slide = 6;
        if( $q->have_posts() ):
            $i = 1;
//            var_dump($q->post_count);
//            var_dump($q->found_posts);
            while ( $q->have_posts() ):
                $q->the_post();
                $id = get_the_ID();
                $_product = wc_get_product( $id );
                $product_cats = get_the_terms( $id, 'product_cat' );
                $price = $_product->get_price();
                $regular_price = $_product->get_regular_price();
                $sale_price = $_product->get_sale_price();
                $rating = $_product->get_average_rating();

                if ($i%$per_slide == 1){
                    echo '<div class="x_carousel-item-wrap"><div class="row">';
                }

                ?>

                <div class="x_carousel-item col-sm-<?php echo (12/$per_row); ?>">

                    <?php if( has_post_thumbnail() ): ?>
                        <div class="x_carousel-item__img">
                            <img src="<?php echo esc_url( stm_x_builder_get_cropped_image_url( get_post_thumbnail_id(), 300, 360 ) ); ?>"
                                 alt="<?php the_title(); ?>"/>
                        </div>
                    <?php endif; ?>
                    <div class="x_carousel-item__product-info">
                        <div class="title">
                            <a href="<?php the_permalink(); ?>" class="heading_font">
                                <?php the_title(); ?>
                            </a>
                        </div>

                        <div class="x_average_rating_unit">
                            <div class="x_star-rating">
                                <span style="width:<?php echo (floatval($rating) / 5) * 100; ?>%">Rated <strong class="rating"><?php echo $rating; ?></strong> out of 5</span>
                            </div>
                        </div>

                        <?php if( !empty( $product_cats ) ): ?>
                            <a href="<?php echo get_term_link( $product_cats[ 0 ]->term_id, 'product_cat' ); ?>"
                               class="category_title"><?php echo esc_html( $product_cats[ 0 ]->name ); ?></a>
                        <?php endif; ?>
                        <div class="price">
                            <?php
                            $_product = wc_get_product( get_the_ID() );
                            $regular_price = $_product->get_regular_price();
                            $sale_price = $_product->get_sale_price();
                            $symbol = get_woocommerce_currency_symbol();
                            $price_class = 'regular_price';
                            if( empty( $sale_price ) ) {
                                $price_class = 'sale_price';
                            }
                            if( !empty( $sale_price ) ) {
                                $sale_price = floatval( $sale_price );
                                echo '<span class="sale_price"><span>' . esc_html( $symbol ) . '</span>' .esc_html( number_format( $sale_price, 2, '.', ' ' ) ) . '</span>';
                            }
                            if( !empty( $regular_price ) ) {
                                $regular_price = floatval( $regular_price );
                                echo '<span class="'.esc_attr( $price_class ).'"><span>'.esc_html( $symbol ).'</span>'.esc_html( number_format( $regular_price, 2, '.', ' ' ) ).'</span>';
                            }
                            ?>

                            <a href="?add-to-cart=<?php echo $id; ?>"
                               data-quantity="1"
                               data-product_id="<?php echo $id; ?>"
                               rel="nofollow"
                               class="button product_type_simple add_to_cart_button ajax_add_to_cart"><i class="lnricons-cart"></i><?php _e('Add to cart','x_builser'); ?></a>

                        </div>
                    </div>

                </div>

            <?php
                if ($i%$per_slide == 0){
                    echo '</div></div>';
                }
                $i++;

            endwhile;

            if ($i%$per_slide != 1) echo '</div></div>';

            wp_reset_postdata();
        endif; ?>
    </div>

</div>

