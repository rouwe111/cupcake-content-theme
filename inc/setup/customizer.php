<?php
/**
 * CupCake Theme — WordPress Customizer settings.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Register Customizer sections, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize The Customizer manager instance.
 */
function cupcake_customizer_register(WP_Customize_Manager $wp_customize): void {

    // =========================================================================
    // PANEL: CupCake Theme Options
    // =========================================================================
    $wp_customize->add_panel(
        'cupcake_options',
        [
            'title'       => __('CupCake Theme Options', 'cupcake'),
            'description' => __('Global design settings for the CupCake theme.', 'cupcake'),
            'priority'    => 130,
        ]
    );

    // =========================================================================
    // SECTION: Logo & Favicon
    // =========================================================================
    $wp_customize->add_section(
        'cupcake_logo',
        [
            'title'    => __('Logo & Favicon', 'cupcake'),
            'panel'    => 'cupcake_options',
            'priority' => 5,
        ]
    );

    // Main logo upload.
    $wp_customize->add_setting(
        'cupcake_logo_id',
        [
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'cupcake_logo_id',
            [
                'label'       => __('Site logo', 'cupcake'),
                'description' => __('Upload a logo used by the theme header. Falls back to Site Identity logo if empty.', 'cupcake'),
                'section'     => 'cupcake_logo',
                'mime_type'   => 'image',
            ]
        )
    );

    // Footer dark logo upload.
    $wp_customize->add_setting(
        'cupcake_footer_logo_dark_id',
        [
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'cupcake_footer_logo_dark_id',
            [
                'label'       => __('Footer dark logo', 'cupcake'),
                'description' => __('Upload an alternate logo for the footer. Falls back to the main site logo when empty.', 'cupcake'),
                'section'     => 'cupcake_logo',
                'mime_type'   => 'image',
            ]
        )
    );

    // Move core favicon control into this section.
    $site_icon_control = $wp_customize->get_control('site_icon');
    if ($site_icon_control instanceof WP_Customize_Control) {
        $site_icon_control->section = 'cupcake_logo';
    }

    // =========================================================================
    // SECTION: Colors
    // =========================================================================
    $wp_customize->add_section(
        'cupcake_colors',
        [
            'title'    => __('Colors', 'cupcake'),
            'panel'    => 'cupcake_options',
            'priority' => 8,
        ]
    );

    $color_controls = [
        'cupcake_color_primary' => [
            'default' => '#1A1A2E',
            'label'   => __('Primary color', 'cupcake'),
        ],
        'cupcake_color_secondary' => [
            'default' => '#16213E',
            'label'   => __('Secondary color', 'cupcake'),
        ],
        'cupcake_color_accent' => [
            'default' => '#E94560',
            'label'   => __('Accent color', 'cupcake'),
        ],
        'cupcake_color_accent_light' => [
            'default' => '#FF6B6B',
            'label'   => __('Accent light color', 'cupcake'),
        ],
        'cupcake_color_surface' => [
            'default' => '#F8F9FA',
            'label'   => __('Surface color', 'cupcake'),
        ],
        'cupcake_color_surface_alt' => [
            'default' => '#FFFFFF',
            'label'   => __('Surface alt color', 'cupcake'),
        ],
        'cupcake_color_text' => [
            'default' => '#1A1A2E',
            'label'   => __('Text color', 'cupcake'),
        ],
        'cupcake_color_text_muted' => [
            'default' => '#6B7280',
            'label'   => __('Muted text color', 'cupcake'),
        ],
        'cupcake_color_border' => [
            'default' => '#E5E7EB',
            'label'   => __('Border color', 'cupcake'),
        ],
        'cupcake_header_bg_color' => [
            'default' => '#FFFFFF',
            'label'   => __('Header background color', 'cupcake'),
        ],
        'cupcake_header_text_color' => [
            'default' => '#1A1A2E',
            'label'   => __('Header text color', 'cupcake'),
        ],
        'cupcake_footer_bg_color' => [
            'default' => '#2A2320',
            'label'   => __('Footer background color', 'cupcake'),
        ],
        'cupcake_footer_text_color' => [
            'default' => '#F8F9FA',
            'label'   => __('Footer text color', 'cupcake'),
        ],
        'cupcake_set_rose_bg' => [
            'default' => '#FFF3F1',
            'label'   => __('Rose background', 'cupcake'),
        ],
        'cupcake_set_rose_icon' => [
            'default' => '#FA4D56',
            'label'   => __('Rose icon/accent', 'cupcake'),
        ],
        'cupcake_set_sage_bg' => [
            'default' => '#EAF3EC',
            'label'   => __('Sage background', 'cupcake'),
        ],
        'cupcake_set_sage_icon' => [
            'default' => '#4E7D5B',
            'label'   => __('Sage icon/accent', 'cupcake'),
        ],
        'cupcake_set_sand_bg' => [
            'default' => '#FFF1DC',
            'label'   => __('Sand background', 'cupcake'),
        ],
        'cupcake_set_sand_icon' => [
            'default' => '#D98A2B',
            'label'   => __('Sand icon/accent', 'cupcake'),
        ],
        'cupcake_set_berry_bg' => [
            'default' => '#FBE8EF',
            'label'   => __('Berry background', 'cupcake'),
        ],
        'cupcake_set_berry_icon' => [
            'default' => '#C9417A',
            'label'   => __('Berry icon/accent', 'cupcake'),
        ],
        'cupcake_set_grey_bg' => [
            'default' => '#F3F4F6',
            'label'   => __('Grey background', 'cupcake'),
        ],
        'cupcake_set_grey_icon' => [
            'default' => '#6B7280',
            'label'   => __('Grey icon/accent', 'cupcake'),
        ],
    ];

    foreach ($color_controls as $setting_key => $control) {
        $wp_customize->add_setting(
            $setting_key,
            [
                'default'           => $control['default'],
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                $setting_key,
                [
                    'label'   => $control['label'],
                    'section' => 'cupcake_colors',
                ]
            )
        );
    }

    // =========================================================================
    // SECTION: Header
    // =========================================================================
    $wp_customize->add_section(
        'cupcake_header',
        [
            'title'    => __('Header', 'cupcake'),
            'panel'    => 'cupcake_options',
            'priority' => 10,
        ]
    );

    // Sticky header toggle.
    $wp_customize->add_setting(
        'cupcake_sticky_header',
        [
            'default'           => true,
            'sanitize_callback' => 'cupcake_sanitize_checkbox',
            'transport'         => 'refresh',
        ]
    );
    $wp_customize->add_control(
        'cupcake_sticky_header',
        [
            'label'   => __('Enable sticky header', 'cupcake'),
            'section' => 'cupcake_header',
            'type'    => 'checkbox',
        ]
    );

    // Header primary menu selection.
    $wp_customize->add_setting(
        'cupcake_primary_header_menu',
        [
            'default'           => 0,
            'sanitize_callback' => 'cupcake_sanitize_menu_id',
            'transport'         => 'refresh',
        ]
    );

    $menu_choices = [
        0 => __('Use menu assigned to Primary location', 'cupcake'),
    ];

    foreach (wp_get_nav_menus() as $menu) {
        $menu_choices[(int) $menu->term_id] = $menu->name;
    }

    $wp_customize->add_control(
        'cupcake_primary_header_menu',
        [
            'label'       => __('Primary header menu', 'cupcake'),
            'description' => __('Choose a specific menu for the header navigation.', 'cupcake'),
            'section'     => 'cupcake_header',
            'type'        => 'select',
            'choices'     => $menu_choices,
        ]
    );

    // Header logo max width.
    $wp_customize->add_setting(
        'cupcake_header_logo_width',
        [
            'default'           => 120,
            'sanitize_callback' => 'cupcake_sanitize_header_logo_width',
            'transport'         => 'postMessage',
        ]
    );
    $wp_customize->add_control(
        'cupcake_header_logo_width',
        [
            'label'       => __('Header logo max width (px)', 'cupcake'),
            'description' => __('Controls the logo width in the header. The logo keeps width: 100% and this value as max-width.', 'cupcake'),
            'section'     => 'cupcake_header',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 40,
                'max'  => 120,
                'step' => 1,
            ],
        ]
    );

    // =========================================================================
    // SECTION: Footer
    // =========================================================================
    $wp_customize->add_section(
        'cupcake_footer',
        [
            'title'    => __('Footer', 'cupcake'),
            'panel'    => 'cupcake_options',
            'priority' => 20,
        ]
    );

    // Footer tagline.
    $wp_customize->add_setting(
        'cupcake_footer_tagline',
        [
            'default'           => __('Built with care.', 'cupcake'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ]
    );
    $wp_customize->add_control(
        'cupcake_footer_tagline',
        [
            'label'   => __('Footer tagline text', 'cupcake'),
            'section' => 'cupcake_footer',
            'type'    => 'text',
        ]
    );

    // Footer social links (used in footer bottom-right).
    $wp_customize->add_setting(
        'cupcake_social_instagram_url',
        [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]
    );
    $wp_customize->add_control(
        'cupcake_social_instagram_url',
        [
            'label'       => __('Instagram URL', 'cupcake'),
            'description' => __('Shown as an icon in the footer bottom-right. Leave empty to hide.', 'cupcake'),
            'section'     => 'cupcake_footer',
            'type'        => 'url',
        ]
    );

    $wp_customize->add_setting(
        'cupcake_social_linkedin_url',
        [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]
    );
    $wp_customize->add_control(
        'cupcake_social_linkedin_url',
        [
            'label'       => __('LinkedIn URL', 'cupcake'),
            'description' => __('Shown as an icon in the footer bottom-right. Leave empty to hide.', 'cupcake'),
            'section'     => 'cupcake_footer',
            'type'        => 'url',
        ]
    );

    // =========================================================================
    // SECTION: Typography
    // =========================================================================
    $wp_customize->add_section(
        'cupcake_typography',
        [
            'title'    => __('Typography', 'cupcake'),
            'panel'    => 'cupcake_options',
            'priority' => 40,
        ]
    );

    // Body font size base (number input, 14–20).
    $wp_customize->add_setting(
        'cupcake_body_font_size',
        [
            'default'           => 16,
            'sanitize_callback' => 'cupcake_sanitize_range',
            'transport'         => 'postMessage',
        ]
    );
    $wp_customize->add_control(
        'cupcake_body_font_size',
        [
            'label'       => __('Body font size (px)', 'cupcake'),
            'description' => __('Base font size between 14 and 20px.', 'cupcake'),
            'section'     => 'cupcake_typography',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 14,
                'max'  => 20,
                'step' => 1,
            ],
        ]
    );
}
add_action('customize_register', 'cupcake_customizer_register');

