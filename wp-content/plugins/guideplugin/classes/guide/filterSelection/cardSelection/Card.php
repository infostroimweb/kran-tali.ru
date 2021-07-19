<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\CardSelection;

class Card
{

    private $image;
    private $label;
    private $facetConditions;
    private $uniqueIdentifier;
    private $resultCount;

    public function __construct($image, string $label, array $facetConditions, string $uniqueIdentifier, $resultCount = 0)
    {
        $this->image = $image;
        $this->label = $label;
        $this->facetConditions = $facetConditions;
        $this->uniqueIdentifier = $uniqueIdentifier;
        $this->resultCount = $resultCount;
    }

    /**
     * @return mixed
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getFacetConditions()
    {
        return $this->facetConditions;
    }

    /**
     * @return mixed
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }

    /**
     * @param mixed $resultCount
     *
     * @return self
     */
    public function setResultCount(int $resultCount)
    {
        $this->resultCount = $resultCount;

        return $this;
    }
}
