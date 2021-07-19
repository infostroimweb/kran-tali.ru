<?php if (get_field('guideplugin_show_help', $filter->getId())) { ?>
	<div class="guideplugin-modal" id="<?php echo 'modal_'.$filter->getUniqueIdentifier();?>" style="display: none;">
		<div class="guideplugin-modal-content">
			<div class="guide-modal-heading"><?php _e('Help', 'guideplugin');?>
				<div class="guideplugin-modal-close-button" data-guideplugin-modal-close><i class="fa fa-times"></i></div>
			</div>
			<div class="guide-modal-body"><?php echo get_field('guideplugin_help_text', $filter->getId());?></div>
		</div>
	</div>
<?php } ?>
