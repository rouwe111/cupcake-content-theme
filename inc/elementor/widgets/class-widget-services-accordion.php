<?php
/**
 * CupCake Theme — Elementor Services Accordion Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-color-sets.php';
require_once __DIR__ . '/trait-heading-tag.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

/**
 * Services accordion with single-open behavior via existing accordion JS.
 */
class CupCake_Widget_Services_Accordion extends Widget_Base {
    use CupCake_Color_Sets;
    use CupCake_Heading_Tag;

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
        return 'cupcake-services-accordion';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Services Accordion', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-accordion';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['services', 'diensten', 'accordion', 'toggle', 'cupcake'];
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
            'title',
            [
                'label'       => __('Section title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Onze diensten', 'cupcake'),
                'placeholder' => __('Enter title', 'cupcake'),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => __('Section title HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => $this->get_heading_tag_options(),
            ]
        );

        $this->add_control(
            'title_style',
            [
                'label'   => __('Title style', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_title_style_options(),
            ]
        );

        $this->add_control(
            'open_first',
            [
                'label'        => __('Open first item by default', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'color_set',
            [
                'label'   => __('Color set', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'rose',
                'options' => $this->get_color_set_options(),
            ]
        );

        $repeater->add_control(
            'icon_bg',
            [
                'label'     => __('Icon background', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFE9E7',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FA4D56',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'service_title',
            [
                'label'       => __('Service title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Content creatie', 'cupcake'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'service_icon',
            [
                'label'   => __('Icon', 'cupcake'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-camera',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'service_description',
            [
                'label'   => __('Service description', 'cupcake'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Foto, video en UGC die niet alleen wordt bekeken, maar ook gevoeld. Authentieke storytelling die past bij jouw merk.', 'cupcake'),
                'rows'    => 4,
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Service items', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ service_title }}}',
                'default'     => [
                    [
                        'color_set'           => 'rose',
                        'service_title'       => __('Content creatie', 'cupcake'),
                        'service_icon'        => [
                            'value'   => 'fas fa-camera',
                            'library' => 'solid',
                        ],
                        'service_description' => __('Foto, video en UGC die niet alleen wordt bekeken, maar ook gevoeld. Authentieke storytelling die past bij jouw merk.', 'cupcake'),
                    ],
                    [
                        'color_set'           => 'sage',
                        'service_title'       => __('Social media management', 'cupcake'),
                        'service_icon'        => [
                            'value'   => 'far fa-comments',
                            'library' => 'regular',
                        ],
                        'service_description' => __('Strategie, planning en community management. Ik bouw consistent aan je digitale identiteit - verder dan alleen posten.', 'cupcake'),
                    ],
                    [
                        'color_set'           => 'sand',
                        'service_title'       => __('SEO optimalisatie', 'cupcake'),
                        'service_icon'        => [
                            'value'   => 'fas fa-search',
                            'library' => 'solid',
                        ],
                        'service_description' => __('Een slim fundament dat staat als een huis. Word gevonden door de klanten die echt bij jou passen.', 'cupcake'),
                    ],
                    [
                        'color_set'           => 'berry',
                        'service_title'       => __('Online teksten en blogs', 'cupcake'),
                        'service_icon'        => [
                            'value'   => 'far fa-file-alt',
                            'library' => 'regular',
                        ],
                        'service_description' => __('Pakkende teksten en blogs die jouw verhaal vertellen en je vindbaarheid een boost geven.', 'cupcake'),
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings    = $this->get_settings_for_display();
        $title       = trim((string) ($settings['title'] ?? ''));
        $title_tag   = $this->sanitize_heading_tag((string) ($settings['title_tag'] ?? 'h2'), 'h2');
        $title_style = $this->resolve_title_style_class((string) ($settings['title_style'] ?? ''));
        $items       = $settings['items'] ?? [];
        $open_first  = 'yes' === ($settings['open_first'] ?? 'yes');

        if (! is_array($items) || empty($items)) {
            return;
        }

        $widget_id = (string) $this->get_id();
        ?>
        <section class="cc-faq-widget cc-services-accordion" aria-label="<?php echo esc_attr__('Services accordion', 'cupcake'); ?>">
            <?php if ('' !== $title) : ?>
                <<?php echo esc_attr($title_tag); ?> class="cc-faq-widget__title<?php echo $title_style ? ' ' . esc_attr($title_style) : ''; ?>"><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

            <div class="cc-faq-widget__list" data-cc-accordion="single">
                <?php foreach ($items as $index => $item) : ?>
                    <?php
                    $service_title       = trim((string) ($item['service_title'] ?? ''));
                    $service_description = trim((string) ($item['service_description'] ?? ''));

                    if ('' === $service_title || '' === $service_description) {
                        continue;
                    }

                    $resolved_colors = $this->resolve_color_set(
                        (string) ($item['color_set'] ?? 'rose'),
                        [
                            'card_bg'    => '#FFFFFF',
                            'card_border'=> '#F0E7DC',
                            'icon_bg'    => (string) ($item['icon_bg'] ?? '#FFE9E7'),
                            'icon_color' => (string) ($item['icon_color'] ?? '#FA4D56'),
                        ]
                    );

                    $item_style = sprintf(
                        '--cc-service-card-icon-bg:%s;--cc-service-card-icon-color:%s;',
                        esc_attr($resolved_colors['icon_bg']),
                        esc_attr($resolved_colors['icon_color'])
                    );

                    $service_icon_html = '';

                    if (! empty($item['service_icon'])) {
                        ob_start();
                        Icons_Manager::render_icon($item['service_icon'], ['aria-hidden' => 'true']);
                        $service_icon_html = trim((string) ob_get_clean());
                    }

                    if ('' === $service_icon_html) {
                        $icon_value = $item['service_icon']['value'] ?? '';

                        if (is_string($icon_value) && '' !== $icon_value) {
                            $service_icon_html = sprintf('<i class="%s" aria-hidden="true"></i>', esc_attr($icon_value));
                        }
                    }

                    $is_open   = $open_first && 0 === (int) $index;
                    $button_id = sprintf('cc-services-btn-%s-%d', $widget_id, (int) $index);
                    $panel_id  = sprintf('cc-services-panel-%s-%d', $widget_id, (int) $index);
                    ?>
                    <article class="cc-faq-widget__item<?php echo $is_open ? ' is-open' : ''; ?>" style="<?php echo esc_attr($item_style); ?>">
                        <h3 class="cc-faq-widget__heading">
                            <button
                                id="<?php echo esc_attr($button_id); ?>"
                                class="cc-faq-widget__trigger"
                                type="button"
                                aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($panel_id); ?>"
                            >
                                <span class="cc-services-accordion__service-head">
                                    <?php if ('' !== $service_icon_html) : ?>
                                        <span class="cc-service-card__icon cc-services-accordion__service-icon"><?php echo $service_icon_html; ?></span>
                                    <?php endif; ?>
                                    <span class="cc-faq-widget__question"><?php echo esc_html($service_title); ?></span>
                                </span>
                                <span class="cc-faq-widget__icon" aria-hidden="true"><?php echo $is_open ? '▾' : '▸'; ?></span>
                            </button>
                        </h3>

                        <div
                            id="<?php echo esc_attr($panel_id); ?>"
                            class="cc-faq-widget__panel"
                            role="region"
                            aria-labelledby="<?php echo esc_attr($button_id); ?>"
                            <?php echo $is_open ? '' : 'hidden'; ?>
                        >
                            <p class="cc-faq-widget__answer"><?php echo wp_kses_post($service_description); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