/**
 * Output Customizer-driven CSS custom properties.
 * Runs on wp_head so values are available before stylesheets paint.
 */
function cupcake_customizer_css(): void {
    $primary      = cupcake_get_color_mod('cupcake_color_primary', '#1A1A2E');
    $secondary    = cupcake_get_color_mod('cupcake_color_secondary', '#16213E');
    $accent       = cupcake_get_color_mod('cupcake_color_accent', '#E94560');
    $accent_light = cupcake_get_color_mod('cupcake_color_accent_light', '#FF6B6B');
    $surface      = cupcake_get_color_mod('cupcake_color_surface', '#F8F9FA');
    $surface_alt  = cupcake_get_color_mod('cupcake_color_surface_alt', '#FFFFFF');
    $text         = cupcake_get_color_mod('cupcake_color_text', '#1A1A2E');
    $text_muted   = cupcake_get_color_mod('cupcake_color_text_muted', '#6B7280');
    $border       = cupcake_get_color_mod('cupcake_color_border', '#E5E7EB');

    $header_bg = cupcake_get_color_mod('cupcake_header_bg_color', '#FFFFFF');
    $header_tx = cupcake_get_color_mod('cupcake_header_text_color', '#1A1A2E');
    $footer_bg = cupcake_get_color_mod('cupcake_footer_bg_color', '#2A2320');
    $footer_tx = cupcake_get_color_mod('cupcake_footer_text_color', '#F8F9FA');

    $set_rose_bg   = cupcake_get_color_mod('cupcake_set_rose_bg', '#FFF3F1');
    $set_rose_icon = cupcake_get_color_mod('cupcake_set_rose_icon', '#FA4D56');
    $set_sage_bg   = cupcake_get_color_mod('cupcake_set_sage_bg', '#EAF3EC');
    $set_sage_icon = cupcake_get_color_mod('cupcake_set_sage_icon', '#4E7D5B');
    $set_sand_bg   = cupcake_get_color_mod('cupcake_set_sand_bg', '#FFF1DC');
    $set_sand_icon = cupcake_get_color_mod('cupcake_set_sand_icon', '#D98A2B');
    $set_berry_bg  = cupcake_get_color_mod('cupcake_set_berry_bg', '#FBE8EF');
    $set_berry_icon= cupcake_get_color_mod('cupcake_set_berry_icon', '#C9417A');
    $set_grey_bg   = cupcake_get_color_mod('cupcake_set_grey_bg', '#F3F4F6');
    $set_grey_icon = cupcake_get_color_mod('cupcake_set_grey_icon', '#6B7280');

    $font_size = (int) get_theme_mod('cupcake_body_font_size', 16);
    $font_size = max(14, min(20, $font_size));
    $logo_size = (int) get_theme_mod('cupcake_header_logo_width', 120);
    $logo_size = max(40, min(120, $logo_size));

    echo '<style id="cupcake-customizer-css">:root{';
    echo '--cc-color-primary:' . esc_attr($primary) . ';';
    echo '--cc-color-secondary:' . esc_attr($secondary) . ';';
    echo '--cc-color-accent:' . esc_attr($accent) . ';';
    echo '--cc-color-accent-light:' . esc_attr($accent_light) . ';';
    echo '--cc-color-surface:' . esc_attr($surface) . ';';
    echo '--cc-color-surface-alt:' . esc_attr($surface_alt) . ';';
    echo '--cc-color-text:' . esc_attr($text) . ';';
    echo '--cc-color-text-muted:' . esc_attr($text_muted) . ';';
    echo '--cc-color-border:' . esc_attr($border) . ';';
    echo '--cc-header-bg:' . esc_attr($header_bg) . ';';
    echo '--cc-header-text:' . esc_attr($header_tx) . ';';
    echo '--cc-footer-bg:' . esc_attr($footer_bg) . ';';
    echo '--cc-footer-text:' . esc_attr($footer_tx) . ';';
    echo '--cc-set-rose-bg:' . esc_attr($set_rose_bg) . ';';
    echo '--cc-set-rose-icon:' . esc_attr($set_rose_icon) . ';';
    echo '--cc-set-sage-bg:' . esc_attr($set_sage_bg) . ';';
    echo '--cc-set-sage-icon:' . esc_attr($set_sage_icon) . ';';
    echo '--cc-set-sand-bg:' . esc_attr($set_sand_bg) . ';';
    echo '--cc-set-sand-icon:' . esc_attr($set_sand_icon) . ';';
    echo '--cc-set-berry-bg:' . esc_attr($set_berry_bg) . ';';
    echo '--cc-set-berry-icon:' . esc_attr($set_berry_icon) . ';';
    echo '--cc-set-grey-bg:' . esc_attr($set_grey_bg) . ';';
    echo '--cc-set-grey-icon:' . esc_attr($set_grey_icon) . ';';
    // Keep Elementor Global Color variable aliases available on frontend.
    echo '--e-global-color-cupcake-rose-light:' . esc_attr($set_rose_bg) . ';';
    echo '--e-global-color-cupcake-rose-dark:' . esc_attr($set_rose_icon) . ';';
    echo '--e-global-color-cupcake-sage-light:' . esc_attr($set_sage_bg) . ';';
    echo '--e-global-color-cupcake-sage-dark:' . esc_attr($set_sage_icon) . ';';
    echo '--e-global-color-cupcake-sand-light:' . esc_attr($set_sand_bg) . ';';
    echo '--e-global-color-cupcake-sand-dark:' . esc_attr($set_sand_icon) . ';';
    echo '--e-global-color-cupcake-berry-light:' . esc_attr($set_berry_bg) . ';';
    echo '--e-global-color-cupcake-berry-dark:' . esc_attr($set_berry_icon) . ';';
    echo '--e-global-color-cupcake-grey-light:' . esc_attr($set_grey_bg) . ';';
    echo '--e-global-color-cupcake-grey-dark:' . esc_attr($set_grey_icon) . ';';
    echo '--cc-body-font-size:' . esc_attr((string) $font_size) . 'px;';
    echo '--cc-header-logo-max-width:' . esc_attr((string) $logo_size) . 'px;';
    echo '}</style>' . "\n";
}
add_action('wp_head', 'cupcake_customizer_css', 99);

