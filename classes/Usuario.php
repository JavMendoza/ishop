<?php
/**
 * Class Usuario
 *
 * Esta clase maneja los datos y las consultas con la
 * tabla de usuarios.
 */
class Usuario implements \JsonSerializable
{
	private $id_usuario;
	private $nombre;
	private $apellido;
	private $sexo;
	private $username;
	private $pass;
	private $email;
	private $imagen;
	private $nivel;
	private $productos = [];

	public static $reglas = [
		'nombre' => ['required', 'minlength:3', 'maxlength:20' ,'nombreApellido'],
		'apellido' => ['required', 'minlength:3', 'maxlength:20', 'nombreApellido'],					
		'email' => ['required', 'email'],
		'sexo' => ['required'],
		'usuario' => ['required', 'minlength:3', 'maxlength:20', 'username'],
		'password' => ['required', 'minlength:6', 'maxlength:20', 'password'],
		'nivel' => ['required']
	];

	/**
	 * Retorna todas las usuarios como un array
	 * 
     * @return Usuario[]
     */
    public static function traer()
    {
        $query = "SELECT 
        			usuarios.id AS id,
        		    usuarios.nombre AS nombre,
        		    usuarios.apellido AS apellido,
        		    usuarios.usuario AS usuario,
        		    usuarios.password AS password,
        		    usuarios.email AS email,
        		    usuarios.sexo AS sexo,
        		    usuarios.imagen AS imagen, 
        		    nivel.nombre AS nivel  
        		FROM usuarios JOIN nivel ON usuarios.id_nivel = nivel.id";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute();

        $salida = [];

        while($datosUsr = $stmt->fetch()) {
            $usr = new Usuario();
            $usr->cargarDatos($datosUsr);

            $salida[] = $usr;
        }

        return $salida;
    }

