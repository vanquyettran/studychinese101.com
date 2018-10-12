<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/29/2018
 * Time: 4:59 PM
 */
?>
<script>
    TextWrapper.elementTextLinesLimit('[data-max-line-count]');
</script>

<script>
    !function (expandableWrappers) {
        [].forEach.call(expandableWrappers, function (expandableWrapper) {
            var expandable = expandableWrapper.querySelector('.expandable-content');
            if (expandable.scrollHeight > expandable.clientHeight) {
                expandable.classList.add('expandable');
                var button = elm('button', 'Xem thêm', {
                    class: 'expand-button',
                    onclick: function () {
                        if (expandable.classList.contains('expanded')) {
                            expandable.classList.remove('expanded');
                            button.textContent = 'Xem thêm';
                        } else {
                            expandable.classList.add('expanded');
                            button.textContent = 'Thu gọn';
                        }
                    }
                });
                expandableWrapper.insertBefore(button, expandable.nextElementSibling);
            } else {
                expandable.classList.remove('expandable');

            }
        });
    }(document.querySelectorAll('.expandable-wrapper'));
</script>

<script>
    // Youtube video
    !function (paras) {
        [].forEach.call(paras, function (para) {
            var iFrames = para.querySelectorAll('iframe[src^="https://www.youtube.com/embed/"]');
            [].forEach.call(iFrames, function (iFrame) {
                if (!iFrame.getAttribute('width') && !iFrame.getAttribute('height')) {
                    var wrapperInner = elm('div', null);
                    var wrapper = elm('div', wrapperInner, {'class': 'video aspect-ratio __16x9'});
                    iFrame.parentNode.insertBefore(wrapper, iFrame);
                    wrapperInner.appendChild(iFrame);
                }
            });
        });
    }(document.querySelectorAll('.paragraph'));
</script>

<script>
    // Sticky
    function updateAllStickies() {
        var containers = document.querySelectorAll('[data-sticky-container]');

        [].forEach.call(containers, function (container) {
            var startY = container.getAttribute('data-sticky-start');
            var stickyId = container.getAttribute('data-sticky-container');
            var stickyElms = container.querySelectorAll('[data-sticky-in="' + stickyId + '"]:not([data-sticky-copy])');
            [].forEach.call(stickyElms, function (el) {
                if (el.getAttribute('data-sticky-responsive').split('|').indexOf(getResponsiveMode()) > -1) {
                    if (!el.hasAttribute('data-sticky')) {
                        initSticky(el, container, startY);
                    }
                } else if (el.hasAttribute('data-sticky')) {
                    destroySticky(el);
                }
            });
        });
    }

    updateAllStickies();
    window.addEventListener('resize', updateAllStickies);

    /**
     *
     * @param {HTMLElement} el
     * @param {HTMLElement} ctn
     * @param {string} startY
     */
    function initSticky(el, ctn, startY) {
        var copy = el.cloneNode(true);
        copy.style.visibility = 'hidden';
        copy.style.pointerEvents = 'none';
        copy.setAttribute('data-sticky-copy', '');
        el.stickyCopy = copy;

        el.setAttribute('data-sticky', '');
        el.setAttribute('data-style-position', el.style.position);
        el.setAttribute('data-style-top', el.style.top);
        el.setAttribute('data-style-width', el.style.width);

        el.onWindowScroll = function () {
            var elRect = el.getBoundingClientRect();
            var copyRect = copy.getBoundingClientRect();
            var ctnRect = ctn.getBoundingClientRect();
            var y0;
            var fix = function () {
                el.parentNode.insertBefore(copy, el);
                el.style.position = 'fixed';
                el.style.width = copy.offsetWidth + 'px';

                if (ctnRect.top + ctn.offsetHeight > el.offsetHeight + y0) {
                    el.style.top = y0 + 'px';
                } else {
                    el.style.top = (ctnRect.top + ctn.offsetHeight) - el.offsetHeight + 'px';
                }

                el.setAttribute('data-sticky', 'fixed');
            };
            var release = function () {
                el.style.top = el.getAttribute('data-style-top');
                el.style.position = el.getAttribute('data-style-position');
                el.style.width = el.getAttribute('data-style-width');
                if (copy.parentNode) {
                    copy.parentNode.removeChild(copy);
                }

                el.setAttribute('data-sticky', 'released');
            };

            if (elRect.height < ctnRect.height) {
                if (startY && isNaN(startY)) {
                    var topEl = document.querySelector(startY + ':not([data-sticky-copy])');
                    if (topEl) {
                        y0 = topEl.getBoundingClientRect().bottom;
                    } else {
                        y0 = 0;
                    }
                } else {
                    y0 = Number(startY) || 0;
                }

                if (elRect.top + copyRect.top < y0) {
                    fix();
                } else if (elRect.top + copyRect.top > y0 * 2) {
                    release();
                }
            } else {
                release();
            }
        };

        window.addEventListener('scroll', el.onWindowScroll);
    }

    /**
     *
     * @param {HTMLElement} el
     */
    function destroySticky(el) {
        // rollback style attributes
        el.style.top = el.getAttribute('data-style-top');
        el.style.position = el.getAttribute('data-style-position');
        el.style.width = el.getAttribute('data-style-width');

        // remove copy and event listener
        if (el.stickyCopy.parentNode) {
            el.stickyCopy.parentNode.removeChild(el.stickyCopy);
        }
        window.removeEventListener('scroll', el.onWindowScroll);

        // remove attributes
        el.removeAttribute('data-sticky');
        el.removeAttribute('data-style-top');
        el.removeAttribute('data-style-position');
        el.removeAttribute('data-style-width');
        el.stickyCopy = undefined;
        el.onWindowScroll = undefined;
        delete el.stickyCopy;
        delete el.onWindowScroll;
    }
</script>

<script src="//hammerjs.github.io/dist/hammer.min.js" type="text/javascript"></script>
<script src="//cdn.rawgit.com/vanquyettran/slider/8893af99/slider.js" type="text/javascript"></script>
<script>
    // Init sliders
    [].forEach.call(document.querySelectorAll(".slider"), initSlider);
</script>