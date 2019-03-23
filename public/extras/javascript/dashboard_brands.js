/* ##################################################################################### */
function renderContent(brands) {

  return html;
}

/* ##################################################################################### */
function serializeForm() {

}

/* ##################################################################################### */
function fillForm(form) {

}

/* ##################################################################################### */
function clearImage() {
  $('#input_brand_image_filename').val(null);
  $('#original_brand_image_tokenname').val(null);
  $('#brand_image_tokenname').val(null);
  $('#change_brand_image').val(0);

  $('#brand_image_preview').prop('src', image_not_found_path);
}

/* ##################################################################################### */
function clearForm() {
  $('#input_brand_name').val(null);
  $('#input_brand_description').val(null);
  $('#input_brand_link').val(null);
  clearImage();

  M.updateTextFields();
  M.textareaAutoResize($('#input_brand_description'));
}
