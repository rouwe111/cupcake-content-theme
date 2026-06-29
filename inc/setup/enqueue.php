<?php
/**
 * CupCake Theme — Asset enqueueing.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Enqueue front-end styles and scripts.
 */
function cupcake_enqueue_assets(): void {

    $theme   = wp_get_theme();
    $version = $theme->get('Version') ?: '1.0.0';

    // ─── Google Fonts ─────────────────────────────────────────────────────────
    wp_enqueue_style(
        'cupcake-google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Mulish:wght@400;500;600;700&display=swap',
        [],
        null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
    );

    // ─── Main stylesheet ──────────────────────────────────────────────────────
    wp_enqueue_style(
        'cupcake-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['cupcake-google-fonts'],
        $version
    );

    // ─── Inline CSS custom properties (design tokens from style.css) ──────────
    wp_add_inline_style('cupcake-main', cupcake_get_css_tokens());

    // ─── Elementor overrides (only when Elementor is active) ─────────────────
    if (did_action('elementor/loaded')) {
        wp_enqueue_style(
            'cupcake-elementor',
            get_template_directory_uri() . '/assets/css/elementor-overrides.css',
            ['cupcake-main'],
            $version
        );

        // Ensure Elementor icon packs are available on frontend widget renders.
        wp_enqueue_style('elementor-icons');
        wp_enqueue_style('elementor-icons-fa-solid');
        wp_enqueue_style('elementor-icons-fa-regular');
        wp_enqueue_style('elementor-icons-fa-brands');

        // Frontend safety net: ensure FA class-based icons render even when
        // Elementor's icon-pack handles are skipped by optimization/caching layers.
        wp_enqueue_style(
            'cupcake-fontawesome-fallback',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
            [],
            '5.15.4'
        );
    }

    // ─── Main script ──────────────────────────────────────────────────────────
    wp_enqueue_script(
        'cupcake-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        $version,
        true // Load in footer.
    );

    // Add defer attribute to the main script.
    add_filter(
        'script_loader_tag',
        static function (string $tag, string $handle): string {
            if ('cupcake-main' === $handle) {
                return str_replace(' src=', ' defer src=', $tag);
            }
            return $tag;
        },
        10,
        2
    );

    // ─── Comments ─────────────────────────────────────────────────────────────
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'cupcake_enqueue_assets');

/**
 * Enqueue Gutenberg editor assets for custom block styles.
 */
function cupcake_enqueue_block_editor_assets(): void {
    $theme   = wp_get_theme();
    $version = $theme->get('Version') ?: '1.0.0';

    wp_enqueue_script(
        'cupcake-editor-block-styles',
        get_template_directory_uri() . '/assets/js/editor-block-styles.js',
        ['wp-blocks', 'wp-dom-ready'],
        $version,
        true
    );
}
add_action('enqueue_block_editor_assets', 'cupcake_enqueue_block_editor_assets');

/**
 * Add resource hints for Google Fonts.
 *
 * @param array<int, string>       $urls          URLs to print for resource hints.
 * @param string                   $relation_type Hint relation type.
 *
 * @return array<int, string>
 */
function cupcake_fonts_resource_hints(array $urls, string $relation_type): array {
    if ('preconnect' === $relation_type) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = 'https://fonts.gstatic.com';
    }

    return array_unique($urls);
}
add_filter('wp_resource_hints', 'cupcake_fonts_resource_hints', 10, 2);

/**
 * Build the :root CSS custom-properties block from the design tokens.
 *
 * We extract the :root block from style.css so the tokens stay as the
 * single source of truth and are also inlined into <head> via wp_head().
 *
 * @return string CSS string suitable for wp_add_inline_style().
 */
function cupcake_get_css_tokens(): string {
    $style_css = get_template_directory() . '/style.css';

    if (! file_exists($style_css)) {
        return '';
    }

    $contents = file_get_contents($style_css); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

    if (false === $contents) {
        return '';
    }

    // Extract everything inside the first :root { … } block.
    if (! preg_match('/:root\s*\{([^}]+)\}/s', $contents, $matches)) {
        return '';
    }

    return ':root {' . $matches[1] . '}';
}
