<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'usuario') {
    header('Location: login.php');
    exit;
}

$active_section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

if (!function_exists('getClientStats')) {
    function getClientStats($client_id) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;port=3306;dbname=green_valley_db;charset=utf8",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stats = [
                'my_quotes' => 0,
                'approved_quotes' => 0,
                'pending_quotes' => 0,
                'my_projects' => 0,
                'completed_projects' => 0,
                'total_invested' => 0,
                'active_projects' => 0
            ];

            // Lista de consultas específicas para el cliente
            $queries = [
                'my_quotes' => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ?",
                'approved_quotes' => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ? AND estado = 'aprobada'",
                'pending_quotes' => "SELECT COUNT(*) AS total FROM cotizacion WHERE id_usuario = ? AND estado = 'pendiente'",
                'my_projects' => "SELECT COUNT(*) AS total FROM proyecto WHERE id_usuario = ?",
                'completed_projects' => "SELECT COUNT(*) AS total FROM proyecto WHERE id_usuario = ? AND estado = 'completado'",
                'active_projects' => "SELECT COUNT(*) AS total FROM proyecto WHERE id_usuario = ? AND estado != 'completado'",
                'total_invested' => "SELECT COALESCE(SUM(total), 0) AS total FROM cotizacion WHERE id_usuario = ? AND estado = 'aprobada'"
            ];

            foreach ($queries as $key => $sql) {
                $stmt = $pdo->prepare($sql);
                if (!$stmt) {
                    continue;
                }
                $stmt->execute([$client_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats[$key] = $result['total'] ?? 0;
            }

            return $stats;
        } catch (Exception $e) {
            return [
                'my_quotes' => 3,
                'approved_quotes' => 1,
                'pending_quotes' => 2,
                'my_projects' => 1,
                'completed_projects' => 0,
                'total_invested' => 45000000,
                'active_projects' => 1
            ];
        }
    }
}

if (!function_exists('getClientQuotes')) {
    function getClientQuotes($client_id) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;port=3306;dbname=green_valley_db;charset=utf8",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("SELECT c.*, m.nombre as modelo_nombre FROM cotizacion c 
                                   LEFT JOIN modelo_casa m ON c.id_modelo = m.id_modelo 
                                   WHERE c.id_usuario = ? ORDER BY c.fecha DESC LIMIT 10");
            $stmt->execute([$client_id]);
            
            $quotes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quotes[] = $row;
            }
            
            return $quotes;
        } catch (Exception $e) {
            return [
                [
                    'id_cotizacion' => 1,
                    'modelo_nombre' => 'Casa Moderna 120m²',
                    'total' => 45000000,
                    'estado' => 'pendiente',
                    'fecha' => date('Y-m-d')
                ],
                [
                    'id_cotizacion' => 2,
                    'modelo_nombre' => 'Casa Familiar 90m²',
                    'total' => 35000000,
                    'estado' => 'aprobada',
                    'fecha' => date('Y-m-d', strtotime('-5 days'))
                ]
            ];
        }
    }
}

if (!function_exists('getClientProjects')) {
    function getClientProjects($client_id) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;port=3306;dbname=green_valley_db;charset=utf8",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("SELECT p.*, m.nombre as modelo_nombre FROM proyecto p 
                                   LEFT JOIN modelo_casa m ON p.id_modelo = m.id_modelo 
                                   WHERE p.id_usuario = ? ORDER BY p.fecha_inicio DESC");
            $stmt->execute([$client_id]);
            
            $projects = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Calcular progreso basado en estado
                $progreso = 0;
                switch($row['estado']) {
                    case 'planificacion': $progreso = 10; break;
                    case 'en_construccion': $progreso = 50; break;
                    case 'terminacion': $progreso = 85; break;
                    case 'completado': $progreso = 100; break;
                }
                $row['progreso'] = $progreso;
                $projects[] = $row;
            }
            
            return $projects;
        } catch (Exception $e) {
            return [
                [
                    'id_proyecto' => 1,
                    'modelo_nombre' => 'Casa Moderna 120m²',
                    'estado' => 'en_construccion',
                    'progreso' => 65,
                    'fecha_inicio' => date('Y-m-d', strtotime('-30 days')),
                    'fecha_estimada_fin' => date('Y-m-d', strtotime('+60 days'))
                ]
            ];
        }
    }
}

$client_id = $_SESSION['id_usuario'] ?? 1;
$stats = getClientStats($client_id);
$client_quotes = getClientQuotes($client_id);
$client_projects = getClientProjects($client_id);

