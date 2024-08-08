;jQuery(document).on("click", ".tg_upload_image_button", function() {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    jQuery.data(document.body, 'prevElement', jQuery(this).prev());
    
	wp.media.editor.send.attachment = function(props, attachment) {
	 	var imgurl = attachment.url;
        var inputText = jQuery.data(document.body, 'prevElement');

        if(inputText != undefined && inputText != '')
        {
            inputText.val(imgurl);
        }

	    wp.media.editor.send.attachment = send_attachment_bkp;
	}
	
	wp.media.editor.open();
	return false;
});