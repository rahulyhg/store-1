/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(logs) {
  for (var log in logs) {
    html += '<tr>';
    html += '<td class="left-align">' + logs[log]['id'] + '</td>';
    html += '<td class="center">' + logs[log]['data'] + '</td>';
    html += '<td class="center">' + logs[log]['datetime'] + '</td>';
    html += '<td class="right-align">';
    html += '<a class="btn red" onclick="deleteLog(' + logs[log]['id'] + ')"><i class="material-icons">delete</i></a>';
    html += '</td>';
    html += '</tr>';
  }
  return html;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getLogs() {
  $.ajax({
    url: get_logs_action_url,
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
      if (response == false) {
        elegant_alert.warning("В базе данных отсутствуют логи");
        $('#logs_count').text(0);
        html = '<div class="col s12 m12 l12 center">В базе данных отсутствуют логи</div>';
        $('#logs_content').html(html);
        return false;
      } else {
        html = "";
        html = renderContent(response);
        $('#logs_count').text(response.length);
        $('#logs_content').html(html);
        $('html, body').animate({
          scrollTop: 0
        }, 500);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_logs);
      console.log(xhr);
      writeLog('Dashboard/JS/Logs: Error Get Logs');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteLog(log_id) {
  $.ajax({
    url: delete_log_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'log_id': log_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getLogs();
        elegant_alert.success(alert_delete_log);
      } else {
        elegant_alert.error(error_delete_log);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_delete_log);
      console.log(xhr);
      writeLog('Dashboard/JS/Logs: Error Delete Log');
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteLogs() {
  $.ajax({
    url: delete_logs_action_url,
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
      if (response.result == true) {
        getLogs();
        elegant_alert.success(alert_delete_logs);
      } else {
        elegant_alert.error(error_delete_logs);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_delete_logs);
      console.log(xhr);
      writeLog('Dashboard/JS/Logs: Error Delete Logs');
    }
  });
}
