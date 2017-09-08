<?php
header('Content-Type: application/json');

require_once "../includes/autoload.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){
	if (!empty($_GET) && isset($_GET) && !empty($_GET['id'])) {
		if ($_GET['id'] != "false") {
			$categorias = Categoria::traerUnaCategoria($_GET);
		} else {
			$categorias = Categoria::traerTodas();
		}
	} else {
		$categorias = Categoria::traerTodas();
	}
} else {
	header("Location: ../index.php");
	exit;
}
echo json_encode($categorias);