<?php
header('Content-Type: application/json');

require_once '../includes/autoload.php';
session_start();

if ( $_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST) ) {
	$output = [];
	$validator = new Validator;
	$validator->validate($_POST, Auth::$reglas);

	if ($validator->exito()) {
		if (Auth::login($_POST["usuario"], $_POST["password"])){
			$output["data"]["user"] = $_SESSION["usuario"];
			$output["status"] = "success";
		} else {
			$output["status"] = "error";
			$output["errors"] = "Usuario y/o password incorrecto."; 
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