/**
 * Enqueue Customizer live-preview JS.
 */
function cupcake_customizer_preview_js(): void {
    wp_enqueue_script(
        'cupcake-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        ['customize-preview'],
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'cupcake_customizer_preview_js');

// ─── Sanitization helpers ─────────────────────────────────────────────────────

/**
 * Sanitize a checkbox value.
 *
 * @param mixed $value Raw value from the Customizer.
 * @return bool
 */
function cupcake_sanitize_checkbox(mixed $value): bool {
    return (bool) $value;
}

/**
 * Sanitize a numeric range value.
 *
 * @param mixed $value Raw value from the Customizer.
 * @return int
 */
function cupcake_sanitize_range(mixed $value): int {
    return (int) $value;
}

/**
 * Sanitize header logo max-width value.
 *
 * @param mixed $value Raw value from Customizer.
 * @return int
 */
function cupcake_sanitize_header_logo_width(mixed $value): int {
    $width = (int) $value;

    return max(40, min(120, $width));
}

/**
 * Sanitize a selected menu ID from the Customizer.
 *
 * @param mixed $value Raw value from Customizer.
 * @return int
 */
function cupcake_sanitize_menu_id(mixed $value): int {
    $menu_id = absint($value);

    if (0 === $menu_id) {
        return 0;
    }

    return wp_get_nav_menu_object($menu_id) ? $menu_id : 0;
}

/**
 * Get a sanitized Customizer color value with a guaranteed fallback.
 *
 * @param string $theme_mod The theme_mod key.
 * @param string $default   Default color.
 * @return string
 */
function cupcake_get_color_mod(string $theme_mod, string $default): string {
    $value = sanitize_hex_color((string) get_theme_mod($theme_mod, $default));

    return $value ?: $default;
}
