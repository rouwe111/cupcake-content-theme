<?php
/**
 * CupCake Theme — Elementor Blogs Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-heading-tag.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Latest blog posts section with heading and CTA button.
 */
class CupCake_Widget_Blogs extends Widget_Base {
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
        return 'cupcake-blogs';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Blogs', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-posts-grid';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['blog', 'posts', 'articles', 'news', 'grid', 'cupcake'];
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
            'subtitle',
            [
                'label'       => __('Subtitle', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Inspiratie', 'cupcake'),
                'placeholder' => __('Enter subtitle', 'cupcake'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Laat je inspireren', 'cupcake'),
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
            'title_style',
            [
                'label'   => __('Title style', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_title_style_options(),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => __('Button text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Bekijk alle artikelen', 'cupcake'),
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
                'placeholder'   => __('https://example.com/blog', 'cupcake'),
                'show_external' => true,
                'default'       => [
                    'url'         => get_post_type_archive_link('post') ?: home_url('/'),
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'button_link_type' => 'external',
                ],
            ]
        );

        $this->add_control(
            'enable_structured_data',
            [
                'label'        => __('Output structured data (SEO)', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings    = $this->get_settings_for_display();
        $subtitle    = esc_html($settings['subtitle'] ?? '');
        $title       = esc_html($settings['title'] ?? '');
        $title_tag   = $this->sanitize_heading_tag((string) ($settings['title_tag'] ?? 'h2'), 'h2');
        $title_style = $this->resolve_title_style_class((string) ($settings['title_style'] ?? ''));
        $button_text = esc_html($settings['button_text'] ?? '');
        $heading_id  = $this->get_id() . '-title';
        $schema_items = [];
        $use_structured_data = 'yes' === ($settings['enable_structured_data'] ?? 'yes');

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

        $query = new WP_Query(
            [
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => true,
            ]
        );

        $has_button_link = ! empty($link_data['url']);

        if ($has_button_link) {
            $this->add_link_attributes('button_url', $link_data);
        }
        ?>
        <section class="cc-blogs-widget" <?php if ('' !== $title) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Blog posts', 'cupcake'); ?>"<?php endif; ?>>
            <header class="cc-blogs-widget__header">
                <div class="cc-blogs-widget__heading-group">
                    <?php if ($subtitle) : ?>
                        <span class="cc-blogs-widget__subtitle"><?php echo $subtitle; ?></span>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <<?php echo esc_attr($title_tag); ?> id="<?php echo esc_attr($heading_id); ?>" class="cc-blogs-widget__title<?php echo $title_style ? ' ' . esc_attr($title_style) : ''; ?>"><?php echo $title; ?></<?php echo esc_attr($title_tag); ?>>
                    <?php endif; ?>
                </div>

                <?php if ($button_text && $has_button_link) : ?>
                    <a class="cc-blogs-widget__button" <?php echo $this->get_render_attribute_string('button_url'); ?>>
                        <?php echo $button_text; ?>
                    </a>
                <?php elseif ($button_text) : ?>
                    <span class="cc-blogs-widget__button"><?php echo $button_text; ?></span>
                <?php endif; ?>
            </header>

            <?php if ($query->have_posts()) : ?>
                <div class="cc-blogs-widget__grid">
                    <?php
                    while ($query->have_posts()) :
                        $query->the_post();

                        $post_id       = get_the_ID();
                        $post_title    = get_the_title($post_id);
                        $post_permalink = get_permalink($post_id);
                        $post_date     = get_the_date('j F Y', $post_id);
                        $category      = get_the_category($post_id);
                        $category_name = ! empty($category) ? $category[0]->name : __('Blog', 'cupcake');
                        $thumb_url     = get_the_post_thumbnail_url($post_id, 'large');

                        $word_count = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
                        $read_time  = max(1, (int) ceil($word_count / 200));

                        if ($use_structured_data) {
                            $schema_items[] = [
                                '@type'         => 'BlogPosting',
                                'headline'      => $post_title,
                                'datePublished' => get_post_time('c', true, $post_id),
                                'url'           => $post_permalink,
                                'image'         => $thumb_url ? $thumb_url : null,
                                'articleSection'=> $category_name,
                            ];
                        }
                        ?>
                        <article class="cc-blogs-widget__card">
                            <a class="cc-blogs-widget__media-link" href="<?php echo esc_url($post_permalink); ?>" aria-label="<?php echo esc_attr(sprintf(__('Open blog post: %s', 'cupcake'), $post_title)); ?>">
                                <?php if ($thumb_url) : ?>
                                    <img
                                        src="<?php echo esc_url($thumb_url); ?>"
                                        alt="<?php echo esc_attr($post_title); ?>"
                                        class="cc-blogs-widget__image"
                                        loading="lazy"
                                    />
                                <?php else : ?>
                                    <span class="cc-blogs-widget__image-placeholder" aria-hidden="true"></span>
                                <?php endif; ?>
                            </a>

                            <div class="cc-blogs-widget__body">
                                <span class="cc-blogs-widget__tag"><?php echo esc_html($category_name); ?></span>
                                <h3 class="cc-blogs-widget__card-title">
                                    <a href="<?php echo esc_url($post_permalink); ?>" aria-label="<?php echo esc_attr(sprintf(__('Read blog post: %s', 'cupcake'), $post_title)); ?>"><?php echo esc_html($post_title); ?></a>
                                </h3>
                                <p class="cc-blogs-widget__meta">
                                    <?php echo esc_html($post_date . ' · ' . $read_time . ' min'); ?>
                                </p>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="cc-blogs-widget__empty"><?php esc_html_e('No blog posts found.', 'cupcake'); ?></p>
            <?php endif; ?>
        </section>
        <?php
        if ($use_structured_data && ! empty($schema_items)) {
            $schema = [
                '@context'         => 'https://schema.org',
                '@type'            => 'Blog',
                'name'             => '' !== $title ? $title : __('Blog posts', 'cupcake'),
                'blogPost'         => $schema_items,
            ];
            ?>
            <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
            <?php
        }

        wp_reset_postdata();
    }
}
