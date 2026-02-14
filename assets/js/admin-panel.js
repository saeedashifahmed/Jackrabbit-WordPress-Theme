/**
 * Jackrabbit Admin Panel JS
 *
 * Handles tab switching, color picker initialization, and media upload.
 *
 * @package Jackrabbit
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        /* ─── Tab Switching ─── */
        var $tabs = $('.jackrabbit-tab');
        var $panels = $('.jackrabbit-tab-content');

        $tabs.on('click', function () {
            var target = $(this).data('tab');

            $tabs.removeClass('active').attr('aria-selected', 'false');
            $(this).addClass('active').attr('aria-selected', 'true');

            $panels.removeClass('active');
            $('#tab-' + target).addClass('active');

            // Persist active tab in sessionStorage
            if (window.sessionStorage) {
                sessionStorage.setItem('jk_active_tab', target);
            }
        });

        // Restore active tab from session
        if (window.sessionStorage) {
            var savedTab = sessionStorage.getItem('jk_active_tab');
            if (savedTab) {
                $tabs.filter('[data-tab="' + savedTab + '"]').trigger('click');
            }
        }

        /* ─── Color Picker ─── */
        if ($.fn.wpColorPicker) {
            $('.jk-color-picker').wpColorPicker();
        }

        /* ─── Media Upload ─── */
        $('.jk-upload-btn').on('click', function (e) {
            e.preventDefault();
            var $input = $(this).siblings('input');

            var mediaFrame = wp.media({
                title: 'Select or Upload Image',
                button: { text: 'Use This Image' },
                multiple: false,
            });

            mediaFrame.on('select', function () {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                $input.val(attachment.url).trigger('change');
            });

            mediaFrame.open();
        });

        /* ─── Character Countdown for Meta Description ─── */
        var $metaDesc = $('#jk-meta-desc');
        if ($metaDesc.length) {
            var $counter = $('<span class="jk-char-count" style="font-size:12px;color:#9ca3af;"></span>');
            $metaDesc.after($counter);

            function updateCounter() {
                var len = $metaDesc.val().length;
                var maxLen = 160;
                $counter.text(len + '/' + maxLen + ' characters');
                $counter.css('color', len > maxLen ? '#ef4444' : '#9ca3af');
            }

            $metaDesc.on('input', updateCounter);
            updateCounter();
        }

    });

})(jQuery);
