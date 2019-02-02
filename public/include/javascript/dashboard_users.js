/* ##################################################################################### */
//
/* ##################################################################################### */
function renderContent(users) {
  for (var user in users) {
    /*html += '<div class="col s12 m6 l4">';
    html += '<div class="card hoverable">';

    html += '<div class="card-image waves-effect waves-block waves-light">';
    if (users[user]['id'] == 0) {
      html += '<img class="responsive-img activator" src="' + path_defaults_images + users[user]['image'] + '">';
    } else {
      html += '<img class="responsive-img activator" src="' + path_users_images + users[user]['image'] + '">';
    }
    html += '</div>';

    html += '<div class="card-content">';
    if (users[user]['status'] == 1) {
      html += '<span class="card-title activator grey-text text-darken-4 truncate"><i class="material-icons left">person</i>' + users[user]['name'] + '</span>';
    } else {
      html += '<span class="card-title activator grey-text text-darken-4 truncate"><i class="material-icons left">person_outline</i>' + users[user]['name'] + '</span>';
    }
    html += '</div>';

    html += '<div class="card-reveal">';
    html += '<span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>';
    html += '<span class="card-title">' + text_user_about + '</span>';
    html += '<p>' + users[user]['about'] + '</p>';
    html += '<div class="center">';
    html += '<p><a class="clickable" href="mailto:' + users[user]['email'] + '"><div class="chip">' + users[user]['email'] + '</div></a></p>';

    if (users[user]['id'] != 0) {
      if (users[user]['status'] == 1) {
        html += '<p class="center"><div class="switch"><label><i class="material-icons">person_outline</i><input type="checkbox" user-id="' + users[user]['id'] + '" checked="checked"><span class="lever"></span><i class="material-icons">person</i></label></div></p>';
      } else {
        html += '<p class="center"><div class="switch"><label><i class="material-icons">person_outline</i><input type="checkbox" user-id="' + users[user]['id'] + '"><span class="lever"></span><i class="material-icons">person</i></label></div></p>';
      }
      html += '<br />';

      if (users[user]['dependence'] == true) {
        html += '<p class="center"><a class="btn grey white-text clickable"><i class="material-icons">delete</i></a></p>';
      } else {
        html += '<p class="center"><a class="btn red white-text clickable" onclick="deleteUser(' + users[user]['id'] + ');"><i class="material-icons">delete</i></a></p>';
      }

    }
    html += '</div>';


    html += '</div>';
    html += '</div>';

    html += '</div>';
    html += '</div>';*/


    html += '<div class="col s12 m6 l4">';
    html += '<div class="card hoverable" style="min-height: 280px;">';
    html += '<div class="card-content center">';
    html += '<span class="card-title truncate">' + users[user]['name'] + '</span>';
    html += '<p>';
    html += '<a class="clickable" href="mailto:' + users[user]['email'] + '"><div class="chip tooltipped truncate" data-position="top" data-tooltip="' + user_email_tooltip + '">' + users[user]['email'] + '</div></a>';
    html += '</p>';

    html += '<br />';

    if (users[user]['id'] != users[user]['current_user_id']) {
      html += '<div class="center">';
      html += '<label>' + text_user_status + '</label>';
      if (users[user]['status'] == 1) {
        html += '<p class="center"><div class="switch"><label><i class="material-icons">person_outline</i><input type="checkbox" user-id="' + users[user]['id'] + '" checked="checked"><span class="lever"></span><i class="material-icons">person</i></label></div></p>';
      } else {
        html += '<p class="center"><div class="switch"><label><i class="material-icons">person_outline</i><input type="checkbox" user-id="' + users[user]['id'] + '"><span class="lever"></span><i class="material-icons">person</i></label></div></p>';
      }
      html += '</div>';

      html += '<br />';
      html += '<br />';

      html += '<p class="center"><a class="btn red white-text clickable" onclick="deleteUser(' + users[user]['id'] + ');"><i class="material-icons">delete</i></a></p>';
    } else {
      html += '<p>';
        html += text_current_user;
      html += '</p>';
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
function getUsers() {
  $.ajax({
    url: get_users_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      "request": true
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      html = "";
      html = renderContent(response);
      $('#users_content').html(html);

      $('input[type="checkbox"]').on('change', function() {
        if ($(this).prop("checked") == true) {
          var checked_status = Number(1);
        } else {
          var checked_status = Number(0);
        }
        var form_data = {
          'id': $(this).attr("user-id"),
          'status': checked_status
        };
        changeUser(form_data);
        delete checked_status;
        delete form_data;
      });
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_get_users);
      writeLog("Dashboard/Javascript/Users/getUsers: Ajax Error");
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function changeUser(form_data) {
  $.ajax({
    url: change_user_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      "request": true,
      "form_data": form_data
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getUsers();
        elegant_alert.success(alert_change_user);
      } else {
        elegant_alert.error(error_change_user);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_change_user);
      writeLog("Dashboard/JS/Users/changeUser: Ajax Error");
    }
  });
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function deleteUser(user_id) {
  $.ajax({
    url: delete_user_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'user_id': user_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        getUsers();
        elegant_alert.success(alert_delete_user);
      } else {
        elegant_alert.warning('?????????? ??????? ?????? ??-?? ???????????? ????????????');
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log(xhr);
      elegant_alert.error(error_delete_user);
      writeLog("Dashboard/JS/Users/deleteUser: Ajax Error");
    }
  });
}
