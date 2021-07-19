<?php //declare(strict_types=1);

namespace Guideplugin\Design;

use Guideplugin\Guide\Guide\Guide;

class DesignController
{
    public function includeStyles(Guide $guide)
    {
        $customStyles = array();
        $design = $this->getDesignGroup($guide);
        if (!empty($design)) {
            array_push($customStyles, $this->addStylesGuide($guide, $design));
            array_push($customStyles, $this->addStylesGuideBackground($guide, $design));
            array_push($customStyles, $this->addStylesFilter($guide, $design));
            array_push($customStyles, $this->addStylesColors($guide, $design));
            array_push($customStyles, $this->addStylesResult($guide, $design));

            wp_register_style('guideplugin-guide-' . $guide->getId() . '-inline-style', false, array('guideplugin'));
            wp_enqueue_style('guideplugin-guide-' . $guide->getId() . '-inline-style');
            wp_add_inline_style('guideplugin-guide-' . $guide->getId() . '-inline-style', implode('', $customStyles));
        }
        return;
    }
    
    private function getDesignGroup($guide) /*: array */
    {
        $designType = get_field('guideplugin_design_type', $guide->getId());
        switch ($designType) {
            case 'preset_design':
                return get_field('guideplugin_design_group', get_field('guideplugin_design_selection', $guide->getId()));
                break;

            case 'custom_design':
                return get_field('guideplugin_custom_design_group', $guide->getId());
                break;

            default:
                # code...
                break;
        }
        return array();
    }

