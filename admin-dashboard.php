<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
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

function getAdminStats()
{
    global $pdo, $db_connected;
    if ($db_connected) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total_quotes FROM cotizacion");
            $total_quotes = $stmt->fetch()['total_quotes'] ?? 0;

            $stmt = $pdo->query("SELECT COUNT(*) as total_houses FROM modelo_casa");
            $total_houses = $stmt->fetch()['total_houses'] ?? 0;

            $stmt = $pdo->query("SELECT SUM(total) as total_revenue FROM cotizacion");
            $total_revenue = $stmt->fetch()['total_revenue'] ?? 0;

            $stmt = $pdo->query("SELECT COUNT(*) as pending_quotes FROM cotizacion WHERE estado = 'pendiente'");
            $pending_quotes = $stmt->fetch()['pending_quotes'] ?? 0;

            $stmt = $pdo->query("SELECT COUNT(*) as total_clients FROM usuario WHERE rol = 'usuario'");
            $total_clients = $stmt->fetch()['total_clients'] ?? 0;

            $stmt = $pdo->query("SELECT COUNT(*) as completed_projects FROM proyecto WHERE estado = 'entregado'");
            $completed_projects = $stmt->fetch()['completed_projects'] ?? 0;

            return [
                'total_quotes'       => $total_quotes,
                'total_houses'       => $total_houses,
                'total_revenue'      => $total_revenue,
                'pending_quotes'     => $pending_quotes,
                'total_clients'      => $total_clients,
                'completed_projects' => $completed_projects
            ];
        } catch (Exception $e) {
            // Si hay error, usar datos de ejemplo
        }
    }
}


