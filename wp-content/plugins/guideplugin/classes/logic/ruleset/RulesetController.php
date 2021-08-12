<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Ruleset;

use Guideplugin\Logic\Action\Action;
use Guideplugin\Logic\Condition\FilterCondition;
use Guideplugin\Logic\Condition\OrCondition;
use Guideplugin\Logic\Ruleset\Ruleset;

class RulesetController
{
    public function buildRuleset(array $conditionsFields, array $actionsFields) /*: Ruleset*/
    {
        $conditions = $this->getConditions($conditionsFields);
        $actions = $this->getActions($actionsFields);

        if (is_array($conditions) && is_array($actions)) {
            return (new Ruleset($conditions, $actions));
        }
        return;
    }

    private function getConditions(array $conditionsFields) /*: array*/
    {
        $conditions = array();
        if (!empty($conditionsFields)) {
            foreach ($conditionsFields as $conditionFields) {
                switch ($conditionFields['acf_fc_layout']) {
                    case 'filter_condition':
                        $conditionTypeFilter = (new FilterCondition(
                            $conditionFields['filter'],
                            $conditionFields['rule'],
                            $conditionFields['value']
                        ));
                        array_push($conditions, $conditionTypeFilter);
                        break;

                    case 'or_condition':
                        $conditionTypeOr = (new OrCondition());
                        array_push($conditions, $conditionTypeOr);
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
        return $conditions;
    }

    private function getActions(array $actionsFields) /*: array*/
    {
        $actions = array();
        if (!empty($actionsFields)) {
            foreach ($actionsFields as $actionFields) {
                array_push($actions, (new Action($actionFields['filter'], $actionFields['action'])));
            }
        }
        return $actions;
    }
}
