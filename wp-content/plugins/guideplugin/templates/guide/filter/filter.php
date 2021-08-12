<div class="guide-slider-item">

	<div 
	class="guide-filter guide-filter-<?php echo $filter->getType();?> guide-filter-<?php echo $filter->getType();?>-<?php echo $filter->getSelection()->getType();?> <?php echo ($filter->getIsFirst()) ? 'guide-first-filter' : '';?> <?php echo ($filter->getIsLast()) ? 'guide-last-filter' : '';?>" 
	data-unique-identifier="<?php echo $filter->getUniqueIdentifier();?>" 
	data-type="<?php echo $filter->getType();?>" 
	data-selection-type="<?php echo $filter->getSelection()->getType();?>" 
	data-index="<?php echo $filter->getIndex();?>" 
	data-id="<?php echo $filter->getId();?>"
	data-input-required="<?php echo $filter->getInputRequired();?>"
	data-next-on-select="<?php echo $filter->getNextOnSelect();?>">

		<div class="guide-filter-title"><?php echo $filter->getTitle();?></div>
		<div class="guide-filter-description"><?php echo $filter->getDescription();?></div>

		<?php echo \GuidepluginHelper::template_filter_selection($filter); ?>

		<?php if (get_field('guideplugin_show_help', $filter->getId())) { ?>
			<div class="guide-filter-help">
				<a href="#modal_<?php echo $filter->getUniqueIdentifier();?>" data-guideplugin-modal-open><i class="far fa-question-circle"></i> <?php _e('Подсказка', 'guideplugin');?></a>
			</div>
		<?php } ?>

		<div class="guide-form-buttons">
			<?php $disabled = ($filter->getSelection()->getResultCount() == 0) ? 'disabled="disabled"' : '';?>
			<?php echo \GuidepluginHelper::template_spinner('md', 'guide-button-spinner'); ?>
			<button type="button" class="guide-button guide-button-light guide-button-previous" data-action="previous"><?php echo apply_filters('guideplugin/button/label/previous', __('Предыдущий параметр', 'guideplugin'), $filter->getId());?></button>
			<button type="button" <?php echo $disabled;?> class="guide-button guide-button-primary guide-button-next" data-action="next"><?php echo apply_filters('guideplugin/button/label/next', __('Следующий параметр', 'guideplugin'), $filter->getId());?></button>
			<button type="button" <?php echo $disabled;?> class="guide-button guide-button-primary guide-button-finish" data-action="finish"><?php echo apply_filters('guideplugin/button/label/finish', __('Показать результат', 'guideplugin'), $filter->getId());?></button>
		</div>

		<div id="modal-zayavka" class="modal-zayavka">
			<?php echo do_shortcode('[contact-form-7 id="2256" title="Отправить заявку"]'); ?>
		</div>

	</div>

</div>

