<?php
header('Content-Type: application/json');

require_once "../includes/autoload.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){
	if (!empty($_GET) && isset($_GET) && !empty($_GET["id"])) {
		if ($_GET['id'] != "false") {
			$usuarios = Usuario::traerUnUsuarioId($_GET["id"]);
		} else {
			$usuarios = Usuario::traer();
		}
	} else {
		$usuarios = Usuario::traer();
	}
} else {
	header("Location: ../index.php");
	exit;
}
echo json_encode($usuarios);