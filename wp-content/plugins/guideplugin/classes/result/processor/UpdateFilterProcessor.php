<?php //declare(strict_types=1);

namespace Guideplugin\Result\Processor;

use Guideplugin\Guide\Filter\FilterController;
use Guideplugin\Guide\Guide\GuideController;
use Guideplugin\Result\Query\QueryController;

class UpdateFilterProcessor extends Processor
{
    public function updatedFilter(int $guideId, $currentIndex = 0, array $data = array()) /*: array*/
    {
        $time_pre = microtime(true);

        $response = array();
        $valueSets = $this->getValueSets($data); // parse submitted data from form to valueSet class
        $guide = (new GuideController())->buildGuide($guideId, $valueSets); // Guide with logic applied
        if (is_null($guide)) {
            return $response;
        }

        $previousFilters = $this->getPreviousFilters($guide, $currentIndex); // Get all the previous filters

        // Get current filter
        $response['current_filter'] = '';
        $currentFilter = $this->getCurrentFilter($guide, $currentIndex); // Get current filter by index
        if (!is_null($currentFilter)) {
            $currentFilter = (new QueryController())->getUpdatedFilter($guide, $currentFilter, $previousFilters); // Update counts and boundaries
            $currentFilterHtml = (new FilterController())->getFilterTemplate($currentFilter); // Get the HTML of current filter
            $response['current_filter'] = $currentFilterHtml; // Add current filter to HTML map
        }

        $response['filter_count'] = count($guide->getFilters());

        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;
        $response['processing_time'] = $exec_time;

        // return current and next filter HTML map
        return $response;
    }

}
