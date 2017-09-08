<?php	
	$categorias = Categoria::traerTodas();
    $cat_sexo = CategoriaGenero::traerTodas();
?>
<form id="form-alta-prod" action="../api/save-productos.php" method="post" enctype="multipart/form-data" class="form-cms" novalidate>
    <div class="status"></div>
	<fieldset class="datos-producto">
        <label>Nombre*
            <input type="text" name="nombre" />
        </label>
        <label>Descripcion*
            <textarea name="descripcion"></textarea>
        </label>
    	<label>Precio*
            <input type="text" name="precio" />
        </label>
        <label>Foto*
            <input type="file" name="imagen" />
        </label>
        <label>Stock
            <input type="text" name="stock" />
        </label>
    </fieldset>
    
    <fieldset class="datos-adicionales">
        <label>Elija la categoria a la que pertenece el producto:*
            <select name="id_categoria">
            	<?php
                foreach($categorias as $cate): ?>
                    <option value="<?= $cate->getIdCategoria();?>"><?= $cate->getNombre();?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
        <label>Elija a que sexo apunta el producto:*
            <select name="id_cat_sexo">
                <?php
                foreach($cat_sexo as $cate_sexo): ?>
                    <option value="<?= $cate_sexo->getIdcatsexo();?>"><?= $cate_sexo->getNombre();?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
    </fieldset>
    <p class="obligatorios">Campos con (*) obligatorios.</p>
	<div class="submit"><input type="submit" value="GUARDAR" /></div>
</form>	

