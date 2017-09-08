<form action="../api/registro-procesar.php" method="post" class="form-login form-registro" id="form-registro" novalidate>
    <div class="error"></div>
    <div>
      <label>Nombre:</label> 
      <input type="text" name="nombre" id="nombre" />
    </div>
    <div>
      <label>Apellido:</label> 
      <input type="text" name="apellido" id="apellido" />
    </div>
    <div>
      <label>Email:</label> 
      <input type="email" name="email" id="email" />
    </div>
     <div>
      <label>Sexo:</label> 
      <input type="radio" name="sexo" value="f" /> Mujer
      <input type="radio" name="sexo" value="m" checked /> Hombre
    </div>
    <div>
      <label>Usuario:</label> 
      <input type="text" name="usuario" id="usuario" />
    </div>
    <div>
      <label>Contraseña:</label> 
      <input type="password" name="password" id="clave" />
    </div>
    <div>
      <label>Nivel:</label> 
      <select name="nivel">
        <option value="1">Admin</option>
        <option value="2">Editor</option>
      </select>
    </div>
	<div><input type="submit" value="Registrarse" /></div>
</form>	