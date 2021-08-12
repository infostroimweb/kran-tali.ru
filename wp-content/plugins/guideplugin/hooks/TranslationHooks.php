<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class TranslationHooks
{
    public function initialize()
    {
        add_action('plugins_loaded', array($this, 'loadPluginTextdomain'));
    }

    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'guideplugin',
            false,
            'guideplugin/languages/'
        );
    }
}
