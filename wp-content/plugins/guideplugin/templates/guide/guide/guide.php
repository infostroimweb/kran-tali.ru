<?php 
$guideUniqueIdentifier = 'guide_'.bin2hex(random_bytes(6));
?>
<section 
	class="guideplugin guide-loading guide-<?php echo $guide->getId();?>" 
	id="<?php echo $guideUniqueIdentifier;?>" 
	data-unique-identifier="<?php echo $guideUniqueIdentifier;?>" 
	data-id="<?php echo $guide->getId();?>" 
	data-filter-count="" 
	data-progress-modal="<?php echo (get_field('guideplugin_show_progress_steps', $guide->getId()) ? 'true' : 'false');?>"
	data-confetti="<?php echo (get_field('guideplugin_result_settings', $guide->getId())['show_confetti']) ? 'true' : 'false';?>"
	data-font-awesome-pro="<?php echo (get_field('guideplugin_font_awesome_exclude', 'option') && get_field('guideplugin_font_awesome_pro', 'option')) ? 'true' : 'false';?>">

	<div class="guideplugin-background"></div>

	<div class="guide-container">
		<div class="guide-title"><?php echo $guide->getTitle(); ?></div>
		<div class="guide-description"><?php echo $guide->getDescription(); ?></div>

		<form class="guide-form">
			<div class="guide-filter-container">

				<div class="guide-progress guide-slider-progress">
					<div class="guide-progress-bar" style="width: <?php echo 100 / count($guide->getFilters());?>%"></div>
				</div>

				<div class="guide-slider">
					<!-- filters will be displayed here -->
				</div>

				<?php echo \GuidepluginHelper::template_spinner('lg', 'guide-loading-indicator'); ?>

				<?php 
				if (get_field('guideplugin_credits_show', 'option')) {
					$url = (get_field('guideplugin_credits_affiliate_link', 'option')) ? esc_url_raw(get_field('guideplugin_credits_affiliate_link', 'option')) : 'https://www.guideplugin.com';
					?>
					<div class="guide-credits"><?php _e('Powered by', 'guideplugin');?> <a href="<?php echo $url;?>" target="_blank" rel="nofollow">GuidePlugin</a></div>
					<?php
				}
				?>

			</div>
		</form>

		

		<div class="guide-results" data-next-page="1">
			<!-- results will be displayed here -->
		</div>
		
	</div>


	<?php 
	if (!empty($guide->getAllFilters())) {
		foreach ($guide->getAllFilters() as $filter) {
			echo \GuidepluginHelper::filter_help_modal($filter);
		}
	}
	?>

	<?php echo \GuidepluginHelper::guide_progress_modal($guide);?>

	<?php echo \GuidepluginHelper::guide_input_required_modal($guide);?>

</section>