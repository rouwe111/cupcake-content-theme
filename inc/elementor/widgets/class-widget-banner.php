<?php
/**
 * CupCake Theme — Elementor Banner Widget.
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
 * Notification banner with an optional icon and a message.
 */
class CupCake_Widget_Banner extends Widget_Base {
    use CupCake_Color_Sets;

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
        return 'cupcake-banner';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Banner', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-alert';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['banner', 'notification', 'alert', 'message', 'cupcake'];
    }

    /** {@inheritdoc} */
    protected function register_controls(): void {

        // =====================================================================
        // SECTION: Content
        // =====================================================================
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'cupcake'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label'        => __('Show icon', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'     => __('Icon', 'cupcake'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-exclamation-circle',
                    'library' => 'solid',
                ],
                'condition' => [
                    'show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'message',
            [
                'label'       => __('Message', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('This is an important notification message.', 'cupcake'),
                'placeholder' => __('Enter the banner message', 'cupcake'),
                'rows'        => 3,
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();

        // =====================================================================
        // SECTION: Style
        // =====================================================================
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
            'banner_bg',
            [
                'label'     => __('Background color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFF1DC',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'banner_border',
            [
                'label'     => __('Border color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F2E0C3',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#D98A2B',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'message_color',
            [
                'label'     => __('Message color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5A4A2F',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $message = wp_kses_post($settings['message'] ?? '');

        if ('' === trim((string) $message)) {
            return;
        }

        $resolved_colors = $this->resolve_color_set(
            (string) ($settings['color_set'] ?? 'sand'),
            [
                'card_bg'    => (string) ($settings['banner_bg'] ?? '#FFF1DC'),
                'card_border'=> (string) ($settings['banner_border'] ?? '#F2E0C3'),
                'icon_bg'    => '',
                'icon_color' => (string) ($settings['icon_color'] ?? '#D98A2B'),
            ]
        );

        $banner_bg     = esc_attr($resolved_colors['card_bg']);
        $banner_border = esc_attr($resolved_colors['card_border']);
        $icon_color    = esc_attr($resolved_colors['icon_color']);
        $message_color = esc_attr($settings['message_color'] ?? '#5A4A2F');

        $style = sprintf(
            '--cc-banner-bg:%s;--cc-banner-border:%s;--cc-banner-icon-color:%s;--cc-banner-message-color:%s;',
            $banner_bg,
            $banner_border,
            $icon_color,
            $message_color
        );

        $show_icon = 'yes' === ($settings['show_icon'] ?? 'yes');
        $icon_html = '';

        if ($show_icon && ! empty($settings['selected_icon'])) {
            ob_start();
            Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
            $icon_html = trim((string) ob_get_clean());
        }
        ?>
        <div class="cc-banner" role="alert" style="<?php echo esc_attr($style); ?>">
            <?php if ('' !== $icon_html) : ?>
                <span class="cc-banner__icon" aria-hidden="true">
                    <?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </span>
            <?php endif; ?>

            <div class="cc-banner__message"><?php echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        </div>
        <?php
    }
}
