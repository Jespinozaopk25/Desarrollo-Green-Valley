<?php
ob_start(); // Evita errores de cabecera


// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivos necesarios (ajustar rutas según tu estructura)
include_once 'config/conexionDB.php';
include_once 'config/datosconexion.php';
include_once 'includes/functions.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Función para validar datos de entrada
function validarDatos($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Función para calcular total del carrito
function calcularTotal($carrito) {
    $total = 0;
    if (!empty($carrito)) {
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
    }
    return $total;
}

// Función para formatear precio
function formatearPrecio($precio) {
    return '$' . number_format($precio, 0, ',', '.');
}

// Función para obtener cantidad total de items
function obtenerCantidadTotal($carrito) {
    $total = 0;
    if (!empty($carrito)) {
        foreach ($carrito as $item) {
            $total += $item['cantidad'];
        }
    }
    return $total;
}

// Función para limpiar precio (remover puntos y convertir a float)
function limpiarPrecio($precio) {
    if (is_string($precio)) {
        $precio = str_replace(['.', ','], ['', '.'], $precio);
    }
    return floatval($precio);
}

// Función para generar ID único del item
function generarIdItem($id, $kit_idx = null) {
    return $kit_idx !== null ? $id . '-' . $kit_idx : $id;
}

// Función para validar item del carrito
function validarItemCarrito($data) {
    $errores = [];
    
    if (empty($data['id'])) {
        $errores[] = "ID del producto es requerido";
    }
    
    if (empty($data['nombre'])) {
        $errores[] = "Nombre del producto es requerido";
    }
    
    if (!isset($data['precio']) || $data['precio'] <= 0) {
        $errores[] = "Precio válido es requerido";
    }
    
    if (!isset($data['cantidad']) || $data['cantidad'] <= 0) {
        $errores[] = "Cantidad válida es requerida";
    }
    
    return $errores;
}

// Datos de ejemplo de casas y kits (normalmente vendrían de base de datos)
$casas = [
    1 => [
        'titulo' => 'Casa Prefabricada 21 m²',
        'imagen' => 'IMG/casa1.jpg'
    ],
    2 => [
        'titulo' => 'Casa Prefabricada 36 m²',
        'imagen' => 'IMG/casa2.jpg'
    ],
    3 => [
        'titulo' => 'Casa Prefabricada 48 m²',
        'imagen' => 'IMG/casa3.jpg'
    ]
];

$kits = [
    1 => [
        0 => ['nombre' => 'Kit Básico', 'precio' => '5000000'],
        1 => ['nombre' => 'Kit Completo', 'precio' => '7000000'],
        2 => ['nombre' => 'Kit Premium', 'precio' => '9000000']
    ],
    2 => [
        0 => ['nombre' => 'Kit Estructural', 'precio' => '3000000'],
        1 => ['nombre' => 'Kit Inicial', 'precio' => '5000000'],
        2 => ['nombre' => 'Kit Completo', 'precio' => '7000000']
    ],
    3 => [
        0 => ['nombre' => 'Kit Esencial', 'precio' => '8000000'],
        1 => ['nombre' => 'Kit Integral', 'precio' => '11000000'],
        2 => ['nombre' => 'Kit Luxury', 'precio' => '15000000']
    ]
];

// Variable para mensajes
$mensaje = '';
$tipo_mensaje = '';

// Procesar acciones del carrito
if (isset($_GET['action']) || (isset($_POST['action']))) {
    $action = isset($_GET['action']) ? $_GET['action'] : $_POST['action'];

    switch ($action) {
        case 'add':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    // Agregar desde URL (método simple)
                    $id = validarDatos($_GET['id']);
                    $nombre = validarDatos($_GET['nombre']);
                    $precio = limpiarPrecio($_GET['precio']);
                    $cantidad = isset($_GET['cantidad']) ? intval($_GET['cantidad']) : 1;
                    $imagen = validarDatos($_GET['imagen']);
                    
                    $itemData = [
                        'id' => $id,
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'cantidad' => $cantidad,
                        'imagen' => $imagen
                    ];
                    
                } else {
                    // Agregar desde formulario POST (con kits)
                    $id = intval($_POST['id']);
                    $kit_idx = intval($_POST['kit']);
                    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
                    
                    // Validar que existan los datos
                    if (!isset($casas[$id]) || !isset($kits[$id][$kit_idx])) {
                        throw new Exception("Producto o kit no válido");
                    }
                    
                    $nombre = $casas[$id]['titulo'];
                    $imagen = $casas[$id]['imagen'];
                    $kit_nombre = $kits[$id][$kit_idx]['nombre'];
                    $kit_precio = limpiarPrecio($kits[$id][$kit_idx]['precio']);
                    
                    $key = generarIdItem($id, $kit_idx);
                    
                    $itemData = [
                        'id' => $key,
                        'nombre' => $nombre,
                        'kit' => $kit_nombre,
                        'precio' => $kit_precio,
                        'cantidad' => $cantidad,
                        'imagen' => $imagen
                    ];
                }
                
                // Validar datos del item
                $errores = validarItemCarrito($itemData);
                if (!empty($errores)) {
                    throw new Exception("Datos inválidos: " . implode(", ", $errores));
                }
                
                // Agregar al carrito
                $itemId = $itemData['id'];
                if (isset($_SESSION['carrito'][$itemId])) {
                    $_SESSION['carrito'][$itemId]['cantidad'] += $itemData['cantidad'];
                } else {
                    $_SESSION['carrito'][$itemId] = $itemData;
                }
                
                $mensaje = "Producto agregado al carrito exitosamente";
                $tipo_mensaje = "success";
                
            } catch (Exception $e) {
                $mensaje = "Error al agregar producto: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
            
            header("Location: carrito.php" . ($mensaje ? "?msg=" . urlencode($mensaje) . "&type=" . $tipo_mensaje : ""));
            exit;

        case 'update':
            try {
                if (isset($_POST['updates']) && is_array($_POST['updates'])) {
                    // Actualización múltiple
                    foreach ($_POST['updates'] as $itemId => $nuevaCantidad) {
                        $nuevaCantidad = intval($nuevaCantidad);
                        if (isset($_SESSION['carrito'][$itemId])) {
                            if ($nuevaCantidad > 0) {
                                $_SESSION['carrito'][$itemId]['cantidad'] = $nuevaCantidad;
                            } else {
                                unset($_SESSION['carrito'][$itemId]);
                            }
                        }
                    }
                    $mensaje = "Carrito actualizado exitosamente";
                } else {
                    // Actualización individual
                    $itemId = validarDatos($_POST['item_id']);
                    $cantidad = intval($_POST['cantidad']);
                    
                    if (isset($_SESSION['carrito'][$itemId])) {
                        if ($cantidad > 0) {
                            $_SESSION['carrito'][$itemId]['cantidad'] = $cantidad;
                            $mensaje = "Cantidad actualizada exitosamente";
                        } else {
                            unset($_SESSION['carrito'][$itemId]);
                            $mensaje = "Producto eliminado del carrito";
                        }
                    } else {
                        throw new Exception("Producto no encontrado en el carrito");
                    }
                }
                
                $tipo_mensaje = "success";
                
            } catch (Exception $e) {
                $mensaje = "Error al actualizar: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
            
            header("Location: carrito.php" . ($mensaje ? "?msg=" . urlencode($mensaje) . "&type=" . $tipo_mensaje : ""));
            exit;

        case 'remove':
            try {
                $itemId = isset($_GET['id']) ? validarDatos($_GET['id']) : validarDatos($_POST['item_id']);
                
                if (isset($_SESSION['carrito'][$itemId])) {
                    unset($_SESSION['carrito'][$itemId]);
                    $mensaje = "Producto eliminado del carrito exitosamente";
                    $tipo_mensaje = "success";
                } else {
                    throw new Exception("Producto no encontrado en el carrito");
                }
                
            } catch (Exception $e) {
                $mensaje = "Error al eliminar producto: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
            
            header("Location: carrito.php" . ($mensaje ? "?msg=" . urlencode($mensaje) . "&type=" . $tipo_mensaje : ""));
            exit;

        case 'clear':
            $_SESSION['carrito'] = [];
            $mensaje = "Carrito vaciado exitosamente";
            $tipo_mensaje = "success";
            
            header("Location: carrito.php" . ($mensaje ? "?msg=" . urlencode($mensaje) . "&type=" . $tipo_mensaje : ""));
            exit;

        case 'checkout':
            try {
                if (empty($_SESSION['carrito'])) {
                    throw new Exception("El carrito está vacío");
                }
                
                // Validar datos del formulario
                $nombre = validarDatos($_POST['nombre']);
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $telefono = validarDatos($_POST['telefono']);
                $direccion = validarDatos($_POST['direccion'] ?? '');
                $notas = validarDatos($_POST['notas'] ?? '');
                
                if (empty($nombre) || !$email || empty($telefono)) {
                    throw new Exception("Nombre, email válido y teléfono son requeridos");
                }
                
                // Aquí puedes procesar el pedido:
                // - Guardar en base de datos
                // - Enviar email de confirmación
                // - Procesar pago
                // - Etc.
                
                $pedido = [
                    'fecha' => date('Y-m-d H:i:s'),
                    'cliente' => [
                        'nombre' => $nombre,
                        'email' => $email,
                        'telefono' => $telefono,
                        'direccion' => $direccion,
                        'notas' => $notas
                    ],
                    'items' => $_SESSION['carrito'],
                    'total' => calcularTotal($_SESSION['carrito'])
                ];
                
                // Simular procesamiento del pedido
                // En producción, aquí harías:
                // $pedidoId = guardarPedido($pedido);
                // enviarEmailConfirmacion($pedido);
                
                // Limpiar carrito después del pedido exitoso
                $_SESSION['carrito'] = [];
                
                // Redirigir a página de confirmación
                header("Location: gracias.php?pedido_id=" . uniqid());
                exit;
                
            } catch (Exception $e) {
                $mensaje = "Error al procesar pedido: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
            break;
    }
}

// Procesar mensajes de la URL
if (isset($_GET['msg'])) {
    $mensaje = urldecode($_GET['msg']);
    $tipo_mensaje = $_GET['type'] ?? 'info';
}

// Calcular totales para mostrar
$subtotal = calcularTotal($_SESSION['carrito']);
$iva = $subtotal * 0.19;
$total = $subtotal + $iva;
$cantidadTotal = obtenerCantidadTotal($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Green Valley</title>
    <style>
        :root {
            --primary-color: #7cb342;
            --primary-dark: #689f38;
            --secondary-color: #2c3e50;
            --accent-color: #25d366;
            --text-light: #7f8c8d;
            --background-light: #f8f9fa;
            --white: #ffffff;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-medium: rgba(0, 0, 0, 0.12);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--secondary-color);
            background: var(--background-light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Messages */
        .message {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success-color);
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }

        .message.info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        /* Header simplificado para el ejemplo */
        header {
            background: var(--white);
            box-shadow: 0 4px 20px var(--shadow-light);
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            background: rgba(124, 179, 66, 0.2);
            color: var(--primary-color);
        }

        .cart-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        /* Cart Styles */
        .cart-container {
            padding: 30px 0;
            min-height: 60vh;
        }

        .cart-title {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 700;
        }

        .cart-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-top: 30px;
        }

        .cart-items {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px var(--shadow-light);
        }

        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto auto;
            gap: 20px;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image img {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info h3 {
            font-size: 1.1rem;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        .item-info .kit-info {
            color: var(--text-light);
            font-size: 14px;
        }

        .kit-badge {
            background: var(--primary-color);
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 11px;
        }

        .item-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .quantity-form {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-secondary {
            background: var(--text-light);
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Cart Summary */
        .cart-summary {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px var(--shadow-light);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .summary-row:last-of-type {
            border-bottom: 2px solid var(--primary-color);
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--secondary-color);
        }

        .total-amount {
            color: var(--primary-color);
        }

        /* Checkout Form */
        .checkout-form {
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(124, 179, 66, 0.2);
        }

        .checkout-btn {
            width: 100%;
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .checkout-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .continue-shopping {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 20px;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cart-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }
            
            .item-price, .quantity-form, .btn {
                grid-column: 2;
                margin-top: 10px;
                justify-self: start;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo">Green Valley</a>
            <div class="cart-icon">
                🛒<span class="cart-badge"><?php echo $cantidadTotal; ?></span>
            </div>
        </div>
    </header>

    <section class="cart-container">
        <div class="container">
            <h1 class="cart-title">Carrito de Compras</h1>

            <?php if ($mensaje): ?>
                <div class="message <?php echo $tipo_mensaje; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['carrito'])): ?>
                <div class="cart-content">
                    <div class="cart-items">
                        <!-- Formulario para actualización múltiple -->
                        <form method="POST" action="carrito.php">
                            <input type="hidden" name="action" value="update">
                            
                            <?php foreach ($_SESSION['carrito'] as $itemId => $item): ?>
                                <div class="cart-item">
                                    <div class="item-image">
                                        <img src="<?php echo htmlspecialchars($item['imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                                    </div>
                                    <div class="item-info">
                                        <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                        <?php if (isset($item['kit'])): ?>
                                            <div class="kit-info">
                                                Kit: <span class="kit-badge"><?php echo htmlspecialchars($item['kit']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-price">
                                        <?php echo formatearPrecio($item['precio']); ?>
                                    </div>
                                    <div class="quantity-form">
                                        <input type="number" 
                                               name="updates[<?php echo htmlspecialchars($itemId); ?>]" 
                                               value="<?php echo $item['cantidad']; ?>" 
                                               min="0" 
                                               class="quantity-input">
                                    </div>
                                    <div>
                                        <a href="carrito.php?action=remove&id=<?php echo urlencode($itemId); ?>" 
                                           class="btn btn-danger"
                                           onclick="return confirm('¿Eliminar este producto?')">
                                            Eliminar
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div style="margin-top: 20px; text-align: center;">
                                <button type="submit" class="btn btn-primary">Actualizar Carrito</button>
                                <a href="carrito.php?action=clear" class="btn btn-secondary"
                                   onclick="return confirm('¿Vaciar todo el carrito?')">Vaciar Carrito</a>
                            </div>
                        </form>
                    </div>

                    <div class="cart-summary">
                        <h3 class="summary-title">Resumen del Pedido</h3>
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span><?php echo formatearPrecio($subtotal); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>IVA (19%):</span>
                            <span><?php echo formatearPrecio($iva); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Total:</span>
                            <span class="total-amount"><?php echo formatearPrecio($total); ?></span>
                        </div>

                        <form class="checkout-form" method="POST" action="carrito.php">
                            <input type="hidden" name="action" value="checkout">
                            
                            <div class="form-group">
                                <label for="nombre">Nombre Completo *</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefono">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="direccion">Dirección de Entrega</label>
                                <textarea id="direccion" name="direccion" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="notas">Notas Adicionales</label>
                                <textarea id="notas" name="notas" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="checkout-btn">Finalizar Compra</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <h3>Tu carrito está vacío</h3>
                        <p>¡Agrega algunas casas prefabricadas para comenzar!</p>
                        <a href="index.php" class="continue-shopping">Continuar Comprando</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Auto-submit form when quantity changes
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (confirm('¿Actualizar cantidad?')) {
                        this.closest('form').submit();
                    }
                });
            });
        });
        
        // Form validation
        document.querySelector('.checkout-form')?.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();
            const telefono = document.getElementById('telefono').value.trim();
            
            if (!nombre || !email || !telefono) {
                e.preventDefault();
                alert('Por favor completa todos los campos obligatorios.');
                return false;
            }
            
            return confirm('¿Confirmar pedido?');
        });
    </script>
</body>
</html>