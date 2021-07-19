<?php //declare(strict_types=1);

use \Guideplugin\Guide\Filter\Filter;
use \Guideplugin\Guide\Filter\FilterController;
use \Guideplugin\Guide\Guide\Guide;
use \Guideplugin\Guide\Guide\GuideController;
use \Guideplugin\Helper\Template;
use \Guideplugin\IndexBuilder\IndexBuilder;
use \Guideplugin\Result\ResultItemDataController;

class GuidepluginHelper
{
    public static function template(string $path)
    {
        return Template::getTemplatePath($path);
    }

    public static function template_guide($guide)
    {
        if ($guide instanceof Guide) {
            return (new GuideController())->getGuideTemplate($guide);
        }
        return;
    }

    public static function get_data(string $dataSource, int $postId)
    {
        return (new ResultItemDataController())->getData($dataSource, $postId);
    }

    public static function guide_progress_modal($guide)
    {
        if ($guide instanceof Guide) {
            return (new GuideController())->getGuideProgressModalTemplate($guide);
        }
        return array();
    }

    public static function guide_input_required_modal($guide)
    {
        if ($guide instanceof Guide) {
            return (new GuideController())->getGuideInputRequiredModalTemplate($guide);
        }
        return array();
    }

    public static function filter_help_modal($filter)
    {
        if ($filter instanceof Filter) {
            return (new FilterController())->getFilterHelpModalTemplate($filter);
        }
        return array();
    }

    public static function template_filter($filter)
    {
        if ($filter instanceof Filter) {
            return (new FilterController())->getFilterTemplate($filter);
        }
        return;
    }

    public static function template_filter_selection($filter)
    {
        if ($filter instanceof Filter) {
            return (new FilterController())->getFilterSelectionTemplate($filter);
        }
        return;
    }

    public static function template_spinner($size = '', $class = '')
    {
        ob_start();
        include Template::getTemplatePath('templates/spinner/spinner.php');
        return ob_get_clean();
    }

    public static function build_index($postId = null)
    {
        (new IndexBuilder())->buildIndex($postId);
    }

    public static function getGuidepluginPostTypes() /*: array*/
    {
        $postTypes = get_post_types();
        $guidepluginPostTypes = array('guideplugin_guides', 'guideplugin_filters', 'guideplugin_facets', 'guideplugin_designs', 'guideplugin_logics', 'guideplugin_template');
        $validGuidepluginPostTypes = array_intersect($postTypes, $guidepluginPostTypes);
        /**
         * return intersected array to be sure all Guideplugin post types are registered
         */
        return array_values($validGuidepluginPostTypes);
    }

    /**
     * Don't load values on ACF Field Group edit screen
     * @return bool Whether or not to load field options
     */
    public static function skip_load_field()
    {
        if (!function_exists('get_current_screen')) {
            return false;
        }

        $currentScreen = get_current_screen();
        if (is_object($currentScreen) && property_exists($currentScreen, 'id') && $currentScreen->id === 'acf-field-group') {
            return true;
        }

        return false;
    }
}
