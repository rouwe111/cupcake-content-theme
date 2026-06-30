<?php
/**
 * CupCake Theme — Elementor Section Intro Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Reusable section intro block with eyebrow, title, and description.
 */
class CupCake_Widget_Section_Intro extends Widget_Base {

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

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-section-intro';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Section Intro', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-t-letter';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['section', 'intro', 'heading', 'subtitle', 'title', 'description', 'cupcake'];
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
            'eyebrow',
            [
                'label'       => __('Subtitle / Eyebrow', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Diensten', 'cupcake'),
                'placeholder' => __('Enter subtitle', 'cupcake'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Alles voor jouw online groei', 'cupcake'),
                'placeholder' => __('Enter title', 'cupcake'),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => __('Title HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => $this->get_heading_tag_options(),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'       => __('Description', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Van strategie tot creatie. Ik neem je online marketing uit handen, zodat jij je kunt richten op waar je goed in bent: ondernemen.', 'cupcake'),
                'placeholder' => __('Enter description', 'cupcake'),
                'rows'        => 4,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'cupcake'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __('Left', 'cupcake'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'cupcake'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                ],
            ]
        );

        $this->add_control(
            'max_width',
            [
                'label'      => __('Max width (px)', 'cupcake'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 320,
                        'max'  => 1200,
                        'step' => 10,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 640,
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
            'eyebrow_color',
            [
                'label'   => __('Subtitle color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '',
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

        $eyebrow     = esc_html($settings['eyebrow'] ?? '');
        $title       = esc_html($settings['title'] ?? '');
        $title_tag   = strtolower((string) ($settings['title_tag'] ?? 'h2'));
        $title_tag   = in_array($title_tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'], true) ? $title_tag : 'h2';
        $description = wp_kses_post($settings['description'] ?? '');

        $align = in_array($settings['align'] ?? 'center', ['left', 'center'], true) ? $settings['align'] : 'center';
        $max_width = (int) ($settings['max_width']['size'] ?? 640);

        $eyebrow_color     = sanitize_hex_color((string) ($settings['eyebrow_color'] ?? ''));
        $title_color       = esc_attr($settings['title_color'] ?? '#211F1E');
        $description_color = esc_attr($settings['description_color'] ?? '#6B635F');

        $wrapper_style = sprintf(
            '--cc-section-intro-max-width:%dpx;--cc-section-intro-title-color:%s;--cc-section-intro-description-color:%s;',
            $max_width,
            $title_color,
            $description_color
        );

        if ($eyebrow_color) {
            $wrapper_style .= '--cc-section-intro-eyebrow-color:' . esc_attr($eyebrow_color) . ';';
        }
        ?>
        <div class="cc-section-intro cc-section-intro--<?php echo esc_attr($align); ?>" style="<?php echo esc_attr($wrapper_style); ?>">
            <?php if ($eyebrow) : ?>
                <span class="cc-section-intro__eyebrow"><?php echo $eyebrow; ?></span>
            <?php endif; ?>

            <?php if ($title) : ?>
                <<?php echo esc_attr($title_tag); ?> class="cc-section-intro__title"><?php echo $title; ?></<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

            <?php if ($description) : ?>
                <p class="cc-section-intro__description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
