/* ##################################################################################### */
//
/* ##################################################################################### */
function serializeForm() {
  var form_data = {
    'name': $('#input_user_name').val(),
    //'about': $('#input_author_about').val(),
    'phone': $('#input_user_phone').val(),
    'email': $('#input_user_email').val(),
    'token': $('#image_token_name').val(),
    //'login': $('#input_author_login').val(),
    'password': $('#input_user_password').val()
  };
  return form_data;
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function clearForm() {
  $('#input_user_name').val(null).trigger('blur');
  //$('#input_author_about').val(null).trigger('blur');
  $('#input_user_email').val(null).trigger('blur');
  $('#input_user_phone').val(null).trigger('blur');
  $('#input_user_image_file').val(null);
  $('#input_user_image_filename').val(null).trigger('blur');
  $('#input_token_name').val(null);
  //$('#input_author_login').val(null).trigger('blur');
  $('#input_user_password').val(null).trigger('blur');
}

/* ##################################################################################### */
// Снятие выделения на обрезаемом изображении
/* ##################################################################################### */
function releaseCrop() {
  jcrop_api.release();
  $('#crop').hide();
}

/* ##################################################################################### */
// Отображения обрезки изображения
// и вставка значение в input hidden
/* ##################################################################################### */
function showCoords(c) {
  $('#image_x1').val(c.x);
  $('#image_y1').val(c.y);
  $('#image_x2').val(c.x2);
  $('#image_y2').val(c.y2);

  $('#image_w').val(c.w);
  $('#image_h').val(c.h);
  if (c.w > 0 && c.h > 0) {
    $('#crop').show();
  } else {
    $('#crop').hide();
  }
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getImageOriginSize(img_src) {
  var image = new Image();
  image.onload = function() {
    origin_image_height = image.height;
    origin_image_width = image.width;
  }
  image.src = img_src;
}

/* ##################################################################################### */
// Выводит выбранное изображение в форму обрезки
// и устанавливает отношение сторон "квадрат"
/* ##################################################################################### */
function previewImage(input) {
  $('#origin_image_field').html('<img class="responsive-img" id="origin_image" src="">');
  if (input.files && input.files[0]) {
    var file_reader = new FileReader();
    file_reader.onload = function(e) {
      $('#origin_image').attr('src', e.target.result).show();
      getImageOriginSize(e.target.result);
      $('#origin_image').Jcrop({
        onChange: showCoords,
        onSelect: showCoords
      }, function() {
        jcrop_api = this;
        jcrop_api.setOptions({
          aspectRatio: 1 / 1
        });
        jcrop_api.focus();
      });
    };
    file_reader.readAsDataURL(input.files[0]);
  }
}
