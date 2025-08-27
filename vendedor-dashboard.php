<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'vendedor') {
    header('Location: login.php');
    exit;
}


$db_connected = false;
$pdo = null;

try {
    $pdo = new PDO(
        "mysql:host=localhost;port=3306;dbname=green_valley_bd;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $db_connected = true;
} catch (PDOException $e) {
    // Si no puede conectar, usar datos de ejemplo
    $db_connected = false;
}


$active_section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

function getEmployeeStats($employee_id)
{
        try {
            // Conexión a la BD
            $conn = new mysqli("localhost", "root", "", "green_valley_bd");
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }

            // Estructura base de estadísticas
            $stats = [
                'my_quotes'         => 0,
                'my_clients'        => 0,
                'monthly_revenue'   => 0,
                'pending_quotes'    => 0,
                'monthly_goal'      => 0,
                'completed_sales'   => 0,
                'total_quotes'      => 0,
                'total_houses'      => 0,
                'total_revenue'     => 0,
                'total_clients'     => 0,
                'completed_projects'=> 0
            ];
            // Consultas principales
            $queries = [
                'my_quotes'        => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ?",
                'total_quotes'     => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ?",
                'my_clients'       => "SELECT COUNT(DISTINCT id_usuario) AS total FROM cotizacion WHERE id_usuario = ?",
                'total_clients'    => "SELECT COUNT(DISTINCT id_usuario) AS total FROM cotizacion WHERE id_usuario = ?",
                'monthly_revenue'  => "SELECT COALESCE(SUM(total), 0) AS total 
                                        FROM cotizacion 
                                        WHERE id_usuario = ? 
                                        AND MONTH(fecha) = MONTH(CURDATE()) 
                                        AND YEAR(fecha) = YEAR(CURDATE())",
                'total_revenue'    => "SELECT COALESCE(SUM(total), 0) AS total FROM cotizacion WHERE id_usuario = ?",
                'pending_quotes'   => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ? AND estado = 'pendiente'",
                'completed_sales'  => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ? AND estado = 'aceptada'",
                'completed_projects'=> "SELECT COUNT(*) AS total FROM proyecto WHERE id_usuario = ? AND estado = 'entregado'"
            ];

            // Ejecutar consultas
            foreach ($queries as $key => $sql) {
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    continue; // Si falla la consulta, pasar
                }

                $stmt->bind_param("i", $employee_id);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();

                $stats[$key] = $result['total'] ?? 0;
                $stmt->close();
            }

            // Total de modelos de casas disponibles
            $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM modelo_casa");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                $stats['total_houses'] = $result['total'] ?? 0;
                $stmt->close();
            }

            // Meta mensual fija
            $stats['monthly_goal'] = 5000000;

            // Cerrar conexión
            $conn->close();

            return $stats;

        } catch (Exception $e) {
            // Datos de ejemplo si falla conexión o consultas
            return [];
        }
}

// ID del empleado actual (desde sesión o default 1)
$employee_id = $_SESSION['id_usuario'] ?? 1;

// Obtener estadísticas del empleado
$stats = getEmployeeStats($employee_id);

