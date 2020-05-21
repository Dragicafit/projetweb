function get_user_note(user_id, cour_id, $exo_id, $type) {
    if (user_id == -1) {
        $('#note_' + cour_id).html("---");
        return;
    }

    $.ajax({
        url: '/result/user',
        type: 'POST',
        dataType: 'json',
        data: {
            type: $type,
            user: user_id,
            cour: cour_id,
            exo: $exo_id
        },
        async: true,

        success: function (data, status) {
            $('#note_' + cour_id).html(data)
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ajax request failed.');
        }
    });
}