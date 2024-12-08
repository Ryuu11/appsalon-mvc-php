<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>
<?php 

    include_once __DIR__ . "/../templates/alertas.php";

?>
<form method="POST" action="/crear-cuenta" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tu Nombre"
            value="<?php echo s($usuario->nombre);?>"
            />
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Tu Apellido"
            value="<?php echo s($usuario->apellido);?>"/>
    </div>

    <div class="campo">
        <label for="telefono">Telefono</label>
        <input 
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Tu Telefono"
            value="<?php echo s($usuario->telefono);?>"/>
    </div>
    <div class="campo">
        <label for="E-mail">E-mail</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu E-mail"
            value="<?php echo s($usuario->email);?>"/>
    </div>
    <div class="campo">
        <label for="Password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password"
            />
    </div>

    <div class="centrar-boton">
        <input type="submit" class="boton" value="Crear Cuenta">
    </div>
</form>

<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia sesion</a>
    <a href="/olvide">Olvidaste tu contrasena?</a>

</div>