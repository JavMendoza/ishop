<?php 
/**
 * Class Validator
 *
 * Esta clase valida datos de un formulario y retorna errores especificos si los hay.
 */
class Validator
{
	public $datosForm;
	public $reglas;
	public $exito;
	public $errores = [];

	private static $patrones = [
		'nombreApellido' => '/^[a-zA-Z ]{3,20}$/',
		'email' => '/^[a-z0-9\._]{3,}@[a-z]+\.[a-z]{2,6}(\.[a-z]{2})?$/',	
		'password' => '/^[a-zA-Z0-9\*\.\_\-\$\#]{6,20}$/',
		'foto' => '/(jpeg|gif|png)$/',
		'precio' => '/^[0-9]{1,4}\.?[0-9]{1,2}$/',
		'descripcion' => '/^[a-zA-Z ]{5,}$/',
		'username' => '/^[a-zA-Z0-9]{3,20}$/'
	];
	
	/**
	 * Chekea que tipo de validacion se tiene que ejecutar teniendo en cuenta las reglas, sus valores y los datos del form
	 *
	 * @param array $datosForm datos del formulario
	 * @param array $reglas reglas de validacion del formulario
	 *  
	 */
	public function validate($datosForm, $reglas)
	{
		$this->datosForm = $datosForm; // guardo los datos por $_POST y $_FILE en un array propio
		foreach($reglas as $nameField => $arrayReglas) {
			foreach($arrayReglas as $regla) {
				if(strpos($regla, ':') !== false) {
					$datosRegla = explode(':', $regla);
					$nombreRegla = '_' . $datosRegla[0];
					$valorRegla = $datosRegla[1];

					if(method_exists($this, $nombreRegla)) {
						$this->$nombreRegla($nameField, $valorRegla);
					} else {
						throw new Exception("No existe el metodo " . $nombreRegla);
					}
					
				} else {
					$nombreRegla = "_" . $regla;
					if(method_exists($this, $nombreRegla)) {
						$this->$nombreRegla($nameField);
					} else {
						throw new Exception("No existe el metodo " . $nombreRegla);
					}
				}
			}
		}
	}
	
	/**
	 * Retorna todos los errores en un array. De no haber ninguno, retorna un array vacío.
	 *
	 * @return array El array de errores.
	 */
	public function getErrores()
	{
		return $this->errores;
	}
	
	/**
	 * Retorna el error del $campo pedido, si existe. De no existir, retorna false.
	 * 
	 * @return string|boolean
	 */
	public function getError($campo)
	{
		return isset($this->errores[$campo]) ? $this->errores[$campo] : false;
	}
	
	/**
	 * Retorna un boolean indicando si la validación tuvo éxito o no.
	 *
	 * @return boolean
	 */
	public function exito()
	{
		return empty($this->errores);
	}

	/***** METODOS DE VALIDACION *****/
	
	protected function _required($campo)
	{
		if(empty($this->datosForm[$campo])) {
			$this->errores[$campo] = "<b>$campo</b> no puede estar vacío.<br />";
			return false;
		}
		
		return true;
	}
	
	protected function _minlength($campo, $longitud)
	{
		if(strlen($this->datosForm[$campo]) < $longitud) {
			$this->errores[$campo] = "<b>$campo</b> debe tener al menos $longitud caracteres.<br />";
			return false;
		}
		return true;
	}

	protected function _maxlength($campo, $longitud)
	{
		if(strlen($this->datosForm[$campo]) > $longitud) {
			$this->errores[$campo] = "<b>$campo</b> debe tener como maximo $longitud caracteres.<br />";
			return false;
		}
		return true;
	}
	
	protected function _equals($campo, $campoVerificacion)
	{
		if($this->datosForm[$campo] !== $this->datosForm[$campoVerificacion]) {
			$this->errores[$campo] = "<b>$campo</b> no coincide con <b>$campoVerificacion</b>.<br />";
			return false;
		}
		return true;
	}
	
	protected function _numeric($campo)
	{
		if(!is_numeric($this->datosForm[$campo])) {
			$this->errores[$campo] = "El <b>$campo</b> debe ser un número.<br />";
			return false;
		}
		return true;
	}

	protected function _nombreApellido($campo)
	{
		if(!preg_match( self::$patrones["nombreApellido"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "El <b>$campo</b> no es valido, debe contener solo letras.<br />";
			return false;
		}
		return true;
	}

	protected function _email($campo)
	{
		if(!preg_match( self::$patrones["email"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "El <b>$campo</b> no es valido, debe tener formato de email(example@server.com).<br />";
			return false;
		}
		return true;
	}

	protected function _password($campo)
	{
		if(!preg_match( self::$patrones["password"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "El <b>$campo</b> es invalido, caracteres validos: a-z 0-9 - _ $ * . # <br />";
			return false;
		}
		return true;
	}

	protected function _foto($campo)
	{
		if(!preg_match( self::$patrones["foto"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "La <b>$campo</b> no es valida, tiene que ser un jpg, png o gif. <br />";
			return false;
		}
		return true;
	}

	protected function _precio($campo)
	{
		if(!preg_match( self::$patrones["precio"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "El <b>$campo</b> no es valido, tiene que ser un numero hasta 6 cifras y puede tener 2 decimales. <br />";
			return false;
		}
		return true;
	}

	protected function _descripcion($campo)
	{
		if(!preg_match( self::$patrones["descripcion"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "La <b>$campo</b> no es valida, solo puede contener letras y espacios. <br />";
			return false;
		}
		return true;
	}

	protected function _username($campo)
	{
		if(!preg_match( self::$patrones["username"] , $this->datosForm[$campo])) {
			$this->errores[$campo] = "El <b>$campo</b> no es valido, no puede contener caracteres especiales. <br />";
			return false;
		}
		return true;
	}
}