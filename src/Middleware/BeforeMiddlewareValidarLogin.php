<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\Usuario;

class BeforeMiddlewareValidarLogin
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $usr = new Usuario;

        $body = $request->getParsedBody();

        $queryEmail = $usr->where('email', $body['email'])->value('email');
        $queryPsw = $usr->where('clave', $body['clave'])->value('clave');
            
            
        if ($queryEmail == $body['email'] && $queryPsw == $body['clave']) 
        {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);
            $response->getBody()->write($existingContent);
                
        } else {
            $response->getBody()->write('Usuario o contraseña incorrecta');  
        }
            
        return $response;
    }
}