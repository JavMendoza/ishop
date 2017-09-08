<?php
header('Content-Type: application/json');

require_once '../includes/autoload.php';
session_start();

if( $_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST) ){
//print_r($_POST);
	$output = [];
	$validator = new Validator;

	$validator->validate($_POST, Usuario::$reglas);
	
	if ($validator->exito()){
		$usrExists = Usuario::chequearSiExiste($_POST);
        if ($usrExists == false) {
			$datosPost = $_POST; 
			$datosPost["password"] = password_hash($datosPost["password"], PASSWORD_DEFAULT);
			try{
				$output = Usuario::crear($datosPost);
			} catch(Exception $e) {
				$output["errors"] = $e->getMessage();
            	$output["status"] = "error";
			}
		} else {
        	$output["errors"] = "Usuario ya existente, por favor ingrese otro.";
            $output["status"] = "error";
        }
	} else {
		$output["status"] = "error";
		$output["errors"] = $validator->getErrores();
	}
} else {
	header("Location: ../index.php");
	exit;
}
echo json_encode($output);
?>