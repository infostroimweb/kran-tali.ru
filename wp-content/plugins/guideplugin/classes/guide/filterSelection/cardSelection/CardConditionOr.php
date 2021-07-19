<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\CardSelection;

class CardConditionOr
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
