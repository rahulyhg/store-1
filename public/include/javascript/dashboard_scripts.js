/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(scripts) {
  for (var script in scripts) {
    html += '<div class="col s12 m12 l12">';
    html += '<div class="card hoverable">';
    html += '<div class="card-content">';
    html += '<span class="card-title">' + scripts[script]['id'] + '. ' + scripts[script]['name'] + '</span>';

    if (scripts[script]['status'] == true) {
      html += '<p><label><i class="material-icons left">link</i>' + render_script_status_on + '</label></p>';
    } else {
      html += '<p><label><i class="material-icons left">link_off</i>' + render_script_status_off + '</label></p>';
    }

    html += '<pre><small>' + String(scripts[script]['data']).replace(/\</g, '&lt;').replace(/\>/g, '&gt;') + '</small></pre>';
    html += '</div>';
    html += '<div class="card-action">';
    html += '<a class="green-text clickable" onclick="getScript(' + scripts[script]['id'] + ')">' + button_edit_script + '</a>';
    html += '<a class="red-text clickable" onclick="deleteScript(' + scripts[script]['id'] + ')">' + button_delete_script + '</a>';
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
    'id': $('#input_script_id').val(),
    'name': $('#input_script_name').val(),
    'data': $('#input_script_data').val(),
    'status': $('#input_script_status').val()
  };
  return form;
}

/* ##################################################################################### */
// Проверить правильность autoresize для текстовых полей
/* ##################################################################################### */
function clearForm() {
  $('#form_title_edit_script').hide();
  $('#form_title_add_script').show();

  $('#input_script_id').val(null);

  $('#input_script_name').val(null);

  $('#input_script_data').val(null);
  M.textareaAutoResize($('#input_script_data'));

  $('#input_script_status').val(0).formSelect();

  M.updateTextFields();

  $('#button_save_script').hide();
  $('#button_add_script').show();
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getScripts() {
  $.ajax({
    url: get_scripts_action_url,
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
        $('#scripts_content').html(html);
        elegant_alert.error(warning_scripts_missing);
        $('#card_scripts_missing').show();
      } else {
        $('#card_scripts_missing').hide();
        html = "";
        html = renderContent(response);
        $('#scripts_content').html(html);
      }
      $('html, body').animate({
        scrollTop: 0
      }, 500);
    },
    complete: function() {
      $('#preloader_circle').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_scripts);
      writeLog('Dashboard/JS/Script: Error Get Scripts');
    }
  });
}

/* ##################################################################################### */
// Проверить триггер autoresize
/* ##################################################################################### */
function getScript(script_id) {
  $.ajax({
    url: get_script_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'script_id': script_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      var el = document.getElementById("scripts_tabs");
      var instance = M.Tabs.getInstance(el);
      instance.select('tab_form');

      $('#form_title_add_script').hide();
      $('#form_title_edit_script').show();

      $('#input_script_id').val(response.id);

      $('#input_script_name').val(response.name);

      $('#input_script_data').val(response.data);
      M.textareaAutoResize($('#input_script_data'));

      if (response.status == true) {
        $('#input_script_status').val(1).formSelect();
      } else {
        $('#input_script_status').val(0).formSelect();
      }

      M.updateTextFields();

      $('#button_add_script').hide();
      $('#button_save_script').show();
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_script);
      writeLog('Dashboard/JS/Script: Error Get Script');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function addScript(form) {
  $.ajax({
    url: add_script_action_url,
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

        var el = document.getElementById("scripts_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_scripts');

        getScripts();
        elegant_alert.success(alert_add_script);
      } else {
        elegant_alert.error(error_add_script);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_add_script);
      writeLog('Dashboard/JS/Script: Error Add Script');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function editScript(form) {
  $.ajax({
    url: edit_script_action_url,
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

        var el = document.getElementById("scripts_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_scripts');

        getScripts();
        elegant_alert.success(alert_edit_script);
      } else {
        elegant_alert.error(error_edit_script);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_edit_script);
      writeLog('Dashboard/JS/Script: Error Edit Script');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteScript(script_id) {
  $.ajax({
    url: delete_script_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'script_id': script_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getScripts();
        elegant_alert.success(alert_delete_script);
      } else {
        elegant_alert.error(error_delete_script);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_delete_script);
      writeLog('Dashboard/JS/Script: Error Delete Script');
    }
  });
}
