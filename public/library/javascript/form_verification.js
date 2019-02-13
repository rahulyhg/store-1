/* ##################################################################################### */
// возвращает true или false
/* ##################################################################################### */
function checkEmail(email) {
  var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  return pattern.test(email);
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function checkPhone(phone) {
  var pattern = new RegExp(/^\d[\d\(\)\ -]{4,14}\d$/);
  return pattern.test(phone);
}

// функции проверки имени, пароля