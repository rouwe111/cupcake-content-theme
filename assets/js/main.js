(function () {
    'use strict';

    var toggle = document.querySelector('[data-nav-toggle]');
    var closeButtons = document.querySelectorAll('[data-nav-close]');
    var nav = document.getElementById('site-navigation');
    var navLinks = nav ? nav.querySelectorAll('a') : [];
    var mobileQuery = window.matchMedia('(max-width: 900px)');

    if (toggle && nav) {
        function setMenuState(open) {
            toggle.setAttribute('aria-expanded', String(open));
            nav.classList.toggle('is-open', open);
            document.body.classList.toggle('menu-open', open);

            closeButtons.forEach(function (button) {
                button.classList.toggle('is-open', open);
            });
        }

        toggle.addEventListener('click', function () {
            var isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            setMenuState(!isExpanded);
        });

        closeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                setMenuState(false);
            });
        });

        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (mobileQuery.matches) {
                    setMenuState(false);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setMenuState(false);
            }
        });

        window.addEventListener('resize', function () {
            if (!mobileQuery.matches) {
                setMenuState(false);
            }
        });
    }

    // Single-open accordion behavior for FAQ widgets.
    var accordions = document.querySelectorAll('[data-cc-accordion="single"]');

    accordions.forEach(function (accordion) {
        var items = accordion.querySelectorAll('.cc-faq-widget__item');

        function closeItem(item) {
            var trigger = item.querySelector('.cc-faq-widget__trigger');
            var panel = item.querySelector('.cc-faq-widget__panel');
            var icon = item.querySelector('.cc-faq-widget__icon');

            if (!trigger || !panel) {
                return;
            }

            item.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
            panel.hidden = true;

            if (icon) {
                icon.textContent = '▸';
            }
        }

        function openItem(item) {
            var trigger = item.querySelector('.cc-faq-widget__trigger');
            var panel = item.querySelector('.cc-faq-widget__panel');
            var icon = item.querySelector('.cc-faq-widget__icon');

            if (!trigger || !panel) {
                return;
            }

            item.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
            panel.hidden = false;

            if (icon) {
                icon.textContent = '▾';
            }
        }

        items.forEach(function (item) {
            var trigger = item.querySelector('.cc-faq-widget__trigger');
            if (!trigger) {
                return;
            }

            trigger.addEventListener('click', function () {
                var isOpen = trigger.getAttribute('aria-expanded') === 'true';

                items.forEach(function (otherItem) {
                    if (otherItem !== item) {
                        closeItem(otherItem);
                    }
                });

                if (isOpen) {
                    closeItem(item);
                } else {
                    openItem(item);
                }
            });
        });
    });

    // Testimonial carousel behavior.
    var carousels = document.querySelectorAll('[data-cc-testimonial-carousel="true"]');

    carousels.forEach(function (carousel) {
        var slides = Array.prototype.slice.call(
            carousel.querySelectorAll('.cc-testimonial-carousel__slide')
        );

        if (slides.length < 2) {
            return;
        }

        var prevButton = carousel.querySelector('.cc-testimonial-carousel__button--prev');
        var nextButton = carousel.querySelector('.cc-testimonial-carousel__button--next');
        var autoplay = carousel.getAttribute('data-autoplay') === 'true';
        var interval = parseInt(carousel.getAttribute('data-interval') || '6000', 10);

        if (!Number.isFinite(interval) || interval < 2000) {
            interval = 6000;
        }

        var activeIndex = 0;
        var timerId = null;

        function showSlide(nextIndex) {
            activeIndex = (nextIndex + slides.length) % slides.length;

            slides.forEach(function (slide, idx) {
                var isActive = idx === activeIndex;
                slide.hidden = !isActive;
                slide.classList.toggle('is-active', isActive);
            });
        }

        function stopAutoplay() {
            if (timerId) {
                window.clearInterval(timerId);
                timerId = null;
            }
        }

        function startAutoplay() {
            if (!autoplay) {
                return;
            }

            stopAutoplay();
            timerId = window.setInterval(function () {
                showSlide(activeIndex + 1);
            }, interval);
        }

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                showSlide(activeIndex - 1);
                startAutoplay();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                showSlide(activeIndex + 1);
                startAutoplay();
            });
        }

        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);

        showSlide(0);
        startAutoplay();
    });

    // Match subtitle/eyebrow accent color to the nearest matching background preset.
    function applyElementorContextAccent() {
        var rootStyles = window.getComputedStyle(document.documentElement);
        var colorProbe = document.createElement('span');
        colorProbe.style.position = 'absolute';
        colorProbe.style.width = '0';
        colorProbe.style.height = '0';
        colorProbe.style.overflow = 'hidden';
        colorProbe.style.visibility = 'hidden';
        document.body.appendChild(colorProbe);

        function normalizeColor(value) {
            if (!value) {
                return '';
            }

            colorProbe.style.color = value.trim();
            return window.getComputedStyle(colorProbe).color;
        }

        function getToken(name, fallback) {
            var value = rootStyles.getPropertyValue(name).trim();
            if (!value) {
                return fallback;
            }

            return value;
        }

        var presets = [
            {
                bg: normalizeColor(getToken('--cc-set-rose-bg', '#fff3f1')),
                accent: getToken('--cc-set-rose-icon', '#fa4d56')
            },
            {
                bg: normalizeColor(getToken('--cc-set-sage-bg', '#eaf3ec')),
                accent: getToken('--cc-set-sage-icon', '#4e7d5b')
            },
            {
                bg: normalizeColor(getToken('--cc-set-sand-bg', '#fff1dc')),
                accent: getToken('--cc-set-sand-icon', '#d98a2b')
            },
            {
                bg: normalizeColor(getToken('--cc-set-berry-bg', '#fbe8ef')),
                accent: getToken('--cc-set-berry-icon', '#c9417a')
            },
            {
                bg: normalizeColor(getToken('--cc-set-grey-bg', '#f3f4f6')),
                accent: getToken('--cc-set-grey-icon', '#6b7280')
            }
        ];

        var targets = document.querySelectorAll(
            '.cc-blogs-widget__subtitle, .cc-section-intro__eyebrow, .cc-blog-archive__eyebrow'
        );

        function isTransparent(color) {
            return (
                !color ||
                color === 'transparent' ||
                color === 'rgba(0, 0, 0, 0)'
            );
        }

        function findAccentForTarget(target) {
            var node = target;

            while (node && node !== document.documentElement) {
                var background = window.getComputedStyle(node).backgroundColor;

                if (!isTransparent(background)) {
                    var match = presets.find(function (preset) {
                        return preset.bg && preset.bg === background;
                    });

                    if (match) {
                        return match.accent;
                    }
                }

                node = node.parentElement;
            }

            return '';
        }

        targets.forEach(function (target) {
            var accent = findAccentForTarget(target);

            if (accent) {
                target.style.setProperty('--cc-context-accent', accent);
            } else {
                target.style.removeProperty('--cc-context-accent');
            }
        });

        document.body.removeChild(colorProbe);
    }

    if (document.body) {
        applyElementorContextAccent();
        window.setTimeout(applyElementorContextAccent, 250);
        window.setTimeout(applyElementorContextAccent, 1000);
        window.addEventListener('load', applyElementorContextAccent);

        // Elementor editor updates styles and wrappers dynamically.
        // Re-apply accent mapping when relevant nodes change.
        var scheduled = false;
        function scheduleAccentRefresh() {
            if (scheduled) {
                return;
            }

            scheduled = true;
            window.requestAnimationFrame(function () {
                scheduled = false;
                applyElementorContextAccent();
            });
        }

        var observer = new MutationObserver(function (mutations) {
            for (var i = 0; i < mutations.length; i += 1) {
                var mutation = mutations[i];

                if (mutation.type === 'childList') {
                    scheduleAccentRefresh();
                    return;
                }

                if (
                    mutation.type === 'attributes' &&
                    (mutation.attributeName === 'style' || mutation.attributeName === 'class')
                ) {
                    scheduleAccentRefresh();
                    return;
                }
            }
        });

        observer.observe(document.body, {
            subtree: true,
            childList: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }
})();
