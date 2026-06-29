<?php
/**
 * CupCake Theme — Search results template.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main" role="main">
    <div class="container">

        <header class="archive-header">
            <h1 class="archive-header__title">
                <?php
                printf(
                    /* translators: %s: search term */
                    esc_html__('Search results for: %s', 'cupcake'),
                    '<span class="search-term">' . esc_html(get_search_query()) . '</span>'
                );
                ?>
            </h1>
        </header>

        <!-- Inline search form ──────────────────────────────────────────── -->
        <div class="search-form-wrapper">
            <?php get_search_form(); ?>
        </div>

        <?php if (have_posts()) : ?>

            <p class="search-results-count">
                <?php
                printf(
                    /* translators: %d: number of results */
                    esc_html(_n('%d result found.', '%d results found.', (int) $wp_query->found_posts, 'cupcake')),
                    (int) $wp_query->found_posts
                );
                ?>
            </p>

            <div class="posts-grid" role="list">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>"
                             <?php post_class('post-card'); ?>
                             role="listitem">

                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>"
                               class="post-card__thumbnail-link"
                               tabindex="-1"
                               aria-hidden="true">
                                <?php
                                the_post_thumbnail(
                                    'card-landscape',
                                    [
                                        'class'   => 'post-card__thumbnail',
                                        'loading' => 'lazy',
                                        'alt'     => '',
                                    ]
                                );
                                ?>
                            </a>
                        <?php endif; ?>

                        <div class="post-card__body">
                            <div class="post-card__meta">
                                <time class="post-card__date"
                                      datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                            </div>

                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"
                                   class="post-card__title-link">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <p class="post-card__excerpt">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 24, '…')); ?>
                            </p>

                            <a href="<?php the_permalink(); ?>"
                               class="post-card__read-more cc-btn cc-btn--ghost cc-btn--sm"
                               aria-label="<?php echo esc_attr(sprintf(__('Read more: %s', 'cupcake'), get_the_title())); ?>">
                                <?php esc_html_e('Read more', 'cupcake'); ?>
                            </a>
                        </div>

                    </article>
                <?php endwhile; ?>
            </div><!-- .posts-grid -->

            <!-- Pagination ──────────────────────────────────────────────── -->
            <nav class="posts-pagination" aria-label="<?php esc_attr_e('Search results pagination', 'cupcake'); ?>">
                <?php
                the_posts_pagination(
                    [
                        'mid_size'  => 2,
                        'prev_text' => __('&laquo; Previous', 'cupcake'),
                        'next_text' => __('Next &raquo;', 'cupcake'),
                    ]
                );
                ?>
            </nav>

        <?php else : ?>

            <div class="search-no-results">
                <p class="search-no-results__message">
                    <?php
                    printf(
                        /* translators: %s: search term */
                        esc_html__('Nothing was found for "%s". Try a different search.', 'cupcake'),
                        esc_html(get_search_query())
                    );
                    ?>
                </p>
            </div>

        <?php endif; ?>

    </div><!-- .container -->
</main><!-- #main -->

<?php
get_footer();
