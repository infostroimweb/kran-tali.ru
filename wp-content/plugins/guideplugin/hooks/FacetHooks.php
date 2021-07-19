<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class FacetHooks
{
    public function initialize()
    {
        add_filter('acf/load_field/key=field_5dbca31095024', array($this, 'getFacetSources')); // Facet field
        add_filter('acf/load_field/key=field_5e99950d7a2e8', array($this, 'getFacetSources')); // Template data source
    }

    public function getFacetSources($field)
    {
        if (\GuidepluginHelper::skip_load_field()) {
            return $field;
        }

        $facetSources = array();
        $facetSources['Post Data'] = $this->getPostData();
        $facetSources['Taxonomies'] = $this->getTaxonomies();
        $facetSources['Custom Fields'] = $this->getCustomFields();

        if (class_exists('ACF')) {
            $facetSources['Advanced Custom Fields'] = $this->getACFFields();
        }

        if (class_exists('WooCommerce')) {
            $facetSources['WooCommerce'] = $this->getWoocommerceFields();
        }

        $field['choices'] = $facetSources;

        return $field;
    }

    private function getPostData()
    {
        $postData = array(
            'post_id' => '[Post] Post ID',
            'post_title' => '[Post] Post Title',
            'post_type' => '[Post] Post Type',
            'post_date' => '[Post] Post Date',
            'post_modified' => '[Post] Post Modified',
            'post_author' => '[Post] Post Author',
        );

        return $postData;
    }

    private function getTaxonomies()
    {
        $taxonomies = get_taxonomies(array(), 'objects');
        $taxonomiesData = array();
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $taxonomiesData['taxonomy_' . $taxonomy->name] = '[Taxonomy] ' . $taxonomy->labels->name;
            }
        }

        return $taxonomiesData;
    }

    private function getCustomFields()
    {
        $customFields = get_post_custom_keys();
        $customFieldsData = array();

        if (!empty($customFields)) {
            asort($customFields);
            foreach ($customFields as $customField) {
                $customFieldsData['custom_field_' . $customField] = '[Custom Field] ' . $customField;
            }
        }

        return $customFieldsData;
    }

    private function getWoocommerceFields()
    {
        $wooCommerceData = array(
            'woocommerce_price' => '[WooCommerce] Price',
            'woocommerce_sale_price' => '[WooCommerce] Sale Price',
            'woocommerce_regular_price' => '[WooCommerce] Regular Price',
            'woocommerce_rating' => '[WooCommerce] Rating',
            'woocommerce_stock_status' => '[WooCommerce] Stock Status',
            'woocommerce_on_sale' => '[WooCommerce] On Sale',
        );

        return $wooCommerceData;
    }

    private function getACFFields()
    {
        $acfFields = array();
        $excludedGroups = array(
            'group_5dbc5da7e067b', // facet
            'group_5dc9466f08606', // filter
            'group_5e996f1908565', // gutenberg blocks
            'group_5dfb8ace86651', // guide
            'group_5e2193ef05ca5', // settings
            'group_5e29f139d8cbc', // logic - rules
            'group_5e88b630a8f12', // design
            'group_5e8b2e13af264', // design - post
            'group_5e9862d14f82b', // templates
            'group_5e99950d5b69a', // templates - fields
        );

        $acfGroups = acf_get_field_groups();

        if (!empty($acfGroups)) {
            foreach ($acfGroups as $acfGroup) {
                if (!in_array($acfGroup['key'], $excludedGroups)) {
                    $groupFields = acf_get_fields($acfGroup['key']);
                    $acfFields = array_merge($acfFields, $this->getAcfGroupFields($groupFields, array($acfGroup['title'])));
                }
            }
        }

        wp_reset_query();

        return $acfFields;
    }

    private function getAcfGroupFields($groupFields, $path = array()) //: array
    {
        $fields = array();

        if (!empty($groupFields)) {
            foreach ($groupFields as $groupField) {
                $fields = array_merge($fields, $this->getAcfGroupField($groupField, $path));
            }
        }

        return $fields;
    }

    private function getAcfGroupField($groupField, $path = array()) //: array
    {
        switch ($groupField['type']) {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'url':
            case 'select':
            case 'checkbox':
            case 'radio':
            case 'date_picker':
            case 'date_time_picker':
            case 'time_picker':
            case 'color_picker':
            case 'post_object':
            case 'relationship':
            case 'taxonomy':
            case 'true_false':
                return $this->getAcfGroupDefaultField($groupField, $path);
                break;

            case 'repeater':
                return $this->getAcfGroupRepreaterField($groupField, $path);
                break;

            /*case 'flexible_content':
                return $this->getAcfGroupFlexibleContentField($groupField, $path);
                break;*/

            default:
                # code...
                break;
        }
        return array();
    }

    private function getAcfGroupDefaultField($groupField, $path)
    {
        return array('acf_' . $groupField['key'] => '[ACF] [' . implode('] [', $path) . '] ' . $groupField['label']);
    }

    private function getAcfGroupRepreaterField($groupField, $path)
    {
        array_push($path, $groupField['label']);
        return $this->getAcfGroupFields($groupField['sub_fields'], $path);
    }

    private function getAcfGroupFlexibleContentField($groupField, $path)
    {
        array_push($path, $groupField['label']);
        $fields = array();
        if (!empty($groupField['layouts'])) {
            foreach ($groupField['layouts'] as $layout) {
                $fields = array_merge($fields, $this->getAcfGroupFields($layout['sub_fields'], $path));
            }
        }
        return $fields;
    }
}
