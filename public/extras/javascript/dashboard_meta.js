/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form = {
    'title': $('#input_meta_title').val(),
    'name': $('#input_meta_name').val(),
    'description': $('#input_meta_description').val(),
    'keywords': $('#input_meta_keywords').val(),
    'aboutus': $('#input_meta_aboutus').val(),
    'copyright': $('#input_meta_copyright').val(),
    'revisit': Number($('#input_meta_revisit').val())
  };

  return form;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function fillForm(form) {
  $('#input_meta_title').val(form.title);
  $('#input_meta_name').val(form.name);
  $('#input_meta_description').val(form.description);
  $('#input_meta_keywords').val(form.keywords);
  $('#input_meta_aboutus').val(form.aboutus);
  $('#input_meta_copyright').val(form.copyright);
  $('#input_meta_revisit').val(Number(form.revisit));
  M.updateTextFields();
  M.textareaAutoResize($('#input_meta_description'));
  M.textareaAutoResize($('#input_meta_keywords'));
  M.textareaAutoResize($('#input_meta_aboutus'));
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#input_meta_title').val(null);
  $('#input_meta_name').val(null);
  $('#input_meta_description').val(null);
  $('#input_meta_keywords').val(null);
  $('#input_meta_aboutus').val(null);
  $('#input_meta_copyright').val(null);
  $('#input_meta_revisit').val(null);
  M.updateTextFields();
  M.textareaAutoResize($('#input_meta_description'));
  M.textareaAutoResize($('#input_meta_keywords'));
  M.textareaAutoResize($('#input_meta_aboutus'));
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
        if (response.newfile == true) {
          elegant_alert.success(alert_create_new_file);
        }
        clearForm();
        fillForm(response.form);
      } else {
        elegant_alert.error(error_get_meta);
        clearForm();
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_meta);
      writeLog('App/Javascript/DashboardInformation::getInformation');
      $('#preloader').hide();
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function saveMeta(language_id, form) {
  $.ajax({
    url: save_meta_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'language_id': language_id,
      'form': form
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.status == true) {
        elegant_alert.success(alert_save_meta);
      } else {
        elegant_alert.error(error_save_meta);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_save_meta);
      writeLog('App/Javascript/DashboardInformation::saveInformation');
      $('#preloader').hide();
    }
  });
}
