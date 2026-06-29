(function (wp) {
    'use strict';

    if (!wp || !wp.blocks || !wp.domReady) {
        return;
    }

    wp.domReady(function () {
        wp.blocks.registerBlockStyle('core/paragraph', {
            name: 'cupcake-highlight-quote',
            label: 'CupCake Highlight Quote',
        });

        wp.blocks.registerBlockStyle('core/quote', {
            name: 'cupcake-highlight',
            label: 'CupCake Highlight',
        });

        wp.blocks.registerBlockStyle('core/pullquote', {
            name: 'cupcake-highlight',
            label: 'CupCake Highlight',
        });
    });
})(window.wp);
