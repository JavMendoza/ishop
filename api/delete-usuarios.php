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

if ( !empty($_SERVER['QUERY_STRING']) && isset($_SERVER['QUERY_STRING']) ) {
	$entrada = $_SERVER['QUERY_STRING'];
	parse_str($entrada, $deleteData);

    $user = Auth::getUser();
    if ($user->getIdUsuario() != $deleteData["id"]) {
    	try{
			$output = Usuario::borrar($deleteData);
    	} catch(Exception $e) {
            $output["errors"] = $e->getMessage();
            $output["status"] = "error";
    	}
    } else {
    	echo json_encode([
			'status' => "error",
			'errors' => "Ya has iniciado sesion, no puedes borrar tu usuario mientras estes logeado."
		]);
		exit;
    }
} else {
	echo json_encode([
		'status' => "error",
		'errors' => "No se envio nada al recurso."
	]);
	exit;
}
echo json_encode($output);