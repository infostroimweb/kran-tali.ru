<?php //declare(strict_types=1);

namespace Guideplugin\Guide\Facet;

use Guideplugin\Guide\Facet\Facet;

class FacetController
{
    public function buildFacet(int $facetId)
    {
        if ($facetId !== 0 && get_post_status($facetId) === 'publish') {

            $facetTitle = get_the_title($facetId);
            $facetSlug = get_field('guideplugin_slug', $facetId);
            $source = get_field('guideplugin_source', $facetId);

            $sourceComponents = explode('_', $source, 2);
            $facetType = (isset($sourceComponents[0])) ? $sourceComponents[0] : '';
            $facetSource = (isset($sourceComponents[1])) ? $sourceComponents[1] : '';

            if (is_int($facetId) && is_string($facetTitle) && is_string($facetSlug) && is_string($facetType) && is_string($facetSource)) {
                return (new Facet($facetId, $facetTitle, $facetSlug, $facetType, $facetSource));
            }
        }
        return null;
    }
}
