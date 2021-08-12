<?php //declare(strict_types=1);

namespace Guideplugin\Result\Processor;

use \Guideplugin\Guide\Guide\GuideController;
use \Guideplugin\Result\Query\QueryController;

class ResultProcessor extends Processor
{
    public function getResults(int $guideId, array $data, int $page) /*: array*/
    {
        $time_pre = microtime(true);

        $valueSets = $this->getValueSets($data); // parse submitted data from form to valueSet class
        $guide = (new GuideController())->buildGuide($guideId, $valueSets); // Guide with logic applied
        $posts = (new QueryController())->getResults($guide, $guide->getFilters());
        $resultHtml = (new GuideController())->getResultTemplate($guide, $posts, $page);

        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;
        $filters['processing_time'] = count($guide->getFilters());
        $nextPage = $page + 1;

        return array('result_html' => $resultHtml, 'next_page' => $nextPage, 'processing_time' => $exec_time);
    }

}
