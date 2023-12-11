<table>
    <tr>
        <th>Nombre</th>
        <th>Cantidad</th>
        <th>Importe(€)</th>
        <?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>
            <th>Acciones</th>
        <?php endif;?>
    </tr>
    <?php 
            // how many records should be displayed on a page?
            $records_per_page = 6;
            // include the pagination class
            require 'vendor/stefangabos/zebra_pagination/Zebra_Pagination.php';
            // instantiate the pagination object
            $pagination = new Zebra_Pagination();
            $pagination->base_url(BASE_URL);
            // the number of total records is the number of records in the array
            $pagination->records(count($medicamento));
            // records per page
            $pagination->records_per_page($records_per_page);
            // here's the magic: we need to display *only* the records for the current page
            $medicamento = array_slice(
                $medicamento,
                (($pagination->get_page() - 1) * $records_per_page),
                $records_per_page
            );
        ?>
    <?php foreach ($resultadoBusqueda as $medicamentos): ?>
        <?php if((isset($_GET['id'])) && ($_GET['id'] == $medicamentos['id'])): ?>
        <tr>
        <form action="<?=BASE_URL?>medicamento/actualizar/?page=<?=$pagination->get_page()?>" method="post">
                <td><input type="text" name="nombre" value="<?=$medicamentos['nombre']?>"></td>
                <td><input type="text" name="stock" value="<?=$medicamentos['cantidad']?>"></td>
                <td><input type="text" name="precio" value="<?=$medicamentos['importe']?>"></td>
                <?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>
                    <td>
                        <input type="hidden" name="busquedaPorNombre" value="valor">
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
                    <a href="<?=BASE_URL?>medicamento/editar/?id=<?=$medicamentos['id']?>&page=<?=$pagination->get_page()?>"><button>Editar</button></a>
                    <a href="<?=BASE_URL?>medicamento/borrar/?id=<?=$medicamentos['id']?>&page=<?=$pagination->get_page()?>"><button>Borrar</button></a>
                </td>
            <?php endif;?>
        </tr>
        <?php endif;?>
    <?php endforeach;?>
</table>

<div>
    <h3>Buscar por nombre</h3>
    <?php if((isset($_POST['nombre'])) && ($_POST['nombre'] == "")): ?>
        <form action="<?=BASE_URL?>medicamento/mostrar/" id="buscar" method="post">
    <?php else: ?>
        <form action="<?=BASE_URL?>medicamento/buscar/" id="buscar" method="post">
    <?php endif; ?>
        <input type="text" name="nombre">
        <input type="submit" value="Buscar">
    </form>
</div>
