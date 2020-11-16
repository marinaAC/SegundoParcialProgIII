<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\User;
    use App\Validations\Validaciones;
    use App\Security\Auth;
  

    class UserController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = User::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['email'])&&(isset($parseBody['clave'])||isset($parseBody['nombre']))){
                $userCoincidenteEmail = User::where('email',$parseBody['email'])->first()->attributesToArray();
               // $userCoindicenteName = User::where('nombre',$parseBody['nombre'])->first()->attributesToArray();
                $userCoindicentePass = User::where('clave',$parseBody['clave'])->first()->attributesToArray();
                if($userCoincidenteEmail['id']==$userCoindicentePass['id']){
                    $user = new User();
                    $user->email= $userCoincidenteEmail['email'];
                    $user->nombre= $userCoincidenteEmail['nombre'];
                    $user->clave= $userCoincidenteEmail['clave'];
                    $user->tipo= $userCoincidenteEmail['tipo'];
                    $token= Auth::SignIn($user);
                    $_SERVER['HTTP_TOKEN'] =  $token;
                    echo "$token";
                }
                
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

             if(Validaciones::validName($parseBody['email'])&&
                Validaciones::validName($parseBody['nombre'])&&
                Validaciones::validClave($parseBody['clave'])){
                $userCoincidenteEmail = User::where('email',$parseBody['email'])->first();
                $userCoindicenteName = User::where('nombre',$parseBody['nombre'])->first();
                $userConverEmail = $userCoincidenteEmail->attributesToArray();
                $userCoindicenteName = $userCoindicenteName->attributesToArray();
                if(($userConverEmail !=null || $userCoindicenteName != null) &&
                    (strcmp($userConverEmail['email'],$parseBody['email'])==0 ||
                     strcmp($userCoindicenteName['nombre'],$parseBody['nombre'])==0) ){
                    
                    echo "tiene que seleccionar otro email o nombre, ya que estan guardados";
                    return $response = "Error";
                }
                
                $user = new User;
                $user->email= $parseBody['email'];
                $user->nombre= $parseBody['nombre'];
                $user->clave= $parseBody['clave'];
                $user->tipo= $parseBody['tipo'];
                $response->getBody()->write(json_encode($user->save()));
             }else{
                 echo "Faltan elementos para dar de alta";
                 $response = "Error";
             }



            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();

            if(isset($parseBody['email'])&&isset($parseBody['pass'])){
                $user = new User;
                $user->email= $parseBody['email'];
                $user->pass= $parseBody['pass'];
                $response->getBody()->write(json_encode($user->save()));
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $user =User::find($args['id']);
            $rta =$user->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>