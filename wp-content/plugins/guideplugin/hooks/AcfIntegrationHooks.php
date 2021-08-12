<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use Guideplugin\Helper\MultilanguageHelper;

class AcfIntegrationHooks
{
    public function initialize()
    {
        add_filter('acf/settings/path', array($this, 'setPath'));
        add_filter('acf/settings/dir', array($this, 'setDir'));
        add_action('acf/settings/show_admin', array($this, 'showAdmin'));
        add_filter('acf/settings/load_json', array($this, 'addLoadJsonDir'));
        add_filter('acf/settings/save_json', array($this, 'addSaveJsonDir'));
        add_action('acf/init', array($this, 'registerFieldGroups'));
    }

    public function setPath()
    {
        return GUIDEPLUGIN_PATH . 'vendor/advanced-custom-fields/advanced-custom-fields-pro/';
    }

    public function setDir()
    {
        return GUIDEPLUGIN_URL . 'vendor/advanced-custom-fields/advanced-custom-fields-pro/';
    }

    public function showAdmin()
    {
        return true;
    }

    public function addLoadJsonDir($paths)
    {
        if ($this->isDev()) {
            array_push($paths, GUIDEPLUGIN_PATH . 'acf-json');
        }
        return $paths;
    }

    public function addSaveJsonDir($paths)
    {
        if ($this->isDev()) {
            return GUIDEPLUGIN_PATH . 'acf-json';
        }
        return $paths;
    }

    public function registerFieldGroups()
    {
        if (!$this->isDev()) {
            $fieldGroupPath = GUIDEPLUGIN_PATH . 'dist/acf-php/';
            $fieldGroups = array(
                'guide.php', // fieldGroup: Guide
                'design.php', // fieldGroup: Design
                'design_post.php', // fieldGroup: Design Post
                'facet.php', // fieldGroup: Facets
                'filter.php', // fieldGroup: Filter
                'gutenberg_block.php', // fieldGroup: Gutenberg implementation
                'logic_rules.php', // fieldGroup: Logic rules
                'settings.php', // fieldGroup: Settings
                'template.php', // fieldGroup: Template
                'template_fields.php', // fieldGroup: Template Fields
            );

            foreach ($fieldGroups as $fieldGroup) {
                if (file_exists($fieldGroupPath . $fieldGroup)) {
                    $fieldGroupMap = include_once $fieldGroupPath . $fieldGroup;
                    $fieldGroupMap = (new MultilanguageHelper())->addWpmlSupport($fieldGroupMap);
                    acf_add_local_field_group($fieldGroupMap);
                }
            }
        }
    }

    private function isDev()
    {
        $httpHost = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
        if (in_array($httpHost, array('plugin.guideplugin.com'))) {
            return true;
        }
        return false;
    }
}
