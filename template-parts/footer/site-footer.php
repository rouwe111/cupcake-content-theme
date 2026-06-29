<?php
/**
 * CupCake Theme — Site footer template part.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

$tagline = esc_html((string) get_theme_mod('cupcake_footer_tagline', __('Built with care.', 'cupcake')));
$year    = date('Y'); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date

$instagram_url = esc_url((string) get_theme_mod('cupcake_social_instagram_url', ''));
$linkedin_url  = esc_url((string) get_theme_mod('cupcake_social_linkedin_url', ''));

$has_custom_social_links = ('' !== $instagram_url) || ('' !== $linkedin_url);
?>
<footer class="site-footer" role="contentinfo">

    <!-- Top row: brand + two nav columns ─────────────────────────────── -->
    <div class="site-footer__top">
        <div class="site-footer__inner">

            <!-- Brand column ─────────────────────────────────────────── -->
            <div class="site-footer__brand">
                <?php
                $custom_logo_id = (int) get_theme_mod('cupcake_footer_logo_dark_id', 0);

                if (! $custom_logo_id) {
                    $custom_logo_id = (int) get_theme_mod('cupcake_logo_id', 0);
                }

                if (! $custom_logo_id) {
                    $custom_logo_id = (int) get_theme_mod('custom_logo');
                }
                if ($custom_logo_id) :
                    echo wp_get_attachment_image(
                        $custom_logo_id,
                        'full',
                        false,
                        [
                            'class'   => 'site-footer__logo',
                            'loading' => 'lazy',
                            'alt'     => esc_attr(get_bloginfo('name')),
                        ]
                    );
                else :
                    ?>
                    <p class="site-footer__site-name">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                    </p>
                    <?php
                endif;
                ?>
                <?php if ($tagline) : ?>
                    <p class="site-footer__tagline"><?php echo $tagline; ?></p>
                <?php endif; ?>
            </div><!-- .site-footer__brand -->

            <!-- Nav column 1 ─────────────────────────────────────────── -->
            <?php if (has_nav_menu('footer-col-1')) : ?>
                <nav class="site-footer__nav site-footer__nav--col1"
                     aria-label="<?php esc_attr_e('Footer navigation column 1', 'cupcake'); ?>">
                    <?php
                    wp_nav_menu(
                        [
                            'theme_location' => 'footer-col-1',
                            'menu_class'     => 'site-footer__nav-list',
                            'container'      => false,
                            'depth'          => 1,
                            'items_wrap'     => '<ul class="%2$s" role="list">%3$s</ul>',
                            'fallback_cb'    => false,
                        ]
                    );
                    ?>
                </nav>
            <?php endif; ?>

            <!-- Nav column 2 ─────────────────────────────────────────── -->
            <?php if (has_nav_menu('footer-col-2')) : ?>
                <nav class="site-footer__nav site-footer__nav--col2"
                     aria-label="<?php esc_attr_e('Footer navigation column 2', 'cupcake'); ?>">
                    <?php
                    wp_nav_menu(
                        [
                            'theme_location' => 'footer-col-2',
                            'menu_class'     => 'site-footer__nav-list',
                            'container'      => false,
                            'depth'          => 1,
                            'items_wrap'     => '<ul class="%2$s" role="list">%3$s</ul>',
                            'fallback_cb'    => false,
                        ]
                    );
                    ?>
                </nav>
            <?php endif; ?>

        </div><!-- .site-footer__inner -->
    </div><!-- .site-footer__top -->

    <!-- Bottom row: copyright + social ───────────────────────────────── -->
    <div class="site-footer__bottom">
        <div class="site-footer__inner">

            <p class="site-footer__copyright">
                &copy; <?php echo esc_html($year); ?>
                <?php bloginfo('name'); ?>.
                <?php esc_html_e('All rights reserved.', 'cupcake'); ?>
            </p>

            <!-- Social links (Customizer URLs) with menu fallback ───────── -->
            <?php if ($has_custom_social_links) : ?>
                <nav class="site-footer__social"
                     aria-label="<?php esc_attr_e('Social links', 'cupcake'); ?>">
                    <ul class="site-footer__social-list" role="list">
                        <?php if ('' !== $instagram_url) : ?>
                            <li>
                                <a class="site-footer__social-link" href="<?php echo $instagram_url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Instagram', 'cupcake'); ?>">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <rect x="3" y="3" width="18" height="18" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="1.8"></rect>
                                        <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"></circle>
                                        <circle cx="17.2" cy="6.8" r="1" fill="currentColor"></circle>
                                    </svg>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ('' !== $linkedin_url) : ?>
                            <li>
                                <a class="site-footer__social-link" href="<?php echo $linkedin_url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('LinkedIn', 'cupcake'); ?>">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path d="M4.98 3.5a2 2 0 1 1 0 4 2 2 0 0 1 0-4ZM3 9h4v12H3V9Zm6 0h3.8v1.7h.05c.53-.95 1.83-1.95 3.77-1.95 4 0 4.74 2.5 4.74 5.8V21H17v-5.3c0-1.27-.02-2.9-1.77-2.9-1.77 0-2.04 1.38-2.04 2.8V21H9V9Z" fill="currentColor"></path>
                                    </svg>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php elseif (has_nav_menu('social')) : ?>
                <nav class="site-footer__social"
                     aria-label="<?php esc_attr_e('Social links', 'cupcake'); ?>">
                    <?php
                    wp_nav_menu(
                        [
                            'theme_location'  => 'social',
                            'menu_class'      => 'site-footer__social-list',
                            'container'       => false,
                            'depth'           => 1,
                            'items_wrap'      => '<ul class="%2$s" role="list">%3$s</ul>',
                            'link_before'     => '<span class="screen-reader-text">',
                            'link_after'      => '</span>',
                            'fallback_cb'     => false,
                        ]
                    );
                    ?>
                </nav>
            <?php endif; ?>

        </div><!-- .site-footer__inner -->
    </div><!-- .site-footer__bottom -->

</footer><!-- .site-footer -->
