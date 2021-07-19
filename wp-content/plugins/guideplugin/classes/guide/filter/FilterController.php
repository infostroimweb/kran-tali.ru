<?php //declare(strict_types=1);

namespace Guideplugin\Guide\Filter;

use Guideplugin\Guide\FilterSelection\CardSelection\CardSelectionController;
use Guideplugin\Guide\FilterSelection\SliderSelection\SliderSelectionController;
use Guideplugin\Guide\Filter\Filter;
use Guideplugin\Helper\Template;

class FilterController
{

    public function buildFilter(int $filterId, $uniqueIdentifier = '', $index = 0, array $values = array()) /*: Filter*/
    {
        if ($filterId !== 0 && get_post_status($filterId) === 'publish' && $this->getSelection($filterId) !== null) {
            return new Filter(
                $filterId,
                get_field('guideplugin_title', $filterId),
                get_field('guideplugin_description', $filterId),
                get_field('guideplugin_filter_type', $filterId),
                $this->getSelection($filterId),
                $uniqueIdentifier,
                (get_field('guideplugin_input_required', $filterId)) ? true : false,
                (get_field('guideplugin_next_on_select', $filterId)) ? true : false,
                (get_field('guideplugin_show_in_progress', $filterId)) ? true : false,
                $index,
                $values
            );
        }
        return null;
    }

    private function getSelection(int $filterId) /*: FilterSelection*/
    {
        $type = get_field('guideplugin_filter_type', $filterId);

        switch ($type) {
            case 'cards':
                return (new CardSelectionController())->buildCardSelection($filterId);
                break;

            case 'slider':
                return (new SliderSelectionController())->buildSliderSelection($filterId);
                break;

            default:
                # code...
                break;
        }

        return null;
    }

    public function getFilterTemplate(Filter $filter) /*: string*/
    {
        ob_start();
        include Template::getTemplatePath('templates/guide/filter/filter.php');
        $filterTemplate = ob_get_clean();

        return apply_filters('guideplugin/template/filter', $filterTemplate, $filter);
    }

    public function getFilterSelectionTemplate(Filter $filter) /*: string*/
    {
        ob_start();
        switch ($filter->getType()) {
            case 'cards':
                include Template::getTemplatePath('templates/guide/filter/cards/cards.php');
                break;

            case 'slider':
                include Template::getTemplatePath('templates/guide/filter/slider/slider.php');
                break;

            default:
                # code...
                break;
        }
        $filterSelectionTemplate = ob_get_clean();

        return apply_filters('guideplugin/template/filter_selection', $filterSelectionTemplate, $filter);
    }

    public function getFilterHelpModalTemplate(Filter $filter) /*: string*/
    {
        ob_start();
        include Template::getTemplatePath('templates/guide/modals/filterHelpModal.php');
        $modalTemplate = ob_get_clean();

        return apply_filters('guideplugin/template/help_modal', $modalTemplate, $filter);
    }

}
