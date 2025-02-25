$('form').on('submit', function (e) {
    e.preventDefault(); // Evitar el envío inmediato del formulario

    // Mostrar el overlay con el spinner
    $('.spinner-overlay').show();

    // Obtener el valor del botón que se hizo clic
    var buttonName = $(this).find('button[type=submit]:focus').attr('name');

    // Simular un retraso para demostrar el spinner (elimina esto en producción)
    var form = this;
    // Agregar el valor del botón como un campo oculto al formulario
    $('<input>').attr({
        name: buttonName
    }).appendTo(form);

    // Enviar el formulario después del retraso simulado
    form.submit();

});



