<?php //declare(strict_types=1);

namespace Guideplugin\Result\Limit;

use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Guide\Guide\Guide;
use Guideplugin\Result\PostSet\PostSetController;
use Guideplugin\Result\PostSetBuilder\CardPostSetBuilder;

class CardCount
{
    public function updateFilter(Guide $guide, Filter $filter, array $previousPostSet) /*: Filter*/
    {
        $querySets = array();
        $behavior = ($filter->getSelection()->getType() === 'radio') ? 'widen' : $filter->getSelection()->getBehavior();
        switch ($behavior) {
            case 'widen':
                $this->getWidenedPostSets($guide, $filter, $previousPostSet);
                break;

            case 'narrow':
                $this->getNarrowedPostSets($guide, $filter, $previousPostSet);
                break;

            default:
                # code...
                break;
        }

        $resultCount = $this->getResultCount($filter->getSelection()->getCards());
        $filter->getSelection()->setResultCount($resultCount);

        return $filter;
    }

    private function getResultCount(array $cards)
    {
        $resultCount = 0;
        if (!empty($cards)) {
            foreach ($cards as $card) {
                if ($card->getResultCount() > 0) {
                    $resultCount = $resultCount + $card->getResultCount();
                }
            }
        }
        return $resultCount;
    }

    private function getWidenedPostSets(Guide $guide, Filter $filter, array $previousPostSet) /*: bool*/
    {
        $cards = $filter->getSelection()->getCards();

        if (!empty($cards)) {
            foreach ($cards as $card) {
                $postSets = array();
                if (!(count($previousPostSet) == 0 && $filter->getIsFirst())) {
                    array_push($postSets, $previousPostSet);
                    array_push($postSets, (new CardPostSetBuilder())->getCardPostSet($card));
                    $postSet = (new PostSetController())->mergePostSets($postSets, 'AND');
                }

                $card->setResultCount(count($postSet));
            }
        }

        return true;
    }

    private function getNarrowedPostSets(Guide $guide, Filter $filter, array $previousPostSet) /*: bool*/
    {
        $selectedPostSet = $this->getSelectedPostSet($guide, $filter);

        $cards = $filter->getSelection()->getCards();

        if (!empty($cards)) {
            foreach ($cards as $card) {
                $postSets = array();
                if (!(count($previousPostSet) == 0 && $filter->getIsFirst())) {
                    array_push($postSets, $previousPostSet);
                    if (count($selectedPostSet) > 0) {
                        array_push($postSets, $selectedPostSet);
                    }
                    array_push($postSets, (new CardPostSetBuilder())->getCardPostSet($card));
                    $postSet = (new PostSetController())->mergePostSets($postSets, 'AND');
                }

                $card->setResultCount(count($postSet));
            }
        }

        return true;
    }

    private function getSelectedPostSet(Guide $guide, Filter $filter) /*: array*/
    {
        $selectedPostSets = array();
        $cards = $filter->getSelection()->getCards();

        if (!empty($cards)) {
            foreach ($cards as $card) {
                if (in_array($card->getUniqueIdentifier(), $filter->getValues())) {
                    array_push($selectedPostSets, (new CardPostSetBuilder())->getCardPostSet($card));
                }
            }
        }

        return (new PostSetController())->mergePostSets($selectedPostSets, 'AND');
    }
}
