<?php
echo "<h2>Administrar Usuarios</h2>";
$usuarios = Usuario::traer();

if ($user->getNivel() == "admin") { ?>
<a href="#" data-id="save-usuario" class="boton-white btn-agregar">Agregar Usuario</a>
<?php
}
if(empty($usuarios)) {
	echo "<p>No hay usuarios para mostrar.</p>";
} else {
?>
<div class="status"></div>
<table>
	<thead>
		<tr>
			<th>ID</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Nivel</th>
            <th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $usuarios as $usuario ): ?>
			<tr>
				<td><?= $usuario->getIdUsuario(); ?></td>
				<td><?php if( !empty($usuario->getImagen()) ) { ?>
					<img alt="<?= $usuario->getNombre(); ?>" src="imagenes/usuarios/<?= $usuario->getImagen(); ?>" />
					<?php } else { ?>
					No posee imagen.
					<?php } ?>
				</td>	
				<td><?= $usuario->getNombre(); ?></td>
				<td><?= $usuario->getApellido(); ?></td>
				<td><?= $usuario->getEmail(); ?></td>
				<td><?= $usuario->getNivel(); ?></td>				
				<td>
					<a class="cambiar-button" data-section="update-usuario" title="Editar Usuario" href="/api/update-usuario.php?id=<?= $usuario->getIdUsuario(); ?>">EDITAR</a>
					<?php if ($user->getNivel() == "admin"): ?>
					<a class="delete-button" title="Borrar Usuario" href="/api/delete-usuarios.php?id=<?= $usuario->getIdUsuario(); ?>">BORRAR</a>
					<?php endif; ?>
				</td>	
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php } ?>	