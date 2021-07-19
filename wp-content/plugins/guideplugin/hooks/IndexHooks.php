<?php //declare(strict_types=1);

namespace Guideplugin\Hooks;

use Guideplugin\IndexBuilder\IndexBuilder;

class IndexHooks
{
    public function initialize()
    {
        add_action('acf/save_post', array($this, 'indexPost'));
    }

    public function indexPost($postId)
    {
        if (is_int($postId) && $this->isValidPostType($postId)) {
            (new IndexBuilder())->buildIndex(array($postId));
        }
    }

    private function isValidPostType(int $postId)
    {
        $postType = get_post_type($postId);

        $postTypes = get_post_types();
        $acfPostTypes = array('acf-field', 'acf-field-group');
        $guidepluginPostTypes = \GuidepluginHelper::getGuidepluginPostTypes();
        $validPostTypes = array_diff($postTypes, $guidepluginPostTypes, $acfPostTypes);

        return (in_array($postType, $validPostTypes));
    }
}
