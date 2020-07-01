<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\Usuario;
use Firebase\JWT\JWT;

class BeforeMiddlewareValidarJwt
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

        $headers = getallheaders();
        $packageRecieved = $headers['token'] ?? '';
        
        try 
        {
            $decode = JWT::decode($packageRecieved, 'pepe', array('HS256'));
        } 
        catch (\Throwable $th) 
        {
            $decode = null;
        }
        
        if($decode == null)
        {
            $response->getBody()->write('Token invalido');
        }
        else
        {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $response->getBody()->write($existingContent);
        }
        return $response;
    }
}