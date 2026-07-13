<?php
/**
 * CupCake Theme — Elementor Packages Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once __DIR__ . '/trait-color-sets.php';
require_once __DIR__ . '/trait-heading-tag.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Pricing/packages card grid with include/exclude lists and optional bonus.
 */
class CupCake_Widget_Packages extends Widget_Base {
    use CupCake_Color_Sets;
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

    /**
     * Parse textarea input into a cleaned list of lines.
     *
     * @param string $raw Raw textarea value.
     * @return string[]
     */
    private function parse_list_items(string $raw): array {
        if ('' === trim($raw)) {
            return [];
        }

        $lines = preg_split('/\R/', $raw) ?: [];

        return array_values(
            array_filter(
                array_map(static fn (string $line): string => trim($line), $lines),
                static fn (string $line): bool => '' !== $line
            )
        );
    }

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-packages';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake Packages', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-price-table';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['packages', 'pricing', 'plans', 'offers', 'cupcake'];
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
            'section_title',
            [
                'label'       => __('Section title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Pakketten', 'cupcake'),
                'placeholder' => __('Enter section title', 'cupcake'),
            ]
        );

        $this->add_control(
            'section_title_tag',
            [
                'label'   => __('Section title HTML tag', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => $this->get_heading_tag_options(),
            ]
        );

        $this->add_control(
            'section_title_style',
            [
                'label'   => __('Title style', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_title_style_options(),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label'       => __('Section subtitle', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Kies het pakket dat het beste past bij jouw groeifase.', 'cupcake'),
                'placeholder' => __('Enter section subtitle', 'cupcake'),
                'rows'        => 2,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'color_set',
            [
                'label'   => __('Color set', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'rose',
                'options' => $this->get_color_set_options(),
            ]
        );

        $repeater->add_control(
            'accent_soft',
            [
                'label'     => __('Soft accent color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFE9E7',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'accent_color',
            [
                'label'     => __('Accent color', 'cupcake'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FA4D56',
                'condition' => [
                    'color_set' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'package_eyebrow',
            [
                'label'       => __('Package eyebrow', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Starter', 'cupcake'),
                'placeholder' => __('Enter small top label', 'cupcake'),
            ]
        );

        $repeater->add_control(
            'package_title',
            [
                'label'       => __('Package title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Authentieke content voor jouw bedrijf', 'cupcake'),
                'placeholder' => __('Enter package title', 'cupcake'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'package_subtitle',
            [
                'label'       => __('Package subtitle', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Een helder startpakket om direct zichtbaar te zijn met sterke content.', 'cupcake'),
                'placeholder' => __('Enter package subtitle', 'cupcake'),
                'rows'        => 3,
            ]
        );

        $repeater->add_control(
            'is_popular',
            [
                'label'        => __('Mark as popular', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => '',
                'separator'    => 'before',
            ]
        );

        $repeater->add_control(
            'popular_label',
            [
                'label'     => __('Popular label', 'cupcake'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Populaire keuze', 'cupcake'),
                'condition' => [
                    'is_popular' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'includes_items',
            [
                'label'       => __('Included items (one per line)', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => "Basisstrategie\n6-8 posts per maand\nMaandelijkse contentkalender",
                'rows'        => 6,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'excludes_items',
            [
                'label'     => __('Excluded items (one per line)', 'cupcake'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => '',
                'rows'      => 4,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'       => __('Button text', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Vraag jouw offerte aan', 'cupcake'),
                'placeholder' => __('Enter button text', 'cupcake'),
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'button_link_type',
            [
                'label'   => __('Button link type', 'cupcake'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'     => __('No link', 'cupcake'),
                    'internal' => __('Page or post', 'cupcake'),
                    'external' => __('External/custom URL', 'cupcake'),
                ],
            ]
        );

        $repeater->add_control(
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

        $repeater->add_control(
            'button_url',
            [
                'label'         => __('Button URL', 'cupcake'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'cupcake'),
                'show_external' => true,
                'default'       => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'     => [
                    'button_link_type' => 'external',
                ],
            ]
        );

        $repeater->add_control(
            'bonus_text',
            [
                'label'       => __('Bonus text (optional)', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => '',
                'placeholder' => __('Optional bonus text shown at the bottom', 'cupcake'),
                'rows'        => 3,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'packages',
            [
                'label'       => __('Packages', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ package_eyebrow }}} - {{{ package_title }}}',
                'default'     => [
                    [
                        'color_set'         => 'rose',
                        'package_eyebrow'   => __('Content creatie', 'cupcake'),
                        'package_title'     => __('Authentieke content voor jouw bedrijf', 'cupcake'),
                        'package_subtitle'  => __('Een helder startpakket om direct zichtbaar te zijn met sterke content.', 'cupcake'),
                        'includes_items'    => "Basisstrategie\n6-8 posts per maand\nKorte voortgangsrapportage",
                        'button_text'       => __('Vraag jouw offerte aan', 'cupcake'),
                    ],
                    [
                        'color_set'         => 'sand',
                        'package_eyebrow'   => __('Groeipakket', 'cupcake'),
                        'package_title'     => __('Meer structuur en bereik', 'cupcake'),
                        'package_subtitle'  => __('Voor bedrijven die sneller willen groeien met een sterk contentritme.', 'cupcake'),
                        'is_popular'        => 'yes',
                        'popular_label'     => __('Populaire keuze', 'cupcake'),
                        'includes_items'    => "Uitgebreide contentstrategie\n10-12 posts per maand\nWekelijkse check-in",
                        'button_text'       => __('Vraag jouw offerte aan', 'cupcake'),
                    ],
                    [
                        'color_set'         => 'sage',
                        'package_eyebrow'   => __('Jouw marketing manager', 'cupcake'),
                        'package_title'     => __('Flexibel en bereikbaar', 'cupcake'),
                        'package_subtitle'  => __('Alles-in-een ondersteuning voor content, social en optimalisatie.', 'cupcake'),
                        'includes_items'    => "Social media beheer\nContent creatie\nWebsite optimalisaties",
                        'button_text'       => __('Vraag jouw offerte aan', 'cupcake'),
                    ],
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
        $settings = $this->get_settings_for_display();

        $section_title       = trim((string) ($settings['section_title'] ?? ''));
        $section_title_tag   = $this->sanitize_heading_tag((string) ($settings['section_title_tag'] ?? 'h2'), 'h2');
        $section_title_style = $this->resolve_title_style_class((string) ($settings['section_title_style'] ?? ''));
        $section_subtitle    = trim((string) ($settings['section_subtitle'] ?? ''));
        $packages         = $settings['packages'] ?? [];
        $heading_id       = $this->get_id() . '-title';
        $schema_items     = [];
        $use_structured_data = 'yes' === ($settings['enable_structured_data'] ?? 'yes');

        if (! is_array($packages) || empty($packages)) {
            return;
        }
        ?>
        <section class="cc-packages-widget" <?php if ('' !== $section_title) : ?>aria-labelledby="<?php echo esc_attr($heading_id); ?>"<?php else : ?>aria-label="<?php echo esc_attr__('Packages', 'cupcake'); ?>"<?php endif; ?>>
            <?php if ('' !== $section_title || '' !== $section_subtitle) : ?>
                <header class="cc-packages-widget__header">
                    <?php if ('' !== $section_title) : ?>
                        <<?php echo esc_attr($section_title_tag); ?> id="<?php echo esc_attr($heading_id); ?>" class="cc-packages-widget__title<?php echo $section_title_style ? ' ' . esc_attr($section_title_style) : ''; ?>"><?php echo esc_html($section_title); ?></<?php echo esc_attr($section_title_tag); ?>>
                    <?php endif; ?>

                    <?php if ('' !== $section_subtitle) : ?>
                        <p class="cc-packages-widget__subtitle"><?php echo esc_html($section_subtitle); ?></p>
                    <?php endif; ?>
                </header>
            <?php endif; ?>

            <div class="cc-packages-widget__grid" role="list">
                <?php foreach ($packages as $index => $package) : ?>
                    <?php
                    $eyebrow        = trim((string) ($package['package_eyebrow'] ?? ''));
                    $title          = trim((string) ($package['package_title'] ?? ''));
                    $subtitle       = trim((string) ($package['package_subtitle'] ?? ''));
                    $includes_items = $this->parse_list_items((string) ($package['includes_items'] ?? ''));
                    $excludes_items = $this->parse_list_items((string) ($package['excludes_items'] ?? ''));
                    $bonus_text     = trim((string) ($package['bonus_text'] ?? ''));
                    $button_text    = trim((string) ($package['button_text'] ?? ''));

                    if ('' === $title) {
                        continue;
                    }

                    $selected_set = (string) ($package['color_set'] ?? 'rose');

                    $resolved_colors = $this->resolve_color_set(
                        $selected_set,
                        [
                            'card_bg'    => '#FFFFFF',
                            'card_border'=> '#F0E7DC',
                            'icon_bg'    => (string) ($package['accent_soft'] ?? '#FFE9E7'),
                            'icon_color' => (string) ($package['accent_color'] ?? '#FA4D56'),
                        ]
                    );

                    $button_accent = 'grey' === $selected_set ? '#4E7D5B' : (string) $resolved_colors['icon_color'];

                    $card_style = sprintf(
                        '--cc-package-accent:%s;--cc-package-button-accent:%s;--cc-package-soft:%s;--cc-package-border:%s;',
                        esc_attr($resolved_colors['icon_color']),
                        esc_attr($button_accent),
                        esc_attr($resolved_colors['icon_bg']),
                        esc_attr($resolved_colors['card_border'])
                    );

                    $is_popular    = 'yes' === ($package['is_popular'] ?? '');
                    $popular_label = trim((string) ($package['popular_label'] ?? __('Populaire keuze', 'cupcake')));

                    $link_type = $package['button_link_type'] ?? 'none';
                    $link_data = [];

                    if ('internal' === $link_type) {
                        $internal_post_id = (int) ($package['button_internal_post'] ?? 0);

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
                        $link_data = $package['button_url'] ?? [];
                    }

                    $has_link = '' !== ($link_data['url'] ?? '');
                    $link_key = sprintf('package_button_%d', (int) $index);

                    if ($use_structured_data) {
                        $schema_item = [
                            '@type'       => 'Offer',
                            'name'        => $title,
                            'description' => $subtitle,
                            'category'    => $eyebrow,
                        ];

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
                                /* translators: %s: package title */
                                __('Open package: %s', 'cupcake'),
                                $title
                            )
                        );

                        $this->add_link_attributes($link_key, $link_data);
                    }
                    ?>
                    <article class="cc-package-card<?php echo $is_popular ? ' is-popular' : ''; ?>" style="<?php echo esc_attr($card_style); ?>" role="listitem">
                        <?php if ($is_popular && '' !== $popular_label) : ?>
                            <p class="cc-package-card__popular"><?php echo esc_html($popular_label); ?></p>
                        <?php endif; ?>

                        <header class="cc-package-card__header">
                            <?php if ('' !== $eyebrow) : ?>
                                <p class="cc-package-card__eyebrow"><?php echo esc_html($eyebrow); ?></p>
                            <?php endif; ?>

                            <h3 class="cc-package-card__title"><?php echo esc_html($title); ?></h3>
                        </header>

                        <?php if ('' !== $subtitle) : ?>
                            <p class="cc-package-card__subtitle"><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>

                        <?php if (! empty($includes_items) || ! empty($excludes_items)) : ?>
                            <ul class="cc-package-card__items" role="list">
                                <?php foreach ($includes_items as $item_text) : ?>
                                    <li class="cc-package-card__item cc-package-card__item--include">
                                        <span class="cc-package-card__item-icon" aria-hidden="true">&#10003;</span>
                                        <span><?php echo esc_html($item_text); ?></span>
                                    </li>
                                <?php endforeach; ?>

                                <?php foreach ($excludes_items as $item_text) : ?>
                                    <li class="cc-package-card__item cc-package-card__item--exclude">
                                        <span class="cc-package-card__item-icon" aria-hidden="true">&#10005;</span>
                                        <span><?php echo esc_html($item_text); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if ('' !== $bonus_text) : ?>
                            <p class="cc-package-card__bonus"><?php echo esc_html($bonus_text); ?></p>
                        <?php endif; ?>

                        <?php if ('' !== $button_text) : ?>
                            <div class="cc-package-card__button-wrap">
                                <?php if ($has_link) : ?>
                                    <a class="cc-package-card__button" <?php echo $this->get_render_attribute_string($link_key); ?>><?php echo esc_html($button_text); ?></a>
                                <?php else : ?>
                                    <span class="cc-package-card__button"><?php echo esc_html($button_text); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
        if ($use_structured_data && ! empty($schema_items)) {
            $schema = [
                '@context'        => 'https://schema.org',
                '@type'           => 'OfferCatalog',
                'name'            => '' !== $section_title ? $section_title : __('Packages', 'cupcake'),
                'itemListElement' => $schema_items,
            ];
            ?>
            <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
            <?php
        }

    }
}
