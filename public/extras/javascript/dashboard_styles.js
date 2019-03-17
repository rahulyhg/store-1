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

}

/* ##################################################################################### */
function getStyles() {

}

/* ##################################################################################### */
function saveStyles(form) {

}
