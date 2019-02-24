/* ##################################################################################### */
// STORE ICON IMAGE
/* ##################################################################################### */
function previewStoreIconImage(input) {
  $('#block_store_icon_preview').html('<img class="responsive-img" id="store_icon_preview" src="">');
  if (input.files && input.files[0]) {
    var file_reader = new FileReader();
    file_reader.onload = function(e) {
      $('#store_icon_preview').attr('src', e.target.result).show();
    };
    file_reader.readAsDataURL(input.files[0]);
  }
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function cancelStoreIconImage() {
  $('#change_store_icon').val(Number(0));
  $('#store_icon_tokenname').val(null);
  $('#input_store_icon_filename').val(null);
  M.updateTextFields();
  var original_store_icon_tokenname = $('#original_store_icon_tokenname').val();
  var html = '<img class="responsive-img" src="' + original_store_icon_tokenname + '" id="store_icon_preview" />';
  $('#block_store_icon_preview').html(html).show();
  delete original_store_icon_tokenname;
  delete html;
}


/* ##################################################################################### */
// STORE LOGO IMAGE
/* ##################################################################################### */
function previewStoreLogoImage(input) {
  $('#block_store_logo_preview').html('<img class="responsive-img" id="store_logo_preview" src="">');
  if (input.files && input.files[0]) {
    var file_reader = new FileReader();
    file_reader.onload = function(e) {
      $('#store_logo_preview').attr('src', e.target.result).show();
    };
    file_reader.readAsDataURL(input.files[0]);
  }
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function cancelStoreLogoImage() {
  $('#change_store_logo').val(Number(0));
  $('#store_logo_tokenname').val(null);
  $('#input_store_logo_filename').val(null);
  M.updateTextFields();
  var original_store_logo_tokenname = $('#original_store_logo_tokenname').val();
  var html = '<img class="responsive-img" src="' + original_store_logo_tokenname + '" id="store_logo_preview" />';
  $('#block_store_logo_preview').html(html).show();
  delete original_store_logo_tokenname;
  delete html;
}
