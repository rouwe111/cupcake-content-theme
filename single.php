<?php
/**
 * CupCake Theme — Single post template.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main" role="main">
    <div class="container">

        <?php
        while (have_posts()) :
            the_post();

            $post_id = get_the_ID();

            $posts_page_id = (int) get_option('page_for_posts');
            $blog_url      = $posts_page_id > 0
                ? get_permalink($posts_page_id)
                : get_post_type_archive_link('post');

            if (! is_string($blog_url) || '' === $blog_url) {
                $blog_url = home_url('/');
            }

            $categories    = get_the_category($post_id);
            $category_name = ! empty($categories) ? $categories[0]->name : __('Blog', 'cupcake');
            $post_date     = get_the_date('j F Y', $post_id);

            $word_count = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
            $read_time  = max(1, (int) ceil($word_count / 200));

            $author_name    = get_the_author();
            $author_initial = function_exists('mb_substr')
                ? mb_strtoupper(mb_substr(trim($author_name), 0, 1))
                : strtoupper(substr(trim($author_name), 0, 1));

            $author_bio = get_the_author_meta('description');
            if ('' === trim((string) $author_bio)) {
                $author_bio = __('Freelance marketingmanager & oprichter van Cupcake Content.', 'cupcake');
            }

            $contact_page = get_page_by_path('contact');
            $contact_url  = $contact_page instanceof WP_Post
                ? get_permalink($contact_page)
                : home_url('/contact/');

            $related_posts = get_posts(
                [
                    'post_type'           => 'post',
                    'post_status'         => 'publish',
                    'posts_per_page'      => 3,
                    'post__not_in'        => [$post_id],
                    'ignore_sticky_posts' => true,
                ]
            );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('cc-single-post'); ?>>

                <header class="cc-single-post__header">
                    <a class="cc-single-post__back-link" href="<?php echo esc_url($blog_url); ?>">
                        <?php esc_html_e('← Terug naar inspiratie', 'cupcake'); ?>
                    </a>

                    <span class="cc-single-post__category"><?php echo esc_html($category_name); ?></span>

                    <h1 class="cc-single-post__title"><?php the_title(); ?></h1>

                    <div class="cc-single-post__meta">
                        <span class="cc-single-post__meta-avatar" aria-hidden="true"><?php echo esc_html($author_initial); ?></span>
                        <span class="cc-single-post__meta-text">
                            <?php
                            echo esc_html(
                                sprintf(
                                    '%1$s · %2$s · %3$s %4$s',
                                    $author_name,
                                    $post_date,
                                    $read_time,
                                    __('min', 'cupcake')
                                )
                            );
                            ?>
                        </span>
                    </div>
                </header>

                <div class="cc-single-post__featured-wrap">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php
                        the_post_thumbnail(
                            'hero',
                            [
                                'class'   => 'cc-single-post__featured-image',
                                'loading' => 'eager',
                                'alt'     => esc_attr(get_the_title()),
                            ]
                        );
                        ?>
                    <?php else : ?>
                        <div class="cc-single-post__featured-placeholder" aria-hidden="true">
                            <?php echo esc_html(strtolower($category_name)); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="cc-single-post__content entry-content">
                    <?php the_content(); ?>
                </div>

                <section class="cc-single-post__author-box" aria-label="<?php esc_attr_e('Author details', 'cupcake'); ?>">
                    <span class="cc-single-post__author-avatar" aria-hidden="true"><?php echo esc_html($author_initial); ?></span>

                    <div class="cc-single-post__author-content">
                        <span class="cc-single-post__author-name">
                            <?php
                            printf(
                                /* translators: %s: author name */
                                esc_html__('Geschreven door %s', 'cupcake'),
                                esc_html($author_name)
                            );
                            ?>
                        </span>
                        <span class="cc-single-post__author-bio"><?php echo esc_html($author_bio); ?></span>
                    </div>

                    <a class="cc-single-post__author-cta" href="<?php echo esc_url($contact_url); ?>">
                        <?php esc_html_e('Werk met mij', 'cupcake'); ?>
                    </a>
                </section>

                <?php if (! empty($related_posts)) : ?>
                    <section class="cc-single-post__related" aria-label="<?php esc_attr_e('Related posts', 'cupcake'); ?>">
                        <h2 class="cc-single-post__related-title"><?php esc_html_e('Meer inspiratie', 'cupcake'); ?></h2>

                        <div class="cc-blogs-widget__grid">
                            <?php foreach ($related_posts as $related_post) : ?>
                                <?php
                                $related_id         = (int) $related_post->ID;
                                $related_title      = get_the_title($related_id);
                                $related_permalink  = get_permalink($related_id);
                                $related_categories = get_the_category($related_id);
                                $related_cat_name   = ! empty($related_categories) ? $related_categories[0]->name : __('Blog', 'cupcake');
                                $related_date       = get_the_date('j F Y', $related_id);
                                $related_words      = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $related_id)));
                                $related_read_time  = max(1, (int) ceil($related_words / 200));
                                ?>

                                <article class="cc-blogs-widget__card">
                                    <a class="cc-blogs-widget__card-link" href="<?php echo esc_url($related_permalink); ?>" aria-label="<?php echo esc_attr($related_title); ?>">
                                        <div class="cc-blogs-widget__media-link">
                                            <?php if (has_post_thumbnail($related_id)) : ?>
                                                <?php echo get_the_post_thumbnail($related_id, 'card-landscape', ['class' => 'cc-blogs-widget__image', 'loading' => 'lazy', 'alt' => esc_attr($related_title)]); ?>
                                            <?php else : ?>
                                                <span class="cc-blogs-widget__image-placeholder" aria-hidden="true"></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="cc-blogs-widget__body">
                                            <span class="cc-blogs-widget__tag"><?php echo esc_html($related_cat_name); ?></span>
                                            <h3 class="cc-blogs-widget__card-title"><?php echo esc_html($related_title); ?></h3>
                                            <span class="cc-blogs-widget__meta">
                                                <?php echo esc_html($related_date . ' · ' . $related_read_time . ' ' . __('min', 'cupcake')); ?>
                                            </span>
                                        </div>
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; ?>

    </div><!-- .container -->
</main><!-- #main -->

<?php
get_footer();
