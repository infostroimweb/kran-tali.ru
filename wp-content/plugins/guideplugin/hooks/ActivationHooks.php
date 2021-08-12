<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class ActivationHooks
{
    public static function initialize()
    {
        self::createIndexTable();
    }

    public static function createIndexTable()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'guideplugin_index';
        $charsetCollate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $tableName (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			post_id int(10) NOT NULL,
			facet_name varchar(50) NOT NULL,
			facet_value varchar(50) NOT NULL,
			facet_display_value varchar(50) NOT NULL,
			PRIMARY KEY  (id),
			INDEX post (post_id),
	        INDEX facet (facet_name, facet_value),
	        INDEX post_facet (post_id, facet_name, facet_value)
		) $charsetCollate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
