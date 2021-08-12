<?php
/**
 * GuidePlugin
 *
 * @package           GuidePlugin
 * @author            Sebastian Kraus
 * @copyright         Sebastian Kraus
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       GuidePlugin
 * Plugin URI:        https://www.guideplugin.com
 * Description:       Create awesome guides to help your visitors finding the right content.
 * Version:           1.0.15
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Sebastian Kraus
 * Author URI:        https://www.guideplugin.com
 * Text Domain:       guideplugin
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
//declare(strict_types=1);

namespace Guideplugin;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('Guideplugin')) {
    class Guideplugin
    {
        public function initialize()
        {
            /**
             * Abort early if the PHP version is insuffisant
             */
            if ($this->validatePhpVersion() == false) {return;}

            $this->defineGlobalVariables();
            $this->initializeUpdater();
            $this->loadClasses();
            $this->registerSystemHooks();
            $this->initializeHooks();
        }

        /**
         * Validate PHP version
         */
        private function validatePhpVersion()
        {
            if (version_compare(PHP_VERSION, '7.0', '<')) {
                if (is_admin()) {
                    add_action('admin_notices', function () {
                        ?><div class="notice notice-warning"><p><?php echo 'The GuidePlugin requires PHP version 7.0. Your PHP version is ' . PHP_VERSION; ?></p></div><?php
                    });
                }
                return false;
            }
            return true;
        }

        /**
         * Define all global variables
         */
        private function defineGlobalVariables()
        {
            /**
             * Currently plugin version.
             * Start at version 1.0.0 and use SemVer - https://semver.org
             * Rename this for your plugin and update it as you release new versions.
             */
            define('GUIDEPLUGIN_VERSION', '1.0.15');
            define('GUIDEPLUGIN_PATH', plugin_dir_path(__FILE__));
            define('GUIDEPLUGIN_URL', plugin_dir_url(__FILE__));
        }

        /**
         * Initialize the updater
         */
        private function initializeUpdater()
        {
            require_once GUIDEPLUGIN_PATH . 'lib/wp-package-updater/class-wp-package-updater.php';
            $guideplugin = new \WP_Package_Updater(
                'https://license.guideplugin.com',
                wp_normalize_path(__FILE__),
                wp_normalize_path(GUIDEPLUGIN_PATH),
                true
            );
        }

        /**
         * Load all PHP files for all classes.
         */
        private function loadClasses()
        {
            require_once plugin_dir_path(__FILE__) . 'class-loader.php';
        }

        /**
         * Register activation and deactivation hooks
         */
        private function registerSystemHooks()
        {
            register_activation_hook(__FILE__, array('Guideplugin\Hooks\ActivationHooks', 'initialize'));
            register_deactivation_hook(__FILE__, array('Guideplugin\Hooks\DeactivationHooks', 'initialize'));
        }

        /**
         * Initialize all WP hooks for the plugin
         */
        private function initializeHooks()
        {
            (new \Guideplugin\Hooks\AcfDebugHooks())->initialize();
            (new \Guideplugin\Hooks\AcfHooks())->initialize();
            (new \Guideplugin\Hooks\AcfIntegrationHooks())->initialize();
            (new \Guideplugin\Hooks\AdminHooks())->initialize();
            (new \Guideplugin\Hooks\AjaxHooks())->initialize();
            (new \Guideplugin\Hooks\ConditionFacetHooks())->initialize();
            (new \Guideplugin\Hooks\CronTaskHooks())->initialize();
            (new \Guideplugin\Hooks\Enqueues())->initialize();
            (new \Guideplugin\Hooks\FacetHooks())->initialize();
            (new \Guideplugin\Hooks\FacetSlugHooks())->initialize();
            (new \Guideplugin\Hooks\GutenbergHooks())->initialize();
            (new \Guideplugin\Hooks\IndexHooks())->initialize();
            (new \Guideplugin\Hooks\LogicConditionHooks())->initialize();
            (new \Guideplugin\Hooks\OptionsHooks())->initialize();
            (new \Guideplugin\Hooks\PostTypeHooks())->initialize();
            (new \Guideplugin\Hooks\ShortcodeHooks())->initialize();
            (new \Guideplugin\Hooks\TranslationHooks())->initialize();
            (new \Guideplugin\Hooks\UpgradeHooks())->initialize();
        }
    }

    /**
     * Initialize the plugin
     */
    (new Guideplugin())->initialize();
}
