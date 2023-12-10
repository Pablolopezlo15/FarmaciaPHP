<body>
    <nav>
    <h1>Lista de Pedidos</h1>
        <ul>
            <li><a href="<?=BASE_URL?>pedido/crear/">Crear nuevo pedido</a></li>
        </ul>
    </nav>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Nombre Cliente</th>
                <th>Email Cliente</th>
                <th>Fecha Pedido</th>
                <th>Producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedido as $pedidos): ?>
                <tr>
                    <td><?= $pedidos['id'] ?></td>
                    <td><?= $pedidos['nombre_cliente'] ?></td>
                    <td><?= $pedidos['email_cliente'] ?></td>
                    <td><?= $pedidos['fecha_pedido'] ?></td>
                    <td><?= $pedidos['medicamento'] ?></td>
                    <td>
                        <a href="<?=BASE_URL?>pedido/enviarCorreoPedido/?id=<?=$pedidos['id']?>"><button>Enviar email</button></a>
                        <a href="<?=BASE_URL?>pedido/borrar/?id=<?=$pedidos['id']?>"><button>Borrar</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>