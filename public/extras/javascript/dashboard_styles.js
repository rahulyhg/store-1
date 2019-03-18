/* ##################################################################################### */
// COLORS PALETTE AND INIT CLIPBOARD
/* ##################################################################################### */
function preselectColor(color) {
  selected_color = color;
  $('#field_color_code').text(color);
}

function unselectColors() {
  $('.color-palette').each(function() {
    $(this).removeClass('z-depth-5');
  });
}

/* ##################################################################################### */
function serializeForm() {
  var form = {
    'header_background': $('#input_header_background').val(),
    'header_text': $('#input_header_text').val(),
    'footer_background': $('#input_footer_background').val(),
    'footer_text': $('#input_footer_text').val()
  };
  return form;
}

/* ##################################################################################### */
function fillForm(form) {
  $('#input_header_background').val(form.header_background);
  $('#input_header_text').val(form.header_text);
  $('#input_footer_background').val(form.footer_background);
  $('#input_footer_text').val(form.footer_text);

  M.updateTextFields();
}

/* ##################################################################################### */
function getStyles() {
  $.ajax({
    url: get_styles_action_url,
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
      fillForm(response);
      $('.button-preview').each(function() {
        $(this).trigger('click');
      });
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function() {
      elegant_alert.error(error_get_styles);
      writeLog('App/Javascript/DashboardStyles::getStyles > Ajax Error');
      $('#preloader').hide();
    }
  });
}

/* ##################################################################################### */
function saveStyles(form) {
  $.ajax({
    url: save_styles_action_url,
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
      if (response.status == true) {
        elegant_alert.success(alert_save_styles);
        getStyles();
      } else {
        elegant_alert.error(error_save_styles);
      }
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function() {
      elegant_alert.error(error_save_styles);
      writeLog('App/Javascript/DashboardStyles::saveStyles > Ajax Error');
      $('#preloader').hide();
    }
  });
}
