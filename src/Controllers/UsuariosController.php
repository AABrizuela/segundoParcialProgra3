<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use Firebase\JWT\JWT;

class UsuariosController
{
    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Usuario::all());

        $response->getBody()->write($rta);
        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();        

        if($body["legajo"] >= 1000 && $body["legajo"] <= 2000)
        {
            if($body["tipo"] >= 1 && $body["tipo"] <= 3)
            {
                $usuario = new Usuario;
                $usuario->email = $body["email"];
                $usuario->nombre = $body["nombre"];
                $usuario->clave = $body["clave"];
                $usuario->tipo_id = $body["tipo"];
                $usuario->legajo = $body["legajo"];

                $rta = json_encode(array("ok" => $usuario->save()));
            }
            else
            {
                $response->getBody()->write("ERROR: El tipo tiene que estar entre 1 y 3");
                return $response;
            }
        }        
        else
        {
            $response->getBody()->write("ERROR: El legajo tiene que estar entre 1000 y 2000");
            return $response;
        }
        
        $response->getBody()->write("Cargado con exito");
        return $response;
    }
    
    public function login(Request $request, Response $response, $args)
    {
        $usuario = new Usuario;        
        $key = "pepe";
        $bool = false;

        try {
            $body = $request->getParsedBody();

            $payload = array(
                "email"  => $usuario->where('email', $body['email'])->value('email'),
                "nombre" => $usuario->where('email', $body['email'])->value('nombre'),
                "clave"  => $usuario->where('email', $body['email'])->value('clave'),
                "tipo"   => $usuario->where('email', $body['email'])->value('tipo_id')
            );

            $token = JWT::encode($payload, $key);
            $bool = true;

        } catch (\Throwable $th) {
            $token = "Error: " . $th->getMessage();
        }

        $ret = array(
            "success" => $bool,
            "token" => $token
        );

        $retJson = json_encode($ret);

        $response->getBody()->write($retJson);
        return $response;        
    }
}