<?php
/**
 *
 * @var $params
 * @var $name
 * @var $module
 * @var $link
 * @var $subtitle
 * @var $button_title
 * @var $title_width
 * @var $subtitle_width
 * @var $banner_overlay
 * @var $custom_css
 * @var $positions
 */
$params = stm_x_builder_get_params($module, $params);
extract($params);
$classes = array();
$module_id = stm_x_builder_module_id($module, $params);
$classes[] = $module_id;
$inline_styles = (empty($custom_css)) ? '' : $custom_css;

$tag = (!empty($link)) ? "a" : "div";

if (!empty($title_width)) {
	$inline_styles .= ".{$module_id} .title{max-width: {$title_width}px;}";
}

if (!empty($subtitle_width)) {
    $inline_styles .= ".{$module_id} .subtitle{max-width: {$subtitle_width}px;}";
}

if(!empty($min_height)){
    $inline_styles .= ".{$module_id} {min-height: {$min_height}px;}";
}

if (!empty($content_width)) {
    $inline_styles .= ".{$module_id} .content{max-width: {$content_width}px;}";
}

if(!empty($banner_overlay)) {
    $inline_styles .= ".{$module_id}:after {background-color: {$banner_overlay};}";
}

stm_x_builder_register_style($module, array(), $inline_styles);

$positions = (!empty($positions)) ? $positions : 'title|subtitle|content';
$classes[] = sanitize_title($positions);
$positions = explode('|', $positions);

$classes[] = (empty($subtitle)) ? 'no-subtitle' : '';

$price = (isset($price)) ? $price : '';
$price_label = (isset($price_label)) ? $price_label : '';
$price_style = (isset($price_style)) ? $price_style : '';
$button_position = (isset($button_position)) ? $button_position : 'left';

$image = (isset($background_image) && !empty($background_image)) ? stm_x_builder_get_cropped_image_url($background_image, 0, 0, 1) : '';
$title = (isset($title)) ? $title : '';
?>

<<?php echo stm_x_filtered_output($tag); ?> <?php if(!empty($link)) echo "href='{$link}'"; ?> class="x_autoparty_banner <?php echo implode(' ', $classes); ?>">

    <?php if(!empty($image)): ?>
        <div class="x_autoparty__item_image">
            <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" />
        </div>
    <?php endif; ?>

	<?php foreach ($positions as $position) {
		STM_X_Templates::show_x_template("x_banner/{$position}", compact($position));
	} ?>

<?php if(!empty($price) || !empty($price_label)): ?>
    <div class="x_autoparty__price_holder <?php echo $price_style; ?>">
        <?php if(!empty($price_label)): ?>
            <span class="x_autoparty__price_label"><?php echo wp_kses_post($price_label); ?></span>
        <?php endif; ?>
        <?php if(!empty($price)): ?>
            <span class="x_autoparty__price"><?php echo wp_kses_post($price); ?></span>
        <?php endif; ?>
    </div>
<?php endif; ?>

    <?php if(!empty($button_title)): ?>
        <div class="text-<?php echo $button_position; ?>">
            <span class="btn btn-primary"><?php echo wp_kses_post($button_title); ?></span>
        </div>
    <?php endif; ?>

</<?php echo stm_x_filtered_output($tag); ?>>