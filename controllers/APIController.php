<?php

namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController{
    public static function index(){

        $servicios= Servicio::all();

       echo json_encode($servicios);
    }

    public static function guardar(){

      //Almacena la cita y devuelve el ID
      $cita = new Cita($_POST);
      $resultado= $cita-> guardar();

      $id=$resultado['id'];
      //Almacena los servicios con el ID de la cita

      $idServicios= explode(",", $_POST['servicios'] );

      foreach($idServicios as $idServicio){
        $args=[
          'servicioId' => $idServicio,
          'citasId' => $id
          
        ];

        $citaServicio= new CitaServicio($args);
        $citaServicio->guardar();

      }

      //retornar una respuesta

      //Almacena la cita y el servicio;

      echo json_encode(['resultado'=> $resultado]);
    }

    public static function eliminar(){
      $id= $_POST['id'];

      $cita= Cita::find($id);
      $cita->eliminar();

      header('Location:' . $_SERVER['HTTP_REFERER']);
    
}

}