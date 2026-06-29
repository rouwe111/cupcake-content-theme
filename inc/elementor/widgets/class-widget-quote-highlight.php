<?php
/**
 * CupCake Theme — Elementor Quote Highlight Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Small highlighted quote block for blog posts.
 */
class CupCake_Widget_Quote_Highlight extends Widget_Base {

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-quote-highlight';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Quote Highlight', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-blockquote';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['quote', 'blockquote', 'blog', 'highlight', 'cupcake'];
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
            'quote_text',
            [
                'label'       => __('Quote text', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Mijn Hobby Keuken gaf me het vertrouwen om Cupcake Content te starten.', 'cupcake'),
                'placeholder' => __('Type your quote here', 'cupcake'),
                'rows'        => 3,
            ]
        );

        $this->add_control(
            'quote_author',
            [
                'label'       => __('Author (optional)', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('e.g. Janique', 'cupcake'),
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
            'quote_background',
            [
                'label'   => __('Background color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#EAF3EC',
            ]
        );

        $this->add_control(
            'quote_border_color',
            [
                'label'   => __('Accent line color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#4E7D5B',
            ]
        );

        $this->add_control(
            'quote_text_color',
            [
                'label'   => __('Text color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#2E4D39',
            ]
        );

        $this->add_control(
            'quote_author_color',
            [
                'label'   => __('Author color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#3F6A4B',
            ]
        );

        $this->add_control(
            'font_size',
            [
                'label'      => __('Quote font size (px)', 'cupcake'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 14,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
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
                        'min'  => 260,
                        'max'  => 1200,
                        'step' => 10,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 1000,
                ],
            ]
        );

        $this->add_control(
            'align',
            [
                'label'   => __('Alignment', 'cupcake'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
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

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $quote_text   = trim((string) ($settings['quote_text'] ?? ''));
        $quote_author = trim((string) ($settings['quote_author'] ?? ''));

        if ('' === $quote_text) {
            return;
        }

        $quote_background = esc_attr($settings['quote_background'] ?? '#EAF3EC');
        $quote_line_color = esc_attr($settings['quote_border_color'] ?? '#4E7D5B');
        $quote_text_color = esc_attr($settings['quote_text_color'] ?? '#2E4D39');
        $quote_author_color = esc_attr($settings['quote_author_color'] ?? '#3F6A4B');
        $quote_font_size  = (int) ($settings['font_size']['size'] ?? 20);
        $max_width        = (int) ($settings['max_width']['size'] ?? 1000);
        $align            = in_array($settings['align'] ?? 'left', ['left', 'center'], true) ? $settings['align'] : 'left';

        $style = sprintf(
            '--cc-quote-widget-bg:%s;--cc-quote-widget-line:%s;--cc-quote-widget-text:%s;--cc-quote-widget-author:%s;--cc-quote-widget-size:%dpx;--cc-quote-widget-max-width:%dpx;',
            $quote_background,
            $quote_line_color,
            $quote_text_color,
            $quote_author_color,
            $quote_font_size,
            $max_width
        );
        ?>
        <div class="cc-quote-widget-wrap cc-quote-widget-wrap--<?php echo esc_attr($align); ?>" style="<?php echo esc_attr($style); ?>">
            <blockquote class="cc-quote-widget">
                <p class="cc-quote-widget__text"><?php echo esc_html($quote_text); ?></p>

                <?php if ('' !== $quote_author) : ?>
                    <cite class="cc-quote-widget__author"><?php echo esc_html($quote_author); ?></cite>
                <?php endif; ?>
            </blockquote>
        </div>
        <?php
    }
}
