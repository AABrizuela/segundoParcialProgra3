<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\Usuario;

class BeforeMiddlewareValidarMailLegajo
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

        $queryLegajo = $usr->where('legajo', $body['legajo'])->value('legajo');
        $queryMail = $usr->where('email', $body['email'])->value('email');
            
            
        if ($queryLegajo != $body['legajo'] && $queryMail != $body['email']) 
        {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);
            $response->getBody()->write($existingContent);
                
        } else {
            $response->getBody()->write('No se pueden repetir los emails y legajos');  
        }
            
        return $response;
    }
}