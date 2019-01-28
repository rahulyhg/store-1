/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form_data = {
    'email': $('#input_user_email').val(),
    'password': $('#input_user_password').val()
  };
  return form_data;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function loginUser(form_data) {
  $.ajax({
    url: login_user_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'form_data': form_data
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        elegant_alert.success(alert_allow_authorization);
        location.reload(false);
      } else {
        elegant_alert.warning(alert_deny_authorization);
        writeLog("Dashboard/Javascript/Authorization/loginAuthor: False result for authorization");
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      writeLog("Dashboard/Javascript/Authorization/loginAuthor: Ajax Error");
      elegant_alert.error(error_login_author);
    }
  });
}
