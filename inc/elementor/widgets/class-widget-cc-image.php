<?php
/**
 * CupCake Theme — Elementor CC Image Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

/**
 * Standalone version of the Hero widget's framed image treatment, with
 * configurable shadow, white border, border radius, and rotation.
 */
class CupCake_Widget_CC_Image extends Widget_Base {

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-cc-image';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CC Image', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-image';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['image', 'photo', 'frame', 'rotate', 'cupcake'];
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
            'image',
            [
                'label'   => __('Image', 'cupcake'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => ''],
            ]
        );

        $this->add_control(
            'image_alt',
            [
                'label'       => __('Alt text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('Describe the image', 'cupcake'),
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
            'show_border',
            [
                'label'        => __('White border', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_shadow',
            [
                'label'        => __('Shadow', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'is_circle',
            [
                'label'        => __('Circle', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius (px)', 'cupcake'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'default'    => ['unit' => 'px', 'size' => 34],
                'condition'  => [
                    'is_circle!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'rotation',
            [
                'label'      => __('Rotation (deg)', 'cupcake'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range'      => [
                    'deg' => [
                        'min'  => -45,
                        'max'  => 45,
                        'step' => 0.5,
                    ],
                ],
                'default'    => ['unit' => 'deg', 'size' => 1.6],
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $image_url = esc_url($settings['image']['url'] ?? '');

        if ('' === $image_url) {
            return;
        }

        $image_alt     = esc_attr($settings['image_alt'] ?? '');
        $show_border   = 'yes' === ($settings['show_border'] ?? 'yes');
        $show_shadow   = 'yes' === ($settings['show_shadow'] ?? 'yes');
        $is_circle     = 'yes' === ($settings['is_circle'] ?? '');
        $border_radius = (float) ($settings['border_radius']['size'] ?? 34);
        $rotation      = (float) ($settings['rotation']['size'] ?? 1.6);

        $wrap_style = sprintf(
            'border-radius:%1$s;border:%2$s;box-shadow:%3$s;transform:rotate(%4$sdeg);%5$s',
            $is_circle ? '50%' : esc_attr((string) $border_radius) . 'px',
            $show_border ? '10px solid #fff' : 'none',
            $show_shadow ? '0 44px 80px -42px rgba(33, 31, 30, 0.45)' : 'none',
            esc_attr((string) $rotation),
            $is_circle ? 'aspect-ratio:1/1;' : ''
        );

        ?>
        <div class="cc-image">
            <div class="cc-image__wrap<?php echo $is_circle ? ' cc-image__wrap--circle' : ''; ?>" style="<?php echo esc_attr($wrap_style); ?>">
                <img src="<?php echo $image_url; ?>"
                     alt="<?php echo $image_alt; ?>"
                     class="cc-image__img"
                     loading="lazy" />
            </div>
        </div>
        <?php
    }
}
