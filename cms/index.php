<?php
    require_once '../includes/autoload.php';	
    session_start();

    if (!Auth::userLogged()) {
        header('Location: ../index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  	<meta charset="utf-8">  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Panel de control | InstantShop</title>
    
    <link href='http://fonts.googleapis.com/css?family=Signika+Negative:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css" type="text/css" />
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript" src="js/cms.js"></script>
    
<!--[if lt IE 9]>
  <script src="../js/html5shiv.min.js"></script>
  <script src="../js/respond.min.js"></script>
<![endif]-->
</head>
<body>
    <?php include("templates/top_header.php"); ?>
    <main class="container cms">
        <div class="row">
            <div class="col-xs-12">
                <h1>Panel de control</h1>
            </div>
        </div>
        
        <ul class="nav nav-pills nav-justified navigation-cms">
            <li class="active"><a href="#" data-id="productos">PRODUCTOS</a></li>
            <li><a href="#" data-id="categorias">CATEGORIAS</a></li>
            <li><a href="#" data-id="usuarios">USUARIOS</a></li>
        </ul>

        <section id="page-usuarios" class="row">     <!-- USUARIOS -->
            <div class="col-xs-12 listado-section section-interior active">
                <?php include('get-usuarios.php'); ?>
            </div> 
            <div id="save-usuario" class="col-xs-12 save-section section-interior">  
               <?php include('save-usuario.php'); ?>    
            </div>
            <div id="update-usuario" class="col-xs-12 section-interior">  
               <?php include('update-usuario.php'); ?>    
            </div> 
        </section>

        <section id="page-categorias" class="row">  <!-- CATEGORIAS -->   
            <div class="col-xs-12 listado-section section-interior active">  
               <?php include('get-categorias.php'); ?>    
            </div>
            <div id="save-categoria" class="col-xs-12 save-categoria save-section section-interior">  
               <?php include('save-categoria.php'); ?>    
            </div>
            <div id="update-categoria" class="col-xs-12 section-interior">  
               <?php include('update-categoria.php'); ?>    
            </div>
        </section>

        <section id="page-productos" class="row active"> <!-- PRODUCTOS -->
            <div class="col-xs-12 listado-section section-interior active">
                <?php include('get-productos.php'); ?>
            </div>   
            <div id="save-producto" class="col-xs-12 save-producto save-section section-interior">  
               <?php include('save-producto.php'); ?>    
            </div>
            <div id="update-producto" class="col-xs-12 section-interior">  
               <?php include('update-producto.php'); ?>    
            </div>  
        </section>
    </main>  
    <?php include("../templates/includes/footer.php"); ?>
</body>
</html>