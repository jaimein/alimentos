<?php
include 'libs/utils.php';
include 'libs/classValidacion.php';
class Controller
{
    public function inicio()
    {
        $params = array(
            'mensaje' => 'Bienvenido al repositorio de alimentos',
            'fecha' => date('d-m-yyy'),
        );
        require __DIR__ . '/templates/inicio.php';
    }

    public function error()
    {
        require __DIR__ . '/templates/error.php';
    }

    public function listar()
    {
        try {
            $m = new Model();
            $params = array(
                'alimentos' => $m->dameAlimentos(),
            );

            // Recogemos los dos tipos de excepciones que se pueden producir
        } catch (Exception $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
            header('Location: index.php?ctl=error');
        } catch (Error $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
            header('Location: index.php?ctl=error');
        }
        require __DIR__ . '/templates/mostrarAlimentos.php';
    }

    public function insertar()
    {
        try {
            $params = array(
                'nombre' => '',
                'energia' => '',
                'proteina' => '',
                'hc' => '',
                'fibra' => '',
                'grasa' => '',
            );

            if (isset($_POST['insertar'])) {
                $nombre = recoge('nombre');
                $energia = recoge('energia');
                $proteina = recoge('proteina');
                $hc = recoge('hc');
                $fibra = recoge('fibra');
                $grasa = recoge('grasa');
                // comprobar campos formulario
                if (validarDatos($nombre, $energia, $proteina, $hc, $fibra, $grasa)) {

                    // Si no ha habido problema creo modelo y hago inserción
                    $m = new Model();
                    if ($m->insertarAlimento($nombre, $energia, $proteina, $hc, $fibra, $grasa)) {
                        header('Location: index.php?ctl=listar');
                    } else {
                        $params = array(
                            'nombre' => $nombre,
                            'energia' => $energia,
                            'proteina' => $proteina,
                            'hc' => $hc,
                            'fibra' => $fibra,
                            'grasa' => $grasa,
                        );
                        $params['mensaje'] = 'No se ha podido insertar el alimento. Revisa el formulario';
                    }
                } else {
                    $params = array(
                        'nombre' => $nombre,
                        'energia' => $energia,
                        'proteina' => $proteina,
                        'hc' => $hc,
                        'fibra' => $fibra,
                        'grasa' => $grasa,
                    );
                    $params['mensaje'] = 'Hay datos que no son correctos. Revisa el formulario';
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
            header('Location: index.php?ctl=error');
        } catch (Error $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
            header('Location: index.php?ctl=error');
        }

        require __DIR__ . '/templates/formInsertar.php';
    }

    public function buscarPorNombre()
    {
        try {
            $params = array(
                'nombre' => '',
                'resultado' => array(),
            );
            $m = new Model();
            if (isset($_POST['buscar'])) {
                $nombre = recoge("nombre");
                $params['nombre'] = $nombre;
                $params['resultado'] = $m->buscarAlimentosPorNombre($nombre);
            }
        } catch (Exception $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
            header('Location: index.php?ctl=error');
        } catch (Error $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
            header('Location: index.php?ctl=error');
        }
        require __DIR__ . '/templates/buscarPorNombre.php';
    }

    public function ver()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Página no encontrada');
            }
            $id = recoge('id');
            $m = new Model();
            $alimento = $m->dameAlimento($id);
            $params = $alimento;
        } catch (Exception $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
            header('Location: index.php?ctl=error');
        } catch (Error $e) {
            error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
            header('Location: index.php?ctl=error');
        }

