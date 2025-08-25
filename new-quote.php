<?php
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "green_valley_bd");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener clientes (usuarios tipo 'usuario')
$clientes = [];
$result = $conn->query("SELECT id, nombre FROM usuario WHERE tipo_usuario = 'usuario'");
while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}

// Obtener modelos de casa
$modelos = [];
$result = $conn->query("SELECT id, nombre FROM modelo_casa");
while ($row = $result->fetch_assoc()) {
    $modelos[] = $row;
}

// Procesar formulario
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $modelo_id = $_POST['modelo_id'];
    $total = $_POST['total'];
    $vendedor_id = $_SESSION['user_id'];
    $fecha = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO cotizacion (cliente_id, vendedor_id, modelo_id, total, fecha, estado) VALUES (?, ?, ?, ?, ?, 'pendiente')");
    $stmt->bind_param("iiiis", $cliente_id, $vendedor_id, $modelo_id, $total, $fecha);
    if ($stmt->execute()) {
        $msg = "Cotización creada exitosamente.";
    } else {
        $msg = "Error al crear la cotización.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Cotización</title>
</head>
<body>
    <h2>Crear Nueva Cotización</h2>
    <?php if ($msg): ?>
        <p><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Cliente:</label>
        <select name="cliente_id" required>
            <option value="">Seleccione...</option>
            <?php foreach ($clientes as $c): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>Modelo de Casa:</label>
        <select name="modelo_id" required>
            <option value="">Seleccione...</option>
            <?php foreach ($modelos as $m): ?>
                <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['nombre']); ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>Total:</label>
        <input type="number" name="total" required><br>
        <button type="submit">Crear Cotización</button>
    </form>
    <a href="employee-dashboard.php?section=my-quotes">Volver</a>
</body>
</html>