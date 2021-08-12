jQuery(document).ready( function() {
	jQuery("#file-load input[type='file']").change(function(){
		var filename = jQuery(this).val().replace(/.*\\/, "");
		jQuery(this).closest("label").find('span.file_name').html(filename);
	});

	//Google Analytics Goals
	document.addEventListener ('wpcf7submit', function(e) {
		if(e.detail.contactFormId == "1531") {
			dataLayer.push({'event': 'call'})
		} else if(e.detail.contactFormId == "1550") {
			dataLayer.push({'event': 'request'})
		}
	}, false );

	jQuery('.custom-logo').attr({
	 height: '45px',
	 width: '164px'
	});

	jQuery('.footer-logo img').attr({
	 height: '114px',
	 width: '200px'
	});


});

