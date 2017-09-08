<?php
echo "<h2>Administrar Productos</h2>";
$productos = Producto::traer(false);

if ($user->getNivel() == "admin") { ?>
<a href="#" data-id="save-producto" class="boton-white btn-agregar">Agregar Producto</a>
<?php
}

if(empty($productos)) {
	echo "<p>No hay productos para mostrar.</p>";
} else {
?>
<div class="status"></div>
<table>
	<thead>
		<tr>
			<th>ID</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Precio</th>
            <th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $productos as $producto ): ?>
			<tr>
				<td><?= $producto->getIdProducto(); ?></td>
				<td><img alt="<?= $producto->getNombre(); ?>" src="imagenes/productos/<?= $producto->getImagen(); ?>" /></td>	
				<td><?= $producto->getNombre(); ?></td>
				<td><?= $producto->getDescripcion(); ?></td>
				<td>$<?= $producto->getPrecio(); ?></td>				
				<td>
					<a class="cambiar-button" title="Editar Producto" data-section="update-producto" href="/api/update-productos.php?id=<?= $producto->getIdProducto(); ?>">EDITAR</a>
					<?php if ($user->getNivel() == "admin"): ?>
						<a class="delete-button" title="Borrar Producto" href="/api/delete-productos.php?id=<?= $producto->getIdProducto(); ?>">BORRAR</a>
					<?php endif; ?>
				</td>	
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>	
<?php } ?>