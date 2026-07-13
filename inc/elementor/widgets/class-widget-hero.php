<?php
/**
 * CupCake Theme — Elementor Hero Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-heading-tag.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Full-width hero section widget with heading, subheading, and two CTA buttons.
 */
class CupCake_Widget_Hero extends Widget_Base {
    use CupCake_Heading_Tag;

    /**
     * Get available hero theme presets.
     *
     * @return array<string, string>
     */
    private function get_theme_options(): array {
        return [
            'rose' => __('Rose', 'cupcake'),
            'sage' => __('Sage', 'cupcake'),
        ];
    }

    /**
     * Resolve theme token values.
     *
     * @param string $selected Selected theme key.
     * @return array<string, string>
     */
    private function resolve_theme_tokens(string $selected): array {
        $themes = [
            'rose' => [
                'pill_bg'                 => '#FFE3E1',
                'pill_color'              => '#C9303A',
                'heading_marked'          => '#FA4D56',
                'primary_btn_bg'          => '#4E7D5B',
                'primary_btn_bg_hover'    => '#456F51',
                'primary_btn_shadow'      => '0 18px 30px -16px rgba(78, 125, 91, 0.85)',
                'secondary_btn_border'    => '#DCEDE2',
                'secondary_btn_border_hv' => '#C6DDCF',
                'image_shadow'            => '0 44px 80px -42px rgba(250, 77, 86, 0.5)',
                'float_number_bg'         => '#FFE3E1',
                'float_number_color'      => '#FA4D56',
                'float_dot'               => '#4E7D5B',
            ],
            'sage' => [
                'pill_bg'                 => '#DCEDE2',
                'pill_color'              => '#3F6A4B',
                'heading_marked'          => '#4E7D5B',
                'primary_btn_bg'          => '#4E7D5B',
                'primary_btn_bg_hover'    => '#3F664C',
                'primary_btn_shadow'      => '0 18px 30px -16px rgba(78, 125, 91, 0.85)',
                'secondary_btn_border'    => '#C9E1D2',
                'secondary_btn_border_hv' => '#A9CCB7',
                'image_shadow'            => '0 44px 80px -42px rgba(78, 125, 91, 0.45)',
                'float_number_bg'         => '#DCEDE2',
                'float_number_color'      => '#4E7D5B',
                'float_dot'               => '#4E7D5B',
            ],
        ];

        return $themes[$selected] ?? $themes['rose'];
    }

