<?php
if (ob_get_level()) ob_clean();
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'vendedor') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "green_valley_bd");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_client':
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $rol = $_POST['rol'] ?? 'usuario';

        if (!$nombre || !$apellido || !$correo || !$password || !$telefono) {
            echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
            exit;
        }

        // Validar que el correo no exista
        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => 'El correo ya está registrado']);
            $stmt->close();
            exit;
        }
        $stmt->close();

        // Insertar nuevo cliente con todos los campos
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuario (nombre, apellido, correo, contrasena, telefono, rol) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta: ' . $conn->error]);
            break;
        }
        $stmt->bind_param("ssssss", $nombre, $apellido, $correo, $hash, $telefono, $rol);
        if ($stmt->execute()) {
            $nuevo_id = $conn->insert_id;
            echo json_encode(['success' => true, 'id_usuario' => $nuevo_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar el cliente: ' . $stmt->error]);
        }
        $stmt->close();
        break;
    case 'create':
        $id_usuario = $_POST['id_usuario'] ?? '';
        $modelo_casa = $_POST['modelo_casa'] ?? '';
        $region = $_POST['region'] ?? '';
        $total = $_POST['total'] ?? 0;
        $estado = $_POST['estado'] ?? 'pendiente';
        $observaciones = $_POST['observaciones'] ?? '';
        // Obtener el id del vendedor actual desde la sesión
        $id_vendedor = $_SESSION['id_usuario'] ?? $_SESSION['user_id'] ?? null;

        // Validación básica detallada para depuración
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'error' => 'Falta el id_usuario']);
            exit;
        }
        if (!$modelo_casa) {
            echo json_encode(['success' => false, 'error' => 'Falta el modelo_casa']);
            exit;
        }
        if (!$region) {
            echo json_encode(['success' => false, 'error' => 'Falta la región']);
            exit;
        }
        if ($total === '') {
            echo json_encode(['success' => false, 'error' => 'Falta el total']);
            exit;
        }

        if (!$id_vendedor) {
            $session_debug = isset($_SESSION) ? json_encode($_SESSION) : 'NO SESSION';
            echo json_encode([
                'success' => false,
                'error' => 'Falta el id_vendedor. Depuración: $_SESSION = ' . $session_debug
            ]);
            exit;
        }

    // Log para depuración
    error_log('ID vendedor usado para cotización: ' . $id_vendedor);
    $stmt = $conn->prepare("INSERT INTO cotizacion (id_usuario, modelo_casa, region, total, estado, observaciones, fecha, id_vendedor) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("issdssi", $id_usuario, $modelo_casa, $region, $total, $estado, $observaciones, $id_vendedor);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al crear la cotización: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'edit':
        // Editar cotización existente
        $id_cotizacion = $_POST['id_cotizacion'] ?? '';
        $id_usuario = $_POST['id_usuario'] ?? null;
        $modelo_casa = $_POST['modelo_casa'] ?? null;
        $region = $_POST['region'] ?? null;
        $total = $_POST['total'] ?? null;
        $estado = $_POST['estado'] ?? null;
        $observaciones = $_POST['observaciones'] ?? null;
        // Obtener el id del vendedor actual desde la sesión (soportar ambos nombres)
        $id_vendedor = $_SESSION['id_usuario'] ?? $_SESSION['user_id'] ?? null;
        if (empty($id_cotizacion)) {
            echo json_encode(['success' => false, 'error' => 'ID de cotización es obligatorio']);
            break;
        }

        // Construir consulta dinámica solo con campos que se envían
        $fields = [];
        $values = [];
        $types = '';

        if ($id_usuario !== null) {
            $fields[] = "id_usuario = ?";
            $values[] = $id_usuario;
            $types .= 'i';
        }
        if ($modelo_casa !== null) {
            $fields[] = "modelo_casa = ?";
            $values[] = $modelo_casa;
            $types .= 's';
        }
        if ($region !== null) {
            $fields[] = "region = ?";
            $values[] = $region;
            $types .= 's';
        }
        if ($total !== null) {
            $fields[] = "total = ?";
            $values[] = $total;
            $types .= 'd';
        }
        if ($estado !== null) {
            $fields[] = "estado = ?";
            $values[] = $estado;
            $types .= 's';
        }
        if ($observaciones !== null) {
            $fields[] = "observaciones = ?";
            $values[] = $observaciones;
            $types .= 's';
        }

        if (empty($fields)) {
            echo json_encode(['success' => false, 'error' => 'No hay campos para actualizar']);
            break;
        }

        $values[] = $id_cotizacion;
        $types .= 'i';

        $sql = "UPDATE cotizacion SET " . implode(', ', $fields) . " WHERE id_cotizacion = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param($types, ...$values);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cotización actualizada correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la cotización']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta']);
        }
        break;

    case 'delete':
        // Eliminar cotización
        $id_cotizacion = $_POST['id_cotizacion'] ?? '';

        if (empty($id_cotizacion)) {
            echo json_encode(['success' => false, 'error' => 'ID de cotización es obligatorio']);
            break;
        }

        $stmt = $conn->prepare("DELETE FROM cotizacion WHERE id_cotizacion = ?");
        
        if ($stmt) {
            $stmt->bind_param("i", $id_cotizacion);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cotización eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar la cotización']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta']);
        }
        break;

    case 'get':
        $id = intval($_POST['id_cotizacion']);
        $stmt = $conn->prepare("SELECT id_cotizacion, total, estado FROM cotizacion WHERE id_cotizacion = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($cot = $result->fetch_assoc()) {
            echo json_encode(['success' => true, 'cotizacion' => $cot]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Cotización no encontrada']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        break;
}

$conn->close();
error_log('ID vendedor en sesión: ' . ($_SESSION['id_usuario'] ?? 'NO SET'));
