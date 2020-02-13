<?php

function sec_session_start()
{
    $session_name = 'sec_session_id';
    $secure = false;
    $httponly = true;
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(
        15 * 60,
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);

    session_name($session_name);
    session_start();
    if (!$_SESSION) {
        $_SESSION["nivel_usuario"] = 0;
    }
    // Establecer tiempo de vida de la sesión en segundos
    $inactividad = 15 * 60;
    // Comprobar si $_SESSION["timeout"] está establecida
    if (isset($_SESSION["timeout"])) {
        // Calcular el tiempo de vida de la sesión (TTL = Time To Live)
        $sessionTTL = time() - $_SESSION["timeout"];
        if ($sessionTTL > $inactividad) {
            session_destroy();
            header("Location: index.php");
        } else {
            session_regenerate_id(true);
        }
    }
    // El siguiente key se crea cuando se inicia sesión
    $_SESSION["timeout"] = time();

}

function login($usuario, $password)
{
    $m = new Model();
    $datos = $m->obtener_datos_usu($usuario, $password);
//     echo '<pre>';
    // print_r($datos);
    // echo '</pre>';
    if ($datos) {
        $_SESSION['id'] = $datos['id'];
        $_SESSION['user'] = $datos['user'];
        $_SESSION['pass'] = $datos['pass'];
        $_SESSION['nivel_usuario'] = $datos['nivel'];
        $_SESSION['email'] = $datos['email'];
        $_SESSION['ciudad'] = $datos['ciudad'];
        return true;
    } else {
        return false;
    }

}

function getUsuario()
{
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    } else {
        return "";
    }
}
function getCiudad()
    {
        if (isset($_SESSION['ciudad'])) {
            return $_SESSION['ciudad'];
        } else {
            return "";
        }
    }


    ?>
