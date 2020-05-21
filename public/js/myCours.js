
function show_exo(cour_id) {
    let elem = $('[name ="tr_' + cour_id + '"]');
    if (elem.is(':visible')) {
        elem.slideUp("slow");
    } else {
        elem.slideDown("slow");
    }
}