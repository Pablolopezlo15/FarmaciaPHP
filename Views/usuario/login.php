<?php use Utils\Utils;?>

<?php if(!isset($_SESSION['login']) OR $_SESSION['login'] == 'failed'):?>
    <div>
        <h1>Farmacia</h1>
        <h2>Inicia Sesión</h2>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] == 'complete'): ?>
            <h3 class="exito">Login completado correctamente</h3>
        <?php elseif(isset($_SESSION['login']) && $_SESSION['login'] == 'failed'):?>
            <strong class="error">No se ha podido iniciar sesión</strong>
            <?php Utils::deleteSession('login'); ?>
        <?php endif;?>

        <?php if(!empty($errores)): ?>
            <div id="error" class="error">
                <?php foreach ($errores as $error): ?>
                    <p><?=$error?></p>
                <?php endforeach; ?>
            </div>
        <?php endif;?>
        
        <form action="<?=BASE_URL?>usuario/login/" method="POST">
            <label for="username">Username</label>
            <input type="text" name="data[username]" id="username">

            <label for="password">Contraseña</label>
            <input type="password" name="data[password]" id="password">

            <input type="submit" value="Login" required>
        </form>
    </div>

<?php endif;?>