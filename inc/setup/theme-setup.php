<?php
/**
 * CupCake Theme — Theme setup: supports, menus, image sizes.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Register theme supports, nav menus, and custom image sizes.
 */
function cupcake_theme_setup(): void {

    // ─── Core theme support ───────────────────────────────────────────────────
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');

    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );

    add_theme_support(
        'custom-logo',
        [
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ]
    );

    add_theme_support(
        'custom-header',
        [
            'default-image' => '',
            'width'         => 1440,
            'height'        => 300,
            'flex-height'   => true,
            'flex-width'    => true,
        ]
    );

    add_theme_support('custom-background');

    // ─── Editor styles ────────────────────────────────────────────────────────
    add_editor_style('assets/css/editor-style.css');

    // ─── Navigation menus ────────────────────────────────────────────────────
    register_nav_menus(
        [
            'primary'      => __('Primary Navigation', 'cupcake'),
            'footer-col-1' => __('Footer Column 1', 'cupcake'),
            'footer-col-2' => __('Footer Column 2', 'cupcake'),
            'footer-col-3' => __('Footer Column 3', 'cupcake'),
            'social'       => __('Social Links', 'cupcake'),
        ]
    );

    // ─── Custom image sizes ───────────────────────────────────────────────────
    add_image_size('hero',           1440, 700,  true);
    add_image_size('card-landscape', 800,  500,  true);
    add_image_size('card-square',    600,  600,  true);
    add_image_size('avatar',         120,  120,  true);

    // ─── Content width ────────────────────────────────────────────────────────
    if (! isset($GLOBALS['content_width'])) {
        $GLOBALS['content_width'] = 1280;
    }
}
add_action('after_setup_theme', 'cupcake_theme_setup');