function getHouseModels()
{
    global $pdo, $db_connected;
    if ($db_connected) {
        try {
            $stmt = $pdo->query("
                SELECT m.id_modelo, m.nombre, m.precio_base, m.imagen_url, m.descripcion,
                       (
                           SELECT s.cantidad_disponible
                           FROM stock_casa s
                           WHERE s.id_modelo = m.id_modelo AND s.estado = 'disponible'
                           ORDER BY s.id_stock DESC LIMIT 1
                       ) AS cantidad_disponible
                FROM modelo_casa m
            ");
            $models = [];
            while ($row = $stmt->fetch()) {
                $models[] = [
                    'id_modelo'   => $row['id_modelo'],
                    'name'        => $row['nombre'],
                    'price'       => $row['precio_base'],
                    'image'       => $row['imagen_url'] ?: '/placeholder.svg?height=200&width=300',
                    'description' => $row['descripcion'],
                    'stock'       => is_null($row['cantidad_disponible']) ? 0 : $row['cantidad_disponible']
                ];
            }
            if (!empty($models)) return $models;
        } catch (Exception $e) {
            return [];
        }
    }

    return [];
}



function getRecentActivity()
{
    global $pdo, $db_connected;    
    $stmt = $pdo->prepare("UPDATE stock_casa SET cantidad_disponible = ? WHERE id_modelo = ?");

    if ($db_connected) {
        try {
            $stmt = $pdo->query("
                SELECT 'Nueva cotización' as title, 
                        CONCAT('Cliente ', u.nombre, ' solicitó cotización para ', c.modelo_casa) as description,
                        c.fecha as time
                FROM cotizacion c 
                JOIN usuario u ON c.id_usuario = u.id_usuario 
                ORDER BY c.fecha DESC 
                LIMIT 5
            ");


            $activities = [];
            while ($row = $stmt->fetch()) {
                $activities[] = [
                    'icon'        => 'calculator',
                    'title'       => $row['title'],
                    'description' => $row['description'],
                    'time'        => 'Hace ' . timeAgo($row['time'])
                ];
            }

            if (!empty($activities)) {
                return $activities;
            }
        } catch (Exception $e) {
            return [];
        }
    }
    return [];
}

function timeAgo($datetime)
{
    $time = time() - strtotime($datetime);

    if ($time < 60) {
        return 'unos segundos';
    }
    if ($time < 3600) {
        return floor($time / 60) . ' minutos';
    }
    if ($time < 86400) {
        return floor($time / 3600) . ' horas';
    }
    if ($time < 2592000) {
        return floor($time / 86400) . ' días';
    }

    return 'hace tiempo';
}

function getAllUsers()
{
    global $pdo, $db_connected;
    if ($db_connected) {
    global $pdo, $db_connected;
        try {
            $stmt = $pdo->query("
                SELECT 
                    id_usuario, 
                    nombre, 
                    apellido, 
                    correo, 
                    telefono, 
                    rol, 
                    estado, 
                    fecha_creacion 
                FROM usuario
                ORDER BY fecha_creacion DESC
            ");

            $users = [];
            while ($row = $stmt->fetch()) {
                $users[] = $row;
            }

            if (!empty($users)) {
                return $users;
            }
        } catch (Exception $e) {
            // Si hay error, usar datos de ejemplo
        }
    }

    return [];
}


function getVendedoresWithClients()
{
    global $pdo, $db_connected;

    if ($db_connected) {
        try {
            $stmt = $pdo->query("
                SELECT 
                    v.id_usuario AS vendedor_id, 
                    v.nombre AS vendedor_nombre, 
                    v.apellido AS vendedor_apellido, 
                    COUNT(c.id_cotizacion) AS total_cotizaciones, 
                    COUNT(DISTINCT c.id_usuario) AS total_clientes
                FROM usuario v
                LEFT JOIN cotizacion c 
                    ON v.id_usuario = c.id_vendedor
                WHERE v.rol = 'vendedor'
                GROUP BY v.id_usuario
                ORDER BY total_cotizaciones DESC
            ");

            $vendedores = [];
            while ($row = $stmt->fetch()) {
                $vendedores[] = $row;
            }

            if (!empty($vendedores)) {
                return $vendedores;
            }
        } catch (Exception $e) {
            return [];
        }
    }
     return [];
}


function getAllCotizaciones()
{
    global $pdo, $db_connected;

    if ($db_connected) {
        try {
            $stmt = $pdo->query("
                SELECT 
                    c.id_cotizacion, 
                    c.fecha, 
                    c.estado, 
                    c.total, 
                    u.nombre AS cliente_nombre, 
                    u.apellido AS cliente_apellido, 
                    c.modelo_casa, 
                    u.correo, 
                    u.telefono, 
                    c.region, 
                    c.observaciones
                FROM cotizacion c
                JOIN usuario u 
                    ON c.id_usuario = u.id_usuario
                ORDER BY c.fecha DESC
                LIMIT 20
            ");

            $cotizaciones = [];
            while ($row = $stmt->fetch()) {
                $cotizaciones[] = $row;
            }

            if (!empty($cotizaciones)) {
                return $cotizaciones;
            }
        } catch (Exception $e) {
            // Si hay error, usar datos de ejemplo
        }
    }
    return[];
}

// Variables para pasar a la vista
$active_section    = isset($_GET['section']) ? $_GET['section'] : 'dashboard';
$stats             = getAdminStats();
$house_models_data = getHouseModels();
$recent_activity   = getRecentActivity();
$all_users         = getAllUsers();
$vendedores_data   = getVendedoresWithClients();
$all_cotizaciones  = getAllCotizaciones();



?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Administrador - Green Valley Estructuras</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
      /* Agregando estilos CSS directamente en el archivo */
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }

      body {
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background-color: #f5f5f5;
          color: #333;
          line-height: 1.6;
      }

      .container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 20px;
      }

      .top-bar {
          background-color: #2c3e50;
          color: white;
          padding: 10px 0;
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

      .whatsapp-buttons {
          display: flex;
          gap: 10px;
      }

      .whatsapp-btn {
          background-color: #25d366;
          color: white;
          padding: 5px 10px;
          border-radius: 15px;
          text-decoration: none;
          font-size: 12px;
          transition: background-color 0.3s;
      }

      .whatsapp-btn:hover {
          background-color: #128c7e;
      }

      .header {
          background-color: white;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          padding: 20px 0;
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
          font-size: 2rem;
          color: #2c3e50;
          margin: 0;
      }

      .role-badge {
          background-color: #e74c3c;
          color: white;
          padding: 5px 15px;
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
          padding: 10px 20px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 8px;
          font-size: 14px;
          transition: all 0.3s;
      }

      .btn-primary {
          background-color: #27ae60;
          color: white;
      }

      .btn-outline {
          background-color: transparent;
          color: #2c3e50;
          border: 2px solid #2c3e50;
      }

      .btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      }

      .main {
          padding: 30px 0;
      }

      .nav-tabs {
          display: flex;
          background-color: white;
          border-radius: 10px;
          padding: 10px;
          margin-bottom: 30px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          overflow-x: auto;
      }

      .nav-link {
          padding: 15px 20px;
          text-decoration: none;
          color: #666;
          border-radius: 5px;
          transition: all 0.3s;
          white-space: nowrap;
          display: flex;
          align-items: center;
          gap: 8px;
      }

      .nav-link:hover,
      .nav-link.active {
          background-color: #27ae60;
          color: white;
      }

      .section {
          background-color: white;
          border-radius: 10px;
          padding: 30px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

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
          border-radius: 10px;
          display: flex;
          align-items: center;
          gap: 20px;
          transition: transform 0.3s;
      }

      .stat-card:hover {
          transform: translateY(-5px);
      }

      .stat-icon {
          font-size: 2.5rem;
          opacity: 0.8;
      }

      .stat-info h3 {
          font-size: 2rem;
          margin-bottom: 5px;
      }

      .recent-activity {
          margin-top: 30px;
      }

      .activity-list {
          display: flex;
          flex-direction: column;
          gap: 15px;
          margin-top: 20px;
      }

      .activity-item {
          display: flex;
          align-items: center;
          gap: 15px;
          padding: 15px;
          background-color: #f8f9fa;
          border-radius: 8px;
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
      }

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
          padding: 8px 15px;
          font-size: 12px;
      }

      .under-construction {
          text-align: center;
          padding: 60px 20px;
          color: #666;
      }

      .construction-icon {
          font-size: 4rem;
          margin-bottom: 20px;
      }

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
                  <span class="role-badge admin">Administrador</span>
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
          <a href="?section=history" class="nav-link <?php echo $active_section === 'history' ? 'active' : ''; ?>">
              <i class="fas fa-history"></i> Historial
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
                          <h3><?php echo $stats['total_quotes']; ?></h3>
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
                          <p>Proyectos Entregados</p>
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
                      <?php foreach ($house_models_data as $index => $model): ?>
                          <div class="inventory-card">
                              <img src="<?php echo htmlspecialchars($model['image']); ?>" alt="<?php echo htmlspecialchars($model['name']); ?>" class="inventory-image">
                              <div class="inventory-info">
                                  <h3><?php echo htmlspecialchars($model['name']); ?></h3>
                                  <p>Stock Disponible: <span class="stock-count <?php echo $model['stock'] < 5 ? 'low-stock' : ''; ?>" id="stock-count-<?php echo $index; ?>"><?php echo $model['stock']; ?></span> unidades</p>
                                  <p class="model-price">Precio: $<?php echo number_format($model['price']); ?></p>
                                  <p class="model-description"><?php echo htmlspecialchars($model['description']); ?></p>
                                  <div class="inventory-actions">
                                      <button class="btn btn-primary btn-sm" onclick="showEditStockModal('<?php echo htmlspecialchars($model['name']); ?>', <?php echo $model['stock']; ?>, <?php echo $index; ?>, <?php echo $model['id_modelo']; ?>)"><i class="fas fa-edit"></i> Editar</button>
                                  </div>
                              </div>
                          </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
          </section>
      <?php elseif ($active_section === 'clients'): ?>
          <!-- Nueva sección completa de gestión de clientes -->
          <section class="section active">
              <h2>Gestión de Clientes y Usuarios</h2>
              <p class="section-subtitle">Administra todos los usuarios del sistema: clientes, vendedores y administradores.</p>

              <div style="margin-bottom: 20px;">
                  <button class="btn btn-primary" onclick="showAddUserForm()">
                      <i class="fas fa-user-plus"></i> Agregar Usuario
                  </button>
              </div>

              <div class="table-container" style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <thead style="background-color: #27ae60; color: white;">
                          <tr>
                              <th style="padding: 15px; text-align: left;">ID</th>
                              <th style="padding: 15px; text-align: left;">Nombre Completo</th>
                              <th style="padding: 15px; text-align: left;">Email</th>
                              <th style="padding: 15px; text-align: left;">Teléfono</th>
                              <th style="padding: 15px; text-align: left;">Rol</th>
                              <th style="padding: 15px; text-align: left;">Estado</th>
                              <th style="padding: 15px; text-align: left;">Fecha Registro</th>
                              <th style="padding: 15px; text-align: left;">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php if (empty($all_users)): ?>
                              <tr>
                                  <td colspan="8" style="padding: 40px; text-align: center; color: #666;">No hay usuarios registrados.</td>
                              </tr>
                          <?php else: ?>
                              <?php foreach ($all_users as $user): ?>
                                  <tr style="border-bottom: 1px solid #eee;">
                                      <td style="padding: 15px;"><?php echo $user['id_usuario']; ?></td>
                                      <td style="padding: 15px;">
                                          <strong><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></strong>
                                      </td>
                                      <td style="padding: 15px;"><?php echo htmlspecialchars($user['correo']); ?></td>
                                      <td style="padding: 15px;"><?php echo htmlspecialchars($user['telefono'] ?? 'No registrado'); ?></td>
                                      <td style="padding: 15px;">
                                          <span class="role-badge" style="background-color: <?php 
                                              echo $user['rol'] === 'administrador' ? '#e74c3c' : 
                                                   ($user['rol'] === 'vendedor' ? '#f39c12' : '#3498db'); 
                                          ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                              <?php echo ucfirst($user['rol']); ?>
                                          </span>
                                      </td>
                                      <td style="padding: 15px;">
                                          <span style="color: <?php echo $user['estado'] === 'activo' ? '#27ae60' : '#e74c3c'; ?>;">
                                              <?php echo ucfirst($user['estado'] ?? 'activo'); ?>
                                          </span>
                                      </td>
                                      <td style="padding: 15px;">
                                        <?php echo date('d/m/Y', strtotime($user['fecha_creacion'])); ?>
                                     </td>
                                      <td style="padding: 15px;">
                                        <?php if ($user['rol'] !== 'administrador'): ?>
                                            <button class="btn btn-primary btn-sm" onclick="editUser(<?php echo $user['id_usuario']; ?>)" style="margin-right: 5px;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline btn-sm" onclick="deleteUser(<?php echo $user['id_usuario']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </section>

      <?php elseif ($active_section === 'quotes'): ?>
          <!-- Nueva sección completa de gestión de cotizaciones -->
          <section class="section active">
              <h2>Gestión de Cotizaciones</h2>
              <p class="section-subtitle">Visualiza y administra todas las cotizaciones del sistema.</p>

              <div class="table-container" style="overflow-x: auto;">
                  <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                      <thead style="background-color: #27ae60; color: white;">
                          <tr>
                              <th style="padding: 15px; text-align: left;">ID</th>
                              <th style="padding: 15px; text-align: left;">Cliente</th>
                              <th style="padding: 15px; text-align: left;">Modelo</th>
                              <th style="padding: 15px; text-align: left;">Precio Total</th>
                              <th style="padding: 15px; text-align: left;">Estado</th>
                              <th style="padding: 15px; text-align: left;">Fecha</th>
                              <th style="padding: 15px; text-align: left;">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php if (empty($all_cotizaciones)): ?>
                              <tr>
                                  <td colspan="7" style="padding: 40px; text-align: center; color: #666;">No hay cotizaciones registradas.</td>
                              </tr>
                          <?php else: ?>
                              <?php foreach ($all_cotizaciones as $cotizacion): ?>
                                  <tr style="border-bottom: 1px solid #eee;">
                                      <td style="padding: 15px;"><?php echo $cotizacion['id_cotizacion']; ?></td>
                                      <td style="padding: 15px;">
                                          <strong><?php echo htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']); ?></strong>
                                      </td>
                                      <td style="padding: 15px;"><?php echo htmlspecialchars($cotizacion['modelo_casa']); ?></td>
                                      <td style="padding: 15px; font-weight: bold; color: #27ae60;">
                                          $<?php echo number_format($cotizacion['total']); ?>
                                      </td>
                                      <td style="padding: 15px;">
                                          <span style="background-color: <?php 
                                              echo $cotizacion['estado'] === 'pendiente' ? '#f39c12' : 
                                                   ($cotizacion['estado'] === 'aceptada' ? '#27ae60' : '#e74c3c'); 
                                          ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                              <?php echo ucfirst($cotizacion['estado']); ?>
                                          </span>
                                      </td>
                                      <td style="padding: 15px;"><?php echo date('d/m/Y', strtotime($cotizacion['fecha'])); ?></td>
                                      <td style="padding: 15px;">
                                          <button class="btn btn-primary btn-sm" onclick="viewCotizacion(<?php echo $cotizacion['id_cotizacion']; ?>)">
                                              <i class="fas fa-eye"></i> Ver
                                          </button>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </section>

      <?php elseif ($active_section === 'projects'): ?>
          <!-- Nueva sección de gestión de proyectos -->
          <section class="section active">
              <h2>Gestión de Proyectos</h2>
              <p class="section-subtitle">Supervisa todos los proyectos en construcción y entregados.</p>

              <div class="stats-grid" style="margin-bottom: 30px;">
                  <div class="stat-card">
                      <div class="stat-icon"><i class="fas fa-hammer"></i></div>
                      <div class="stat-info">
                          <h3>8</h3>
                          <p>En Construcción</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                      <div class="stat-info">
                          <h3><?php echo $stats['completed_projects']; ?></h3>
                          <p>Completados</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon"><i class="fas fa-pause-circle"></i></div>
                      <div class="stat-info">
                          <h3>2</h3>
                          <p>Suspendidos</p>
                      </div>
                  </div>
              </div>

              <div class="under-construction">
                  <div class="construction-icon">🏗️</div>
                  <h3>Vista Detallada de Proyectos</h3>
                  <p>La gestión completa de proyectos estará disponible próximamente.</p>
              </div>
          </section>

        <?php elseif ($active_section === 'history'): ?>
            <section class="section active">
                <h2>Historial de Cotizaciones Recientes</h2>
                <p class="section-subtitle">Últimas cotizaciones realizadas en el sistema.</p>
                <div class="table-container" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <thead style="background-color: #27ae60; color: white;">
                            <tr>
                                <th style="padding: 15px;">ID</th>
                                <th style="padding: 15px;">Cliente</th>
                                <th style="padding: 15px;">Modelo</th>
                                <th style="padding: 15px;">Precio Total</th>
                                <th style="padding: 15px;">Estado</th>
                                <th style="padding: 15px;">Fecha</th>
                                <th style="padding: 15px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all_cotizaciones)): ?>
                                <tr>
                                    <td colspan="7" style="padding: 40px; text-align: center; color: #666;">No hay cotizaciones registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($all_cotizaciones as $cotizacion): ?>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 15px;"><?php echo $cotizacion['id_cotizacion']; ?></td>
                                        <td style="padding: 15px;">
                                            <strong><?php echo htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']); ?></strong>
                                        </td>
                                        <td style="padding: 15px;"><?php echo htmlspecialchars($cotizacion['modelo_casa']); ?></td>
                                        <td style="padding: 15px; font-weight: bold; color: #27ae60;">
                                            $<?php echo number_format($cotizacion['total']); ?>
                                        </td>
                                        <td style="padding: 15px;">
                                            <span style="background-color: <?php 
                                                echo $cotizacion['estado'] === 'pendiente' ? '#f39c12' : 
                                                    ($cotizacion['estado'] === 'aceptada' ? '#27ae60' : '#e74c3c'); 
                                            ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                                <?php echo ucfirst($cotizacion['estado']); ?>
                                            </span>
                                        </td>
                                        <td style="padding: 15px;"><?php echo date('d/m/Y', strtotime($cotizacion['fecha'])); ?></td>
                                        <td style="padding: 15px;">
                                            <button class="btn btn-primary btn-sm" onclick="viewCotizacion(<?php echo $cotizacion['id_cotizacion']; ?>)">
                                                <i class="fas fa-eye"></i> Ver
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php elseif ($active_section === 'settings'): ?>
            <section class="section active">
                <h2>Configuración de Mi Cuenta</h2>
                <p class="section-subtitle">Actualiza tus datos personales y de acceso.</p>
                <form id="adminSettingsForm" style="max-width:400px;">
                    <div style="margin-bottom:15px;">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($_SESSION['nombre']); ?>" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:15px;">
                        <label>Email:</label>
                        <input type="email" name="correo" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:15px;">
                        <label>Teléfono:</label>
                        <input type="text" name="telefono" value="<?php echo isset($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : ''; ?>" style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:15px;">
                        <label>Nueva Contraseña (opcional):</label>
                        <input type="password" name="contrasena" style="width:100%;padding:8px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
                <script>
                document.getElementById('adminSettingsForm').onsubmit = function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    formData.append('action', 'update_admin_settings');
                    fetch('admin-ajax.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            alert('Datos actualizados correctamente.');
                            location.reload();
                        } else {
                            alert(data.error || 'Error al actualizar datos.');
                        }
                    })
                    .catch(() => {
                        alert('Error al procesar la solicitud.');
                    });
                };
                </script>
            </section>
        <?php endif; ?>


    
  </main>

  <!-- WhatsApp Float Button -->
  <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank">💬</a>

  <!-- Agregando JavaScript para funcionalidades administrativas -->
    <script>
    // --- MODALES DE STOCK ---
    function showEditStockModal(modelName, currentStock, modelIndex, idModelo) {
        document.getElementById('editStockModal').style.display = 'flex';
        document.getElementById('editStockModelName').innerText = modelName;
        document.getElementById('editStockInput').value = currentStock;
        document.getElementById('editStockModelIndex').value = modelIndex;
        document.getElementById('editStockIdModelo').value = idModelo;
    }

    function closeEditStockModal() {
        document.getElementById('editStockModal').style.display = 'none';
    }

    function saveStockChange() {
        const newStock = document.getElementById('editStockInput').value;
        const modelIndex = document.getElementById('editStockModelIndex').value;
        const idModelo = document.getElementById('editStockIdModelo').value;
        const formData = new FormData();
        formData.append('action', 'add_or_update_stock');
        formData.append('cantidad', newStock);
        formData.append('id_modelo', idModelo);
        fetch('admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('stock-count-' + modelIndex).innerText = newStock;
                alert(data.message || 'Stock actualizado correctamente.');
            } else {
                alert(data.error || 'Error al actualizar el stock.');
            }
            closeEditStockModal();
        })
        .catch(() => {
            alert('Error al actualizar el stock.');
            closeEditStockModal();
        });
    }

    // --- MODALES DE USUARIOS ---
    function showAddUserForm() {
        document.getElementById('addUserModal').style.display = 'flex';
    }

    function closeAddUserModal() {
        document.getElementById('addUserModal').style.display = 'none';
        document.getElementById('addUserForm').reset();
    }

    function editUser(userId) {
        // Carga los datos del usuario y abre el modal de edición
        const formData = new FormData();
        formData.append('action', 'get_user');
        formData.append('id_usuario', userId);
        fetch('admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.user) {
                document.getElementById('edit_user_id').value = data.user.id_usuario;
                document.getElementById('edit_user_nombre').value = data.user.nombre;
                document.getElementById('edit_user_apellido').value = data.user.apellido;
                document.getElementById('edit_user_correo').value = data.user.correo;
                document.getElementById('edit_user_telefono').value = data.user.telefono || '';
                document.getElementById('edit_user_rol').value = data.user.rol;
                document.getElementById('editUserModal').style.display = 'flex';
            } else {
                alert(data.error || 'No se pudo cargar el usuario.');
            }
        })
        .catch(() => {
            alert('Error al cargar el usuario.');
        });
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').style.display = 'none';
        document.getElementById('editUserForm').reset();
    }

    // --- ESTADO DE USUARIO (NO IMPLEMENTADO EN BACKEND) ---
    function toggleUserStatus(userId) {
        if (!confirm('¿Estás seguro de cambiar el estado de este usuario?')) return;
        const formData = new FormData();
        formData.append('action', 'toggle_user_status');
        formData.append('id_usuario', userId);
        fetch('admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Estado del usuario cambiado correctamente.');
                location.reload();
            } else {
                alert(data.error || 'Error al cambiar estado.');
            }
        })
        .catch(() => {
            alert('Error al procesar la solicitud.');
        });
    }

    // --- MODAL DE COTIZACIÓN ---
    function viewCotizacion(cotizacionId) {
        const formData = new FormData();
        formData.append('action', 'get_cotizacion_details');
        formData.append('id_cotizacion', cotizacionId);
        fetch('admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.cotizacion) {
                const cot = data.cotizacion;
                const content = `
                    <div style="margin-bottom:15px;">
                        <strong>ID:</strong> ${cot.id_cotizacion}<br>
                        <strong>Cliente:</strong> ${cot.cliente_nombre} ${cot.cliente_apellido}<br>
                        <strong>Email:</strong> ${cot.correo}<br>
                        <strong>Teléfono:</strong> ${cot.telefono || 'No registrado'}<br>
                        <strong>Modelo:</strong> ${cot.modelo_casa}<br>
                        <strong>Región:</strong> ${cot.region}<br>
                        <strong>Total:</strong> $${new Intl.NumberFormat().format(cot.total)}<br>
                        <strong>Estado:</strong> ${cot.estado}<br>
                        <strong>Fecha:</strong> ${cot.fecha}<br>
                        <strong>Observaciones:</strong> ${cot.observaciones || 'Ninguna'}
                    </div>
                `;
                document.getElementById('cotizacionDetailsContent').innerHTML = content;
                document.getElementById('cotizacionDetailsModal').style.display = 'flex';
            } else {
                alert(data.error || 'No se pudo cargar la cotización.');
            }
        })
        .catch(() => {
            alert('Error al cargar la cotización.');
        });
    }

    function closeCotizacionDetailsModal() {
        document.getElementById('cotizacionDetailsModal').style.display = 'none';
    }

    // --- ENVÍO DE FORMULARIOS ---
    document.addEventListener('DOMContentLoaded', function() {
        // Formulario agregar usuario
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            addUserForm.onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'create_user');
                fetch('admin-ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario creado correctamente.');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al crear usuario.');
                    }
                })
                .catch(() => {
                    alert('Error al procesar la solicitud.');
                });
                closeAddUserModal();
            };
        }

        // Formulario editar usuario
        const editUserForm = document.getElementById('editUserForm');
        if (editUserForm) {
            editUserForm.onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'edit_user');
                fetch('admin-ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario actualizado correctamente.');
                        location.reload();
                    } else {
                        alert(data.error || 'Error al actualizar usuario.');
                    }
                })
                .catch(() => {
                    alert('Error al procesar la solicitud.');
                });
                closeEditUserModal();
            };
        }
    });
    </script>

