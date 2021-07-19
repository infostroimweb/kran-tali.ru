<?php //declare(strict_types=1);

namespace Guideplugin\Result\PostSetBuilder;

class PostSetBuilder
{
    public function getQueryResult(string $where) /*: array*/
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'guideplugin_index';

        $query = 'SELECT post_id FROM ' . $tableName . ' WHERE ' . $where . ' GROUP BY post_id';

        $result = $wpdb->get_col($query);

        if (is_array($result)) {
            return $result;
        }
        return array();
    }

}
