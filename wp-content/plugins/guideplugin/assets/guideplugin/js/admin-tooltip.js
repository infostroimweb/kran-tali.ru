(function($) {
    'use strict';

    $(document).ready(function() {
        $('.acf-label p').each(function(i) {
            let $description = $(this);
            let $label = $description.parent().find('label');
            let tooltipContent = $description.text();
            $label.append('<i class="dashicons dashicons-editor-help" style="font-size: 1.1rem; margin-left: 3px; color: #8300E9;" data-guideplugin-tippy-content="' + nl2br(escapeHtml(tooltipContent)) + '"></i>');
            $description.hide();

        });
        tippy('.acf-label label i', {
            animation: 'scale',
            placement: 'auto',
            allowHTML: true,
            maxWidth: 300,
            content(reference) {
                return reference.getAttribute('data-guideplugin-tippy-content');
            }
        });
    });



    let escapeHtml = function(unsafe) {
        return unsafe
            /*.replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")*/
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    let nl2br = function(str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }



}(jQuery));