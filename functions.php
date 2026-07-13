<?php
/**
 * CupCake Theme — functions.php
 *
 * @package CupCake
 */

declare(strict_types=1);

// Prevent direct access.
defined('ABSPATH') || exit;

// ─── Load theme modules ───────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/setup/theme-setup.php';
require_once get_template_directory() . '/inc/setup/enqueue.php';
require_once get_template_directory() . '/inc/setup/customizer.php';
require_once get_template_directory() . '/inc/setup/version-check.php';

/**
 * Boot Elementor integration once, when Elementor is available.
 */
function cupcake_boot_elementor_integration(): void {
    static $booted = false;

    if ($booted) {
        return;
    }

    if (! did_action('elementor/loaded') && ! class_exists('\Elementor\Plugin')) {
        return;
    }

    require_once get_template_directory() . '/inc/elementor/class-elementor-integration.php';
    new CupCake_Elementor_Integration();
    $booted = true;
}

// Elementor can load before theme callbacks are attached, so we hook both.
add_action('after_setup_theme', 'cupcake_boot_elementor_integration', 20);
add_action('elementor/loaded', 'cupcake_boot_elementor_integration');
