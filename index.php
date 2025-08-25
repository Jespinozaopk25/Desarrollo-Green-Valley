<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page == 'index.php' && isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];
    switch ($user_role) {
        case 'admin':
            header('Location: admin-dashboard.php');
            exit();
        case 'employee':
            header('Location: vendedor-dashboard.php');
            exit();
        case 'client':
            header('Location: client-dashboard.php');
            exit();
        default:
            header('Location: login.php');
            exit();
    }
} elseif (isset($_SESSION['usuario']) && isset($_SESSION['id_role'])) {
    // Fallback al sistema anterior - mapear a nuevo sistema y redirigir
    $id_role = $_SESSION['id_role'];
    switch($id_role) {
        case 1: // Dashboard -> admin
        case 3: // Superadmin -> admin
            $_SESSION['user_role'] = 'administrador'; // Establecer el nuevo rol para futuras verificaciones
            header('Location: admin-dashboard.php');
            exit();
        case 2: // Usuario -> client
            $_SESSION['user_role'] = 'client'; // Establecer el nuevo rol para futuras verificaciones
            header('Location: client-dashboard.php');
            exit();
        case 5: // Admin ranking -> employee
            $_SESSION['user_role'] = 'employee'; // Establecer el nuevo rol para futuras verificaciones
            header('Location: vendedor-dashboard.php');
            exit();
        default:
            header('Location: login.php');
            exit();
    }
}
// Si no hay sesión activa, el script continúa para mostrar la página de inicio normal.
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Valley - Casas Prefabricadas</title>
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
            background: #25d366;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            transition: background 0.3s;
        }

        .whatsapp-btn:hover {
            background: #128c7e;
        }

        /* Header Styles */
        header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .logo-image {
            height: 50px;
            width: auto;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #7cb342;
        }

        .logout-btn {
            background: #e74c3c;
            color: white !important;
            padding: 8px 15px;
            border-radius: 20px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .user-greeting {
            color: #7cb342;
            font-weight: 600;
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Hero Section - IMPROVED */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        /* Fondo con imágenes */
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .hero-background img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hero-background img.active {
            opacity: 1;
        }

        /* Overlay para mejorar legibilidad del texto */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                    rgba(0, 0, 0, 0.6) 0%,
                    rgba(0, 0, 0, 0.3) 50%,
                    rgba(0, 0, 0, 0.5) 100%);
            z-index: 2;
        }

        .hero .container {
            position: relative;
            z-index: 3;
            width: 100%;
        }

        .hero-content {
            max-width: 600px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            padding: 20px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #7cb342, #8bc34a);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);
            border: none;
            cursor: pointer;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 179, 66, 0.4);
            background: linear-gradient(45deg, #8bc34a, #9ccc65);
        }

        /* Navegación de imágenes */
        .hero-nav {
            position: absolute;
            bottom: 30px;
            right: 30px;
            z-index: 4;
            display: flex;
            gap: 10px;
        }

        .nav-arrow {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-arrow:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        /* Indicadores de imagen */
        .hero-indicators {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 4;
            display: flex;
            gap: 10px;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: white;
            transform: scale(1.2);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .feature-card p {
            color: #7f8c8d;
            line-height: 1.6;
        }

        /* Catalog Section */
        .catalog {
            padding: 80px 0;
            background: white;
        }

        .houses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .house-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .house-card:hover {
            transform: translateY(-10px);
        }

        .house-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .house-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .house-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #7cb342;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .house-info {
            padding: 25px;
        }

        .house-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .house-price {
            font-size: 1.8rem;
            font-weight: bold;
            color: #7cb342;
            margin-bottom: 15px;
        }

        .house-details {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .house-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #7cb342;
            color: white;
        }

        .btn-primary:hover {
            background: #689f38;
        }

        .btn-secondary {
            background: transparent;
            color: #7cb342;
            border: 2px solid #7cb342;
        }

        .btn-secondary:hover {
            background: #7cb342;
            color: white;
        }

        /* Quote Section */
        .quote-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #7cb342;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            color: #7cb342;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: #7cb342;
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #34495e;
            color: #bdc3c7;
        }

        /* WhatsApp Float Button */
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .hero-nav {
                bottom: 20px;
                right: 20px;
            }

            .nav-arrow {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .top-bar-content {
                flex-direction: column;
                gap: 10px;
            }

            .contact-info {
                justify-content: center;
            }

            nav ul {
                flex-direction: column;
                gap: 10px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .house-actions {
                flex-direction: column;
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
                    <div class="contact-item">
                        <span>📍</span>
                        <span>Av. Padre Jorge Alessandri KM 22, San Bernardo, RM.</span>
                    </div>
                    <div class="contact-item">
                        <span>📧</span>
                        <span>contacto@casasgreenvalley.cl</span>
                    </div>
                    <div class="contact-item">
                        <span>📞</span>
                        <span>Tel.: +56 2 2583 2001</span>
                    </div>
                </div>
                <div class="whatsapp-buttons">
                    <a href="https://wa.me/56956397365" class="whatsapp-btn" target="_blank">
                        <span>💬</span>
                        <span>+569 5309 7365</span>
                    </a>
                    <a href="https://wa.me/56987037917" class="whatsapp-btn" target="_blank">
                        <span>💬</span>
                        <span>+569 8703 7917</span>
                    </a>
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
                    <li><a href="#catalog">Casas prefabricadas</a></li>
                    <li><a href="#llave">Llave en mano</a></li>
                    <li><a href="#proyectos">Proyectos</a></li>
                    <?php if (isset($_SESSION['user_id'])): // Usar user_id para verificar si hay sesión activa ?>
                        <li>
                            <?php
                            $dashboard_link = 'login.php'; // Enlace por defecto si el rol no está definido
                            if (isset($_SESSION['user_role'])) {
                                switch ($_SESSION['user_role']) {
                                    case 'admin':
                                        $dashboard_link = 'admin-dashboard.php';
                                        break;
                                    case 'employee':
                                        $dashboard_link = 'employee-dashboard.php';
                                        break;
                                    case 'client':
                                        $dashboard_link = 'client-dashboard.php';
                                        break;
                                }
                            }
                            ?>
                            <a href="<?php echo $dashboard_link; ?>">Dashboard</a>
                        </li>
                        <li><a href="logout.php" class="logout-btn">Cerrar Sesión</a></li>
                        <li class="user-greeting">Hola,
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['nombre']); ?>!
                        </li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <a href="carrito.php" class="cart-icon">
                🛒
                <span class="cart-badge">0</span>
            </a>

        </div>
    </header>

    <!-- Hero Section - MEJORADO -->
    <section class="hero">
        <!-- Fondo con imágenes -->
        <div class="hero-background">
            <img src="imagendeinicio.jpg" alt="Casa Prefabricada 1" class="active">
            <img src="imagendeinicio_2.jpg" alt="Casa Prefabricada 2">
            <img src="imagendeinicio_3.jpg" alt="Casa Prefabricada 3">
        </div>

        <!-- Overlay para mejorar legibilidad -->
        <div class="hero-overlay"></div>

        <!-- Contenido principal -->
        <div class="container">
            <div class="hero-content">
                <h1>Transformamos tus sueños en un hogar a tu medida con nuestras casas prefabricadas</h1>
                <p>Desde Tiny Houses hasta casas prefabricadas de lujo. Explora nuestras opciones y descubre la que más
                    se adecúa a tus necesidades.</p>
                <a href="#quote" class="cta-button">Agenda tu asesoría →</a>
            </div>
        </div>

        <!-- Indicadores de imagen -->
        <div class="hero-indicators">
            <div class="indicator active" data-slide="0"></div>
            <div class="indicator" data-slide="1"></div>
            <div class="indicator" data-slide="2"></div>
        </div>

        <!-- Navegación -->
        <div class="hero-nav">
            <div class="nav-arrow" id="prevBtn">‹</div>
            <div class="nav-arrow" id="nextBtn">›</div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">¿Por qué elegir Green Valley?</h2>
            <p class="section-subtitle">Somos especialistas en construcción de casas prefabricadas con más de 10 años de
                experiencia en el mercado</p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Construcción Rápida</h3>
                    <p>Nuestras casas se construyen en tiempo récord sin comprometer la calidad. Desde el diseño hasta
                        la entrega en tiempo mínimo.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏗️</div>
                    <h3>Calidad Garantizada</h3>
                    <p>Utilizamos materiales de primera calidad y técnicas de construcción avanzadas para garantizar la
                        durabilidad de tu hogar.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3>Diseño Personalizado</h3>
                    <p>Cada casa es única. Trabajamos contigo para crear el diseño perfecto que se adapte a tus
                        necesidades y gustos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Precios Competitivos</h3>
                    <p>Ofrecemos las mejores opciones de financiamiento y precios justos para hacer realidad tu sueño de
                        tener casa propia.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌱</div>
                    <h3>Eco-Friendly</h3>
                    <p>Nuestras casas son amigables con el medio ambiente, utilizando materiales sostenibles y
                        tecnologías eficientes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Servicio Integral</h3>
                    <p>Te acompañamos en todo el proceso, desde el diseño inicial hasta la entrega final de tu casa
                        completamente terminada.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section id="catalog" class="catalog">
        <div class="container">
            <h2 class="section-title">Nuestras Casas Prefabricadas</h2>
            <p class="section-subtitle">Descubre nuestra amplia gama de modelos, desde casas compactas hasta residencias
                de lujo</p>
            <div class="houses-grid">
                <!-- Casa 1 -->
                <div class="house-card">
                    <div class="house-image">
                        <img src="casa1.jpg" alt="Casa Prefabricada 21 m2">
                        <span class="house-badge">Más Popular</span>
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 21 m2</h3>
                        <div class="house-price">Desde $1.940.000</div>
                        <div class="house-details">
                            <span>🛏️ 1 Habitacion</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 21 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php" class="btn btn-secondary">Ver producto</a>
                        </div>
                    </div>
                </div>
                <!-- Casa 2 -->
                <div class="house-card">
                    <div class="house-image">
                        <img src="/placeholder.svg?height=250&width=400" alt="Casa Modelo Beta">
                        <span class="house-badge">Recomendado</span>
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Modelo Beta</h3>
                        <div class="house-price">Desde $65.000.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Habitaciones</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 90 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="#" class="btn btn-secondary">Ver producto</a>
                        </div>
                    </div>
                </div>
                <!-- Casa 3 -->
                <div class="house-card">
                    <div class="house-image">
                        <img src="/placeholder.svg?height=250&width=400" alt="Casa Modelo Gamma">
                        <span class="house-badge">Premium</span>
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Modelo Gamma</h3>
                        <div class="house-price">Desde $85.000.000</div>
                        <div class="house-details">
                            <span>🛏️ 4 Habitaciones</span>
                            <span>🚿 3 Baños</span>
                            <span>📐 120 m²</span>
                        </div>
                        <div class="house-actions">

                            <a href="#" class="btn btn-secondary">Ver producto</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Sección de Contacto tipo footer -->
    <section style="background:#204c3a; color:#fff; padding:50px 0;">
      <div class="container" style="display:flex; flex-wrap:wrap; gap:40px; align-items:flex-start;">
        <div style="flex:1; min-width:300px;">
          <h3 style="letter-spacing:2px; color:#b6e2b3;">CONTACTO</h3>
          <h2 style="margin:20px 0 10px;">¿Quieres saber más sobre cómo construir la casa perfecta?</h2>
          <p style="margin-bottom:30px;">Contáctanos y te ayudaremos en cada etapa</p>
          <div style="margin-bottom:20px;">
            <strong>Lunes – Viernes</strong><br>10:00 AM – 6:00 PM
          </div>
          <div>
            <strong>Sábado</strong><br>10:00 AM – 5:00 PM
          </div>
        </div>
        <form style="flex:1; min-width:300px; display:flex; flex-direction:column; gap:15px;">
          <input type="text" placeholder="Tu nombre" style="padding:10px; border-radius:5px; border:none;">
          <input type="email" placeholder="Tu correo" style="padding:10px; border-radius:5px; border:none;">
          <input type="text" placeholder="Tu teléfono" style="padding:10px; border-radius:5px; border:none;">
          <input type="text" placeholder="Asunto" style="padding:10px; border-radius:5px; border:none;">
          <textarea placeholder="Mensaje" rows="4" style="padding:10px; border-radius:5px; border:none;"></textarea>
          <button type="submit" style="background:#b6e2b3; color:#204c3a; padding:12px; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">Enviar mensaje</button>
        </form>
      </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Green Valley Estructuras</h3>
                    <p style="color: #bdc3c7; margin-bottom: 20px;">
                        Especialistas en casas prefabricadas de alta calidad. Transformamos tus sueños en realidad con
                        diseños únicos y construcción eficiente.
                    </p>
                    <div style="color: #bdc3c7;">
                        <p>📍 Av. Padre Jorge Alessandri KM 22</p>
                        <p>San Bernardo, Región Metropolitana</p>
                        <p>📞 +56 2 2583 2001</p>
                        <p>📧 contacto@casasgreenvalley.cl</p>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Nuestros Servicios</h3>
                    <ul>
                        <li><a href="#catalog">Casas Prefabricadas</a></li>
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
                    <div style="margin-top: 20px;">
                        <h4 style="color: #7cb342; margin-bottom: 10px;">WhatsApp</h4>
                        <a href="https://wa.me/56956397365" style="color: #25d366; text-decoration: none;">+569 5309
                            7365</a><br>
                        <a href="https://wa.me/56987037917" style="color: #25d366; text-decoration: none;">+569 8703
                            7917</a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Green Valley Estructuras. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank">
        💬
    </a>

    <script>
        // Funcionalidad del carrusel de imágenes en el hero
        let currentSlide = 0;
        const images = document.querySelectorAll('.hero-background img');
        const indicators = document.querySelectorAll('.indicator');
        const totalSlides = images.length;

        function showSlide(index) {
            // Ocultar todas las imágenes
            images.forEach(img => img.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Mostrar la imagen actual
            images[index].classList.add('active');
            indicators[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        // Event listeners para navegación
        document.getElementById('nextBtn').addEventListener('click', nextSlide);
        document.getElementById('prevBtn').addEventListener('click', prevSlide);

        // Indicadores clickeables
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        // Auto-play (cambia cada 5 segundos)
        setInterval(nextSlide, 5000);

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form submission
        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('¡Gracias por tu consulta! Nos pondremos en contacto contigo pronto.');
        });
    </script>
</body>

</html>
