<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Materia;
use App\Models\Usuario;
use Firebase\JWT\JWT;

class MateriasController
{
    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Materia::all());

        $response->getBody()->write($rta);
        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        
        $materia = new Materia;
        $materia->materia = $body["materia"];
        $materia->cuatrimestre = $body["cuatrimestre"];
        $materia->vacantes = $body["vacantes"];
        $materia->profesor_id = $body["profesor"];

        $rta = json_encode($materia->save());

        if($rta == true)
        {
            $response->getBody()->write("Materia cargada con exito ");
            return $response;
        }        
    }

    public function mostrar(Request $request, Response $response, $args)
    {
        $headers = getallheaders();
        $packageRecieved = $headers['token'] ?? '';
        $mat = new Materia;

        try 
        {
            $decode = JWT::decode($packageRecieved, 'pepe', array('HS256'));
        } 
        catch (\Throwable $th) 
        {
            $decode = null;
        }

        if($decode->tipo == 1)
        {
            $queryMateria = $mat->find($args['id'])->value('materia');
            $queryCuatrimestre = $mat->find($args['id'])->value('cuatrimestre');
            $queryVacantes = $mat->find($args['id'])->value('vacantes');
            $queryProfesor = $mat->find($args['id'])->join('users', 'profesor_id', '=', 'users.id')->select('nombre')->get();
            
            $materia = array("Materia: " . $queryMateria, "Cuatrimestre: " . $queryCuatrimestre, "Vacantes: " . $queryVacantes, "Profesor: " . $queryProfesor);            

            $materiaJson = json_encode($materia);

            $response->getBody()->write($materiaJson);
            return $response;
        }
        else
        {
            ;
        }

    }

    public function addProf(Request $request, Response $response , array $args)
    {
        $body = $request->getParsedBody();

        $materias = new Materia();

        $materia= $materias->find($args["id"]);

        if ($materia!=null) 
        {
        $materia->profesor_id =$args["profesor"];
        $rta = json_encode(array("OK"=>$materia->save()));
        }
        else 
        {
        $rta = json_encode(array("ERROR"=>"La materia no existe"));
        }

        $response->getBody()->write( $rta);
        return $response;
    }
}