    /**
     * Build options for internal link selection.
     *
     * @return array<string, string>
     */
    private function get_internal_link_options(): array {
        $options = [];

        $items = get_posts(
            [
                'post_type'      => ['page', 'post'],
                'post_status'    => 'publish',
                'posts_per_page' => 300,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]
        );

        foreach ($items as $item) {
            $type_label = 'page' === $item->post_type ? __('Page', 'cupcake') : __('Post', 'cupcake');
            $label      = sprintf('%s (%s)', $item->post_title, $type_label);
            $options[(string) $item->ID] = $label;
        }

        return $options;
    }

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-hero';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Hero', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-banner';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['hero', 'banner', 'heading', 'cta', 'cupcake'];
    }

    /** {@inheritdoc} */
    protected function register_controls(): void {

        // =====================================================================
        // SECTION: Content
        // =====================================================================
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label_text',
            [
                'label'       => __('Label text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Voor de foodbranche · Regio Zwolle', 'cupcake'),
                'placeholder' => __('Enter label text', 'cupcake'),
            ]
        );

        $this->add_control(
            'heading_intro',
            [
                'label'       => __('Heading intro', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Laat jouw business', 'cupcake'),
                'placeholder' => __('First heading line', 'cupcake'),
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'heading_highlight',
            [
                'label'       => __('Heading highlight', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('bloeien', 'cupcake'),
                'placeholder' => __('Highlighted heading word(s)', 'cupcake'),
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label'   => __('Heading HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => $this->get_heading_tag_options(),
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label'   => __('Title style', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_title_style_options(),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => __('Description', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Ik help MKB-bedrijven uit de foodbranche om in 30 dagen consistent zichtbaar te worden met authentieke content, klanten als ambassadeurs en een slim SEO-fundament. Meer aanvragen, zonder wekelijkse contentstress.', 'cupcake'),
                'placeholder' => __('Hero description', 'cupcake'),
                'rows'        => 5,
            ]
        );

        $this->add_control(
            'theme_preset',
            [
                'label'     => __('Theme preset', 'cupcake'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'rose',
                'options'   => $this->get_theme_options(),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_primary_button',
            [
                'label' => __('Primary Button', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cta_primary_enabled',
            [
                'label'        => __('Show primary button', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'cta_primary_text',
            [
                'label'       => __('Primary CTA text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Plan een gratis adviesgesprek', 'cupcake'),
                'condition'   => [
                    'cta_primary_enabled' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_primary_link_type',
            [
                'label'     => __('Primary CTA link type', 'cupcake'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'external',
                'options'   => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
                'condition' => [
                    'cta_primary_enabled' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_primary_internal_post',
            [
                'label'       => __('Select primary page or post', 'cupcake'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_internal_link_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'cta_primary_enabled'   => 'yes',
                    'cta_primary_link_type' => 'internal',
                ],
            ]
        );

        $this->add_control(
            'cta_primary_url',
            [
                'label'         => __('Primary CTA URL', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'cupcake'),
                'show_external' => true,
                'default'       => ['url' => '#'],
                'condition'     => [
                    'cta_primary_enabled'   => 'yes',
                    'cta_primary_link_type' => 'external',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_secondary_button',
            [
                'label' => __('Secondary Button', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cta_secondary_enabled',
            [
                'label'        => __('Show secondary button', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'cta_secondary_text',
            [
                'label'     => __('Secondary CTA text', 'cupcake'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Bekijk inspiratie', 'cupcake'),
                'condition' => [
                    'cta_secondary_enabled' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_secondary_link_type',
            [
                'label'   => __('Secondary CTA link type', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'external',
                'options' => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
                'condition' => [
                    'cta_secondary_enabled' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_secondary_internal_post',
            [
                'label'       => __('Select secondary page or post', 'cupcake'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_internal_link_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'cta_secondary_enabled'   => 'yes',
                    'cta_secondary_link_type' => 'internal',
                ],
            ]
        );

        $this->add_control(
            'cta_secondary_url',
            [
                'label'         => __('Secondary CTA URL', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'cupcake'),
                'show_external' => true,
                'default'       => ['url' => '#'],
                'condition'     => [
                    'cta_secondary_enabled'   => 'yes',
                    'cta_secondary_link_type' => 'external',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================================================================
        // SECTION: Visual — Hero Image
        // =====================================================================
        $this->start_controls_section(
            'section_visual_image',
            [
                'label' => __('Visual · Hero Image', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hero_image',
            [
                'label'       => __('Hero image', 'cupcake'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => ['url' => ''],
                'description' => __('Recommended: portrait/vertical image around 900x1100.', 'cupcake'),
            ]
        );

        $this->add_control(
            'hero_image_alt',
            [
                'label'       => __('Hero image alt text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Koffie met hartje', 'cupcake'),
                'placeholder' => __('Describe the hero image', 'cupcake'),
            ]
        );

        $this->end_controls_section();

        // =====================================================================
        // SECTION: Visual — Floating Badge Top
        // =====================================================================
        $this->start_controls_section(
            'section_visual_badge_top',
            [
                'label' => __('Visual · Floating Badge Top', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'floating_number',
            [
                'label'       => __('Floating badge number', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('30', 'cupcake'),
                'placeholder' => __('30', 'cupcake'),
            ]
        );

        $this->add_control(
            'floating_text',
            [
                'label'       => __('Floating badge text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('dagen tot zichtbaar', 'cupcake'),
                'placeholder' => __('dagen tot zichtbaar', 'cupcake'),
            ]
        );

        $this->end_controls_section();

        // =====================================================================
        // SECTION: Visual — Floating Badge Bottom
        // =====================================================================
        $this->start_controls_section(
            'section_visual_badge_bottom',
            [
                'label' => __('Visual · Floating Badge Bottom', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'floating_status_text',
            [
                'label'       => __('Floating status text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Klanten als ambassadeurs', 'cupcake'),
                'placeholder' => __('Klanten als ambassadeurs', 'cupcake'),
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $label_text      = esc_html($settings['label_text'] ?? '');
        $heading_intro   = wp_kses_post($settings['heading_intro'] ?? '');
        $heading_marked  = esc_html($settings['heading_highlight'] ?? '');
        $heading_tag     = $this->sanitize_heading_tag((string) ($settings['heading_tag'] ?? 'h1'), 'h1');
        $heading_style   = $this->resolve_title_style_class((string) ($settings['heading_title_style'] ?? ''));
        $description     = wp_kses_post($settings['description'] ?? '');
        $hero_image_url  = esc_url($settings['hero_image']['url'] ?? '');
        $hero_image_alt  = esc_attr($settings['hero_image_alt'] ?? '');
        $float_number    = esc_html($settings['floating_number'] ?? '');
        $float_text      = esc_html($settings['floating_text'] ?? '');
        $float_status    = esc_html($settings['floating_status_text'] ?? '');
        $heading_id      = $this->get_id() . '-heading';

        $theme_preset = (string) ($settings['theme_preset'] ?? 'rose');
        $theme_tokens = $this->resolve_theme_tokens($theme_preset);

        $hero_style = sprintf(
            '--cc-hero-pill-bg:%s;--cc-hero-pill-color:%s;--cc-hero-heading-marked:%s;--cc-hero-primary-btn-bg:%s;--cc-hero-primary-btn-bg-hover:%s;--cc-hero-primary-btn-shadow:%s;--cc-hero-secondary-btn-border:%s;--cc-hero-secondary-btn-border-hover:%s;--cc-hero-image-shadow:%s;--cc-hero-float-number-bg:%s;--cc-hero-float-number-color:%s;--cc-hero-float-dot:%s;',
            esc_attr($theme_tokens['pill_bg']),
            esc_attr($theme_tokens['pill_color']),
            esc_attr($theme_tokens['heading_marked']),
            esc_attr($theme_tokens['primary_btn_bg']),
            esc_attr($theme_tokens['primary_btn_bg_hover']),
            esc_attr($theme_tokens['primary_btn_shadow']),
            esc_attr($theme_tokens['secondary_btn_border']),
            esc_attr($theme_tokens['secondary_btn_border_hv']),
            esc_attr($theme_tokens['image_shadow']),
            esc_attr($theme_tokens['float_number_bg']),
            esc_attr($theme_tokens['float_number_color']),
            esc_attr($theme_tokens['float_dot'])
        );

        $cta1_enabled = 'yes' === ($settings['cta_primary_enabled'] ?? 'yes');
        $cta2_enabled = 'yes' === ($settings['cta_secondary_enabled'] ?? 'yes');

        $cta1_text = esc_html($settings['cta_primary_text'] ?? '');

        $cta1_link_type = $settings['cta_primary_link_type'] ?? 'external';
        $cta1_link_data = [];

        if ($cta1_enabled && 'internal' === $cta1_link_type) {
            $cta1_internal_post_id = (int) ($settings['cta_primary_internal_post'] ?? 0);

            if ($cta1_internal_post_id > 0) {
                $cta1_internal_url = get_permalink($cta1_internal_post_id);

                if (is_string($cta1_internal_url) && '' !== $cta1_internal_url) {
                    $cta1_link_data = [
                        'url'         => $cta1_internal_url,
                        'is_external' => false,
                        'nofollow'    => false,
                    ];
                }
            }
        } elseif ($cta1_enabled && 'external' === $cta1_link_type) {
            $cta1_link_data = $settings['cta_primary_url'] ?? [];
        }

        $has_cta1_link = ! empty($cta1_link_data['url']);

        if ($has_cta1_link) {
            $this->add_link_attributes('cta_primary_link', $cta1_link_data);
        }

        $cta2_text = esc_html($settings['cta_secondary_text'] ?? '');

        $cta2_link_type = $settings['cta_secondary_link_type'] ?? 'external';
        $cta2_link_data = [];

        if ($cta2_enabled && 'internal' === $cta2_link_type) {
            $cta2_internal_post_id = (int) ($settings['cta_secondary_internal_post'] ?? 0);

            if ($cta2_internal_post_id > 0) {
                $cta2_internal_url = get_permalink($cta2_internal_post_id);

                if (is_string($cta2_internal_url) && '' !== $cta2_internal_url) {
                    $cta2_link_data = [
                        'url'         => $cta2_internal_url,
                        'is_external' => false,
                        'nofollow'    => false,
                    ];
                }
            }
        } elseif ($cta2_enabled && 'external' === $cta2_link_type) {
            $cta2_link_data = $settings['cta_secondary_url'] ?? [];
        }

        $has_cta2_link = ! empty($cta2_link_data['url']);

        if ($has_cta2_link) {
            $this->add_link_attributes('cta_secondary_link', $cta2_link_data);
        }

        ?>
        <section class="cc-hero cc-hero--theme-<?php echo esc_attr($theme_preset); ?>" style="<?php echo esc_attr($hero_style); ?>" <?php if ($heading_intro || $heading_marked) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Hero section', 'cupcake'); ?>"<?php endif; ?>>
            <div class="cc-hero__inner">
                <div class="cc-hero__content">
                    <?php if ($label_text) : ?>
                        <span class="cc-hero__pill"><?php echo $label_text; ?></span>
                    <?php endif; ?>

                    <?php if ($heading_intro || $heading_marked) : ?>
                        <<?php echo esc_attr($heading_tag); ?> id="<?php echo esc_attr($heading_id); ?>" class="cc-hero__heading<?php echo $heading_style ? ' ' . esc_attr($heading_style) : ''; ?>">
                            <?php echo $heading_intro; ?>
                            <?php if ($heading_marked) : ?>
                                <span class="cc-hero__heading-marked"><?php echo $heading_marked; ?></span>
                            <?php endif; ?>
                        </<?php echo esc_attr($heading_tag); ?>>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="cc-hero__subheading"><?php echo $description; ?></p>
                    <?php endif; ?>

                    <div class="cc-hero__actions">
                        <?php if ($cta1_enabled && $cta1_text) : ?>
                            <?php if ($has_cta1_link) : ?>
                                <a class="cc-hero__btn cc-hero__btn--primary" <?php echo $this->get_render_attribute_string('cta_primary_link'); ?>>
                                    <?php echo $cta1_text; ?>
                                </a>
                            <?php else : ?>
                                <span class="cc-hero__btn cc-hero__btn--primary"><?php echo $cta1_text; ?></span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($cta2_enabled && $cta2_text) : ?>
                            <?php if ($has_cta2_link) : ?>
                                <a class="cc-hero__btn cc-hero__btn--secondary" <?php echo $this->get_render_attribute_string('cta_secondary_link'); ?>>
                                    <?php echo $cta2_text; ?>
                                </a>
                            <?php else : ?>
                                <span class="cc-hero__btn cc-hero__btn--secondary"><?php echo $cta2_text; ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cc-hero__media">
                    <?php if ($hero_image_url) : ?>
                        <div class="cc-hero__image-wrap">
                            <img src="<?php echo $hero_image_url; ?>"
                                 alt="<?php echo $hero_image_alt; ?>"
                                 class="cc-hero__image"
                                 loading="lazy" />
                        </div>
                    <?php endif; ?>

                    <?php if ($float_number || $float_text) : ?>
                        <div class="cc-hero__float-card cc-hero__float-card--top">
                            <?php if ($float_number) : ?>
                                <span class="cc-hero__float-number"><?php echo $float_number; ?></span>
                            <?php endif; ?>

                            <?php if ($float_text) : ?>
                                <span class="cc-hero__float-text"><?php echo nl2br($float_text); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($float_status) : ?>
                        <div class="cc-hero__float-card cc-hero__float-card--bottom">
                            <span class="cc-hero__float-dot" aria-hidden="true"></span>
                            <span class="cc-hero__float-status"><?php echo $float_status; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
