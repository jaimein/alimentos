<?php
// web/index.php
// carga del modelo y los controladores
require_once __DIR__ . '/../app/Config.php';
require_once __DIR__ . '/../app/Model.php';
require_once __DIR__ . '/../app/Controller.php';
require_once __DIR__ . '/../app/libs/sesion.php';
sec_session_start();
/*
Si tenemos que usar sesiones podemos poner aqui el inicio de sesión, de manera que si el usuario todavia no está logueado
lo identificamos como visitante, por ejemplo de la siguiente manera: $_SESSION['nivel_usuario']=0
 */

// enrutamiento
$map = array(
    /*
    En cada elemento podemos añadir una posición mas que se encargará de otorgar el nivel mínimo para ejecutar la acción
    Puede quedar de la siguiente manera
    'inicio' => array('controller' =>'Controller', 'action' =>'inicio', 'nivel_usuario'=>0)
     */
    'inicio' => array('controller' => 'Controller', 'action' => 'inicio', 'nivel_usuario' => 0),
    'login' => array('controller' => 'Controller', 'action' => 'login', 'nivel_usuario' => 0),
    'registro' => array('controller' => 'Controller', 'action' => 'registro', 'nivel_usuario' => 0),
    'listar' => array('controller' => 'Controller', 'action' => 'listar', 'nivel_usuario' => 1),
    'insertar' => array('controller' => 'Controller', 'action' => 'insertar', 'nivel_usuario' => 2),
    'buscar' => array('controller' => 'Controller', 'action' => 'buscarPorNombre', 'nivel_usuario' => 1),
    'ver' => array('controller' => 'Controller', 'action' => 'ver', 'nivel_usuario' => 1),
    'error' => array('controller' => 'Controller', 'action' => 'error', 'nivel_usuario' => 0),
    'logout' => array('controller' => 'Controller', 'action' => 'logout', 'nivel_usuario' => 0),
);

$params = array(
    'usuario' => getUsuario(),
    'ciudad' => getCiudad(),
);
// Parseo de la ruta
if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else {

        //Si el valor puesto en ctl en la URL no existe en el array de mapeo envía una cabecera de error
        error_log("No existe " . $_GET['ctl'] . "-" . microtime() . PHP_EOL, 3, "errores_no_valido.txt");
        require __DIR__ . '/../app/templates/error.php';
        // header('Status: 404 Not Found');
        // echo '<html><body><h1>Error 404: No existe la ruta <i>' .
        //     $_GET['ctl'] .'</p></body></html>';
        exit;
    }
} else {
    $ruta = 'inicio';
}
$controlador = $map[$ruta];
/*
Comprobamos si el metodo correspondiente a la acción relacionada con el valor de ctl existe, si es así ejecutamos el método correspondiente.
En aso de no existir cabecera de error.
En caso de estar utilizando sesiones y permisos en las diferentes acciones comprobariaos tambien si el usuario tiene permiso suficiente para ejecutar esa acción
 */

if (method_exists($controlador['controller'], $controlador['action'])) {
    if ($controlador['nivel_usuario'] <= $_SESSION['nivel_usuario']) {
        call_user_func(array(new $controlador['controller'],
            $controlador['action']),$params);
    } else {
        error_log("No tiene acceso al controlador" .
            $controlador['controller'] .
            "->" .
            $controlador['action'] .
            "no existe" . "-" . microtime() . PHP_EOL, 3, "errores_acceso.txt");
        require __DIR__ . '/../app/templates/no_permisos.php';

        // header('Status: 403 Forbiden');
        // echo '<html><body><h1>Error 403: No tiene acceso al controlador <i>' .
        //     $controlador['controller'] .
        //     '->' .
        //     $controlador['action'] .
        //     '</i> </h1></body></html>';
    }

} else {
    error_log("El controlador" .
        $controlador['controller'] .
        "->" .
        $controlador['action'] .
        "no existe" . "-" . microtime() . PHP_EOL, 3, "errores_no_valido.txt");
    require __DIR__ . '/../app/templates/error.php';
    // header('Status: 404 Not Found');
    // echo '<html><body><h1>Error 404: El controlador <i>' .
    //     $controlador['controller'] .
    //     '->' .
    //     $controlador['action'] .
    //     '</i> no existe</h1></body></html>';
}
