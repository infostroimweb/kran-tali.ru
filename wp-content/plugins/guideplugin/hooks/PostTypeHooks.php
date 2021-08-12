<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class PostTypeHooks
{
    public function initialize()
    {
        add_action('init', array($this, 'addGuides'));
        add_action('init', array($this, 'addFacets'));
        add_action('init', array($this, 'addFilters'));
        add_action('init', array($this, 'addLogics'));
        add_action('init', array($this, 'addDesigns'));
        add_action('init', array($this, 'addTemplates'));
    }

    public function addTemplates()
    {
        $labels = array(
            'name' => __('Guide Templates', 'guideplugin'),
            'singular_name' => __('Guide Template', 'guideplugin'),
            'all_items' => __('Templates', 'guideplugin'),
            'edit_item' => __('Edit Template', 'guideplugin'),
        );

        $args = array(
            'label' => __('Guide Templates', 'guideplugin'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => 'edit.php?post_type=guideplugin_guides',
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'guideplugin_template', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
        );

        register_post_type('guideplugin_template', $args);
    }

    public function addDesigns()
    {
        $labels = array(
            'name' => __('Guide Designs', 'guideplugin'),
            'singular_name' => __('Guide Design', 'guideplugin'),
            'all_items' => __('Designs', 'guideplugin'),
            'edit_item' => __('Edit Design', 'guideplugin'),
        );

        $args = array(
            'label' => __('Guide Designs', 'guideplugin'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => false,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => 'edit.php?post_type=guideplugin_guides',
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'guideplugin_designs', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
        );

        register_post_type('guideplugin_designs', $args);
    }

    public function addLogics()
    {
        $labels = array(
            'name' => __('Guide Logics', 'guideplugin'),
            'singular_name' => __('Guide Logic', 'guideplugin'),
            'all_items' => __('Logic', 'guideplugin'),
            'edit_item' => __('Edit Logic', 'guideplugin')
        );

        $args = array(
            'label' => __('Guide Logics', 'guideplugin'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => 'edit.php?post_type=guideplugin_guides',
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'guideplugin_logics', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
        );

        register_post_type('guideplugin_logics', $args);
    }

    public function addFilters()
    {
        $labels = array(
            'name' => __('Guide Filters', 'guideplugin'),
            'singular_name' => __('Guide Filters', 'guideplugin'),
            'all_items' => __('Filters', 'guideplugin'),
            'edit_item' => __('Edit Filter', 'guideplugin'),
        );

        $args = array(
            'label' => __('Guide Filters', 'guideplugin'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => 'edit.php?post_type=guideplugin_guides',
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'guideplugin_filters', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title'),
        );

        register_post_type('guideplugin_filters', $args);
    }

    public function addFacets()
    {

        $labels = array(
            'name' => __('Guide Facets', 'guideplugin'),
            'singular_name' => __('Guide Facets', 'guideplugin'),
            'all_items' => __('Facets', 'guideplugin'),
            'edit_item' => __('Edit Facet', 'guideplugin'),
        );

        $args = array(
            'label' => __('Guide Facets', 'guideplugin'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => 'edit.php?post_type=guideplugin_guides',
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'guideplugin_facets', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title'),
        );

        register_post_type('guideplugin_facets', $args);
    }

    public function addGuides()
    {

        $labels = array(
            'name' => __('Guides', 'guideplugin'),
            'singular_name' => __('Guide', 'guideplugin'),
            'all_items' => __('Guides', 'guideplugin'),
            'edit_item' => __('Edit Guide', 'guideplugin'),
        );

        $args = array(
            'label' => __('Guides', 'guideplugin'),
            'labels' => $labels,
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'guideplugin_guides', 'with_front' => true),
            'query_var' => true,
            'menu_icon' => 'dashicons-filter',
            'supports' => array('title'),
        );

        register_post_type('guideplugin_guides', $args);
    }
}
