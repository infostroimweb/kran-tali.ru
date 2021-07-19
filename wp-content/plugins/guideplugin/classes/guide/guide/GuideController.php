<?php //declare(strict_types=1);

namespace Guideplugin\Guide\Guide;

use Guideplugin\Design\DesignController;
use Guideplugin\Guide\Filter\FilterController;
use Guideplugin\Helper\Template;
use Guideplugin\Logic\LogicProcessor\LogicProcessor;

class GuideController
{

    public function buildGuide(int $guideId, array $values = array())
    {
        if ($guideId !== 0 && get_post_status($guideId) === 'publish') {

            $title = get_field('guideplugin_title', $guideId);
            $description = get_field('guideplugin_description', $guideId);
            $allFilters = $this->getFilters($guideId);
            $filters = $this->getPreparedFilters($allFilters, $values, $guideId);

            if (is_string($title) && is_string($description) && is_array($filters)) {
                return new Guide($guideId, $title, $description, $filters, $allFilters);
            }
        }
        return null;
    }

    private function addFilterValues(array $filters, array $values) /*: array */
    {
        if (!empty($filters) && !empty($values)) {
            foreach ($filters as $filter) {
                foreach ($values as $valueSet) {
                    if ($filter->getUniqueIdentifier() == $valueSet->getUniqueFilterIdentifier()) {
                        $filter->setValues($valueSet->getValues());
                    }
                }
            }
        }

        return $filters;
    }

    private function getFilters(int $guideId) /*: array*/
    {
        $rawFilters = get_field('guideplugin_filters', $guideId);
        $filters = array();
        if (!empty($rawFilters)) {
            foreach ($rawFilters as $index => $rawFilter) {
                $filter = (new FilterController())->buildFilter(intval($rawFilter['filter']), $rawFilter['filter_unique_identifier'], $index);
                if (!is_null($filter)) {
                    array_push($filters, $filter);
                }
            }
        }

        return $filters;
    }

    private function getPreparedFilters(array $filters, array $values, int $guideId) /*: array*/
    {
        if (!empty($filters)) {
            $filters = $this->addFilterValues($filters, $values);
            $filters = (new LogicProcessor())->applyLogic($filters, $guideId);
            $filters = $this->setFirstLastFilter($filters);
        }

        return $filters;
    }

    private function setFirstLastFilter(array $filters) /*: array*/
    {
        if (!empty($filters)) {
            foreach ($filters as $index => $filter) {
                if ($index === 0) {
                    $filter->setIsFirst(true);
                }
                if ($index === count($filters) - 1) {
                    $filter->setIsLast(true);
                }

            }
        }
        return $filters;
    }

    public function getGuideTemplate(Guide $guide) /*: string*/
    {
        (new DesignController())->includeStyles($guide);
        ob_start();
        include Template::getTemplatePath('templates/guide/guide/guide.php');
        $guideTemplate = ob_get_clean();

        return $guideTemplate;
    }

    public function getGuideProgressModalTemplate(Guide $guide) /*: string*/
    {
        ob_start();
        include Template::getTemplatePath('templates/guide/modals/progressModal.php');
        $modalTemplate = ob_get_clean();

        return $modalTemplate;
    }

    public function getGuideInputRequiredModalTemplate(Guide $guide) /*: string*/
    {
        ob_start();
        include Template::getTemplatePath('templates/guide/modals/inputRequiredModal.php');
        $modalTemplate = ob_get_clean();

        return $modalTemplate;
    }

    public function getResultTemplate(Guide $guide, array $posts, int $page) /*: string*/
    {
        $posts = apply_filters('guideplugin/result/posts', $posts, $guide->getId());
        $resultQuery = $this->getResultQuery($guide, $posts, $page);
        ob_start();
        include Template::getTemplatePath('templates/result/result.php');
        $resultTemplate = ob_get_clean();

        return apply_filters('guideplugin/result/template', $resultTemplate, $guide->getId(), $posts);
    }

    private function getResultQuery(Guide $guide, array $posts, int $page) /*: \WP_Query*/
    {
        $resultCount = get_field('guideplugin_result_settings', $guide->getId())['result_count'];

        $arguments = array(
            'post_type' => 'any',
            'post_status' => 'publish',
            'post__in' => $posts,
            'posts_per_page' => $resultCount,
            'guideplugin' => true,
            'paged' => $page
        );

        $resultQuery = new \WP_Query($arguments);

        return $resultQuery;
    }

}
