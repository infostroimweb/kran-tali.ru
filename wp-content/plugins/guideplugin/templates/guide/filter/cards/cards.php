<div class="guide-cards-container">
	<?php
	$values = $filter->getValues();
	$cardSelection = $filter->getSelection();
	$cards = $cardSelection->getCards();
	if (!empty($cards)) {
		foreach ($cards as $key => $card) {
			$checked = (in_array($card->getUniqueIdentifier(), $values)) ? 'checked="checked"' : '';
			$selected = (in_array($card->getUniqueIdentifier(), $values)) ? 'selected' : '';
			$ghostClass = ($card->getResultCount() <= 0) ? 'guide-card-ghost' : '';
			$ghostDisabled = ($card->getResultCount() <= 0) ? 'disabled="disabled"' : '';
			?>
			<label class="guide-card <?php echo $selected;?> <?php echo $ghostClass; ?>" data-card-type="<?php echo $cardSelection->getType();?>">
				<?php if (!empty($card->getImage())) { ?>
					<img class="guide-card-image" src="<?php echo wp_get_attachment_image_src($card->getImage(), 'medium')[0];?>" style="width: <?php echo $cardSelection->getImageWidth();?>px; height: <?php echo $cardSelection->getImageHeight();?>px;" />
				<?php } ?>
				<div class="guide-card-label"><?php echo $card->getLabel();?> <span class="guide-card-result-count">(<?php echo $card->getResultCount();?>)</span></div>

				<?php 
				switch ($cardSelection->getType()) {
					case 'radio':
						?><input data-card-value="<?php echo $card->getUniqueIdentifier();?>" type="radio" value="<?php echo $card->getUniqueIdentifier();?>" name="<?php echo $filter->getUniqueIdentifier();?>" <?php echo $checked; ?> <?php echo $ghostDisabled; ?> /><?php
						break;

					case 'checkbox':
						?><input data-card-value="<?php echo $card->getUniqueIdentifier();?>" type="checkbox" value="1" name="<?php echo $card->getUniqueIdentifier();?>" <?php echo $checked; ?> <?php echo $ghostDisabled; ?> /><?php
						break;
					
					default:
						# code...
						break;
				}
				?>
			</label>
			<?php
		}
	}
	?>
</div>
