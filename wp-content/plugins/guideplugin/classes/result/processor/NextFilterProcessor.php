<?php //declare(strict_types=1);

namespace Guideplugin\Result\Processor;

use Guideplugin\Guide\Filter\FilterController;
use Guideplugin\Guide\Guide\GuideController;
use Guideplugin\Result\Query\QueryController;

class NextFilterProcessor extends Processor
{
    public function nextFilter(int $guideId, $currentIndex = 0, array $data = array()) /*: array*/
    {
        $time_pre = microtime(true);

        $response = array();
        $valueSets = $this->getValueSets($data); // parse submitted data from form to valueSet class
        $guide = (new GuideController())->buildGuide($guideId, $valueSets); // Guide with logic applied
        if (is_null($guide)) {
            return $response;
        }

        $nextFilter = $this->getNextFilter($guide, $currentIndex); // Get next filter by current index
        $nextIndex = $nextFilter->getIndex();

        $previousFilters = $this->getPreviousFilters($guide, $nextIndex); // Get all the previous filters

        $nextFilterHtml = '';
        if (!is_null($nextFilter)) {
            $nextFilter = (new QueryController())->getUpdatedFilter($guide, $nextFilter, $previousFilters); // Update counts and boundaries
            $nextFilterHtml = (new FilterController())->getFilterTemplate($nextFilter); // Get the HTML of next filter
        }
        $response['next_filter'] = $nextFilterHtml; // Add current filter to HTML map

        $response['filter_count'] = count($guide->getFilters());

        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;
        $response['processing_time'] = $exec_time;

        // return next filter HTML map
        return $response;
    }

}
