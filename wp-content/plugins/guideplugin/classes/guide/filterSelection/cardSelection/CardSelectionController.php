<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\CardSelection;

use Guideplugin\Guide\Facet\FacetController;
use Guideplugin\Guide\FilterSelection\CardSelection\Card;
use Guideplugin\Guide\FilterSelection\CardSelection\CardConditionFacet;
use Guideplugin\Guide\FilterSelection\CardSelection\CardSelection;
use Guideplugin\Guide\FilterSelection\FilterSelection;
use Guideplugin\Helper\Template;

class CardSelectionController
{

    public function buildCardSelection(int $filterId) /*: FilterSelection*/
    {
        $cardFields = get_field('guideplugin_cards', $filterId);
        $cardType = $cardFields['card_type'];
        $cardBehavior = $cardFields['card_behavior'];
        $cards = $this->getCards($filterId);
        $imageWidth = (isset($cardFields['image_width']) && !empty($cardFields['image_width'])) ? intval($cardFields['image_width']) : 100;
        $imageHeight = (isset($cardFields['image_height']) && !empty($cardFields['image_height'])) ? intval($cardFields['image_height']) : 100;

        if (is_string($cardType) && is_string($cardBehavior) && is_array($cards) && is_int($imageWidth) && is_int($imageHeight)) {
            return (new CardSelection($cardType, $cardBehavior, $cards, $imageWidth, $imageHeight));
        }
        return;
    }

    private function getCards(int $filterId) /*: array*/
    {
        $cardFields = get_field('guideplugin_cards', $filterId);
        $cardItems = $cardFields['card_items'];
        $cards = array();

        if (!empty($cardItems)) {
            foreach ($cardItems as $key => $cardItem) {
                array_push($cards, $this->getCard($cardItem));
            }
        }

        return $cards;
    }

    private function getCard(array $cardProperties) /*: Card*/
    {
        $image = $cardProperties['image'];
        $label = $cardProperties['label'];
        $conditions = $this->getCardConditions($cardProperties['conditions']);
        $uniqueIdentifier = $cardProperties['card_unique_identifier'];

        return (new Card($image, $label, $conditions, $uniqueIdentifier));
    }

    private function getCardConditions($conditionFields) /*: array*/
    {
        $conditions = array();

        if (!empty($conditionFields)) {
            foreach ($conditionFields as $conditionField) {
                switch ($conditionField['acf_fc_layout']) {
                    case 'facet':
                        $facet = (new FacetController())->buildFacet($conditionField['facet']);
                        if (!is_null($facet)) {
                            $cardConditionFacet = (new CardConditionFacet(
                                $facet,
                                $conditionField['rule'],
                                $conditionField['behavior'],
                                $this->prepareCardConditionValues($conditionField['values'])
                            ));
                            array_push($conditions, $cardConditionFacet);
                        }
                        break;

                    case 'or_condition':
                        $cardConditionOr = (new CardConditionOr());
                        array_push($conditions, $cardConditionOr);
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return $conditions;
    }

    private function prepareCardConditionValues($values) /*: array*/
    {
        $preparedValues = array();

        if (!empty($values)) {
            foreach ($values as $value) {
                array_push($preparedValues, $value['value']);
            }
        }

        return $preparedValues;
    }

    public function getCardTemplate(Card $card) /*: string*/
    {
        ob_start();
        include Template::getTemplatePath('templates/guide/filter/cards/card.php');
        $cardTemplate = ob_get_clean();

        return apply_filters('guideplugin/template/card', $cardTemplate, $card);
    }

}
