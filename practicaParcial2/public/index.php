<?php
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use FastRoute\RouteCollector;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


use Config\Database;


use FastRoute\DataGenerator\GroupPosBased;

use App\Controllers\UserController;
use App\Controllers\MateriaController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\JsonMiddleware;

//use Slim\Handlers\Strategies\RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/practicaParcial2/public');



new Database;

$app->group('/materia',function(RouteCollectorProxy $group){
    $group->get('[/]',MateriaController::class.":getAll");
    $group->post('[/]',MateriaController::class.":addOne");
    $group->get('/{id}',MateriaController::class.":getOne");
    $group->put('/{id}',MateriaController::class.":updateOne");
    $group->delete('/{id}',MateriaController::class.":deleteOne");
})->add(new AuthMiddleware);

$app->group('/users',function(RouteCollectorProxy $group){
    $group->get('[/]',UserController::class.":getAll");
    $group->post('[/]',UserController::class.":addOne");
    $group->get('/{id}',UserController::class.":getOne");
    $group->put('/{id}',UserController::class.":updateOne");
    $group->delete('/{id}',UserController::class.":deleteOne");
});

$app->group('/login',function(RouteCollectorProxy $group){
    $group->post('[/]',UserController::class.":getOne");
});

$app->run();


?>