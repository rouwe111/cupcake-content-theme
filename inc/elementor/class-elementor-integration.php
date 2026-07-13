<?php
/**
 * CupCake Theme — Elementor Integration.
 *
 * Wires up custom widget registration, categories, and kit defaults.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Handles all Elementor-specific integration for the CupCake theme.
 */
class CupCake_Elementor_Integration {

    /**
     * Constructor — registers all Elementor hooks.
     */
    public function __construct() {
        add_action('elementor/init',                            [$this, 'init']);
        add_action('elementor/widgets/register',               [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_categories']);
        add_action('elementor/frontend/after_enqueue_styles',  [$this, 'enqueue_widget_styles']);
        add_action('elementor/preview/enqueue_scripts',        [$this, 'enqueue_preview_scripts']);
        add_action('elementor/theme/register_locations',       [$this, 'register_theme_locations']);
    }

    /**
     * Fired after Elementor initialises.
     *
     * Pre-populates the active Elementor kit with the CupCake design tokens so
     * that Global Colors and Global Fonts are already set when a user opens the
     * Elementor panel for the first time.
     */
    public function init(): void {
        add_action('init', [$this, 'maybe_seed_kit_defaults'], 99);
    }

    /**
     * Seed the active Elementor kit with CupCake colour and font tokens.
     *
     * Runs once — skips if a flag is already set.
     */
    public function maybe_seed_kit_defaults(): void {
        $kit_id = (int) get_option('elementor_active_kit');

        if (! $kit_id) {
            return;
        }

        $get_theme_color = static function (string $mod, string $fallback): string {
            if (function_exists('cupcake_get_color_mod')) {
                return cupcake_get_color_mod($mod, $fallback);
            }

            $value = sanitize_hex_color((string) get_theme_mod($mod, $fallback));

            return $value ?: $fallback;
        };

        // ─── Global colours ───────────────────────────────────────────────────
        $global_colors = [
            [
                'id'    => 'cupcake-primary',
                'title' => 'CupCake Primary',
                'color' => '#1A1A2E',
            ],
            [
                'id'    => 'cupcake-secondary',
                'title' => 'CupCake Secondary',
                'color' => '#16213E',
            ],
            [
                'id'    => 'cupcake-accent',
                'title' => 'CupCake Accent',
                'color' => '#E94560',
            ],
            [
                'id'    => 'cupcake-accent-light',
                'title' => 'CupCake Accent Light',
                'color' => '#FF6B6B',
            ],
            [
                'id'    => 'cupcake-rose-light',
                'title' => 'CC Rose Light',
                'color' => $get_theme_color('cupcake_set_rose_bg', '#FFF3F1'),
            ],
            [
                'id'    => 'cupcake-rose-dark',
                'title' => 'CC Rose Dark',
                'color' => $get_theme_color('cupcake_set_rose_icon', '#FA4D56'),
            ],
            [
                'id'    => 'cupcake-sage-light',
                'title' => 'CC Sage Light',
                'color' => $get_theme_color('cupcake_set_sage_bg', '#EAF3EC'),
            ],
            [
                'id'    => 'cupcake-sage-dark',
                'title' => 'CC Sage Dark',
                'color' => $get_theme_color('cupcake_set_sage_icon', '#4E7D5B'),
            ],
            [
                'id'    => 'cupcake-sand-light',
                'title' => 'CC Sand Light',
                'color' => $get_theme_color('cupcake_set_sand_bg', '#FFF1DC'),
            ],
            [
                'id'    => 'cupcake-sand-dark',
                'title' => 'CC Sand Dark',
                'color' => $get_theme_color('cupcake_set_sand_icon', '#D98A2B'),
            ],
            [
                'id'    => 'cupcake-berry-light',
                'title' => 'CC Berry Light',
                'color' => $get_theme_color('cupcake_set_berry_bg', '#FBE8EF'),
            ],
            [
                'id'    => 'cupcake-berry-dark',
                'title' => 'CC Berry Dark',
                'color' => $get_theme_color('cupcake_set_berry_icon', '#C9417A'),
            ],
            [
                'id'    => 'cupcake-grey-light',
                'title' => 'CC Grey Light',
                'color' => $get_theme_color('cupcake_set_grey_bg', '#F3F4F6'),
            ],
            [
                'id'    => 'cupcake-grey-dark',
                'title' => 'CC Grey Dark',
                'color' => $get_theme_color('cupcake_set_grey_icon', '#6B7280'),
            ],
            [
                'id'    => 'cupcake-surface',
                'title' => 'CupCake Surface',
                'color' => '#F8F9FA',
            ],
            [
                'id'    => 'cupcake-text-muted',
                'title' => 'CupCake Text Muted',
                'color' => '#6B7280',
            ],
        ];

        // ─── Global fonts ─────────────────────────────────────────────────────
        $global_fonts = [
            [
                'id'       => 'cupcake-display',
                'title'    => 'CupCake Display',
                'font_family' => 'Playfair Display',
                'font_weight' => '700',
            ],
            [
                'id'       => 'cupcake-body',
                'title'    => 'CupCake Body',
                'font_family' => 'Inter',
                'font_weight' => '400',
            ],
            [
                'id'          => 'cupcake-content-hero',
                'title'       => 'CC Hero',
                'font_family' => 'Poppins',
                'font_weight' => '700',
                'font_size'   => ['unit' => 'px', 'size' => 72],
                'line_height' => ['unit' => 'em', 'size' => 1.2],
            ],
            [
                'id'          => 'cupcake-content-h1',
                'title'       => 'CC H1',
                'font_family' => 'Poppins',
                'font_weight' => '700',
                'font_size'   => ['unit' => 'px', 'size' => 48],
                'line_height' => ['unit' => 'em', 'size' => 1.2],
            ],
            [
                'id'          => 'cupcake-content-h2',
                'title'       => 'CC H2',
                'font_family' => 'Poppins',
                'font_weight' => '700',
                'font_size'   => ['unit' => 'px', 'size' => 32],
                'line_height' => ['unit' => 'em', 'size' => 1.2],
            ],
            [
                'id'          => 'cupcake-content-h3',
                'title'       => 'CC H3',
                'font_family' => 'Poppins',
                'font_weight' => '700',
                'font_size'   => ['unit' => 'px', 'size' => 24],
                'line_height' => ['unit' => 'em', 'size' => 1.2],
            ],
            [
                'id'          => 'cupcake-content-body',
                'title'       => 'CC Body',
                'font_family' => 'Poppins',
                'font_weight' => '400',
                'font_size'   => ['unit' => 'px', 'size' => 16],
                'line_height' => ['unit' => 'em', 'size' => 1.5],
            ],
        ];

        // Retrieve existing kit settings and merge ours in.
        $kit_meta = get_post_meta($kit_id, '_elementor_page_settings', true);
        $kit_meta = is_array($kit_meta) ? $kit_meta : [];

        $existing_colors = $kit_meta['system_colors'] ?? [];
        $existing_fonts  = $kit_meta['system_typography'] ?? [];
        $did_change      = false;

        // Add tokens when missing and sync color values when they exist.
        foreach ($global_colors as $color) {
            $exists = false;
            foreach ($existing_colors as $index => $ec) {
                if (($ec['_id'] ?? '') === $color['id']) {
                    $exists = true;

                    if (($ec['title'] ?? '') !== $color['title']) {
                        $existing_colors[$index]['title'] = $color['title'];
                        $did_change = true;
                    }

                    if (($ec['color'] ?? '') !== $color['color']) {
                        $existing_colors[$index]['color'] = $color['color'];
                        $did_change = true;
                    }

                    break;
                }
            }
            if (! $exists) {
                $existing_colors[] = [
                    '_id'   => $color['id'],
                    'title' => $color['title'],
                    'color' => $color['color'],
                ];
                $did_change = true;
            }
        }

        foreach ($global_fonts as $font) {
            $exists = false;

            foreach ($existing_fonts as $index => $ef) {
                if (($ef['_id'] ?? '') !== $font['id']) {
                    continue;
                }

                $exists = true;

                if (($ef['title'] ?? '') !== $font['title']) {
                    $existing_fonts[$index]['title'] = $font['title'];
                    $did_change = true;
                }

                // Self-heal legacy entries seeded with the wrong (unprefixed)
                // array keys — Elementor's schema expects "typography_*".
                if (isset($ef['font_family'])) {
                    unset($existing_fonts[$index]['font_family']);
                    $did_change = true;
                }

                if (isset($ef['font_weight'])) {
                    unset($existing_fonts[$index]['font_weight']);
                    $did_change = true;
                }

                // Keep typography values in sync with the source of truth
                // defined above, so later adjustments here also reach kits
                // that were already seeded.
                //
                // Elementor's typography group control requires this
                // discriminator key set to "custom" — without it, the CSS
                // renderer treats the whole row as unset and silently
                // skips emitting any --e-global-typography-* variables,
                // even when every other typography_* field is populated.
                if (($ef['typography_typography'] ?? null) !== 'custom') {
                    $existing_fonts[$index]['typography_typography'] = 'custom';
                    $did_change = true;
                }

                if (($ef['typography_font_family'] ?? null) !== $font['font_family']) {
                    $existing_fonts[$index]['typography_font_family'] = $font['font_family'];
                    $did_change = true;
                }

                if (($ef['typography_font_weight'] ?? null) !== $font['font_weight']) {
                    $existing_fonts[$index]['typography_font_weight'] = $font['font_weight'];
                    $did_change = true;
                }

                if (isset($font['font_size']) && ($ef['typography_font_size'] ?? null) !== $font['font_size']) {
                    $existing_fonts[$index]['typography_font_size'] = $font['font_size'];
                    $did_change = true;
                }

                if (isset($font['line_height']) && ($ef['typography_line_height'] ?? null) !== $font['line_height']) {
                    $existing_fonts[$index]['typography_line_height'] = $font['line_height'];
                    $did_change = true;
                }

                break;
            }

            if (! $exists) {
                $entry = [
                    '_id'                    => $font['id'],
                    'title'                  => $font['title'],
                    'typography_typography'  => 'custom',
                    'typography_font_family' => $font['font_family'],
                    'typography_font_weight' => $font['font_weight'],
                ];

                if (isset($font['font_size'])) {
                    $entry['typography_font_size'] = $font['font_size'];
                }

                if (isset($font['line_height'])) {
                    $entry['typography_line_height'] = $font['line_height'];
                }

                $existing_fonts[] = $entry;
                $did_change = true;
            }
        }

        if (! $did_change) {
            return;
        }

        $kit_meta['system_colors']     = array_values($existing_colors);
        $kit_meta['system_typography'] = array_values($existing_fonts);

        update_post_meta($kit_id, '_elementor_page_settings', $kit_meta);
        update_option('cupcake_kit_seeded', true);
    }

    /**
     * Register CupCake custom Elementor widgets.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager The Elementor widgets manager.
     */
    public function register_widgets(\Elementor\Widgets_Manager $widgets_manager): void {
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-hero.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-section-intro.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-service-card.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-steps-block.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-cta-card.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-testimonial.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-blogs.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-bloom-banner.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-header-button.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-faq.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-services-accordion.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-quote-highlight.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-direct-contact.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-small-content.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-packages.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-company-logos.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-banner.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-cc-button.php';
        require_once get_template_directory() . '/inc/elementor/widgets/class-widget-cc-image.php';

        $widgets_manager->register(new CupCake_Widget_Hero());
        $widgets_manager->register(new CupCake_Widget_Section_Intro());
        $widgets_manager->register(new CupCake_Widget_Service_Card());
        $widgets_manager->register(new CupCake_Widget_Steps_Block());
        $widgets_manager->register(new CupCake_Widget_CTA_Card());
        $widgets_manager->register(new CupCake_Widget_Testimonial());
        $widgets_manager->register(new CupCake_Widget_Blogs());
        $widgets_manager->register(new CupCake_Widget_Bloom_Banner());
        $widgets_manager->register(new CupCake_Widget_Header_Button());
        $widgets_manager->register(new CupCake_Widget_FAQ());
        $widgets_manager->register(new CupCake_Widget_Services_Accordion());
        $widgets_manager->register(new CupCake_Widget_Quote_Highlight());
        $widgets_manager->register(new CupCake_Widget_Direct_Contact());
        $widgets_manager->register(new CupCake_Widget_Small_Content());
        $widgets_manager->register(new CupCake_Widget_Packages());
        $widgets_manager->register(new CupCake_Widget_Company_Logos());
        $widgets_manager->register(new CupCake_Widget_Banner());
        $widgets_manager->register(new CupCake_Widget_CC_Button());
        $widgets_manager->register(new CupCake_Widget_CC_Image());
    }

    /**
     * Register editable theme locations for Elementor Theme Builder.
     *
     * @param mixed $elementor_theme_manager Theme manager instance.
     */
    public function register_theme_locations($elementor_theme_manager): void {
        if (is_object($elementor_theme_manager) && method_exists($elementor_theme_manager, 'register_all_core_location')) {
            $elementor_theme_manager->register_all_core_location();
        }
    }

    /**
     * Register the 'cupcake-content' widget category in the Elementor panel.
     *
     * @param \Elementor\Elements_Manager $elements_manager The elements manager.
     */
    public function add_widget_categories(\Elementor\Elements_Manager $elements_manager): void {
        $elements_manager->add_category(
            'cupcake-content',
            [
                'title' => __('Cupcake Content', 'cupcake'),
                'icon'  => 'fa fa-layer-group',
            ]
        );
    }

    /**
     * Enqueue additional widget styles on the frontend.
     * Our widget styles are bundled in elementor-overrides.css; nothing extra needed.
     */
    public function enqueue_widget_styles(): void {
        // Intentionally left empty — styles handled via cupcake-elementor handle.
    }

    /**
     * Ensure frontend runtime scripts are available in Elementor preview iframe.
     */
    public function enqueue_preview_scripts(): void {
        $theme   = wp_get_theme();
        $version = $theme->get('Version') ?: '1.0.0';

        if (wp_script_is('cupcake-main', 'registered')) {
            wp_enqueue_script('cupcake-main');
            return;
        }

        wp_enqueue_script(
            'cupcake-main-preview',
            get_template_directory_uri() . '/assets/js/main.js',
            [],
            $version,
            true
        );
    }
}
