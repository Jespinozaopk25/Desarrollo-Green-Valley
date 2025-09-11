<?php
/**
 * Archivo de configuración y funciones para Green Valley
 * config.php
 */

// Configuración de la aplicación
define('APP_NAME', 'Green Valley Estructuras');
define('APP_VERSION', '1.0.0');
define('DEFAULT_TIMEZONE', 'America/Santiago');

// Configurar zona horaria
date_default_timezone_set(DEFAULT_TIMEZONE);

// Configuración de errores (cambiar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en HTTPS

// Configuración de base de datos (opcional)
define('DB_HOST', 'localhost');
define('DB_NAME', 'green_valley');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'contacto@casasgreenvalley.cl');
define('SMTP_PASS', ''); // Configurar en producción

// Configuración de WhatsApp
define('WHATSAPP_NUMBER_1', '56956397365');
define('WHATSAPP_NUMBER_2', '56987037917');

// Configuración de archivos
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

/**
 * Funciones auxiliares para el carrito
 */

/**
 * Conectar a la base de datos (opcional)
 */
function conectarDB() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Error de conexión: " . $e->getMessage());
        return false;
    }
}

/**
 * Validar y limpiar datos de entrada
 */
function limpiarDatos($data) {
    if (is_array($data)) {
        return array_map('limpiarDatos', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validar teléfono chileno
 */
function validarTelefono($telefono) {
    // Remover espacios y caracteres especiales
    $telefono = preg_replace('/[^0-9+]/', '', $telefono);
    
    // Patrones para teléfonos chilenos
    $patrones = [
        '/^\+569[0-9]{8}$/',    // +569XXXXXXXX
        '/^569[0-9]{8}$/',      // 569XXXXXXXX
        '/^09[0-9]{8}$/',       // 09XXXXXXXX
        '/^9[0-9]{8}$/',        // 9XXXXXXXX
        '/^\+5622[0-9]{7}$/',   // +56222XXXXXXX
        '/^22[0-9]{7}$/',       // 222XXXXXXX
    ];
    
    foreach ($patrones as $patron) {
        if (preg_match($patron, $telefono)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Formatear precio chileno
 */
function formatearPrecio($precio) {
    return '$' . number_format($precio, 0, ',', '.');
}

/**
 * Limpiar precio (convertir string a número)
 */
function limpiarPrecio($precio) {
    if (is_string($precio)) {
        $precio = str_replace(['.', '$', ' '], '', $precio);
        $precio = str_replace(',', '.', $precio);
    }
    return floatval($precio);
}

/**
 * Generar ID único
 */
function generarId($prefijo = '') {
    return $prefijo . uniqid() . rand(100, 999);
}

/**
 * Registrar actividad en log
 */
function registrarLog($mensaje, $tipo = 'INFO') {
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $log = "[{$fecha}] [{$tipo}] IP: {$ip} | {$mensaje} | UA: {$user_agent}" . PHP_EOL;
    
    file_put_contents('logs/app.log', $log, FILE_APPEND | LOCK_EX);
}

/**
 * Enviar notificación por email (requiere configuración SMTP)
 */
function enviarEmail($destinatario, $asunto, $mensaje, $esHTML = true) {
    // Esta función requiere una librería como PHPMailer
    // Por ahora solo registramos en log
    registrarLog("Email enviado a {$destinatario}: {$asunto}");
    return true;
}

/**
 * Generar mensaje de WhatsApp para pedido
 */
function generarMensajeWhatsApp($pedido) {
    $mensaje = "🏠 *NUEVO PEDIDO - GREEN VALLEY*\n\n";
    $mensaje .= "📝 *Pedido:* #{$pedido['id']}\n";
    $mensaje .= "📅 *Fecha:* " . date('d/m/Y H:i') . "\n\n";
    
    $mensaje .= "👤 *CLIENTE:*\n";
    $mensaje .= "• Nombre: {$pedido['cliente']['nombre']}\n";
    $mensaje .= "• Email: {$pedido['cliente']['email']}\n";
    $mensaje .= "• Teléfono: {$pedido['cliente']['telefono']}\n";
    
    if (!empty($pedido['cliente']['direccion'])) {
        $mensaje .= "• Dirección: {$pedido['cliente']['direccion']}\n";
    }
    
    $mensaje .= "\n🏠 *PRODUCTOS:*\n";
    foreach ($pedido['items'] as $item) {
        $mensaje .= "• {$item['nombre']}";
        if (isset($item['kit'])) {
            $mensaje .= " - {$item['kit']}";
        }
        $mensaje .= " (Qty: {$item['cantidad']}) - " . formatearPrecio($item['precio']) . "\n";
    }
    
    $mensaje .= "\n💰 *TOTAL:* " . formatearPrecio($pedido['total']);
    
    if (!empty($pedido['cliente']['notas'])) {
        $mensaje .= "\n\n📝 *Notas:* {$pedido['cliente']['notas']}";
    }
    
    return urlencode($mensaje);
}

/**
 * Guardar pedido en base de datos (opcional)
 */
function guardarPedido($pedido) {
    try {
        $pdo = conectarDB();
        if (!$pdo) {
            throw new Exception("Error de conexión a la base de datos");
        }
        
        // Comenzar transacción
        $pdo->beginTransaction();
        
        // Insertar pedido principal
        $sql = "INSERT INTO pedidos (id, fecha, cliente_nombre, cliente_email, cliente_telefono, cliente_direccion, notas, subtotal, iva, total, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $pedido['id'],
            $pedido['fecha'],
            $pedido['cliente']['nombre'],
            $pedido['cliente']['email'],
            $pedido['cliente']['telefono'],
            $pedido['cliente']['direccion'],
            $pedido['cliente']['notas'],
            $pedido['subtotal'],
            $pedido['iva'],
            $pedido['total'],
            'pendiente'
        ]);
        
        // Insertar items del pedido
        $sql_item = "INSERT INTO pedido_items (pedido_id, producto_nombre, kit, precio, cantidad, subtotal) 
                     VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_item = $pdo->prepare($sql_item);
        
        foreach ($pedido['items'] as $item) {
            $stmt_item->execute([
                $pedido['id'],
                $item['nombre'],
                $item['kit'] ?? null,
                $item['precio'],
                $item['cantidad'],
                $item['precio'] * $item['cantidad']
            ]);
        }
        
        // Confirmar transacción
        $pdo->commit();
        
        registrarLog("Pedido guardado: {$pedido['id']}");
        return true;
        
    } catch (Exception $e) {
        if (isset($pdo)) {
            $pdo->rollBack();
        }
        registrarLog("Error al guardar pedido: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

/**
 * Obtener datos de casas (normalmente desde DB)
 */
function obtenerCasas() {
    return [
        1 => [
            'titulo' => 'Casa Prefabricada 21 m²',
            'descripcion' => 'Perfecta para parejas o como oficina',
            'imagen' => 'IMG/casa1.jpg',
            'precio_base' => 4500000,
            'caracteristicas' => ['2 ambientes', 'Baño completo', 'Cocina integrada']
        ],
        2 => [
            'titulo' => 'Casa Prefabricada 36 m²',
            'descripcion' => 'Ideal para familias pequeñas',
            'imagen' => 'IMG/casa2.jpg',
            'precio_base' => 6500000,
            'caracteristicas' => ['2 dormitorios', 'Living-comedor', 'Baño completo', 'Cocina']
        ],
        3 => [
            'titulo' => 'Casa Prefabricada 48 m²',
            'descripcion' => 'Amplia y confortable para toda la familia',
            'imagen' => 'IMG/casa3.jpg',
            'precio_base' => 8500000,
            'caracteristicas' => ['3 dormitorios', 'Living-comedor', '2 baños', 'Cocina equipada']
        ]
    ];
}

/**
 * Obtener kits disponibles para cada casa
 */
function obtenerKits() {
    return [
        1 => [
            0 => [
                'nombre' => 'Kit Básico',
                'precio' => '5000000',
                'descripcion' => 'Estructura básica y terminaciones estándar',
                'incluye' => ['Estructura de madera', 'Revestimientos básicos', 'Puertas y ventanas estándar']
            ],
            1 => [
                'nombre' => 'Kit Completo',
                'precio' => '7000000',
                'descripcion' => 'Todo lo necesario para habitar',
                'incluye' => ['Kit Básico', 'Instalaciones eléctricas', 'Instalaciones sanitarias', 'Terminaciones mejoradas']
            ],
            2 => [
                'nombre' => 'Kit Premium',
                'precio' => '9000000',
                'descripción' => 'La mejor calidad y terminaciones de lujo',
                'incluye' => ['Kit Completo', 'Terminaciones premium', 'Electrodomésticos', 'Sistema de calefacción']
            ]
        ],
        2 => [
            0 => [
                'nombre' => 'Kit Inicial',
                'precio' => '6000000',
                'descripcion' => 'Lo esencial para comenzar',
                'incluye' => ['Estructura principal', 'Revestimientos básicos', 'Aberturas estándar']
            ],
            1 => [
                'nombre' => 'Kit Estándar',
                'precio' => '8000000',
                'descripcion' => 'Equipamiento completo estándar',
                'incluye' => ['Kit Inicial', 'Instalaciones completas', 'Terminaciones estándar']
            ],
            2 => [
                'nombre' => 'Kit Deluxe',
                'precio' => '12000000',
                'descripcion' => 'Máximo confort y calidad',
                'incluye' => ['Kit Estándar', 'Terminaciones de lujo', 'Equipamiento premium']
            ]
        ],
        3 => [
            0 => [
                'nombre' => 'Kit Esencial',
                'precio' => '8000000',
                'descripcion' => 'Base sólida para tu hogar',
                'incluye' => ['Estructura reforzada', 'Revestimientos de calidad', 'Aberturas mejoradas']
            ],
            1 => [
                'nombre' => 'Kit Integral',
                'precio' => '11000000',
                'descripcion' => 'Solución completa y funcional',
                'incluye' => ['Kit Esencial', 'Todas las instalaciones', 'Terminaciones de calidad']
            ],
            2 => [
                'nombre' => 'Kit Luxury',
                'precio' => '15000000',
                'descripcion' => 'La experiencia de lujo completa',
                'incluye' => ['Kit Integral', 'Materiales premium', 'Domótica básica', 'Garantía extendida']
            ]
        ]
    ];
}

/**
 * Validar datos del formulario de checkout
 */
function validarCheckout($datos) {
    $errores = [];
    
    if (empty($datos['nombre'])) {
        $errores[] = "El nombre es obligatorio";
    }
    
    if (empty($datos['email']) || !validarEmail($datos['email'])) {
        $errores[] = "Email válido es obligatorio";
    }
    
    if (empty($datos['telefono']) || !validarTelefono($datos['telefono'])) {
        $errores[] = "Teléfono válido es obligatorio";
    }
    
    return $errores;
}

/**
 * Crear directorio si no existe
 */
function crearDirectorio($ruta) {
    if (!is_dir($ruta)) {
        mkdir($ruta, 0755, true);
    }
}

// Crear directorios necesarios
crearDirectorio('logs');
crearDirectorio('uploads');

/**
 * Función para debugging (solo en desarrollo)
 */
function debug($data, $die = false) {
    if (defined('DEBUG') && DEBUG) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($die) die();
    }
}

// Definir constante de debug (cambiar en producción)
define('DEBUG', true);
?>

<!-- Script SQL para crear las tablas (opcional) -->
<?php /*
CREATE DATABASE IF NOT EXISTS green_valley;
USE green_valley;

CREATE TABLE pedidos (
    id VARCHAR(50) PRIMARY KEY,
    fecha DATETIME NOT NULL,
    cliente_nombre VARCHAR(100) NOT NULL,
    cliente_email VARCHAR(100) NOT NULL,
    cliente_telefono VARCHAR(20) NOT NULL,
    cliente_direccion TEXT,
    notas TEXT,
    subtotal DECIMAL(12,2) NOT NULL,
    iva DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'en_produccion', 'listo', 'entregado', 'cancelado') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pedido_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id VARCHAR(50) NOT NULL,
    producto_nombre VARCHAR(200) NOT NULL,
    kit VARCHAR(100),
    precio DECIMAL(12,2) NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(12,2) NOT NULL,
    imagen VARCHAR(300),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(12,2) NOT NULL,
    incluye JSON,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);
*/ ?>