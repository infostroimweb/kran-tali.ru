<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\CardSelection;

use Guideplugin\Guide\Facet\Facet;

class CardConditionFacet
{

    private $facet;
    private $rule;
    private $behavior;
    private $values;
    private $conditionType;

    public function __construct(Facet $facet, string $rule, string $behavior, array $values)
    {
        $this->facet = $facet;
        $this->rule = $rule;
        $this->behavior = $behavior;
        $this->values = $values;
        $this->conditionType = 'facet';
    }

    /**
     * @return mixed
     */
    public function getFacet()
    {
        return $this->facet;
    }

    /**
     * @return mixed
     */
    public function getRule()
    {
        return $this->rule;
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
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return mixed
     */
    public function getConditionType()
    {
        return $this->conditionType;
    }
}
