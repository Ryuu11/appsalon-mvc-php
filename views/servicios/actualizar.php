<h1 class="nombre-pagina">Actualizar Servicio</h1>

<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php

    //include_once __DIR__ . '/../templates/barra.php';

    include_once __DIR__ . '/../templates/alertas.php';
?>

<form method="POST" class="formulario formGuardar">
    <?php include_once __DIR__ . '/formulario.php';?>

    <input type="submit" class="boton botonGuardar" value="Actualizar">
</form>

<?php
    $script="
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/alerta.js'></script>";
    
?>

