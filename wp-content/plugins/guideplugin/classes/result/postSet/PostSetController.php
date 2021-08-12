<?php //declare(strict_types=1);

namespace Guideplugin\Result\PostSet;

use Guideplugin\Guide\Guide\Guide;
use Guideplugin\Result\PostSetBuilder\CardPostSetBuilder;
use Guideplugin\Result\PostSetBuilder\SliderPostSetBuilder;
use Guideplugin\Result\PostSet\PostBaseController;

class PostSetController
{
    public function getPostSet(Guide $guide, array $filters) /*: array*/
    {
        $postSets = array();

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                switch ($filter->getType()) {
                    case 'cards':
                        array_push($postSets, (new CardPostSetBuilder())->buildPostSet($filter));
                        break;

                    case 'slider':
                        array_push($postSets, (new SliderPostSetBuilder())->buildPostSet($filter));
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        $postSet = $this->mergePostSets($postSets, 'AND');

        return (new PostBaseController())->getPostBase($postSet, $guide);
    }

    public function mergePostSets(array $postSets, $method = 'AND') /*: array */
    {
        if (!empty($postSets)) {
            $mergedPostSets = array();
            foreach ($postSets as $postSet) {
                if (is_array($postSet) && count($postSet) > 0) {
                    switch ($method) {
                        case 'AND':
                            if (count($mergedPostSets) === 0) {
                                $mergedPostSets = $postSet;
                            }
                            $mergedPostSets = $this->arrayIntersect($mergedPostSets, $postSet);
                            break;

                        case 'OR':
                            $mergedPostSets = $this->arrayMerge($mergedPostSets, $postSet);
                            break;

                        default:
                            # code...
                            break;
                    }

                }
            }
            return $mergedPostSets;
        }
        return array();
    }

    private function arrayIntersect($firstArray, $secondArray)
    {
        $index = array_flip($firstArray);
        $second = array_flip($secondArray);

        $x = array_intersect_key($index, $second);

        return array_flip($x);
    }

    private function arrayMerge($firstArray, $secondArray)
    {
        foreach ($secondArray as $i) {
            $firstArray[] = $i;
        }
        return $firstArray;
    }

}
