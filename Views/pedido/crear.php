<?php use Utils\Utils;?>
    <?php if(isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'complete'): ?>
        <strong>Producto creado correctamente</strong>
    <?php elseif(isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'failed'):?>
        <strong>No se ha podido crear el producto</strong>
    <?php endif;?>
    <?php Utils::deleteSession('pedido');?>
    <?php if(!empty($errores)): ?>
        <div id="error" class="error">
            <?php foreach ($errores as $error): ?>
                <p><?=$error?></p>
            <?php endforeach; ?>
        </div>
    <?php endif;?>
    <div>
    <h2>Introduce un nuevo Producto</h2>
        <form action="<?=BASE_URL?>pedido/crear/" method="POST">
            <label for="nombre_cliente">Nombre cliente</label>
            <input type="text" name="nombre_cliente" value="<?php echo isset($_POST['nombre_cliente']) ? $_POST['nombre_cliente'] : ''; ?>">
                
            <label for="email_cliente">Email cliente</label>
            <input type="email" name="email_cliente" value="<?php echo isset($_POST['email_cliente']) ? $_POST['email_cliente'] : ''; ?>">
                
            <label for="medicamento">Nombre Producto</label>
            <input type="text" name="medicamento" value="<?php echo isset($_POST['medicamento']) ? $_POST['medicamento'] : ''; ?>">
                
            <input type="submit" value="Nuevo Pedido">
        </form>
    </div>
