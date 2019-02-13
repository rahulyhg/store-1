/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(modules) {
  for (var module in modules) {
    html += '<div class="col s12 m12 l12">';
    html += '<div class="card hoverable">';
    html += '<div class="card-content">';
    html += '<span class="card-title">' + modules[module]['id'] + '. ' + modules[module]['name'] + '</span>';

    if (modules[module]['status'] == true) {
      html += '<p><label><i class="material-icons left">link</i>' + render_module_status_on + '</label></p>';
    } else {
      html += '<p><label><i class="material-icons left">link_off</i>' + render_module_status_off + '</label></p>';
    }

    html += '<pre><small>' + String(modules[module]['data']).replace(/\</g, '&lt;').replace(/\>/g, '&gt;') + '</small></pre>';
    html += '</div>';
    html += '<div class="card-action">';
    html += '<a class="green-text clickable" onclick="getModule(' + modules[module]['id'] + ')">' + button_edit_module + '</a>';
    html += '<a class="red-text clickable" onclick="deleteModule(' + modules[module]['id'] + ')">' + button_delete_module + '</a>';
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
    'id': $('#input_module_id').val(),
    'name': $('#input_module_name').val(),
    'data': $('#input_module_data').val(),
    'status': $('#input_module_status').val()
  };
  return form;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#form_title_edit_module').hide();
  $('#form_title_add_module').show();

  $('#input_module_id').val(null);

  $('#input_module_name').val(null);

  $('#input_module_data').val(null);
  M.textareaAutoResize($('#input_module_data'));

  $('#input_module_status').val(0).formSelect();

  M.updateTextFields();

  $('#button_save_module').hide();
  $('#button_add_module').show();
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getModules() {
  $.ajax({
    url: get_modules_action_url,
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
        $('#modules_content').html(html);
        elegant_alert.error(warning_modules_missing);
        $('#card_modules_missing').show();
      } else {
        $('#card_modules_missing').hide();
        html = "";
        html = renderContent(response);
        $('#modules_content').html(html);
      }
      $('html, body').animate({
        scrollTop: 0
      }, 500);
    },
    complete: function() {
      $('#preloader_circle').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_modules);
      writeLog('Dashboard/JS/Module: Error Get Modules');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getModule(module_id) {
  $.ajax({
    url: get_module_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'module_id': module_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      var el = document.getElementById("modules_tabs");
      var instance = M.Tabs.getInstance(el);
      instance.select('tab_form');

      $('#form_title_add_module').hide();
      $('#form_title_edit_module').show();

      $('#input_module_id').val(response.id);

      $('#input_module_name').val(response.name);

      $('#input_module_data').val(response.data);
      M.textareaAutoResize($('#input_module_data'));

      if (response.status == true) {
        $('#input_module_status').val(1).formSelect();
      } else {
        $('#input_module_status').val(0).formSelect();
      }

      M.updateTextFields();

      $('#button_add_module').hide();
      $('#button_save_module').show();
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_module);
      writeLog('Dashboard/JS/Module: Error Get Module');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function addModule(form) {
  $.ajax({
    url: add_module_action_url,
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

        var el = document.getElementById("modules_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_modules');

        getModules();
        elegant_alert.success(alert_add_module);
      } else {
        elegant_alert.error(error_add_module);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_add_module);
      writeLog('Dashboard/JS/Module: Error Add Module');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function editModule(form) {
  $.ajax({
    url: edit_module_action_url,
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

        var el = document.getElementById("modules_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_modules');

        getModules();
        elegant_alert.success(alert_edit_module);
      } else {
        elegant_alert.error(error_edit_module);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_edit_module);
      writeLog('Dashboard/JS/Module: Error Edit Module');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteModule(module_id) {
  $.ajax({
    url: delete_module_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'module_id': module_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getModules();
        elegant_alert.success(alert_delete_module);
      } else {
        elegant_alert.error(error_delete_module);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_delete_module);
      writeLog('Dashboard/JS/Module: Error Delete Module');
    }
  });
}
