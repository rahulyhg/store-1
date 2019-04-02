/* ##################################################################################### */
function serializeForm() {

}

/* ##################################################################################### */
function fillForm(form) {

}

/* ##################################################################################### */
function clearImage() {
  $('#input_brand_image_filename').val(null);
  $('#original_brand_image_tokenname').val(null);
  $('#brand_image_tokenname').val(null);
  $('#change_brand_image').val(0);

  $('#brand_image_preview').prop('src', image_not_found_path);
}

/* ##################################################################################### */
function clearForm() {
  $('#input_brand_name').val(null);
  $('#input_brand_description').val(null);
  $('#input_brand_link').val(null);
  clearImage();

  M.updateTextFields();
  M.textareaAutoResize($('#input_brand_description'));

  $('#button_save_brand').hide();
  $('#button_add_brand').show();
}

/* ##################################################################################### */
// Снятие выделения на обрезаемом изображении и очистка координатов
/* ##################################################################################### */
function releaseCrop() {
  jcrop_api.release();
  $('#crop').hide();

  $('#block_brand_image_preview').hide();
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
function showPreview(c) {
  $('#block_brand_image_preview').show();

	var rx = c.x / c.w;
	var ry = c.y / c.h;

	$('#brand_image_preview').css({
		width: Math.round(rx * origin_image_width) + 'px',
		height: Math.round(ry * origin_image_height) + 'px',
		marginLeft: '-' + Math.round(rx * c.x) + 'px',
		marginTop: '-' + Math.round(ry * c.y) + 'px'
	});

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

      // вывод изображения в превью
      $('#brand_image_preview').prop('src', e.target.result);

      getImageOriginSize(e.target.result);
      $('#origin_image').Jcrop({
        onChange: showPreview,
        onSelect: showPreview
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

/* ##################################################################################### */
//
/* ##################################################################################### */
function saveCrop() {
  var image_x1 = Number($('#image_x1').val());
  var image_y1 = Number($('#image_y1').val());
  var image_w = Number($('#image_w').val());
  var image_h = Number($('#image_h').val());

  var image_scale = origin_image_width / Number($('#origin_image').width());

  var image_real_x1 = Math.floor(image_x1 * image_scale);
  $('#image_x1').val(image_real_x1);
  var image_real_y1 = Math.floor(image_y1 * image_scale);
  $('#image_y1').val(image_real_y1);
  var image_real_width = Math.ceil(image_w * image_scale);
  $('#image_w').val(image_real_width);
  var image_real_height = Math.ceil(image_h * image_scale);
  $('#image_h').val(image_real_height);

  var elem = $('#modal_crop_image');
  var instance = M.Modal.getInstance(elem);
  instance.close();

  var image_file = document.getElementById('input_brand_image_file');
  var file_reader = new FileReader();
  file_reader.onload = function(e) {
    $('#brand_image_preview').prop('src', e.target.result);
    /*var preview_image_object = new Image();
    preview_image_object.src = e.target.result;
    console.log(preview_image_object);
    preview_image_object.vspace = image_real_x1;
    preview_image_object.hspace = image_real_y1;
    $('#brand_image_preview').prop('src', preview_image_object.src);*/
  };
  file_reader.readAsDataURL(image_file.files[0]);
}

/* ##################################################################################### */
//
/* ##################################################################################### */
function getBrand(brand_id) {
  $.ajax({
    url: get_brand_action_url,
    type: "POST",
    cache: true,
    async: true,
    data: {
      'request': true,
      'module_id': module_id
    },
    dataType: "json",
    beforeSend: function() {
      $('#preloader').show();
    },
    success: function(response) {
      var el = document.getElementById("modules_tabs");
      var instance = M.Tabs.getInstance(el);
      instance.select('tab_form');

      $('#form_title_add_module').hide();
      $('#form_title_edit_module').show();

      $('#input_module_id').val(response.id);

      $('#input_module_name').val(response.name);

      $('#input_module_data').val(response.data);
      M.textareaAutoResize($('#input_module_data'));

      if (response.status == true) {
        $('#input_module_status').val(1).formSelect();
      } else {
        $('#input_module_status').val(0).formSelect();
      }

      M.updateTextFields();

      $('#button_add_module').hide();
      $('#button_save_module').show();
    },
    complete: function() {
      $('#preloader').hide();
    },
    error: function(xhr) {
      elegant_alert.error(error_get_module);
      writeLog('Dashboard/JS/Module: Error Get Module');
    }
  });
}
