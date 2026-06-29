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
        if (get_option('cupcake_kit_seeded')) {
            return;
        }

        $kit_id = (int) get_option('elementor_active_kit');

        if (! $kit_id) {
            return;
        }

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
        ];

        // Retrieve existing kit settings and merge ours in.
        $kit_meta = get_post_meta($kit_id, '_elementor_page_settings', true);
        $kit_meta = is_array($kit_meta) ? $kit_meta : [];

        $existing_colors = $kit_meta['system_colors'] ?? [];
        $existing_fonts  = $kit_meta['system_typography'] ?? [];

        // Only add tokens that don't already exist (identified by 'id' field).
        foreach ($global_colors as $color) {
            $exists = false;
            foreach ($existing_colors as $ec) {
                if (($ec['_id'] ?? '') === $color['id']) {
                    $exists = true;
                    break;
                }
            }
            if (! $exists) {
                $existing_colors[] = [
                    '_id'   => $color['id'],
                    'title' => $color['title'],
                    'color' => $color['color'],
                ];
            }
        }

        foreach ($global_fonts as $font) {
            $exists = false;
            foreach ($existing_fonts as $ef) {
                if (($ef['_id'] ?? '') === $font['id']) {
                    $exists = true;
                    break;
                }
            }
            if (! $exists) {
                $existing_fonts[] = [
                    '_id'         => $font['id'],
                    'title'       => $font['title'],
                    'font_family' => $font['font_family'],
                    'font_weight' => $font['font_weight'],
                ];
            }
        }

        $kit_meta['system_colors']     = $existing_colors;
        $kit_meta['system_typography'] = $existing_fonts;

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
}
