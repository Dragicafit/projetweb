var nb_solution = 1;
function new_solution() {
    nb_solution++;
    let row = document.createElement('div');
    row.setAttribute('class', 'row');
    row.setAttribute('id', 'solution_' + nb_solution);
    row.setAttribute('style', 'margin-top:10px;');
    let col = document.createElement('div');
    col.setAttribute('class', 'col-7');
    let field = document.createElement('fieldset');
    let h = document.createElement('h5');
    let area = document.createElement('textarea');
    area.setAttribute('class', 'form-control');
    area.setAttribute('rows', '5');
    area.setAttribute('cols', '33');
    area.setAttribute('name', 'solution' + nb_solution);
    area.setAttribute('placeholder', 'Tapez votre code');
    area.setAttribute('required', 'true');
    h.appendChild(area);
    field.appendChild(h);
    col.appendChild(field);
    row.appendChild(col);
    document.getElementById("count_sol").setAttribute('value', nb_solution);
    let elem = document.getElementById("solution_" + (nb_solution - 1));
    console.log(nb_solution);
    elem.after(row);
}