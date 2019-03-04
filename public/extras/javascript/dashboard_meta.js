/* ##################################################################################### */
//
/* ##################################################################################### */
function fillForm(form) {
  $('#input_meta_title').val(form.title);
  $('#input_meta_description').val(form.description);
  $('#input_meta_keywords').val(keywords);
  $('#input_meta_revisit').val(Number(form.revisit));
  M.updateTextFields();
  M.textareaAutoResize($('#input_meta_description'));
  M.textareaAutoResize($('#input_meta_keywords'));
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#input_meta_title').val(null);
  $('#input_meta_description').val(null);
  $('#input_meta_keywords').val(null);
  $('#input_meta_revisit').val(null);
  M.updateTextFields();
  M.textareaAutoResize($('#input_meta_description'));
  M.textareaAutoResize($('#input_meta_keywords'));
}

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
        clearForm();
        if (response.newfile == true) {
          elegant_alert.success(alert_create_new_file);
          clearForm();
        } else {
          fillForm(response.content);
        }
      } else {
        elegant_alert.error(error_get_information);
        clearForm();
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_information);
      writeLog('App/Javascript/DashboardInformation::getInformation');
      $('#preloader').hide();
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
      $('#preloader').hide();
    }
  });
}