    /**
     * Retorna un Usuario por su "usuario", null de no
     * existir.
     *
     * @param $usuario
     * @return null|Usuario
     */
    public static function traerUnUsuario($usuario)
    {
        $query = "SELECT 
        			usuarios.id AS id,
        		    usuarios.nombre AS nombre,
        		    usuarios.apellido AS apellido,
        		    usuarios.usuario AS usuario,
        		    usuarios.password AS password,
        		    usuarios.email AS email,
        		    usuarios.sexo AS sexo,
        		    usuarios.imagen AS imagen,  
        		    nivel.nombre AS nivel 
        		FROM 
        			usuarios JOIN nivel ON usuarios.id_nivel = nivel.id 
        		WHERE 
        			usuarios.usuario = ? 
        		LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$usuario]);

        if ($datosUsr = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usr = new Usuario();
            $usr->cargarDatos($datosUsr);
            return $usr;
        }

        return null;
    }

    /**
     * Retorna un Usuario por su "id", null de no
     * existir.
     *
     * @param $id
     * @return null|Usuario
     */
    public static function traerUnUsuarioId($usuario)
    {
        $query = "SELECT 
                    usuarios.id AS id,
                    usuarios.nombre AS nombre,
                    usuarios.apellido AS apellido,
                    usuarios.usuario AS usuario,
                    usuarios.password AS password,
                    usuarios.email AS email,
                    usuarios.sexo AS sexo,
                    usuarios.imagen AS imagen,  
                    nivel.nombre AS nivel 
                FROM 
                    usuarios JOIN nivel ON usuarios.id_nivel = nivel.id 
                WHERE 
                    usuarios.id = ? 
                LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$usuario]);

        if ($datosUsr = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usr = new Usuario();
            $usr->cargarDatos($datosUsr);
            return $usr;
        }

        return null;
    }

    /**
     * No devuelve nada porque guardamos en el array de productos los productos propios del usuario
     *
     * @param string $usuario
     */
    public static function traerProductosUsuario($usuario)
    {
        $query = "SELECT
					productos.id AS id,
					productos.imagen AS imagen,
					productos.nombre AS nombre,
					productos.descripcion AS descripcion,
					productos.precio AS precio,
					relacion_usuario_producto.id AS id_relacion
				FROM 
					productos JOIN relacion_usuario_producto ON productos.id = relacion_usuario_producto.id_productos
				JOIN
					usuarios ON usuarios.id = relacion_usuario_producto.id_usuario
				WHERE
					usuarios.id = ?";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$usuario]);

        while ($datosUsrProd = $stmt->fetch()) {
        	$prod = new Producto();
        	$prod->cargarDatos($datosUsrProd);
            $this->setProductos($prod);
        }
    }

    /**
     * @param $usuario
     * @return boolean $data_exists
     */
    public static function chequearSiExiste($usuario)
    {
        $query = "SELECT 
                    COUNT(*)
                FROM 
                    usuarios 
                WHERE 
                    usuarios.usuario = ?
                LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$usuario["usuario"]]);

        $data_exists = ($stmt->fetchColumn() > 0) ? true : false;

        return $data_exists;
    }

    /**
     * @param $data
     */
    public static function crear($data)
    {
    	$output = [];
    	
    	$query = "INSERT INTO usuarios
              VALUES (:id, :usr, :pass, :nom, :ape, :email, :sexo, :imagen, :niv)";

        $stmt = DBConnection::getStatement($query);

        $exito = $stmt->execute([
        	"nom" => $data['nombre'],
        	"id" => null,
        	"ape" => $data['apellido'],
        	"email" => $data['email'],
        	"sexo" => $data['sexo'],
        	"usr" => $data['usuario'],
        	"pass" => $data['password'],
        	"imagen" => (!empty($data['imagen'])) ? $data['imagen'] : null,
        	"niv" => $data['nivel']
        ]);

        if(!$exito) {
        	throw new Exception('Error al crear el usuario. Puede que el email ya este en uso');
        } else { 
        	$output["status"] = "success";
        	$output["msg"] = "El usuario se ha ingresado exitosamente.";
            $db = DBConnection::getConnection();
            $output["ultimoId"] = $db->lastInsertId();
        }
        
        return $output;
    }

    /**
     * @param $data
     */
    public static function editar($data)
    {	
    	$output = [];
    	$query = "UPDATE usuarios
              	  SET 
              	  	nombre = :nom, 
              	  	apellido = :ape, 
              	  	email = :email,
              	  	usuario = :usr,
              	  	password = :pass,
              	  	sexo = :sexo,
              	  	id_nivel = :niv
              	  WHERE
              	  	id = :id";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([
        	"nom" => $data['nombre'],
        	"ape" => $data['apellido'],
        	"email" => $data['email'],
        	"sexo" => $data['sexo'],
        	"usr" => $data['usuario'],
        	"pass" => $data['password'],
        	"niv" => $data['id_nivel'],
        	"id" => $data['id']
        ]);

        if(!$exito) {
        	throw new Exception('Error al editar los datos del usuario.');
        } else { 
        	$output["status"] = "success";
        	$output["msg"] = "El usuario se ha modificado exitosamente.";
        }
        return $output;
    }

    /**
     * @param $data
     */
    public static function borrar($data)
    {
        $output = [];
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data["id"]]);
        if(!$exito) {
            throw new Exception('Error al borrar los datos.');
        } else {
        	$output["status"] = "success";
        	$output["msg"] = "Se borro el usuario exitosamente!";
        }
        return $output;
    }

	/**
     * Carga todos los datos válidos del array en las
     * propiedades correspondientes.
     *
     * @param $datosUsr
     */
    protected function cargarDatos($datosUsr)
    {
        $this->setIdUsuario($datosUsr['id']);
        $this->setUsername($datosUsr['usuario']);
        $this->setPass($datosUsr['password']);
        $this->setEmail($datosUsr['email']);
        $this->setNombre($datosUsr['nombre']);
        $this->setApellido($datosUsr['apellido']);
        $this->setSexo($datosUsr['sexo']);
        $this->setImagen($datosUsr['imagen']);
        $this->setNivel($datosUsr['nivel']);
    }

	public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**** SETTERS & GETTERS ****/

	/**
	 * @param string $prop 	Contiene el nombre de la propiedad que se está tratando de asignar.
	 * @param mixed $valor 	Contiene el valor que se está tratando de asignar.
	 */
	public function __set($prop, $valor)
	{
		if(!property_exists($this, $prop)) {
			throw new Exception("No existe la propiedad " . $prop . " en la clase Usuario");
		}

		$setterName = "set" . ucfirst($prop);

		if(method_exists($this, $setterName)) {
			$this->$setterName($valor);
		} else {
			throw new Exception("No existe el metodo " . $setterName);
		}
	}

	/**
	 * @param string $prop 	Contiene el nombre de la propiedad que se está tratando de leer.
	 */
	public function __get($prop)
	{
		if(!property_exists($this, $prop)) {
			throw new Exception("No existe la propiedad " . $prop . " en la clase Usuario");
		}

		$getterName = "get" . ucfirst($prop);

		if(method_exists($this, $getterName)) {
			$this->$getterName();
		} else {
			throw new Exception("No existe el metodo " . $getterName);
		}
	}

	/**
	 * @param mixed $nombre
	 */
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	/**
	 * @param mixed $apellido
	 */
	public function setApellido($apellido)
	{
		$this->apellido = $apellido;
	}

	/**
	 * @param mixed $sexo
	 */
	public function setSexo($sexo)
	{
		$this->sexo = $sexo;
	}
	
	/**
	 * @param mixed $id_usuario
	 */
	public function setIdUsuario($id_usuario)
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @param mixed $pass
	 */
	public function setPass($pass)
	{
		$this->pass = $pass;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @param mixed $imagen
	 */
	public function setImagen($imagen)
	{
		$this->imagen = $imagen;
	}

	/**
	 * @param mixed $nivel
	 */
	public function setNivel($nivel)
	{
		$this->nivel = $nivel;
	}

	/**
	 * @param obj $prod
	 */
	public function setProductos($prod)
	{
		$this->productos[] = $prod;
	}

	/**
     * @return mixed
     */
	public function getNombre()
	{
		return $this->nombre;
	}
	
	/**
     * @return mixed
     */
	public function getApellido()
	{
		return $this->apellido;
	}

	/**
     * @return mixed
     */
	public function getSexo()
	{
		return $this->sexo;
	}

	/**
     * @return mixed
     */
	public function getIdUsuario()
	{
		return $this->id_usuario;
	}
	
	/**
     * @return mixed
     */
	public function getUsername()
	{
		return $this->username;
	}

	/**
     * @return mixed
     */
	public function getPass()
	{
		return $this->pass;
	}
	
	/**
     * @return mixed
     */
	public function getEmail()
	{
		return $this->email;
	}

	/**
     * @return mixed
     */
	public function getImagen()
	{
		return $this->imagen;
	}
	
	/**
     * @return mixed
     */
	public function getNivel()
	{
		return $this->nivel;
	}

	/**
     * @return mixed
     */
	public function getProductos()
	{
		return $this->productos;
	}
}