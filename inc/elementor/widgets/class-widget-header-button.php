<?php
/**
 * CupCake Theme — Elementor Header Button Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

/**
 * Reusable rounded CTA button for header templates.
 */
class CupCake_Widget_Header_Button extends Widget_Base {

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
        return 'cupcake-header-button';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Header Button', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-button';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['header', 'button', 'cta', 'cupcake'];
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
            'button_text',
            [
                'label'       => __('Text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Offerte aanvragen', 'cupcake'),
                'placeholder' => __('Button text', 'cupcake'),
            ]
        );

        $this->add_control(
            'button_link_type',
            [
                'label'     => __('Link type', 'cupcake'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'internal',
                'options'   => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_internal_post',
            [
                'label'       => __('Select page or post', 'cupcake'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_internal_link_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'button_link_type' => 'internal',
                ],
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label'         => __('Link', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'show_external' => true,
                'default'       => [
                    'url' => home_url('/contact'),
                ],
                'condition'     => [
                    'button_link_type' => 'external',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $text = trim((string) ($settings['button_text'] ?? ''));

        if ('' === $text) {
            return;
        }

        $link_type = $settings['button_link_type'] ?? 'internal';
        $link_data = [];

        if ('internal' === $link_type) {
            $internal_post_id = (int) ($settings['button_internal_post'] ?? 0);

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
            $link_data = $settings['button_url'] ?? [];
        }

        $has_link = ! empty($link_data['url']);

        if ($has_link) {
            $this->add_link_attributes('button_link', $link_data);
            echo '<a class="site-header__cta-link" ' . $this->get_render_attribute_string('button_link') . '>' . esc_html($text) . '</a>';
            return;
        }

        echo '<span class="site-header__cta-link">' . esc_html($text) . '</span>';
    }
}
