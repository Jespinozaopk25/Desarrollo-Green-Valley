<?php
<?php
session_start();
$id = $_GET['id'] ?? null;

// Dummy data para pruebas
$quote = [
    'id' => $id,
    'client_name' => 'Cliente Demo',
    'model' => 'Modelo A',
    'total' => 10000000,
    'date' => '2025-08-10',
    'status' => 'pendiente'
];

// Procesar formulario (solo ejemplo, no guarda nada)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí deberías guardar los cambios en la base de datos
    $quote['client_name'] = $_POST['client_name'];
    $quote['model'] = $_POST['model'];
    $quote['total'] = $_POST['total'];
    $quote['status'] = $_POST['status'];
    $msg = "Cambios guardados (simulado)";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cotización</title>
</head>
<body>
    <h2>Editar Cotización #<?php echo htmlspecialchars($quote['id']); ?></h2>
    <?php if (isset($msg)): ?>
        <p style="color:green;"><?php echo $msg; ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Cliente:</label>
        <input type="text" name="client_name" value="<?php echo htmlspecialchars($quote['client_name']); ?>"><br>
        <label>Modelo:</label>
        <input type="text" name="model" value="<?php echo htmlspecialchars($quote['model']); ?>"><br>
        <label>Total:</label>
        <input type="number" name="total" value="<?php echo htmlspecialchars($quote['total']); ?>"><br>
        <label>Estado:</label>
        <select name="status">
            <option value="pendiente" <?php if($quote['status']=='pendiente') echo 'selected'; ?>>Pendiente</option>
            <option value="aprobada" <?php if($quote['status']=='aprobada') echo 'selected'; ?>>Aprobada</option>
            <option value="rechazada" <?php if($quote['status']=='rechazada') echo 'selected'; ?>>Rechazada</option>
        </select><br>
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="employee-dashboard.php?section=my-quotes">Volver</a>
</body>
</html>