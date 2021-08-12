<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class UpgradeHooks
{
    public function initialize()
    {
        add_action('plugins_loaded', array($this, 'upgrade'));
    }

    public function upgrade()
    {
        $currentVersion = $this->getCurrentVersion();

        $upgradeVersions = array(
            '0.0.8',
            '0.0.10',
            '1.0.0-beta.4',
            '1.0.0-beta.5'
        );

        foreach ($upgradeVersions as $upgradeVersion) {
            $this->upgradeVersion($currentVersion, $upgradeVersion);
        }

        update_option('guideplugin_version', GUIDEPLUGIN_VERSION);
    }

    private function upgradeVersion(string $currentVersion, string $upgradeVersion)
    {
        $upgradeVersionString = str_replace('.', '_', $upgradeVersion);
        $upgradeVersionString = str_replace('-', '_', $upgradeVersionString);
        $upgradeVersionClass = 'Guideplugin\\Upgrade\\Version\\Upgrade_' . $upgradeVersionString;

        if (version_compare($currentVersion, $upgradeVersion, '<') && file_exists(GUIDEPLUGIN_PATH . 'classes/upgrade/version/Upgrade_' . $upgradeVersionString . '.php')) {
            include_once GUIDEPLUGIN_PATH . 'classes/upgrade/version/Upgrade_' . $upgradeVersionString . '.php';
            (new $upgradeVersionClass())->upgrade();
        }
    }

    private function getCurrentVersion()
    {
        if (get_option('guideplugin_version') === false) {
            update_option('guideplugin_version', GUIDEPLUGIN_VERSION);
        }
        return get_option('guideplugin_version');
    }
}
