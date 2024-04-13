<?php
include_once "../src/config.php";
if (class_exists('DataBase'))
	return;

class DataBase {
	public static function connection(){
		static $connection = null;
		if (null === $connection) {
			$connection = new mysqli(DB_HOST, DB_USR, DB_PASS, DB_DB)
			or die("No se puede conectar con la Base de Datos");
		}
		$connection->set_charset("utf8");
		return $connection;
	}

	public function sendQuery($sql, $params, $tipoRetorno){
		$dbClass = new DataBase();
		$response = new \stdClass();

		$connection = $dbClass->connection();
		if($connection){
			$query = $connection->prepare($sql);
			$paramsTemp = array();
			if($params){
				foreach($params as $key => $value)
					$paramsTemp[$key] = &$params[$key];

				call_user_func_array(array($query, 'bind_param'), $paramsTemp);
			}
			if (!$query) {
				// Check for errors
				var_dump($connection->error);
				$response->result = 0;
				$response->message = "Error preparing SQL statement: " . $connection->error;
			} else {
				if($query->execute()){
					$result = $query->get_result();
					
					if($tipoRetorno == "LIST"){
						$arrayResult = array();
						while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
							$arrayResult[] = $row;
						}
						if(sizeof($arrayResult) > 0){
							$response->result = 2;
							$response->listResult = $arrayResult;
						} else $response->result = 1;
					} else if($tipoRetorno == "OBJECT") {
						$objectResult = $result->fetch_object();
						if(!is_null($objectResult)){
							$response->result = 2;
							$response->objectResult = $objectResult;
						} else $response->result = 1;
					}else if($tipoRetorno == "BOOLE"){
						$response->result = 2;
						$response->id = $connection->insert_id;
					}
				} else {
					$response->result = 0;
					if(strpos($query->error, "Duplicate") !== false) {
						$msjError = $query->error;
						$msjError = str_replace("Duplicate entry", "BASE DE DATOS: El valor ", $msjError);
						$msjError = str_replace(" for key", " ya fue ingresado previamente para el campo ", $msjError);
						$response->message = $msjError . "(dato único)";
					} else if(strpos($query->error, "Column") !== false) {
						$msjError = $query->error;
						$msjError = str_replace("Column", "BASE DE DATOS: La columna", $msjError);
						$msjError = str_replace("cannot be", "no puede ser", $msjError);
						$response->message = $msjError;
					} else {
						$response->message = "BASE DE DATOS: " . $query->error;
					}
				}
			}
		}else{
			$response->result = 0;
			$response->message = "Ocurrió un error y no se pudo acceder a la base de datos del sistema.";
		}
		return $response;
	}

	public function getDataBaseNameTable(){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->sendQuery('SHOW TABLES', null, "LIST");
		if($responseQuery->result == 2){
			$arrayResult = array();
			foreach ($responseQuery->listResult as $key => $table) {
				$arrayResult[] = $table['Tables_in_' . DB_DB];
			}
			$responseQuery->listResult = $arrayResult;
		}else if($responseQuery->result == 1) $responseQuery->message = "Las tablas de la base de datos no fueron genereadas.";

		return $responseQuery;
	}

	public function importDataBase(){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->getDataBaseNameTable();
		if($responseQuery->result != 2){
			$filename = dirname(dirname(__DIR__)) . "/src/connection/sigecom.sql";
			$contentQuerys = file_get_contents($filename);
			return $dbClass->connection()->multi_query($contentQuerys);
		}
	}

	public function clearTables(){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->getDataBaseNameTable();
		if($responseQuery->result == 2){
			foreach ($responseQuery->listResult as $key => $value)
				$dbClass->sendQuery("DELETE FROM " . $value, null, "BOOLE");
		}
	}
}
