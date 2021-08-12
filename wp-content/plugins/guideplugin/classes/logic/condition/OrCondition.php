<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Condition;

class OrCondition extends Condition
{
    private $conditionType;

    public function __construct()
    {
        $this->conditionType = 'or';
    }

    /**
     * @return mixed
     */
    public function getConditionType()
    {
        return $this->conditionType;
    }
}
