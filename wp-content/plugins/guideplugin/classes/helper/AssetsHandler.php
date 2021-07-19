<?php //declare(strict_types=1);

namespace Guideplugin\Helper;

use Guideplugin\Helper\MultilanguageHelper;

class AssetsHandler
{

    public function enqueueGuideAssets()
    {
        if (!get_field('guideplugin_font_awesome_exclude', 'option')) {
            wp_enqueue_style('guideplugin-font-awesome');
        }
        wp_enqueue_style('guideplugin-ion-slider');
        wp_enqueue_style('guideplugin-slick-carousel');
        wp_enqueue_style('guideplugin-modal');
        wp_enqueue_style('guideplugin');

        wp_localize_script('guideplugin', 'ajax_object', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('guideplugin-security'),
            'lang' => (new MultilanguageHelper)->getLanguageCode()
        ));
        wp_enqueue_script('guideplugin-ion-slider');
        wp_enqueue_script('guideplugin-slick-carousel');
        wp_enqueue_script('guideplugin-canvas-confetti');
        wp_enqueue_script('guideplugin-modal');
        wp_enqueue_script('guideplugin');

    }

}
