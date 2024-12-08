<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        

        isAdmin();
        date_default_timezone_set('America/Mexico_City');

        $fecha= $_GET['fecha'] ?? $fecha= date('Y-m-d');
        $fechas= explode('-', $fecha);


        if(!checkdate($fechas[1],$fechas[2],$fechas[0])){
            header('Location: /404');
        }

          //consulta la base de datos.

          $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS cliente, ";
          $consulta .= "usuarios.email, usuarios.telefono, servicios.nombre AS servicio, servicios.precio ";
          $consulta .= "FROM citas ";
          $consulta .= "iNNER JOIN usuarios ";
          $consulta .= "ON citas.usuarioId = usuarios.id ";
          $consulta .= "INNER JOIN citasservicios ";
          $consulta .= "ON citasservicios.citasId = citas.id ";
          $consulta .= "INNER JOIN servicios ";
          $consulta .= "ON servicios.id = citasservicios.servicioId ";
          $consulta .= "WHERE fecha = '{$fecha}' ";

          
          
          $citas=AdminCita::SQL($consulta);

          
     
        $router->render('admin/index', [
        'nombre' => $_SESSION['nombre'],
        'citas'=> $citas,
        'fecha'=> $fecha
        ]);
    }
}