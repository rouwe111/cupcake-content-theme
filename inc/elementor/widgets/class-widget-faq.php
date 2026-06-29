<?php
/**
 * CupCake Theme — Elementor FAQ Widget.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * FAQ accordion with single-open behavior handled in frontend JS.
 */
class CupCake_Widget_FAQ extends Widget_Base {

    /** {@inheritdoc} */
    public function get_name(): string {
        return 'cupcake-faq';
    }

    /** {@inheritdoc} */
    public function get_title(): string {
        return __('CupCake FAQ', 'cupcake');
    }

    /** {@inheritdoc} */
    public function get_icon(): string {
        return 'eicon-accordion';
    }

    /** {@inheritdoc} */
    public function get_categories(): array {
        return ['cupcake-content'];
    }

    /** {@inheritdoc} */
    public function get_keywords(): array {
        return ['faq', 'vragen', 'accordion', 'toggle', 'cupcake'];
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
                'label'       => __('Section title', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Veelgestelde vragen', 'cupcake'),
                'placeholder' => __('Enter title', 'cupcake'),
            ]
        );

        $this->add_control(
            'open_first',
            [
                'label'        => __('Open first item by default', 'cupcake'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'cupcake'),
                'label_off'    => __('No', 'cupcake'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question',
            [
                'label'       => __('Question', 'cupcake'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Voor wie werkt Cupcake Content?', 'cupcake'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'answer',
            [
                'label'       => __('Answer', 'cupcake'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Ik werk voor MKB-bedrijven in de foodbranche in en rondom de Regio Zwolle die consistent zichtbaar willen worden, zonder elke week contentstress.', 'cupcake'),
                'rows'        => 4,
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('FAQ items', 'cupcake'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ question }}}',
                'default'     => [
                    [
                        'question' => __('Voor wie werkt Cupcake Content?', 'cupcake'),
                        'answer'   => __('Ik werk voor MKB-bedrijven in de foodbranche in en rondom de Regio Zwolle die consistent zichtbaar willen worden, zonder elke week contentstress.', 'cupcake'),
                    ],
                    [
                        'question' => __('Hoe snel zie ik resultaat?', 'cupcake'),
                        'answer'   => __('In 30 dagen leggen we samen een fundament: strategie, contentplan en de eerste publicaties. Vanaf daar bouwen we aan duurzame groei.', 'cupcake'),
                    ],
                    [
                        'question' => __('Werken we met vaste pakketten?', 'cupcake'),
                        'answer'   => __('Ja, je kiest een maandelijks pakket met een vast bedrag en een vast aanspreekpunt. Liever maatwerk? Dat kan natuurlijk ook.', 'cupcake'),
                    ],
                    [
                        'question' => __('Wat heb je van mij nodig?', 'cupcake'),
                        'answer'   => __('Vooral toegang tot je verhaal en je keuken. De rest - strategie, creatie en planning - neem ik uit handen.', 'cupcake'),
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    /** {@inheritdoc} */
    protected function render(): void {
        $settings   = $this->get_settings_for_display();
        $title      = trim((string) ($settings['title'] ?? ''));
        $items      = $settings['items'] ?? [];
        $open_first = 'yes' === ($settings['open_first'] ?? 'yes');

        if (! is_array($items) || empty($items)) {
            return;
        }

        $widget_id = (string) $this->get_id();
        ?>
        <section class="cc-faq-widget" aria-label="<?php echo esc_attr__('Frequently asked questions', 'cupcake'); ?>">
            <?php if ('' !== $title) : ?>
                <h2 class="cc-faq-widget__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <div class="cc-faq-widget__list" data-cc-accordion="single">
                <?php foreach ($items as $index => $item) : ?>
                    <?php
                    $question = trim((string) ($item['question'] ?? ''));
                    $answer   = trim((string) ($item['answer'] ?? ''));

                    if ('' === $question || '' === $answer) {
                        continue;
                    }

                    $is_open   = $open_first && 0 === (int) $index;
                    $button_id = sprintf('cc-faq-btn-%s-%d', $widget_id, (int) $index);
                    $panel_id  = sprintf('cc-faq-panel-%s-%d', $widget_id, (int) $index);
                    ?>
                    <article class="cc-faq-widget__item<?php echo $is_open ? ' is-open' : ''; ?>">
                        <h3 class="cc-faq-widget__heading">
                            <button
                                id="<?php echo esc_attr($button_id); ?>"
                                class="cc-faq-widget__trigger"
                                type="button"
                                aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($panel_id); ?>"
                            >
                                <span class="cc-faq-widget__question"><?php echo esc_html($question); ?></span>
                                <span class="cc-faq-widget__icon" aria-hidden="true"><?php echo $is_open ? '▾' : '▸'; ?></span>
                            </button>
                        </h3>

                        <div
                            id="<?php echo esc_attr($panel_id); ?>"
                            class="cc-faq-widget__panel"
                            role="region"
                            aria-labelledby="<?php echo esc_attr($button_id); ?>"
                            <?php echo $is_open ? '' : 'hidden'; ?>
                        >
                            <p class="cc-faq-widget__answer"><?php echo wp_kses_post($answer); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
