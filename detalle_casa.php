<?php
session_start(); // Mover al principio del archivo, antes de cualquier HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalle - Casa Prefabricada 21 m²</title>
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

        /* Detalle Container */
        .detalle-container {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 70vh;
        }

        .detalle-flex {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .detalle-img {
            position: relative;
        }

        #mainImage {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        #mainImage:hover {
            transform: scale(1.02);
        }

        .nav-btn {
            position: absolute;
            top: 45%;
            background-color: rgba(0,0,0,0.3);
            color: white;
            border: none;
            font-size: 20px;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-btn:hover {
            background-color: rgba(0,0,0,0.5);
            transform: scale(1.1);
        }

        #prevBtn {
            left: 10px;
        }

        #nextBtn {
            right: 10px;
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .thumbnail:hover {
            border-color: #7cb342;
            transform: scale(1.05);
        }

        .detalle-info {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            height: fit-content;
        }

        .detalle-info h1 {
            font-size: 2.2rem;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .precio {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #666;
        }

        .precio strong {
            color: #7cb342;
            font-size: 1.8rem;
        }

        .detalle-info ul {
            list-style: none;
            margin-bottom: 25px;
        }

        .detalle-info ul li {
            padding: 8px 0;
            color: #555;
            font-size: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detalle-info ul li:last-child {
            border-bottom: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #7cb342;
        }

        .precio-display {
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #7cb342;
        }

        .precio-display label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        #precioDisplay {
            color: #7cb342;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .quantity-cart-container {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 20px;
        }

        .quantity-input {
            width: 80px;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-cart {
            background: #7cb342;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-cart:hover {
            background: #689f38;
            transform: translateY(-1px);
        }

        .detalle-descripcion {
            max-width: 1200px;
            margin: 40px auto 0;
            padding: 0 20px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .detalle-descripcion h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .detalle-descripcion p {
            color: #666;
            line-height: 1.6;
            font-size: 15px;
        }

        /* Footer - mismo estilo que index */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .detalle-flex {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .detalle-info h1 {
                font-size: 1.8rem;
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

            .quantity-cart-container {
                flex-direction: column;
            }

            .quantity-input {
                width: 100%;
            }

            .thumbnail-container {
                justify-content: center;
            }

            .nav-btn {
                width: 35px;
                height: 35px;
                font-size: 16px;
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
                    <li><a href="sobre-nosotros.html">Nuestra Empresa</a></li>
                    <li><a href="#catalog">Casas prefabricadas</a></li>
                    <li><a href="#llave">Llave en mano</a></li>
                    <li><a href="#proyectos">Proyectos</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="logout.php" class="logout-btn">Cerrar Sesión</a></li>
                        <li class="user-greeting">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <div class="cart-icon">🛒<span class="cart-badge">0</span></div>
        </div>
    </header>

    <!-- Contenido Detalle -->
    <section class="detalle-container">
        <div class="detalle-flex">
            <div class="detalle-img">
                <button id="prevBtn" class="nav-btn">←</button>
                <img id="mainImage" src="casa1.jpg" alt="Casa Prefabricada 21 m²">
                <button id="nextBtn" class="nav-btn">→</button>
                <div class="thumbnail-container">
                    <img src="casa1.jpg" alt="Vista 1" class="thumbnail">
                    <img src="casa1_1.jpg" alt="Vista 2" class="thumbnail">
                    <img src="casa1_2.jpg" alt="Vista 3" class="thumbnail">
                    <img src="casa1_3.jpg" alt="Vista 4" class="thumbnail">
                </div>
            </div>
            
            <div class="detalle-info">
                <h1>Casa Prefabricada 21 m²</h1>
                <div class="precio">Desde <strong>$1.940.000</strong></div>
                
                <ul>
                    <li>🛏️ 1 Habitación</li>
                    <li>🚿 1 Baño</li>
                    <li>📐 21 m² Totales</li>
                    <li>🏠 Panel SIP, aislación térmica</li>
                    <li>🛠️ Personalizable en distribución</li>
                </ul>
                
                <form action="carrito.php" method="POST">
                    <div class="form-group">
                        <label for="kit">Selecciona el Kit:</label>
                        <select name="kit" id="kit" class="form-control">
                            <option value="estructural">Kit Estructural</option>
                            <option value="inicial">Kit Inicial</option>
                            <option value="completo">Kit Completo</option>
                        </select>
                    </div>
                    
                    <div class="precio-display">
                        <label>Precio del Kit:</label><br>
                        <strong id="precioDisplay">$1.940.000</strong>
                    </div>
                    
                    <div class="quantity-cart-container">
                        <input type="number" name="cantidad" value="1" min="1" class="quantity-input" />
                        <button type="submit" class="btn-cart">AÑADIR AL CARRITO</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="detalle-descripcion">
            <h2>Descripción del Producto</h2>
            <p>
                Esta casa prefabricada de 21 metros cuadrados es una excelente solución para quienes buscan una vivienda
                compacta, eficiente y con gran aislación térmica. Está construida con paneles SIP, cuenta con estructura
                metálica y se entrega con instalación eléctrica completa. Ideal como ampliación, oficina, casa de campo
                o espacio independiente. Se puede personalizar según tus necesidades.
            </p>
        </div>
    </section>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56956397365" class="whatsapp-float" target="_blank" title="Contáctanos por WhatsApp">💬</a>

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
                        <li><a href="#">Sobre Nosotros</a></li>
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

    <script>
        // ------- CAMBIO DE PRECIO POR KIT -------
        const preciosKit = {
            "estructural": 1940000,
            "inicial": 3380000,
            "completo": 4600000
        };

        const selectKit = document.getElementById('kit');
        const precioDisplay = document.getElementById('precioDisplay');

        function formatearPrecio(num) {
            return '$' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        selectKit.addEventListener('change', () => {
            const kitSeleccionado = selectKit.value;
            const precio = preciosKit[kitSeleccionado];
            precioDisplay.textContent = formatearPrecio(precio);
        });

        // ------- CARRUSEL DE IMÁGENES -------
        const mainImage = document.getElementById("mainImage");
        const thumbnails = document.querySelectorAll(".thumbnail");
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");

        let imagenes = Array.from(thumbnails).map(img => img.src);
        let currentIndex = 0;

        function actualizarImagen(index) {
            mainImage.src = imagenes[index];
            // Actualizar thumbnails activos
            thumbnails.forEach((thumb, i) => {
                thumb.style.border = i === index ? '2px solid #7cb342' : '2px solid transparent';
            });
        }

        // Inicializar primer thumbnail como activo
        actualizarImagen(0);

        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener("click", () => {
                currentIndex = index;
                actualizarImagen(currentIndex);
            });
        });

        nextBtn.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % imagenes.length;
            actualizarImagen(currentIndex);
        });

        prevBtn.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + imagenes.length) % imagenes.length;
            actualizarImagen(currentIndex);
        });

        mainImage.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % imagenes.length;
            actualizarImagen(currentIndex);
        });

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
    </script>
</body>
</html>
