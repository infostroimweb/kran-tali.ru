<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use \Guideplugin\Guide\Facet\Facet;
use \Guideplugin\Guide\Facet\FacetController;

class ConditionFacetHooks
{
    public function initialize()
    {
        add_filter('acf/load_field/key=field_5ea71d529a647', array($this, 'getValues')); // Facet field
    }

    public function getValues($field)
    {
        if (\GuidepluginHelper::skip_load_field()) {
            return $field;
        }

        $choices = array();
        $facets = $this->getFacets();

        if (!empty($facets)) {
            foreach ($facets as $facet) {
                $facetChoices = $this->getFacetChoices($facet);
                if (count((array) $facetChoices)) {
                    $choices[$facet->getTitle()] = $facetChoices;
                }
            }
        }

        $field['choices'] = $choices;

        return $field;
    }

    public function getFacetChoices(Facet $facet)
    {
        $facetChoices = array();
        $facetType = $facet->getType();
        $facetSource = $facet->getSource();

        switch ($facetType) {
            case 'taxonomy':
                return $this->getTaxonomyFacetChoices($facetSource);
                break;

            case 'acf':
                return $this->getAcfFacetChoices($facetSource);
                break;

            default:
                # code...
                break;
        }

        return $facetChoices;
    }

    private function getTaxonomyFacetChoices(string $facetSource) //: array

    {
        $choices = array();
        $terms = get_terms(array(
            'taxonomy' => $facetSource,
            'hide_empty' => false,
        ));

        if (!empty($terms)) {
            foreach ($terms as $term) {
                $termPath = trim(get_term_parents_list($term->term_id, $facetSource, array('separator' => ' &rarr; ', 'link' => 'false')), ' &rarr; ');
                $choices[$term->term_taxonomy_id] = $termPath;
            }
        }

        return $choices;
    }

    private function getAcfFacetChoices(string $facetSource) //: array

    {
        $choices = array();

        $fieldObject = get_field_object($facetSource);

        if (is_array($fieldObject) && isset($fieldObject['type'])) {
            switch ($fieldObject['type']) {
                case 'true_false':
                    return $this->getAcfTrueFalse($fieldObject);
                    break;

                case 'select':
                case 'checkbox':
                case 'radio':
                    return $this->getAcfSelection($fieldObject);
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    private function getAcfTrueFalse(array $fieldObject)
    {
        return array('0' => __('False', 'guideplugin'), '1' => __('True', 'guideplugin'));
    }

    private function getAcfSelection(array $fieldObject)
    {
        return $fieldObject['choices'];
    }

    public function getFacets()
    {
        $facets = array();
        $arguments = array(
            'post_type' => 'guideplugin_facets',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'suppress_filters' => false
        );
        $facetPosts = get_posts($arguments);

        if (!empty($facetPosts)) {
            foreach ($facetPosts as $facetPost) {
                $facet = (new FacetController())->buildFacet($facetPost->ID);
                if (!is_null($facet)) {
                    array_push($facets, $facet);
                }
            }
        }

        return $facets;
    }
}
