<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use Guideplugin\Helper\MultilanguageHelper;
use Guideplugin\IndexBuilder\IndexBuilder;
use Guideplugin\Result\Processor\NextFilterProcessor;
use Guideplugin\Result\Processor\ResultProcessor;
use Guideplugin\Result\Processor\UpdateFilterProcessor;

class AjaxHooks
{
    public function initialize()
    {
        // Guide request
        add_action('wp_ajax_nopriv_guideplugin_guide_initialize', array($this, 'guideInitialize'));
        add_action('wp_ajax_guideplugin_guide_initialize', array($this, 'guideInitialize'));

        add_action('wp_ajax_nopriv_guideplugin_guide_updated_filter', array($this, 'guideUpdatedFilter'));
        add_action('wp_ajax_guideplugin_guide_updated_filter', array($this, 'guideUpdatedFilter'));

        add_action('wp_ajax_nopriv_guideplugin_guide_next_filter', array($this, 'guideNextFilter'));
        add_action('wp_ajax_guideplugin_guide_next_filter', array($this, 'guideNextFilter'));

        add_action('wp_ajax_nopriv_guideplugin_guide_results', array($this, 'guideResults'));
        add_action('wp_ajax_guideplugin_guide_results', array($this, 'guideResults'));

        add_action('wp_ajax_guideplugin_guide_purge_index', array($this, 'purgeIndex'));
        add_action('wp_ajax_guideplugin_guide_rebuild_index', array($this, 'rebuildIndex'));
    }

    public function guideInitialize()
    {
        if (wp_verify_nonce($_POST['nonce'], 'guideplugin-security') && defined('DOING_AJAX') && DOING_AJAX) {

            (new MultilanguageHelper())->setLanguage(sanitize_text_field($_POST['lang']));

            $guideId = intval($_POST['data']['guide_id']);
            $guideFilter = (new UpdateFilterProcessor())->updatedFilter($guideId);

            if (empty($guideFilter)) {
                wp_send_json_error();
            }
            wp_send_json_success(array('current_filter' => $guideFilter['current_filter']));
        }
    }

    public function guideUpdatedFilter()
    {
        if (wp_verify_nonce($_POST['nonce'], 'guideplugin-security') && defined('DOING_AJAX') && DOING_AJAX) {

            (new MultilanguageHelper())->setLanguage($_POST['lang']);

            $guideId = intval($_POST['data']['guide_id']);
            $currentIndex = intval($_POST['data']['current_index']);
            $values = (!empty($_POST['data']['filter_values'])) ? $_POST['data']['filter_values'] : array();

            $updatedFilters = (new UpdateFilterProcessor())->updatedFilter($guideId, $currentIndex, $values);

            if (empty($updatedFilters)) {
                wp_send_json_error();
            }
            wp_send_json_success(array('current_filter' => $updatedFilters['current_filter'], 'filter_count' => $updatedFilters['filter_count'], 'processing_time' => $updatedFilters['processing_time']));
        }
    }

    public function guideNextFilter()
    {
        if (wp_verify_nonce($_POST['nonce'], 'guideplugin-security') && defined('DOING_AJAX') && DOING_AJAX) {

            (new MultilanguageHelper())->setLanguage($_POST['lang']);

            $guideId = intval($_POST['data']['guide_id']);
            $currentIndex = intval($_POST['data']['current_index']);
            $values = (!empty($_POST['data']['filter_values'])) ? $_POST['data']['filter_values'] : array();

            $updatedFilters = (new NextFilterProcessor())->nextFilter($guideId, $currentIndex, $values);

            if (empty($updatedFilters)) {
                wp_send_json_error();
            }
            wp_send_json_success(array('next_filter' => $updatedFilters['next_filter'], 'filter_count' => $updatedFilters['filter_count'], 'processing_time' => $updatedFilters['processing_time']));
        }
    }

    public function guideResults()
    {
        if (wp_verify_nonce($_POST['nonce'], 'guideplugin-security') && defined('DOING_AJAX') && DOING_AJAX) {

            (new MultilanguageHelper())->setLanguage($_POST['lang']);

            $guideId = intval($_POST['data']['guide_id']);
            $values = (!empty($_POST['data']['filter_values'])) ? $_POST['data']['filter_values'] : array();
            $page = intval($_POST['data']['page']);

            $response = (new ResultProcessor())->getResults($guideId, $values, $page);

            if (empty($response)) {
                wp_send_json_error();
            }
            wp_send_json_success($response);
        }
    }

    public function purgeIndex()
    {
        if (wp_verify_nonce($_POST['nonce'], 'guideplugin-security') && defined('DOING_AJAX') && DOING_AJAX) {

            (new IndexBuilder())->purgeIndex();
            (new IndexBuilder())->buildIndex();

            wp_send_json_success();
        }
    }

    public function rebuildIndex()
    {
        if (wp_verify_nonce($_POST['nonce'], 'guideplugin-security') && defined('DOING_AJAX') && DOING_AJAX) {

            (new IndexBuilder())->buildIndex();

            wp_send_json_success();
        }
    }
}
