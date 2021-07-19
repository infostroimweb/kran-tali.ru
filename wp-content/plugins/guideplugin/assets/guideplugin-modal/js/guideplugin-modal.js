(function($) {

    'use strict';

    $(document).ready(function() {
        $('.guideplugin-modal').removeAttr('style');
    });


    $(document).on('click', '[data-guideplugin-modal-open]', function(e) {
        e.preventDefault();
        if ($($(this).attr('href')).length > 0) {
            $($(this).attr('href')).guideplugin_modal('open');
        }
    });


    $(document).on('click', '.guideplugin-modal:not(.guideplugin-modal-not-dismissable)', function() {
        $('body').guideplugin_modal('close');
    });

    $(document).on('click', '.guideplugin-modal', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '[data-guideplugin-modal-close]', function() {
        $('body').guideplugin_modal('close');
    });


    $.fn.guideplugin_modal = function(action) {

        let openModal = function($modal) {
            let classes = ['guideplugin-modal-show'];
            if ($modal.attr('data-dismissable') == 'false') {
                classes.push('guideplugin-modal-not-dismissable');
            }
            if ($modal.length > 0) {
                $modal.addClass(classes.join(' '));
                $('body').addClass('body-guideplugin-modal-open');
            }
        }


        let closeModal = function() {
            $('.guideplugin-modal').removeClass('guideplugin-modal-show');
            $('body').removeClass('body-guideplugin-modal-open');
        }

        switch (action) {
            case 'open':
                openModal($(this));
                break;
            case 'close':
                closeModal();
                break;
            default:
                // statements_def
                break;
        }
    }

}(jQuery));