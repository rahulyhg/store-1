var colors_pallete = {

};

function openColorsPalette(input) {
  var elem = $('#modal_colors_palette');
  var instance = M.Modal.getInstance(elem);
  instance.open();

  input[0].value = "skdjfhksdjfhksdh";
  M.updateTextFields();

  console.log(input);

}

function preselectColor(selected_color) {
  console.log(selected_color);
  elegant_alert.success(selected_color[0].attributes.color.value);
}
