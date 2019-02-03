/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(scripts) {
  for (var script in scripts) {
    html += '<tr>';
    html += '<td class="left-align">' + scripts[script]['id'] + '</td>';
    html += '<td class="left-align">' + scripts[script]['name'] + '</td>';
    html += '<td class="left-align">';

    html += '<pre><small>' + String(scripts[script]['data']).replace(/\</g, '&lt;').replace(/\>/g, '&gt;') + '</small></pre>';

    html += '</td>';
    html += '<td class="right-align">';
    html += '<a class="btn green clickable" onclick="getScript(' + scripts[script]['id'] + ')"><i class="material-icons">edit</i></a>';
    html += '<a class="btn red clickable" onclick="deleteScript(' + scripts[script]['id'] + ')"><i class="material-icons">delete</i></a>';
    html += '</td>';
    html += '</tr>';
  }

  return html;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form_data = {
    'id': $('#input_script_id').val(),
    'name': $('#input_script_name').val(),
    'data': $('#input_script_data').val()
  };
  return form_data;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#title_edit_script').hide();
  $('#title_add_script').show();

  $('#input_script_id').val(null);
  $('#input_script_name').val(null).trigger('blur');
  $('#input_script_data').val(null).trigger('blur').trigger('autoresize');

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
      $('#preloader').show();
    },
    success: function(response) {
      html = "";
      html = renderContent(response);
      $('#scripts_content').html(html);

      $('html, body').animate({
        scrollTop: 0
      }, 500);
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_get_scripts);
      writeLog('Dashboard/JS/Script: Error Get Scripts');
    }
  });
}

/* ##################################################################################### */
//
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
      $('#input_form').modal('open');

      $('#title_add_script').hide();
      $('#title_edit_script').show();

      $('#input_script_id').val(response.id);
      $('#input_script_name').val(response.name).trigger('focus');
      $('#input_script_data').val(response.data).trigger('focus').trigger('autoresize');

      $('#button_add_script').hide();
      $('#button_save_script').show();
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_get_script);
      writeLog('Dashboard/JS/Script: Error Get Script');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function addScript(form_data) {
  $.ajax({
    url: add_script_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'form_data': form_data
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        clearForm();
        $('#input_form').modal('close');
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
      console.log(xhr);
      elegant_alert.error(error_add_script);
      writeLog('Dashboard/JS/Script: Error Add Script');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function editScript(form_data) {
  $.ajax({
    url: edit_script_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'form_data': form_data
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        clearForm();
        $('#input_form').modal('close');
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
