<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<button
    class="site-nav-backdrop"
    type="button"
    data-nav-close
    aria-label="<?php esc_attr_e('Close navigation menu', 'cupcake'); ?>"
    tabindex="-1"></button>
<?php
if (! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('header')) {
    get_template_part('template-parts/header/site-header');
}

