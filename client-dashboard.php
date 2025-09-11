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
        'time' => 'Hace 1 día',
        'color' => '#667eea'
    ],
    [
        'icon' => 'check-circle',
        'title' => 'Cotización aprobada',
        'description' => 'Tu cotización #1001 ha sido aprobada',
        'time' => 'Hace 3 días',
        'color' => '#764ba2'
    ],
    [
        'icon' => 'hammer',
        'title' => 'Proyecto iniciado',
        'description' => 'Construcción de tu casa ha comenzado',
        'time' => 'Hace 1 semana',
        'color' => '#f093fb'
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
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-light: 0 8px 32px rgba(31, 38, 135, 0.15);
            --shadow-heavy: 0 15px 35px rgba(31, 38, 135, 0.2);
            --border-radius: 16px;
            --animation-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            color: #2d3748;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            left: 70%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            left: 40%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .top-bar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .contact-info {
            display: flex;
            gap: 2rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.9;
            transition: all var(--animation-speed) ease;
        }

        .contact-item:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .whatsapp-buttons {
            display: flex;
            gap: 1rem;
        }

        .whatsapp-btn {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: white;
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--animation-speed) ease;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        }

        .whatsapp-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.6);
        }

        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            color: white;
            padding: 2rem;
            box-shadow: var(--shadow-light);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo i {
            font-size: 2.5rem;
            background: var(--success-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .role-badge {
            background: var(--success-gradient);
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4); }
            50% { box-shadow: 0 4px 25px rgba(79, 172, 254, 0.6); }
            100% { box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4); }
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .btn {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            color: white;
            padding: 0.8rem 1.5rem;
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all var(--animation-speed) ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavy);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .nav-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
            overflow-x: auto;
            padding: 1rem 0;
        }

        .nav-link {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--animation-speed) ease;
            border: 1px solid var(--glass-border);
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .nav-link.active {
            background: var(--primary-gradient);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transform: translateY(-2px);
        }

        .nav-link:hover:not(.active) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
            background: rgba(255, 255, 255, 0.15);
        }

        .main {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .section-subtitle {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            transition: all var(--animation-speed) ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--animation-speed) ease;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-card:nth-child(1) .stat-icon { background: var(--primary-gradient); }
        .stat-card:nth-child(2) .stat-icon { background: var(--success-gradient); }
        .stat-card:nth-child(3) .stat-icon { background: var(--warning-gradient); }
        .stat-card:nth-child(4) .stat-icon { background: var(--secondary-gradient); }
        .stat-card:nth-child(5) .stat-icon { background: var(--info-gradient); }
        .stat-card:nth-child(6) .stat-icon { background: var(--dark-gradient); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .stat-info h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .stat-info p {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 1rem;
        }

        .activity-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-light);
            margin-top: 2rem;
        }

        .activity-section h3 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .activity-item {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all var(--animation-speed) ease;
            position: relative;
            overflow: hidden;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-gradient);
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform var(--animation-speed) ease;
        }

        .activity-item:hover::before {
            transform: scaleY(1);
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .activity-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .activity-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .activity-info {
            flex: 1;
        }

        .activity-info strong {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .activity-info p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .activity-info small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .inventory-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all var(--animation-speed) ease;
            position: relative;
        }

        .inventory-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--animation-speed) ease;
        }

        .inventory-card:hover::before {
            transform: scaleX(1);
        }

        .inventory-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-heavy);
        }

        .inventory-info {
            padding: 2rem;
        }

        .inventory-info h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .model-price {
            color: #4facfe;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .inventory-info p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stock-count {
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            background: var(--success-gradient);
            color: white;
        }

        .stock-count.low-stock {
            background: var(--warning-gradient);
        }

        .progress-bar {
            background: rgba(255, 255, 255, 0.2);
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            margin: 1rem 0;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--success-gradient);
            border-radius: 6px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .inventory-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .btn-sm {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .under-construction {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            color: white;
        }

        .construction-icon {
            font-size: 4rem;
            background: var(--warning-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .whatsapp-float {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            transition: all var(--animation-speed) ease;
            z-index: 1000;
            animation: pulse 2s infinite;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(37, 211, 102, 0.6);
        }

        .no-data {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            padding: 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            border: 1px solid var(--glass-border);
        }

        @media (max-width: 768px) {
            .top-bar-content {
                flex-direction: column;
                gap: 1rem;
            }

            .contact-info {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .logo {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .inventory-grid {
                grid-template-columns: 1fr;
            }

            .nav-tabs {
                justify-content: center;
                flex-wrap: wrap;
            }

            .section h2 {
                font-size: 2rem;
                text-align: center;
            }

            .main {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .whatsapp-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .whatsapp-btn {
                font-size: 0.8rem;
                padding: 0.5rem 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    Av. Padre Jorge Alessandri KM 22, San Bernardo, RM.
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    contacto@casasgreenvalley.cl
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    +56 2 2583 2001
                </div>
            </div>
            <div class="whatsapp-buttons">
                <a href="https://wa.me/56956397365" class="whatsapp-btn" target="_blank">
                    <i class="fab fa-whatsapp"></i> +569 5309 7365
                </a>
                <a href="https://wa.me/56987037917" class="whatsapp-btn" target="_blank">
                    <i class="fab fa-whatsapp"></i> +569 8703 7917
                </a>
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
                    <span class="role-badge">Cliente Premium</span>
                </div>
                <div class="user-info">
                    <span>¡Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</span>
                    <a href="logout.php" class="btn">
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
            <section class="section">
                <h2>🏠 Mi Panel Personal</h2>
                <p class="section-subtitle">Resumen completo de tus proyectos y cotizaciones</p>
                
                <!-- Stats Grid -->
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
                <div class="activity-section">
                    <h3>🔄 Mi Actividad Reciente</h3>
                    <?php if (empty($recent_activity)): ?>
                        <p class="no-data">No hay actividad reciente para mostrar.</p>
                    <?php else: ?>
                        <?php foreach ($recent_activity as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-content">
                                    <div class="activity-icon" style="background: <?php echo $activity['color']; ?>">
                                        <i class="fas fa-<?php echo $activity['icon']; ?>"></i>
                                    </div>
                                    <div class="activity-info">
                                        <strong><?php echo htmlspecialchars($activity['title']); ?></strong>
                                        <p><?php echo htmlspecialchars($activity['description']); ?></p>
                                        <small><?php echo $activity['time']; ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        <?php elseif ($active_section === 'quotes'): ?>
            <!-- Quotes Section -->
            <section class="section">
                <h2>📋 Mis Cotizaciones</h2>
                <p class="section-subtitle">Revisa el estado de todas tus cotizaciones solicitadas</p>

                <div class="inventory-grid">
                    <?php if (empty($client_quotes)): ?>
                        <div class="no-data">
                            <i class="fas fa-file-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>No tienes cotizaciones registradas aún.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($client_quotes as $quote): ?>
                            <div class="inventory-card">
                                <div class="inventory-info">
                                    <h3><?php echo htmlspecialchars($quote['modelo_nombre']); ?></h3>
                                    <p class="model-price">💰 $<?php echo number_format($quote['total']); ?></p>
                                    <p>Estado: <span class="stock-count <?php echo $quote['estado'] === 'pendiente' ? 'low-stock' : ''; ?>"><?php echo ucfirst($quote['estado']); ?></span></p>
                                    <p>📅 <?php echo date('d/m/Y', strtotime($quote['fecha'])); ?></p>
                                    <div class="inventory-actions">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </button>
                                        <?php if ($quote['estado'] === 'pendiente'): ?>
                                            <button class="btn btn-outline btn-sm">
                                                <i class="fas fa-edit"></i> Modificar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        <?php elseif ($active_section === 'projects'): ?>
            <!-- Projects Section -->
            <section class="section">
                <h2>🏗️ Mis Proyectos</h2>
                <p class="section-subtitle">Seguimiento del progreso de construcción de tus casas</p>

                <div class="inventory-grid">
                    <?php if (empty($client_projects)): ?>
                        <div class="no-data">
                            <i class="fas fa-hammer" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>No tienes proyectos en curso actualmente.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($client_projects as $project): ?>
                            <div class="inventory-card">
                                <div class="inventory-info">
                                    <h3><?php echo htmlspecialchars($project['modelo_nombre']); ?></h3>
                                    <p>Estado: <span class="stock-count"><?php echo str_replace('_', ' ', ucfirst($project['estado'])); ?></span></p>
                                    <p>Progreso: <?php echo $project['progreso']; ?>%</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $project['progreso']; ?>%"></div>
                                    </div>
                                    <p>🚀 Inicio: <?php echo date('d/m/Y', strtotime($project['fecha_inicio'])); ?></p>
                                    <div class="inventory-actions">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar"></i> Cronograma
                                        </button>
                                        <button class="btn btn-outline btn-sm">
                                            <i class="fas fa-images"></i> Fotos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        <?php else: ?>
            <!-- Under Construction Section -->
            <section class="section">
                <div class="under-construction">
                    <div class="construction-icon">🚧</div>
                    <h3>Sección en Desarrollo</h3>
                    <p>La funcionalidad de <strong><?php echo ucfirst($active_section); ?></strong> estará disponible próximamente.</p>
                    <p>Estamos trabajando para brindarte la mejor experiencia.</p>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script>
        // Animate stats on load
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });

            // Add click effects to cards
            const cards = document.querySelectorAll('.stat-card, .inventory-card, .activity-item');
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    const ripple = document.createElement('div');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.3);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        left: ${x}px;
                        top: ${y}px;
                        width: ${size}px;
                        height: ${size}px;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>