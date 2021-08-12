<?php //declare(strict_types=1);

namespace Guideplugin\Result\PostSetBuilder;

use Guideplugin\Guide\FilterSelection\CardSelection\Card;
use Guideplugin\Guide\FilterSelection\CardSelection\CardConditionFacet;
use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Result\PostSet\PostSetController;

class CardPostSetBuilder extends PostSetBuilder
{

    public function buildPostSet(Filter $filter) /*: array */
    {
        $postSets = array();
        $cards = $filter->getSelection()->getCards();
        $behavior = ($filter->getSelection()->getType() === 'radio') ? 'narrow' : $filter->getSelection()->getBehavior();

        if (!empty($cards)) {
            foreach ($cards as $card) {
                if (in_array($card->getUniqueIdentifier(), $filter->getValues())) {
                    array_push($postSets, $this->getCardPostSet($card));
                }
            }
        }

        switch ($behavior) {
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

        return array();

    }

    public function getCardPostSet(Card $card) /*: string*/
    {
        $postSets = array();
        $conditions = $card->getFacetConditions();
        $index = 0;
        $firstIteration = true;

        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                switch ($condition->getConditionType()) {
                    case 'facet':
                        if ($firstIteration) {
                            $postSets[$index] = $this->getPostSetFromQueries($condition);
                            break;
                        }
                        $tempQuery = array($postSets[$index], $this->getPostSetFromQueries($this->getQueries($condition)));
                        array_push($postSets[$index], (new PostSetController())->mergePostSets($tempQuery, 'AND'));
                        break;

                    case 'or':
                        $index++;
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return (new PostSetController())->mergePostSets($postSets, 'OR');
    }

    private function getPostSetFromQueries(CardConditionFacet $condition)
    {
        $postSets = array();
        $queries = $this->getQueries($condition);
        if (!empty($queries)) {
            foreach ($queries as $query) {
                array_push($postSets, $this->getQueryResult($query));
            }
        }

        switch ($condition->getBehavior()) {
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

        return array();

    }

    private function getQueries(CardConditionFacet $condition) /*: QuerySet*/
    {
        $queries = array();

        switch ($condition->getRule()) {
            case 'equal':
            case 'not_equal':
            case 'like':
            case 'not_like':
            case 'greater_than':
            case 'less_than':
                $queries = $this->getCompareQueries($condition);
                break;

            case 'contains':
            case 'not_contains':
                $queries = $this->getContainQueries($condition);
                break;

            default:
                # code...
                break;
        }

        return $queries;
    }

    private function getCompareQueries(CardConditionFacet $condition) /*: QuerySet*/
    {
        global $wpdb;
        $queries = array();
        $values = $condition->getValues();
        $logicalOperator = '';

        switch ($condition->getRule()) {
            case 'equal':
                $logicalOperator = '=';
                break;
            case 'not_equal':
                $logicalOperator = '!=';
                break;
            case 'like':
                $logicalOperator = 'LIKE';
                break;
            case 'not_like':
                $logicalOperator = 'NOT LIKE';
                break;
            case 'greater_than':
                $logicalOperator = '>';
                break;
            case 'less_than':
                $logicalOperator = '<';
                break;
            default:
                # code...
                break;
        }

        if (!empty($values)) {
            foreach ($values as $value) {
                array_push($queries, $wpdb->prepare('(facet_name = \'' . $condition->getFacet()->getSlug() . '\' AND facet_value ' . $logicalOperator . ' %s)', $value));
            }
        }

        return $queries;
    }

    private function getContainQueries(CardConditionFacet $condition) /*: QuerySet*/
    {
        global $wpdb;
        $queries = array();
        $values = $condition->getValues();
        $logicalOperator = '';

        switch ($condition->getRule()) {
            case 'contains':
                $logicalOperator = 'LIKE';
                break;
            case 'not_contains':
                $logicalOperator = 'NOT LIKE';
                break;
            default:
                # code...
                break;
        }

        if (!empty($values)) {
            foreach ($values as $value) {
                array_push($queries, $wpdb->prepare('(facet_name = \'' . $condition->getFacet()->getSlug() . '\' AND facet_value ' . $logicalOperator . ' %s)', '%' . $wpdb->esc_like($value) . '%'));
            }
        }

        return $queries;
    }

}
