<?php 
header('Content-Type: application/json');

require "../includes/autoload.php";
session_start();

if (!Auth::userLogged()) {
	echo json_encode([
		'status' => "error",
		'errors' => "Debe haber iniciado sesión para acceder a este recurso."
	]);
	exit;
}

if ( $_SERVER["REQUEST_METHOD"] == "PUT" ){
	$entrada = file_get_contents('php://input');
	parse_str($entrada, $putData);
	try {
		$output = Producto::editar($putData);
	} catch(Exception $e){
		$output["errors"] = $e->getMessage();
        $output["status"] = "error";
	}
} else {
	echo json_encode([
		'status' => "error",
		'errors' => "No se envio nada al recurso."
	]);
	exit;
}
echo json_encode($output);