function signIn(){
	$("#btnSignIn").prop( "disabled", true );
	let user = $("#userInput").val();
	let password = $("#passwordInput").val();
	sendAsyncPost("signIn", {user:user, password:password})
	.then(( response )=>{
		if(response.result == 2){
			window.location.href = getSiteURL();
		} else if ( response.result == 0 ){
			window.location.href = getSiteURL() + "salir";
		} else {
			showMessage(response.result, response.message, "Notificación", null)
		}
		$("#btnSignIn").prop( "disabled", false );
	})
}