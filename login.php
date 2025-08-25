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
                    // Verificar contraseña (sin hash, solo para pruebas)
                    if ($contrasena == $row['contrasena']) {
                        // Asignar variables de sesión según tu base de datos
                        $_SESSION['user_id'] = $row['id_usuario'];
                        $_SESSION['id_usuario'] = $row['id_usuario']; // Asegura compatibilidad
                        $_SESSION['usuario'] = $row['correo'];
                        $_SESSION['nombre'] = $row['nombre'];
                        $_SESSION['rol'] = $row['rol'];
                        $_SESSION['estado'] = $row['estado'];
                        $_SESSION['user_role'] = $row['rol']; // Usar el valor real: administrador, vendedor, usuario

                        // Redirigir según el rol
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
            background: linear-gradient(135deg, #7cb342 0%, #8bc34a 100%);
            min-height: 100vh;
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

        /* Login Section */
        .login-section {
            padding: 60px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 60px 40px;
            width: 100%;
            max-width: 500px;
        }

        .login-container h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #7cb342;
            background: white;
            box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.1);
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(45deg, #7cb342, #8bc34a);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 179, 66, 0.4);
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 20px 0;
        }

        .copyright {
            text-align: center;
            color: #bdc3c7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
                padding: 40px 20px;
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
                    <li><a href="sobrenosotros.php">Nuestra Empresa</a></li>
                    <li><a href="index.php#catalog">Casas prefabricadas</a></li>
                    <?php if (isset($_SESSION['user_id']) || isset($_SESSION['usuario'])): ?>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="logout.php" style="background: #e74c3c; color: white; padding: 8px 15px; border-radius: 20px;">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <h2>Iniciar Sesión</h2>
                
                <!-- Mostrar mensajes -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>

                <!-- Formulario de Login -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="login" class="submit-btn">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2025 Green Valley Estructuras. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
