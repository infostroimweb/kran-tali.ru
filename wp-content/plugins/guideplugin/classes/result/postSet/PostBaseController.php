<?php //declare(strict_types=1);

namespace Guideplugin\Result\PostSet;

use Guideplugin\Guide\Guide\Guide;
use Guideplugin\Helper\MultilanguageHelper;

class PostBaseController
{
    public function getPostBase(array $postSet = array(), Guide $guide)
    {
        /**
         * Fixed query arguments that should not be filtered
         * @var array
         */
        $arguments = array(
            'fields' => 'ids',
            'posts_per_page' => -1,
            'suppress_filters' => false,
        );
        $arguments = (new MultilanguageHelper())->addPostBaseMultilanguageSupport($arguments);
        $arguments = $this->getFilteredArguments($arguments, $postSet, $guide);

        return get_posts($arguments);
    }

    private function getFilteredArguments(array $arguments, array $postSet, Guide $guide)
    {
        /**
         * Filterable query arguments the user can filter
         * @var array
         */
        $filterableArguments = array(
            'post_type' => 'any',
        );

        $filterableArguments = $this->addPostTypeArguments($filterableArguments, $guide);
        $filterableArguments = $this->addTaxonomyArguments($filterableArguments, $guide);

        if (count($postSet) > 0) {
            $filterableArguments['post__in'] = $postSet;
        }
        $filterableArguments = apply_filters('guideplugin/result/query_args', $filterableArguments, $guide->getId());

        /**
         * Strip out all arguments the user should not be able to set
         */
        unset($filterableArguments['fields']);
        unset($filterableArguments['posts_per_page']);
        unset($filterableArguments['suppress_filters']);

        $arguments = array_merge($arguments, $filterableArguments);

        return $arguments;
    }

    private function addTaxonomyArguments(array $arguments, Guide $guide)
    {
        $option = (get_field('guideplugin_taxonomy_option', $guide->getId())) ? get_field('guideplugin_taxonomy_option', $guide->getId()) : '';
        $selection = (get_field('guideplugin_taxonomy_selection', $guide->getId())) ? get_field('guideplugin_taxonomy_selection', $guide->getId()) : array();

        $taxonomySettings = array(
            'option' => $option,
            'selection' => $this->getSelectionTermIds($selection),
        );
        apply_filters('guideplugin/guide/taxonomies', $taxonomySettings, $guide->getId());

        if (empty($taxonomySettings['option']) || empty($taxonomySettings['selection'])) {
            return $arguments;
        }

        $opterator = '';
        switch ($taxonomySettings['option']) {
            case 'include':
                $operator = 'IN';
                break;

            case 'exclude':
                $operator = 'NOT IN';
                break;

            default:
                # code...
                break;
        }

        $taxonomySets = $this->getTaxonomySets($taxonomySettings['selection']);

        $taxQuery = array('relation' => 'OR');

        foreach ((array) $taxonomySets as $taxonomyName => $taxonomyTerms) {
            array_push($taxQuery, array(
                'taxonomy' => $taxonomyName,
                'field' => 'term_taxonomy_id',
                'terms' => $taxonomyTerms,
                'operator' => $operator,
            ));
        }

        $arguments['tax_query'] = $taxQuery;

        return $arguments;
    }

    private function getTaxonomySets(array $termTaxonomyIds)
    {
        $taxonomySets = array();
        foreach ((array) $termTaxonomyIds as $termTaxonomyId) {
            $term = get_term_by('term_taxonomy_id', $termTaxonomyId);
            if (!isset($taxonomySets[$term->taxonomy])) {
                $taxonomySets[$term->taxonomy] = array();
            }
            $taxonomySets[$term->taxonomy][] = $termTaxonomyId;
        }
        return $taxonomySets;
    }

    private function getSelectionTermIds(array $selection)
    {
        foreach ((array) $selection as $key => $term) {
            $selection[$key] = str_replace('term_id_', '', $term);
        }
        return $selection;
    }

    private function addPostTypeArguments(array $arguments, Guide $guide)
    {
        $validPostTypes = $this->getValidPostTypes();
        $option = (get_field('guideplugin_post_type_option', $guide->getId())) ? get_field('guideplugin_post_type_option', $guide->getId()) : '';
        $selection = (get_field('guideplugin_post_type_selection', $guide->getId())) ? get_field('guideplugin_post_type_selection', $guide->getId()) : array();

        $postTypeSettings = array(
            'option' => $option,
            'selection' => $selection,
        );
        apply_filters('guideplugin/guide/post_types', $postTypeSettings, $guide->getId());

        if (empty($postTypeSettings['option']) || empty($postTypeSettings['selection'])) {
            return $arguments;
        }

        switch ($postTypeSettings['option']) {
            case 'include':
                $arguments['post_type'] = $postTypeSettings['selection'];
                break;

            case 'exclude':
                $arguments['post_type'] = array_diff($validPostTypes, $postTypeSettings['selection']);
                break;

            default:
                # code...
                break;
        }

        return $arguments;
    }

    private function getValidPostTypes()
    {
        $postTypes = get_post_types(); // all post types of current WP instance
        $acfPostTypes = array('acf-field', 'acf-field-group');
        $guidepluginPostTypes = \GuidepluginHelper::getGuidepluginPostTypes(); // intersected guideplugin post types
        $validPostTypes = array_diff($postTypes, $guidepluginPostTypes, $acfPostTypes);

        return $validPostTypes;
    }

}
