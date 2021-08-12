<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\SliderSelection;

use Guideplugin\Guide\FilterSelection\FilterSelection;

class SliderSelection extends FilterSelection
{

    private $type;
    private $behavior;
    private $conditions;
    private $lowerLimit;
    private $upperLimit;
    private $prefix;
    private $suffix;
    private $images;
    private $imageWidth;
    private $imageHeight;
    private $prettifyValues;
    private $resultCount;

    public function __construct(string $type, string $behavior, array $conditions, string $lowerLimit, string $upperLimit, string $prefix, string $suffix, array $images, int $imageWidth, int $imageHeight, bool $prettifyValues, int $resultCount = null)
    {
        $this->type = $type;
        $this->behavior = $behavior;
        $this->conditions = $conditions;
        $this->lowerLimit = $lowerLimit;
        $this->upperLimit = $upperLimit;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->images = $images;
        $this->imageWidth = $imageWidth;
        $this->imageHeight = $imageHeight;
        $this->prettifyValues = $prettifyValues;
        $this->resultCount = $resultCount;
    }

    /**
     * @param mixed $lowerLimit
     *
     * @return self
     */
    public function setLowerLimit($lowerLimit)
    {
        $this->lowerLimit = $lowerLimit;

        return $this;
    }

    /**
     * @param mixed $upperLimit
     *
     * @return self
     */
    public function setUpperLimit($upperLimit)
    {
        $this->upperLimit = $upperLimit;

        return $this;
    }

    /**
     * @param mixed $presetValue
     *
     * @return self
     */
    public function setPresetValue($presetValue)
    {
        $this->presetValue = $presetValue;

        return $this;
    }

    /**
     * @param mixed $resultCount
     *
     * @return self
     */
    public function setResultCount($resultCount)
    {
        $this->resultCount = $resultCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getBehavior()
    {
        return $this->behavior;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return mixed
     */
    public function getLowerLimit()
    {
        return $this->lowerLimit;
    }

    /**
     * @return mixed
     */
    public function getUpperLimit()
    {
        return $this->upperLimit;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return mixed
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }

    /**
     * @return mixed
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }

    /**
     * @return mixed
     */
    public function getPrettifyValues()
    {
        return $this->prettifyValues;
    }

    /**
     * @return mixed
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }
}
