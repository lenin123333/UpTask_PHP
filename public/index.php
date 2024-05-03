<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboadController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

//Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
//Cerrar Sesion
$router->get('/logout', [LoginController::class, 'logout']);
//crear cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);
//Olvide cuenta
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
//Colocar nuevo password
$router->get('/restablecer', [LoginController::class, 'restablecer']);
$router->post('/restablecer', [LoginController::class, 'restablecer']);
//Confirmacion de cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

$router->get('/dashboard', [DashboadController::class, 'index']);
$router->get('/crear-proyecto', [DashboadController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboadController::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboadController::class, 'proyecto']);
$router->get('/perfil', [DashboadController::class, 'perfil']);
$router->post('/perfil', [DashboadController::class, 'perfil']);
$router->get('/cambiar-password', [DashboadController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboadController::class, 'cambiar_password']);
//API para las tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);
// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();