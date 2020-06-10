$(document)
.on("submit", "form.js-enviar", function(event) {
	event.preventDefault();

	var _form = $(this);
	var _error = $(".js-error", _form);

	var data_enviarSMS = {
		BaseDatosRef: $("input#NombreDBref", _form).val(),
		BaseDatos: $("input#NombreDB", _form).val(),
		NombreArchivo: $("input#NombreArchivo", _form).val()
	};


	// Assuming the code gets this far, we can start the ajax process
	_error.hide();

	$.ajax({
		type: 'POST',
		url: '/Blueberry/code/envio_prueba_folios.php',
		data: data_enviarSMS,
		dataType: 'json',
		async: true,
	})
	.done(function ajaxDone(data) {
		// Whatever data is 
		console.log(data);
		if(data.redirect !== undefined) {
			 window.location = data.redirect;
		} else if(data.error !== undefined) {
			_error
				.text(data.error)
				.show();
		}

	})
	.fail(function ajaxFailed(e) {
		// This failed 
		console.log(e);
	})
	.always(function ajaxAlwaysDoThis(data) {
		// Always do
		console.log('Always');
	})

	return false;
})


