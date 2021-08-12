<?php
/**
 * Server configuration error displayed in errors section.
 *
 * @package WebP Converter for Media
 */

?>
<p>
	<?php
	echo wp_kses_post(
		sprintf(
		/* translators: %1$s: open strong tag, %2$s: close strong tag */
			__( 'Redirects on your server are not working. Check the correct configuration for you in %1$sthe plugin FAQ%2$s. If your configuration is correct, it means that your server does not support redirects from the .htaccess file or requests to images are processed by your server bypassing Apache.', 'webp-converter-for-media' ),
			'<a href="https://wordpress.org/plugins/webp-converter-for-media/#faq" target="_blank">',
			'</a>'
		)
	);
	?>
	<br><br>
	<?php
	echo esc_html(
		__( 'In this case, please contact your server administrator.', 'webp-converter-for-media' )
	);
	?>
</p>
<?php require __DIR__ . '/passthru-info.php'; ?>
