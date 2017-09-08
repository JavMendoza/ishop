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

if ( $_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST) && !empty($_FILES) && isset($_FILES) ) {
	$validator = new Validator;
	$data = [];
	$data["postfile"] = $_POST;
	$data["postfile"]["imagen"] = $_FILES["imagen"]["type"]; 
	$validator->validate($data["postfile"], Producto::$reglas);

	if ($validator->exito()){
		$prodExists = Producto::chequearSiExiste($_POST);
        if ($prodExists == false) {
			$outputGD = GDLibrary($_FILES, 135, "../cms/imagenes/productos/");

			if ($outputGD["status"] == "error") {
				echo json_encode($outputGD);
				exit;
			} else {
				$data["postfile"]["imagen"] = $outputGD["nombreImagen"];
			}
			try{
				$output = Producto::crear($data["postfile"]);
			} catch(Exception $e) {
				$output["errors"] = $e->getMessage();
            	$output["status"] = "error";
			}
		} else {
        	$output["errors"] = "Producto ya existente bajo la misma categoria, por favor ingrese otro.";
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