<?php //declare(strict_types=1);

namespace Guideplugin\Upgrade\Version;

use Guideplugin\Upgrade\Upgrade;

class Upgrade_1_0_0_beta_5 implements Upgrade
{
    public function upgrade()
    {
        global $wpdb;
        

        $posts = $this->getPosts();
        $fields = array('show_results_on', 'result_count', 'template_type', 'template_selection', 'show_confetti');

        if (!is_null($posts)) {
            foreach ((array) $fields as $field) {
                $tableName = $wpdb->prefix . 'postmeta';
                $query = 'UPDATE '.$tableName.' 
                SET meta_key = REPLACE(meta_key, \'guideplugin_'.$field.'\', \'guideplugin_result_settings_'.$field.'\'), meta_key = REPLACE(meta_key, \'_guideplugin_'.$field.'\', \'_guideplugin_result_settings_'.$field.'\')
                WHERE post_id IN ('.implode(',', $posts).')';

                $wpdb->query($query);
            }

            foreach ((array) $posts as $post) {
                $tableName = $wpdb->prefix . 'postmeta';

                $query = 'INSERT INTO '.$tableName.' (post_id, meta_key, meta_value)
                VALUES ('.$post.', \'_guideplugin_result_settings\', \'field_5ee0d809df765\' );';
                $wpdb->query($query);
                
                $query = 'INSERT INTO '.$tableName.' (post_id, meta_key, meta_value)
                VALUES ('.$post.', \'guideplugin_result_settings\', \'\' );';
                $wpdb->query($query);
            }
        }

    }

    private function getPosts()
    {
        $arguments = array(
            'post_type' => 'guideplugin_guides',
            'posts_per_page' => -1,
            'suppress_filters' => false,
            'fields' => 'ids'
        );

        $posts = get_posts($arguments);

        if (is_array($posts) && count($posts) > 0) {
            return $posts;
        }
        return;
    }
}
