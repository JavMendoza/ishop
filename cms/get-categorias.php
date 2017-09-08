<?php
echo "<h2>Administrar Categorias</h2>";
$categorias = Categoria::traerTodas();

if ($user->getNivel() == "admin") { ?>
<a href="#" data-id="save-categoria" class="boton-white btn-agregar">Agregar categoria</a>
<?php
}

if(empty($categorias)) {
	echo "<p>No hay categorias para mostrar.</p>";
} else {
?>
<div class="status"></div>
<table>
	<thead>
		<tr>
			<th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $categorias as $categoria ): ?>
			<tr>
				<td><?= $categoria->getIdCategoria(); ?></td>
				<td><?= $categoria->getNombre(); ?></td>		
				<td>
					<a class="cambiar-button" title="Editar Categoria" data-section="update-categoria" href="/api/update-categoria.php?id=<?= $categoria->getIdCategoria(); ?>">EDITAR</a>
					<?php if ($user->getNivel() == "admin"): ?>
					<a class="delete-button" title="Borrar Categoria" href="/api/delete-categorias.php?id=<?= $categoria->getIdCategoria(); ?>">BORRAR</a>
					<?php endif; ?>
				</td>	
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>	
<?php } ?>