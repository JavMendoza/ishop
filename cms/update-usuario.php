<form id="form-editar-usu" action="../api/update-usuarios.php" method="post" class="form-cms" novalidate>
    <div class="status"></div>
    <input type="hidden" name="id" />
	<fieldset class="datos-usuario">
        <label>Nombre*
            <input type="text" name="nombre" />
        </label>
        <label>Apellido*
            <input type="text" name="apellido" />
        </label>
    	<label>Email*
            <input type="email" name="email" />
        </label>
        <label>Usuario*
            <input type="text" name="usuario" />
        </label>
        <label>Password*
            <input type="text" name="password" />
        </label>
        <label>Sexo*
            <input type="radio" name="sexo" value="m" checked />Masculino
            <input type="radio" name="sexo" value="f" />Femenino
        </label>
    </fieldset>
    
    <fieldset class="datos-adicionales">
        <label>Elija el nivel al que pertenece el usuario:*
            <select name="nivel">
            	<?php
                foreach($niveles as $nivel): ?>
                    <?php $selected = ($usuario->getNivel() == $nivel->getIdNivel()) ? "selected" : ""; ?>
                    <option <?= $selected  ?> value="<?= $nivel->getIdNivel();?>"><?= $nivel->getNombre();?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
    </fieldset>
    <p class="obligatorios">Campos con (*) obligatorios.</p>
	<div class="submit"><input type="submit" value="GUARDAR" /></div>
</form>	