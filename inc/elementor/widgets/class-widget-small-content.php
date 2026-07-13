<?php
/**
 * CupCake Theme — Elementor Small Content Widget.
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
 * Compact content card for short supporting text blocks.
 */
class CupCake_Widget_Small_Content extends Widget_Base {
    use CupCake_Color_Sets;
    use CupCake_Heading_Tag;

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-small-content';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Small Content', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-editor-list-ul';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['content', 'small', 'info', 'card', 'cupcake'];
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
                'default'     => __('Snel een vast pakket?', 'cupcake'),
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
                'default'     => __('Kies voor de zekerheid van een vast bedrag per maand en een vast aanspreekpunt voor je online marketing.', 'cupcake'),
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
                'default' => 'sand',
                'options' => $this->get_color_set_options(),
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'   => __('Card background', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#FFF1DC',
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

        $title       = trim((string) ($settings['title'] ?? ''));
        $title_tag   = $this->sanitize_heading_tag((string) ($settings['title_tag'] ?? 'h3'), 'h3');
        $title_style = $this->resolve_title_style_class((string) ($settings['title_style'] ?? ''));
        $description = trim((string) ($settings['description'] ?? ''));

        $resolved_colors = $this->resolve_color_set(
            (string) ($settings['color_set'] ?? 'sand'),
            [
                'card_bg'    => (string) ($settings['card_bg'] ?? '#FFF1DC'),
                'card_border'=> '#00000000',
                'icon_bg'    => '#00000000',
                'icon_color' => '#00000000',
            ]
        );

        $style = sprintf(
            '--cc-small-content-bg:%s;--cc-small-content-title:%s;--cc-small-content-text:%s;',
            esc_attr($resolved_colors['card_bg']),
            esc_attr($settings['title_color'] ?? '#211F1E'),
            esc_attr($settings['description_color'] ?? '#6B635F')
        );
        ?>
        <aside class="cc-small-content" style="<?php echo esc_attr($style); ?>">
            <?php if ('' !== $title) : ?>
                <<?php echo esc_attr($title_tag); ?> class="cc-small-content__title<?php echo $title_style ? ' ' . esc_attr($title_style) : ''; ?>"><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

            <?php if ('' !== $description) : ?>
                <p class="cc-small-content__description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </aside>
        <?php
    }
}
