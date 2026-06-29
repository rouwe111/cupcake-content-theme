<?php
/**
 * Shared color set helpers for CupCake Elementor widgets.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Provides reusable color preset sets for card-like widgets.
 */
trait CupCake_Color_Sets {

    /**
     * Get all available color sets.
     *
     * @return array<string, array<string, string>>
     */
    private function get_color_sets(): array {
        return [
            'rose' => [
                'label'      => __('Rose', 'cupcake'),
                'card_bg'    => '#FFE9E7',
                'card_border'=> '#F4D6D3',
                'icon_bg'    => '#FFDAD6',
                'icon_color' => '#FA4D56',
            ],
            'sage' => [
                'label'      => __('Sage', 'cupcake'),
                'card_bg'    => '#EAF3EC',
                'card_border'=> '#D8E9DD',
                'icon_bg'    => '#DDEEE3',
                'icon_color' => '#4E7D5B',
            ],
            'sand' => [
                'label'      => __('Sand', 'cupcake'),
                'card_bg'    => '#FFF1DC',
                'card_border'=> '#F2E0C3',
                'icon_bg'    => '#FDE9C8',
                'icon_color' => '#D98A2B',
            ],
            'berry' => [
                'label'      => __('Berry', 'cupcake'),
                'card_bg'    => '#FBE8EF',
                'card_border'=> '#F2D5E2',
                'icon_bg'    => '#F7D8E8',
                'icon_color' => '#C9417A',
            ],
        ];
    }

    /**
     * Build Elementor select options from color sets.
     *
     * @return array<string, string>
     */
    private function get_color_set_options(): array {
        $options = [
            'custom' => __('Custom colors', 'cupcake'),
        ];

        foreach ($this->get_color_sets() as $key => $set) {
            $options[$key] = $set['label'];
        }

        return $options;
    }

    /**
     * Resolve selected set, falling back to provided defaults.
     *
     * @param string               $selected Selected color set key.
     * @param array<string,string> $fallback Fallback values.
     * @return array<string,string>
     */
    private function resolve_color_set(string $selected, array $fallback): array {
        if ('custom' === $selected) {
            return $fallback;
        }

        $sets = $this->get_color_sets();

        if (! isset($sets[$selected])) {
            return $fallback;
        }

        return [
            'card_bg'    => $sets[$selected]['card_bg'],
            'card_border'=> $sets[$selected]['card_border'],
            'icon_bg'    => $sets[$selected]['icon_bg'],
            'icon_color' => $sets[$selected]['icon_color'],
        ];
    }
}
