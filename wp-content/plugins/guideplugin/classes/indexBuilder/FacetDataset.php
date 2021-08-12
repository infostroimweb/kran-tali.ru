<?php //declare(strict_types=1);

namespace Guideplugin\IndexBuilder;

class FacetDataset
{
    private $postId;
    private $facetName;
    private $facetValue;
    private $facetDisplayValue;

    public function __construct(int $postId, string $facetName, string $facetValue, string $facetDisplayValue, array $termIds = array(), $postType = '')
    {
        $this->postId = $postId;
        $this->facetName = $facetName;
        $this->facetValue = $facetValue;
        $this->facetDisplayValue = $facetDisplayValue;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return mixed
     */
    public function getFacetName()
    {
        return $this->facetName;
    }

    /**
     * @return mixed
     */
    public function getFacetValue()
    {
        return $this->facetValue;
    }

    /**
     * @return mixed
     */
    public function getFacetDisplayValue()
    {
        return $this->facetDisplayValue;
    }
}
