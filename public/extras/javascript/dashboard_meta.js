/* ##################################################################################### */
//
/* ##################################################################################### */
function getMeta(language_id) {
  $.ajax({
    url: get_meta_action_url,
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
        if (response.newfile == true) {
          elegant_alert.success(alert_create_new_file);
          $('#input_information_text').val(null);
          M.updateTextFields();
          M.textareaAutoResize($('#input_information_text'));
        } else {
          $('#input_information_text').val(response.content);
          M.updateTextFields();
          M.textareaAutoResize($('#input_information_text'));
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

/* ##################################################################################### */
//
/* ##################################################################################### */
function saveMeta(language_id, meta_content) {
  $.ajax({
    url: save_meta_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'language_id': language_id,
      'information_content': information_content
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.status == true) {
        elegant_alert.success(alert_save_information);
      } else {
        elegant_alert.error(error_save_information);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_save_information);
      writeLog('App/Javascript/DashboardInformation::saveInformation');
    }
  });
}
