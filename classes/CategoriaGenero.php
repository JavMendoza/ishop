<?php 
/**
 * Class CategoriaGenero
 *
 * Esta clase maneja los datos y las consultas con la
 * tabla de categoria_sexo.
 */
class CategoriaGenero
{
	private $id_cat_sexo;
	private $nombre;

	/**
   *  Retorna todas las categorias sexo como un array
   * 
     * @return Categoria[]
     */
    public static function traerTodas()
    {
        $query = "SELECT * FROM categoria_sexo";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute();

        $salida = [];

        while($datosCat = $stmt->fetch()) {
            $cat = new CategoriaGenero();
            $cat->cargarDatos($datosCat);

            $salida[] = $cat;
        }

        return $salida;
    }

    /**
     * @param categoria
     * @return null|categoria
     */
    public static function traerUnaCategoriaSexo($categoria)
    {
        $query = "SELECT * FROM categoria_sexo WHERE categoria_sexo.id = ? LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$categoria["id"]]);

        if ($datosCat = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cat = new CategoriaGenero();
            $cat->cargarDatos($datosCat);
            return $cat;
        }

        return null;
    }

    /**
     * @param $datosCat
     */
    protected function cargarDatos($datosCat)
    {
        $this->setIdcatsexo($datosCat['id']);
        $this->setNombre($datosCat['nombre']);
    }

    /**
     * @param $data
     */
    public static function crear($data)
    {
        $isCat = self::traerUnaCategoriaSexo($data);
        if ($isCat === null) {
	        $query = "INSERT INTO categoria_sexo (id, nombre)
	                  VALUES (?, ?)";

	        $stmt = DBConnection::getStatement($query);

	        $exito = $stmt->execute([$data['id'], $data['nombre']]);

	        if (!$exito) {
	            throw new Exception('Error al insertar los datos.');
	            $output = "Error al crear la categoria genero.";
	        } else {
	        	$output = true;
	        }
	    } else {
        	$output = "Categoria genero ya existente, por favor ingrese otra.";
        }
        return $output; 
    }

    /**
     * @param $data
     */
    public static function editar($data)
    {   
        $query = "UPDATE categoria_sexo
                  SET 
                  	nombre = ?
                  WHERE
					id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data['nombre'], $data['id']]);
        if (!$exito) {
            throw new Exception('Error al editar los datos.');
            $output = "Error al editar categoria genero.";
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
        $query = "DELETE FROM categoria_sexo WHERE id = ?";
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
	 * @param mixed $id_cat_sexo
	 */
	public function setIdcatsexo($id_cat_sexo)
	{
		$this->id_cat_sexo = $id_cat_sexo;
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
	public function getIdcatsexo()
	{
		return $this->id_cat_sexo;
	}
}