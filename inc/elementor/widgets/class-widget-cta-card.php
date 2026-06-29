<?php
/**
 * CupCake Theme — Elementor CTA Card Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

/**
 * Feature / call-to-action card with icon, heading, body text, and a link.
 */
class CupCake_Widget_CTA_Card extends Widget_Base {

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
    public function get_style_depends(): array {
        return [
            'elementor-icons',
            'elementor-icons-fa-solid',
            'elementor-icons-fa-regular',
            'elementor-icons-fa-brands',
        ];
    }

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-cta-card';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake CTA Card', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-price-table';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['card', 'cta', 'feature', 'icon', 'cupcake'];
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
            'selected_icon',
            [
                'label'   => __('Icon', 'cupcake'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'heading',
            [
                'label'       => __('Heading', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Feature Title', 'cupcake'),
                'placeholder' => __('Enter card heading', 'cupcake'),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'body_text',
            [
                'label'       => __('Body text', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('A concise description of this feature or benefit. Keep it focused and actionable.', 'cupcake'),
                'placeholder' => __('Describe this feature', 'cupcake'),
                'rows'        => 4,
            ]
        );

        $this->add_control(
            'cta_label',
            [
                'label'       => __('CTA label', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Learn more', 'cupcake'),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'cta_link_type',
            [
                'label'     => __('Link type', 'cupcake'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'external',
                'options'   => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cta_internal_post',
            [
                'label'       => __('Select page or post', 'cupcake'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_internal_link_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'cta_link_type' => 'internal',
                ],
            ]
        );

        $this->add_control(
            'cta_url',
            [
                'label'         => __('CTA URL', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'cupcake'),
                'show_external' => true,
                'default'       => ['url' => '#'],
                'condition'     => [
                    'cta_link_type' => 'external',
                ],
            ]
        );

        $this->end_controls_section();

        // =====================================================================
        // SECTION: Card Style
        // =====================================================================
        $this->start_controls_section(
            'section_card_style',
            [
                'label' => __('Card Style', 'cupcake'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label'   => __('Card background color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => __('Border radius (px)', 'cupcake'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'default'    => ['unit' => 'px', 'size' => 8],
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label'      => __('Padding (px)', 'cupcake'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 8,
                        'max'  => 64,
                        'step' => 4,
                    ],
                ],
                'default'    => ['unit' => 'px', 'size' => 32],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E94560',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings      = $this->get_settings_for_display();
        $bg_color      = esc_attr($settings['card_bg_color'] ?? '#FFFFFF');
        $border_radius = (int) ($settings['card_border_radius']['size'] ?? 8);
        $padding       = (int) ($settings['card_padding']['size'] ?? 32);
        $icon_color    = esc_attr($settings['icon_color'] ?? '#E94560');

        $heading   = esc_html($settings['heading'] ?? '');
        $body_text = wp_kses_post($settings['body_text'] ?? '');
        $heading_id = $this->get_id() . '-heading';

        $cta_label = esc_html($settings['cta_label'] ?? '');

        $link_type = $settings['cta_link_type'] ?? 'external';
        $link_data = [];

        if ('internal' === $link_type) {
            $internal_post_id = (int) ($settings['cta_internal_post'] ?? 0);

            if ($internal_post_id > 0) {
                $internal_url = get_permalink($internal_post_id);

                if (is_string($internal_url) && '' !== $internal_url) {
                    $link_data = [
                        'url'         => $internal_url,
                        'is_external' => false,
                        'nofollow'    => false,
                    ];
                }
            }
        } elseif ('external' === $link_type) {
            $link_data = $settings['cta_url'] ?? [];
        }

        $has_link = ! empty($link_data['url']);

        if ($has_link) {
            $this->add_render_attribute('cta_link', 'aria-label', __('Open call to action', 'cupcake'));
            $this->add_link_attributes('cta_link', $link_data);
        }

        $card_style = sprintf(
            'background-color:%s;border-radius:%dpx;padding:%dpx;',
            $bg_color,
            $border_radius,
            $padding
        );
        ?>
        <div class="cc-card" style="<?php echo esc_attr($card_style); ?>" <?php if ($heading) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Call to action card', 'cupcake'); ?>"<?php endif; ?>>
            <?php if (! empty($settings['selected_icon']['value'])) : ?>
                <div class="cc-card__icon" style="color:<?php echo $icon_color; ?>;" aria-hidden="true">
                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </div>
            <?php endif; ?>

            <?php if ($heading) : ?>
                <h3 id="<?php echo esc_attr($heading_id); ?>" class="cc-card__heading"><?php echo $heading; ?></h3>
            <?php endif; ?>

            <?php if ($body_text) : ?>
                <div class="cc-card__body"><?php echo $body_text; ?></div>
            <?php endif; ?>

            <?php if ($cta_label && $has_link) : ?>
                <a class="cc-card__link" <?php echo $this->get_render_attribute_string('cta_link'); ?>>
                    <?php echo $cta_label; ?>
                    <span class="cc-card__link-arrow" aria-hidden="true">&rarr;</span>
                </a>
            <?php elseif ($cta_label) : ?>
                <span class="cc-card__link">
                    <?php echo $cta_label; ?>
                    <span class="cc-card__link-arrow" aria-hidden="true">&rarr;</span>
                </span>
            <?php endif; ?>
        </div>
        <?php
    }
}
