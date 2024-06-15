function getSiteURL(){
	let url = window.location.href;
	if(url.includes("localhost"))
		return '/gastos/public/';
	else
		return 'http://gastos.casa.byg.uy/';
}


function showMessage(result, message, titulo, modal){
    $("#modalShowMessageLabel").text(titulo)
    $("#modalShowMessageBody").text(message)
    $("#modalShowMessageHeader").removeClass()
    if (result == 0)
		$('#modalShowMessageHeader').addClass('modal-header alert-danger');
	else if (result == 2)
		$('#modalShowMessageHeader').addClass('modal-header alert-success');
	else if (result == 1)
		$('#modalShowMessageHeader').addClass('modal-header alert-warning');

    if (modal !== null) {
        $("#" + modal).modal("hide")
        $('#btnModalShowMessage').off('click').click(function() {
            $("#" + modal).modal("show")
        });
    }
    $("#modalShowMessage").modal("show")
}

function openModalNew(){
    $("#modalSelectNewOption").modal("show")
    
}