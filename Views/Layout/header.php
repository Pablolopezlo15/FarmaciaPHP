<?php
    use Controllers\MedicamentoController;
    use Controllers\PedidoController;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Pagerfanta;

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmacia PHP Pablo</title>
    <link rel="stylesheet" href="<?=BASE_URL?>src/css/styles.css">
</head>
<body>
    <header>
        <?php if (isset($_SESSION['login']) AND $_SESSION['login']!='failed'):?>
            <?php
                $medicamento =  MedicamentoController::obtenerMedicamentos();
                $pedido = PedidoController::obtenerPedidos();
            ?>
            <nav>
                <ul>
                    <li><a href="<?=BASE_URL?>medicamento/mostrar/">Ver Productos</a></li>
                    <li><a href="<?=BASE_URL?>pedido/mostrar/">Ver Pedidos</a></li>
                <?php if (isset($_SESSION['login']) && ($_SESSION['login']->rol=='admin' || $_SESSION['login']->rol=='encargado')):?>
                    <!-- <li><a href="<?=BASE_URL?>medicamento/crear/">Nuevo Producto</a></li> -->
                    <?php if (isset($_SESSION['login']) AND $_SESSION['login']->rol=='admin'):?>
                    <li><a href="<?=BASE_URL?>usuario/registro/">Registrar Nuevo Empleado</a></li>
                    <li><a href="<?=BASE_URL?>usuario/verTodos/">Gestión de empleados</a></li>
                    <?php endif;?>
                <?php endif;?>
                </ul>
                <ul>
                    <li>Has iniciado sesión como: <?=$_SESSION['login']->nombre?>, <?=$_SESSION['login']->rol?></li>
                    <li><a href="<?=BASE_URL?>usuario/logout/">Cerrar Sesión</a></li>
                </ul>
            </nav>

        <?php endif;?>

    </header>


