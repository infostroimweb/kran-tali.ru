<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use Guideplugin\Helper\Template;
use Guideplugin\IndexBuilder\IndexBuilder;

class OptionsHooks
{
    public function initialize()
    {
        add_action('acf/render_field/key=field_5ed0fc60e63db', array($this, 'addOptions'), 10, 1);
    }

    public function addOptions()
    {
        $postCount = (new IndexBuilder())->getIndexStatus();
        include Template::getTemplatePath('templates/options/dashboard.php');
    }
}
