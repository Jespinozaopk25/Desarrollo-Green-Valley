<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sobre Nosotros - Green Valley Casas Prefabricadas</title>
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        /* Hero Section */
        .hero-about {
            background: linear-gradient(135deg, #7cb342 0%, #8bc34a 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .hero-about h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-about p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto;
            opacity: 0.95;
        }

        /* About Content */
        .about-content {
            padding: 80px 0;
            background: white;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            margin-bottom: 60px;
        }

        .about-text h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .about-text p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .about-image {
            position: relative;
        }

        .about-image img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Stats Section */
        .stats-section {
            background: #f8f9fa;
            padding: 60px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-card {
            background: white;
            padding: 30px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #7cb342;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 600;
        }

        /* Values Section */
        .values-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 3rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .value-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .value-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #7cb342;
        }

        .value-card h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .value-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Team Section */
        .team-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .team-intro {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 50px;
        }

        .team-intro h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .team-intro p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.7;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block;
            background: #7cb342;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background: #689f38;
            transform: translateY(-2px);
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 15px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-about h1 {
                font-size: 2.2rem;
            }

            .hero-about p {
                font-size: 1.1rem;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .about-text h2 {
                font-size: 2rem;
            }

            .section-title {
                font-size: 2rem;
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

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .values-grid {
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
                    <li><a href="sobre-nosotros.php">Nuestra Empresa</a></li>
                    <li><a href="index.php#catalog">Casas prefabricadas</a></li>
                    <li><a href="#llave">Llave en mano</a></li>
                    <li><a href="#proyectos">Proyectos</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <div class="cart-icon">🛒<span class="cart-badge">0</span></div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-about">
        <div class="container">
            <h1>Sobre Nosotros</h1>
            <p>Descubre quiénes somos en Casas Prefabricadas Green Valley</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <h2>Líderes en Casas Prefabricadas</h2>
                    <p>
                        En Casas Prefabricadas Green Valley somos una empresa líder en la construcción y diseño de casas
                        prefabricadas en metalcon en Chile. Nos enorgullece ofrecer a nuestros clientes una amplia gama
                        de diseños personalizados y una construcción de alta calidad.
                    </p>
                    <p>
                        Nuestro equipo de profesionales altamente capacitados se asegura de que cada detalle de tu casa
                        prefabricada se construya con precisión y cuidado. Nos encargamos de todo el proceso de
                        construcción para asegurarte una experiencia sin preocupaciones.
                    </p>
                </div>
                <!-- Aca deberia de tner una imagen del equipo " que no se nos olvide xd" -->
                <div class="about-image">
                    <img src="/placeholder.svg?height=400&width=500" alt="Equipo Green Valley">
                </div>
            </div>
            <!-- Aca deberia de tner una imagen de una casa " que no se nos olvide xd" -->

            <div class="about-grid">
                <div class="about-image">
                    <img src="/placeholder.svg?height=400&width=500" alt="Casa Green Valley">
                </div>
                <div class="about-text">
                    <h2>Más que una Casa, un Hogar</h2>
                    <p>
                        Creemos que una casa es más que una estructura, es un hogar donde se crea una vida llena de
                        momentos y recuerdos. Por eso, nos tomamos el tiempo de conocer a nuestros clientes y entender
                        sus necesidades, para asegurarnos de que construimos la casa de sus sueños.
                    </p>
                    <p>
                        Nos esforzamos por mantener altos estándares en nuestra construcción y en el servicio al
                        cliente. En Casas Prefabricadas Green Valley, nos enorgullece nuestro compromiso con la calidad,
                        la atención al detalle y la satisfacción del cliente.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Años de Experiencia</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Casas Construidas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Clientes Satisfechos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">15</div>
                    <div class="stat-label">Profesionales</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Nuestros Valores</h2>
            <p class="section-subtitle">Los principios que guían nuestro trabajo y compromiso con cada cliente</p>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🏆</div>
                    <h3>Calidad Superior</h3>
                    <p>Utilizamos materiales de primera calidad y técnicas de construcción avanzadas para garantizar la
                        durabilidad y excelencia de cada proyecto.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🤝</div>
                    <h3>Compromiso Total</h3>
                    <p>Nos comprometemos con cada cliente desde el primer contacto hasta la entrega final, asegurando
                        una experiencia excepcional en todo momento.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🎯</div>
                    <h3>Atención al Detalle</h3>
                    <p>Cada elemento de tu casa prefabricada es cuidadosamente planificado y ejecutado con la máxima
                        precisión y atención a los detalles.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">💡</div>
                    <h3>Innovación Constante</h3>
                    <p>Nos mantenemos a la vanguardia de las nuevas tecnologías y tendencias en construcción
                        prefabricada para ofrecer soluciones modernas.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🌱</div>
                    <h3>Sostenibilidad</h3>
                    <p>Construimos pensando en el futuro, utilizando materiales eco-friendly y procesos que respetan el
                        medio ambiente.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">⏰</div>
                    <h3>Puntualidad</h3>
                    <p>Cumplimos con los plazos establecidos sin comprometer la calidad, porque sabemos lo importante
                        que es tu tiempo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="team-intro">
                <h2>Nuestro Equipo</h2>
                <p>
                    Contamos con un equipo de profesionales altamente capacitados y comprometidos con la excelencia.
                    Arquitectos, ingenieros, constructores y diseñadores trabajan juntos para hacer realidad tu proyecto
                    de casa prefabricada, superando las expectativas en cada construcción.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>¿Listo para Construir tu Hogar?</h2>
            <p>Si estás buscando una casa prefabricada de alta calidad y un servicio al cliente excepcional, no busques
                más allá de Casas Prefabricadas Green Valley.</p>
            <a href="index.php#quote" class="cta-button">Solicita tu Cotización</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Green Valley Estructuras</h3>
                    <p style="color: #bdc3c7; margin-bottom: 20px; font-size: 14px;">
                        Especialistas en casas prefabricadas de alta calidad. Transformamos tus sueños en realidad con
                        diseños únicos y construcción eficiente.
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
                        <li><a href="index.php#catalog">Casas Prefabricadas</a></li>
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
                        <li><a href="sobre-nosotros.php">Sobre Nosotros</a></li>
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
                        <a href="https://wa.me/56956397365"
                            style="color: #25d366; text-decoration: none; font-size: 13px;">+569 5309 7365</a><br>
                        <a href="https://wa.me/56987037917"
                            style="color: #25d366; text-decoration: none; font-size: 13px;">+569 8703 7917</a>
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

        // Animation on scroll (simple version)
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.value-card, .stat-card, .about-text').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>

</html>