<!-- Modal para editar stock -->
<div id="editStockModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
    <div style="background:#fff;padding:30px;border-radius:10px;max-width:400px;margin:auto;position:relative;">
        <h3>Editar Stock de <span id="editStockModelName"></span></h3>
        <input type="number" id="editStockInput" min="0" style="width:100%;padding:8px;margin:15px 0;">
    <input type="hidden" id="editStockModelIndex">
    <input type="hidden" id="editStockIdModelo">
        <div style="display:flex;gap:10px;">
            <button class="btn btn-primary" onclick="saveStockChange()">Guardar</button>
            <button class="btn btn-outline" onclick="closeEditStockModal()">Cancelar</button>
        </div>
    </div>
</div>

</script>


<div id="addUserModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
    <div style="background:#fff;padding:30px;border-radius:10px;max-width:500px;margin:auto;position:relative;">
        <h3>Agregar Nuevo Usuario</h3>
        <form id="addUserForm">
            <div style="margin-bottom:15px;">
                <label>Nombre:</label>
                <input type="text" name="nombre" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Apellido:</label>
                <input type="text" name="apellido" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Email:</label>
                <input type="email" name="correo" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Teléfono:</label>
                <input type="text" name="telefono" style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Contraseña:</label>
                <input type="password" name="contrasena" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Rol:</label>
                <select name="rol" required style="width:100%;padding:8px;margin-top:5px;">
                    <option value="usuario">Usuario</option>
                    <option value="vendedor">Vendedor</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                <button type="button" class="btn btn-outline" onclick="closeAddUserModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar usuario -->
