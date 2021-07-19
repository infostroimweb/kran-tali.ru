<?php if (get_field('guideplugin_show_progress_steps', $guide->getId())) { ?>
	<div class="guideplugin-modal guideplugin-progress-modal" data-dismissable="false" style="display: none;">
		<div class="guideplugin-modal-content">
			<div class="guide-modal-body">
				<div class="guideplugin-modal-spinner-wrapper">
					<?php echo \GuidepluginHelper::template_spinner('lg', 'guideplugin-modal-spinner'); ?>
					<div class="guideplugin-modal-finish"></div>
				</div>

				<div class="guide-progress guide-progress-loading guide-search-progress">
					<div class="guide-progress-bar" style="width: 0%;"></div>
				</div>

				<div class="guideplugin-step-progress">
					
					<?php 
					if (!empty($guide->getFilters())) {
						foreach ($guide->getFilters() as $filter) {
							if ($filter->getShowInProgress()) { 
								?><div class="guide-progress-step"><?php echo get_field('guideplugin_progress_text', $filter->getId());?></div><?php 
							}
						}
					}
					?>

					<?php if (get_field('guideplugin_final_progress_step_text', $guide->getId())) { ?>
						<div class="guide-progress-step"><?php echo get_field('guideplugin_final_progress_step_text', $guide->getId());?></div>
					<?php } ?>

				</div>
			</div>
		</div>
	</div>
<?php } ?>
