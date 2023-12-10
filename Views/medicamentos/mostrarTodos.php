<h1>Productos</h1>
<table>
    <tr>
        <th><a href="<?=BASE_URL?>medicamento/mostrar/?ordenacion=nombre&orden=asc">Nombre</a></th>
        <th><a href="<?=BASE_URL?>medicamento/mostrar/?ordenacion=cantidad&orden=desc">Cantidad</a></th>
        <th><a href="<?=BASE_URL?>medicamento/mostrar/?ordenacion=importe&orden=desc">Importe(€)</a></th>
        <?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>
            <th>Acciones</th>
        <?php endif;?>
    </tr>
    <?php foreach ($medicamento as $medicamentos): ?>
        <?php if((isset($_GET['id'])) && ($_GET['id'] == $medicamentos['id'])): ?>
        <tr>
            <form action="<?=BASE_URL?>medicamento/actualizar/" method="post">
                <td><input type="text" name="nombre" value="<?=$medicamentos['nombre']?>"></td>
                <td><input type="text" name="stock" value="<?=$medicamentos['cantidad']?>"></td>
                <td><input type="text" name="precio" value="<?=$medicamentos['importe']?>"></td>
                <?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>
                    <td>
                        <input type="hidden" name="id" value="<?=$medicamentos['id']?>">
                        <input type="submit" value="Guardar">
                    </td>
                <?php endif;?>
            </form>
        </tr>
        <?php else: ?>
        <tr>
            <td><?=$medicamentos['nombre']?></td>
            <td><?=$medicamentos['cantidad']?></td>
            <td><?=$medicamentos['importe']?></td>
            <?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>
                <td>
                    <a href="<?=BASE_URL?>medicamento/editar/?id=<?=$medicamentos['id']?>"><button>Editar</button></a>
                    <a href="<?=BASE_URL?>medicamento/borrar/?id=<?=$medicamentos['id']?>"><button>Borrar</button></a>
                </td>
            <?php endif;?>
        </tr>
        <?php endif;?>
    <?php endforeach;?>
</table>

<div>
    <h3>Buscar por nombre</h3>
    <?php if((isset($_POST['nombre'])) && ($_POST['nombre'] == "")): ?>
        <form id="buscar" action="<?=BASE_URL?>medicamento/mostrar/" method="post">
    <?php else: ?>
        <form id="buscar" action="<?=BASE_URL?>medicamento/buscar/" method="post">
    <?php endif; ?>
        <input type="text" name="nombre">
        <input type="submit" value="Buscar">
    </form>
</div>
<?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>

<div >
    <h2>Introduce un nuevo Producto</h2>
    <form action="<?=BASE_URL?>medicamento/crear/" id="nuevoProducto" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>
    
        <label for="stock">Stock</label>
        <input type="number" name="stock" id="stock" required>
    
        <label for="precio">Precio</label>
        <input type="text" name="precio" id="precio" required>
    
            
        <input type="submit" value="Crear" required>
    </form>
</div>

<?php endif;?>



