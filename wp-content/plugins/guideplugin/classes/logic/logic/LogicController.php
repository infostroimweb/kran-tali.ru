<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Logic;

use Guideplugin\Logic\Logic\Logic;
use Guideplugin\Logic\Ruleset\RulesetController;

class LogicController
{

    public function buildLogic(int $guideId)
    {
        $logicIds = $this->getLogicIds($guideId);
        $rulesets = $this->getRulesets($logicIds);

        if (is_array($logicIds) && is_array($rulesets)) {
            return (new Logic($guideId, $rulesets));
        }
        return;
    }

    private function getRulesets(array $logicIds) /*: array*/
    {
        $rulesets = array();
        if (!empty($logicIds)) {
            foreach ($logicIds as $logicId) {
                $rulesets = array_merge($rulesets, $this->getSingleRuleset($logicId));
            }
        }

        return $rulesets;
    }

    private function getSingleRuleset(int $logicId) /*: array*/
    {
        $rulesetsFields = get_field('guideplugin_rulesets', $logicId);
        $rulesets = array();

        if (!empty($rulesetsFields)) {
            foreach ($rulesetsFields as $rulesetFields) {
                array_push($rulesets, (new RulesetController())->buildRuleset($rulesetFields['conditions'], $rulesetFields['actions']));
            }
        }

        return $rulesets;
    }

    private function getLogicIds($guideId) /*: array*/
    {
        $arguments = array(
            'posts_per_page' => -1,
            'post_type' => 'guideplugin_logics',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'guideplugin_guides',
                    'value' => '"'.$guideId.'"',
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'guideplugin_guides',
                    'value' => '',
                    'compare' => '=',
                ),
            ),
        );
        $logicPosts = new \WP_Query($arguments);

        $logicIds = array();
        if (isset($logicPosts->posts) && !empty($logicPosts->posts)) {
            foreach ($logicPosts->posts as $logicPost) {
                array_push($logicIds, $logicPost->ID);
            }
        }

        return $logicIds;
    }
}
