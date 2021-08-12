<?php //declare(strict_types=1);

namespace Guideplugin\IndexBuilder;

class FacetValueSet
{

    private $facetValue;
    private $facetDisplayValue;

    public function __construct(string $facetValue, string $facetDisplayValue)
    {
        $this->facetValue = $facetValue;
        $this->facetDisplayValue = $facetDisplayValue;
    }

    /**
     * @return mixed
     */
    public function getFacetValue()
    {
        return $this->facetValue;
    }

    /**
     * @param mixed $facetValue
     *
     * @return self
     */
    public function setFacetValue($facetValue)
    {
        $this->facetValue = $facetValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacetDisplayValue()
    {
        return $this->facetDisplayValue;
    }

    /**
     * @param mixed $facetDisplayValue
     *
     * @return self
     */
    public function setFacetDisplayValue($facetDisplayValue)
    {
        $this->facetDisplayValue = $facetDisplayValue;

        return $this;
    }
}
