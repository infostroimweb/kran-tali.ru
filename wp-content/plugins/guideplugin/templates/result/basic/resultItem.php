<?php $highlight = ($resultQuery->current_post == 0 && !$resultQuery->is_paged) ? 'guide-result-highlight' : ''; ?>

<div class="guide-result-item <?php echo $highlight;?>">
	<div class="guide-result-highlight-label"><?php echo $resultSettings['badge_text'];?></div>
	<div class="guide-result-image"><?php echo get_the_post_thumbnail(null, 'medium');?></div>
	<div class="guide-result-content">
		<div><a href="<?php echo get_permalink();?>" class="guide-result-title"><?php echo get_the_title();?></a></div>
		<?php
		$termNames = [];
		$terms = wp_get_post_terms(get_the_ID(), get_taxonomies('','names'));
		if (!empty($terms)) {
			foreach ($terms as $term) {
				array_push($termNames, $term->name);
			}
		}
		?>
		<div class="guide-result-category-list"><?php echo implode(', ', $termNames); ?></div>
	</div>
</div>

<?php if ($resultQuery->current_post == 0 && !$resultQuery->is_paged && $resultQuery->post_count > 1) { ?>
	<div class="guide-heading-after-highlight"><?php _e('More results', 'guidetheme');?></div>
<?php } ?>