        require __DIR__ . '/templates/verAlimento.php';
    }

    public function registro()
    {
        $params = array(
            'usuario' => '',
            'password' => '',
            'email' => '',
            'ciudad' => '',
            'mensaje' => '',
        );
        if (!$_POST) {
            require __DIR__ . '/templates/registro.php';
        } else {
            try {
                $params = array(
                    'usuario' => recoge('usuario'),
                    'password' => recoge('password'),
                    'email' => recoge('email'),
                    'ciudad' => recoge('ciudad'),
                    'mensaje' => '',
                );

                try {
                    $validacion = new Validacion();
                    $regla = array(
                        array(
                            'name' => 'usuario',
                            'regla' => 'no-empty,letras',
                        ),
                        array(
                            'name' => 'password',
                            'regla' => 'no-empty',
                        ),
                        array(
                            'name' => 'email',
                            'regla' => 'no-empty,email',
                        )
                        ,
                        array(
                            'name' => 'ciudad',
                            'regla' => 'no-empty,letras',
                        ),
                    );

                    $validaciones = $validacion->rules($regla, $params);
                    // La clase nos devolverá true si no ha habido errores y un objeto con que incluye los errores en un array
                    // Ahora nos sirve para ver lo que devuelve la clase
                    // echo "<pre>";
                    // print_r($validaciones);
                    // echo "</pre><br>";
                } catch (Exception $e) {
                    error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
                    header('Location: error.php');
                } catch (Error $e) {
                    error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
                    header('Location: error.php');
                }

                if ($validaciones === true) {
                    // Si no ha habido problema creo modelo y hago inserción
                    $m = new Model();
                    if ($m->comprobar_si_existe_usuario($params['usuario'])) {
                        if ($m->insertarUsuario($params['usuario'], $params['password'], 1, $params['email'], $params['ciudad'])) {
                            if(login($params['usuario'], $params['password'])){
                                header('Location: index.php?ctl=listar');
                            } else {
                                $params['mensaje']="Error usu o pass";
                                require __DIR__ . '/templates/inicio.php';
                            }
                        } else {
                            // $params = array(
                            //     'usuario' => $usuario,
                            //     'password' => $password,
                            //     'email' => $email,
                            //     'ciudad' => $ciudad,
                            // );
                            $params['mensaje'] = 'No se ha podido insertar el alimento. Revisa el formulario';
                        }
                    } else {
                        $params['mensaje'] = 'El nombre de usuario ya existe, escoja otro por favor';
                    }
                } else {
                    foreach ($validacion->mensaje as $errores) {
                        foreach ($errores as $error) {
                            $params['mensaje'] = $params['mensaje'] . $error . '<br>';
                        }
                    }

                    require __DIR__ . '/templates/registro.php';
                }
            } catch (Exception $e) {
                error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
                header('Location: index.php?ctl=error');
            } catch (Error $e) {
                error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
                header('Location: index.php?ctl=error');
            }
        }
    }

    public function login()
    {
        
        $params = array(
            'mensaje' => 'Bienvenido al repositorio de alimentos',
            'fecha' => date('d-m-yyy'),
            'usuario' => '',
            'password' => '',
            'mensaje' => ''
        );

        if (!$_POST) {
            require __DIR__ . '/templates/registro.php';
        } else {
            try {
                $params['usuario']=recoge('usuario');
                $params['password']=recoge('password');
                $validacion = new Validacion();
                $regla = array(
                array(
                    'name' => 'usuario',
                    'regla' => 'no-empty,letras',
                ),
                array(
                    'name' => 'password',
                    'regla' => 'no-empty',
                )
            );

                $validaciones = $validacion->rules($regla, $params);
                // La clase nos devolverá true si no ha habido errores y un objeto con que incluye los errores en un array
            // Ahora nos sirve para ver lo que devuelve la clase
            // echo "<pre>";
            // print_r($validaciones);
            // echo "</pre><br>";
            } catch (Exception $e) {
                error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
                header('Location: error.php');
            } catch (Error $e) {
                error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
                header('Location: error.php');
            }

            if ($validaciones === true) {
                // Si no ha habido problema creo modelo y hago inserción
           
                if(login($params['usuario'], $params['password'])){
                    header('Location: index.php?ctl=listar');
                } else {
                    $params['mensaje']="Error usu o pass";
                    require __DIR__ . '/templates/inicio.php';
                }
                    
                
            } else {
                foreach ($validacion->mensaje as $errores) {
                    foreach ($errores as $error) {
                        $params['mensaje'] = $params['mensaje'] . $error . '<br>';
                    }
                }

                require __DIR__ . '/templates/inicio.php';
            }
        }
    }
}
;
