<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Ruleset;

class Ruleset
{

    private $conditions;
    private $actions;

    public function __construct(array $conditions, array $actions)
    {
        $this->conditions = $conditions;
        $this->actions = $actions;
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
    public function getActions()
    {
        return $this->actions;
    }
}
