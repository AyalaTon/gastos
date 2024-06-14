function sendAsyncPost(nombreFuncion, parametros){
	return new Promise( function(resolve, reject){
		$.ajax({
			async: true,
			url: getSiteURL() + nombreFuncion,
			type: "POST",
			data: parametros,
			timeout: 120000, //miliseconds
			success: function (response) {
				response = response.trim();
				var response = jQuery.parseJSON(response);
				resolve(response);
			},
			error: function ( jqXHR, textStatus, errorThrown) {
				var response = {result:0, message:errorThrown}
				resolve(response);
			},
		});
	});
}

function sendAsyncPostForm(nombreFuncion, formData){
	return new Promise( function(resolve, reject){
		$.ajax({
			async: true,
			url: getSiteURL() + nombreFuncion,
			type: "POST",
			data: formData,
			timeout: 120000, //miliseconds
			success: function (response) {
				response = response.trim();
				var response = jQuery.parseJSON(response);
				resolve(response);
			},
			error: function ( jqXHR, textStatus, errorThrown) {
				var response = {result:0, message:errorThrown}
				resolve(response);
			},
			cache: false,
	        contentType: false,
	        processData: false
		});
	});
}

function sendAsyncPut(nombreFuncion, parametros){
	return new Promise( function(resolve, reject){
		$.ajax({
			async: true,
			url: getSiteURL() + nombreFuncion,
			type: "PUT",
			data: parametros,
			timeout: 120000, //miliseconds
			success: function (response) {
				response = response.trim();
				var response = jQuery.parseJSON(response);
				resolve(response);
			},
			error: function ( jqXHR, textStatus, errorThrown) {
				var response = {result:0, message:errorThrown}
				resolve(response);
			},
		});
	});
}