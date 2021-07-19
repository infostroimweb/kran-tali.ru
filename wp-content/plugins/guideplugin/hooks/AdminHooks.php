<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class AdminHooks
{
    public function initialize()
    {
        add_filter('manage_guideplugin_guides_posts_columns', array($this, 'addGuideColumns'));
        add_filter('manage_guideplugin_guides_posts_custom_column', array($this, 'addGuideColumnContent'), 10, 2);

        add_filter('manage_guideplugin_facets_posts_columns', array($this, 'addFacetSlugColumn'));
        add_filter('manage_guideplugin_facets_posts_custom_column', array($this, 'addFacetSlugColumnContent'), 10, 2);

        add_action('admin_menu', array($this, 'editAdminMenu'), 10);

        add_action('admin_enqueue_scripts', array($this, 'enqueueTooltip'));
    }

    public function enqueueTooltip($hook)
    {
        $currentScreen = get_current_screen();
        if (is_object($currentScreen) && property_exists($currentScreen, 'id') && in_array($currentScreen->id, \GuidepluginHelper::getGuidepluginPostTypes())) {
            wp_enqueue_script('guideplugin-popper', GUIDEPLUGIN_URL.'dist/js/popper.min.js', array('jquery'), GUIDEPLUGIN_VERSION, true);
            wp_enqueue_script('guideplugin-tippy', GUIDEPLUGIN_URL.'dist/js/tippy.min.js', array('jquery', 'guideplugin-popper'), GUIDEPLUGIN_VERSION, true);
            wp_enqueue_script('guideplugin-admin-tooltip', GUIDEPLUGIN_URL.'assets/guideplugin/js/admin-tooltip.js', array('guideplugin-tippy'), GUIDEPLUGIN_VERSION, true);
            
            wp_enqueue_style('guideplugin-tippy', GUIDEPLUGIN_URL.'dist/css/tippy.min.css');
            wp_enqueue_style('guideplugin-admin-tooltip', GUIDEPLUGIN_URL.'assets/guideplugin/css/admin-tooltip.css');
        }
    }

    public function addGuideColumns($columns)
    {
        $guideColumns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'shortcode' => __('Shortcode', 'guideplugin'),
            'ID' => __('Guide ID', 'guideplugin'),
            'date' => $columns['date'],
        );

        return $guideColumns;
    }

    public function addGuideColumnContent($column, $post_id)
    {
        switch ($column) {
            case 'ID':
                echo $post_id;
                break;

            case 'shortcode':
                echo '[guideplugin_guide id=' . $post_id . ']';
                break;
        }
    }

    public function addFacetSlugColumn($columns)
    {
        $guideColumns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'slug' => __('Facet Slug', 'guideplugin'),
            'source' => __('Facet Source', 'guideplugin'),
        );

        return $guideColumns;
    }

    public function addFacetSlugColumnContent($column, $post_id)
    {
        switch ($column) {
            case 'slug':
                echo get_field('guideplugin_slug', $post_id);
                break;

            case 'source':
                $field = get_field_object('guideplugin_source', $post_id);
                if ($field) {
                    array_walk_recursive($field['choices'], function($value, $key) use($field) {
                        if ($key === $field['value']) {
                            echo $value;
                        }
                    });
                }
                break;
        }
    }

    public function editAdminMenu()
    {
        global $menu;

        foreach ((array) $menu as $key => $menuItem) {
            if (isset($menuItem[2]) && $menuItem[2] === 'edit.php?post_type=guideplugin_guides') {
                $menu[$key][0] = 'GuidePlugin';
            }
        }

        global $submenu;

        $submenuItems = $submenu['edit.php?post_type=guideplugin_guides'];

        $createKey = $this->getSubmenuKey($submenuItems, 'post-new.php?post_type=guideplugin_guides');

        $guideKey = $this->getSubmenuKey($submenuItems, 'edit.php?post_type=guideplugin_guides');
        $guideItem = $submenuItems[$guideKey];

        $facetKey = $this->getSubmenuKey($submenuItems, 'edit.php?post_type=guideplugin_facets');
        $facetItem = $submenuItems[$facetKey];

        $filterKey = $this->getSubmenuKey($submenuItems, 'edit.php?post_type=guideplugin_filters');
        $filterItem = $submenuItems[$filterKey];

        unset($submenuItems[$createKey]);
        unset($submenuItems[$guideKey]);
        unset($submenuItems[$facetKey]);
        unset($submenuItems[$filterKey]);

        $submenuItems[0] = $facetItem;
        $submenuItems[1] = $filterItem;
        $submenuItems[2] = $guideItem;

        ksort($submenuItems);

        $submenu['edit.php?post_type=guideplugin_guides'] = $submenuItems;
    }

    private function getSubmenuKey($submenuItems, $needle)
    {
        foreach ($submenuItems as $submenuKey => $submenuItem) {
            if ($submenuItem[2] === $needle) {
                return $submenuKey;
            }
        }
        return;
    }
}
