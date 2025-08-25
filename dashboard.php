<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Green Valley Estructuras</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #7cb342;
            --primary-dark: #689f38;
            --secondary-color: #2c3e50;
            --secondary-light: #34495e;
            --accent-color: #e8f5e8;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --border-color: #e9ecef;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        /* Reset y estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--accent-color) 0%, #f0f8f0 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: var(--white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .header .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-dark);
            text-decoration: none;
        }

        .logo i {
            font-size: 2rem;
            color: var(--primary-color);
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo h1 {
            background: linear-gradient(45deg, var(--text-dark), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav {
            display: flex;
            gap: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            text-decoration: none;
            color: var(--text-light);
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            transition: var(--transition);
            z-index: -1;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            left: 0;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* Main Content */
        .main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .section {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: var(--text-dark);
            position: relative;
            display: inline-block;
        }

        .section h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border-radius: 2px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.8rem;
            box-shadow: var(--shadow);
        }

        .stat-info h3 {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--text-light);
            font-weight: 500;
        }

        /* Form Styles */
        .quote-form {
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--light-gray);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.1);
            transform: translateY(-2px);
        }

        /* Extras Section */
        .extras-section {
            background: var(--light-gray);
            padding: 30px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
        }

        .extras-section h3 {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .extras-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .extra-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: var(--white);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .extra-item:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .extra-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--primary-color);
        }

        /* Quote Summary */
        .quote-summary {
            background: var(--white);
            padding: 25px;
            border-radius: var(--border-radius);
            border: 2px solid var(--primary-color);
            margin-bottom: 30px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item.total {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-color);
            border-top: 2px solid var(--primary-color);
            margin-top: 15px;
            padding-top: 15px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--border-color);
            transform: translateY(-2px);
        }

        /* Recent Activity */
        .recent-activity {
            background: var(--white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .recent-activity h3 {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-size: 1.4rem;
        }

        .activity-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .activity-item:hover {
            background: var(--light-gray);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 0.9rem;
        }

        /* Controls */
        .inventory-controls,
        .history-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            min-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            background: var(--white);
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.1);
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        /* Tables */
        .quotes-table {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        tr:hover {
            background: var(--light-gray);
        }

        /* Status badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }

        .status-aprobada {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .status-rechazada {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: var(--white);
            margin: 5% auto;
            padding: 0;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 600px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.3rem;
        }

        .close {
            font-size: 1.5rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .close:hover {
            transform: scale(1.1);
        }

        .modal form {
            padding: 30px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        /* Inventory Grid */
        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .inventory-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .inventory-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        }

        .inventory-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .inventory-card h4 {
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .inventory-card .price {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .inventory-card .stock {
            display: inline-block;
            padding: 4px 12px;
            background: var(--light-gray);
            border-radius: 15px;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .inventory-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header .container {
                flex-direction: column;
                height: auto;
                padding: 15px 20px;
                gap: 15px;
            }

            .nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            .main {
                padding: 20px 15px;
            }

            .section h2 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .extras-grid {
                grid-template-columns: 1fr;
            }

            .inventory-controls,
            .history-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }

            .quotes-table {
                overflow-x: auto;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Animaciones adicionales */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        .slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Loading states */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-light);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <i class="fas fa-home"></i>
                <h1>Green Valley</h1>
            </div>
            
            <nav class="nav">
                <a href="#dashboard" class="nav-link active" data-section="dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="#cotizaciones" class="nav-link" data-section="cotizaciones">
                    <i class="fas fa-calculator"></i> Cotizaciones
                </a>
                <a href="#inventario" class="nav-link" data-section="inventario">
                    <i class="fas fa-boxes"></i> Inventario
                </a>
                <a href="#historial" class="nav-link" data-section="historial">
                    <i class="fas fa-history"></i> Historial
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <!-- Dashboard Section -->
        <section id="dashboard" class="section active">
            <h2>Dashboard</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="total-quotes">0</h3>
                        <p>Cotizaciones Totales</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="total-houses">12</h3>
                        <p>Modelos Disponibles</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="total-revenue">$0</h3>
                        <p>Valor Total Cotizado</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="pending-quotes">0</h3>
                        <p>Cotizaciones Pendientes</p>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <h3>Actividad Reciente</h3>
                <div class="activity-list" id="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>
                            <strong>Nueva cotización creada</strong>
                            <p>Cliente: Juan Pérez - Modelo Eco Basic</p>
                            <small>Hace 2 horas</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <strong>Cotización aprobada</strong>
                            <p>Cliente: María González - Modelo Family Deluxe</p>
                            <small>Hace 5 horas</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cotizaciones Section -->
        <section id="cotizaciones" class="section">
            <h2>Nueva Cotización</h2>
            <form class="quote-form" id="quote-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="client-name">Nombre del Cliente</label>
                        <input type="text" id="client-name" required>
                    </div>
                    <div class="form-group">
                        <label for="client-email">Email</label>
                        <input type="email" id="client-email" required>
                    </div>
                    <div class="form-group">
                        <label for="client-phone">Teléfono</label>
                        <input type="tel" id="client-phone" required>
                    </div>
                    <div class="form-group">
                        <label for="house-model">Modelo de Casa</label>
                        <select id="house-model" required>
                            <option value="">Seleccionar modelo</option>
                            <option value="eco-basic" data-price="45000">Eco Basic - $45,000</option>
                            <option value="eco-comfort" data-price="65000">Eco Comfort - $65,000</option>
                            <option value="eco-premium" data-price="85000">Eco Premium - $85,000</option>
                            <option value="family-standard" data-price="75000">Family Standard - $75,000</option>
                            <option value="family-deluxe" data-price="95000">Family Deluxe - $95,000</option>
                            <option value="luxury-villa" data-price="120000">Luxury Villa - $120,000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="house-size">Tamaño (m²)</label>
                        <input type="number" id="house-size" min="50" max="300" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Ubicación</label>
                        <input type="text" id="location" required>
                    </div>
                </div>

                <div class="extras-section">
                    <h3>Extras Opcionales</h3>
                    <div class="extras-grid">
                        <label class="extra-item">
                            <input type="checkbox" value="5000" data-name="Paneles Solares">
                            <span>Paneles Solares (+$5,000)</span>
                        </label>
                        <label class="extra-item">
                            <input type="checkbox" value="3000" data-name="Sistema de Seguridad">
                            <span>Sistema de Seguridad (+$3,000)</span>
                        </label>
                        <label class="extra-item">
                            <input type="checkbox" value="2500" data-name="Jardín Paisajístico">
                            <span>Jardín Paisajístico (+$2,500)</span>
                        </label>
                        <label class="extra-item">
                            <input type="checkbox" value="4000" data-name="Garaje Adicional">
                            <span>Garaje Adicional (+$4,000)</span>
                        </label>
                        <label class="extra-item">
                            <input type="checkbox" value="1500" data-name="Terraza Extendida">
                            <span>Terraza Extendida (+$1,500)</span>
                        </label>
                        <label class="extra-item">
                            <input type="checkbox" value="2000" data-name="Chimenea">
                            <span>Chimenea (+$2,000)</span>
                        </label>
                    </div>
                </div>

                <div class="quote-summary">
                    <div class="summary-item">
                        <span>Precio Base:</span>
                        <span id="base-price">$0</span>
                    </div>
                    <div class="summary-item">
                        <span>Extras:</span>
                        <span id="extras-price">$0</span>
                    </div>
                    <div class="summary-item total">
                        <span>Total:</span>
                        <span id="total-price">$0</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calculator"></i> Generar Cotización
                </button>
            </form>
        </section>

        <!-- Inventario Section -->
        <section id="inventario" class="section">
            <h2>Gestión de Inventario</h2>
            <div class="inventory-controls">
                <button class="btn btn-primary" id="add-model-btn">
                    <i class="fas fa-plus"></i> Agregar Modelo
                </button>
                <div class="search-box">
                    <input type="text" id="search-inventory" placeholder="Buscar modelos...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="inventory-grid" id="inventory-grid">
                <div class="inventory-card">
                    <h4>Eco Basic</h4>
                    <div class="price">$45,000</div>
                    <div class="stock">Stock: 8 unidades</div>
                    <p>Casa ecológica básica con acabados estándar y eficiencia energética.</p>
                    <div class="inventory-actions">
                        <button class="btn btn-secondary btn-small">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-primary btn-small">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </div>
                </div>
                <div class="inventory-card">
                    <h4>Family Deluxe</h4>
                    <div class="price">$95,000</div>
                    <div class="stock">Stock: 5 unidades</div>
                    <p>Casa familiar de lujo con acabados premium y espacios amplios.</p>
                    <div class="inventory-actions">
                        <button class="btn btn-secondary btn-small">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-primary btn-small">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Historial Section -->
        <section id="historial" class="section">
            <h2>Historial de Cotizaciones</h2>
            <div class="history-controls">
                <div class="filter-group">
                    <select id="status-filter">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>
                <div class="search-box">
                    <input type="text" id="search-history" placeholder="Buscar por cliente...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="quotes-table">
                <table id="quotes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Modelo</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="quotes-tbody">
                        <tr>
                            <td>#001</td>
                            <td>Juan Pérez</td>
                            <td>Eco Basic</td>
                            <td>$48,000</td>
                            <td>2024-01-15</td>
                            <td><span class="status-badge status-pendiente">Pendiente</span></td>
                            <td>
                                <button class="btn btn-secondary btn-small">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-small">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>#002</td>
                            <td>María González</td>
                            <td>Family Deluxe</td>
                            <td>$102,000</td>
                            <td>2024-01-14</td>
                            <td><span class="status-badge status-aprobada">Aprobada</span></td>
                            <td>
                                <button class="btn btn-secondary btn-small">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-small">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal para agregar/editar modelo -->
    <div id="model-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Agregar Modelo</h3>
                <span class="close">&times;</span>
            </div>
            <form id="model-form">
                <div class="form-group">
                    <label for="model-name">Nombre del Modelo</label>
                    <input type="text" id="model-name" required>
                </div>
                <div class="form-group">
                    <label for="model-price">Precio Base</label>
                    <input type="number" id="model-price" required>
                </div>
                <div class="form-group">
                    <label for="model-description">Descripción</label>
                    <textarea id="model-description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="model-stock">Stock Disponible</label>
                    <input type="number" id="model-stock" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" id="cancel-model">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Variables globales
        let quotes = JSON.parse(localStorage.getItem('quotes')) || [];
        let models = JSON.parse(localStorage.getItem('models')) || [
            { id: 1, name: 'Eco Basic', price: 45000, stock: 8, description: 'Casa ecológica básica' },
            { id: 2, name: 'Family Deluxe', price: 95000, stock: 5, description: 'Casa familiar de lujo' }
        ];

        // Navegación
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetSection = e.target.closest('.nav-link').dataset.section;
                showSection(targetSection);

                // Actualizar navegación activa
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                e.target.closest('.nav-link').classList.add('active');
            });
        });

        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');

            // Actualizar datos específicos de cada sección
            if (sectionId === 'dashboard') {
                updateDashboard();
            } else if (sectionId === 'inventario') {
                renderInventory();
            } else if (sectionId === 'historial') {
                renderQuotesHistory();
            }
        }

        // Dashboard
        function updateDashboard() {
            document.getElementById('total-quotes').textContent = quotes.length;
            document.getElementById('total-houses').textContent = models.length;

            const totalRevenue = quotes.reduce((sum, quote) => sum + (quote.total || 0), 0);
            document.getElementById('total-revenue').textContent = `$${totalRevenue.toLocaleString()}`;

            const pendingQuotes = quotes.filter(q => q.status === 'pendiente').length;
            document.getElementById('pending-quotes').textContent = pendingQuotes;
        }

        // Cotizaciones
        const quoteForm = document.getElementById('quote-form');
        const houseModelSelect = document.getElementById('house-model');
        const basePriceSpan = document.getElementById('base-price');
        const extrasPriceSpan = document.getElementById('extras-price');
        const totalPriceSpan = document.getElementById('total-price');

        houseModelSelect.addEventListener('change', updateQuotePrice);
        document.querySelectorAll('.extra-item input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateQuotePrice);
        });

        function updateQuotePrice() {
            const selectedOption = houseModelSelect.selectedOptions[0];
            const basePrice = selectedOption ? parseInt(selectedOption.dataset.price) || 0 : 0;

            let extrasPrice = 0;
            document.querySelectorAll('.extra-item input[type="checkbox"]:checked').forEach(checkbox => {
                extrasPrice += parseInt(checkbox.value) || 0;
            });

            const total = basePrice + extrasPrice;

            basePriceSpan.textContent = `$${basePrice.toLocaleString()}`;
            extrasPriceSpan.textContent = `$${extrasPrice.toLocaleString()}`;
            totalPriceSpan.textContent = `$${total.toLocaleString()}`;
        }

        quoteForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(quoteForm);
            const selectedExtras = [];
            document.querySelectorAll('.extra-item input[type="checkbox"]:checked').forEach(checkbox => {
                selectedExtras.push({
                    name: checkbox.dataset.name,
                    price: parseInt(checkbox.value)
                });
            });

            const quote = {
                id: quotes.length + 1,
                clientName: document.getElementById('client-name').value,
                clientEmail: document.getElementById('client-email').value,
                clientPhone: document.getElementById('client-phone').value,
                houseModel: houseModelSelect.selectedOptions[0].text,
                houseSize: document.getElementById('house-size').value,
                location: document.getElementById('location').value,
                extras: selectedExtras,
                basePrice: parseInt(houseModelSelect.selectedOptions[0].dataset.price) || 0,
                extrasPrice: selectedExtras.reduce((sum, extra) => sum + extra.price, 0),
                total: parseInt(totalPriceSpan.textContent.replace(/[$,]/g, '')),
                date: new Date().toISOString().split('T')[0],
                status: 'pendiente'
            };

            quotes.push(quote);
            localStorage.setItem('quotes', JSON.stringify(quotes));

            // Mostrar mensaje de éxito
            alert('Cotización generada exitosamente!');
            quoteForm.reset();
            updateQuotePrice();

            // Agregar a actividad reciente
            addRecentActivity(`Nueva cotización creada para ${quote.clientName}`, 'plus');
        });

        // Inventario
        function renderInventory() {
            const inventoryGrid = document.getElementById('inventory-grid');
            inventoryGrid.innerHTML = '';

            models.forEach(model => {
                const card = document.createElement('div');
                card.className = 'inventory-card';
                card.innerHTML = `
                    <h4>${model.name}</h4>
                    <div class="price">$${model.price.toLocaleString()}</div>
                    <div class="stock">Stock: ${model.stock} unidades</div>
                    <p>${model.description}</p>
                    <div class="inventory-actions">
                        <button class="btn btn-secondary btn-small" onclick="editModel(${model.id})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-primary btn-small">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </div>
                `;
                inventoryGrid.appendChild(card);
            });
        }

        // Modal
        const modal = document.getElementById('model-modal');
        const addModelBtn = document.getElementById('add-model-btn');
        const closeModal = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancel-model');

        addModelBtn.addEventListener('click', () => {
            document.getElementById('modal-title').textContent = 'Agregar Modelo';
            document.getElementById('model-form').reset();
            modal.style.display = 'block';
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Historial
        function renderQuotesHistory() {
            const tbody = document.getElementById('quotes-tbody');
            tbody.innerHTML = '';

            quotes.forEach(quote => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>#${quote.id.toString().padStart(3, '0')}</td>
                    <td>${quote.clientName}</td>
                    <td>${quote.houseModel}</td>
                    <td>$${quote.total.toLocaleString()}</td>
                    <td>${quote.date}</td>
                    <td><span class="status-badge status-${quote.status}">${quote.status}</span></td>
                    <td>
                        <button class="btn btn-secondary btn-small">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-primary btn-small">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Actividad reciente
        function addRecentActivity(message, icon) {
            const activityList = document.getElementById('activity-list');
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item';
            activityItem.innerHTML = `
                <div class="activity-icon">
                    <i class="fas fa-${icon}"></i>
                </div>
                <div>
                    <strong>${message}</strong>
                    <small>Ahora</small>
                </div>
            `;

            if (activityList.querySelector('.no-activity')) {
                activityList.innerHTML = '';
            }

            activityList.insertBefore(activityItem, activityList.firstChild);
        }

        // Búsqueda
        document.getElementById('search-inventory').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.inventory-card').forEach(card => {
                const modelName = card.querySelector('h4').textContent.toLowerCase();
                card.style.display = modelName.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            updateDashboard();
            renderInventory();
            renderQuotesHistory();
        });
    </script>
</body>

</html>