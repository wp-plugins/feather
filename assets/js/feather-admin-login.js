jQuery(document).ready(function($) {

	// Define namespace
	feather={};

	// Set image field; Show media library
	$('.feather-image-button').click(function() {
		feather.image_field = $(this).siblings('input[type="text"]').attr('name');
		feather.setting_id = $(this).attr('data-id');
		tb_show('','media-upload.php?type=image&TB_iframe=true');
		return false;
	});

	// Populate image field with URL; Remove media library
	window.send_to_editor = function(html) {
		feather.image_url = $('img',html).attr('src');
		$('input[name="'+feather.image_field+'"]').val(feather.image_url);
		$('#feather_'+feather.setting_id+'_placeholder').html('<img src="'+feather.image_url+'" width="41" height="41" />');
		tb_remove();
	}

	$('.feather-colorpicker').each(function() {
		var colorDiv = $(this);
		var colorInput = $(this).siblings('input');
		$(colorDiv).ColorPicker({
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(colorInput.val());
			},
			onChange: function (hsb, hex, rgb) {
				$(colorInput).val(hex);
				$(colorDiv).find('div').css('background-color', '#' + hex);
			}
		});
	});

});