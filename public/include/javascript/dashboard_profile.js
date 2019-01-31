/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form_data = {
    'name': $('#input_user_name').val(),
    'email': $('#input_user_email').val(),
    'password': $('#input_user_password').val()
  };
  return form_data;
}
