<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Restablece tu password escribiendo tu email a continuacion</p>

<?php 

    include_once __DIR__ . "/../templates/alertas.php";

?>

<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="text"
            id="email"
            name="email"
            placeholder="Tu email"
        
        />
    </div>
     
    <div class="centrar-boton">
    <input type="submit" class="boton" value="Enviar Instrucciones">
    </div>

</form>

<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia sesion</a>
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crea una</a>
</div>