    private function addStylesGuide(Guide $guide, array $design) /*: string*/
    {
        ob_start();
        ?>
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-title {
            <?php echo ($design['guide_title_color']) ? 'color: ' . $design['guide_title_color'] . ';' : ''; ?>
            <?php echo ($design['guide_title_font_size']) ? 'font-size: ' . $design['guide_title_font_size'] . 'rem;' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-description p {
            <?php echo ($design['guide_description_color']) ? 'color: ' . $design['guide_description_color'] . ';' : ''; ?>
            <?php echo ($design['guide_description_font_size']) ? 'font-size: ' . $design['guide_description_font_size'] . 'rem;' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-results-headline {
            <?php echo ($design['guide_title_color']) ? 'color: ' . $design['guide_title_color'] . ';' : ''; ?>
        }
        <?php
        return ob_get_clean();
    }

    private function addStylesGuideBackground(Guide $guide, array $design) /*: string*/
    {
        $deg = ($design['background_direction']) ? $design['background_direction'] . 'deg' : '60deg';
        $colorStart = ($design['background_color_start']) ? $design['background_color_start'] : 'transparent';
        $colorEnd = ($design['background_color_end']) ? $design['background_color_end'] : 'transparent';

        $image = '';
        if (isset(wp_get_attachment_image_src($design['background_image'], 'full')[0])) {
            $image = 'url(' . wp_get_attachment_image_src($design['background_image'], 'full')[0] . '),';
        }

        $backgroundTilt = ($design['background_tilt']) ? floatval($design['background_tilt']) : '0';
        $backgroundTiltPolygon = 'polygon(0 0,100% 0,100% 100%,0 calc(100% - ' . abs($backgroundTilt) . 'vw))';

        if ($backgroundTilt < 0) {
            $backgroundTiltPolygon = 'polygon(0 0,100% 0,100% calc(100% - ' . abs($backgroundTilt) . 'vw),0 100%)';
        }

        ob_start();
        ?>
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guideplugin-background {
            background-image: <?php echo $image; ?>linear-gradient(<?php echo $deg . ',' . $colorStart . ',' . $colorEnd; ?>);
            clip-path: <?php echo $backgroundTiltPolygon; ?>;
        }
        <?php
        return ob_get_clean();
    }

    private function addStylesFilter(Guide $guide, array $design) /*: string*/
    {
        ob_start();
        ?>
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-filter-title {
            <?php echo ($design['filter_title_color']) ? 'color: ' . $design['filter_title_color'] . ';' : ''; ?>
            <?php echo ($design['filter_title_font_size']) ? 'font-size: ' . $design['filter_title_font_size'] . 'rem;' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-filter-description p {
            <?php echo ($design['filter_description_color']) ? 'color: ' . $design['filter_description_color'] . ';' : ''; ?>
            <?php echo ($design['filter_description_font_size']) ? 'font-size: ' . $design['filter_description_font_size'] . 'rem;' : ''; ?>
        }
        <?php
        return ob_get_clean();
    }

    private function addStylesColors(Guide $guide, array $design) /*: string*/
    {
        ob_start();
        ?>
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-progress .guide-progress-bar {
            <?php echo ($design['slider_progress_color']) ? 'background-color: ' . $design['slider_progress_color'] . ';' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-progress.guide-progress-loading .guide-progress-bar::after {
            <?php echo ($design['slider_progress_color']) ? 'background: repeating-linear-gradient(to right, ' . $design['slider_progress_color'] . ' 0%, ' . $design['slider_progress_color'] . ' 40%, ' . $this->adjustColor($design['slider_progress_color'], 0.7) . ' 50%, ' . $design['slider_progress_color'] . ' 60%, ' . $design['slider_progress_color'] . ' 100%);' : ''; ?>
            background-size: 200% auto;
        }

        /**
         * Buttons
         */
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-button.guide-button-primary {
            <?php echo ($design['basic_color']) ? 'background-color: ' . $design['basic_color'] . ';' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-button.guide-button-primary:hover {
            <?php echo ($design['basic_color']) ? 'background-color: ' . $this->adjustColor($design['basic_color'], -0.2) . ';' : ''; ?>
            <?php echo ($design['basic_color']) ? 'box-shadow: 0 2px 4px ' . $this->adjustColor($design['basic_color'], 0.5) . ';' : ''; ?>
        }


        /**
         * Spinner
         */
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guideplugin-spinner > .guideplugin-spinner-container > .guideplugin-spinner-inner {
            <?php echo ($design['basic_color']) ? 'stroke: ' . $design['basic_color'] . ';' : ''; ?>
        }

        /**
         * Cards
         */
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-card.selected {
            <?php echo ($design['basic_color']) ? 'border: 3px solid ' . $design['basic_color'] . ';' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-card[data-card-type="radio"]:hover::after,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-card[data-card-type="checkbox"]:hover::after,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-card.selected[data-card-type="radio"]::after,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-card.selected[data-card-type="checkbox"]::after {
            <?php echo ($design['basic_color']) ? 'color: ' . $design['basic_color'] . ';' : ''; ?>
        }


        /**
         * IRS Slider
         */
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-handle>i:first-child,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-bar,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-from,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-to,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-single {
            <?php echo ($design['basic_color']) ? 'background-color: ' . $this->adjustColor($design['basic_color'], 0.2) . '!important;' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-from:before,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-to:before,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-single:before {
            <?php echo ($design['basic_color']) ? 'border-top-color: ' . $this->adjustColor($design['basic_color'], 0.2) . '!important;' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-handle,
        .guideplugin.guide-<?php echo $guide->getId(); ?> .irs--flat .irs-handle:hover {
            <?php echo ($design['basic_color']) ? 'border-color: ' . $design['basic_color'] . '!important;' : ''; ?>
        }
        <?php
        return ob_get_clean();
    }

    private function addStylesResult(Guide $guide, array $design) /*: string*/
    {
        ob_start();
        ?>
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-result-item .guide-result-template-title {
            <?php echo ($design['result_title_color']) ? 'color: ' . $design['result_title_color'] . ';' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-result-item .guide-result-highlight-label {
            <?php echo ($design['highlight_badge_text_color']) ? 'color: ' . $design['highlight_badge_text_color'] . ';' : ''; ?>
            <?php echo ($design['highlight_badge_background_color']) ? 'background-color: ' . $design['highlight_badge_background_color'] . ';' : ''; ?>
        }
        .guideplugin.guide-<?php echo $guide->getId(); ?> .guide-result-item.guide-result-highlight {
            <?php echo ($design['highlight_border_thickness']) ? 'border: ' . $design['highlight_border_thickness'] . 'px solid #fff;' : ''; ?>
            <?php echo ($design['highlight_border_color']) ? 'border-color: ' . $design['highlight_border_color'] . ';' : ''; ?>
            <?php echo ($design['highlight_border_color']) ? 'box-shadow: 0 5px 20px -10px ' . $design['highlight_border_color'] . ';' : ''; ?>
        }
        <?php
        return ob_get_clean();
    }

    private function adjustColor($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as &$color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }
}