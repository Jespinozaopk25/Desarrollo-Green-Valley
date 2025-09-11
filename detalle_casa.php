<?php
session_start();

// Obtener ID de la casa desde URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 2; // Por defecto casa 2

// Datos de las casas (normalmente vendrían de base de datos)
$casas = [
    1 => [
        'titulo' => 'Casa Prefabricada 21 m²',
        'imagen' => 'IMG/casa1.jpg',
        'precio_desde' => '2.890.000',
        'dormitorios' => 1,
        'banos' => 1,
        'superficie' => 21,
        'descripcion' => 'Modelo compacto ideal para parejas jóvenes o como casa de campo. Perfecta combinación de funcionalidad y confort en un espacio optimizado.'
    ],
    2 => [
        'titulo' => 'Casa Prefabricada 36 m²',
        'imagen' => 'IMG/casa2.jpg',
        'precio_desde' => '3.390.000',
        'dormitorios' => 2,
        'banos' => 1,
        'superficie' => 36,
        'descripcion' => 'Modelo familiar pequeño, perfecto para familias jóvenes o como segunda vivienda. Esta casa prefabricada combina funcionalidad y comodidad en un diseño compacto pero bien distribuido.'
    ],
    3 => [
        'titulo' => 'Casa Prefabricada 48 m²',
        'imagen' => 'IMG/casa3.jpg',
        'precio_desde' => '4.290.000',
        'dormitorios' => 3,
        'banos' => 2,
        'superficie' => 48,
        'descripcion' => 'Modelo familiar amplio, ideal para familias grandes que buscan espacios cómodos y funcionales. Diseño moderno con todas las comodidades.'
    ]
];

