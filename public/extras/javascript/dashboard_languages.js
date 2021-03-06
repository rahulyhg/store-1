/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(languages) {
  for (var language in languages) {
    html += '<div class="col s12 m12 l12">';
    html += '<div class="card hoverable">';
    html += '<div class="card-content">';
    html += '<span class="card-title">' + languages[language]['id'] + '. ' + languages[language]['name'] + '</span>';

    html += '<p>' + text_language_code + '<b>' + languages[language]['code'] + '</b></p>';

    html += '</div>';
    html += '<div class="card-action">';
    html += '<a class="green-text clickable" onclick="getLanguage(' + languages[language]['id'] + ')">' + button_edit_language + '</a>';

    if (languages[language]['dependence'] == false) {
      html += '<a class="red-text clickable" onclick="deleteLanguage(' + languages[language]['id'] + ')">' + button_delete_language + '</a>';
    }
    
    html += '</div>';
    html += '</div>';
    html += '</div>';
  }

  return html;
}

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

  $('#button_save_language').hide();
  $('#button_add_language').show();
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getLanguages() {
  $.ajax({
    url: get_languages_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader_circle').show();
    },
    success: function(response) {
      if (response.status == false) {
        html = "";
        $('#languages_content').html(html);
        elegant_alert.error(warning_languages_missing);
        $('#card_languages_missing').show();
      } else {
        $('#card_languages_missing').hide();
        html = "";
        html = renderContent(response);
        $('#languages_content').html(html);
      }
      $('html, body').animate({
        scrollTop: 0
      }, 500);
    },
    complete: function() {
      $('#preloader_circle').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_languages);
      writeLog('App/Javascript/DashboardLanguages::getLanguages > Ajax Error > ' + JSON.stringify(xhr));
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getLanguage(language_id) {
  $.ajax({
    url: get_language_action_url,
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
      var el = document.getElementById("languages_tabs");
      var instance = M.Tabs.getInstance(el);
      instance.select('tab_form');

      $('#form_title_add_language').hide();
      $('#form_title_edit_language').show();

      $('#input_language_id').val(response.id);

      $('#input_language_name').val(response.name);

      $('#input_language_code').val(response.code);

      M.updateTextFields();

      $('#button_add_language').hide();
      $('#button_save_language').show();
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_language);
      writeLog('App/Javascript/DashboardLanguages::getLanguage > Ajax Error > ' + JSON.stringify(xhr));
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function addLanguage(form) {
  $.ajax({
    url: add_language_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'form': form
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        location.reload(false);
        /*clearForm();

        var el = document.getElementById("languages_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_languages');

        getLanguages();*/
        elegant_alert.success(alert_add_language);
      } else {
        elegant_alert.error(error_add_language);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_add_language);
      writeLog('App/Javascript/DashboardLanguages::addLanguage > Ajax Error > ' + JSON.stringify(xhr));
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function editLanguage(form) {
  $.ajax({
    url: edit_language_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'form': form
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        clearForm();

        var el = document.getElementById("languages_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_languages');

        getLanguages();
        elegant_alert.success(alert_edit_language);
      } else {
        elegant_alert.error(error_edit_language);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_edit_language);
      writeLog('App/Javascript/DashboardLanguages::editLanguage > Ajax Error > ' + JSON.stringify(xhr));
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteLanguage(language_id) {
  $.ajax({
    url: delete_language_action_url,
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
      if (response.result == true) {
        getLanguages();
        elegant_alert.success(alert_delete_language);
      } else {
        elegant_alert.error(error_delete_language);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_delete_language);
      writeLog('App/Javascript/DashboardLanguages::deleteLanguage > Ajax Error > ' + JSON.stringify(xhr));
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function saveDefaultLanguages(form) {
  $.ajax({
    url: save_default_languages_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'form': form
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getLanguages();
        elegant_alert.success(alert_save_default_languages);
      } else {
        elegant_alert.error(response.error);
        elegant_alert.error(error_save_default_languages);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(response) {
      elegant_alert.error(error_save_default_languages);
      writeLog('App/Javascript/DashboardLanguages::saveDefaultLanguages > Ajax Error > ' + JSON.stringify(xhr));
    }
  });
}
