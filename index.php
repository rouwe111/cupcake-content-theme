<?php
/**
 * CupCake Theme — index.php
 *
 * The fallback template for all queries not handled by a more specific template.
 * Used as the blog index when no front-page.php / home.php override applies.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

get_header();

$posts_page_id = (int) get_option('page_for_posts');
$base_blog_url = $posts_page_id > 0
    ? get_permalink($posts_page_id)
    : get_post_type_archive_link('post');

if (! is_string($base_blog_url) || '' === $base_blog_url) {
    $base_blog_url = home_url('/');
}

$selected_cat_id = (int) get_query_var('cat');
$blog_categories = get_categories(
    [
        'hide_empty' => true,
    ]
);
?>

<main id="main" class="site-main" role="main">
    <div class="container">

        <header class="archive-header cc-blog-archive__header">
            <div class="cc-section-intro cc-section-intro--center cc-blog-archive__intro">
                <span class="cc-section-intro__eyebrow cc-blog-archive__eyebrow"><?php esc_html_e('Inspiratie', 'cupcake'); ?></span>

                <h1 class="archive-header__title cc-section-intro__title cc-blog-archive__title">
                    <?php
                    if (is_home() && ! is_front_page()) {
                        esc_html_e('Laat je inspireren', 'cupcake');
                    } else {
                        esc_html_e('Blog artikelen', 'cupcake');
                    }
                    ?>
                </h1>

                <p class="archive-header__description cc-section-intro__description cc-blog-archive__description">
                    <?php esc_html_e('Klantcases, blogs en praktische tips over content, social media en zichtbaarheid.', 'cupcake'); ?>
                </p>
            </div>

            <?php if (! empty($blog_categories)) : ?>
                <nav class="cc-blog-archive__filters" aria-label="<?php esc_attr_e('Filter posts by category', 'cupcake'); ?>">
                    <a href="<?php echo esc_url($base_blog_url); ?>" class="cc-blog-archive__filter<?php echo 0 === $selected_cat_id ? ' is-active' : ''; ?>">
                        <?php esc_html_e('Alles', 'cupcake'); ?>
                    </a>

                    <?php foreach ($blog_categories as $category) : ?>
                        <a href="<?php echo esc_url(add_query_arg('cat', (string) $category->term_id, $base_blog_url)); ?>" class="cc-blog-archive__filter<?php echo $selected_cat_id === (int) $category->term_id ? ' is-active' : ''; ?>">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>

            <div class="cc-blogs-widget__grid" role="list">
                <?php
                while (have_posts()) :
                    the_post();

                    $post_id        = get_the_ID();
                    $post_title     = get_the_title($post_id);
                    $post_permalink = get_permalink($post_id);
                    $post_date      = get_the_date('j F Y', $post_id);
                    $categories     = get_the_category($post_id);
                    $category_name  = ! empty($categories) ? $categories[0]->name : __('Blog', 'cupcake');
                    $word_count     = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
                    $read_time      = max(1, (int) ceil($word_count / 200));
                    ?>
                    <article id="post-<?php the_ID(); ?>"
                             <?php post_class('cc-blogs-widget__card'); ?>
                             role="listitem">

                        <a class="cc-blogs-widget__card-link" href="<?php echo esc_url($post_permalink); ?>" aria-label="<?php echo esc_attr($post_title); ?>">
                            <div class="cc-blogs-widget__media-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php
                                    the_post_thumbnail(
                                        'card-landscape',
                                        [
                                            'class'   => 'cc-blogs-widget__image',
                                            'loading' => 'lazy',
                                            'alt'     => $post_title,
                                        ]
                                    );
                                    ?>
                                <?php else : ?>
                                    <span class="cc-blogs-widget__image-placeholder" aria-hidden="true"></span>
                                <?php endif; ?>
                            </div>

                            <div class="cc-blogs-widget__body">
                                <span class="cc-blogs-widget__tag"><?php echo esc_html($category_name); ?></span>

                                <h2 class="cc-blogs-widget__card-title">
                                    <?php echo esc_html($post_title); ?>
                                </h2>

                                <span class="cc-blogs-widget__meta">
                                    <?php echo esc_html($post_date . ' · ' . $read_time . ' ' . __('min', 'cupcake')); ?>
                                </span>
                            </div>
                        </a>

                    </article>
                <?php endwhile; ?>
            </div><!-- .posts-grid -->

            <!-- Pagination ──────────────────────────────────────────────── -->
            <nav class="posts-pagination" aria-label="<?php esc_attr_e('Posts pagination', 'cupcake'); ?>">
                <?php
                the_posts_pagination(
                    [
                        'mid_size'  => 2,
                        'prev_text' => __('Vorige', 'cupcake'),
                        'next_text' => __('Volgende', 'cupcake'),
                    ]
                );
                ?>
            </nav>

        <?php else : ?>

            <p class="no-results">
                <?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Try a search.', 'cupcake'); ?>
            </p>
            <?php get_search_form(); ?>

        <?php endif; ?>

    </div><!-- .container -->
</main><!-- #main -->

<?php
get_footer();
