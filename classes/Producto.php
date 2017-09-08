<?php 
/**
 * Class Producto
 *
 * Esta clase maneja los datos y las consultas con la
 * tabla de productos.
 */
class Producto implements \JsonSerializable
{
	private $id_producto;
	private $nombre;
	private $descripcion;
	private $precio;
	private $imagen;
	private $stock;
	private $categoria;
	private $cat_sexo;

    public static $reglas = [
        'nombre' => ['required', 'minlength:3', 'maxlength:20' ,'nombreApellido'],
        'descripcion' => ['required', 'minlength:5', 'descripcion'],                    
        'precio' => ['required', 'precio'],
        'imagen' => ['required', 'foto'],
        'id_categoria' => ['required'],
        'id_cat_sexo' => ['required']
    ];

	/**
     * Retorna todas las productos como un array
     * 
	 * @param $id_cate|null
     * @return array Producto[]
     */
    public static function traer($id_cate)
    {
    	if ($id_cate != null) {
    		$query = "SELECT 
    					productos.id AS id,
	        		    productos.nombre AS nombre,
	        		    productos.descripcion AS descripcion,
	        		    productos.precio AS precio,
	        		    productos.stock AS stock,
	        		    productos.imagen AS imagen,  
	        		    categoria.nombre AS categoria,
	        		    categoria_sexo.nombre AS cat_sexo   
    				  FROM productos LEFT JOIN categoria ON productos.id_categoria = categoria.id
        			  LEFT JOIN categoria_sexo ON productos.id_cat_sexo = categoria_sexo.id 
        			  WHERE 
        			  	productos.id_categoria = ?";
    	} else {
    		$query = "SELECT 
    					productos.id AS id,
	        		    productos.nombre AS nombre,
	        		    productos.descripcion AS descripcion,
	        		    productos.precio AS precio,
	        		    productos.stock AS stock,
	        		    productos.imagen AS imagen,  
	        		    categoria.nombre AS categoria,
	        		    categoria_sexo.nombre AS cat_sexo   
    				  FROM productos LEFT JOIN categoria ON productos.id_categoria = categoria.id
        			  LEFT JOIN categoria_sexo ON productos.id_cat_sexo = categoria_sexo.id";
    	}
        
        $stmt = DBConnection::getStatement($query);

        if ($id_cate != null) {
        	$stmt->execute([$id_cate]);
        } else {
        	$stmt->execute();
        }

        $salida = [];

        while($datosProd = $stmt->fetch()) {
            $prod = new Producto();
            $prod->cargarDatos($datosProd);

            $salida[] = $prod;
        }

        return $salida;
    }

