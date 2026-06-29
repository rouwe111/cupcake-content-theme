<?php
/**
 * CupCake Theme — footer.php
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;
?>
<?php
if (! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('footer')) {
	get_template_part('template-parts/footer/site-footer');
}
?>
<?php wp_footer(); ?>
</body>
</html>
