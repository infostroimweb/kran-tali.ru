<?php //declare(strict_types=1);

namespace Guideplugin\Logic\LogicProcessor;

use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Logic\Condition\Condition;
use Guideplugin\Logic\Logic\LogicController;
use Guideplugin\Logic\Ruleset\Ruleset;

class LogicProcessor
{
    public function applyLogic(array $filters, int $guideId) /*: array*/
    {
        $logic = (new LogicController())->buildLogic($guideId);
        $actionSet = $this->getActionSet($logic->getRulesets(), $filters);

        return $this->applyActions($actionSet, $filters);
    }

    private function applyActions(array $actions, array $filters)
    {
        if (!empty($actions) && !empty($filters)) {
            foreach ($filters as $filterKey => $filter) {
                // loop through all filters to remove also filters before current index
                $this->applyFilterActions($filters, $actions, $filterKey);
            }
        }

        return array_values($filters);
    }

    private function applyFilterActions(array &$filters, array $actions, int $filterKey) /*: array*/
    {
        if (!empty($actions)) {
            foreach ($actions as $action) {
                if (isset($filters[$filterKey]) && $action->getFilterId() == $filters[$filterKey]->getId()) {
                    switch ($action->getAction()) {
                        case 'hide':
                            unset($filters[$filterKey]);
                            break;
                        default:
                            // statements_def
                            break;
                    }
                }
            }
        }

        return $filters;

    }

    // This is the first touchpoint
    private function getActionSet(array $rulesets, array $filters)
    {
        $actions = array();

        if (!empty($rulesets)) {
            foreach ($rulesets as $ruleset) {
                $rulesetActions = $this->getRulesetActions($ruleset, $filters);
                if (!empty($rulesetActions) > 0) {
                    $actions = array_merge($actions, $rulesetActions);
                }
            }
        }

        return $actions;

    }

    private function getRulesetActions(Ruleset $ruleset, array $filters)
    {

        $conditionsFulfilled = $this->checkRulesetConditions($ruleset->getConditions(), $filters);

        if ($conditionsFulfilled) {
            return $ruleset->getActions();
        }

        return array();
    }

    private function checkRulesetConditions(array $conditions, array $filters) /*: bool*/
    {
        $results = array();

        if (!empty($conditions)) {
            foreach ($conditions as $condition) {

                if ($condition->getConditionType() == 'or' && count($results) > 0) {
                    if ($this->checkFulfillment($results)) {
                        return true;
                    }
                    $results = array();
                }

                if ($condition->getConditionType() == 'filter') {
                    $filter = $this->getFilterById($filters, $condition->getFilterId());
                    if ($filter instanceof Filter) {
                        array_push($results, $this->checkFilterCondition($condition, $filter));
                    }
                }
            }
        }

        return $this->checkFulfillment($results);
    }

    private function getFilterById(array $filters, int $filterId)
    {
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if ($filter->getId() == $filterId) {
                    return $filter;
                }
            }
        }
        return;
    }

    private function checkFulfillment(array $results) /*: bool*/
    {
        if (!empty($results)) {
            foreach ($results as $result) {
                if ($result !== true) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    private function checkFilterCondition(Condition $condition, Filter $filter) /*: bool*/
    {
        switch ($condition->getRule()) {
            case 'card_id_is':
                if ($filter->getType() == 'cards') {
                    $response = in_array($condition->getValue(), $filter->getValues());
                    return $response;
                    break;
                }
            case 'card_id_is_not':
                if ($filter->getType() == 'cards') {
                    $response = !in_array($condition->getValue(), $filter->getValues());
                    return $response;
                    break;
                }
            case 'slider_single_less_than':
                if ($filter->getType() == 'slider') {
                    $response = floatval($filter->getValues()[0]) < floatval($condition->getValue());
                    return $response;
                    break;
                }
            case 'slider_single_greater_than':
                if ($filter->getType() == 'slider') {
                    $response = floatval($filter->getValues()[0]) > floatval($condition->getValue());
                    return $response;
                    break;
                }
        }
        return false;
    }
}
