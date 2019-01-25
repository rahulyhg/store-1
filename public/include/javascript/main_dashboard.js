$(document).ready(function() {
  $('.slider').slider();
  $('.sidenav').sidenav({
    menuWidth: 240,
    edge: 'left'
  });
  $('.parallax').parallax();
  $('.tooltipped').tooltip({
    delay: 50
  });
  $('.modal').modal();
  $('.collapsible').collapsible();
});

/* ##################################################################################### */
//
/* ##################################################################################### */
var elegant_alert = {
  'success': function(alert_data) {
    M.toast({
      html: '<span class="text-select-none"><i class="left material-icons light-green-text text-accent-3 notranslate">done_all</i>' + alert_data + '</span>',
      classes: 'rounded'
    });
  },
  'warning': function(alert_data) {
    M.toast({
      html: '<span class="text-select-none"><i class="left material-icons yellow-text text-accent-3 notranslate">warning</i>' + alert_data + '</span>'
    });
  },
  'attention': function(alert_data) {
    M.toast({
      html: '<span class="text-select-none"><i class="left material-icons blue-text notranslate">error_outline</i>' + alert_data + '</span>'
    });
  },
  'error': function(alert_data) {
    M.toast({
      html: '<span class="text-select-none"><i class="left material-icons red-text notranslate">error</i>' + alert_data + '</span>'
    });
  }
};


/* ##################################################################################### */
//
/* ##################################################################################### */
function writeLog(log_data) {
  $.ajax({
    url: write_log_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'log_data': log_data
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      if (response.result == true) {
        // do something
      } else {
        // do something
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      console.log("Error write logs\n" + xhr);
    }
  });
}

