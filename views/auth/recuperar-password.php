<h1 class="nombre-pagina">Recuperar Password</h1>

<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>

<?php 

    include_once __DIR__ . "/../templates/alertas.php";

?>

<?php if($error) return; ?>

<form  class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
        type="password"
        id="password"
        name="password"
        placeholder="Tu nuevo password">
    </div>
    <div class="centar-boton">
    <input type="submit" class="boton"  value="Guardar nuevo password">
    </div>
</form>

<div class="acciones">
    <a href="/" class="href">Ya tienes cuenta? Incia sesion</a>
    <a href="/crear-cuenta">Aun no tienes cuenta? Crea una nueva cuenta</a>
</div>