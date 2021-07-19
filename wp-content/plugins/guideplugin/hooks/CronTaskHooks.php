<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use Guideplugin\IndexBuilder\IndexBuilder;

class CronTaskHooks
{
    public function initialize()
    {
        add_filter('cron_schedules', array($this, 'addCrontaskSchedule'));
        add_action('wp', array($this, 'addNameCrontask'));
        add_action('guideplugin_update_index_loop', array($this, 'updateIndexLoop'));
    }

    public function addCrontaskSchedule($schedules)
    {
        $schedules['guideplugin_every_1_minutes'] = array(
            'interval' => 60,
            'display' => __('Every 1 minutes'),
        );
        return $schedules;
    }

    public function addNameCrontask()
    {
        if (!wp_next_scheduled('guideplugin_update_index_loop')) {
            wp_schedule_event(time(), 'guideplugin_every_1_minutes', 'guideplugin_update_index_loop');
        }
    }

    public function updateIndexLoop()
    {
        if (get_option('guideplugin_index_processing') == 1) {
            (new IndexBuilder())->buildIndex();
        }
    }
}
