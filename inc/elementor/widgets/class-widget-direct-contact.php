<?php
/**
 * CupCake Theme — Elementor Direct Contact Widget.
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
 * Contact information card with icon rows and social links.
 */
class CupCake_Widget_Direct_Contact extends Widget_Base {
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
        return 'cupcake-direct-contact';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Direct Contact', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-mail';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['contact', 'direct', 'email', 'social', 'card', 'cupcake'];
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
                'label'       => __('Title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Direct contact', 'cupcake'),
                'placeholder' => __('Enter title', 'cupcake'),
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label'   => __('Title HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
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

        $items_repeater = new Repeater();

        $items_repeater->add_control(
            'item_icon',
            [
                'label'   => __('Icon', 'cupcake'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'far fa-envelope',
                    'library' => 'regular',
                ],
            ]
        );

        $items_repeater->add_control(
            'item_text',
            [
                'label'       => __('Text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('info@cupcakecontent.nl', 'cupcake'),
                'label_block' => true,
            ]
        );

        $items_repeater->add_control(
            'item_link',
            [
                'label'         => __('Link (optional)', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('mailto:info@cupcakecontent.nl', 'cupcake'),
                'show_external' => false,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Contact rows', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $items_repeater->get_controls(),
                'title_field' => '{{{ item_text }}}',
                'default'     => [
                    [
                        'item_text' => __('info@cupcakecontent.nl', 'cupcake'),
                        'item_link' => [
                            'url' => 'mailto:info@cupcakecontent.nl',
                        ],
                    ],
                    [
                        'item_icon' => [
                            'value'   => 'fas fa-map-marker-alt',
                            'library' => 'solid',
                        ],
                        'item_text' => __('Regio Zwolle', 'cupcake'),
                    ],
                ],
            ]
        );

        $social_repeater = new Repeater();

        $social_repeater->add_control(
            'social_icon',
            [
                'label'   => __('Icon', 'cupcake'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fab fa-instagram',
                    'library' => 'brands',
                ],
            ]
        );

        $social_repeater->add_control(
            'social_link',
            [
                'label'         => __('Link', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://instagram.com/your-profile', 'cupcake'),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => true,
                    'nofollow'    => false,
                ],
            ]
        );

        $social_repeater->add_control(
            'social_label',
            [
                'label'       => __('Accessible label', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Social profile', 'cupcake'),
                'placeholder' => __('e.g. Instagram profile', 'cupcake'),
            ]
        );

        $this->add_control(
            'social_items',
            [
                'label'       => __('Social icons', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $social_repeater->get_controls(),
                'title_field' => __('Social link', 'cupcake'),
                'default'     => [
                    [
                        'social_icon' => [
                            'value'   => 'fab fa-instagram',
                            'library' => 'brands',
                        ],
                        'social_link' => [
                            'url' => 'https://www.instagram.com/cupcakecontent',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value'   => 'fab fa-linkedin-in',
                            'library' => 'brands',
                        ],
                        'social_link' => [
                            'url' => 'https://www.linkedin.com/company/cupcake-content/',
                        ],
                    ],
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
                'default' => 'sage',
                'options' => $this->get_color_set_options(),
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'   => __('Card background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#EAF3EC',
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
            'text_color',
            [
                'label'   => __('Text color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#2E4D39',
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label'   => __('Icon background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
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
                'default' => '#4E7D5B',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $title         = trim((string) ($settings['title'] ?? ''));
        $heading_tag   = $this->sanitize_heading_tag((string) ($settings['heading_tag'] ?? 'h3'), 'h3');
        $heading_style = $this->resolve_title_style_class((string) ($settings['heading_title_style'] ?? ''));
        $items         = $settings['items'] ?? [];
        $social_items = $settings['social_items'] ?? [];
        $heading_id   = $this->get_id() . '-title';

        $resolved_colors = $this->resolve_color_set(
            (string) ($settings['color_set'] ?? 'sage'),
            [
                'card_bg'    => (string) ($settings['card_bg'] ?? '#EAF3EC'),
                'card_border'=> '#00000000',
                'icon_bg'    => (string) ($settings['icon_bg'] ?? '#FFFFFF'),
                'icon_color' => (string) ($settings['icon_color'] ?? '#4E7D5B'),
            ]
        );

        $style = sprintf(
            '--cc-direct-contact-bg:%s;--cc-direct-contact-title:%s;--cc-direct-contact-text:%s;--cc-direct-contact-icon-bg:%s;--cc-direct-contact-icon:%s;',
            esc_attr($resolved_colors['card_bg']),
            esc_attr($settings['title_color'] ?? '#211F1E'),
            esc_attr($settings['text_color'] ?? '#2E4D39'),
            esc_attr($resolved_colors['icon_bg']),
            esc_attr($resolved_colors['icon_color'])
        );
        ?>
        <section class="cc-direct-contact" style="<?php echo esc_attr($style); ?>" <?php if ('' !== $title) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Direct contact', 'cupcake'); ?>"<?php endif; ?>>
            <?php if ('' !== $title) : ?>
                <<?php echo esc_attr($heading_tag); ?> id="<?php echo esc_attr($heading_id); ?>" class="cc-direct-contact__title<?php echo $heading_style ? ' ' . esc_attr($heading_style) : ''; ?>"><?php echo esc_html($title); ?></<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>

            <?php if (is_array($items) && ! empty($items)) : ?>
                <ul class="cc-direct-contact__list">
                    <?php foreach ($items as $index => $item) : ?>
                        <?php
                        $text = trim((string) ($item['item_text'] ?? ''));
                        if ('' === $text) {
                            continue;
                        }

                        $item_key = 'direct_contact_item_' . (string) $index;
                        $has_link = ! empty($item['item_link']['url']);

                        if ($has_link) {
                            $this->add_link_attributes($item_key, $item['item_link']);
                        }
                        ?>
                        <li class="cc-direct-contact__item">
                            <span class="cc-direct-contact__icon" aria-hidden="true">
                                <?php Icons_Manager::render_icon($item['item_icon'] ?? [], ['aria-hidden' => 'true']); ?>
                            </span>

                            <?php if ($has_link) : ?>
                                <a class="cc-direct-contact__link" <?php echo $this->get_render_attribute_string($item_key); ?>>
                                    <?php echo esc_html($text); ?>
                                </a>
                            <?php else : ?>
                                <span class="cc-direct-contact__text"><?php echo esc_html($text); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (is_array($social_items) && ! empty($social_items)) : ?>
                <div class="cc-direct-contact__social" aria-label="<?php echo esc_attr__('Social links', 'cupcake'); ?>">
                    <?php foreach ($social_items as $index => $item) : ?>
                        <?php
                        $url = trim((string) ($item['social_link']['url'] ?? ''));
                        if ('' === $url) {
                            continue;
                        }

                        $item_key = 'direct_contact_social_' . (string) $index;
                        $label    = trim((string) ($item['social_label'] ?? ''));
                        if ('' === $label) {
                            $label = __('Social profile', 'cupcake');
                        }

                        $this->add_render_attribute($item_key, 'aria-label', $label);
                        $this->add_link_attributes($item_key, $item['social_link']);
                        ?>
                        <a class="cc-direct-contact__social-link" <?php echo $this->get_render_attribute_string($item_key); ?>>
                            <?php Icons_Manager::render_icon($item['social_icon'] ?? [], ['aria-hidden' => 'true']); ?>
                            <span class="screen-reader-text"><?php echo esc_html($label); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
