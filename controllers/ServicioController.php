<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{

    public static function index (Router $router){

        isAdmin();

        $servicios= Servicio::all();
       $router->render('servicios/index',[
        'nombre'=> $_SESSION['nombre'],
        'servicios' => $servicios
       ]);

      
    }
     
    // public static function crear (Router $router){
        
    //     $servicio= new Servicio;
    //     $alertas=[];

    //    if($_SERVER['REQUEST_METHOD'] ===  'POST'){
    //     $servicio->sincronizar($_POST);

    //     $alertas= $servicio->validar();

    //     if(empty($alertas)){
    //         $servicio->guardar();
    //         header('Location: /servicios');
    //     }
        
    //    }

    //    $router->render('servicios/crear',[
    //     'nombre'=> $_SESSION['nombre'],
    //     'servicio' => $servicio,
    //     'alertas'=> $alertas
    //    ]);
    // }

    public static function crear(Router $router)
{
    isAdmin();
    $servicio = new Servicio;
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servicio->sincronizar($_POST);

        // Validar el servicio
        $alertas = $servicio->validar();

        if (empty($alertas)) {
            // Guardar el servicio si no hay errores
            $resultado = $servicio->guardar();

            // Devolver respuesta de éxito en formato JSON
            echo json_encode([
                'resultado' => 'exito',
                'mensaje' => 'El servicio fue guardado correctamente.',
                'redireccion' => '/servicios'
                
            ]);
            return;
            
        }

        // Si hay errores, devolverlos en formato JSON
        echo json_encode([
            'resultado' => 'error',
            'alertas' => $alertas
        ]);
        return;
    }

    // Si es GET, renderizar la vista normalmente
    $router->render('servicios/crear', [
        'nombre' => $_SESSION['nombre'],
        'servicio' => $servicio,
        'alertas' => $alertas
    ]);
}

   
    public static function actualizar (Router $router){

       isAdmin();
        if(!is_numeric($_GET['id'])) return;
        $servicio= Servicio::find($_GET['id']);
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] ===  'POST'){
            $servicio->sincronizar($_POST);

            $alertas= $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                echo json_encode([
                    'resultado' => 'exito',
                    'mensaje' => 'El servicio fue actualizado correctamente.',
                    'redireccion' => '/servicios'
                    
                ]);
                return;
            }

            
        // Si hay errores, devolverlos en formato JSON
        echo json_encode([
            'resultado' => 'error',
            'alertas' => $alertas
        ]);
        return;
        
        }

        $router->render('servicios/actualizar',[
            'nombre'=> $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas'  =>$alertas
           ]);
    }

    public static function eliminar (){
        isAdmin();
        if($_SERVER['REQUEST_METHOD'] ===  'POST'){
            $id= $_POST['id'];
            $servicio= Servicio::find($id);
           $servicio->eliminar();
        }
    }
    
}

