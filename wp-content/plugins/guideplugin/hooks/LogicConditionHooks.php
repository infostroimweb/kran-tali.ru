<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use \GuidePlugin\Guide\Filter\FilterController;

class LogicConditionHooks
{
    public function initialize()
    {
        add_filter('acf/load_field/key=field_5ea831aac956b', array($this, 'getFilterCardUniqueIdentifiers')); // Get card unique identifiers
    }

    public function getFilterCardUniqueIdentifiers($field)
    {
        if (\GuidepluginHelper::skip_load_field()) {
            return $field;
        }

        $choices = $this->getChoices();

        if (!empty($choices)) {
            $field['choices'] = $choices;
        }
        return $field;
    }

    private function getChoices()
    {
        $arguments = array(
            'posts_per_page' => -1,
            'post_type' => 'guideplugin_filters',
            'post_status' => 'publish',
            'suppress_filters' => false
        );
        $filters = get_posts($arguments);

        $choices = array();

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $filterObject = (new FilterController())->buildFilter($filter->ID);
                if (!is_null($filterObject) && $filterObject->getType() === 'cards') {
                    $choices = array_merge($choices, $this->getCardIdentifiers(get_the_title($filter->ID), $filterObject->getSelection()->getCards()));
                }
            }
        }

        return $choices;
    }

    private function getCardIdentifiers(string $filterTitle, array $cards)
    {
        $cardIdentifiers = array();
        if (!empty($cards)) {
            foreach ($cards as $card) {
                $cardIdentifiers[$card->getUniqueIdentifier()] = $filterTitle . ' &rarr; ' . $card->getLabel() . ' (' . $card->getUniqueIdentifier() . ')';
            }
        }
        return $cardIdentifiers;
    }
}
