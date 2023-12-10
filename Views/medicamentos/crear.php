<?php use Utils\Utils;?>
    <?php if(isset($_SESSION['medicamento']) && $_SESSION['medicamento'] == 'complete'): ?>
        <strong>Producto creado correctamente</strong>
    <?php elseif(isset($_SESSION['medicamento']) && $_SESSION['medicamento'] == 'failed'):?>
        <strong>No se ha podido crear el producto</strong>
    <?php endif;?>
    <?php Utils::deleteSession('medicamento');?>
    <div>
        <h2>Introduce un nuevo Producto</h2>
        <form action="<?=BASE_URL?>medicamento/crear/" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required>
        
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" required>
        
            <label for="precio">Precio</label>
            <input type="text" name="precio" id="precio" required>
        
                
            <input type="submit" value="Crear" required>
        </form>
    </div>
