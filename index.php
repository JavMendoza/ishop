<?php header('Content-Type: text/html; charset=UTF-8'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>InstantShop</title>
    
    <link href='http://fonts.googleapis.com/css?family=Signika+Negative:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script type="text/javascript" src="js/functions.js"></script>
    <script type="text/javascript" src="js/validator.js"></script>
    <script type="text/javascript" src="js/app.js"></script>

<!--[if lt IE 9]>
  <script src="js/html5shiv.min.js"></script>
  <script src="js/respond.min.js"></script>
<![endif]-->
</head>
<body>	
	<?php include("templates/includes/top_header.php"); ?>
	<main class="container"> 
		<?php include("templates/includes/header.php"); ?>
	    <section class="row page-index active">     
	    	<div class="col-xs-12">	        	
	            <?php include('templates/index.php'); ?>		            
	        </div> 
	    </section>
	    <section class="row page-login">     
	    	<div class="col-xs-12">	        	
	            <?php include('templates/login.php'); ?>		            
	        </div> 
	    </section>
	    <section class="row page-registro">     
	    	<div class="col-xs-12">	        	
	            <?php include('templates/registro.php'); ?>		            
	        </div> 
	    </section>
    </main>    
    <?php include("templates/includes/footer.php"); ?>    
</body>
</html>