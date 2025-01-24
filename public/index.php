<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\TaskController;
use Controllers\DashboardController;
use Controllers\LoginController;
use MVC\Router;
$router = new Router();

// Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crate Account
$router->get('/register', [LoginController::class, 'register']);
$router->post('/register', [LoginController::class, 'register']);

// Forgot Password Form
$router->get('/forgot', [LoginController::class, 'forgot']);
$router->post('/forgot', [LoginController::class, 'forgot']);

// Reset Password
$router->get('/reset', [LoginController::class, 'reset']);
$router->post('/reset', [LoginController::class, 'reset']);

// Confirm Account
$router->get('/message', [LoginController::class, 'message']);
$router->get('/confirm', [LoginController::class, 'confirm']);

// Dashboard - Projects zone
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/new-project', [DashboardController::class, 'newProject']);
$router->post('/new-project', [DashboardController::class, 'newProject']);
$router->get('/project', [DashboardController::class, 'project']);
$router->get('/profile', [DashboardController::class, 'profile']);

// Task API
$router->get('/api/tasks', [TaskController::class, 'index']);
$router->post('/api/tasks', [TaskController::class, 'create']);
$router->post('/api/tasks/update', [TaskController::class, 'update']);
$router->post('/api/tasks/delete', [TaskController::class, 'delete']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->checkRoutes();