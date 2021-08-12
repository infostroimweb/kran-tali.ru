<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Action;

class Action
{

    private $filterId;
    private $action;

    public function __construct(int $filterId, string $action)
    {
        $this->filterId = $filterId;
        $this->action = $action;
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
    public function getAction()
    {
        return $this->action;
    }
}