<div id="editUserModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
    <div style="background:#fff;padding:30px;border-radius:10px;max-width:500px;margin:auto;position:relative;">
        <h3>Editar Usuario</h3>
        <form id="editUserForm">
            <input type="hidden" name="id_usuario" id="edit_user_id">
            <div style="margin-bottom:15px;">
                <label>Nombre:</label>
                <input type="text" name="nombre" id="edit_user_nombre" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Apellido:</label>
                <input type="text" name="apellido" id="edit_user_apellido" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Email:</label>
                <input type="email" name="correo" id="edit_user_correo" required style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Teléfono:</label>
                <input type="text" name="telefono" id="edit_user_telefono" style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Nueva Contraseña (dejar vacío para mantener actual):</label>
                <input type="password" name="contrasena" style="width:100%;padding:8px;margin-top:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Rol:</label>
                <select name="rol" id="edit_user_rol" required style="width:100%;padding:8px;margin-top:5px;">
                    <option value="usuario">Usuario</option>
                    <option value="vendedor">Vendedor</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                <button type="button" class="btn btn-outline" onclick="closeEditUserModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para ver detalles de cotización -->
<div id="cotizacionDetailsModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
    <div style="background:#fff;padding:30px;border-radius:10px;max-width:600px;margin:auto;position:relative;">
        <h3>Detalles de Cotización</h3>
        <div id="cotizacionDetailsContent">
            <!-- Contenido se carga dinámicamente -->
        </div>
        <button type="button" class="btn btn-outline" onclick="closeCotizacionDetailsModal()">Cerrar</button>
    </div>
</div>
</body>
</html>
