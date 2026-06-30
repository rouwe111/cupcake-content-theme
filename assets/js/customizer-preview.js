/* global wp */
(function (api) {
    if (!api || !api.customize) {
        return;
    }

    function bindCssVar(settingId, cssVar, suffix) {
        api(settingId, function (value) {
            value.bind(function (to) {
                document.documentElement.style.setProperty(cssVar, to + (suffix || ''));
            });
        });
    }

    bindCssVar('cupcake_color_primary', '--cc-color-primary');
    bindCssVar('cupcake_color_secondary', '--cc-color-secondary');
    bindCssVar('cupcake_color_accent', '--cc-color-accent');
    bindCssVar('cupcake_color_accent_light', '--cc-color-accent-light');
    bindCssVar('cupcake_color_surface', '--cc-color-surface');
    bindCssVar('cupcake_color_surface_alt', '--cc-color-surface-alt');
    bindCssVar('cupcake_color_text', '--cc-color-text');
    bindCssVar('cupcake_color_text_muted', '--cc-color-text-muted');
    bindCssVar('cupcake_color_border', '--cc-color-border');
    bindCssVar('cupcake_header_bg_color', '--cc-header-bg');
    bindCssVar('cupcake_header_text_color', '--cc-header-text');
    bindCssVar('cupcake_footer_bg_color', '--cc-footer-bg');
    bindCssVar('cupcake_footer_text_color', '--cc-footer-text');
    bindCssVar('cupcake_set_rose_bg', '--cc-set-rose-bg');
    bindCssVar('cupcake_set_rose_bg', '--e-global-color-cupcake-rose-light');
    bindCssVar('cupcake_set_rose_icon', '--cc-set-rose-icon');
    bindCssVar('cupcake_set_rose_icon', '--e-global-color-cupcake-rose-dark');
    bindCssVar('cupcake_set_sage_bg', '--cc-set-sage-bg');
    bindCssVar('cupcake_set_sage_bg', '--e-global-color-cupcake-sage-light');
    bindCssVar('cupcake_set_sage_icon', '--cc-set-sage-icon');
    bindCssVar('cupcake_set_sage_icon', '--e-global-color-cupcake-sage-dark');
    bindCssVar('cupcake_set_sand_bg', '--cc-set-sand-bg');
    bindCssVar('cupcake_set_sand_bg', '--e-global-color-cupcake-sand-light');
    bindCssVar('cupcake_set_sand_icon', '--cc-set-sand-icon');
    bindCssVar('cupcake_set_sand_icon', '--e-global-color-cupcake-sand-dark');
    bindCssVar('cupcake_set_berry_bg', '--cc-set-berry-bg');
    bindCssVar('cupcake_set_berry_bg', '--e-global-color-cupcake-berry-light');
    bindCssVar('cupcake_set_berry_icon', '--cc-set-berry-icon');
    bindCssVar('cupcake_set_berry_icon', '--e-global-color-cupcake-berry-dark');
    bindCssVar('cupcake_set_grey_bg', '--cc-set-grey-bg');
    bindCssVar('cupcake_set_grey_bg', '--e-global-color-cupcake-grey-light');
    bindCssVar('cupcake_set_grey_icon', '--cc-set-grey-icon');
    bindCssVar('cupcake_set_grey_icon', '--e-global-color-cupcake-grey-dark');
    bindCssVar('cupcake_body_font_size', '--cc-body-font-size', 'px');
    bindCssVar('cupcake_header_logo_width', '--cc-header-logo-max-width', 'px');

    api('cupcake_footer_tagline', function (value) {
        value.bind(function (to) {
            var nodes = document.querySelectorAll('.site-footer__tagline');
            nodes.forEach(function (node) {
                node.textContent = to;
            });
        });
    });
})(wp);