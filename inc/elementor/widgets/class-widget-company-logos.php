<?php
/**
 * CupCake Theme - Elementor Company Logos Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Company logos/images grid with per-item link controls.
 */
class CupCake_Widget_Company_Logos extends Widget_Base {

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

    /**
     * Resolve link data for one company row.
     *
     * @param array<string, mixed> $company Repeater row settings.
     *
     * @return array<string, mixed>
     */
    private function resolve_company_link_data(array $company): array {
        $link_type = $company['link_type'] ?? 'none';

        if ('internal' === $link_type) {
            $internal_post_id = (int) ($company['internal_post'] ?? 0);

            if ($internal_post_id > 0) {
                $internal_url = get_permalink($internal_post_id);

                if (is_string($internal_url) && '' !== $internal_url) {
                    return [
                        'url'         => $internal_url,
                        'is_external' => false,
                        'nofollow'    => false,
                    ];
                }
            }

            return [];
        }

        if ('external' === $link_type) {
            $external = $company['external_url'] ?? [];

            return is_array($external) ? $external : [];
        }

        return [];
    }

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-company-logos';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Company Logos', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-logo';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['company', 'companies', 'logo', 'logos', 'clients', 'brands', 'cupcake'];
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
                'default'     => __('Bedrijven waar ik mee werkte', 'cupcake'),
                'placeholder' => __('Enter title', 'cupcake'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => __('Subtitle', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Een selectie van merken en bedrijven waarmee ik content en social media resultaten heb neergezet.', 'cupcake'),
                'rows'        => 2,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'company_name',
            [
                'label'       => __('Company name', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Company name', 'cupcake'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'company_image',
            [
                'label'       => __('Logo or image', 'cupcake'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => ['url' => ''],
                'description' => __('Upload a logo or company image.', 'cupcake'),
            ]
        );

        $repeater->add_control(
            'link_type',
            [
                'label'   => __('Link type', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'internal_post',
            [
                'label'       => __('Select page or post', 'cupcake'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_internal_link_options(),
                'multiple'    => false,
                'label_block' => true,
                'condition'   => [
                    'link_type' => 'internal',
                ],
            ]
        );

        $repeater->add_control(
            'external_url',
            [
                'label'         => __('External URL', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'cupcake'),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'link_type' => 'external',
                ],
            ]
        );

        $this->add_control(
            'companies',
            [
                'label'       => __('Companies', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ company_name }}}',
                'default'     => [
                    ['company_name' => __('Brand One', 'cupcake')],
                    ['company_name' => __('Brand Two', 'cupcake')],
                    ['company_name' => __('Brand Three', 'cupcake')],
                    ['company_name' => __('Brand Four', 'cupcake')],
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
        $settings  = $this->get_settings_for_display();
        $title     = trim((string) ($settings['title'] ?? ''));
        $subtitle  = trim((string) ($settings['subtitle'] ?? ''));
        $companies = $settings['companies'] ?? [];
        $heading_id = $this->get_id() . '-title';
        $schema_items = [];
        $use_structured_data = 'yes' === ($settings['enable_structured_data'] ?? 'yes');

        if (! is_array($companies) || empty($companies)) {
            return;
        }
        ?>
        <section class="cc-company-logos-widget" <?php if ('' !== $title) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Companies', 'cupcake'); ?>"<?php endif; ?>>
            <?php if ('' !== $title || '' !== $subtitle) : ?>
                <header class="cc-company-logos-widget__header">
                    <?php if ('' !== $title) : ?>
                        <h2 id="<?php echo esc_attr($heading_id); ?>" class="cc-company-logos-widget__title"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ('' !== $subtitle) : ?>
                        <p class="cc-company-logos-widget__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </header>
            <?php endif; ?>

            <div class="cc-company-logos-widget__grid" role="list">
                <?php foreach ($companies as $index => $company) : ?>
                    <?php
                    $name      = trim((string) ($company['company_name'] ?? ''));
                    $image_id  = (int) ($company['company_image']['id'] ?? 0);
                    $image_url = trim((string) ($company['company_image']['url'] ?? ''));

                    if ('' === $name && '' === $image_url) {
                        continue;
                    }

                    $link_data = $this->resolve_company_link_data($company);

                    $has_link = '' !== ($link_data['url'] ?? '');
                    $link_key = sprintf('company_link_%d', (int) $index);
                    $image_alt = '';

                    if ($image_id > 0) {
                        $image_alt = trim((string) get_post_meta($image_id, '_wp_attachment_image_alt', true));
                    }

                    if ('' === $image_alt) {
                        $image_alt = '' !== $name ? $name : __('Company logo', 'cupcake');
                    }

                    if ($use_structured_data) {
                        $schema_item = [
                            '@type'    => 'ListItem',
                            'position' => (int) $index + 1,
                            'name'     => '' !== $name ? $name : __('Company', 'cupcake'),
                        ];

                        if ('' !== $image_url) {
                            $schema_item['image'] = $image_url;
                        }

                        if ($has_link) {
                            $schema_item['url'] = (string) $link_data['url'];
                        }

                        $schema_items[] = $schema_item;
                    }

                    if ($has_link) {
                        $this->add_render_attribute(
                            $link_key,
                            'aria-label',
                            sprintf(
                                /* translators: %s: company name */
                                __('Open company page: %s', 'cupcake'),
                                '' !== $name ? $name : __('Company', 'cupcake')
                            )
                        );

                        $this->add_link_attributes($link_key, $link_data);
                    }
                    ?>
                    <?php if ($has_link) : ?>
                        <a class="cc-company-logos-widget__card cc-company-logos-widget__card--link" role="listitem" <?php echo $this->get_render_attribute_string($link_key); ?>>
                    <?php else : ?>
                        <div class="cc-company-logos-widget__card" role="listitem">
                    <?php endif; ?>
                            <?php if ('' !== $image_url) : ?>
                                <img
                                    class="cc-company-logos-widget__image"
                                    src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr($image_alt); ?>"
                                    loading="lazy"
                                />
                            <?php else : ?>
                                <span class="cc-company-logos-widget__placeholder"><?php echo esc_html('' !== $name ? $name : __('Company', 'cupcake')); ?></span>
                            <?php endif; ?>

                            <?php if ('' !== $name) : ?>
                                <span class="cc-company-logos-widget__name"><?php echo esc_html($name); ?></span>
                            <?php endif; ?>
                    <?php if ($has_link) : ?>
                        </a>
                    <?php else : ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
        if ($use_structured_data && ! empty($schema_items)) {
            $schema = [
                '@context'        => 'https://schema.org',
                '@type'           => 'ItemList',
                'name'            => '' !== $title ? $title : __('Companies', 'cupcake'),
                'itemListElement' => $schema_items,
            ];
            ?>
            <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
            <?php
        }
    }
}
