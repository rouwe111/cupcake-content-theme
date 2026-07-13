<?php
/**
 * CupCake Theme — Elementor Bloom Banner Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-heading-tag.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Standout bottom CTA banner with decorative background shapes.
 */
class CupCake_Widget_Bloom_Banner extends Widget_Base {
    use CupCake_Heading_Tag;

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
        return 'cupcake-bloom-banner';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Bloom Banner', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-call-to-action';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['cta', 'banner', 'bottom', 'standout', 'cupcake'];
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
            'heading',
            [
                'label'       => __('Heading', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Klaar om te bloeien?', 'cupcake'),
                'placeholder' => __('Enter heading', 'cupcake'),
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label'   => __('Heading HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
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
                'default'     => __('Plan een gratis adviesgesprek. Samen kijken we waar jouw kansen liggen — zonder verplichtingen.', 'cupcake'),
                'placeholder' => __('Enter description', 'cupcake'),
                'rows'        => 3,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => __('Button text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Vraag je gratis advies aan', 'cupcake'),
                'placeholder' => __('Enter button text', 'cupcake'),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'button_link_type',
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
                'label'         => __('Button URL', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com/contact', 'cupcake'),
                'show_external' => true,
                'default'       => [
                    'url'         => home_url('/contact'),
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'button_link_type' => 'external',
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
            'banner_bg',
            [
                'label'   => __('Banner background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FA4D56',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label'   => __('Heading color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'   => __('Description color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFE6E4',
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label'   => __('Button background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4E7D5B',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'   => __('Button text color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
            ]
        );

        $this->add_control(
            'shape_color',
            [
                'label'   => __('Shape color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.14)',
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $heading       = esc_html($settings['heading'] ?? '');
        $heading_tag   = $this->sanitize_heading_tag((string) ($settings['heading_tag'] ?? 'h2'), 'h2');
        $heading_style = $this->resolve_title_style_class((string) ($settings['heading_title_style'] ?? ''));
        $description   = wp_kses_post($settings['description'] ?? '');
        $button_text = esc_html($settings['button_text'] ?? '');
        $heading_id  = $this->get_id() . '-heading';

        $link_type = $settings['button_link_type'] ?? 'external';
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

        $style = sprintf(
            '--cc-bloom-banner-bg:%s;--cc-bloom-banner-heading:%s;--cc-bloom-banner-description:%s;--cc-bloom-banner-button-bg:%s;--cc-bloom-banner-button-text:%s;--cc-bloom-banner-shape:%s;',
            esc_attr($settings['banner_bg'] ?? '#FA4D56'),
            esc_attr($settings['heading_color'] ?? '#FFFFFF'),
            esc_attr($settings['description_color'] ?? '#FFE6E4'),
            esc_attr($settings['button_bg'] ?? '#4E7D5B'),
            esc_attr($settings['button_text_color'] ?? '#FFFFFF'),
            esc_attr($settings['shape_color'] ?? 'rgba(255,255,255,0.14)')
        );

        $has_button_link = ! empty($link_data['url']);

        if ($has_button_link) {
            $this->add_render_attribute('button_url', 'aria-label', __('Open call to action', 'cupcake'));
            $this->add_link_attributes('button_url', $link_data);
        }
        ?>
        <section class="cc-bloom-banner" style="<?php echo esc_attr($style); ?>" <?php if ($heading) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Call to action', 'cupcake'); ?>"<?php endif; ?>>
            <span class="cc-bloom-banner__shape cc-bloom-banner__shape--top" aria-hidden="true"></span>
            <span class="cc-bloom-banner__shape cc-bloom-banner__shape--bottom" aria-hidden="true"></span>

            <div class="cc-bloom-banner__inner">
                <?php if ($heading) : ?>
                    <<?php echo esc_attr($heading_tag); ?> id="<?php echo esc_attr($heading_id); ?>" class="cc-bloom-banner__heading<?php echo $heading_style ? ' ' . esc_attr($heading_style) : ''; ?>"><?php echo $heading; ?></<?php echo esc_attr($heading_tag); ?>>
                <?php endif; ?>

                <?php if ($description) : ?>
                    <p class="cc-bloom-banner__description"><?php echo $description; ?></p>
                <?php endif; ?>

                <?php if ($button_text && $has_button_link) : ?>
                    <a class="cc-bloom-banner__button" <?php echo $this->get_render_attribute_string('button_url'); ?>>
                        <?php echo $button_text; ?>
                    </a>
                <?php elseif ($button_text) : ?>
                    <span class="cc-bloom-banner__button"><?php echo $button_text; ?></span>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
