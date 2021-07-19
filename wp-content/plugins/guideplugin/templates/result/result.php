<?php 
$resultSettings = get_field('guideplugin_result_settings', $guide->getId());
?>

<?php if (!$resultQuery->is_paged) { ?>
	<div class="guide-results-headline"><?php echo $resultSettings['headline'];?></div>
<?php } ?>

<?php 
/**
 * Get template settings before WP Query because of ACF repeater field resets post data
 */
$templateId = get_field('guideplugin_result_settings', $guide->getId())['template_selection'];
$templateRows = get_field('guideplugin_template_rows', $templateId);


if ( $resultQuery->have_posts() ) {
	while ($resultQuery->have_posts()) {
		$resultQuery->the_post();

		ob_start();
		switch ($resultSettings['template_type']) {
			case 'preset_template':
				include \GuidepluginHelper::template('templates/result/template/template.php');
				break;
			
			default:
				include \GuidepluginHelper::template('templates/result/basic/resultItem.php');
				break;
		}
		
		$resultItemHtml = ob_get_clean();

		echo apply_filters('guideplugin/result/item_html', $resultItemHtml, get_the_ID(), $guide->getId());
	}
} else {
?>
	<div class="guide-result-notice"><?php _e('Oh no! There are no posts that match your desires', 'guideplugin');?></div>
<?php } ?>


<div class="guide-result-buttons">
	<?php echo \GuidepluginHelper::template_spinner('md', 'guide-button-spinner'); ?>
	<?php if ($resultQuery->max_num_pages != $page) { ?>
	<div><button type="button" class="guide-button guide-button-primary" data-action="load_more"><i class="fas fa-sync-alt item-left"></i> <?php _e('Load more', 'guideplugin');?></button></div>
	<?php } ?>
	<div><button type="button" class="guide-button guide-button-light guide-button-sm" data-action="reset"><i class="fas fa-angle-left item-left"></i> <?php _e('Back to guide', 'guideplugin');?></button></div>
</div>

<?php
wp_reset_query();