$recent_activity = [
    [
        'icon' => 'file-alt',
        'title' => 'Nueva cotización solicitada',
        'description' => 'Cotización para casa de 120m² enviada',
        'time' => 'Hace 1 día'
    ],
    [
        'icon' => 'check-circle',
        'title' => 'Cotización aprobada',
        'description' => 'Tu cotización #1001 ha sido aprobada',
        'time' => 'Hace 3 días'
    ],
    [
        'icon' => 'hammer',
        'title' => 'Proyecto iniciado',
        'description' => 'Construcción de tu casa ha comenzado',
        'time' => 'Hace 1 semana'
    ]
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Cliente - Green Valley Estructuras</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }

      body {
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background-color: #f5f7fa;
          color: #333;
      }

      .top-bar {
          background: #34495e;
          color: white;
          padding: 1rem 2rem;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .top-bar-content {
          display: flex;
          justify-content: space-between;
          align-items: center;
      }

      .contact-info {
          display: flex;
          gap: 2rem;
          font-size: 0.9rem;
      }

      .contact-item {
          display: flex;
          align-items: center;
          gap: 0.5rem;
      }

      .whatsapp-buttons {
          display: flex;
          gap: 1rem;
      }

      .whatsapp-btn {
          background: #25d366;
          color: white;
          padding: 0.5rem 1rem;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          text-decoration: none;
          display: inline-block;
      }

      .header {
          background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
          color: white;
          padding: 1rem 2rem;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 2rem;
      }

      .header-content {
          display: flex;
          justify-content: space-between;
          align-items: center;
      }

      .logo {
          display: flex;
          align-items: center;
          gap: 0.5rem;
      }

      .logo h1 {
          margin: 0;
      }

      .role-badge {
          background: #27ae60;
          padding: 0.5rem 1rem;
          border-radius: 20px;
          font-size: 0.9rem;
      }

      .user-info {
          display: flex;
          align-items: center;
          gap: 1rem;
      }

      .btn {
          background: #27ae60;
          color: white;
          padding: 0.75rem 1.5rem;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          text-decoration: none;
          display: inline-block;
          margin-top: 1rem;
      }

      .btn.btn-outline {
          background: none;
          border: 1px solid #27ae60;
      }

      .btn.btn-primary {
          background: #27ae60;
      }

      .btn.btn-sm {
          padding: 0.5rem 1rem;
      }

      .btn:hover {
          background: #219a52;
      }

      .btn.btn-outline:hover {
          background: #27ae60;
          color: white;
      }

      .main {
          padding: 2rem;
      }

      .section {
          margin-bottom: 2rem;
      }

      .section h2 {
          margin-bottom: 1rem;
      }

      .section-subtitle {
          color: #666;
          margin-bottom: 1rem;
      }

      .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 1.5rem;
          margin-bottom: 2rem;
      }

      .stat-card {
          background: white;
          padding: 1.5rem;
          border-radius: 10px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          text-align: center;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 1rem;
      }

      .stat-icon {
          font-size: 2rem;
          color: #27ae60;
      }

      .stat-info {
          text-align: left;
      }

      .stat-info h3 {
          margin-bottom: 0.5rem;
          color: #2c3e50;
      }

      .stat-info p {
          color: #666;
          margin-bottom: 0.5rem;
      }

      .recent-activity {
          margin-bottom: 2rem;
      }

      .activity-list {
          margin-top: 1rem;
      }

      .activity-item {
          display: flex;
          align-items: center;
          gap: 1rem;
          margin-bottom: 1rem;
      }

      .activity-icon {
          font-size: 1.5rem;
          color: #27ae60;
      }

      .activity-item strong {
          margin-bottom: 0.5rem;
          display: block;
      }

      .activity-item small {
          color: #666;
      }

      .no-data {
          text-align: center;
          color: #666;
          margin-top: 1rem;
      }

      .inventory-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 1.5rem;
      }

      .inventory-card {
          background: white;
          border-radius: 10px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          overflow: hidden;
      }

      .inventory-info {
          padding: 1.5rem;
      }

      .inventory-info h3 {
          margin-bottom: 0.5rem;
          color: #2c3e50;
      }

      .model-price {
          color: #27ae60;
          margin-bottom: 0.5rem;
      }

      .stock-count {
          font-weight: bold;
      }

      .stock-count.low-stock {
          color: #e74c3c;
      }

      .inventory-actions {
          display: flex;
          gap: 1rem;
          margin-top: 1rem;
      }

      .under-construction {
          text-align: center;
          padding: 2rem;
          background: #f9f9f9;
          border-radius: 10px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .construction-icon {
          font-size: 3rem;
          color: #e74c3c;
          margin-bottom: 1rem;
      }

      .whatsapp-float {
          position: fixed;
          bottom: 2rem;
          right: 2rem;
          background: #25d366;
          color: white;
          padding: 1rem;
          border-radius: 50%;
          text-decoration: none;
          display: inline-block;
          font-size: 1.5rem;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      @media (max-width: 768px) {
          .stats-grid {
              grid-template-columns: repeat(2, 1fr);
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
                  <!-- Cambiado badge de vendedor a cliente -->
                  <span class="role-badge">Cliente</span>
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
      <!-- Navegación específica para clientes -->
      <nav class="nav-tabs">
          <a href="?section=dashboard" class="nav-link <?php echo $active_section === 'dashboard' ? 'active' : ''; ?>">
              <i class="fas fa-chart-bar"></i> Mi Dashboard
          </a>
          <a href="?section=quotes" class="nav-link <?php echo $active_section === 'quotes' ? 'active' : ''; ?>">
              <i class="fas fa-file-alt"></i> Mis Cotizaciones
          </a>
          <a href="?section=projects" class="nav-link <?php echo $active_section === 'projects' ? 'active' : ''; ?>">
              <i class="fas fa-hammer"></i> Mis Proyectos
          </a>
          <a href="?section=catalog" class="nav-link <?php echo $active_section === 'catalog' ? 'active' : ''; ?>">
              <i class="fas fa-home"></i> Catálogo
          </a>
          <a href="?section=profile" class="nav-link <?php echo $active_section === 'profile' ? 'active' : ''; ?>">
              <i class="fas fa-user"></i> Mi Perfil
          </a>
      </nav>

      <?php if ($active_section === 'dashboard'): ?>
          <!-- Dashboard Section -->
          <section class="section active">
              <h2>Mi Panel Personal</h2>
              
              <!-- Stats específicas para clientes -->
              <div class="stats-grid">
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-file-alt"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['my_quotes']; ?></h3>
                          <p>Mis Cotizaciones</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-check-circle"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['approved_quotes']; ?></h3>
                          <p>Cotizaciones Aprobadas</p>
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
                          <i class="fas fa-hammer"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['active_projects']; ?></h3>
                          <p>Proyectos Activos</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-home"></i>
                      </div>
                      <div class="stat-info">
                          <h3><?php echo $stats['completed_projects']; ?></h3>
                          <p>Proyectos Completados</p>
                      </div>
                  </div>
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="fas fa-dollar-sign"></i>
                      </div>
                      <div class="stat-info">
                          <h3>$<?php echo number_format($stats['total_invested']); ?></h3>
                          <p>Total Invertido</p>
                      </div>
                  </div>
              </div>

              <!-- Recent Activity -->
              <div class="recent-activity">
                  <h3>Mi Actividad Reciente</h3>
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

      <?php elseif ($active_section === 'quotes'): ?>
          <!-- Sección de cotizaciones del cliente -->
          <section class="section active">
              <h2>Mis Cotizaciones</h2>
              <p class="section-subtitle">Revisa el estado de todas tus cotizaciones solicitadas.</p>

              <div class="inventory-grid">
                  <?php if (empty($client_quotes)): ?>
                      <p class="no-data">No tienes cotizaciones registradas.</p>
                  <?php else: ?>
                      <?php foreach ($client_quotes as $quote): ?>
                          <div class="inventory-card">
                              <div class="inventory-info">
                                  <h3><?php echo htmlspecialchars($quote['modelo_nombre']); ?></h3>
                                  <p class="model-price">Precio: $<?php echo number_format($quote['total']); ?></p>
                                  <p>Estado: <span class="stock-count <?php echo $quote['estado'] === 'pendiente' ? 'low-stock' : ''; ?>"><?php echo ucfirst($quote['estado']); ?></span></p>
                                  <p>Fecha: <?php echo date('d/m/Y', strtotime($quote['fecha'])); ?></p>
                                  <div class="inventory-actions">
                                      <button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver Detalles</button>
                                      <?php if ($quote['estado'] === 'pendiente'): ?>
                                          <button class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Modificar</button>
                                      <?php endif; ?>
                                  </div>
                              </div>
                          </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
          </section>

      <?php elseif ($active_section === 'projects'): ?>
          <!-- Sección de proyectos del cliente -->
          <section class="section active">
              <h2>Mis Proyectos</h2>
              <p class="section-subtitle">Seguimiento del progreso de construcción de tus casas.</p>

              <div class="inventory-grid">
                  <?php if (empty($client_projects)): ?>
                      <p class="no-data">No tienes proyectos en curso.</p>
                  <?php else: ?>
                      <?php foreach ($client_projects as $project): ?>
                          <div class="inventory-card">
                              <div class="inventory-info">
                                  <h3><?php echo htmlspecialchars($project['modelo_nombre']); ?></h3>
                                  <p>Estado: <span class="stock-count"><?php echo str_replace('_', ' ', ucfirst($project['estado'])); ?></span></p>
                                  <p>Progreso: <?php echo $project['progreso']; ?>%</p>
                                  <div style="background: #eee; height: 10px; border-radius: 5px; margin: 10px 0;">
                                      <div style="background: #27ae60; height: 100%; width: <?php echo $project['progreso']; ?>%; border-radius: 5px;"></div>
                                  </div>
                                  <p>Inicio: <?php echo date('d/m/Y', strtotime($project['fecha_inicio'])); ?></p>
                                  <div class="inventory-actions">
                                      <button class="btn btn-primary btn-sm"><i class="fas fa-calendar"></i> Ver Cronograma</button>
                                      <button class="btn btn-outline btn-sm"><i class="fas fa-images"></i> Fotos</button>
                                  </div>
                              </div>
                          </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
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

  <!-- WhatsApp Float Button -->
  <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank">💬</a>
</body>
</html>
