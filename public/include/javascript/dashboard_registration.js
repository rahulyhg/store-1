/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form_data = {
    'name': $('#input_user_name').val(),
    'phone': $('#input_user_phone').val(),
    'email': $('#input_user_email').val(),
    'password': $('#input_user_password').val()
  };
  return form_data;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#input_user_name').val(null).trigger('blur');
  $('#input_user_email').val(null).trigger('blur');
  $('#input_user_phone').val(null).trigger('blur');
  $('#input_user_password').val(null).trigger('blur');
}


