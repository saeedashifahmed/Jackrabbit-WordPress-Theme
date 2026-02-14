/**
 * Jackrabbit - Front-End JavaScript
 *
 * Lightweight enhancements for navigation, reading UX, and archive interactions.
 *
 * @package Jackrabbit
 * @since   1.1.0
 */

(function () {
    'use strict';

    document.documentElement.classList.add('jk-js');

    function onReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
            return;
        }
        callback();
    }

    onReady(function () {
        initMobileMenu();
        initHeaderSearch();
        initThemeToggle();
        initSmoothScroll();
        initLazyObserver();
        initReadingProgress();
        initBackToTop();
        initTableOfContents();
        initCopyLinkButtons();
        initArchiveViewToggle();
        initRevealAnimations();
    });

    function initMobileMenu() {
        var menuToggle = document.querySelector('.menu-toggle');
        var mainNav = document.querySelector('.main-navigation');

        if (!menuToggle || !mainNav) {
            return;
        }

        function closeMenu() {
            menuToggle.setAttribute('aria-expanded', 'false');
            mainNav.classList.remove('is-open');
            document.body.classList.remove('jk-menu-open');
        }

        menuToggle.addEventListener('click', function () {
            var expanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', String(!expanded));
            mainNav.classList.toggle('is-open');
            document.body.classList.toggle('jk-menu-open');
        });

        document.addEventListener('click', function (event) {
            if (!menuToggle.contains(event.target) && !mainNav.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mainNav.classList.contains('is-open')) {
                closeMenu();
                menuToggle.focus();
            }
        });
    }

    function initHeaderSearch() {
        var searchToggle = document.querySelector('.search-toggle');
        var searchPanel = document.getElementById('header-search-panel');

        if (!searchToggle || !searchPanel) {
            return;
        }

        var header = document.getElementById('masthead');

        function closeSearch() {
            searchToggle.setAttribute('aria-expanded', 'false');
            searchPanel.setAttribute('hidden', 'hidden');
            searchPanel.classList.remove('is-open');
        }

        searchToggle.addEventListener('click', function () {
            var expanded = searchToggle.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                closeSearch();
                return;
            }

            searchToggle.setAttribute('aria-expanded', 'true');
            searchPanel.removeAttribute('hidden');
            searchPanel.classList.add('is-open');

            var field = searchPanel.querySelector('.search-field');
            if (field) {
                field.focus();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeSearch();
            }
        });

        document.addEventListener('click', function (event) {
            if (!header || !searchPanel.classList.contains('is-open')) {
                return;
            }

            if (!header.contains(event.target)) {
                closeSearch();
            }
        });
    }

    function initThemeToggle() {
        var storageKey = 'jackrabbit-theme';
        var toggleButton = document.querySelector('.theme-toggle');
        var root = document.documentElement;

        function preferredTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);

            if (!toggleButton) {
                return;
            }

            var isDark = theme === 'dark';
            toggleButton.setAttribute('aria-pressed', String(isDark));
            toggleButton.setAttribute(
                'aria-label',
                isDark ? 'Switch to light mode' : 'Switch to dark mode'
            );
        }

        var storedTheme = null;
        try {
            storedTheme = window.localStorage.getItem(storageKey);
        } catch (error) {
            storedTheme = null;
        }

        applyTheme(storedTheme === 'dark' || storedTheme === 'light' ? storedTheme : preferredTheme());

        if (!toggleButton) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(nextTheme);

            try {
                window.localStorage.setItem(storageKey, nextTheme);
            } catch (error) {
                // Ignore storage write errors.
            }
        });
    }

    function initSmoothScroll() {
        var links = document.querySelectorAll('a[href^="#"]');
        if (!links.length) {
            return;
        }

        Array.prototype.forEach.call(links, function (link) {
            link.addEventListener('click', function (event) {
                var targetId = this.getAttribute('href');
                if (!targetId || targetId === '#') {
                    return;
                }

                var target = document.querySelector(targetId);
                if (!target) {
                    return;
                }

                event.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                if (window.history && window.history.pushState) {
                    window.history.pushState(null, '', targetId);
                }
            });
        });
    }

    function initLazyObserver() {
        if (!('IntersectionObserver' in window)) {
            return;
        }

        var lazyImages = document.querySelectorAll('img[data-src]');
        if (!lazyImages.length) {
            return;
        }

        var imageObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var image = entry.target;
                image.src = image.dataset.src;
                if (image.dataset.srcset) {
                    image.srcset = image.dataset.srcset;
                }

                image.removeAttribute('data-src');
                image.removeAttribute('data-srcset');
                observer.unobserve(image);
            });
        }, {
            rootMargin: '200px 0px',
        });

        Array.prototype.forEach.call(lazyImages, function (image) {
            imageObserver.observe(image);
        });
    }

    function initReadingProgress() {
        var article = document.querySelector('.single-post__content');
        if (!article) {
            return;
        }

        var progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.setAttribute('role', 'progressbar');
        progressBar.setAttribute('aria-label', 'Reading progress');
        progressBar.setAttribute('aria-valuemin', '0');
        progressBar.setAttribute('aria-valuemax', '100');
        document.body.appendChild(progressBar);

        function updateProgress() {
            var articleRect = article.getBoundingClientRect();
            var articleTop = articleRect.top + window.scrollY;
            var articleHeight = article.offsetHeight;
            var viewportHeight = window.innerHeight;
            var scrollY = window.scrollY;

            var progress = ((scrollY - articleTop + viewportHeight) / (articleHeight + viewportHeight)) * 100;
            progress = Math.min(100, Math.max(0, progress));

            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', String(Math.round(progress)));
        }

        updateProgress();
        window.addEventListener('scroll', updateProgress, { passive: true });
        window.addEventListener('resize', updateProgress);
    }

    function initBackToTop() {
        var button = document.querySelector('.back-to-top');
        if (!button) {
            return;
        }

        function updateVisibility() {
            if (window.scrollY > 500) {
                button.classList.add('is-visible');
                return;
            }

            button.classList.remove('is-visible');
        }

        button.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        updateVisibility();
        window.addEventListener('scroll', updateVisibility, { passive: true });
    }

    function initTableOfContents() {
        var toc = document.querySelector('[data-jk-toc]');
        var tocList = toc ? toc.querySelector('.single-post__toc-list') : null;
        var article = document.querySelector('.single-post__content');

        if (!toc || !tocList || !article) {
            return;
        }

        var headings = article.querySelectorAll('h2, h3');
        if (!headings.length) {
            return;
        }

        var slugMap = {};
        Array.prototype.forEach.call(headings, function (heading, index) {
            var text = heading.textContent.trim();
            if (!text) {
                return;
            }

            if (!heading.id) {
                var baseSlug = text
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                if (!baseSlug) {
                    baseSlug = 'section-' + (index + 1);
                }

                if (!slugMap[baseSlug]) {
                    slugMap[baseSlug] = 1;
                    heading.id = baseSlug;
                } else {
                    slugMap[baseSlug] += 1;
                    heading.id = baseSlug + '-' + slugMap[baseSlug];
                }
            }

            var listItem = document.createElement('li');
            listItem.className = 'single-post__toc-item single-post__toc-item--' + heading.tagName.toLowerCase();

            var anchor = document.createElement('a');
            anchor.href = '#' + heading.id;
            anchor.textContent = text;

            listItem.appendChild(anchor);
            tocList.appendChild(listItem);
        });

        if (!tocList.children.length) {
            return;
        }

        toc.removeAttribute('hidden');

        // Signal CSS to enable the two-column TOC layout.
        var contentWrap = toc.closest('.single-post__content-wrap');
        if (contentWrap) {
            contentWrap.classList.add('has-toc');
        }

        if (!('IntersectionObserver' in window)) {
            return;
        }

        var tocLinks = tocList.querySelectorAll('a');
        var linkMap = {};
        Array.prototype.forEach.call(tocLinks, function (link) {
            linkMap[link.getAttribute('href').replace('#', '')] = link;
        });

        var headingObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                Array.prototype.forEach.call(tocLinks, function (link) {
                    link.classList.remove('is-active');
                });

                var activeLink = linkMap[entry.target.id];
                if (activeLink) {
                    activeLink.classList.add('is-active');
                }
            });
        }, {
            rootMargin: '-25% 0px -60% 0px',
            threshold: 0,
        });

        Array.prototype.forEach.call(headings, function (heading) {
            headingObserver.observe(heading);
        });
    }

    function initCopyLinkButtons() {
        var buttons = document.querySelectorAll('[data-copy-link]');
        if (!buttons.length) {
            return;
        }

        function fallbackCopy(text) {
            var tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
        }

        Array.prototype.forEach.call(buttons, function (button) {
            var originalLabel = button.textContent;

            button.addEventListener('click', function () {
                var link = window.location.href;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(link).catch(function () {
                        fallbackCopy(link);
                    });
                } else {
                    fallbackCopy(link);
                }

                button.textContent = 'Copied';
                window.setTimeout(function () {
                    button.textContent = originalLabel;
                }, 1400);
            });
        });
    }

    function initArchiveViewToggle() {
        var grid = document.querySelector('.posts-grid');
        var buttons = document.querySelectorAll('.archive-view-toggle__btn');
        var storageKey = 'jackrabbit-archive-view';

        if (!grid || !buttons.length) {
            return;
        }

        function applyView(view) {
            var isList = view === 'list';
            grid.classList.toggle('posts-grid--list', isList);

            Array.prototype.forEach.call(buttons, function (button) {
                var active = button.getAttribute('data-view-toggle') === view;
                button.classList.toggle('is-active', active);
                button.setAttribute('aria-pressed', String(active));
            });
        }

        var savedView = null;
        try {
            savedView = window.localStorage.getItem(storageKey);
        } catch (error) {
            savedView = null;
        }

        applyView(savedView === 'list' ? 'list' : 'grid');

        Array.prototype.forEach.call(buttons, function (button) {
            button.addEventListener('click', function () {
                var view = button.getAttribute('data-view-toggle') === 'list' ? 'list' : 'grid';
                applyView(view);

                try {
                    window.localStorage.setItem(storageKey, view);
                } catch (error) {
                    // Ignore storage write errors.
                }
            });
        });
    }

    function initRevealAnimations() {
        var revealItems = document.querySelectorAll('[data-jk-reveal]');
        if (!revealItems.length) {
            return;
        }

        if (!('IntersectionObserver' in window)) {
            Array.prototype.forEach.call(revealItems, function (item) {
                item.classList.add('is-visible');
            });
            return;
        }

        var revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            rootMargin: '0px 0px -12% 0px',
            threshold: 0.12,
        });

        Array.prototype.forEach.call(revealItems, function (item) {
            revealObserver.observe(item);
        });
    }
})();
