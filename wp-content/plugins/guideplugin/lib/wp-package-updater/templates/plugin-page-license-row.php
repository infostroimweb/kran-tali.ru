<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<?php 
$style = '';
if (get_option( 'license_key_' . $this->package_slug )) {
	$style="display: none;";
}
?>

<tr id="<?php echo $this->package_slug;?>-license-container" class="plugin-update-tr active installer-plugin-update-tr" style="<?php echo $style;?>">
	<td colspan="3" class="plugin-update colspanchange">
		<div class="notice inline notice-warning notice-alt">
			<?php echo $form; ?><?php // @codingStandardsIgnoreLine ?>
		</div>
	</td>
</tr>
