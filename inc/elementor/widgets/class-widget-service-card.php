<?php
/**
 * CupCake Theme — Elementor Service Card Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-color-sets.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

/**
 * Service card block for grid layouts.
 */
class CupCake_Widget_Service_Card extends Widget_Base {
    use CupCake_Color_Sets;

    /**
     * Allowed semantic heading tag options.
     *
     * @return array<string, string>
     */
    private function get_heading_tag_options(): array {
        return [
            'h1' => __('H1', 'cupcake'),
            'h2' => __('H2', 'cupcake'),
            'h3' => __('H3', 'cupcake'),
            'h4' => __('H4', 'cupcake'),
            'h5' => __('H5', 'cupcake'),
            'h6' => __('H6', 'cupcake'),
            'p'  => __('Paragraph', 'cupcake'),
            'div'=> __('DIV', 'cupcake'),
        ];
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
        return 'cupcake-service-card';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Service Card', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-featured-image';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['service', 'card', 'feature', 'grid', 'icon', 'cupcake'];
    }

    /** {@inheritdoc} */
    protected function register_controls(): void {
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
                    'value'   => 'fas fa-camera',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Content creatie', 'cupcake'),
                'placeholder' => __('Enter card title', 'cupcake'),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => __('Title HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => $this->get_heading_tag_options(),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => __('Description', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Foto, video en UGC die niet alleen wordt bekeken, maar ook gevoeld. Authentieke storytelling die past bij jouw merk.', 'cupcake'),
                'placeholder' => __('Enter card description', 'cupcake'),
                'rows'        => 5,
            ]
        );

        $this->add_control(
            'card_link_type',
            [
                'label'   => __('Link type', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'external',
                'options' => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'card_internal_post',
            [
                'label'       => __('Select page or post', 'cupcake'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_internal_link_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'card_link_type' => 'internal',
                ],
            ]
        );

        $this->add_control(
            'card_link',
            [
                'label'         => __('Card link', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'cupcake'),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'card_link_type' => 'external',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'cupcake'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color_set',
            [
                'label'   => __('Color set', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => $this->get_color_set_options(),
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'   => __('Card background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'card_border',
            [
                'label'   => __('Card border color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#F4E7DC',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label'   => __('Icon background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFE9E7',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'   => __('Icon color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FA4D56',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'   => __('Title color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#211F1E',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'   => __('Description color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#6B635F',
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $title       = esc_html($settings['title'] ?? '');
        $title_tag   = strtolower((string) ($settings['title_tag'] ?? 'h3'));
        $title_tag   = in_array($title_tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'], true) ? $title_tag : 'h3';
        $description = wp_kses_post($settings['description'] ?? '');

        $resolved_colors = $this->resolve_color_set(
            (string) ($settings['color_set'] ?? 'custom'),
            [
                'card_bg'    => (string) ($settings['card_bg'] ?? '#FFFFFF'),
                'card_border'=> (string) ($settings['card_border'] ?? '#F4E7DC'),
                'icon_bg'    => (string) ($settings['icon_bg'] ?? '#FFE9E7'),
                'icon_color' => (string) ($settings['icon_color'] ?? '#FA4D56'),
            ]
        );

        // Keep service cards visually consistent: color sets only theme the icon.
        $card_bg           = '#FFFFFF';
        $card_border       = '#F0E7DC';
        $icon_bg           = esc_attr($resolved_colors['icon_bg']);
        $icon_color        = esc_attr($resolved_colors['icon_color']);
        $title_color       = esc_attr($settings['title_color'] ?? '#211F1E');
        $description_color = esc_attr($settings['description_color'] ?? '#6B635F');

        $style = sprintf(
            '--cc-service-card-bg:%s;--cc-service-card-border:%s;--cc-service-card-icon-bg:%s;--cc-service-card-icon-color:%s;--cc-service-card-title-color:%s;--cc-service-card-description-color:%s;',
            $card_bg,
            $card_border,
            $icon_bg,
            $icon_color,
            $title_color,
            $description_color
        );

        $icon_html = '';

        if (! empty($settings['selected_icon'])) {
            ob_start();
            Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
            $icon_html = trim((string) ob_get_clean());
        }

        if ('' === $icon_html) {
            $icon_value = $settings['selected_icon']['value'] ?? '';

            if (is_string($icon_value) && '' !== $icon_value) {
                $icon_html = sprintf('<i class="%s" aria-hidden="true"></i>', esc_attr($icon_value));
            }
        }

        $link_type = $settings['card_link_type'] ?? 'external';
        $link_data = [];

        if ('internal' === $link_type) {
            $internal_post_id = (int) ($settings['card_internal_post'] ?? 0);

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
            $link_data = $settings['card_link'] ?? [];
        }

        $has_link = ! empty($link_data['url']);

        if ($has_link) {
            $this->add_link_attributes('card_link', $link_data);
        }
        ?>
        <?php if ($has_link) : ?>
            <a class="cc-service-card__link-wrap" <?php echo $this->get_render_attribute_string('card_link'); ?>>
        <?php endif; ?>

        <article class="cc-service-card" style="<?php echo esc_attr($style); ?>">
            <?php if ('' !== $icon_html) : ?>
                <span class="cc-service-card__icon" aria-hidden="true">
                    <?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </span>
            <?php endif; ?>

            <?php if ($title) : ?>
                <<?php echo esc_attr($title_tag); ?> class="cc-service-card__title"><?php echo $title; ?></<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

            <?php if ($description) : ?>
                <p class="cc-service-card__description"><?php echo $description; ?></p>
            <?php endif; ?>
        </article>

        <?php if ($has_link) : ?>
            </a>
        <?php endif; ?>
        <?php
    }
}
