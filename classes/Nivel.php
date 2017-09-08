<?php 
/**
 * Class Nivel
 *
 * Esta clase maneja los datos y las consultas con la
 * tabla de nivel.
 */
class Nivel
{
	private $id_nivel;
	private $nombre;

	/**
     *  Retorna todos los niveles como un array
     * 
     * @return Nivel[]
     */
    public static function traerTodas()
    {
        $query = "SELECT * FROM nivel";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute();

        $salida = [];

        while($datosNiv = $stmt->fetch()) {
            $niv = new Nivel();
            $niv->cargarDatos($datosNiv);

            $salida[] = $niv;
        }

        return $salida;
    }

    /**
     * @param nivel
     * @return null|nivel
     */
    public static function traerUnNivel($nivel)
    {
        $query = "SELECT * FROM nivel WHERE nivel.id = ? LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$nivel["id"]]);

        if ($datosNiv = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $niv = new Nivel();
            $niv->cargarDatos($datosNiv);
            return $niv;
        }

        return null;
    }

    /**
     * @param $datosNiv
     */
    protected function cargarDatos($datosNiv)
    {
        $this->setIdNivel($datosNiv['id']);
        $this->setNombre($datosNiv['nombre']);
    }

    /**
     * @param $data
     */
    public static function crear($data)
    {
        $isNiv = self::traerUnNivel($data);
        if ($isNiv === null) {
	        $query = "INSERT INTO nivel (id, nombre)
	                  VALUES (?, ?)";

	        $stmt = DBConnection::getStatement($query);

	        $exito = $stmt->execute([$data['id'], $data['nombre']]);

	        if (!$exito) {
	            throw new Exception('Error al insertar los datos.');
	            $output = "Error al crear el nivel.";
	        } else {
	        	$output = true;
	        }
	    } else {
        	$output = "nivel ya existente, por favor ingrese otro.";
        }
        return $output; 
    }

    /**
     * @param $data
     */
    public static function editar($data)
    {   
        $query = "UPDATE nivel
                  SET 
                  	nombre = ?
                  WHERE
					id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data['nombre'], $data['id']]);
        if (!$exito) {
            throw new Exception('Error al editar los datos.');
            $output = "Error al editar nivel.";
        } else {
        	$output = true;
        }
        return $output; 
    }

    /**
     * @param $data
     */
    public static function borrar($data)
    {
        $query = "DELETE FROM nivel WHERE id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data["$id"]]);
        if(!$exito) {
            throw new Exception('Error al borrar los datos.');
            $output = "Error al borrar los datos";
        } else {
        	$output = true;
        }
        return $output;
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
	 * @param mixed $id_nivel
	 */
	public function setIdNivel($id_nivel)
	{
		$this->id_nivel = $id_nivel;
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
	public function getIdNivel()
	{
		return $this->id_nivel;
	}
}