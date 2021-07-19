<?php //declare(strict_types=1);

namespace Guideplugin\Result\PostSetBuilder;

use Guideplugin\Guide\FilterSelection\SliderSelection\SliderCondition;
use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Result\PostSet\PostSetController;

class SliderPostSetBuilder extends PostSetBuilder
{

    public function buildPostSet(Filter $filter) /*: string*/
    {
        $postSets = array();
        $conditions = $filter->getSelection()->getConditions();
        $values = $filter->getValues();
        $type = $filter->getSelection()->getType();

        if (empty($values)) {
            return '';
        }

        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $query = $this->getSliderQuery($condition, $type, $values);
                array_push($postSets, $this->getQueryResult($query));
            }
        }

        switch ($filter->getSelection()->getBehavior()) {
            case 'widen':
                return (new PostSetController())->mergePostSets($postSets, 'OR');
                break;

            case 'narrow':
                return (new PostSetController())->mergePostSets($postSets, 'AND');
                break;

            default:
                # code...
                break;
        }

        return [];
    }

    private function getSliderQuery(SliderCondition $condition, string $type, array $values) /*: string*/
    {
        switch ($type) {
            case 'single':
                return $this->getSingleSliderQuery($condition, $values);
                break;

            case 'range':
                return $this->getRangeSliderQuery($condition, $values);
                break;

            default:
                # code...
                break;
        }

        return;
    }

    private function getSingleSliderQuery(SliderCondition $condition, array $values) /*: string*/
    {
        global $wpdb;
        $logicalOperator = '';
        switch ($condition->getRule()) {
            case 'slider_single_less_than':
                $logicalOperator = '<';
                break;

            case 'slider_single_greater_than':
                $logicalOperator = '>';
                break;

            default:
                return '';
                break;
        }

        $query = $wpdb->prepare('(facet_name = \'' . $condition->getFacet()->getSlug() . '\' AND facet_value ' . $logicalOperator . ' CONVERT(%d, SIGNED))', $values[0]);

        return $query;
    }

    private function getRangeSliderQuery(SliderCondition $condition, array $values) /*: string*/
    {
        global $wpdb;
        $logicalOperator = '';
        switch ($condition->getRule()) {
            case 'slider_range_between':
                $logicalOperator = 'BETWEEN';
                break;

            case 'slider_range_not_between':
                $logicalOperator = 'NOT BETWEEN';
                break;

            default:
                return '';
                break;
        }

        $query = $wpdb->prepare('(facet_name = \'' . $condition->getFacet()->getSlug() . '\' AND facet_value ' . $logicalOperator . ' CONVERT(%s, SIGNED) AND CONVERT(%s, SIGNED))', $values);

        return $query;
    }

}
