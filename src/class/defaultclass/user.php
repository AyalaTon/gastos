<?php

require_once '../src/connection/open_connection.php';

class user{
	
	public function updateUserPassword($idUser, $newPassword){
		$dbClass = new DataBase();
		return $dbClass->sendQuery("UPDATE usuario SET pass = ? WHERE id = ?", array('si', $newPassword, $idUser), "BOOLE");
	}

	public function getListUsers(){
		$dbClass = new DataBase();
		return $dbClass->sendQuery("SELECT * FROM usuario", null, "LIST");
	}

	public function setNewTokenAndSession($idUser){
		$dbClass = new DataBase();
		$newToken = bin2hex(random_bytes((100 - (100 % 2)) / 2));
		$responseQuery = $this->updateToken($idUser, $newToken);
		if($responseQuery->result == 2){
			$responseQuery = null;
			$responseQuery = $this->getUserById($idUser);
			if($responseQuery->result == 2){
				$objectSession = new \stdClass();
				$objectSession->idUser = $responseQuery->objectResult->id;
				$objectSession->user = $responseQuery->objectResult->user;
				$objectSession->token = $responseQuery->objectResult->token;
				$_SESSION['userSession'] = $objectSession;
				unset($responseQuery->objectResult);
			}
		} else $responseQuery->message = "Un error interno no permitio iniciar sesión con este usuario.";
		return $responseQuery;
	}

	public function getUserById($idUser){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->sendQuery("SELECT * FROM usuario WHERE id = ?", array('i', $idUser), "OBJECT");
		// var_dump($responseQuery);
		if($responseQuery->result == 1)
			$responseQuery->message = "El identificador ingresado no corresponde a un usuario registrado.";
		return $responseQuery;
	}
	public function getUserByUser($user){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->sendQuery("SELECT * FROM usuario WHERE usuario.user = ?", array('s', $user), "OBJECT");
		if($responseQuery->result == 1)
			$responseQuery->message = "El identificador ingresado no corresponde a un usuario registrado.";
		return $responseQuery;
	}

	public function updateToken($idUser, $newToken){
		$dataBaseClass = new DataBase();
		$sql = "UPDATE usuario SET token = ? WHERE id = ?";
		$response = $dataBaseClass->sendQuery($sql, array('ss', $newToken, $idUser), "BOOLE");
		return $response;
	}
}