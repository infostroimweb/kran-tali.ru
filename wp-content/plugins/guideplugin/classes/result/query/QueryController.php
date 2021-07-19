<?php //declare(strict_types=1);

namespace Guideplugin\Result\Query;

use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Guide\Guide\Guide;
use Guideplugin\Result\Limit\CardCount;
use Guideplugin\Result\Limit\SliderBoundaries;
use Guideplugin\Result\PostSet\PostSetController;

class QueryController
{
    public function getResults(Guide $guide, array $filters) /*: array*/
    {
        return $this->getFiltersPostSet($guide, $filters);
    }

    public function getUpdatedFilter(Guide $guide, Filter $currentFilter, array $previousFilters) /*: Filter*/
    {
        $previousPostSet = $this->getFiltersPostSet($guide, $previousFilters);
        return $this->updateFilter($guide, $currentFilter, $previousPostSet);
    }

    private function getFiltersPostSet(Guide $guide, array $filters)
    {
        return (new PostSetController())->getPostSet($guide, $filters);
    }

    private function updateFilter(Guide $guide, Filter $filter, array $previousPostSet)
    {
        switch ($filter->getType()) {
            case 'cards':
                (new CardCount())->updateFilter($guide, $filter, $previousPostSet);
                break;

            case 'slider':
                (new SliderBoundaries())->updateFilter($guide, $filter, $previousPostSet);
                break;

            default:
                # code...
                break;
        }

        return $filter;
    }
}
