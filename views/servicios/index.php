<h1 class="nombre-pagina">Servicios</h1>

<p class="descripcion-pagina">Administracion de Servicios</p>

<?php

    include_once __DIR__ . '/../templates/barra.php';

?>

<ul class="servicios">
   <?php foreach($servicios as $servicio) {?>
    <li>
        <p>Nombre: <span><?php echo $servicio->nombre;?></span></p>
        <p>Precio: <span>$<?php echo $servicio->precio;?></span></p>

        <div class="acciones">
            <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id?>">Actualizar</a>

            <form action="/servicios/eliminar" method="POST" class="formEliminarServicio">
                <input type="hidden" name="id" value="<?php echo $servicio->id;?>">

                <input type="submit" value="Borrar" class="boton-eliminar eliminarServicio">
            </form>
        </div>
    </li>

    <?php } ?>
</ul>

<?php
    $script="
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/alerta.js'></script>";
    
?>