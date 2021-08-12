<?php //declare(strict_types=1);

namespace Guideplugin\Guide\ValueSet;

class ValueSet
{
    private $uniqueFilterIdentifier;
    private $values;

    public function __construct(string $uniqueFilterIdentifier, array $values)
    {
        $this->uniqueFilterIdentifier = $uniqueFilterIdentifier;
        $this->values = $values;
    }

    /**
     * @return mixed
     */
    public function getUniqueFilterIdentifier()
    {
        return $this->uniqueFilterIdentifier;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }
}
