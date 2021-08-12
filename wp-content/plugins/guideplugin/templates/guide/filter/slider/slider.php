<div class="guide-slider-container">
    <?php 
    $values = $filter->getValues();
    $slider = $filter->getSelection();
    ?>

    <div class="guide-slider-image-container">
        <?php 
        if (!empty($slider->getImages())) {
            foreach ($slider->getImages() as $image) { ?>
                <div class="guide-slider-image">
                    <img class="" src="<?php echo wp_get_attachment_image_src($image, 'medium')[0];?>" style="max-width: <?php echo $slider->getImageWidth();?>px; max-height: <?php echo $slider->getImageHeight();?>px;" />
                </div>
            <?php
            }
        }
        ?>
    </div>

    <?php 
    if ($slider->getType() == "range") {
        $from = (isset($values[0]) && is_numeric($values[0])) ? $values[0] : '';
        $to = (isset($values[1]) && is_numeric($values[1])) ? $values[1] : '';
        ?>
    	<input type="text" class="js-range-slider" name="<?php echo $filter->getUniqueIdentifier();?>" value=""
            data-type="double"
            data-min="<?php echo $slider->getLowerLimit();?>"
            data-max="<?php echo $slider->getUpperLimit();?>"
            data-from="<?php echo $from;?>"
            data-to="<?php echo $to;?>"
            data-prefix="<?php echo $slider->getPrefix();?>"
            data-postfix="<?php echo $slider->getSuffix();?>"
            data-prettify="<?php echo $slider->getPrettifyValues();?>"
        />

    <?php } else { 
        $from = (isset($values[0]) && is_numeric($values[0])) ? $values[0] : ($slider->getLowerLimit() + $slider->getUpperLimit()) / 2;
        ?>
    	<input type="text" class="js-range-slider" name="<?php echo $filter->getUniqueIdentifier();?>" value=""
            data-min="<?php echo $slider->getLowerLimit();?>"
            data-max="<?php echo $slider->getUpperLimit();?>"
            data-from="<?php echo $from;?>"
            data-prefix="<?php echo $slider->getPrefix();?>"
            data-postfix="<?php echo $slider->getSuffix();?>"
            data-prettify="<?php echo $slider->getPrettifyValues();?>"
        />
    <?php } ?>

    <div class="guideplugin-text-center"><div class="guide-slider-result-count">(<?php printf(_n('%d result', '%d results', $filter->getSelection()->getResultCount(), 'guideplugin'), $filter->getSelection()->getResultCount());?>)</div></div>

</div>
