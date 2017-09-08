<?php 
/**
 * Class Auth
 *
 * Se encarga del manejo de la autenticación.
 */
class Auth
{   
    
    public static $reglas = [
        'usuario' => ['required','minlength:3', 'maxlength:20', 'username'],
        'password' => ['required', 'minlength:6', 'maxlength:20', 'password']
    ];

    /**
     * Intenta autenticar un usuario.
     *
     * @param string $usuario
     * @param string $password
     * @return bool
     */ 
    public static function login($usuario, $password) {
        $usuario = Usuario::traerUnUsuario($usuario);

        if ($usuario) {
            if (password_verify($password, $usuario->getPass())){
                self::logUser($usuario);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Loguea un usuario.
     *
     * @param $usuario
     */
    protected static function logUser($usuario){
        $_SESSION["usuario"] = $usuario;
    }

    /**
     * @return Usuario
     */
    public static function getUser() {
        return $_SESSION["usuario"];   
    }

    /**
     * Cierra la sesión.
     */
    public static function logout() {
        unset($_SESSION["usuario"]);
    }

    public static function userLogged() {
        //print_r($_SESSION);
        //exit;
        return isset($_SESSION["usuario"]) && !empty($_SESSION["usuario"]);
    }
}