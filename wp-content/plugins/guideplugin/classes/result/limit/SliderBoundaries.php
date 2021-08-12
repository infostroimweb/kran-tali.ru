<?php //declare(strict_types=1);

namespace Guideplugin\Result\Limit;

use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Guide\Guide\Guide;
use Guideplugin\Result\QueryBuilder\QueryBuilder;
use Guideplugin\Result\PostSetBuilder\SliderPostSetBuilder;
use Guideplugin\Result\PostSet\PostSetController;

class SliderBoundaries
{
    public function updateFilter(Guide $guide, Filter $filter, array $previousPostSet) /*: Filter*/
    {
        $boundaries = $this->getBoundaries($guide, $filter, $previousPostSet);

        if (empty($filter->getSelection()->getLowerLimit())) {
            $filter->getSelection()->setLowerLimit($boundaries['min']);
        }

        if (empty($filter->getSelection()->getUpperLimit())) {
            $filter->getSelection()->setUpperLimit($boundaries['max']);
        }

        if (empty($filter->getValues())) {
            $filter->setValues($this->getValues($filter));
        }

        $resultCount = $this->getResultCount($filter, $previousPostSet);
        $filter->getSelection()->setResultCount($resultCount);

        return $filter;
    }

    private function getValues(Filter $filter)
    {
        $values = array();

        switch ($filter->getSelection()->getType()) {
            case 'single':
                $value = ($filter->getSelection()->getLowerLimit() + $filter->getSelection()->getUpperLimit()) / 2;
                array_push($values, $value);
                break;

            case 'range':
                array_push($values, $filter->getSelection()->getLowerLimit());
                array_push($values, $filter->getSelection()->getUpperLimit());
                break;
        }

        return $values;
    }

    private function getResultCount(Filter $filter, array $previousPostSet)
    {
        $sliderPostSet = (new SliderPostSetBuilder())->buildPostSet($filter);
        $postSet = (new PostSetController)->mergePostSets(array($sliderPostSet, $previousPostSet), 'AND');
        return count($postSet);
    }

    private function getBoundaries(Guide $guide, Filter $filter, array $previousPostSet) /*: string*/
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'guideplugin_index';
        $conditions = $filter->getSelection()->getConditions();
        $boundaries = array('min' => 0, 'max' => 0);
        $boundaryResults = array();
        $where = '';

        if (count($previousPostSet) == 0 && $filter->getIsFirst()) {
            $boundaries['min'] = 0;
            $boundaries['max'] = 0;
            return $boundaries;
        }

        if (count($previousPostSet) == 0) {
            $previousPostSet = array(0);
        }

        $where .= ' AND post_id IN (' . implode(',', $previousPostSet) . ')';

        if (!empty($conditions)) {
            foreach ($conditions as $key => $condition) {
                $query = $wpdb->prepare('
                    SELECT MIN(CONVERT(facet_value, SIGNED)) as min_value, MAX(CONVERT(facet_value, SIGNED)) as max_value
                    FROM ' . $tableName . '
                    WHERE facet_name = %s ' . $where, $condition->getFacet()->getSlug());
                $boundaryResults = array_merge($boundaryResults, $wpdb->get_results($query, ARRAY_A));
            }
        }

        $boundaries['min'] = max(array_column($boundaryResults, 'min_value'));
        $boundaries['max'] = min(array_column($boundaryResults, 'max_value'));
        return $boundaries;

    }
}
