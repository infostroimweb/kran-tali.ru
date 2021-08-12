<?php //declare(strict_types=1);

namespace Guideplugin\Guide\ValueSet;

use Guideplugin\Guide\ValueSet\ValueSet;

class ValueSetController
{
    public function buildValueSet(string $uniqueFilterIdentifier, array $values) /*: ValueSet*/
    {
        return (new ValueSet($uniqueFilterIdentifier, $values));
    }
}
