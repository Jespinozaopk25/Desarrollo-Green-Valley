<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Valley - Casas Prefabricadas</title>
    <style>
        :root {
            --primary-color: #7cb342;
            --primary-dark: #689f38;
            --secondary-color: #2c3e50;
            --accent-color: #25d366;
            --text-light: #7f8c8d;
            --background-light: #f8f9fa;
            --white: #ffffff;
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
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Enhanced Header */
        .top-bar {
            background: var(--secondary-color);
            color: white;
            padding: 8px 0;
            font-size: 13px;
            position: relative;
            overflow: hidden;
        }

        .top-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
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
            transition: var(--transition);
        }

        .contact-item:hover {
            transform: translateY(-1px);
        }

        .whatsapp-buttons {
            display: flex;
            gap: 8px;
        }

        .whatsapp-btn {
            background: var(--accent-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 11px;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-btn:hover {
            background: #128c7e;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        }

        /* Enhanced Header */
        header {
            background: var(--white);
            box-shadow: 0 4px 20px var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
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
            transition: var(--transition);
        }

        .logo-image:hover {
            transform: scale(1.05);
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            position: relative;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 25px;
        }

        nav a:hover {
            color: var(--primary-color);
            background: rgba(124, 179, 66, 0.1);
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: var(--transition);
        }

        nav a:hover::after {
            width: 60%;
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            transition: var(--transition);
            background: rgba(124, 179, 66, 0.1);
        }

        .cart-icon:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Modern Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #0c5217ff 0%, #02b614ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 226, 0.3) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .hero-text {
            color: white;
        }

        .hero-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .hero-image img {
            width: 100%;
            max-width: 500px;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            transform: perspective(1000px) rotateY(-15deg) rotateX(5deg);
            transition: var(--transition);
        }

        .hero-image img:hover {
            transform: perspective(1000px) rotateY(-10deg) rotateX(2deg) scale(1.02);
        }

        /* Enhanced Buttons */
        .btn {
            display: inline-block;
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 8px 25px rgba(124, 179, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(124, 179, 66, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Enhanced Features Section */
        .features {
            padding: 100px 0;
            background: var(--background-light);
            position: relative;
        }

        .features::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: var(--white);
            clip-path: polygon(0 100%, 100% 0, 100% 100%);
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            font-weight: 700;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border-radius: 2px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .feature-card {
            background: var(--white);
            padding: 40px 30px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: 0 10px 30px var(--shadow-light);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            transform: translateX(-100%);
            transition: var(--transition);
        }

        .feature-card:hover::before {
            transform: translateX(0);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px var(--shadow-medium);
        }

        .feature-icon {
            font-size: 3.5rem;
            margin-bottom: 25px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.6;
        }

        /* Enhanced Catalog Section */
        .catalog {
            padding: 100px 0;
            background: var(--white);
        }

        .houses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .house-card {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 10px 30px var(--shadow-light);
            transition: var(--transition);
            position: relative;
        }

        .house-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px var(--shadow-medium);
        }

        .house-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .house-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(124, 179, 66, 0.1), transparent);
            opacity: 0;
            transition: var(--transition);
            z-index: 2;
        }

        .house-card:hover .house-image::before {
            opacity: 1;
        }

        .house-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .house-card:hover .house-image img {
            transform: scale(1.1);
        }

        .house-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: bold;
            z-index: 3;
            box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);
        }

        .house-info {
            padding: 30px;
        }

        .house-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .house-price {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .house-details {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            font-size: 14px;
            color: var(--text-light);
            flex-wrap: wrap;
        }

        .house-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Enhanced Contact Section */
        .contact-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #34495e 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .contact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.03"><polygon points="1000,100 1000,0 0,100"/></svg>');
            background-size: 100% 100px;
            background-repeat: no-repeat;
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: flex-start;
        }

        .contact-info h3 {
            letter-spacing: 2px;
            color: var(--primary-color);
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .contact-info h2 {
            font-size: 2.5rem;
            margin: 20px 0;
            font-weight: 700;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(124, 179, 66, 0.3);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Enhanced Footer */
        footer {
            background: var(--background-light);
            color: var(--secondary-color);
            padding: 60px 0 30px;
            border-top: 4px solid var(--primary-color);
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-logo img {
            width: 50px;
            height: 50px;
            margin-bottom: 20px;
        }

        .footer-column h3 {
            color: var(--secondary-color);
            margin-bottom: 25px;
            font-size: 1.2rem;
            font-weight: 600;
            position: relative;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--primary-color);
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            color: var(--text-light);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
        }

        .footer-column ul li a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }

        /* Enhanced WhatsApp Float */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(45deg, var(--accent-color), #128c7e);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: var(--transition);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(37, 211, 102, 0.5);
            animation: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .contact-content {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
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
                <img src="IMG/logoGreenValley.jpg" alt="Green Valley" class="logo-image">
            </a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="sobrenosotros.php">Nuestra Empresa</a></li>
                    <li><a href="#catalog">Casas prefabricadas</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <a href="carrito.php" class="cart-icon">
                🛒<span class="cart-badge">0</span>
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <p class="hero-subtitle">Tu Hogar, Nuestra Pasión</p>
                    <h1>Encuentra el modelo perfecto</h1>
                    <p>Green Valley ofrece una amplia variedad de casas prefabricadas diseñadas para adaptarse a tus necesidades. Soluciones innovadoras que combinan diseño vanguardista, construcción de alta calidad y eficiencia energética.</p>
                    <a href="#catalog" class="btn btn-primary">Cotiza Ahora</a>
                </div>
                <div class="hero-image">
                    <img src="IMG/imagendeinicio.jpg" alt="Casa prefabricada moderna Green Valley">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">¿Por qué elegir Green Valley?</h2>
            <p class="section-subtitle">Somos especialistas en construcción de casas prefabricadas con más de 10 años de experiencia en el mercado</p>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Construcción Rápida</h3>
                    <p>Nuestras casas se construyen en tiempo récord sin comprometer la calidad. Desde el diseño hasta la entrega en tiempo mínimo.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏗️</div>
                    <h3>Calidad Garantizada</h3>
                    <p>Utilizamos materiales de primera calidad y técnicas de construcción avanzadas para garantizar la durabilidad de tu hogar.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3>Diseño Personalizado</h3>
                    <p>Cada casa es única. Trabajamos contigo para crear el diseño perfecto que se adapte a tus necesidades y gustos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Precios Competitivos</h3>
                    <p>Ofrecemos las mejores opciones de financiamiento y precios justos para hacer realidad tu sueño de tener casa propia.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌱</div>
                    <h3>Eco-Friendly</h3>
                    <p>Nuestras casas son amigables con el medio ambiente, utilizando materiales sostenibles y tecnologías eficientes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Servicio Integral</h3>
                    <p>Te acompañamos en todo el proceso, desde el diseño inicial hasta la entrega final de tu casa completamente terminada.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section id="catalog" class="catalog">
        <div class="container">
            <h2 class="section-title">Nuestras Casas Prefabricadas</h2>
            <p class="section-subtitle">Descubre nuestra amplia gama de modelos, desde casas compactas hasta residencias de lujo</p>
            <div class="houses-grid">
                <!-- Casa 1 -->
                <div class="house-card">
                    <div class="house-image">
                        <img src="IMG/casa1.jpg" alt="Casa Prefabricada 21 m2">
                        <span class="house-badge">Más Popular</span>
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 21 m²</h3>
                        <div class="house-price">Desde $1.940.000</div>
                        <div class="house-details">
                            <span>🛏️ 1 Dormitorio</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 21 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=1" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                <!-- Casa 2 -->
                <div class="house-card">
                    <div class="house-image">
                        <img src="IMG/casa2.jpg" alt="Casa Prefabricada 36 m2">
                        <span class="house-badge">Recomendado</span>
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 36 m²</h3>
                        <div class="house-price">Desde $3.390.000</div>
                        <div class="house-details">
                            <span>🛏️ 2 Dormitorios</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 36 m²</span>
                        </div>
                        <div class="
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=2" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                <!-- Casa 3 -->
                <div class="house-card">
                    <div class="house-image">
                        <img src="IMG/casa3.jpg" alt="Casa Prefabricada 48 m2">
                        <span class="house-badge">Premium</span>
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 48 m2</h3>
                        <div class="house-price">Desde $4.338.000</div>
                        <div class="house-details">
                            <span>🛏️ 2 Dormitorios</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 42 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=3" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                <!-- Casas extras ocultas -->
                    <!-- Casa 4 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa4.jpg" alt="Casa Prefabricada 54 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 54 m2</h3>
                        <div class="house-price">Desde $4.698.000</div>
                        <div class="house-details">
                            <span>🛏️ 2 Dormitorios</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 54 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=4" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 5 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa5.jpg" alt="Casa Prefabricada 66 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 66 m2</h3>
                        <div class="house-price">Desde $5.400.000</div>
                        <div class="house-details">
                            <span>🛏️ 2 Dormitorios</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 60 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=5" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 6 -->

                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa6.jpg" alt="Casa Prefabricada 60 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 60 m2</h3>
                        <div class="house-price">Desde $5.610.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Dormitorios</span>
                            <span>🚿 1 Baño</span>
                            <span>📐 60 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=6" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 7 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa7.jpg" alt="Casa Prefabricada 72 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 72 m2</h3>
                        <div class="house-price">Desde $6.120.000</div>
                        <div class="house-details">
                            <span>🛏️ 2 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 72 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=7" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 8 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa8.jpg" alt="Casa Prefabricada 80 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 80 m2</h3>
                        <div class="house-price">Desde $4.338.000</div>
                        <div class="house-details">
                            <span>🛏️ 3-2 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 80 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=8" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                    <!-- Casa 9 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa9.jpg" alt="Casa Prefabricada 90 m2 Tradicional">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 90 m2 Tradicional</h3>
                        <div class="house-price">Desde $7.650.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 90 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=9" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                    <!-- Casa 10 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa10.jpg" alt="Casa Prefabricada 90 m2 Mediterránea">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 90 m2 Mediterránea</h3>
                        <div class="house-price">Desde $7.650.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 90 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=10" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                    <!-- Casa 11 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa11.jpg" alt="Casa Prefabricada 117 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 117 m2</h3>
                        <div class="house-price">Desde $9.880.000</div>
                        <div class="house-details">
                            <span>🛏️ 4 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 100 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=11" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                    <!-- Casa 12 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa12.jpg" alt="Casa Prefabricada 126 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 126 m2</h3>
                        <div class="house-price">Desde $10.710.000</div>
                        <div class="house-details">
                            <span>🛏️ 4 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 126 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=12" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 13 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa13.jpg" alt="Casa Prefabricada 156 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 156 m2</h3>
                        <div class="house-price">Desde $12.900.000</div>
                        <div class="house-details">
                            <span>🛏️ 4 Dormitorios</span>
                            <span>🚿 3 Baños</span>
                            <span>📐 120 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=13" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 14 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa14.jpg" alt="Casa Prefabricada 130 m2 (2 pisos)">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 130 m2 (2 pisos)</h3>
                        <div class="house-price">Desde $15.568.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 130 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=14" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 15 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa15.jpg" alt="Casa Prefabricada 166 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 166 m2</h3>
                        <div class="house-price">Desde $13.600.000</div>
                        <div class="house-details">
                            <span>🛏️ 4 Dormitorios</span>
                            <span>🚿 2 Baños</span>
                            <span>📐 120 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=15" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 16 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa16.jpg" alt="Casa Prefabricada 190 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 190 m2</h3>
                        <div class="house-price">Desde $15.490.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Dormitorios</span>
                            <span>🚿 3 Baños</span>
                            <span>📐 134 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=16" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

                    <!-- Casa 17 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa17.jpg" alt="Casa Prefabricada 190 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 190 m2</h3>
                        <div class="house-price">Desde $16.490.000</div>
                        <div class="house-details">
                            <span>🛏️ 3 Dormitorios</span>
                            <span>🚿 3 Baños</span>
                            <span>📐 135 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=17" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>
                    <!-- Casa 18 -->
                <div class="house-card extra-house" style="display:none;">
                    <div class="house-image">
                        <img src="IMG/casa18.jpg" alt="Casa Prefabricada 281 m2">
                    </div>
                    <div class="house-info">
                        <h3 class="house-title">Casa Prefabricada 281 m2</h3>
                        <div class="house-price">Desde $30.297.000</div>
                        <div class="house-details">
                            <span>🛏️ 6 Dormitorios</span>
                            <span>🚿 4,5 Baños</span>
                            <span>📐 254 m²</span>
                        </div>
                        <div class="house-actions">
                            <a href="detalle_casa.php?id=4" class="btn btn-secondary">Ver detalles</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div style="text-align:center; margin-top:20px;">
        <button id="verMasBtn" class="btn btn-primary">Ver más casas</button>
    </div>

    <script>
        const btn = document.getElementById('verMasBtn');
        const extras = document.querySelectorAll('.extra-house');

        // Subir el botón desde el borde de la sección
        btn.style.marginBottom = '40px'; // agrega espacio debajo del botón

        btn.addEventListener('click', () => {
            extras.forEach(house => house.style.display = 'block');
            btn.style.display = 'none'; // Oculta el botón después de mostrar
        });
    </script>


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
                <!-- Updated footer structure to match images -->
                <div class="footer-column">
                    <div class="footer-logo">
                        <img src="IMG/logoGreenValley.jpg" alt="Green Valley">
                        <div>
                            <p>© 2025 Green Valley, Inc.</p>
                            <p>All rights reserved.</p>
                        </div>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Enlaces</h3>
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="sobre-nosotros.php">Sobre Nosotros</a></li>
                        <li><a href="#catalog">Modelos</a></li>
                        <li><a href="#quote">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Información</h3>
                    <ul>
                        <li><a href="sobrenosotros.php">Quiénes Somos</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Síguenos</h3>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="https://www.youtube.com/@EstructurasGreenValley">YouTube</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/+56995862538" class="whatsapp-float" target="_blank">
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
