<div class="top-header">
	<div class="container">
        <div class="row">
        	<div class="col-xs-12">
        		<div class="header-data">
	        		<?php $user = Auth::getUser(); ?>
			        <p class="welcome">Hola, <?= $user->getUsername() ?> Bienvenido!</p>
			        <?= $user->getNivel() ?>
		        	<a href="logout.php" class="boton-white">Cerrar sesion</a>
	        	</div>
        	</div>
        </div>
    </div>        	
</div>