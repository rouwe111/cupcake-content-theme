<?php
/**
 * CupCake Theme — Site header template part.
 *
 * @package CupCake
 */

defined('ABSPATH') || exit;

$sticky_header = (bool) get_theme_mod('cupcake_sticky_header', true);
$header_class  = 'site-header';

if ($sticky_header) {
    $header_class .= ' site-header--sticky';
}
?>
<header class="<?php echo esc_attr($header_class); ?>" role="banner">
    <div class="site-header__inner">

        <?php
        $custom_logo_id = (int) get_theme_mod('cupcake_logo_id', 0);

        if (! $custom_logo_id) {
            $custom_logo_id = (int) get_theme_mod('custom_logo');
        }
        ?>

        <!-- Mobile hamburger ───────────────────────────────────────────── -->
        <button
            class="site-header__hamburger"
            data-nav-toggle
            aria-controls="site-navigation"
            aria-expanded="false"
            aria-label="<?php esc_attr_e('Toggle navigation menu', 'cupcake'); ?>">
            <span class="site-header__hamburger-line" aria-hidden="true"></span>
            <span class="site-header__hamburger-line" aria-hidden="true"></span>
            <span class="site-header__hamburger-line" aria-hidden="true"></span>
        </button>

        <!-- Branding ───────────────────────────────────────────────────── -->
        <?php if ($custom_logo_id) : ?>
            <a class="site-branding site-branding__link"
               href="<?php echo esc_url(home_url('/')); ?>"
               rel="home"
               aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php
                echo wp_get_attachment_image(
                    $custom_logo_id,
                    'full',
                    false,
                    [
                        'class'   => 'site-branding__logo',
                        'loading' => 'eager',
                        'alt'     => esc_attr(get_bloginfo('name')),
                    ]
                );
                ?>
            </a>
        <?php endif; ?>

        <!-- Primary navigation ─────────────────────────────────────────── -->
        <nav class="site-nav" id="site-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'cupcake'); ?>">
            <button
                class="site-nav__close"
                type="button"
                data-nav-close
                aria-label="<?php esc_attr_e('Close navigation menu', 'cupcake'); ?>">
                <span aria-hidden="true">&times;</span>
            </button>

            <?php
            $selected_header_menu = (int) get_theme_mod('cupcake_primary_header_menu', 0);

            $menu_args = [
                'menu_id'         => 'primary-menu',
                'menu_class'      => 'site-nav__list',
                'container'       => false,
                'fallback_cb'     => false,
                'depth'           => 2,
                'items_wrap'      => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
            ];

            if ($selected_header_menu > 0) {
                $menu_args['menu'] = $selected_header_menu;
            } else {
                $menu_args['theme_location'] = 'primary';
            }

            wp_nav_menu(
                $menu_args
            );
            ?>

            <!-- Header CTA ─────────────────────────────────────────────── -->
            <div class="site-header__cta">
                <a href="<?php echo esc_url(home_url('/contact')); ?>"
                   class="site-header__cta-link">
                    <?php esc_html_e('Offerte aanvragen', 'cupcake'); ?>
                </a>
            </div>

            <?php if ($custom_logo_id) : ?>
                <a class="site-nav__brand"
                   href="<?php echo esc_url(home_url('/')); ?>"
                   rel="home"
                   aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <?php
                    echo wp_get_attachment_image(
                        $custom_logo_id,
                        'full',
                        false,
                        [
                            'class'   => 'site-nav__brand-logo',
                            'loading' => 'lazy',
                            'alt'     => esc_attr(get_bloginfo('name')),
                        ]
                    );
                    ?>
                </a>
            <?php endif; ?>
        </nav><!-- #site-navigation -->

    </div><!-- .site-header__inner -->
</header><!-- .site-header -->
