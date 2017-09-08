<?php
header('Content-Type: application/json');

require_once "../includes/autoload.php";
session_start();

if (!Auth::userLogged()) {
	echo json_encode([
		'status' => "error",
		'errors' => "Debe haber iniciado sesión para acceder a este recurso."
	]);
	exit;
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST) )  {
	$validator = new Validator;
	$validator->validate($_POST, Categoria::$reglas);

	if ($validator->exito()){
		$catExists = Categoria::chequearSiExiste($_POST);
        if ($catExists == false) {
        	try{
				$output = Categoria::crear($_POST);
        	} catch(Exception $e) {
	            $output["errors"] = $e->getMessage();
	            $output["status"] = "error";
        	}
		} else {
        	$output["errors"] = "Categoria ya existente, por favor ingrese otra.";
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