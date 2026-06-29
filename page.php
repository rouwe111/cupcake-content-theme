<?php
/**
 * CupCake Theme — Generic page template.
 *
 * Elementor builds page content; this template provides the shell.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main" role="main">
    <?php
    while (have_posts()) :
        the_post();

        $is_elementor_page = false;

        if (class_exists('\Elementor\\Plugin')) {
            $document = \Elementor\Plugin::$instance->documents->get(get_the_ID());
            if ($document && method_exists($document, 'is_built_with_elementor')) {
                $is_elementor_page = (bool) $document->is_built_with_elementor();
            }
        }

        if ($is_elementor_page) {
            the_content();
            continue;
        }
        ?>
        <article class="cc-page-default container">
            <header class="cc-page-default__header">
                <h1 class="cc-page-default__title"><?php the_title(); ?></h1>
            </header>

            <div class="cc-page-default__content entry-content">
                <?php the_content(); ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main><!-- #main -->

<?php
get_footer();
