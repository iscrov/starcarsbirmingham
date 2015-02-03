function pageImages_add_image(obj) {
    var parent=jQuery(obj).parent('div.form_field');            
    var inputField = jQuery(parent).find("input.image_data_field");
    var inputFieldId = jQuery(parent).find("input.image_data_field_id");
    var fileFrame = wp.media.frames.file_frame = wp.media({
        multiple: false
    });
    fileFrame.on('select', function() {
        var url = fileFrame.state().get('selection').first().toJSON();
        //alert(url.id);
        inputField.val(url.url);
        inputFieldId.val(url.id);
        jQuery(parent).parent()
        .find("div.image_wrap")
        .html('<img src="'+url.url+'" width="100" />');
        
    });
    fileFrame.open();
};

function pageImages_reomove_field(obj) {
    var parent=jQuery(obj).parent().parent().parent().parent();
    parent.remove();
}
function pageImages_add_field_row(obj) {
	jQuery(obj).parent().siblings('#dynamic_form').find('#field_wrap');
    var row = jQuery(obj).parent().siblings('#master-row').html();
    jQuery(row).appendTo(jQuery(obj).parent().siblings('#dynamic_form').find('#field_wrap'));
}
