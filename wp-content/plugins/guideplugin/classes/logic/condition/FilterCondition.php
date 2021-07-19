<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Condition;

class FilterCondition extends Condition
{

    private $filterId;
    private $rule;
    private $value;
    private $conditionType;

    public function __construct(int $filterId, string $rule, string $value)
    {
        $this->filterId = $filterId;
        $this->rule = $rule;
        $this->value = $value;
        $this->conditionType = 'filter';
    }

    /**
     * @return mixed
     */
    public function getFilterId()
    {
        return $this->filterId;
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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getConditionType()
    {
        return $this->conditionType;
    }
}
