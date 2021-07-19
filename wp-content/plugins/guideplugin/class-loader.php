<?php
/**
 * Composer autoload
 */
require GUIDEPLUGIN_PATH . 'vendor/autoload.php';


/**
 * Include Advanced Custom Fields
 */
require_once GUIDEPLUGIN_PATH . 'vendor/advanced-custom-fields/advanced-custom-fields-pro/acf.php';


/**
 * Include class files
 */
include_once GUIDEPLUGIN_PATH . 'classes/upgrade/Upgrade.php';

include_once GUIDEPLUGIN_PATH . 'classes/guide/valueSet/ValueSet.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/valueSet/ValueSetController.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/facet/Facet.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/facet/FacetController.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/FilterSelection.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/cardSelection/Card.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/cardSelection/CardConditionFacet.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/cardSelection/CardConditionOr.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/cardSelection/CardSelection.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/cardSelection/CardSelectionController.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/sliderSelection/SliderCondition.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/sliderSelection/SliderSelection.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filterSelection/sliderSelection/SliderSelectionController.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filter/Filter.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/filter/FilterController.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/guide/Guide.php';
include_once GUIDEPLUGIN_PATH . 'classes/guide/guide/GuideController.php';

include_once GUIDEPLUGIN_PATH . 'classes/design/DesignController.php';

include_once GUIDEPLUGIN_PATH . 'classes/logic/logic/Logic.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/logic/LogicController.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/ruleset/Ruleset.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/ruleset/RulesetController.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/condition/Condition.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/condition/FilterCondition.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/condition/OrCondition.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/action/Action.php';
include_once GUIDEPLUGIN_PATH . 'classes/logic/logicProcessor/LogicProcessor.php';

include_once GUIDEPLUGIN_PATH . 'classes/indexBuilder/IndexBuilder.php';
include_once GUIDEPLUGIN_PATH . 'classes/indexBuilder/FacetDataset.php';
include_once GUIDEPLUGIN_PATH . 'classes/indexBuilder/FacetValueSet.php';
include_once GUIDEPLUGIN_PATH . 'classes/indexBuilder/AcfValueSetsController.php';
include_once GUIDEPLUGIN_PATH . 'classes/indexBuilder/FacetDatasetController.php';

include_once GUIDEPLUGIN_PATH . 'classes/result/ResultItemDataController.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/postSetBuilder/PostSetBuilder.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/postSetBuilder/CardPostSetBuilder.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/postSetBuilder/SliderPostSetBuilder.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/postSet/PostBaseController.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/postSet/PostSetController.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/limit/CardCount.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/limit/SliderBoundaries.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/processor/Processor.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/processor/UpdateFilterProcessor.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/processor/NextFilterProcessor.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/processor/ResultProcessor.php';
include_once GUIDEPLUGIN_PATH . 'classes/result/query/QueryController.php';

include_once GUIDEPLUGIN_PATH . 'classes/helper/AssetsHandler.php';
include_once GUIDEPLUGIN_PATH . 'classes/helper/MultilanguageHelper.php';
include_once GUIDEPLUGIN_PATH . 'classes/helper/Template.php';

include_once GUIDEPLUGIN_PATH . 'classes/GuidepluginHelper.php';


/**
 * Include hook files
 */
include_once GUIDEPLUGIN_PATH . 'hooks/AcfDebugHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/AcfHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/AcfIntegrationHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/ActivationHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/AdminHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/AjaxHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/ConditionFacetHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/CronTaskHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/DeactivationHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/Enqueues.php';
include_once GUIDEPLUGIN_PATH . 'hooks/FacetHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/FacetSlugHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/GutenbergHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/IndexHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/LogicConditionHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/OptionsHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/PostTypeHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/ShortcodeHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/TranslationHooks.php';
include_once GUIDEPLUGIN_PATH . 'hooks/UpgradeHooks.php';


