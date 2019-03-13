/* ##################################################################################### */
//
/* ##################################################################################### */
function getLogsCount() {
  $.ajax({
    url: get_logs_count_action_url,
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
      if (response.count > 0) {
        elegant_alert.warning(warning_check_logs);
        $('#badge_logs').text(response.count);
        $('#badge_logs').addClass('red');
      } else {
        $('#badge_logs').text(response.count);
        $('#badge_logs').addClass('green');
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_logs_count);
      writeLog('App/Javascript/DashboardSettings::getLogsCount > Ajax Error');
    }
  });
}
