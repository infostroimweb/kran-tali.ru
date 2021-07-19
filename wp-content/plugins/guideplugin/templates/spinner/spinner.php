<?php 
$sizeClass = 'guideplugin-spinner-md';
switch ($size) {
	case 'lg':
		$sizeClass = 'guideplugin-spinner-lg';
		break;

	case 'sm':
		$sizeClass = 'guideplugin-spinner-sm';
		break;
	
	default:
		# code...
		break;
}
?>

<div class="guideplugin-spinner <?php echo $sizeClass;?> <?php echo $class;?>" style="visibility: hidden;">
	<svg class="guideplugin-spinner-container" x="0px" y="0px" viewbox="0 0 150 150">
		<circle class="guideplugin-spinner-inner" cx="75" cy="75" r="60"></circle>
	</svg>
</div>