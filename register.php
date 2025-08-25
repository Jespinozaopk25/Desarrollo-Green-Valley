<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Green Valley</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Bar */
        .top-bar {
            background-color: #2c3e50;
            color: white;
            padding: 8px 0;
            font-size: 0.9rem;
        }

        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .contact-info {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .whatsapp-buttons {
            display: flex;
            gap: 15px;
        }

        .whatsapp-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #25d366;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .whatsapp-btn:hover {
            opacity: 0.8;
        }

        /* Header */
        header {
            background-color: #e8f5e8;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background-color: #2c3e50;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .logo-text {
            color: #2c3e50;
        }

        .logo-text span {
            color: #7cb342;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav ul li a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s;
        }

        nav ul li a:hover {
            background-color: #7cb342;
            color: white;
        }

        /* Register Section */
        .register-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 0;
            background: linear-gradient(135deg, rgba(124, 179, 66, 0.1) 0%, rgba(124, 179, 66, 0.2) 100%);
        }

        .register-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-top: 5px solid #7cb342;
        }

        .register-logo {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
        }

        .register-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
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
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #7cb342;
            outline: none;
            box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.2);
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            margin-top: 10px;
            background-color: #7cb342;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .register-btn:hover {
            background-color: #689f38;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .login-link a {
            color: #7cb342;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .terms {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #7f8c8d;
            text-align: center;
        }

        .terms a {
            color: #7cb342;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
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

        .loading {
            display: none;
            text-align: center;
            margin: 10px 0;
        }

        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #7cb342;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Footer */
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
        }

        .copyright {
            text-align: center;
            color: #bdc3c7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-bar-content {
                flex-direction: column;
                gap: 10px;
            }

            .contact-info {
                flex-direction: column;
                gap: 10px;
            }

            .header-container {
                flex-direction: column;
                gap: 20px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .register-container {
                padding: 30px 20px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
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
            <a href="index.html" class="logo">
                <div class="logo-text">Green<span>Valley</span></div>
            </a>
            <nav>
                <ul>
                    <li><a href="index.html">Inicio</a></li>
                    <li><a href="index.html#catalog">Catálogo</a></li>
                    <li><a href="index.html#quote">Cotizar</a></li>
                    <li><a href="index.html#contacto">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Register Section -->
    <section class="register-section">
        <div class="register-container">
            <div class="register-logo">
                <div class="logo">
                    <div class="logo-text">Green<span>Valley</span></div>
                </div>
            </div>
            <h2 class="register-title">Crear una Cuenta</h2>
            
            <div id="alert-container">
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-error">' . htmlspecialchars($_GET['error']) . '</div>';
                }
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
                }
                ?>
            </div>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                Procesando registro...
            </div>
            
            <form id="registerForm" method="post" action="auth/register_process.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">Nombre</label>
                        <input type="text" id="first-name" name="first_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last-name">Apellido</label>
                        <input type="text" id="last-name" name="last_name" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm-password">Confirmar Contraseña</label>
                        <input type="password" id="confirm-password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="rut">RUT</label>
                        <input type="text" id="rut" name="rut" class="form-control" placeholder="12345678-9" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" id="address" name="address" class="form-control">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">Ciudad</label>
                        <input type="text" id="city" name="city" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="region">Región</label>
                        <input type="text" id="region" name="region" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="user-type">Tipo de Usuario</label>
                    <select id="user-type" name="user_type" class="form-control" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="cliente">Cliente</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="supervisor">Supervisor</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" style="display: inline; font-weight: normal;">Acepto los <a href="#">Términos y Condiciones</a> y la <a href="#">Política de Privacidad</a></label>
                </div>
                
                <button type="submit" class="register-btn" id="registerBtn">Registrarse</button>
                
                <p class="login-link">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                
                <p class="terms">
                    Al registrarte, aceptas recibir comunicaciones de Green Valley relacionadas con tus cotizaciones, presupuestos y órdenes.
                </p>
            </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            const loading = document.getElementById('loading');
            const alertContainer = document.getElementById('alert-container');

            registerForm.addEventListener('submit', function (e) {
                e.preventDefault();
                handleRegister();
            });

            async function handleRegister() {
                const firstName = document.getElementById('first-name').value.trim();
                const lastName = document.getElementById('last-name').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm-password').value;
                const rut = document.getElementById('rut').value.trim();
                const userType = document.getElementById('user-type').value;
                const terms = document.getElementById('terms').checked;

                // Validaciones básicas
                if (!firstName || !lastName || !email || !password || !rut || !userType) {
                    showAlert('Por favor, completa todos los campos obligatorios', 'error');
                    return;
                }

                if (password !== confirmPassword) {
                    showAlert('Las contraseñas no coinciden', 'error');
                    return;
                }

                if (!terms) {
                    showAlert('Debes aceptar los términos y condiciones', 'error');
                    return;
                }

                // Validar formato de RUT chileno (básico)
                const rutRegex = /^[0-9]{7,8}-[0-9kK]$/;
                if (!rutRegex.test(rut)) {
                    showAlert('El formato del RUT no es válido. Usa el formato 12345678-9', 'error');
                    return;
                }

                setLoading(true);
                clearAlert();

                try {
                    const formData = new FormData(registerForm);
                    
                    const response = await fetch('auth/register_process.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        showAlert('Registro exitoso. Redirigiendo al login...', 'success');
                        
                        // Redirigir al login después de 2 segundos
                        setTimeout(() => {
                            window.location.href = 'login.php?success=Registro exitoso. Por favor inicia sesión.';
                        }, 2000);
                    } else {
                        showAlert(data.message || 'Error en el registro', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Error de conexión. Intenta nuevamente.', 'error');
                } finally {
                    setLoading(false);
                }
            }

            function setLoading(isLoading) {
                if (isLoading) {
                    loading.style.display = 'block';
                    registerBtn.disabled = true;
                    registerBtn.textContent = 'Procesando...';
                } else {
                    loading.style.display = 'none';
                    registerBtn.disabled = false;
                    registerBtn.textContent = 'Registrarse';
                }
            }

            function showAlert(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
                alertContainer.innerHTML = `
                    <div class="alert ${alertClass}">
                        ${message}
                    </div>
                `;
            }

            function clearAlert() {
                alertContainer.innerHTML = '';
            }

            // Validación de RUT chileno en tiempo real
            document.getElementById('rut').addEventListener('input', function(e) {
                let value = e.target.value;
                
                // Eliminar caracteres no permitidos
                value = value.replace(/[^0-9kK-]/g, '');
                
                // Asegurar que solo haya un guión
                const parts = value.split('-');
                if (parts.length > 1) {
                    value = parts[0] + '-' + parts.slice(1).join('');
                }
                
                // Limitar la longitud del RUT
                if (value.includes('-')) {
                    const [num, dv] = value.split('-');
                    if (num.length > 8) {
                        value = num.slice(0, 8) + '-' + dv;
                    }
                    if (dv.length > 1) {
                        value = num + '-' + dv.slice(0, 1);
                    }
                }
                
                e.target.value = value;
            });
        });
    </script>
</body>

</html>
