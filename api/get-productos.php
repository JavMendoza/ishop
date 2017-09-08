<?php
header('Content-Type: application/json');

require_once "../includes/autoload.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){
	if (!empty($_GET) && isset($_GET)) {
		if (!empty($_GET['id_categoria'])) {
			$productos = Producto::traer($_GET['id_categoria']);
		} else if (!empty($_GET['id'])) {
			$productos = Producto::traerUnProducto($_GET);
		}
	} else {
		$productos = Producto::traer(null);
	}
} else {
	header("Location: ../index.php");
	exit;
}
echo json_encode($productos);