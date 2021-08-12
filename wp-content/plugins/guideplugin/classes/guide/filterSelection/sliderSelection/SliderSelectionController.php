<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\SliderSelection;

use Guideplugin\Guide\Facet\FacetController;
use Guideplugin\Guide\FilterSelection\FilterSelection;
use Guideplugin\Guide\FilterSelection\SliderSelection\SliderCondition;
use Guideplugin\Guide\FilterSelection\SliderSelection\SliderSelection;

class SliderSelectionController
{

    public function buildSliderSelection(int $filterId) /*: FilterSelection*/
    {
        $sliderFields = get_field('guideplugin_slider', $filterId);
        $type = $sliderFields['slider_type'];
        $behavior = $sliderFields['behavior'];
        $conditions = (isset($sliderFields['conditions']) && is_array($sliderFields['conditions'])) ? $this->getSliderConditions($sliderFields['conditions'], $type) : [];
        $lowerLimit = $sliderFields['lower_limit'];
        $upperLimit = $sliderFields['upper_limit'];
        $prefix = $sliderFields['prefix'];
        $suffix = $sliderFields['suffix'];
        $images = (isset($sliderFields['slider_images']) && is_array($sliderFields['slider_images'])) ? $this->getSliderImages($sliderFields['slider_images']) : [];
        $imageWidth = (isset($sliderFields['image_width']) && !empty($sliderFields['image_width'])) ? intval($sliderFields['image_width']) : 80;
        $imageHeight = (isset($sliderFields['image_height']) && !empty($sliderFields['image_height'])) ? intval($sliderFields['image_height']) : 80;
        $prettifyValues = ($sliderFields['prettify_values']) ? true : false;

        if (is_string($type) && is_string($behavior) && is_array($conditions) && is_string($lowerLimit) && is_string($upperLimit) && is_string($prefix) && is_string($suffix) && is_array($images) && is_int($imageWidth) && is_int($imageHeight)) {
            return (new SliderSelection($type, $behavior, $conditions, $lowerLimit, $upperLimit, $prefix, $suffix, $images, $imageWidth, $imageHeight, $prettifyValues));
        }
        return;
    }

    private function getSliderConditions(array $conditions, string $type) /*: array*/
    {
        $sliderConditions = array();
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $facet = (new FacetController())->buildFacet($condition['facet']);
                $rule = '';
                switch ($type) {
                    case 'single':
                        $rule = $condition['rule_single'];
                        break;

                    case 'range':
                        $rule = $condition['rule_range'];
                        break;

                    default:
                        # code...
                        break;
                }
                if (!is_null($facet)) {
                    array_push($sliderConditions, new SliderCondition($facet, $rule));
                }
            }
        }
        return $sliderConditions;
    }

    private function getSliderImages(array $rawImageArray)
    {
        $images = array();
        if (!empty($rawImageArray)) {
            foreach ($rawImageArray as $rawImage) {
                array_push($images, $rawImage['slider_image']);
            }
        }
        return $images;
    }

}
