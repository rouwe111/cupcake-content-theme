<?php
/**
 * CupCake Theme — Front page template.
 *
 * Elementor builds the front page content; this template simply provides
 * the header/footer shell.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();
    the_content();
endwhile;

get_footer();
