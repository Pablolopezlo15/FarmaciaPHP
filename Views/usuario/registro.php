<?php use Utils\Utils;?>



<div>
    <h2>Registra un nuevo Empleado</h2>
    <?php if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'): ?>
        <strong class="exito">Registro completado correctamente</strong>
    <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'):?>
        <strong class="error">No se ha podido registrar</strong>
    <?php endif;?>
    <?php Utils::deleteSession('register');?>
    <?php if(!empty($errores)): ?>
        <div id="error" class="error">
            <?php foreach ($errores as $error): ?>
                <p><?=$error?></p>
            <?php endforeach; ?>
        </div>
    <?php endif;?>
    <form action="<?=BASE_URL?>usuario/registro/" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="data[nombre]" id="nombre">
    
        <label for="username">Username</label>
        <input type="text" name="data[username]" id="username">
    
        <label for="password">Contraseña</label>
        <input type="password" name="data[password]" id="password">
    
        <label for="rol">Rol</label>
        <select name="data[rol]" id="rol">
            <option value="user">Empleado</option>
            <option value="encargado">Encargado</option>
            <option value="admin">Administrador</option>
        </select>

        <input type="submit" value="Registrarse" required>
    </form>
</div>
