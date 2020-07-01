<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuariosController;
use App\Controllers\MateriasController;
use App\Middleware\BeforeMiddlewareValidarLogin;
use App\Middleware\BeforeMiddlewareValidarJwt;
use App\Middleware\BeforeMiddlewareValidarTipo;
use App\Middleware\BeforeMiddlewareValidarMailLegajo;


return function ($app)
{
    $app->group('/usuario', function (RouteCollectorProxy $group)
    {
        $group->get('[/]', UsuariosController::class . ':getAll');
        $group->post('[/]', UsuariosController::class . ':add')
              ->add(BeforeMiddlewareValidarMailLegajo::class);
    });

    $app->post('/login[/]', UsuariosController::class . ':login')
        ->add(BeforeMiddlewareValidarLogin::class);

    $app->group('/materias', function (RouteCollectorProxy $group)
    {
        $group->post('[/]', MateriasController::class . ':add')
              ->add(BeforeMiddlewareValidarTipo::class)
              ->add(BeforeMiddlewareValidarJwt::class);
        $group->get('/{id}', MateriasController::class . ':mostrar');
        $group->put('/{id}/{profesor}', MateriasController::class . ':addProf');
    });
};