// Actividad reciente simulada
$recent_activity = [];
$conn = new mysqli("localhost", "root", "", "green_valley_bd");
if (!$conn->connect_error) {
    $result = $conn->query("
        SELECT c.id_cotizacion, u.nombre AS cliente, c.modelo_casa, c.fecha
        FROM cotizacion c
        JOIN usuario u ON c.id_usuario = u.id_usuario
        ORDER BY c.fecha DESC
        LIMIT 5
    ");
    while ($row = $result->fetch_assoc()) {
        $recent_activity[] = [
            'icon' => 'calculator',
            'title' => 'Nueva cotización',
            'description' => "Cliente {$row['cliente']} solicitó {$row['modelo_casa']}",
            'time' => $row['fecha']
        ];
    }
    $conn->close();
}


// Modelos de casas (ejemplo)
$house_models_data = [];
$conn = new mysqli("localhost", "root", "", "green_valley_bd");
if (!$conn->connect_error) {
    $result = $conn->query("SELECT nombre, precio_base, imagen_url, descripcion FROM modelo_casa");
    while ($row = $result->fetch_assoc()) {
        // Forzar ruta IMG/ para todas las imágenes y eliminar cualquier prefijo 'images' o similar
        $img = $row['imagen_url']
            ? 'IMG/' . preg_replace('#^(images/|IMG/|img/)+#i', '', ltrim($row['imagen_url'], '/\\'))
            : '/placeholder.svg?height=200&width=300';
        $house_models_data[] = [
            'name' => $row['nombre'],
            'price' => $row['precio_base'],
            'image' => $img,
            'description' => $row['descripcion'],
            'stock' => rand(3, 15) // simulado
        ];
    }
    $conn->close();
}




?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard vendedor - Green Valley Estructuras</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
      /* Integrando estilos CSS directamente para evitar problemas de rutas */
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }

      body {
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background-color: #f5f7fa;
          color: #333;
          line-height: 1.6;
      }

      .container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 20px;
      }

      /* Top Bar */
      .top-bar {
          background-color: #2c3e50;
          color: white;
          padding: 8px 0;
          font-size: 14px;
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
          gap: 10px;
      }

      .whatsapp-btn {
          background-color: #25d366;
          color: white;
          padding: 5px 12px;
          border-radius: 20px;
          text-decoration: none;
          font-size: 12px;
          transition: background-color 0.3s;
      }

      .whatsapp-btn:hover {
          background-color: #128c7e;
      }

      /* Header */
      .header {
          background-color: white;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          padding: 15px 0;
      }

      .header-content {
          display: flex;
          justify-content: space-between;
          align-items: center;
      }

      .logo {
          display: flex;
          align-items: center;
          gap: 15px;
      }

      .logo i {
          font-size: 2rem;
          color: #27ae60;
      }

      .logo h1 {
          font-size: 1.8rem;
          color: #2c3e50;
          margin: 0;
      }

      .role-badge {
          background-color: #e74c3c;
          color: white;
          padding: 4px 12px;
          border-radius: 20px;
          font-size: 12px;
          font-weight: bold;
      }

      .user-info {
          display: flex;
          align-items: center;
          gap: 15px;
      }

      .btn {
          padding: 8px 16px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 5px;
          font-size: 14px;
          transition: all 0.3s;
      }

      .btn-primary {
          background-color: #27ae60;
          color: white;
      }

      .btn-outline {
          background-color: transparent;
          color: #27ae60;
          border: 2px solid #27ae60;
      }

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      }

      /* Navigation */
      .nav-tabs {
          background-color: white;
          padding: 0;
          margin: 20px 0;
          border-radius: 10px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          display: flex;
          overflow-x: auto;
      }

      .nav-link {
          padding: 15px 20px;
          text-decoration: none;
          color: #666;
          border-bottom: 3px solid transparent;
          transition: all 0.3s;
          white-space: nowrap;
          display: flex;
          align-items: center;
          gap: 8px;
      }

      .nav-link:hover,
      .nav-link.active {
          color: #27ae60;
          border-bottom-color: #27ae60;
          background-color: #f8f9fa;
      }

      /* Main Content */
      .main {
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 20px;
      }

      .section {
          background-color: white;
          border-radius: 10px;
          padding: 30px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          margin-bottom: 20px;
      }

      .section h2 {
          color: #2c3e50;
          margin-bottom: 20px;
          font-size: 1.8rem;
      }

      /* Stats Grid */
      .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
      }

      .stat-card {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          padding: 25px;
          border-radius: 15px;
          display: flex;
          align-items: center;
          gap: 20px;
          transition: transform 0.3s;
      }

      .stat-card:hover {
          transform: translateY(-5px);
      }

      .stat-card:nth-child(2) {
          background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      }

      .stat-card:nth-child(3) {
          background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      }

      .stat-card:nth-child(4) {
          background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      }

      .stat-card:nth-child(5) {
          background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      }

      .stat-card:nth-child(6) {
          background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
          color: #333;
      }

      .stat-icon {
          font-size: 2.5rem;
          opacity: 0.8;
      }

      .stat-info h3 {
          font-size: 2rem;
          margin-bottom: 5px;
      }

      .stat-info p {
          opacity: 0.9;
          font-size: 0.9rem;
      }

      /* Recent Activity */
      .recent-activity {
          margin-top: 30px;
      }

      .recent-activity h3 {
          color: #2c3e50;
          margin-bottom: 20px;
          font-size: 1.4rem;
      }

      .activity-list {
          display: flex;
          flex-direction: column;
          gap: 15px;
      }

      .activity-item {
          display: flex;
          align-items: flex-start;
          gap: 15px;
          padding: 15px;
          background-color: #f8f9fa;
          border-radius: 10px;
          border-left: 4px solid #27ae60;
      }

      .activity-icon {
          background-color: #27ae60;
          color: white;
          width: 40px;
          height: 40px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          flex-shrink: 0;
      }

      /* Inventory Grid */
      .inventory-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 20px;
          margin-top: 20px;
      }

      .inventory-card {
          background-color: white;
          border-radius: 10px;
          overflow: hidden;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          transition: transform 0.3s;
      }

      .inventory-card:hover {
          transform: translateY(-5px);
      }

      .inventory-image {
          width: 100%;
          height: 200px;
          object-fit: cover;
      }

      .inventory-info {
          padding: 20px;
      }

      .inventory-info h3 {
          color: #2c3e50;
          margin-bottom: 10px;
      }

      .stock-count {
          font-weight: bold;
          color: #27ae60;
      }

      .stock-count.low-stock {
          color: #e74c3c;
      }

      .model-price {
          font-size: 1.2rem;
          font-weight: bold;
          color: #27ae60;
          margin: 10px 0;
      }

      .inventory-actions {
          display: flex;
          gap: 10px;
          margin-top: 15px;
      }

      .btn-sm {
          padding: 6px 12px;
          font-size: 12px;
      }

      /* Under Construction */
      .under-construction {
          text-align: center;
          padding: 60px 20px;
          color: #666;
      }

      .construction-icon {
          font-size: 4rem;
          margin-bottom: 20px;
      }

      .under-construction h3 {
          font-size: 1.5rem;
          margin-bottom: 10px;
          color: #2c3e50;
      }

      /* WhatsApp Float */
      .whatsapp-float {
          position: fixed;
          bottom: 20px;
          right: 20px;
          background-color: #25d366;
          color: white;
          width: 60px;
          height: 60px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          text-decoration: none;
          font-size: 1.5rem;
          box-shadow: 0 4px 12px rgba(0,0,0,0.3);
          transition: transform 0.3s;
          z-index: 1000;
      }

      .whatsapp-float:hover {
          transform: scale(1.1);
      }

      .no-data {
          text-align: center;
          color: #666;
          font-style: italic;
          padding: 40px;
      }

      /* Responsive */
      @media (max-width: 768px) {
          .top-bar-content {
              flex-direction: column;
              gap: 10px;
          }

          .header-content {
              flex-direction: column;
              gap: 15px;
              text-align: center;
          }

          .nav-tabs {
              flex-direction: column;
          }

          .stats-grid {
              grid-template-columns: 1fr;
          }

          .inventory-grid {
              grid-template-columns: 1fr;
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
                  <div class="contact-item">📞 +56 2 2583 2001</div>
              </div>
              <div class="whatsapp-buttons">
                  <a href="https://wa.me/56956397365" class="whatsapp-btn" target="_blank">💬 +569 5309 7365</a>
                  <a href="https://wa.me/56987037917" class="whatsapp-btn" target="_blank">💬 +569 8703 7917</a>
              </div>
          </div>
      </div>
  </div>

  <!-- Header -->
  <header class="header">
      <div class="container">
          <div class="header-content">
              <div class="logo">
                  <i class="fas fa-home"></i>
                  <h1>Green Valley</h1>
                  <span class="role-badge admin">Vendedor</span>
              </div>
              <div class="user-info">
                  <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                  <a href="logout.php" class="btn btn-outline">
                      <i class="fas fa-sign-out-alt"></i> Salir
                  </a>  
              </div>
          </div>
      </div>
  </header>

  <main class="main">
      <!-- Navigation -->
      <nav class="nav-tabs">
          <a href="?section=dashboard" class="nav-link <?php echo $active_section === 'dashboard' ? 'active' : ''; ?>">
              <i class="fas fa-chart-bar"></i> Dashboard
          </a>
          <a href="?section=quotes" class="nav-link <?php echo $active_section === 'quotes' ? 'active' : ''; ?>">
              <i class="fas fa-calculator"></i> Cotizaciones
          </a>
          <a href="?section=inventory" class="nav-link <?php echo $active_section === 'inventory' ? 'active' : ''; ?>">
              <i class="fas fa-boxes"></i> Inventario
          </a>
          <a href="?section=clients" class="nav-link <?php echo $active_section === 'clients' ? 'active' : ''; ?>">
              <i class="fas fa-users"></i> Clientes
          </a>
          <a href="?section=projects" class="nav-link <?php echo $active_section === 'projects' ? 'active' : ''; ?>">
              <i class="fas fa-home"></i> Proyectos
          </a>
        
          <a href="?section=settings" class="nav-link <?php echo $active_section === 'settings' ? 'active' : ''; ?>">
              <i class="fas fa-cog"></i> Configuración
          </a>
      </nav>

      <?php if ($active_section === 'dashboard'): ?>
          <!-- Dashboard Section -->
          <section class="section active">
              <h2>Panel de Administración</h2>
              
              <!-- Stats Grid -->
              <div class="stats-grid">
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-calculator"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['my_quotes']; ?></h3>
                          <p>Cotizaciones Totales</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-home"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['total_houses']; ?></h3>
                          <p>Modelos Disponibles</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-dollar-sign"></i>
                      </div>
                      <div class="stat-info">
                          <h3>$<?php echo number_format($stats['total_revenue']); ?></h3>
                          <p>Valor Total Cotizado</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-clock"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['pending_quotes']; ?></h3>
                          <p>Cotizaciones Pendientes</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-users"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['total_clients']; ?></h3>
                          <p>Clientes Registrados</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-check-circle"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['completed_projects']; ?></h3>
                          <p>Proyectos Completados</p>
                      </div>
                  </div>
              </div>

              <!-- Recent Activity -->
              <div class="recent-activity">
                  <h3>Actividad Reciente</h3>
                  <div class="activity-list">
                      <?php if (empty($recent_activity)): ?>
                          <p class="no-data">No hay actividad reciente.</p>
                      <?php else: ?>
                          <?php foreach ($recent_activity as $activity): ?>
                              <div class="activity-item">
                                  <div class="activity-icon">
                                      <i class="fas fa-<?php echo $activity['icon']; ?>"></i>
                                  </div>
                                  <div>
                                      <strong><?php echo htmlspecialchars($activity['title']); ?></strong>
                                      <p><?php echo htmlspecialchars($activity['description']); ?></p>
                                      <small><?php echo $activity['time']; ?></small>
                                  </div>
                              </div>
                          <?php endforeach; ?>
                      <?php endif; ?>
                  </div>
              </div>
          </section>
      <?php elseif ($active_section === 'inventory'): ?>
          <!-- Inventory Management Section -->
          <section class="section active">
              <h2>Gestión de Inventario de Casas</h2>
              <p class="section-subtitle">Visualiza y gestiona el stock de tus modelos de casas prefabricadas.</p>

              <div class="inventory-grid">
                  <?php if (empty($house_models_data)): ?>
                      <p class="no-data">No hay modelos de casas registrados.</p>
                  <?php else: ?>
                      <?php foreach ($house_models_data as $model): ?>
                          <div class="inventory-card">
                              <img src="<?php echo htmlspecialchars($model['image']); ?>" alt="<?php echo htmlspecialchars($model['name']); ?>" class="inventory-image">
                              <div class="inventory-info">
                                  <h3><?php echo htmlspecialchars($model['name']); ?></h3>
                                  <p>Stock Disponible: <span class="stock-count <?php echo $model['stock'] < 5 ? 'low-stock' : ''; ?>"><?php echo $model['stock']; ?></span> unidades</p>
                                  <p class="model-price">Precio: $<?php echo number_format($model['price']); ?></p>
                                  <p class="model-description"><?php echo htmlspecialchars($model['description']); ?></p>
                                  <div class="inventory-actions">
                                      <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</button>
                                      <button class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> Añadir Stock</button>
                                  </div>
                              </div>
                          </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
          </section>
      <?php elseif ($active_section === 'clients'): ?>
          <section class="section active">
              <h2>Mis Clientes</h2>
              <!-- Botón para agregar cliente -->
              <div style="margin-bottom: 20px;">
                  <button class="btn btn-primary" onclick="openAddClientPopup()">
                      <i class="fas fa-user-plus"></i> Agregar Cliente
                  </button>
              </div>

              <?php
              $conn = new mysqli("localhost", "root", "", "green_valley_bd");
              $stmt = $conn->prepare("
                  SELECT u.id_usuario, u.nombre, u.apellido, u.correo, u.telefono, c.id_cotizacion, c.fecha, c.total, c.estado
                  FROM usuario u
                  LEFT JOIN cotizacion c ON u.id_usuario = c.id_usuario
                  WHERE u.rol = 'usuario'
                  ORDER BY u.id_usuario, c.fecha DESC
              ");
              if ($stmt) {
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $clientes = [];
                  while ($row = $result->fetch_assoc()) {
                      if (!isset($clientes[$row['id_usuario']])) {
                          $clientes[$row['id_usuario']]['nombre'] = $row['nombre'];
                          $clientes[$row['id_usuario']]['apellido'] = $row['apellido'];
                          $clientes[$row['id_usuario']]['correo'] = $row['correo'];
                          $clientes[$row['id_usuario']]['telefono'] = $row['telefono'];
                          $clientes[$row['id_usuario']]['cotizaciones'] = [];
                      }
                      if ($row['id_cotizacion']) {
                          $clientes[$row['id_usuario']]['cotizaciones'][] = [
                              'id_cotizacion' => $row['id_cotizacion'],
                              'fecha' => $row['fecha'],
                              'total' => $row['total'],
                              'estado' => $row['estado']
                          ];
                      }
                  }
                  $stmt->close();
              ?>
              <?php if (empty($clientes)): ?>
                  <p class="no-data">No tienes clientes registrados aún.</p>
              <?php else: ?>
                  <?php foreach ($clientes as $cliente): ?>
                      <div style="margin-bottom:30px;">
                          <h3>
                              <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?> 
                              <small>(<?php echo htmlspecialchars($cliente['correo']); ?>)</small>
                              <br><small>Tel: <?php echo htmlspecialchars($cliente['telefono']); ?></small>
                          </h3>
                          <?php if (!empty($cliente['cotizaciones'])): ?>
                          <table style="width:100%;margin-bottom:10px;">
                              <tr>
                                  <th>ID Cotización</th>
                                  <th>Fecha</th>
                                  <th>Total</th>
                                  <th>Estado</th>
                                  <th>Acciones</th>
                              </tr>
                              <?php foreach ($cliente['cotizaciones'] as $cot): ?>
                              <tr>
                                  <td><?php echo $cot['id_cotizacion']; ?></td>
                                  <td><?php echo $cot['fecha']; ?></td>
                                  <td>$<?php echo number_format($cot['total']); ?></td>
                                  <td><?php echo ucfirst($cot['estado']); ?></td>
                                  <td>
                                      <button class="btn btn-primary btn-sm" onclick="openEditPopup(<?php echo $cot['id_cotizacion']; ?>)">Modificar</button>
                                      <button class="btn btn-outline btn-sm" onclick="openDeletePopup(<?php echo $cot['id_cotizacion']; ?>)">Eliminar</button>
                                  </td>
                              </tr>
                              <?php endforeach; ?>
                          </table>
                          <?php else: ?>
                              <p class="no-data">Este cliente no tiene cotizaciones registradas.</p>
                          <?php endif; ?>
                      </div>
                  <?php endforeach; ?>
              <?php endif; ?>
              <?php
              } else {
                  echo '<p class="no-data">Error en la consulta de clientes.</p>';
              }
              $conn->close();
              ?>


<!-- Pop-up Modificar (siempre disponible) -->
<div id="editPopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
    <div style="background:#fff;padding:30px;border-radius:10px;max-width:400px;margin:auto;position:relative;">
        <h3>Modificar Cotización</h3>
        <form id="editForm">
            <input type="hidden" name="id_cotizacion" id="edit_id_cotizacion">
            <label>Total:</label>
            <input type="number" name="total" id="edit_total" required style="width:100%;margin-bottom:10px;">
            <label>Estado:</label>
            <select name="estado" id="edit_estado" style="width:100%;margin-bottom:10px;">
                <option value="pendiente">Pendiente</option>
                <option value="aceptada">Aceptada</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            <button type="button" class="btn btn-outline btn-sm" onclick="closeEditPopup()">Cancelar</button>
        </form>
    </div>
</div>

              <!-- Pop-up Eliminar -->
              <div id="deletePopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
                  <div style="background:#fff;padding:30px;border-radius:10px;max-width:400px;margin:auto;position:relative;">
                      <h3>¿Eliminar Cotización?</h3>
                      <form id="deleteForm">
                          <input type="hidden" name="id_cotizacion" id="delete_id_cotizacion">
                          <button type="submit" class="btn btn-primary btn-sm">Eliminar</button>
                          <button type="button" class="btn btn-outline btn-sm" onclick="closeDeletePopup()">Cancelar</button>
                      </form>
                  </div>
              </div>

              

              <!-- Pop-up Agregar Cliente -->
              <div id="addClientPopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
                  <div style="background:#fff;padding:30px;border-radius:10px;max-width:400px;margin:auto;position:relative;">
                      <h3>Agregar Nuevo Cliente</h3>
                      <form id="addClientForm">
                          <label>Nombre:</label>
                          <input type="text" name="nombre" id="add_nombre" required style="width:100%;margin-bottom:10px;">
                          <label>Apellido:</label>
                          <input type="text" name="apellido" id="add_apellido" required style="width:100%;margin-bottom:10px;">
                          <label>Correo:</label>
                          <input type="email" name="correo" id="add_correo" required style="width:100%;margin-bottom:10px;">
                          <label>Contraseña:</label>
                          <input type="password" name="password" id="add_password" required style="width:100%;margin-bottom:10px;">
                          <label>Teléfono:</label>
                          <input type="text" name="telefono" id="add_telefono" required style="width:100%;margin-bottom:10px;">
                          <input type="hidden" name="rol" value="usuario">
                          <button type="submit" class="btn btn-primary btn-sm">Agregar</button>
                          <button type="button" class="btn btn-outline btn-sm" onclick="closeAddClientPopup()">Cancelar</button>
                      </form>
                  </div>
              </div>
          </section>
      <?php elseif ($active_section === 'quotes'): ?>
          <section class="section active">
              <h2>Cotizaciones</h2>
              <div style="margin-bottom: 20px;">
                  <button class="btn btn-primary" onclick="openNewQuotePopup()">
                      <i class="fas fa-plus"></i> Nueva Cotización
                  </button>
              </div>
              <?php
              $conn = new mysqli("localhost", "root", "", "green_valley_bd");
              $result = $conn->query("
                  SELECT c.id_cotizacion, u.nombre AS cliente, c.modelo_casa, c.region, c.total, c.estado, c.fecha
                  FROM cotizacion c
                  JOIN usuario u ON c.id_usuario = u.id_usuario
                  ORDER BY c.fecha DESC
              ");
              ?>
              <table style="width:100%;margin-bottom:10px;">
                  <tr>
                      <th>ID</th>
                      <th>Cliente</th>
                      <th>Modelo</th>
                      <th>Región</th>
                      <th>Total</th>
                      <th>Estado</th>
                      <th>Fecha</th>
                      <th>Acciones</th>
                  </tr>
                  <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                      <td><?php echo $row['id_cotizacion']; ?></td>
                      <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                      <td><?php echo htmlspecialchars($row['modelo_casa']); ?></td>
                      <td><?php echo htmlspecialchars($row['region']); ?></td>
                      <td>$<?php echo number_format($row['total']); ?></td>
                      <td><?php echo ucfirst($row['estado']); ?></td>
                      <td><?php echo $row['fecha']; ?></td>
                      <td>
                          <button class="btn btn-primary btn-sm" onclick="openEditPopup(<?php echo $row['id_cotizacion']; ?>)">Modificar</button>
                          <button class="btn btn-outline btn-sm" onclick="openDeletePopup(<?php echo $row['id_cotizacion']; ?>)">Eliminar</button>
                      </td>
                  </tr>
                  <?php endwhile; ?>
              </table>
              <?php $conn->close(); ?>

              <!-- Pop-up Modificar (debe estar aquí para cotizaciones) -->
              <div id="editPopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
                  <div style="background:#fff;padding:30px;border-radius:10px;max-width:400px;margin:auto;position:relative;">
                      <h3>Modificar Cotización</h3>
                      <form id="editForm">
                          <input type="hidden" name="id_cotizacion" id="edit_id_cotizacion">
                          <label>Total:</label>
                          <input type="number" name="total" id="edit_total" required style="width:100%;margin-bottom:10px;">
                          <label>Estado:</label>
                          <select name="estado" id="edit_estado" style="width:100%;margin-bottom:10px;">
                              <option value="pendiente">Pendiente</option>
                              <option value="aceptada">Aceptada</option>
                          </select>
                          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                          <button type="button" class="btn btn-outline btn-sm" onclick="closeEditPopup()">Cancelar</button>
                      </form>
                  </div>
              </div>

              <!-- Pop-up Eliminar (debe estar aquí para cotizaciones) -->
              <div id="deletePopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
                  <div style="background:#fff;padding:30px;border-radius:10px;max-width:400px;margin:auto;position:relative;">
                      <h3>¿Eliminar Cotización?</h3>
                      <form id="deleteForm">
                          <input type="hidden" name="id_cotizacion" id="delete_id_cotizacion">
                          <button type="submit" class="btn btn-primary btn-sm">Eliminar</button>
                          <button type="button" class="btn btn-outline btn-sm" onclick="closeDeletePopup()">Cancelar</button>
                      </form>
                  </div>
              </div>
          </section>
      <?php elseif ($active_section === 'projects'): ?>
          <section class="section active">
              <h2>Proyectos en Proceso</h2>
              <p class="section-subtitle">Aquí puedes ver el avance de las casas prefabricadas correspondientes a cotizaciones aceptadas.</p>
              <?php
              $conn = new mysqli("localhost", "root", "", "green_valley_bd");
              $result = $conn->query("
                  SELECT c.id_cotizacion, c.modelo_casa, c.region, c.total, c.fecha, p.estado AS estado_proyecto, p.progreso, u.nombre AS cliente
                  FROM cotizacion c
                  JOIN usuario u ON c.id_usuario = u.id_usuario
                  LEFT JOIN proyecto p ON c.id_cotizacion = p.id_cotizacion
                  WHERE c.estado = 'aceptada'
                  ORDER BY c.fecha DESC
              ");
              if ($result && $result instanceof mysqli_result):
              ?>
              <table style="width:100%;margin-bottom:10px;">
                  <tr>
                      <th>ID Cotización</th>
                      <th>Cliente</th>
                      <th>Modelo</th>
                      <th>Región</th>
                      <th>Total</th>
                      <th>Fecha</th>
                      <th>Estado Proyecto</th>
                      <th>Progreso</th>
                      <th>Acciones</th>
                  </tr>
                  <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                      <td><?php echo $row['id_cotizacion']; ?></td>
                      <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                      <td><?php echo htmlspecialchars($row['modelo_casa']); ?></td>
                      <td><?php echo htmlspecialchars($row['region']); ?></td>
                      <td>$<?php echo number_format($row['total']); ?></td>
                      <td><?php echo $row['fecha']; ?></td>
                      <td><?php echo $row['estado_proyecto'] ? ucfirst($row['estado_proyecto']) : 'En espera'; ?></td>
                      <td>
                          <?php if ($row['progreso'] !== null): ?>
                              <div style="width:120px;background:#eee;border-radius:8px;overflow:hidden;">
                                  <div style="width:<?php echo (int)$row['progreso']; ?>%;background:#27ae60;color:#fff;text-align:center;font-size:12px;padding:2px 0;">
                                      <?php echo (int)$row['progreso']; ?>%
                                  </div>
                              </div>
                          <?php else: ?>
                              <span style="color:#888;">-</span>
                          <?php endif; ?>
                      </td>
                      <td>
                          <button class="btn btn-primary btn-sm" onclick="viewProjectProcess(<?php echo $row['id_cotizacion']; ?>)">Ver Proceso</button>
                      </td>
                  </tr>
                  <?php endwhile; ?>
              </table>
              <?php else: ?>
                  <p class="no-data">No se pudieron obtener los proyectos. Verifica la conexión o la consulta SQL.</p>
              <?php endif; $conn->close(); ?>

              <!-- Modal para ver proceso de proyecto -->
              <div id="projectProcessModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
                  <div style="background:#fff;padding:30px;border-radius:10px;max-width:500px;margin:auto;position:relative;">
                      <h3>Proceso de Creación de la Casa</h3>
                      <div id="projectProcessContent">
                          <!-- Aquí se cargará el detalle por AJAX -->
                      </div>
                      <button class="btn btn-outline btn-sm" onclick="closeProjectProcessModal()">Cerrar</button>
                  </div>
              </div>
          </section>
      <?php else: ?>
          <!-- Other Sections -->
          <section class="section active">
              <div class="under-construction">
                  <div class="construction-icon">🚧</div>
                  <h3>Sección en Desarrollo</h3>
                  <p>La funcionalidad de <?php echo ucfirst($active_section); ?> estará disponible próximamente.</p>
              </div>
          </section>
      <?php endif; ?>
  </main>
<script>
    function openEditPopup(id) {
        // Obtener datos de la cotización por AJAX
        var formData = new FormData();
        formData.append('action', 'get');
        formData.append('id_cotizacion', id);

        fetch('cotizacion_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.cotizacion) {
                document.getElementById('edit_id_cotizacion').value = data.cotizacion.id_cotizacion;
                document.getElementById('edit_total').value = data.cotizacion.total;
                document.getElementById('edit_estado').value = data.cotizacion.estado;
                document.getElementById('editPopup').style.display = 'flex';
            } else {
                alert(data.error || 'No se pudo cargar la cotización.');
            }
        });
    }

    function closeEditPopup() {
        document.getElementById('editPopup').style.display = 'none';
    }

    function openDeletePopup(id) {
        document.getElementById('delete_id_cotizacion').value = id;
        document.getElementById('deletePopup').style.display = 'flex';
    }

    function closeDeletePopup() {
        document.getElementById('deletePopup').style.display = 'none';
    }


    function openAddClientPopup() {
        document.getElementById('addClientPopup').style.display = 'flex';
    }
    function closeAddClientPopup() {
        document.getElementById('addClientPopup').style.display = 'none';
        document.getElementById('addClientForm').reset();
    }

    function updateQuotePrice() {
        var select = document.getElementById('new_modelo_casa');
        var selectedOption = select.options[select.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        if (price) {
            document.getElementById('new_total').value = price;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {

        // Agregar cliente
        var addClientForm = document.getElementById('addClientForm');
        if (addClientForm) {
            addClientForm.onsubmit = function(e) {
                e.preventDefault();
                var formData = new FormData(addClientForm);
                formData.append('action', 'add_client');
                fetch('cotizacion_ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Cliente agregado correctamente.');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al agregar el cliente.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
                closeAddClientPopup();
            };
        }

        // Nueva cotización (AJAX)
        var newQuoteForm = document.getElementById('newQuoteForm');
        if (newQuoteForm) {
            newQuoteForm.onsubmit = function(e) {
                e.preventDefault();
                var idUsuario = document.getElementById('new_id_usuario').value;
                if (!idUsuario) {
                    alert('Debes seleccionar un cliente.');
                    return;
                }
                var formData = new FormData(newQuoteForm);
                formData.append('action', 'create');
                fetch('cotizacion_ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Cotización creada correctamente.');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al crear la cotización.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
                closeNewQuotePopup();
            };
        }

        // Editar cotización
        var editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.onsubmit = function(e) {
                e.preventDefault();
                var id = document.getElementById('edit_id_cotizacion').value;
                var total = document.getElementById('edit_total').value;
                var estado = document.getElementById('edit_estado').value;

                var formData = new FormData();
                formData.append('action', 'edit');
                formData.append('id_cotizacion', id);
                formData.append('total', total);
                formData.append('estado', estado);

                fetch('cotizacion_ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Cotización modificada correctamente.');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al modificar.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
                closeEditPopup();
            };
        }
    });


    // Proceso de proyecto (modal)
    function viewProjectProcess(idCotizacion) {
        // Aquí puedes hacer AJAX para traer el detalle del proceso
        var content = document.getElementById('projectProcessContent');
        content.innerHTML = '<p>Cargando proceso...</p>';
        document.getElementById('projectProcessModal').style.display = 'flex';

        // Simulación de AJAX (puedes reemplazar por fetch a un endpoint real)
        fetch('cotizacion_ajax.php', {
            method: 'POST',
            body: new URLSearchParams({ action: 'get_project_process', id_cotizacion: idCotizacion })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.proceso) {
                var html = '<ul style="padding-left:18px;">';
                data.proceso.forEach(function(etapa) {
                    html += '<li><strong>' + etapa.nombre + ':</strong> ' + etapa.estado + ' (' + etapa.fecha + ')</li>';
                });
                html += '</ul>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p>No hay información de proceso disponible.</p>';
            }
        })
        .catch(function() {
            content.innerHTML = '<p>Error al cargar el proceso.</p>';
        });
    }
    function closeProjectProcessModal() {
        document.getElementById('projectProcessModal').style.display = 'none';
    }

    // Mostrar popup de nueva cotización
    function openNewQuotePopup() {
        document.getElementById('newQuotePopup').style.display = 'flex';
    }
    function closeNewQuotePopup() {
        document.getElementById('newQuotePopup').style.display = 'none';
        document.getElementById('newQuoteForm').reset();
    }
</script>
  <!-- WhatsApp Float Button -->
  <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank">💬</a>

  <!-- Pop-up para nueva cotización -->
  <div id="newQuotePopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
      <div style="background:#fff;padding:30px;border-radius:10px;max-width:600px;margin:auto;position:relative;">
          <h3>Crear Nueva Cotización</h3>
          <form id="newQuoteForm">
              <label>Cliente:</label>
              <select name="id_usuario" id="new_id_usuario" required style="width:100%;margin-bottom:10px;">
                  <option value="">Seleccione un cliente</option>
                  <?php
                  $conn_users = new mysqli("localhost", "root", "", "green_valley_bd");
                  $users_result = $conn_users->query("SELECT id_usuario, nombre, correo FROM usuario WHERE rol = 'usuario'");
                  if ($users_result) {
                      while ($user = $users_result->fetch_assoc()) {
                          echo "<option value='{$user['id_usuario']}'>{$user['nombre']} ({$user['correo']})</option>";
                      }
                  }
                  $conn_users->close();
                  ?>
              </select>
              <label>Modelo de Casa:</label>
              <select name="modelo_casa" id="new_modelo_casa" onchange="updateQuotePrice()" style="width:100%;margin-bottom:10px;">
                  <option value="">Seleccione un modelo</option>
                  <?php foreach ($house_models_data as $model): ?>
                      <option value="<?php echo htmlspecialchars($model['name']); ?>" data-price="<?php echo htmlspecialchars($model['price']); ?>">
                          <?php echo htmlspecialchars($model['name']); ?> - $<?php echo number_format($model['price']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
              <label>Región:</label>
              <input type="text" name="region" id="new_region" required style="width:100%;margin-bottom:10px;">
              <label>Total:</label>
              <input type="number" name="total" id="new_total" required style="width:100%;margin-bottom:10px;">
              <label>Estado:</label>
              <select name="estado" id="new_estado" style="width:100%;margin-bottom:10px;">
                  <option value="pendiente">Pendiente</option>
                  <option value="aceptada">Aceptada</option>
              </select>
              <label>Observaciones:</label>
              <textarea name="observaciones" id="new_observaciones" rows="3" style="width:100%;margin-bottom:10px;"></textarea>
              <button type="submit" class="btn btn-primary btn-sm">Crear Cotización</button>
              <button type="button" class="btn btn-outline btn-sm" onclick="closeNewQuotePopup()">Cancelar</button>
          </form>
      </div>
  </div>
</body>
</html>
