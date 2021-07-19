<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use Guideplugin\Helper\AssetsHandler;

class ShortcodeHooks
{
    public function initialize()
    {
        add_shortcode('guideplugin_guide', array($this, 'addGuide'));
    }

    public function addGuide($atts, $content)
    {
        if (!is_admin()) {

            $time_pre = microtime(true);

            (new AssetsHandler())->enqueueGuideAssets();

            if (!isset($atts['id']) || !is_numeric($atts['id'])) {
                return '';
            }

            $guideId = intval($atts['id']);
            $guide = (new \Guideplugin\Guide\Guide\GuideController())->buildGuide($guideId);

            if (!is_null($guide)) {
                return \GuidepluginHelper::template_guide($guide);
            }

            $time_post = microtime(true);
            $exec_time = $time_post - $time_pre;
            #echo 'Time: '.$exec_time;

        }
    }
}
