<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class Enqueues
{
    public function initialize()
    {
        add_action('wp_enqueue_scripts', array($this, 'registerAssets'), 100);
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminAssets'), 100);
    }

    public function registerAssets()
    {

        /**
         * Styles
         */
        wp_register_style('guideplugin-font-awesome', GUIDEPLUGIN_URL . 'dist/css/font-awesome.min.css', false, GUIDEPLUGIN_VERSION);
        wp_register_style('guideplugin-ion-slider', GUIDEPLUGIN_URL . 'dist/css/ion-slider.min.css', false, GUIDEPLUGIN_VERSION);
        wp_register_style('guideplugin-slick-carousel', GUIDEPLUGIN_URL . 'dist/css/slick-carousel.min.css', false, GUIDEPLUGIN_VERSION);
        wp_register_style('guideplugin-modal', GUIDEPLUGIN_URL . 'assets/guideplugin-modal/css/guideplugin-modal.css', false, GUIDEPLUGIN_VERSION);
        wp_register_style('guideplugin', GUIDEPLUGIN_URL . 'assets/guideplugin/css/guideplugin.css', false, GUIDEPLUGIN_VERSION);

        /**
         * JS Scripts
         */
        wp_register_script('guideplugin-ion-slider', GUIDEPLUGIN_URL . 'dist/js/ion-slider.min.js', array('jquery'), GUIDEPLUGIN_VERSION, true);
        wp_register_script('guideplugin-slick-carousel', GUIDEPLUGIN_URL . 'dist/js/slick-carousel.min.js', array('jquery'), GUIDEPLUGIN_VERSION, true);
        wp_register_script('guideplugin-canvas-confetti', GUIDEPLUGIN_URL . 'dist/js/canvas-confetti.min.js', array('jquery'), GUIDEPLUGIN_VERSION, true);
        wp_register_script('guideplugin-modal', GUIDEPLUGIN_URL . 'assets/guideplugin-modal/js/guideplugin-modal.js', array('jquery'), GUIDEPLUGIN_VERSION, true);
        wp_register_script('guideplugin', GUIDEPLUGIN_URL . 'assets/guideplugin/js/guideplugin.js', array('jquery', 'guideplugin-ion-slider', 'guideplugin-slick-carousel', 'guideplugin-canvas-confetti', 'guideplugin-modal'), GUIDEPLUGIN_VERSION, true);
    }

    public function enqueueAdminAssets()
    {

        /**
         * Styles
         */
        wp_register_style('guideplugin-admin', GUIDEPLUGIN_URL . '/assets/guideplugin/css/admin.css', false, GUIDEPLUGIN_VERSION, false);
        wp_enqueue_style('guideplugin-admin');

        wp_register_style('guidefont', GUIDEPLUGIN_URL . '/dist/css/guidefont.min.css', false, GUIDEPLUGIN_VERSION, false);
        wp_enqueue_style('guidefont');

        /**
         * JS Scripts
         */
        wp_register_script('guideplugin-admin', GUIDEPLUGIN_URL . 'assets/guideplugin/js/admin.js', array('jquery'), GUIDEPLUGIN_VERSION, true);
        wp_enqueue_script('guideplugin-admin');
        wp_localize_script('guideplugin-admin', 'ajax_object', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('guideplugin-security'),
        ));
    }
}
