<?php //declare(strict_types=1);

namespace Guideplugin\Result\Processor;

use Guideplugin\Guide\Guide\Guide;
use Guideplugin\Guide\ValueSet\ValueSetController;

class Processor
{
    protected function getValueSets(array $data) /*: array*/
    {
        array_walk_recursive($data, array($this, 'escapeFiltersData'));

        $valueSets = array();
        if (!empty($data)) {
            foreach ($data as $dataItem) {
                if (!is_null($dataItem['filter_identifier']) && isset($dataItem['values']) && !is_null($dataItem['values'])) {
                    array_push($valueSets, (new ValueSetController())->buildValueSet($dataItem['filter_identifier'], $dataItem['values']));
                }
            }
        }
        return $valueSets;
    }

    protected function getPreviousFilters(Guide $guide, int $currentIndex) /*: array*/
    {
        $previousFilters = array();
        if (!empty($guide->getFilters())) {
            foreach ($guide->getFilters() as $filter) {
                if ($filter->getIndex() < $currentIndex) {
                    array_push($previousFilters, $filter);
                }
            }
        }
        return $previousFilters;
    }

    protected function getNextFilter(Guide $guide, int $currentIndex)
    {
        if (!empty($guide->getFilters())) {
            foreach ($guide->getFilters() as $filter) {
                if ($filter->getIndex() > $currentIndex) {
                    return $filter;
                }
            }
        }
        return null;
    }

    protected function getCurrentFilter(Guide $guide, int $currentIndex)
    {
        if (!empty($guide->getFilters())) {
            foreach ($guide->getFilters() as $filter) {
                if ($filter->getIndex() == $currentIndex) {
                    return $filter;
                }
            }
        }
        return null;
    }

    private function escapeFiltersData(&$item, $key)
    {
        $item = sanitize_text_field($item);
    }

}
