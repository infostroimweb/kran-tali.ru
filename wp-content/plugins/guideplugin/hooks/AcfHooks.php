<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class AcfHooks
{
    public function initialize()
    {
        $this->addOptionsPage();
        add_filter('acf/load_field/name=card_unique_identifier', array($this, 'setCardFieldReadOnly'));
        add_filter('acf/update_value/name=card_unique_identifier', array($this, 'setCardUniqueIdentifier'), 10, 3);
        add_filter('acf/load_field/name=filter_unique_identifier', array($this, 'setFilterFieldReadOnly'));
        add_filter('acf/update_value/name=filter_unique_identifier', array($this, 'setFilterUniqueIdentifier'), 10, 3);
        add_filter('acf/load_field/key=field_5ec19569bbeab', array($this, 'setGuidePostTypes')); // exclude post types
        add_filter('acf/load_field/key=field_5e8b35f89b767', array($this, 'setGuideTaxonomies')); // exclude taxonomies
        add_action('acf/input/admin_head', array($this, 'hideFilterIdField'));
    }

    public function setGuidePostTypes($field)
    {
        if (\GuidepluginHelper::skip_load_field()) {
            return $field;
        }

        $availablePostTypes = array();
        $postTypes = get_post_types(array(), 'objects');

        $excludedPostTypes = \GuidepluginHelper::getGuidepluginPostTypes();

        if (!empty($postTypes)) {
            foreach ($postTypes as $postType) {
                if (!in_array($postType->name, $excludedPostTypes)) {
                    $availablePostTypes[$postType->name] = $postType->label;
                }
            }
        }

        asort($availablePostTypes);

        $field['choices'] = $availablePostTypes;
        return $field;
    }

    public function setGuideTaxonomies($field)
    {
        if (\GuidepluginHelper::skip_load_field()) {
            return $field;
        }

        $availableTaxonomies = array();
        $taxonomies = get_taxonomies(array(), 'objects');

        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $availableTaxonomies = array_merge($availableTaxonomies, $this->getTaxonomyHierarchy($taxonomy));
            }
        }

        $field['choices'] = $availableTaxonomies;
        return $field;
    }

    private function getTaxonomyHierarchy($taxonomy)
    {
        $choices = array();
        $terms = get_terms(array(
            'taxonomy' => $taxonomy->name,
            'hide_empty' => false,
        ));

        if (!empty($terms)) {
            foreach ($terms as $term) {
                $termPath = trim($taxonomy->label . ' &rarr; ' . get_term_parents_list($term->term_id, $taxonomy->name, array('separator' => ' &rarr; ', 'link' => 'false')), ' &rarr; ');
                $choices['term_id_'.$term->term_taxonomy_id] = $termPath;
            }
        }

        return $choices;
    }

    public function hideFilterIdField()
    {
        ?>
        <style type="text/css">
            .acf-field.acf-field-5e2d87a973d48 {
                display: none;
            }
            .acf-field.acf-field-5dfb921df4c20 .image-wrap {
                max-width: 70px!important;
            }
            .post-type-guideplugin_guides .acf-field .acf-required,
            .post-type-guideplugin_facets .acf-field .acf-required,
            .post-type-guideplugin_designs .acf-field .acf-required,
            .post-type-guideplugin_templates .acf-field .acf-required,
            .post-type-guideplugin_filters .acf-field .acf-required,
            .post-type-guideplugin_logic .acf-field .acf-required {
                color: #8834fd;
            }
        </style>
        <?php
}

    private function addOptionsPage()
    {
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page(array(
                'page_title' => __('GuidePlugin Settings', 'guideplugin'),
                'menu_title' => __('GuidePlugin', 'guideplugin'),
                'parent_slug' => 'options-general.php',
            ));
        }
    }

    public function setCardFieldReadOnly($field)
    {
        $field['readonly'] = 1;
        return $field;
    }

    public function setCardUniqueIdentifier($value, $post_id, $field)
    {
        if ($value == '') {
            $value = 'card_' . bin2hex(random_bytes(6));
        }
        return $value;
    }

    public function setFilterFieldReadOnly($field)
    {
        $field['readonly'] = 1;
        return $field;
    }

    public function setFilterUniqueIdentifier($value, $post_id, $field)
    {
        if ($value == '') {
            $value = 'filter_' . bin2hex(random_bytes(6));
        }
        return $value;
    }
}
