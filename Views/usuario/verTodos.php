<h1>Empleados</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nombre</th>
            <th>Puesto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['username'] ?></td>
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['rol'] ?></td>
                <td>
                    <a href="<?= BASE_URL ?>usuario/ascender/?id=<?= $usuario['id'] ?>"><button>Ascender</button></a>
                    <a href="<?= BASE_URL ?>usuario/degradar/?id=<?= $usuario['id'] ?>"><button>Degradar</button></a>
                    <a href="<?= BASE_URL ?>usuario/borrar/?id=<?= $usuario['id'] ?>"><button>Borrar</button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>