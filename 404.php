<?php
/**
 * CupCake Theme — 404 Not Found template.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main" role="main">
    <div class="container">

        <section class="error-404">
            <div class="error-404__content">
                <div class="error-404__code" aria-hidden="true">404</div>

                <h1 class="error-404__heading">
                    <?php esc_html_e('Page not found', 'cupcake'); ?>
                </h1>

                <p class="error-404__message">
                    <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'cupcake'); ?>
                </p>

                <div class="error-404__actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>"
                       class="cc-btn cc-btn--primary">
                        <?php esc_html_e('Go back home', 'cupcake'); ?>
                    </a>
                </div>

                <div class="error-404__search">
                    <p class="error-404__search-label">
                        <?php esc_html_e('Or try searching for what you need:', 'cupcake'); ?>
                    </p>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </section><!-- .error-404 -->

    </div><!-- .container -->
</main><!-- #main -->

<?php
get_footer();
