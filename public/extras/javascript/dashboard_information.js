
/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form = {
    'id': $('#input_language_id').val(),
    'name': $('#input_language_name').val(),
    'code': $('#input_language_code').val(),
  };
  return form;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#form_title_edit_language').hide();
  $('#form_title_add_language').show();

  $('#input_language_id').val(null);
  $('#input_language_name').val(null);
  $('#input_language_code').val(null);

  M.updateTextFields();

  M.textareaAutoResize($('#input_module_data'));

  $('#button_save_language').hide();
  $('#button_add_language').show();
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getInformation(language_id) {
  $.ajax({
    url: get_information_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'language_id': language_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.status == true) {
        if (response.filesize == true) {
          $('#input_information_text').val(response.content);
          //M.textareaAutoResize($('#input_information_text'));
          M.updateTextFields();
        } else {
          elegant_alert.success(alert_create_new_file);
        }
      } else {
        elegant_alert.error(error_get_information);
      }
    },
    complete: function() {
      $('#preloader').hide();
      //M.textareaAutoResize($('#input_information_text'));
      //M.updateTextFields();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_information);
      writeLog('App/Javascript/DashboardInformation::getInformation');
    }
  });
}
