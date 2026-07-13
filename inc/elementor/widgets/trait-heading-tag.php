<?php
/**
 * Shared heading-tag control helpers for CupCake Elementor widgets.
 *
 * @package CupCake
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Provides a reusable semantic heading-tag select for title-bearing widgets.
 */
trait CupCake_Heading_Tag {

    /**
     * Allowed semantic heading tag options.
     *
     * @return array<string, string>
     */
    private function get_heading_tag_options(): array {
        return [
            'h1'  => __('H1', 'cupcake'),
            'h2'  => __('H2', 'cupcake'),
            'h3'  => __('H3', 'cupcake'),
            'h4'  => __('H4', 'cupcake'),
            'h5'  => __('H5', 'cupcake'),
            'h6'  => __('H6', 'cupcake'),
            'p'   => __('Paragraph', 'cupcake'),
            'div' => __('DIV', 'cupcake'),
        ];
    }

    /**
     * Sanitize a submitted heading tag against the allowed list.
     *
     * @param string $tag     Submitted tag.
     * @param string $default Fallback tag when invalid.
     * @return string
     */
    private function sanitize_heading_tag(string $tag, string $default): string {
        $tag = strtolower($tag);

        return in_array($tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'], true) ? $tag : $default;
    }

    /**
     * Title style options: visual appearance independent of the HTML tag.
     *
     * @return array<string, string>
     */
    private function get_title_style_options(): array {
        return [
            ''     => __('Default (component style)', 'cupcake'),
            'hero' => __('Hero', 'cupcake'),
            'h1'   => __('H1', 'cupcake'),
            'h2'   => __('H2', 'cupcake'),
            'h3'   => __('H3', 'cupcake'),
            'p'    => __('Paragraph', 'cupcake'),
        ];
    }

    /**
     * Resolve the CSS class for a selected title style, if any.
     *
     * @param string $style Submitted title style.
     * @return string Modifier class, or an empty string for the default look.
     */
    private function resolve_title_style_class(string $style): string {
        return in_array($style, ['hero', 'h1', 'h2', 'h3', 'p'], true) ? 'cc-heading-style--' . $style : '';
    }
}
