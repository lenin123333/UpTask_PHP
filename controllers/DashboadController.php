<?php

namespace Controllers;

use Model\Proyecto;

use Model\Usuario;
use MVC\Router;


class DashboadController
{
    public static function index(Router $router)
    {
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);
            //Validacion

            $alertas = $proyecto->validarProyecto();
            if (empty($alertas)) {
                //Generar url unica 
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                //almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                //guardar el poryecto

                $proyecto->guardar();
                //redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);

            }
        }
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();
        $token = $_GET['id'];
        if (!$token) {
            header('Location: /dashboard');
        }
        //Revisar que la persona que visita el poroyeto, es quien la creo
        $proyecto = Proyecto::where('url', $token);
        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }



    public static function perfil(Router $router)
    {

        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {
                //Verfiicar 
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //Mnesaje error
                    Usuario::setAlerta('error', 'Email ya registrado');
                    $alertas = $usuario->getAlertas();
                } else {
                    //guardar registro
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();
                    //Asignar el nombre nuevo
                    $_SESSION['nombre'] = $usuario->nombre;
                }

            }
        }
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);


    }
    public static function cambiar_password(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario=Usuario::find($_SESSION['id']);
           
            //Sincronizar con los del usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();
            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();
                
                if($resultado){
                    //Asignar el nuevo password
                    $usuario->password = $usuario->password_nueva;
                
                    //Eliminar propiedades
                    unset($usuario->passwordActual);
                    unset($usuario->password_nuevo);
                    //Hashear el password
                    $usuario->hashPassword();
                    //actualizar passwrod
                    $resultado=$usuario->guardar();
                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Guardado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }

                }else{
                    Usuario::setAlerta('error', 'Password incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }

        }


        $router->render('dashboard/cambiarPassword', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas

        ]);

    }

}