$kits = [
    1 => [
        0 => [
            'nombre' => 'Kit Básico',
            'precio' => '5000000',
            'descripcion' => 'Incluye estructura, paneles y techumbre. Ideal para autoconstrucción.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Completo',
            'precio' => '7000000',
            'descripcion' => 'Incluye estructura más ventanas, puertas y terminaciones básicas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Premium',
            'precio' => '9000000',
            'descripcion' => 'Incluye todo lo anterior más radier, electricidad y flete.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    2 => [
        0 => [
            'nombre' => 'Kit Estructural',
            'precio' => '3000000',
            'descripcion' => 'Incluye estructura, paneles y techumbre. Ideal para autoconstrucción.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Inicial',
            'precio' => '5000000',
            'descripcion' => 'Incluye estructura más ventanas, puertas y terminaciones básicas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Completo',
            'precio' => '7000000',
            'descripcion' => 'Incluye todo lo anterior más radier, electricidad y flete.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ],
    3 => [
        0 => [
            'nombre' => 'Kit Esencial',
            'precio' => '8000000',
            'descripcion' => 'Incluye estructura, paneles y techumbre premium.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        1 => [
            'nombre' => 'Kit Integral',
            'precio' => '11000000',
            'descripcion' => 'Incluye estructura completa con terminaciones de calidad.',
            'imagen' => 'IMG/kits_1.jpeg'
        ],
        2 => [
            'nombre' => 'Kit Luxury',
            'precio' => '15000000',
            'descripcion' => 'Incluye todo premium: estructura, terminaciones de lujo, instalaciones completas.',
            'imagen' => 'IMG/kits_1.jpeg'
        ]
    ]
];

// Verificar si la casa existe
if (!isset($casas[$id])) {
    header("Location: index.php");
    exit;
}

$casa = $casas[$id];
$kits_casa = $kits[$id];

// Obtener cantidad total del carrito para el badge
function obtenerCantidadTotal($carrito) {
    $total = 0;
    if (!empty($carrito)) {
        foreach ($carrito as $item) {
            $total += $item['cantidad'];
        }
    }
    return $total;
}

$cantidadCarrito = isset($_SESSION['carrito']) ? obtenerCantidadTotal($_SESSION['carrito']) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($casa['titulo']); ?> - Green Valley</title>
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
            background: var(--background-light);
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
        }

        header {
            background: var(--white);
            box-shadow: 0 4px 20px var(--shadow-light);
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
            color: var(--secondary-color);
            font-weight: 500;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 25px;
        }

        nav a:hover {
            color: var(--primary-color);
            background: rgba(124, 179, 66, 0.1);
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            background: rgba(124, 179, 66, 0.2);
            color: var(--primary-color);
            text-decoration: none;
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
        }

        /* Enhanced Detail Container */
        .detail-container {
            padding: 60px 0;
        }

        .breadcrumb {
            background: var(--white);
            padding: 15px 30px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: 0 2px 10px var(--shadow-light);
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .house-detail-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 50px;
            box-shadow: 0 15px 35px var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .house-detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        }

        .house-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: flex-start;
            margin-bottom: 60px;
        }

        .house-image-container {
            position: relative;
        }

        .house-main-image {
            width: 100%;
            max-width: 500px;
            border-radius: var(--border-radius);
            box-shadow: 0 20px 40px var(--shadow-medium);
            transition: var(--transition);
        }

        .house-main-image:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 50px var(--shadow-medium);
        }

        .house-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);
        }

        .house-info {
            display: flex;
            flex-direction: column;
        }

        .house-title {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .house-price {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 700;
        }

        .house-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .spec-item {
            background: var(--background-light);
            padding: 20px;
            border-radius: var(--border-radius);
            text-align: center;
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
        }

        .spec-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px var(--shadow-light);
        }

        .spec-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .spec-label {
            font-size: 0.9rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .spec-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .house-description {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 40px;
            padding: 25px;
            background: rgba(124, 179, 66, 0.05);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
        }

        /* Enhanced Kits Section */
        .kits-section {
            margin-top: 60px;
        }

        .kits-title {
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 40px;
            text-align: center;
            font-weight: 700;
            position: relative;
        }

        .kits-title::after {
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

        .kits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .kit-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 25px var(--shadow-light);
            padding: 30px;
            text-align: center;
            position: relative;
            transition: var(--transition);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .kit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px var(--shadow-medium);
        }

        .kit-card.selected {
            border-color: var(--primary-color);
            box-shadow: 0 15px 35px rgba(124, 179, 66, 0.2);
        }

        .kit-radio {
            position: absolute;
            top: 20px;
            right: 20px;
            transform: scale(1.5);
            accent-color: var(--primary-color);
        }

        .kit-image {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px var(--shadow-light);
        }

        .kit-name {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .kit-description {
            color: var(--text-light);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .kit-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--secondary-color);
            background: var(--background-light);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
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
            font-size: 1rem;
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

        .btn-large {
            padding: 20px 50px;
            font-size: 1.2rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 30px 0;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(124, 179, 66, 0.3);
            opacity: 0;
            transform: translateY(-20px);
            transition: var(--transition);
        }

        .success-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* WhatsApp Float */
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
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Kit Selection Form */
        .kit-selection-form {
            margin-top: 30px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .quantity-selector label {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .quantity-input {
            width: 80px;
            padding: 10px;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .house-hero {
                grid-template-columns: 1fr;
                gap: 30px;
                text-align: center;
            }

            .house-title {
                font-size: 2rem;
            }

            .house-specs {
                grid-template-columns: repeat(2, 1fr);
            }

            .kits-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                gap: 15px;
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
                    <div>📍 Av. Padre Jorge Alessandri KM 22, San Bernardo, RM.</div>
                    <div>📧 contacto@casasgreenvalley.cl</div>
                    <div>📞 Tel.: +56 2 2583 2001</div>
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
                    <li><a href="index.php#catalog">Casas prefabricadas</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
            <a href="carrito.php" class="cart-icon">
                🛒<span class="cart-badge"><?php echo $cantidadCarrito; ?></span>
            </a>
        </div>
    </header>

    <!-- Detail Container -->
    <section class="detail-container">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="index.php">Inicio</a> > 
                <a href="index.php#catalog">Casas Prefabricadas</a> > 
                <span><?php echo htmlspecialchars($casa['titulo']); ?></span>
            </nav>

            <!-- House Detail Card -->
            <div class="house-detail-card fade-in-up">
                <!-- House Hero Section -->
                <div class="house-hero">
                    <div class="house-image-container">
                        <img src="<?php echo htmlspecialchars($casa['imagen']); ?>" 
                             alt="<?php echo htmlspecialchars($casa['titulo']); ?>" 
                             class="house-main-image">
                        <span class="house-badge">Recomendado</span>
                    </div>
                    <div class="house-info">
                        <h1 class="house-title"><?php echo htmlspecialchars($casa['titulo']); ?></h1>
                        <div class="house-price">Desde $<?php echo number_format((float) str_replace('.', '', $casa['precio_desde']), 0, ',', '.'); ?></div>
                        
                        <div class="house-specs">
                            <div class="spec-item">
                                <div class="spec-icon">🛏️</div>
                                <div class="spec-label">Dormitorios</div>
                                <div class="spec-value"><?php echo $casa['dormitorios']; ?></div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-icon">🚿</div>
                                <div class="spec-label">Baños</div>
                                <div class="spec-value"><?php echo $casa['banos']; ?></div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-icon">📐</div>
                                <div class="spec-label">Superficie</div>
                                <div class="spec-value"><?php echo $casa['superficie']; ?> m²</div>
                            </div>
                        </div>

                        <div class="house-description">
                            <?php echo htmlspecialchars($casa['descripcion']); ?>
                        </div>

                        <a href="#kits" class="btn btn-primary">Ver Opciones de Kits</a>
                    </div>
                </div>

                <!-- Kits Section -->
                <div id="kits" class="kits-section">
                    <h2 class="kits-title">Opciones de Kits para este modelo</h2>

                    <!-- Formulario de selección de kit -->
                    <form id="kit-form" class="kit-selection-form" method="POST" action="carrito.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="kit" id="selected-kit" value="2">
                        
                        <!-- Kits Grid -->
                        <div class="kits-grid">
                            <?php foreach ($kits_casa as $kit_idx => $kit): ?>
                                <div class="kit-card <?php echo $kit_idx === 2 ? 'selected' : ''; ?>" onclick="selectKit(<?php echo $kit_idx; ?>)">
                                    <input type="radio" name="kit_radio" class="kit-radio" value="<?php echo $kit_idx; ?>" <?php echo $kit_idx === 2 ? 'checked' : ''; ?>>
                                    <img src="<?php echo htmlspecialchars($kit['imagen']); ?>" alt="<?php echo htmlspecialchars($kit['nombre']); ?>" class="kit-image">
                                    <div class="kit-name"><?php echo htmlspecialchars($kit['nombre']); ?></div>
                                    <div class="kit-description"><?php echo htmlspecialchars($kit['descripcion']); ?></div>
                                    <div class="kit-price">$<?php echo number_format(intval($kit['precio']), 0, ',', '.'); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="quantity-selector">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="10" class="quantity-input">
                        </div>

                        <!-- Success Message -->
                        <div id="success-message" class="success-message">
                            ✅ Kit agregado al carrito exitosamente
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary btn-large" id="add-to-cart-btn">
                                Agregar al Carrito
                            </button>
                            <a href="index.php#catalog" class="btn btn-secondary">Ver Más Modelos</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56995862538" class="whatsapp-float" target="_blank">💬</a>

    <script>
        let selectedKit = 2; // Kit Completo selected by default
        
        const kits = <?php echo json_encode($kits_casa); ?>;

        function selectKit(kitIndex) {
            selectedKit = kitIndex;
            document.getElementById('selected-kit').value = kitIndex;
            document.querySelectorAll('.kit-card').forEach((card, idx) => {
                if (idx === kitIndex) {
                    card.classList.add('selected');
                    card.querySelector('.kit-radio').checked = true;
                } else {
                    card.classList.remove('selected');
                    card.querySelector('.kit-radio').checked = false;
                }
            });
        }

        function updateSuccessMessage() {
            const successMessage = document.getElementById('success-message');
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
        document.getElementById('kit-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            formData.append('kit', selectedKit);

            fetch('carrito.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateSuccessMessage();
                    // Optionally update cart badge here
                } else {
                    alert('Error al agregar al carrito. Intenta nuevamente.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Se agrega correctamente.');
            });
        });
    </script>
</body>     
</html>
<?php
// contacto.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Configura el destinatario
    $destinatario = "info@tuempresa.com";

    // Configura el asunto
    $asunto = "Nuevo mensaje de contacto de $nombre";

    // Configura el encabezado del correo
    $headers = "From: $nombre <$email>" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Envía el correo
    if (mail($destinatario, $asunto, $mensaje, $headers)) {
        echo "Mensaje enviado exitosamente.";
    } else {
        echo "Error al enviar el mensaje.";
    }
}

