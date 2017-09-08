<?php
header('Content-Type: application/json');

require_once "../includes/functions.php";
require_once "../includes/autoload.php";
session_start();

if (!Auth::userLogged()) {
	echo json_encode([
		'status' => "error",
		'errors' => "Debe haber iniciado sesión para acceder a este recurso."
	]);
	exit;
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST) ) {
	$data = [];
	$data["postfile"] = $_POST;

	if (!empty($_FILES) && isset($_FILES)) {
		$data["postfile"]["imagen"] = $_FILES["imagen"]["type"];
	}
	$validator = new Validator;
	$validator->validate($data["postfile"], Usuario::$reglas);

	if ($validator->exito()){
		$usrExists = Usuario::chequearSiExiste($_POST);
        if ($usrExists == false) {
			if ( !empty($data["postfile"]["imagen"]) && isset($data["postfile"]["imagen"]) ){
				$outputGD = GDLibrary($_FILES, 150, "../cms/imagenes/usuarios/");

				if ($outputGD["status"] == "error") {
					echo json_encode($outputGD);
					exit;
				} else {
					$data["postfile"]["imagen"] = $outputGD["nombreImagen"];
				}
			} 
			
			$data["postfile"]["password"] = password_hash($data["postfile"]["password"], PASSWORD_DEFAULT);
			$output = Usuario::crear($data["postfile"]);

		} else {
        	$output["errors"] = "Usuario ya existente, por favor ingrese otro.";
            $output["status"] = "error";
        }
	} else {
		$output["status"] = "error";
		$output["errors"] = $validator->getErrores();
	}
} else {
	echo json_encode([
		'status' => "error",
		'errors' => "No se envio nada al recurso."
	]);
	exit;
}
echo json_encode($output);