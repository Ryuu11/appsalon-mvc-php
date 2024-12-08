<?php

namespace Model;

class CitaServicio extends ActiveRecord{
    protected static $tabla= 'citasservicios';
    protected static $columnasDB= ['id', 'servicioId', 'citasId'];

    public $id;
    public $citasId;
    public $servicioId;

    public function __construct($args =[])
    {
        $this->id= $args['id'] ?? null;
        $this->servicioId= $args['servicioId'] ?? '';
        $this->citasId= $args['citasId'] ?? '';
    }
}