/* ##################################################################################### */
//
/* ##################################################################################### */
function openColorsPalette() {
  var elem = $('#modal_colors_palette');
  var instance = M.Modal.getInstance(elem);
  instance.open();
}

function preselectColor(color) {
  selected_color = color;
}

function unselectColors() {
  $('.color-palette').each(function() {
    $(this).removeClass('z-depth-5');
  });
}

/* ##################################################################################### */
function serializeForm() {

}

/* ##################################################################################### */
function getStyles() {

}

/* ##################################################################################### */
function saveStyles(form) {

}
