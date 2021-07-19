(function($) {
    'use strict';

    $(document).ready(function() {
        $('guideplugin-spinner').css('visibility', 'visible');
    });


    $(document).on('click', '.guideplugin-admin .guide-button', function() {
        $('.guideplugin-admin .guide-button').hide();
        $('.guideplugin-admin .guideplugin-spinner').css('display', 'inline-block');
        
        switch ($(this).attr('data-action')) {
            case 'purge':
                submitIndexAction('guideplugin_guide_purge_index');
                break;
            case 'rebuild':
                submitIndexAction('guideplugin_guide_rebuild_index');
                break;
            default:
                // statements_def
                break;
        }
    });
    let ajaxRequest = null;
    let submitIndexAction = function(action) {
        if (ajaxRequest != null) {
            ajaxRequest.abort();
        }
        ajaxRequest = $.ajax({
            type: "post",
            dataType: "json",
            url: ajax_object.url,
            data: {
                action: action,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                location.reload();
            }
        });
    }
}(jQuery));