<?php 
	require_once "includes/autoload.php"; 
	session_start();
?>

<div class="top-header">
	<div class="container">
        <div class="row">
        	<div class="col-xs-12">
        	<?php	
		        if (Auth::userLogged()){
		        	echo "<a href='cms/logout.php' class='login' id='logout'>Cerrar Sesion</a>";

		          	$user = Auth::getUser();
		          	if ($user){
		              	echo '<p>Hola, <a href="cms/index.php" class="login">'.$user->getUsername().'</a> Bienvenido!</p>';
		              	echo "<a href='cms/index.php' class='login'>Administrar</a>";
		        	}
		        } else {			
					echo "<a href='#' class='login' id='login'>Iniciar Sesion</a>";
					echo "<a href='#' class='login registro' id='registro'>Registrarse</a>";
				}			
			?>
			</div>
        </div>
    </div>        	
</div>