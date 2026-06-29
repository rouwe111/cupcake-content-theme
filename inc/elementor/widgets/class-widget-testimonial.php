<?php
/**
 * CupCake Theme — Elementor Testimonial Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;

/**
 * Styled testimonial / quote widget with avatar, author name, and role.
 */
class CupCake_Widget_Testimonial extends Widget_Base {

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
        return 'cupcake-testimonial';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Testimonial', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-testimonial';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['testimonial', 'quote', 'review', 'social proof', 'cupcake'];
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

        $repeater = new Repeater();

        $repeater->add_control(
            'quote',
            [
                'label'       => __('Quote', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Working with CupCake Studio transformed our online presence. The attention to detail and strategic thinking they bring to every project is unmatched.', 'cupcake'),
                'placeholder' => __('Enter the testimonial quote', 'cupcake'),
                'rows'        => 5,
            ]
        );

        $repeater->add_control(
            'author_name',
            [
                'label'       => __('Author name', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Alex Johnson', 'cupcake'),
                'placeholder' => __('Full name', 'cupcake'),
            ]
        );

        $repeater->add_control(
            'author_role',
            [
                'label'       => __('Author role / company', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('CEO, Acme Corp', 'cupcake'),
                'placeholder' => __('Job title or company', 'cupcake'),
            ]
        );

        $repeater->add_control(
            'author_avatar',
            [
                'label'       => __('Author avatar', 'cupcake'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => ['url' => ''],
                'description' => __('Recommended: 120×120 px image.', 'cupcake'),
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label'       => __('Testimonials', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ author_name }}}',
                'default'     => [
                    [
                        'quote'       => __('Working with CupCake Studio transformed our online presence. The attention to detail and strategic thinking they bring to every project is unmatched.', 'cupcake'),
                        'author_name' => __('Alex Johnson', 'cupcake'),
                        'author_role' => __('CEO, Acme Corp', 'cupcake'),
                    ],
                    [
                        'quote'       => __('The collaboration was smooth, strategic, and genuinely creative. We saw a clear uplift in both engagement and conversions.', 'cupcake'),
                        'author_name' => __('Sophie de Vries', 'cupcake'),
                        'author_role' => __('Marketing Lead, Bloom & Co', 'cupcake'),
                    ],
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __('Auto-rotate slides', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'      => __('Rotation speed (seconds)', 'cupcake'),
                'type'       => Controls_Manager::NUMBER,
                'min'        => 2,
                'max'        => 20,
                'step'       => 1,
                'default'    => 6,
                'condition'  => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_navigation',
            [
                'label'        => __('Show navigation buttons', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'quote_icon',
            [
                'label'   => __('Quote icon', 'cupcake'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-quote-right',
                    'library' => 'solid',
                ],
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
            'layout',
            [
                'label'   => __('Layout', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'card',
                'options' => [
                    'card'   => __('Card style', 'cupcake'),
                    'inline' => __('Inline quote style', 'cupcake'),
                ],
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label'   => __('Accent color', 'cupcake'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#E94560',
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings        = $this->get_settings_for_display();
        $accent_color    = esc_attr($settings['accent_color'] ?? '#E94560');
        $testimonials    = $settings['testimonials'] ?? [];
        $autoplay        = 'yes' === ($settings['autoplay'] ?? 'yes');
        $autoplay_speed  = max(2, min(20, (int) ($settings['autoplay_speed'] ?? 6)));
        $show_navigation = 'yes' === ($settings['show_navigation'] ?? 'yes');

        if (! is_array($testimonials) || empty($testimonials)) {
            $legacy_quote = trim((string) ($settings['quote'] ?? ''));

            if ('' !== $legacy_quote) {
                $testimonials = [
                    [
                        'quote'        => $legacy_quote,
                        'author_name'  => (string) ($settings['author_name'] ?? ''),
                        'author_role'  => (string) ($settings['author_role'] ?? ''),
                        'author_avatar'=> $settings['author_avatar'] ?? ['url' => ''],
                    ],
                ];
            }
        }

        if (! is_array($testimonials) || empty($testimonials)) {
            return;
        }

        $quote_icon_html = '';

        if (! empty($settings['quote_icon'])) {
            ob_start();
            Icons_Manager::render_icon($settings['quote_icon'], ['aria-hidden' => 'true']);
            $quote_icon_html = trim((string) ob_get_clean());
        }

        if ('' === $quote_icon_html) {
            $quote_icon_value = $settings['quote_icon']['value'] ?? '';

            if (is_string($quote_icon_value) && '' !== $quote_icon_value) {
                $quote_icon_html = sprintf('<i class="%s" aria-hidden="true"></i>', esc_attr($quote_icon_value));
            }
        }

        if ('' === $quote_icon_html) {
            $quote_icon_html = '&rdquo;';
        }

        $carousel_id = sprintf('cc-testimonial-carousel-%s', (string) $this->get_id());
        ?>
        <section
            id="<?php echo esc_attr($carousel_id); ?>"
            class="cc-testimonial-carousel"
            style="--testimonial-accent:<?php echo $accent_color; ?>;"
            data-cc-testimonial-carousel="true"
            data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
            data-interval="<?php echo esc_attr((string) ($autoplay_speed * 1000)); ?>"
            aria-label="<?php echo esc_attr__('Testimonials carousel', 'cupcake'); ?>"
        >
            <div class="cc-testimonial-carousel__viewport">
                <?php foreach ($testimonials as $index => $item) : ?>
                    <?php
                    $quote       = wp_kses_post($item['quote'] ?? '');
                    $author_name = trim((string) ($item['author_name'] ?? ''));
                    $author_role = trim((string) ($item['author_role'] ?? ''));
                    $avatar_url  = esc_url($item['author_avatar']['url'] ?? '');

                    if ('' === trim(wp_strip_all_tags($quote))) {
                        continue;
                    }

                    $quoted_text = sprintf('&ldquo;%s&rdquo;', $quote);
                    $is_active   = 0 === (int) $index;
                    $initial     = '' !== $author_name ? mb_strtoupper(mb_substr($author_name, 0, 1)) : '?';
                    ?>
                    <figure class="cc-testimonial cc-testimonial-carousel__slide" <?php echo $is_active ? '' : 'hidden'; ?>>
                        <div class="cc-testimonial__inner">
                            <div class="cc-testimonial__media">
                                <?php if ($avatar_url) : ?>
                                    <div class="cc-testimonial__image-wrap">
                                        <img
                                            src="<?php echo $avatar_url; ?>"
                                            alt="<?php echo $author_name ? esc_attr(sprintf(__('Photo of %s', 'cupcake'), $author_name)) : esc_attr__('Author avatar', 'cupcake'); ?>"
                                            class="cc-testimonial__image"
                                            width="220"
                                            height="260"
                                            loading="lazy"
                                        />
                                    </div>
                                <?php else : ?>
                                    <div class="cc-testimonial__image-wrap cc-testimonial__image-wrap--placeholder" aria-hidden="true">
                                        <span class="cc-testimonial__avatar-placeholder"><?php echo esc_html($initial); ?></span>
                                    </div>
                                <?php endif; ?>

                                <span class="cc-testimonial__quote-badge" aria-hidden="true">
                                    <span class="cc-testimonial__quote-badge-icon">
                                        <?php echo $quote_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </span>
                                </span>
                            </div>

                            <figcaption class="cc-testimonial__content">
                                <blockquote class="cc-testimonial__quote">
                                    <p><?php echo $quoted_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                                </blockquote>

                                <?php if ('' !== $author_name) : ?>
                                    <cite class="cc-testimonial__name"><?php echo esc_html($author_name); ?></cite>
                                <?php endif; ?>

                                <?php if ('' !== $author_role) : ?>
                                    <span class="cc-testimonial__role"><?php echo esc_html($author_role); ?></span>
                                <?php endif; ?>
                            </figcaption>
                        </div>
                    </figure>
                <?php endforeach; ?>
            </div>

            <?php if ($show_navigation && count($testimonials) > 1) : ?>
                <div class="cc-testimonial-carousel__controls" aria-label="<?php echo esc_attr__('Carousel controls', 'cupcake'); ?>">
                    <button class="cc-testimonial-carousel__button cc-testimonial-carousel__button--prev" type="button" aria-label="<?php echo esc_attr__('Previous testimonial', 'cupcake'); ?>">&#8249;</button>
                    <button class="cc-testimonial-carousel__button cc-testimonial-carousel__button--next" type="button" aria-label="<?php echo esc_attr__('Next testimonial', 'cupcake'); ?>">&#8250;</button>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
