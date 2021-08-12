<?php //declare(strict_types=1);

namespace Guideplugin\Result;

use \Guideplugin\Guide\Facet\Facet;
use \Guideplugin\IndexBuilder\FacetDatasetController;

class ResultItemDataController
{
    public function getData(string $dataSource, $postId) /*: array*/
    {
        $dataValues = array();

        $sourceComponents = explode('_', $dataSource, 2);
        $facetType = $sourceComponents[0];
        $facetSource = $sourceComponents[1];

        $facet = new Facet(0, '', '', $facetType, $facetSource);
        $facetDatasets = (new FacetDatasetController())->getFacetValueSets($postId, $facet);

        if (!empty($facetDatasets)) {
            foreach ($facetDatasets as $facetDataset) {
                array_push($dataValues, $facetDataset->getFacetDisplayValue());
            }
        }

        $dataValues = apply_filters('guideplugin/result/template_data', $dataValues, $postId, $dataSource);
        $dataValues = apply_filters('guideplugin/result/template_data/' . $dataSource, $dataValues, $postId);

        return $dataValues;
    }
}
