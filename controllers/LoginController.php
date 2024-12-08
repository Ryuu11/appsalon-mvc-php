<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;
use PDO;

class LoginController{
    public static function login(Router $router){

        $alertas= [];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth= new Usuario($_POST);

          $alertas= $auth->validarLogin();
           
          if(empty($alertas)){
            //comprobar que usuario exista

            if($usuario= Usuario::where('email', $auth->email)){
                
            };

           if($usuario){

            //verificar password.'

            if($usuario->comprobarPasswordAndVerificado($auth->password)){

                //autenticar usuario;
                if(!isset($_SESSION)) {
                    session_start();
                }
                

                $_SESSION['id']= $usuario->id;
                $_SESSION['nombre']=$usuario->nombre;
                $_SESSION['email']=$usuario->email;
                $_SESSION['login']=true;

                //redireciconar
                if($usuario->admin === '1'){
                    $_SESSION['admin']= $usuario->admin ?? null;

                    header('Location: /admin');
                }else{
                    header('Location: /cita');
                }
               
            }
            

           }
           
           else{

            Usuario::setAlerta('error', 'Usuario no encontrado');
           }
          }
        }

        $alertas= Usuario::getAlertas();

        $router->render('auth/login',[
            'alertas'=> $alertas
        ]);
    }
    public static function logout(){
        if (!$_SESSION['nombre']) {
            session_start();
          } 


        $_SESSION= [];

        header('Location: /');

       
    }

    public static function olvide(Router $router){

        $alertas=[];
        
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $auth = new Usuario($_POST);
            $alertas= $auth->validarEmail();

            if(empty($alertas)){
                $usuario= Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado ==="1"){

                    //generar un token de un solo uso

                    $usuario->crearToken();
                    $usuario->guardar();

                    // Ennviar el email

                    $email= new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu email');
                    
                } else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    
                }
            }
        }

        $alertas= Usuario::getAlertas();
                    

        $router->render('auth/olvide-password',[
            'alertas'=>$alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
     
        $alertas = [];
        $error = false;
     
        $token = s($_GET['token']);
     
        //buscar usuario por su token
     
        $usuario = Usuario::where('token', $token);
     
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }
     
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer nuevo password y guardarlo.
     
            $password = new Usuario($_POST);
     
            $alertas = $password->validarPassword();
     
            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
     
                $resultado = $usuario->guardar();
     
                if($resultado) {
                    Usuario::setAlerta('exito', 'Password Actualizado Correctamente');
     
                    header("Refresh: 3; url=/");
                }
            }
        }
     
        $alertas = Usuario::getAlertas();
     
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
    
        $usuario= new Usuario();
        //alertas vacias

        $alertas=[];
        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $usuario->sincronizar($_POST);
            $alertas= $usuario->validarNuevacuenta();

            if(empty($alertas)){
                //Verificar que el usuario que no este registrado.
                $resultado= $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear el password

                    $usuario->hashPassword();
                    //Generar token unico;

                    $usuario->crearToken();

                    //Enviar el email
                    
                    $email= new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    //crear el usuario

                    $resultado= $usuario->guardar();

                    if($resultado){
                       header('Location: /mensaje');
                    }

            
                }

            }
            
            
        }
        $router->render('auth/crear-cuenta',[
            'usuario'=> $usuario,
            'alertas'=> $alertas
            
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas= [];
        $token= s($_GET['token']);

        $usuario=Usuario::where('token', $token);

        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error',"Token no valido");
        }else{
            $usuario->confirmado= "1";
            $usuario->token=null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta confirmada con exito');
        }
        
        //Obtener alertas
        $alertas= Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
    ]);
}

}