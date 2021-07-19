<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class AcfDebugHooks
{
    public function initialize()
    {
        add_filter('acf/load_field/key=field_5ec66457976d8', array($this, 'setDebugInformation')); // load debug information
    }

    public function setDebugInformation($field)
    {
        $information = array(
            'WordPress' => $this->getWordPressInformation(),
            'Server' => $this->getServerInformation(),
            'Plugins' => $this->getPluginInformation(),
            'Themes' => $this->getThemeInformation(),
            'Paths' => $this->getPathInformation(),
        );

        $field['value'] = json_encode($information, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $field['readonly'] = 1;

        return $field;
    }

    private function getPathInformation()
    {
        return array(
            'Home URL' => home_url(),
            'Site URL' => site_url(),
            'Document Root' => $_SERVER['DOCUMENT_ROOT'],
            'Template URL' => get_template_directory_uri(),
            'Stylesheet URL' => get_stylesheet_directory_uri(),
            'Template Ordner' => get_template_directory(),
            'Stylesheet Ordner' => get_stylesheet_directory(),
            'Uploads Ordner' => wp_upload_dir(),
        );
    }

    private function getWordPressInformation()
    {
        return array(
            'WP Version' => get_bloginfo('version'),
            'WP Multisite' => (is_multisite() ? 'true' : 'false'),
            'WP Memory Limit' => size_format($this->formatSize(WP_MEMORY_LIMIT)),
            'Server Memory Limit' => (ini_get('memory_limit') ? size_format($this->formatSize(ini_get('memory_limit'))) : '0'),
            'WP Debug Mode' => (defined('WP_DEBUG') && WP_DEBUG ? 'true' : 'false'),
            'Language' => get_locale(),
        );
    }

    private function getServerInformation()
    {
        global $wpdb;

        return array(
            'Server' => esc_html($_SERVER['SERVER_SOFTWARE']),
            'PHP Version' => phpversion(),
            'cURL' => (extension_loaded('curl') != function_exists('curl_version') ? 'false' : 'true'),
            'SOAP' => (extension_loaded('soap') == false ? 'false' : 'true'),
            'PHP Allow URL Fopen' => (ini_get('allow_url_fopen') == false ? 'false' : 'true'),
            'PHP Post Max Size' => size_format($this->formatSize(ini_get('post_max_size'))),
            'PHP Time Limit' => ini_get('max_execution_time'),
            'PHP Max Input Vars' => ini_get('max_input_vars'),
            'MySQL Version' => $wpdb->db_version(),
            'Max Upload Size' => size_format(wp_max_upload_size()),
        );
    }

    private function getPluginInformation()
    {
        $information = array();
        $activePlugins = (array) get_option('active_plugins', array());

        if (is_multisite()) {
            $activePlugins = array_merge($activePlugins, get_site_option('active_sitewide_plugins', array()));
        }

        foreach ($activePlugins as $activePlugin) {
            $pluginData = @get_plugin_data(WP_PLUGIN_DIR . '/' . $activePlugin);

            if (!empty($pluginData['Name'])) {
                $information[$pluginData['Name']] = array(
                    'version' => $pluginData['Version'],
                    'PluginURI' => $pluginData['PluginURI'],
                );
            }
        }

        return $information;
    }

    private function getThemeInformation()
    {
        $information = array();
        $themes = wp_get_themes();
        $activeTheme = wp_get_theme();

        foreach ($themes as $theme) {
            $pluginData = @get_plugin_data(WP_PLUGIN_DIR . '/' . $activePlugin);

            $information[$theme->get('Name')] = array(
                'version' => $theme->get('Version'),
                'active' => ($theme->get('Name') === $activeTheme->get('Name')) ? true : false,
            );
        }

        return $information;
    }

    private function formatSize($size)
    {
        $unit = substr($size, -1);
        $multipliedSize = substr($size, 0, -1);
        switch (strtoupper($unit)) {
            case 'P':
                $multipliedSize *= 1024;
            case 'T':
                $multipliedSize *= 1024;
            case 'G':
                $multipliedSize *= 1024;
            case 'M':
                $multipliedSize *= 1024;
            case 'K':
                $multipliedSize *= 1024;
        }
        return $multipliedSize;
    }
}
