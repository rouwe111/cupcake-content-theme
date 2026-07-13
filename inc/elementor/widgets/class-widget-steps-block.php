<?php
/**
 * CupCake Theme — Elementor Steps Block Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-color-sets.php';
require_once __DIR__ . '/trait-heading-tag.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Single step card block intended for grid layouts.
 */
class CupCake_Widget_Steps_Block extends Widget_Base {
    use CupCake_Color_Sets;
    use CupCake_Heading_Tag;

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-steps-block';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Steps Block', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-number-field';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['steps', 'block', 'card', 'values', 'grid', 'cupcake'];
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
            'step_number',
            [
                'label'       => __('Step number', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '01',
                'placeholder' => __('01', 'cupcake'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Authentiek', 'cupcake'),
                'placeholder' => __('Enter title', 'cupcake'),
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
            'title_style',
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
                'default'     => __('Geen gladde reclame, maar echte verhalen. Laat je keuken, je mensen en je proces zien.', 'cupcake'),
                'placeholder' => __('Enter description', 'cupcake'),
                'rows'        => 4,
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
                'default' => 'rose',
                'options' => $this->get_color_set_options(),
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'     => __('Card background', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFE9E7',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'step_number_color',
            [
                'label'     => __('Step number color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FA4D56',
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
        $title_tag   = $this->sanitize_heading_tag((string) ($settings['title_tag'] ?? 'h3'), 'h3');
        $title_style = $this->resolve_title_style_class((string) ($settings['title_style'] ?? ''));
        $description = wp_kses_post($settings['description'] ?? '');
        $step_number = esc_html($settings['step_number'] ?? '');

        $resolved_colors = $this->resolve_color_set(
            (string) ($settings['color_set'] ?? 'rose'),
            [
                'card_bg'    => (string) ($settings['card_bg'] ?? '#FFE9E7'),
                'card_border'=> '#00000000',
                'icon_bg'    => '#00000000',
                'icon_color' => (string) ($settings['step_number_color'] ?? '#FA4D56'),
            ]
        );

        $style = sprintf(
            '--cc-steps-block-bg:%s;--cc-steps-block-number-color:%s;--cc-steps-block-title-color:%s;--cc-steps-block-description-color:%s;',
            esc_attr($resolved_colors['card_bg']),
            esc_attr($resolved_colors['icon_color']),
            esc_attr($settings['title_color'] ?? '#211F1E'),
            esc_attr($settings['description_color'] ?? '#6B635F')
        );
        ?>
        <article class="cc-steps-block" style="<?php echo esc_attr($style); ?>">
            <?php if ($step_number) : ?>
                <span class="cc-steps-block__number"><?php echo $step_number; ?></span>
            <?php endif; ?>

            <?php if ($title) : ?>
                <<?php echo esc_attr($title_tag); ?> class="cc-steps-block__title<?php echo $title_style ? ' ' . esc_attr($title_style) : ''; ?>"><?php echo $title; ?></<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

            <?php if ($description) : ?>
                <p class="cc-steps-block__description"><?php echo $description; ?></p>
            <?php endif; ?>
        </article>
        <?php
    }
}
