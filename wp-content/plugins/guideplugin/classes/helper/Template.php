<?php //declare(strict_types=1);

namespace Guideplugin\Helper;

class Template
{

    public static function getTemplatePath(string $templateUrl) /*: string*/
    {
        if (empty($templateUrl)) {
            return '';
        }
        return GUIDEPLUGIN_PATH . $templateUrl;
    }

}
