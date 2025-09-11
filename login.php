<?php
session_start();

$error_message = '';
$success_message = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);
    
    if (!empty($correo) && !empty($contrasena)) {
        $conn = mysqli_connect("localhost", "root", "", "green_valley_bd");
        if (!$conn) {
            $error_message = "Error de conexión: " . mysqli_connect_error();
        } else {
            $query = "SELECT * FROM usuario WHERE correo = ? LIMIT 1";
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $correo);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)) {
                    if ($contrasena == $row['contrasena']) {
                        $_SESSION['user_id'] = $row['id_usuario'];
                        $_SESSION['id_usuario'] = $row['id_usuario'];
                        $_SESSION['usuario'] = $row['correo'];
                        $_SESSION['nombre'] = $row['nombre'];
                        $_SESSION['rol'] = $row['rol'];
                        $_SESSION['estado'] = $row['estado'];
                        $_SESSION['user_role'] = $row['rol'];

                        if ($row['rol'] == 'administrador') {
                            header("location: admin-dashboard.php");
                            exit();
                        } elseif ($row['rol'] == 'vendedor') {
                            header("location: vendedor-dashboard.php");
                            exit();
                        } elseif ($row['rol'] == 'usuario') {
                            header("location: client-dashboard.php");
                            exit();
                        } else {
                            $error_message = "Rol no reconocido.";
                        }
                    } else {
                        $error_message = "Correo o contraseña incorrectos.";
                    }
                } else {
                    $error_message = "Correo o contraseña incorrectos.";
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_message = "Error en la consulta.";
            }
            mysqli_close($conn);
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Green Valley</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #032676ff 0%, #3660a4ff 25%, #0d683cff 50%, #31762dff 75%, #0d5307ff 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background Elements */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .shape-1 {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #8eff1dff, #25b800f1);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            background: linear-gradient(45deg, #8bc34a, #9ccc65);
            top: 60%;
            right: 15%;
            animation-delay: 5s;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #9ccc65, #aed581);
            top: 40%;
            left: 70%;
            animation-delay: 10s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(100px, -50px) rotate(120deg); }
            66% { transform: translate(-50px, 100px) rotate(240deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        /* Top Bar */
        .top-bar {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            padding: 12px 0;
            font-size: 13px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .top-bar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            flex-wrap: wrap;
        }

        .contact-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0.9;
            transition: opacity 0.3s;
        }

        .contact-item:hover {
            opacity: 1;
        }

        .whatsapp-buttons {
            display: flex;
            gap: 12px;
        }

        .whatsapp-btn {
            background: linear-gradient(45deg, #25d366, #128c7e);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
        }

        .logo-image {
            height: 55px;
            width: auto;
            filter: drop-shadow(0 4px 8px rgba(3, 68, 4, 0.1));
            transition: transform 0.3s;
        }

        .logo-image:hover {
            transform: scale(1.05);
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 35px;
        }

        nav a {
            text-decoration: none;
            color: #334155;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            position: relative;
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(45deg, #7cb342, #8bc34a);
            transition: width 0.3s;
        }

        nav a:hover::after {
            width: 100%;
        }

        nav a:hover {
            color: #7cb342;
        }

        /* Login Section */
        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 85vh;
            padding: 40px 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 60px 50px;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #7cb342, #8bc34a, #9ccc65);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #7cb342, #03320aff);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            box-shadow: 0 8px 25px rgba(132, 255, 0, 0.3);
        }

        .login-container h2 {
            font-size: 2.2rem;
            color: #1e293b;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 16px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            background: #f8fafc;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-control:focus {
            outline: none;
            border-color: #7cb342;
            background: white;
            box-shadow: 0 0 0 4px rgba(124, 179, 66, 0.1);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            transition: color 0.3s;
        }

        .form-control:focus + .input-icon {
            color: #7cb342;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #7cb342 0%, #8bc34a 50%, #9ccc65 100%);
            color: white;
            padding: 18px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.025em;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(124, 179, 66, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            animation: slideInDown 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .alert-error {
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .alert i {
            margin-right: 8px;
        }

        /* Footer */
        footer {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            color: white;
            padding: 30px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .copyright {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
                padding: 40px 30px;
                border-radius: 20px;
            }

            .login-container h2 {
                font-size: 1.8rem;
            }

            .top-bar-content {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .contact-info {
                justify-content: center;
                font-size: 12px;
                gap: 20px;
            }

            nav ul {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-container {
                flex-direction: column;
                gap: 20px;
            }

            .floating-shape {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .form-control {
                padding: 14px 45px 14px 16px;
                font-size: 16px;
            }

            .submit-btn {
                padding: 16px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
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
                    Tel.: +56 2 2583 2001
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
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="IMG/logoGreenValley.jpg" alt="Green Valley" class="logo-image">
            </a>
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="sobrenosotros.php"><i class="fas fa-building"></i> Nuestra Empresa</a></li>
                    <li><a href="index.php#catalog"><i class="fas fa-house-user"></i> Casas prefabricadas</a></li>
                    <?php if (isset($_SESSION['user_id']) || isset($_SESSION['usuario'])): ?>
                        <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a href="logout.php" style="background: linear-gradient(45deg, #ef4444, #f87171); color: white; padding: 10px 20px; border-radius: 25px; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php" style="background: linear-gradient(45deg, #7cb342, #8bc34a); color: white; padding: 10px 20px; border-radius: 25px; box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <?php endif; ?>
                    <li><a href="#contacto"><i class="fas fa-envelope"></i> Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>¡Bienvenido!</h2>
                <p class="login-subtitle">Accede a tu cuenta de Green Valley</p>
            </div>
            
            <!-- Mostrar mensajes -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de Login -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <input type="email" id="correo" name="correo" class="form-control" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                
                <button type="submit" name="login" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="copyright">
            <p>&copy; 2025 Green Valley Estructuras. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        // Animación suave para el formulario
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Efecto de escritura para las alertas
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach((alert, index) => {
                alert.style.animationDelay = index * 0.2 + 's';
            });
        });
    </script>
</body>
</html>