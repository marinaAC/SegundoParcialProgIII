<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Materia;
    use App\Validations\Validaciones;
  

    class MateriaController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = Materia::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {

            $rta = Materia::find($args['id']);

            $response->getBody()->write(json_encode($rta));
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

            if(Validaciones::validName($parseBody['materia'])){
               $Materia = new Materia;
               $Materia->nombre= $parseBody['materia'];
               $Materia->cuatrimestre= $parseBody['cuatrimestre'];
               $Materia->cupos= $parseBody['cupos'];
               $response->getBody()->write(json_encode($Materia->save()));
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
           return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();

            if(isset($parseBody['nombre'])&&isset($parseBody['apellido'])&&isset($parseBody['localidad'])){
                $Materia = new Materia;
                $Materia->nombre= $parseBody['nombre'];
                $Materia->apellido= $parseBody['apellido'];
                $Materia->localidad_id=$parseBody['localidad'];
                $response->getBody()->write(json_encode($Materia->save()));
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $Materia =Materia::find($args['id']);
            $rta =$Materia->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>