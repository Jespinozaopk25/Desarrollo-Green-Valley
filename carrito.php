<?php
session_start();

// CONFIGURACIÓN DE BASE DE DATOS - ADAPTAR SEGÚN TU CONFIGURACIÓN
/*
$host = 'localhost';
$dbname = 'green_valley_db';
$username = 'tu_usuario';
$password = 'tu_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
*/

// INICIALIZAR CARRITO SI NO EXISTE
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// PROCESAR ACCIONES DEL CARRITO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // AGREGAR PRODUCTO AL CARRITO
                $producto = array(
                    'id' => $_POST['id'],
                    'nombre' => $_POST['nombre'],
                    'precio' => $_POST['precio'],
                    'kit' => $_POST['kit'],
                    'cantidad' => $_POST['cantidad'],
                    'imagen' => $_POST['imagen']
                );
                
                // VERIFICAR SI EL PRODUCTO YA EXISTE EN EL CARRITO
                $encontrado = false;
                foreach ($_SESSION['carrito'] as $key => $item) {
                    if ($item['id'] == $producto['id'] && $item['kit'] == $producto['kit']) {
                        $_SESSION['carrito'][$key]['cantidad'] += $producto['cantidad'];
                        $encontrado = true;
                        break;
                    }
                }
                
                if (!$encontrado) {
                    $_SESSION['carrito'][] = $producto;
                }
                break;
                
            case 'update':
                // ACTUALIZAR CANTIDAD
                $index = $_POST['index'];
                $cantidad = $_POST['cantidad'];
                if ($cantidad > 0) {
                    $_SESSION['carrito'][$index]['cantidad'] = $cantidad;
                } else {
                    unset($_SESSION['carrito'][$index]);
                    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                }
                break;
                
            case 'remove':
                // ELIMINAR PRODUCTO
                $index = $_POST['index'];
                unset($_SESSION['carrito'][$index]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                break;
                
            case 'checkout':
                // PROCESAR COMPRA - AQUÍ DEBES ADAPTAR SEGÚN TU LÓGICA DE NEGOCIO
                /*
                // EJEMPLO DE INSERCIÓN EN BASE DE DATOS:
                
                // 1. INSERTAR PEDIDO PRINCIPAL
                $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_nombre, cliente_email, cliente_telefono, total, fecha_pedido, estado) VALUES (?, ?, ?, ?, NOW(), 'pendiente')");
                $stmt->execute([$_POST['nombre'], $_POST['email'], $_POST['telefono'], calcularTotal()]);
                $pedido_id = $pdo->lastInsertId();
                
                // 2. INSERTAR DETALLES DEL PEDIDO
                foreach ($_SESSION['carrito'] as $item) {
                    $stmt = $pdo->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, nombre_producto, kit_tipo, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $stmt->execute([$pedido_id, $item['id'], $item['nombre'], $item['kit'], $item['cantidad'], $item['precio'], $subtotal]);
                }
                
                // 3. LIMPIAR CARRITO
                $_SESSION['carrito'] = array();
                
                // 4. ENVIAR EMAIL DE CONFIRMACIÓN (OPCIONAL)
                // mail($_POST['email'], "Confirmación de pedido #$pedido_id", $mensaje_email);
                
                // 5. REDIRIGIR A PÁGINA DE CONFIRMACIÓN
                header("Location: confirmacion.php?pedido=$pedido_id");
                exit;
                */
                break;
        }
    }
}

// FUNCIÓN PARA CALCULAR TOTAL
function calcularTotal() {
    $total = 0;
    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
    }
    return $total;
}

