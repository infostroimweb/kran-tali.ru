<?php //declare(strict_types=1);

namespace Guideplugin\IndexBuilder;

use Guideplugin\Guide\Facet\FacetController;
use Guideplugin\IndexBuilder\FacetDatasetController;
use Guideplugin\Helper\MultilanguageHelper;

class IndexBuilder
{
    public function buildIndex(array $posts = array())
    {
        #ob_start();
        if (count($posts) === 0) {
            $posts = $this->getPosts();
        }

        $facetDatasets = array();

        if (!empty($posts)) {
            foreach ($posts as $postId) {
                (new MultilanguageHelper)->setIndexBuilderLanguage($postId);
                $facets = $this->getFacets();
                if (count($facets) > 0) {
                    $facetDatasets = array_merge($facetDatasets, $this->getPostFacetDatasets($postId, $facets));
                }
            }
        }

        #error_log(ob_get_clean());
        return $this->updateFacetDatasets($facetDatasets);
    }

    public function purgeIndex()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'guideplugin_index';

        update_option('guideplugin_index_processing', 0);
        update_option('guideplugin_index_offset', 0);

        $query = 'TRUNCATE TABLE ' . $tableName;

        return $wpdb->query($query);
    }

    public function getIndexStatus()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'guideplugin_index';
        $query = 'SELECT COUNT(post_id) AS post_count FROM ' . $tableName . ' GROUP BY post_id';

        return $wpdb->query($query);
    }



    private function getPostFacetDatasets(int $postId, array $facets) /*: array*/
    {
        $facetDatasets = array();
        if (!empty($facets)) {
            foreach ($facets as $facet) {
                $facetDatasets = array_merge($facetDatasets, (new FacetDatasetController())->buildFacetDatasets($postId, $facet));
            }
        }
        return $facetDatasets;
    }

    private function updateFacetDatasets(array $facetDatasets)
    {
        if (count($facetDatasets) > 0) {
            $this->deleteFacetDatasets($facetDatasets);
            $this->insertFacetDatasets($facetDatasets);
        }
    }

    private function insertFacetDatasets(array $facetDatasets)
    {
        global $wpdb;
        $query = $this->getInsertQuery($facetDatasets);

        if ($wpdb->query($query) === false) {
            return false;
        }
        return true;
    }

    private function getInsertQuery(array $facetDatasets) /*: string*/
    {
        global $wpdb;
        $values = array();
        $tableName = $wpdb->prefix . 'guideplugin_index';

        foreach ($facetDatasets as $facetDataset) {
            $values[] = $wpdb->prepare(
                '(%d,%s,%s,%s)',
                $facetDataset->getPostId(),
                $facetDataset->getFacetName(),
                $facetDataset->getFacetValue(),
                $facetDataset->getFacetDisplayValue()
            );
        }

        $query = 'INSERT INTO ' . $tableName . ' (post_id, facet_name, facet_value, facet_display_value) VALUES ';
        $query .= implode(',', $values);

        return $query;
    }

    private function deleteFacetDatasets(array $facetDatasets)
    {
        global $wpdb;
        $query = $this->getDeleteQuery($facetDatasets);

        if ($wpdb->query($query) === false) {
            return false;
        }
        return true;
    }

    private function getDeleteQuery(array $facetDatasets) /*: string*/
    {
        global $wpdb;
        $postIds = $this->getFacetDatasetsPostIds($facetDatasets);
        $tableName = $wpdb->prefix . 'guideplugin_index';

        $query = 'DELETE FROM ' . $tableName . ' WHERE post_id IN( ';
        $query .= implode(',', $postIds);
        $query .= ')';

        return $query;

    }

    private function getFacetDatasetsPostIds(array $facetDatasets) /*: array*/
    {
        $postIds = array();
        if (!empty($facetDatasets)) {
            foreach ($facetDatasets as $facetDataset) {
                array_push($postIds, $facetDataset->getPostId());
            }
        }
        return array_unique($postIds);
    }

    private function getPosts()
    {
        $postTypes = get_post_types(); // all post types of current WP instance
        $acfPostTypes = array('acf-field', 'acf-field-group');
        $guidepluginPostTypes = \GuidepluginHelper::getGuidepluginPostTypes(); // intersected guideplugin post types
        $validPostTypes = array_diff($postTypes, $guidepluginPostTypes, $acfPostTypes);

        $arguments = array(
            'fields' => 'ids',            
            'posts_per_page' => $this->getPostsPerPage(),
            'offset' => $this->getPostsOffset(),
            'post_status' => 'publish',
            'post_type' => $validPostTypes,
            'suppress_filters' => true,
        );
        $arguments = apply_filters('guideplugin/index/query_args', $arguments);
        $posts = get_posts($arguments);

        $this->updateIndexOption(count($posts));

        return $posts;
    }

    private function updateIndexOption(int $postsAmount)
    {
        $indexProcessing = 0;
        $newOffsetOption = 0;
        if ($postsAmount == $this->getPostsPerPage()) {
            $indexProcessing = 1;
            $newOffsetOption = $this->getPostsPerPage() + $this->getPostsOffset();
        }
        update_option('guideplugin_index_processing', $indexProcessing);
        update_option('guideplugin_index_offset', $newOffsetOption);
    }

    private function getPostsOffset()
    {
        $offset = 0;
        $offsetOption = get_option('guideplugin_index_offset');
        if ($offsetOption) {
            return $offsetOption;
        }
        return $offset;
    }

    private function getPostsPerPage()
    {
        $postsPerPage = (is_numeric(get_field('guideplugin_posts_per_index_loop', 'option'))) ? intval(get_field('guideplugin_posts_per_index_loop', 'option')) : 50;
        return apply_filters('guideplugin/index_offset', $postsPerPage);
    }

    private function getFacets()
    {
        $facets = array();
        $arguments = array(
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'guideplugin_facets',
            'suppress_filters' => false,
        );
        $facetsQuery = new \WP_Query($arguments);

        if ($facetsQuery->have_posts()) {
            $facetController = new FacetController();
            while ($facetsQuery->have_posts()) {
                $facetsQuery->the_post();

                $facet = $facetController->buildFacet(get_the_ID());
                if (!is_null($facet)) {
                    array_push($facets, $facet);
                }
            }
        }

        wp_reset_query();

        return $facets;
    }
}