    /**
     * @param Producto
     * @return null|Producto
     */
    public static function traerUnProducto($producto)
    {
        $query = "SELECT 
        			productos.id AS id,
        		    productos.nombre AS nombre,
        		    productos.descripcion AS descripcion,
        		    productos.precio AS precio,
        		    productos.stock AS stock,
        		    productos.imagen AS imagen,  
        		    categoria.nombre AS categoria,
        		    categoria_sexo.nombre AS cat_sexo 
        		FROM 
        			productos LEFT JOIN categoria ON productos.id_categoria = categoria.id
        			LEFT JOIN categoria_sexo ON productos.id_cat_sexo = categoria_sexo.id
        		WHERE 
        			productos.id = ? 
        		LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$producto["id"]]);

        if ($datosProd = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $prod = new Producto();
            $prod->cargarDatos($datosProd);
            return $prod;
        }

        return null;
    }

    /**
     * @param $producto
     * @return boolean $data_exists
     */
    public static function chequearSiExiste($producto)
    {
        $query = "SELECT 
                    COUNT(*)
                FROM 
                    productos LEFT JOIN categoria ON productos.id_categoria = categoria.id
                    LEFT JOIN categoria_sexo ON productos.id_cat_sexo = categoria_sexo.id
                WHERE 
                    productos.nombre = ?
                AND
                    productos.descripcion = ?
                AND
                    categoria.id = ?
                AND
                    categoria_sexo.id = ?
                LIMIT 1";
        $stmt = DBConnection::getStatement($query);
        $stmt->execute([$producto["nombre"], $producto["descripcion"], $producto["id_categoria"], $producto["id_cat_sexo"]]);

        $data_exists = ($stmt->fetchColumn() > 0) ? true : false;

        return $data_exists;
    }

    /**
     * @param $data
     */
    public static function crear($data)
    {
    	$output = [];
        
        $query = "INSERT INTO productos
                  VALUES (:id, :nom, :descripcion, :precio, :imagen, :stock, :id_categoria, :id_cat_sexo)";

        $stmt = DBConnection::getStatement($query);

        $exito = $stmt->execute([
            'nom' => $data['nombre'],
            'id' => null,
            'stock' => $data['stock'],
            'precio' => $data['precio'],
            'descripcion' => $data['descripcion'],
            'imagen' => $data['imagen'],
            'id_categoria' => $data['id_categoria'],
            'id_cat_sexo' => $data['id_cat_sexo']
        ]);

        if(!$exito) {
            throw new Exception('Error al crear el producto.');
        } else {
        	$output["status"] = "success";
            $output["msg"] = "El producto se ha ingresado exitosamente.";
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
        $query = "UPDATE productos 
        		  SET
        		  	nombre = :nom,
        		  	descripcion = :des,
        		  	precio = :prec,  
        		  	stock = :sto, 
        		  	id_categoria = :cat, 
        		  	id_cat_sexo = :cat_sexo
                  WHERE
                  	id = :id";  
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([
            'nom' => $data['nombre'],
            'id' => $data['id'],
            'sto' => $data['stock'],
            'prec' => $data['precio'],
            'des' => $data['descripcion'],
            'cat' => $data['id_categoria'],
            'cat_sexo' => $data['id_cat_sexo']
        ]);

        if(!$exito) {
            throw new Exception('Error al editar el producto.');
        } else {
        	$output["status"] = "success";
            $output["msg"] = "El producto se ha modificado exitosamente.";
        }
        return $output;
    }

    /**
     * @param $data
     */
    public static function borrar($data)
    {
        $output = [];
        $query = "DELETE FROM productos WHERE id = ?";
        $stmt = DBConnection::getStatement($query);
        $exito = $stmt->execute([$data["id"]]);
        if(!$exito) {
            throw new Exception('Error al borrar los datos.');
        } else {
        	$output["status"] = "success";
            $output["msg"] = "Se borro el producto exitosamente!";
        }
        return $output;
    }

    /**
     * @param $datosProd
     */
    protected function cargarDatos($datosProd)
    {
        $this->setIdProducto($datosProd['id']);
        $this->setNombre($datosProd['nombre']);
        $this->setDescripcion($datosProd['descripcion']);
        $this->setPrecio($datosProd['precio']);
        $this->setImagen($datosProd['imagen']);
        $this->setStock($datosProd['stock']);
        $this->setCategoria($datosProd['categoria']);
        $this->setCatSexo($datosProd['cat_sexo']);
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
	
	/**** SETTERS ****/

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
	 * @param mixed $id_producto
	 */
	public function setIdProducto($id_producto)
	{
		$this->id_producto = $id_producto;
	}

	/**
	 * @param mixed $nombre
	 */
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	/**
	 * @param mixed $descripcion
	 */
	public function setDescripcion($descripcion)
	{
		$this->descripcion = $descripcion;
	}

	/**
	 * @param mixed $precio
	 */
	public function setPrecio($precio)
	{
		$this->precio = $precio;
	}

	/**
	 * @param mixed $imagen
	 */
	public function setImagen($imagen)
	{
		$this->imagen = $imagen;
	}

	/**
	 * @param mixed $stock
	 */
	public function setStock($stock)
	{
		$this->stock = $stock;
	}

	/**
	 * @param mixed $categoria
	 */
	public function setCategoria($categoria)
	{
		$this->categoria = $categoria;
	}

	/**
	 * @param mixed $cat_sexo
	 */
	public function setCatSexo($cat_sexo)
	{
		$this->cat_sexo = $cat_sexo;
	}


	/**** GETTERS ****/

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
     * @return mixed
     */
    public function getIdProducto()
    {
        return $this->id_producto;
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
	public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
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
    public function getStock()
    {
        return $this->stock;
    }
	
	/**
     * @return mixed
     */
	public function getCategoria()
	{
		return $this->categoria;
	}

	/**
     * @return mixed
     */
	public function getCatSexo()
	{
		return $this->cat_sexo;
	}
}