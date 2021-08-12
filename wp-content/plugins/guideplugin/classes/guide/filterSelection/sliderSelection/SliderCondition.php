<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\SliderSelection;

use Guideplugin\Guide\Facet\Facet;

class SliderCondition
{

    private $facet;
    private $rule;

    public function __construct(Facet $facet, string $rule)
    {
        $this->facet = $facet;
        $this->rule = $rule;
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
}
