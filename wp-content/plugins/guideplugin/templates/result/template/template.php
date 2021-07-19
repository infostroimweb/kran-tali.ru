<?php 
$hightlight = ($resultQuery->current_post == 0 && !$resultQuery->is_paged) ? 'guide-result-highlight' : '';
$imageSettings = get_field('guideplugin_template_image_settings', $templateId);
?>

<div class="guide-result-item <?php echo $hightlight;?> <?php echo (!$imageSettings['show_image']) ? 'guide-no-image' : '';?> item-post-<?php echo get_the_ID();?>">
	<div class="guide-result-highlight-label"><?php echo $resultSettings['badge_text'];?></div>
	<div class="guide-result-image" style="max-width: <?php echo $imageSettings['image_width']; ?>px;"><?php echo get_the_post_thumbnail(null, 'medium');?></div>
	<div class="guide-result-content" style="padding-left: <?php echo $imageSettings['image_width'] + 20; ?>px;">
		<?php if (!empty($templateRows)) { ?>
			<div class="guide-result-template">
				<?php foreach ($templateRows as $row) {
					if (!empty($row['template_columns'])) { ?>
						<div class="guide-result-row">
							<?php foreach ($row['template_columns'] as $column) { ?>
								<div class="guide-result-column <?php echo ($column['hide_on_mobile']) ? 'guide-result-module-hide-on-mobile' : '';?> <?php echo ($column['vertical_align']) ? 'guide-result-column-align-'.$column['vertical_align'] : '';?>" style="width: <?php echo $column['column_width'];?>%;">
									<?php if (!empty($column['template_modules'])) {
										foreach ($column['template_modules'] as $module) { 
											?>
											<div class="guide-result-module guide-result-module-<?php echo $module['acf_fc_layout'];?> <?php echo ($module['hide_on_mobile']) ? 'guide-result-module-hide-on-mobile' : '';?>" style="text-align: <?php echo $module['align'];?>; margin-top: <?php echo $module['margin_top'];?>px;">
												<?php
												ob_start();
												switch ($module['acf_fc_layout']) {
													case 'title':
														include \GuidepluginHelper::template('templates/result/template/modules/title.php');
														break;

													case 'text':
														include \GuidepluginHelper::template('templates/result/template/modules/text.php');
														break;

													case 'list':
														include \GuidepluginHelper::template('templates/result/template/modules/list.php');
														break;

													case 'button':
														include \GuidepluginHelper::template('templates/result/template/modules/button.php');
														break;

													case 'data':
														include \GuidepluginHelper::template('templates/result/template/modules/data.php');
														break;
													
													default:
														# code...
														break;
												} 
												$moduleHtml = ob_get_clean();
												$module['module'] = $module['acf_fc_layout'];
												echo apply_filters('guideplugin/result/template/module', $moduleHtml, $module, get_the_ID(), $guide->getId());
												?>
											</div>
										<?php }
									 } ?>
								</div>
							<?php } ?>
						</div>
					<?php }
				} ?>
			</div>
		<?php } ?>
	</div>
</div>


<?php if ($resultQuery->current_post == 0 && !$resultQuery->is_paged && $resultQuery->post_count > 1) { ?>
	<div class="guide-heading-after-highlight"><?php echo $resultSettings['more_results'];?></div>
<?php } ?>