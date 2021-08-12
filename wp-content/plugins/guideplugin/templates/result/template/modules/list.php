<?php if (!empty($module['list'])) { ?>
	<table class="guide-result-list-table">
		<?php foreach ($module['list'] as $listItem) { 
			$dataValues = \GuidepluginHelper::get_data($listItem['data_source'], get_the_ID());
			?>
			<tr><td class="guide-result-column-list-label"><?php echo $listItem['label'];?></td><td class="guide-result-column-list-data"><?php echo implode(', ', $dataValues); ?></td></tr>
		<?php } ?>
	</table>
<?php } ?>