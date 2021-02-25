jQuery(document).ready(function($){
	$(document).on( "click", ".jsModalOpen", function(e){
		e.preventDefault();
		openModal($(this).data("id-modal"));
	});
	
	$(document).on( "click", ".jsClose, .jsModalIn", function(e){
		var r = true;
		if ($(e.target).get(0)==$(this).get(0)) r = false;
		if(!r) {
			closeModal($(this).closest(".styleModalFix").attr('id')); 
			return false;
		}
	});
	
	$(document).on( "click", ".entryCoursebBut", function(e){
		var text = $(this).closest(".popular-course-content").find("h3").text();
		$("#entryCourse .styleModalHead").text(text);
		$("#entryCourse input[name='languageCourse']").val(text);
		$("#entryCourse input[name='form_name']").val(text);
	});
	
});

function openModal(id) {
	if(!jQuery("#"+id).hasClass("styleModalFixActive")) {
		var count = 1;
		jQuery(".styleModalFix").each(function(){
			if(jQuery(this).hasClass("styleModalFixActive")) {
				count++;
			}
		});
		jQuery("#"+id+"").fadeIn(200).addClass("styleModalFixActive").css("z-index", parseInt(jQuery("#"+id+"").css('z-index'))+count);
		check_scroll();
		jQuery('body').addClass('overflow');
	}
}

function closeModal(id) {
	jQuery('#'+id).fadeOut(0, function(){
		jQuery(this).removeClass("styleModalFixActive").removeAttr("style");
	});
	check_scroll();
	if(!jQuery(".styleModalFixActive").length) jQuery('body').removeClass('overflow');
}

function check_scroll(){
	if(jQuery('body').height()<=jQuery(window).height() || jQuery(".styleModalFixActive").length) jQuery('body').addClass('noscroll');
		else jQuery('body').removeClass('noscroll');
}