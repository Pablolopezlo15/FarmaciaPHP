<?php use Utils\Utils;?>
    <?php if(isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'complete'): ?>
        <strong>Producto creado correctamente</strong>
    <?php elseif(isset($_SESSION['pedido']) && $_SESSION['pedido'] == 'failed'):?>
        <strong>No se ha podido crear el producto</strong>
    <?php endif;?>
    <?php Utils::deleteSession('pedido');?>
    <div>
        <h2>Introduce un nuevo Producto</h2>
        <form action="<?=BASE_URL?>pedido/crear/" method="POST">
            <label for="nombre_cliente">Nombre cliente</label>
            <input type="text" name="nombre_cliente" required>
        
            <label for="email_cliente">Email cliente</label>
            <input type="email" name="email_cliente" required>
        
            <label for="medicamento">Nombre Producto</label>
            <input type="text" name="medicamento" required>
        
            <input type="submit" value="Nuevo Pedido" required>
        </form>
    </div>
