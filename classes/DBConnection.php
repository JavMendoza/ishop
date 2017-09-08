<?php
class DBConnection
{
	private static $db = null;

	private function __construct() {}

	private static function openConnection()
	{
		$host = "localhost";
		$user = "root";
		$pass = "";
		$base = "ishop";
		
		$dsn = "mysql:host=$host;dbname=$base;charset=utf8";

		try {
			self::$db = new PDO($dsn, $user, $pass);
		} catch(Exception $e) {
			echo "No se pudo conectar con la base de datos";
		}

		//echo "DBConnection: Conexión abierta....<br>";
	}

	/**
	 * Retorna una conexión a la base de datos en modo Singleton.
	 *
	 * @return PDO El objeto de PDO.
	 */
	public static function getConnection()
	{
		if(is_null(self::$db)) {
			self::openConnection();
		}

		return self::$db;
	}

	/**
	 * Retorna el PDOStatement para el $query proporcionado.
	 * 
	 * @param string $query
	 * @return PDOStatement
	 */
	public static function getStatement($query)
	{
		return self::getConnection()->prepare($query);
	}
}