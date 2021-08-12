<?php //declare(strict_types=1);

namespace Guideplugin\IndexBuilder;

use Guideplugin\Guide\Facet\Facet;
use Guideplugin\IndexBuilder\AcfValueSetsController;
use Guideplugin\IndexBuilder\FacetDataset;
use Guideplugin\IndexBuilder\FacetValueSet;

class FacetDatasetController
{

    public function buildFacetDatasets(int $postId, Facet $facet) /*: array*/
    {
        $facetDatasets = array();
        $facetSlug = $facet->getSlug();
        $facetValueSets = $this->getFacetValueSets($postId, $facet);

        if (!empty($facetValueSets)) {
            foreach ($facetValueSets as $facetValueSet) {
                $dataset = apply_filters('guideplugin/index/facet/dataset', array(
                    'post_id' => $postId,
                    'facet_slug' => $facetSlug,
                    'facet_value' => $facetValueSet->getFacetValue(),
                    'facet_display_value' => $facetValueSet->getFacetDisplayValue()
                ));
                array_push($facetDatasets, new FacetDataset($dataset['post_id'], $dataset['facet_slug'], $dataset['facet_value'], $dataset['facet_display_value']));
            }
        }

        return $facetDatasets;
    }

    public function getFacetValueSets(int $postId, Facet $facet) /*: array*/
    {
        $facetValueSets = array();
        $facetType = $facet->getType();
        $facetSource = $facet->getSource();

        switch ($facetType) {
            case 'post':
                return $this->getPostValueSets($facetSource, $postId);
                break;

            case 'acf':
                return $this->getAcfValueSets($facetSource, $postId);
                break;

            case 'taxonomy':
                return $this->getTaxonomyValueSets($facetSource, $postId);
                break;

            case 'custom_field':
                return $this->getCustomFieldValueSets($facetSource, $postId);
                break;

            case 'woocommerce':
                return $this->getWoocommerceValueSets($facetSource, $postId);
                break;

            default:
                # code...
                break;
        }

        return $facetValueSets;
    }

    private function getPostValueSets(string $facetSource, int $postId) /*: FacetValueSet*/
    {
        $facetValue = '';
        $facetDisplayValue = '';
        switch ($facetSource) {
            case 'id':
                $facetValue = $postId;
                $facetDisplayValue = $postId;
                break;

            case 'title':
                $facetValue = get_the_title($postId);
                $facetDisplayValue = get_the_title($postId);
                break;

            case 'type':
                $facetValue = get_post_type($postId);
                $facetDisplayValue = get_post_type($postId);
                break;

            case 'date':
                $facetValue = get_the_date('Y-m-d', $postId);
                $facetDisplayValue = get_the_date(get_option('date_format'), $postId);
                break;

            case 'modified':
                $facetValue = get_the_modified_date('Y-m-d', $postId);
                $facetDisplayValue = get_the_modified_date(get_option('date_format'), $postId);
                break;

            case 'author':
                $facetValue = get_the_author_meta('display_name', get_post_field('post_author', $postId));
                $facetDisplayValue = get_the_author_meta('display_name', get_post_field('post_author', $postId));
                break;

            default:
                # code...
                break;
        }

        return array((new FacetValueSet(strval($facetValue), strval($facetDisplayValue))));
    }

    private function getAcfValueSets(string $facetSource, int $postId) /*: array*/
    {
        $fieldObject = get_field_object($facetSource, $postId);

        if (is_array($fieldObject)) {
            return (new AcfValueSetsController())->getAcfValueSets($fieldObject, $postId);
        }

        return array();
    }

    private function getTaxonomyValueSets(string $facetSource, int $postId) /*: array*/
    {
        $valueSets = array();

        $terms = wp_get_post_terms($postId, $facetSource);
        if (!empty($terms)) {
            foreach ($terms as $term) {
                array_push($valueSets, new FacetValueSet(strval($term->term_taxonomy_id), strval($term->name)));
            }
        }

        return $valueSets;
    }

    private function getCustomFieldValueSets(string $facetSource, int $postId) /*: array*/
    {
        $valueSets = array();

        $postMeta = get_post_meta($postId, $facetSource);
        if (!empty($postMeta)) {
            foreach ($postMeta as $postMetaItem) {
                array_push($valueSets, new FacetValueSet(strval($postMetaItem), strval($facetSource)));
            }
        }

        return $valueSets;
    }

    private function getWoocommerceValueSets(string $facetSource, int $postId) /*: array*/
    {
        $valueSets = array();
        $product = wc_get_product($postId);

        if ($product instanceof \WC_Product) {
            $facetValue = '';
            $facetDisplayValue = '';
            switch ($facetSource) {
                case 'price':
                    $facetValue = $product->get_price();
                    $facetDisplayValue = $product->get_price();
                    break;

                case 'sale_price':
                    $facetValue = $product->get_sale_price();
                    $facetDisplayValue = $product->get_sale_price();
                    break;

                case 'regular_price':
                    $facetValue = $product->get_regular_price();
                    $facetDisplayValue = $product->get_regular_price();
                    break;

                case 'rating':
                    $facetValue = $product->get_average_rating();
                    $facetDisplayValue = $product->get_average_rating();
                    break;

                case 'stock_status':
                    $facetValue = $product->get_stock_status();
                    $facetDisplayValue = $product->get_stock_status();
                    break;

                case 'on_sale':
                    $facetValue = $product->is_on_sale();
                    $facetDisplayValue = $product->is_on_sale();
                    break;

                default:
                    # code...
                    break;
            }
            return array((new FacetValueSet(strval($facetValue), strval($facetDisplayValue))));
        }

        return array();

    }

}
