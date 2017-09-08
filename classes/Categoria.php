<?php 
/**
 * Class Categoria
 *
 * Esta clase maneja los datos y las consultas con la
 * tabla de categorias.
 */
class Categoria implements \JsonSerializable
{
	private $id_categoria;
	private $nombre;

	public static $reglas = [
        'nombre' => ['required', 'minlength:3', 'maxlength:20' ,'nombreApellido']
    ];

    /**
     * Retorna todas las categorias como un array
     * 
     * @return Categoria[]
     */
    public static function traerTodas()
    {
        $query = "SELECT * FROM categoria";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute();

        $salida = [];

        while($datosCat = $stmt->fetch()) {
            $cat = new Categoria();
            $cat->cargarDatos($datosCat);

            $salida[] = $cat;
        }

        return $salida;
    }

    /**
     * @param categoria
     * @return null|categoria
     */
    public static function traerUnaCategoria($categoria)
    {
        $query = "SELECT * FROM categoria WHERE categoria.id = ? LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$categoria["id"]]);

        if ($datosCat = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cat = new Categoria();
            $cat->cargarDatos($datosCat);
            return $cat;
        }

        return null;
    }

    /**
     * @param $categoria
     * @return boolean $data_exists
     */
    public static function chequearSiExiste($categoria)
    {
        $query = "SELECT 
                    COUNT(*)
                FROM 
                    categoria 
                WHERE 
                    categoria.nombre = ?
                LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$categoria["nombre"]]);

        $data_exists = ($stmt->fetchColumn() > 0) ? true : false;

        return $data_exists;
    }

    /**
     * @param $data
     */
    public static function crear($data)
    {
        $output = [];
        
        $query = "INSERT INTO categoria
                  VALUES (?, ?)";

        $stmt = DBConnection::getStatement($query);

        $exito = $stmt->execute([null, $data['nombre']]);

        if (!$exito) {
            throw new Exception('Error al crear la categoria.');
        } else {
        	$output["status"] = "success";
            $output["msg"] = "La categoria se ha ingresado exitosamente.";
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
        $query = "UPDATE categoria
                  SET 
                  	nombre = ?
                  WHERE
					id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data['nombre'], $data['id']]);
        if (!$exito) {
            throw new Exception('Error al editar la categoria.');
        } else {
        	$output["status"] = "success";
            $output["msg"] = "La categoria se ha editado exitosamente.";
        }
        return $output; 
    }

    /**
     * @param $data
     */
    public static function borrar($data)
    {
        $output = [];
        $query = "DELETE FROM categoria WHERE id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data["id"]]);
        if(!$exito) {
            throw new Exception('Error al borrar los datos.');
        } else {
        	$output["status"] = "success";
            $output["msg"] = "Se borro la categoria exitosamente!";
        }
        return $output;
    }

    /**
     * @param $datosCat
     */
    protected function cargarDatos($datosCat)
    {
        $this->setIdCategoria($datosCat['id']);
        $this->setNombre($datosCat['nombre']);
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
			throw new Exception("No se puede acceder a la propiedad " . $prop);
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
			throw new Exception("No se puede acceder a la propiedad " . $prop);
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
	 * @param mixed $id_categoria
	 */
	public function setIdCategoria($id_categoria)
	{
		$this->id_categoria = $id_categoria;
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
	public function getIdCategoria()
	{
		return $this->id_categoria;
	}
}