<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class GutenbergHooks
{
    public function initialize()
    {
        add_action('acf/init', array($this, 'registerGuideBlock'));

        if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {
            add_filter('use_block_editor_for_post_type', array($this, 'disableGutenberg'), 10, 2);
        } else {
            add_filter('gutenberg_can_edit_post_type', array($this, 'disableGutenberg'), 10, 2);
        }
    }

    public function disableGutenberg($isEnabled, $postType)
    {
        $guidepluginPostTypes = \GuidepluginHelper::getGuidepluginPostTypes();
        if (in_array($postType, $guidepluginPostTypes)) {
            return false;
        }
        return $isEnabled;
    }

    public function registerGuideBlock()
    {
        if (function_exists('acf_register_block_type')) {
            acf_register_block_type(array(
                'name' => 'guideplugin_guide',
                'title' => __('Guide'),
                'description' => __('Add a guide from guideplugin.'),
                'render_callback' => array($this, 'renderGuideBlock'),
                'category' => 'embed',
                'icon' => 'filter',
                'keywords' => array('guideplugin', 'guide'),
                'mode' => 'edit',
                'supports' => array('mode' => false),
                'enqueue_assets' => array('AssetsHandler', 'enqueueGuideAssets'),
            ));
        }
    }
}
