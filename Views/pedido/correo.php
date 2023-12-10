<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado</title>
</head>
<body>
    <h1>¡Su pedido ha sido confirmado!</h1>
    <p>Estimado(a) <?php echo htmlspecialchars($this->nombre_cliente); ?>,</p>
    <p>Le informamos que su pedido está ya disponible para su recogida.</p>
    <p>Detalles del pedido:</p>
    <ul>
        <li>ID del pedido: <?php echo htmlspecialchars($this->id); ?></li>
        <li>Medicamento: <?php echo htmlspecialchars($this->medicamento); ?></li>
        <li>Fecha del pedido: <?php echo htmlspecialchars($this->fecha_pedido); ?></li>
    </ul>
    <p>Saludos cordiales,</p>
    <p>Farmacia de Pablo López Lozano</p>
</body>
</html>
