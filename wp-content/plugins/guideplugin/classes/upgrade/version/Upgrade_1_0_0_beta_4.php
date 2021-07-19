<?php //declare(strict_types=1);

namespace Guideplugin\Upgrade\Version;

use Guideplugin\Upgrade\Upgrade;

class Upgrade_1_0_0_beta_4 implements Upgrade
{
    public function upgrade()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'guideplugin_index';

        $indexQuery = 'ALTER TABLE ' . $tableName . '
        DROP INDEX post_id,
        DROP INDEX post_id_meta,
        DROP INDEX post_id_post_type,
        DROP INDEX post_id_term_ids,
        DROP INDEX facet_meta,
        DROP INDEX facet_post_type,
        DROP INDEX facet_term_ids,
        ADD INDEX post (post_id),
        ADD INDEX facet (facet_name, facet_value),
        ADD INDEX post_facet (post_id, facet_name, facet_value)';

        $columnQuery = 'ALTER TABLE ' . $tableName . ' DROP term_ids, DROP post_type';

        $wpdb->query($indexQuery);
    	$wpdb->query($columnQuery);
    }
}
