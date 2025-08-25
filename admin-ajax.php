<?php
header('Content-Type: application/json');
session_start();

try {
    $pdo = new PDO(
        "mysql:host=localhost;port=3306;dbname=green_valley_bd;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}


$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_or_update_stock':
        // Recibe id_modelo, cantidad, ubicacion (opcional), estado (opcional)
        $id_modelo = intval($_POST['id_modelo']);
        $cantidad = intval($_POST['cantidad']);
        $ubicacion = $_POST['ubicacion'] ?? 'Bodega Central - Santiago';
        $estado = $_POST['estado'] ?? 'disponible';
        try {
            // Verificar si ya existe stock para ese modelo y estado
            $stmt = $pdo->prepare("SELECT id_stock FROM stock_casa WHERE id_modelo = ? AND estado = ? LIMIT 1");
            $stmt->execute([$id_modelo, $estado]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                // Si existe, actualizar
                $stmt2 = $pdo->prepare("UPDATE stock_casa SET cantidad_disponible = ?, ubicacion = ?, estado = ?, fecha_actualizacion = NOW() WHERE id_stock = ?");
                $ok = $stmt2->execute([$cantidad, $ubicacion, $estado, $row['id_stock']]);
                echo json_encode(['success' => $ok, 'message' => 'Stock actualizado']);
            } else {
                // Si no existe, insertar
                $stmt2 = $pdo->prepare("INSERT INTO stock_casa (id_modelo, ubicacion, estado, cantidad_disponible, fecha_actualizacion) VALUES (?, ?, ?, ?, NOW())");
                $ok = $stmt2->execute([$id_modelo, $ubicacion, $estado, $cantidad]);
                echo json_encode(['success' => $ok, 'message' => 'Stock agregado']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al agregar/actualizar stock: ' . $e->getMessage()]);
        }
        break;
    case 'get_user':
        $id = intval($_POST['id_usuario']);
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Usuario no encontrado.']);
        }
        break;

    case 'edit_user':
        $id = intval($_POST['id_usuario']);
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'] ?? null;
        $rol = $_POST['rol'];
        $contrasena = $_POST['contrasena'] ?? '';

        if ($contrasena) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuario SET nombre=?, apellido=?, correo=?, telefono=?, rol=?, contrasena=? WHERE id_usuario=?");
            $ok = $stmt->execute([$nombre, $apellido, $correo, $telefono, $rol, $hash, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuario SET nombre=?, apellido=?, correo=?, telefono=?, rol=? WHERE id_usuario=?");
            $ok = $stmt->execute([$nombre, $apellido, $correo, $telefono, $rol, $id]);
        }
        echo json_encode(['success' => $ok]);
        break;

    case 'create_user':
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'] ?? null;
        $rol = $_POST['rol'];
        $contrasena = $_POST['contrasena'];
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Validar que el correo no exista
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'El correo ya está registrado.']);
            break;
        }

        $stmt = $pdo->prepare("INSERT INTO usuario (nombre, apellido, correo, telefono, rol, contrasena, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, 'activo', NOW())");
        $ok = $stmt->execute([$nombre, $apellido, $correo, $telefono, $rol, $hash]);
        echo json_encode(['success' => $ok]);
        break;

    case 'delete_user':
        $id = intval($_POST['id_usuario']);
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario=?");
        $ok = $stmt->execute([$id]);
        echo json_encode(['success' => $ok]);
        break;

    case 'get_cotizacion_details':
        try {
            $stmt = $pdo->prepare("
                SELECT c.*, u.nombre as cliente_nombre, u.apellido as cliente_apellido, 
                       u.correo, u.telefono, m.nombre as modelo_nombre, m.superficie
                FROM cotizacion c
                JOIN usuario u ON c.id_usuario = u.id_usuario
                LEFT JOIN modelo_casa m ON c.id_modelo = m.id_modelo
                WHERE c.id_cotizacion = ?
            ");
            $stmt->execute([$_POST['id_cotizacion']]);
            $cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cotizacion) {
                echo json_encode(['success' => true, 'cotizacion' => $cotizacion]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Cotización no encontrada']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al obtener cotización: ' . $e->getMessage()]);
        }
        break;

    case 'update_stock':
        try {
            $stmt = $pdo->prepare("UPDATE stock_casa SET cantidad_disponible = ?, fecha_actualizacion = NOW() WHERE id_modelo = ? AND estado = 'disponible' LIMIT 1");
            $stmt->execute([$_POST['stock'], $_POST['id_modelo']]);
            echo json_encode(['success' => true, 'message' => 'Stock actualizado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar stock: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
        break;
}
