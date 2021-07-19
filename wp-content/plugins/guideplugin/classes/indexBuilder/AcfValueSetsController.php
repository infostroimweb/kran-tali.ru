<?php //declare(strict_types=1);

namespace Guideplugin\IndexBuilder;

use Guideplugin\IndexBuilder\FacetValueSet;

class AcfValueSetsController
{

    public function getAcfValueSets(array $fieldObject, int $postId) /*: array*/
    {
        $valueSets = array();
        $fieldObjects = $this->getFieldHierarchy($fieldObject);
        if (count($fieldObjects) > 1) {
            $fieldObjects = $this->getLoopedFields($fieldObjects, $postId);
        }

        if (!empty($fieldObjects)) {
            foreach ($fieldObjects as $fieldObjectItem) {
                $valueSet = $this->getFieldObjectValueSets($fieldObjectItem);
                if (!empty($valueSet)) {
                    $valueSets = array_merge($valueSets, $valueSet);
                }
            }
        }

        return $valueSets;
    }

    private function getLoopedFields(array $fieldHierarchy, int $postId, int $index = 0, array $fieldObjects = array())
    {
        if (have_rows($fieldHierarchy[$index]['key'], $postId)) {
            while (have_rows($fieldHierarchy[$index]['key'], $postId)) {
                the_row();
                if ((count($fieldHierarchy) - 2) == $index) {
                    array_push($fieldObjects, get_sub_field_object($fieldHierarchy[$index + 1]['key']));
                } else {
                    $index++;
                    return $this->getLoopedFields($fieldHierarchy, $postId, $index, $fieldObjects);
                }
            }
            return $fieldObjects;
        }
    }

    private function getFieldHierarchy(array $fieldObject, array $fieldHierarchy = array())
    {
        array_unshift($fieldHierarchy, $fieldObject);
        $parentFieldObject = $this->getParentFieldObject($fieldObject);

        if (!empty($parentFieldObject)) {
            return $this->getFieldHierarchy($parentFieldObject, $fieldHierarchy);
        }

        return $fieldHierarchy;
    }

    private function getParentFieldObject($fieldObject)
    {
        if (substr($fieldObject['parent'], 0, 6) == 'field_') {
            return get_field_object($fieldObject['parent']);
        }

        if (is_int($fieldObject['parent'])) {
            return acf_get_field(intval($fieldObject['parent']));
        }

        return array();
    }

    private function getFieldObjectValueSets(array $fieldObject)
    {
        if (is_array($fieldObject) && isset($fieldObject['type']) && !empty($fieldObject['value'])) {
            switch ($fieldObject['type']) {
                case 'text':
                case 'textarea':
                case 'number':
                case 'email':
                case 'url':
                case 'true_false':
                    return $this->getAcfBasicValueSets($fieldObject);
                    break;

                case 'select':
                case 'checkbox':
                case 'radio':
                    return $this->getAcfSelectionValueSets($fieldObject);
                    break;

                default:
                    # code...
                    break;
            }
        }

        return array();
    }

    private function getAcfBasicValueSets(array $fieldObject) /*: array*/
    {
        return array((new FacetValueSet(strval($fieldObject['value']), strval($fieldObject['value']))));
    }

    private function getAcfSelectionValueSets(array $fieldObject) /*: array*/
    {
        switch ($fieldObject['type']) {
            case 'checkbox':
                return $this->getAcfSelectionValueSetsMultiple($fieldObject);
                break;

            case 'radio':
                return $this->getAcfSelectionValueSetsSingle($fieldObject);
                break;

            case 'select':
                if (isset($fieldObject['multiple']) && $fieldObject['multiple'] == 1) {
                    return $this->getAcfSelectionValueSetsMultiple($fieldObject);
                }
                return $this->getAcfSelectionValueSetsSingle($fieldObject);
                break;

            default:
                # code...
                break;
        }

        return array();
    }

    private function getAcfSelectionValueSetsSingle(array $fieldObject) /*: array*/
    {
        $selectionValueSet = $this->getAcfSelectionValueSet(strval($fieldObject['value']), $fieldObject);
        if (!empty($selectionValueSet->getFacetValue()) && !empty($selectionValueSet->getFacetValue())) {
            return array($selectionValueSet);
        }
        return array();

    }

    private function getAcfSelectionValueSetsMultiple(array $fieldObject) /*: array*/
    {
        $selectionValueSets = array();
        if (!empty($fieldObject['value'])) {
            foreach ($fieldObject['value'] as $value) {
                $selectionValueSet = $this->getAcfSelectionValueSet(strval($value), $fieldObject);
                if (!empty($selectionValueSet->getFacetValue()) && !empty($selectionValueSet->getFacetValue())) {
                    array_push($selectionValueSets, $selectionValueSet);
                }
            }
        }
        return $selectionValueSets;
    }

    private function getAcfSelectionValueSet($value, $fieldObject) /*: FacetValueSet*/
    {
        $fieldValue = '';
        $fieldLabel = '';

        if (!empty($value) && is_string($value)) {
            switch ($fieldObject['return_format']) {
                case 'value':
                    $fieldValue = $value;
                    $fieldLabel = $fieldObject['choices'][$value];
                    break;

                case 'label':
                    $fieldValue = array_search($value, $fieldObject['choices']);
                    $fieldLabel = $value;
                    break;

                case 'array':
                    $fieldValue = $value['value'];
                    $fieldLabel = $value['value'];
                    break;

                default:
                    # code...
                    break;
            }
        }

        return new FacetValueSet((string) $fieldValue, (string) $fieldLabel);
    }

}
