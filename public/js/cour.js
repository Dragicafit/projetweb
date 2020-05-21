function makeUL(val) {
    var form = document.createElement('form');
    form.setAttribute('id', 'form');
    // Create the list element:
    var list = document.createElement('ul');
    list.setAttribute('id', 'allFacets');
    list.setAttribute('class', 'facet-list');
    for (var i = 0; i < list_ligne.length; i++) {
        // Create the list item:
        var item = document.createElement('li');
        item.setAttribute('class', 'facet');
        item.setAttribute('id', 'li_' + i);
        item.setAttribute('value', i);
        var btnPlus = document.createElement("BUTTON");
        btnPlus.setAttribute('type', 'button');
        btnPlus.setAttribute('style', 'float:right;');

        btnPlus.setAttribute('onclick', 'add_indent(' + i + ',true)');
        btnPlus.appendChild(document.createTextNode("+"));
        var btnMoins = document.createElement("BUTTON");
        btnMoins.setAttribute('type', 'button');
        btnMoins.setAttribute('style', 'float:right;');

        btnMoins.setAttribute('onclick', 'add_indent(' + i + ' ,false)');
        btnMoins.appendChild(document.createTextNode("-"));

        // Set its contents:
        item.appendChild(document.createTextNode(list_ligne[i].text));
        // Add au li:
        item.appendChild(btnPlus);
        item.appendChild(btnMoins);
        list.appendChild(item);
    }
    form.appendChild(list);
    return form;
}
document.getElementById('foo').appendChild(makeUL('exo'));
function add_indent(i, plus) {
    if (plus) {
        if (list_ligne[i].tab < 10) {
            list_ligne[i].tab++;
            var n = list_ligne[i].tab * 50;
            $('#li_' + i).css("padding-left", n + "px");
        }
    } else {
        if (list_ligne[i].tab > 1) {
            list_ligne[i].tab--;
            var n = list_ligne[i].tab * 50;
            $('#li_' + i).css("padding-left", n + "px");
        } else {
            list_ligne[i].tab = 0;
            $('#li_' + i).css("padding-left", "");
        }
    }
}

function rebuild_option() {
    var array = [];
    var res = $("#userFacets").children();
    for (let i = 0; i < res.length; i++) {
        let ligne = list_ligne[res[i].value];
        array.push({ ligne_id: ligne.ligne_id, nb_tab: ligne.tab });
    }
    return array;
}

function send_value() {
    var val = rebuild_option();
    $.ajax({
        url: '/verif/exo/{{exo_id}}',
        type: 'POST',
        dataType: 'json',
        data: {
            rep: val
        },
        async: true,

        success: function (data, status) {
            if (data) {
                document.getElementById('btn_sub').hidden = false;
            } else {
                document.getElementById('btn_sub').hidden = true;
                alert('Encore un effort, c\'est presque bon !');
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ajax request failed.');
        }
    });
}

$(function () {
    $("#allFacets, #userFacets").sortable({
        connectWith: "ul",
        delay: 150
    }).disableSelection();
});