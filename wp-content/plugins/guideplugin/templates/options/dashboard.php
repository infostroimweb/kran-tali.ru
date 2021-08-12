<div class="guideplugin-admin">
	<span class="guideplugin-indexed-posts"><?php printf(__('%d indexed posts', 'guideplugin'), $postCount);?></span>
	<button type="button" class="guide-button guide-button-sm guide-button-primary" data-action="rebuild"><?php _e('Rebuild index', 'guideplugin');?></button>
	<button type="button" class="guide-button guide-button-sm guide-button-light" data-action="purge"><?php _e('Purge index', 'guideplugin');?></button>
	
	<?php echo GuidepluginHelper::template_spinner(); ?>

	<?php if (get_option('guideplugin_index_processing') == 1) { ?>
		<span class="guide-indexing"><?php printf(__('Indexing is running! %d indexed so far...', 'guideplugin'), get_option('guideplugin_index_offset'));?></span>
	<?php } ?>
</div>