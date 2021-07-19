<?php //declare(strict_types=1);

namespace Guideplugin\Logic\Logic;

class Logic
{

    private $guideId;
    private $rulesets;

    public function __construct(int $guideId, array $rulesets)
    {
        $this->guideId = $guideId;
        $this->rulesets = $rulesets;
    }

    /**
     * @return mixed
     */
    public function getGuideId()
    {
        return $this->guideId;
    }

    /**
     * @return mixed
     */
    public function getRulesets()
    {
        return $this->rulesets;
    }
}
