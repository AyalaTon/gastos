<?php

require_once '../src/connection/open_connection.php';

class user{
	
	public function updateUserPassword($idUser, $newPassword){
		$dbClass = new DataBase();
		return $dbClass->sendQuery("UPDATE usuario SET pass = ? WHERE id = ?", array('si', $newPassword, $idUser), "BOOLE");
	}
	public function setNewTokenAndSession($idUser){
		$dbClass = new DataBase();
		$newToken = bin2hex(random_bytes((100 - (100 % 2)) / 2));
		$responseQuery = $dbClass->sendQuery('UPDATE usuario SET token = ? WHERE id = ?', array('si', $newToken, $idUser), "BOOLE");
		if($responseQuery->result == 2){
			$responseQuery = null;
			$responseQuery = $this->getUserById($idUser);
			if($responseQuery->result == 2){
				if($responseQuery2->result == 2){
					$objectSession = new \stdClass();
					$objectSession->idUser = $responseQuery->objectResult->id;
					$objectSession->nick = $responseQuery->objectResult->user;
					$objectSession->token = $responseQuery->objectResult->token;
					$_SESSION['userSession'] = $objectSession;
					unset($responseQuery->objectResult);
				}
			}
		}else $responseQuery->message = "Un error interno no permitio iniciar sesión con este usuario.";
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
		$responseQuery = $dbClass->sendQuery("SELECT * FROM usuario WHERE user = ?", array('s', $user), "OBJECT");
		// var_dump($responseQuery);
		if($responseQuery->result == 1)
			$responseQuery->message = "El identificador ingresado no corresponde a un usuario registrado.";
		return $responseQuery;
	}
}