// FUNCIÓN PARA FORMATEAR PRECIO
function formatearPrecio($precio) {
    return '$' . number_format($precio, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrito de Compras - Green Valley</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Bar Styles */
        .top-bar {
            background: #2c3e50;
            color: white;
            padding: 8px 0;
            font-size: 13px;
        }

        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .contact-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .whatsapp-buttons {
            display: flex;
            gap: 8px;
        }

        .whatsapp-btn {
            background: #25d366;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 11px;
            transition: background 0.3s;
        }

        .whatsapp-btn:hover {
            background: #128c7e;
        }

        /* Header Styles */
        header {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
        }

        .logo-image {
            height: 45px;
            width: auto;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #7cb342;
        }

        .cart-icon {
            position: relative;
            font-size: 20px;
            cursor: pointer;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        /* Cart Content */
        .cart-container {
            padding: 40px 0;
            min-height: 70vh;
        }

        .cart-title {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }

        .cart-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-top: 30px;
        }

        .cart-items {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info h3 {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .item-info p {
            color: #666;
            font-size: 14px;
        }

        .item-price {
            font-size: 1.3rem;
            font-weight: 600;
            color: #7cb342;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: #7cb342;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .remove-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .remove-btn:hover {
            background: #c0392b;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-cart h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .continue-shopping {
            display: inline-block;
            background: #7cb342;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        /* Cart Summary */
        .cart-summary {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            height: fit-content;
        }

        .summary-title {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .total-amount {
            color: #7cb342;
            font-size: 1.5rem;
        }

        /* Checkout Form */
        .checkout-form {
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #7cb342;
        }

        .checkout-btn {
            width: 100%;
            background: #7cb342;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .checkout-btn:hover {
            background: #689f38;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 15px;
            margin-top: 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 25px;
        }

        .footer-column h3 {
            color: #7cb342;
            margin-bottom: 15px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 8px;
        }

        .footer-column ul li a {
            color: #bdc3c7;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: #7cb342;
        }

        .copyright {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #34495e;
            color: #bdc3c7;
            font-size: 13px;
        }

        /* WhatsApp Float Button */
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #25d366;
            color: white;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            text-decoration: none;
            box-shadow: 0 3px 12px rgba(37, 211, 102, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.05);
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

            .item-price, .quantity-controls, .remove-btn {
                grid-column: 2;
                margin-top: 10px;
            }

            .top-bar-content {
                flex-direction: column;
                gap: 8px;
            }

            .contact-info {
                justify-content: center;
                font-size: 12px;
            }

            nav ul {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="contact-info">
                    <div class="contact-item">📍 Av. Padre Jorge Alessandri KM 22, San Bernardo, RM.</div>
                    <div class="contact-item">📧 contacto@casasgreenvalley.cl</div>
                    <div class="contact-item">📞 Tel.: +56 2 2583 2001</div>
                </div>
                <div class="whatsapp-buttons">
                    <a href="https://wa.me/56956397365" class="whatsapp-btn" target="_blank">💬 +569 5309 7365</a>
                    <a href="https://wa.me/56987037917" class="whatsapp-btn" target="_blank">💬 +569 8703 7917</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo">
                <img src="logoGreenValley.jpg" alt="Green Valley" class="logo-image">
            </a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="sobrenosotros.php">Nuestra Empresa</a></li>
                    <li><a href="index.php#catalog">Casas prefabricadas</a></li>
                    <li><a href="#llave">Llave en mano</a></li>
                    <li><a href="#proyectos">Proyectos</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <div class="cart-icon">
                🛒<span class="cart-badge"><?php echo count($_SESSION['carrito']); ?></span>
            </div>
        </div>
    </header>

    <!-- Cart Container -->
    <section class="cart-container">
        <div class="container">
            <h1 class="cart-title">Carrito de Compras</h1>

            <?php if (empty($_SESSION['carrito'])): ?>
                <!-- CARRITO VACÍO -->
                <div class="cart-items">
                    <div class="empty-cart">
                        <h3>Tu carrito está vacío</h3>
                        <p>¡Agrega algunas casas prefabricadas para comenzar!</p>
                        <a href="index.html#catalog" class="continue-shopping">Continuar Comprando</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- CARRITO CON PRODUCTOS -->
                <div class="cart-content">
                    <div class="cart-items">
                        <?php foreach ($_SESSION['carrito'] as $index => $item): ?>
                            <div class="cart-item">
                                <div class="item-image">
                                    <img src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                                </div>
                                <div class="item-info">
                                    <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                    <p>Kit: <?php echo htmlspecialchars($item['kit']); ?></p>
                                </div>
                                <div class="item-price">
                                    <?php echo formatearPrecio($item['precio']); ?>
                                </div>
                                <div class="quantity-controls">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" name="cantidad" value="<?php echo $item['cantidad'] - 1; ?>" class="quantity-btn">-</button>
                                    </form>
                                    <input type="number" value="<?php echo $item['cantidad']; ?>" class="quantity-input" readonly>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" name="cantidad" value="<?php echo $item['cantidad'] + 1; ?>" class="quantity-btn">+</button>
                                    </form>
                                </div>
                                <div>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" class="remove-btn" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- RESUMEN DEL CARRITO -->
                    <div class="cart-summary">
                        <h3 class="summary-title">Resumen del Pedido</h3>
                        
                        <?php 
                        $subtotal = 0;
                        foreach ($_SESSION['carrito'] as $item) {
                            $subtotal += $item['precio'] * $item['cantidad'];
                        }
                        $iva = $subtotal * 0.19; // 19% IVA - ADAPTAR SEGÚN TU PAÍS
                        $total = $subtotal + $iva;
                        ?>
                        
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

                        <!-- FORMULARIO DE CHECKOUT -->
                        <form method="post" class="checkout-form">
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
                                <textarea id="notas" name="notas" class="form-control" rows="3" placeholder="Instrucciones especiales, comentarios, etc."></textarea>
                            </div>
                            
                            <button type="submit" class="checkout-btn">Finalizar Compra</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Green Valley Estructuras</h3>
                    <p style="color: #bdc3c7; margin-bottom: 20px; font-size: 14px;">
                        Especialistas en casas prefabricadas de alta calidad. Transformamos tus sueños en realidad con diseños únicos y construcción eficiente.
                    </p>
                    <div style="color: #bdc3c7; font-size: 13px;">
                        <p>📍 Av. Padre Jorge Alessandri KM 22</p>
                        <p>San Bernardo, Región Metropolitana</p>
                        <p>📞 +56 2 2583 2001</p>
                        <p>📧 contacto@casasgreenvalley.cl</p>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Nuestros Servicios</h3>
                    <ul>
                        <li><a href="index.html#catalog">Casas Prefabricadas</a></li>
                        <li><a href="#">Tiny Houses</a></li>
                        <li><a href="#">Casas de Lujo</a></li>
                        <li><a href="#">Diseño Personalizado</a></li>
                        <li><a href="#">Llave en Mano</a></li>
                        <li><a href="#">Asesoría Técnica</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Información</h3>
                    <ul>
                        <li><a href="sobre-nosotros.html">Sobre Nosotros</a></li>
                        <li><a href="#">Nuestros Proyectos</a></li>
                        <li><a href="#">Proceso de Construcción</a></li>
                        <li><a href="#">Garantías</a></li>
                        <li><a href="#">Financiamiento</a></li>
                        <li><a href="#">Preguntas Frecuentes</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Síguenos</h3>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">YouTube</a></li>
                        <li><a href="#">LinkedIn</a></li>
                    </ul>
                    <div style="margin-top: 15px;">
                        <h4 style="color: #7cb342; margin-bottom: 8px; font-size: 14px;">WhatsApp</h4>
                        <a href="https://wa.me/56956397365" style="color: #25d366; text-decoration: none; font-size: 13px;">+569 5309 7365</a><br>
                        <a href="https://wa.me/56987037917" style="color: #25d366; text-decoration: none; font-size: 13px;">+569 8703 7917</a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Green Valley Estructuras. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank">💬</a>

    <script>
        // Actualizar badge del carrito
        function updateCartBadge() {
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = <?php echo count($_SESSION['carrito']); ?>;
            }
        }

        // Confirmar eliminación
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                    e.preventDefault();
                }
            });
        });

        // Validar formulario de checkout
        document.querySelector('.checkout-form').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const telefono = document.getElementById('telefono').value;
            
            if (!nombre || !email || !telefono) {
                e.preventDefault();
                alert('Por favor completa todos los campos obligatorios.');
                return false;
            }
            
            if (!confirm('¿Confirmas tu pedido? Se procesará inmediatamente.')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>