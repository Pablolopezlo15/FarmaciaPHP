<?php use Utils\Utils;?>
<?php if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'): ?>
    <strong>Registro completado correctamente</strong>
<?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'):?>
    <strong>No se ha podido registrar</strong>
<?php endif;?>
<?php Utils::deleteSession('register');?>
<div>
    <h2>Registra un nuevo Empleado</h2>
    <form action="<?=BASE_URL?>usuario/registro/" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="data[nombre]" id="nombre" required>
    
        <label for="username">Username</label>
        <input type="text" name="data[username]" id="username" required>
    
        <label for="password">Contraseña</label>
        <input type="password" name="data[password]" id="password" required>
    
        <input type="submit" value="Registrarse" required>
    </form>
</div>
