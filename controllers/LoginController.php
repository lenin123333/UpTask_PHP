<?php
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;


class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //verfiicar usuario
                $auth = Usuario::where('email', $auth->email);
                if(!$auth || !$auth->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }else{
                    // EL usario existe
                    if(password_verify($_POST['password'],$auth->password)){
                        session_start();
                        $_SESSION['id'] = $auth->id;
                        $_SESSION['nombre'] = $auth->nombre;
                        $_SESSION['email'] = $auth->email;
                        $_SESSION['login'] = true;
                        //redireccionar
                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'El usuario no existe o la contraseña es incorrecta');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuenta();
            if (empty($alertas)) {
                $existeUsuario = $usuario::where('email', $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear Paswword
                    $usuario->hashPassword();
                    //Eliminar paswword 2
                    unset($usuario->password2);
                    //Generar token
                    $usuario->crearToken();
                    //enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    //crear un nuevo usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }

                }
            }
        }

        //Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if (empty($alertas)) {
                //Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);
                if ($usuario && $usuario->confirmado === "1") {
                    //Generar un nuevo Token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    //Actualizar el usuario
                    $usuario->guardar();
                    //Enviar Email  
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }

            }
        }
        $alertas = Usuario::getAlertas();
        //Render a la vista
        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true;
        if (!$token) {
            header('Location: /');
        }
        //Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Valido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Añadiendo password
            $usuario->sincronizar($_POST);
            //Validar el password
            $alertas = $usuario->validarPassword();
            if (empty($alertas)) {
                //Hashear Password
                $usuario->hashPassword();
                //Eliminar token
                $usuario->token = null;
                //Guardar el usuario
                $resultado = $usuario->guardar();
                //Redireccionar
                if ($resultado) {
                    header('Location: /');
                }

            }
        }

        $alertas = Usuario::getAlertas();
        //Render a la vista
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);
        if (!$token) {
            header('Location: /');
        }
        //Encontrar al usuario
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no vaido');
        } else {
            //confirmar el usuario
            $usuario->confirmado = "1";
            $usuario->token = null;
            unset($usuario->password2);
            //guardar base de datos
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar cuenta',
            'alertas' => $alertas
        ]);
    }
}