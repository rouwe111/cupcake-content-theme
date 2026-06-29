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
})();
