/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(currencies) {
  for (var currency in currencies) {
    html += '<div class="col s12 m12 l12">';
    html += '<div class="card hoverable">';
    html += '<div class="card-content">';
    html += '<span class="card-title">' + currencies[currency]['id'] + '. ' + currencies[currency]['name'] + '</span>';

    html += '<p>' + text_currency_code + '<b>' + currencies[currency]['code'] + '</b></p>';

    html += '</div>';
    html += '<div class="card-action">';
    html += '<a class="green-text clickable" onclick="getCurrency(' + currencies[currency]['id'] + ')">' + button_edit_currency + '</a>';

    if (currencies[currency]['dependence'] == false) {
      html += '<a class="red-text clickable" onclick="deleteCurrency(' + currencies[currency]['id'] + ')">' + button_delete_currency + '</a>';
    } /*else {
      html += '<a class="grey-text clickable">' + button_delete_currency + '</a>';
    }*/
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
    'id': $('#input_currency_id').val(),
    'name': $('#input_currency_name').val(),
    'code': $('#input_currency_code').val(),
  };
  return form;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#form_title_edit_currency').hide();
  $('#form_title_add_currency').show();

  $('#input_currency_id').val(null);
  $('#input_currency_name').val(null);
  $('#input_currency_code').val(null);

  M.updateTextFields();

  $('#button_save_currency').hide();
  $('#button_add_currency').show();
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getCurrencies() {
  $.ajax({
    url: get_currencies_action_url,
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
        $('#currencies_content').html(html);
        elegant_alert.error(warning_currencies_missing);
        $('#card_currencies_missing').show();
      } else {
        $('#card_currencies_missing').hide();
        html = "";
        html = renderContent(response);
        $('#currencies_content').html(html);
      }
      $('html, body').animate({
        scrollTop: 0
      }, 500);
    },
    complete: function() {
      $('#preloader_circle').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_currencies);
      writeLog('Dashboard/JS/Currency: Error Get Currencies');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getCurrency(currency_id) {
  $.ajax({
    url: get_currency_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'currency_id': currency_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      var el = document.getElementById("currencies_tabs");
      var instance = M.Tabs.getInstance(el);
      instance.select('tab_form');

      $('#form_title_add_currency').hide();
      $('#form_title_edit_currency').show();

      $('#input_currency_id').val(response.id);

      $('#input_currency_name').val(response.name);

      $('#input_currency_code').val(response.code);

      M.updateTextFields();

      $('#button_add_currency').hide();
      $('#button_save_currency').show();
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_currency);
      writeLog('Dashboard/JS/Currency: Error Get Currency');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function addCurrency(form) {
  $.ajax({
    url: add_currency_action_url,
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

        var el = document.getElementById("currencies_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_currencies');

        getCurrencies();*/
        elegant_alert.success(alert_add_currency);
      } else {
        elegant_alert.error(error_add_currency);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_add_currency);
      writeLog('Dashboard/JS/Currency: Error Add Currency');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function editCurrency(form) {
  $.ajax({
    url: edit_currency_action_url,
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

        var el = document.getElementById("currencies_tabs");
        var instance = M.Tabs.getInstance(el);
        instance.select('tab_currencies');

        getCurrencies();
        elegant_alert.success(alert_edit_currency);
      } else {
        elegant_alert.error(error_edit_currency);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_edit_currency);
      writeLog('Dashboard/JS/Currency: Error Edit Currency');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteCurrency(currency_id) {
  $.ajax({
    url: delete_currency_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'currency_id': currency_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getCurrencies();
        elegant_alert.success(alert_delete_currency);
      } else {
        elegant_alert.error(error_delete_currency);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_delete_currency);
      writeLog('Dashboard/JS/Currency: Error Delete Currency');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function saveDefaultCurrencies(form) {
  $.ajax({
    url: save_default_currencies_action_url,
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
        getCurrencies();
        elegant_alert.success(alert_save_default_currencies);
      } else {
        elegant_alert.error(response.error);
        elegant_alert.error(error_save_default_currencies);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(response) {
      elegant_alert.error(response.error);
      elegant_alert.error(error_save_default_currencies);
      writeLog('Dashboard/JS/Currency: Error Add Currency');
    }
  });
}
