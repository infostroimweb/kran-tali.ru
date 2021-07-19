<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

class FacetSlugHooks
{
    public function initialize()
    {
        add_action('trashed_post', array($this, 'disableTrash'));
        add_action('edit_form_after_editor', array($this, 'addPostIdHiddenField'));
        add_filter('acf/validate_value/key=field_5dc972896abe1', array($this, 'validateFacetSlugString'), 10, 4); // Facet slug validation
        add_filter('acf/validate_value/key=field_5dc972896abe1', array($this, 'validateFacetSlugUniqueness'), 10, 4); // Facet slug validation
    }

    public function disableTrash($postId)
    {
        if (get_post_type($postId) == 'guideplugin_facets') {
            wp_delete_post($postId, true);
        }
    }

    public function addPostIdHiddenField($post)
    {
        printf('<input type="hidden" name="acf[%s][post_id]" value="%d"/>', 'guideplugin_hidden_post_id', $post->ID);
    }

    public function validateFacetSlugString($valid, $value, $field, $input_name)
    {
        if ($valid !== true) {
            return $valid;
        }

        if ((preg_match('/[^a-z0-9_]/', $value))) {
            return __('Only use lowercase (a-z), numbers (0-9) and underscore for slug.', 'guideplugin');
        }
        return $valid;
    }

    public function validateFacetSlugUniqueness($valid, $value, $field, $input_name)
    {
        if ($valid !== true) {
            return $valid;
        }

        $postId = $_POST['acf']['guideplugin_hidden_post_id']['post_id'];

        $arguments = array(
            'post_type' => 'guideplugin_facets',
            'post_status' => 'publish',
            'post__not_in' => array($postId),
            'suppress_filters' => false,
            'meta_query' => array(
                array(
                    'key' => 'slug',
                    'value' => $value,
                    'compare' => '=',
                ),
            ),
        );

        $facets = get_posts($arguments);

        if (count($facets) !== 0) {
            return __('This facet slug already exists. Please use unique slug.', 'guideplugin');
        }
        return $valid;
    }
}
