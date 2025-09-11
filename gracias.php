<?php
session_start();

// Obtener ID del pedido de la URL
$pedido_id = isset($_GET['pedido_id']) ? htmlspecialchars($_GET['pedido_id']) : 'N/A';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias por tu compra! - Green Valley</title>
    <style>
        :root {
            --primary-color: #7cb342;
            --primary-dark: #689f38;
            --secondary-color: #2c3e50;
            --accent-color: #25d366;
            --success-color: #27ae60;
            --white: #ffffff;
            --background-light: #f8f9fa;
            --shadow-light: rgba(0, 0, 0, 0.08);
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
            background: linear-gradient(135deg, var(--background-light) 0%, #e8f5e8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-container {
            max-width: 600px;
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 50px;
            text-align: center;
            box-shadow: 0 20px 60px var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .success-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--success-color), var(--primary-color));
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            animation: successPulse 2s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(39, 174, 96, 0.3);
        }

        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .success-title {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .success-message {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .order-details {
            background: var(--background-light);
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid var(--success-color);
        }

        .order-id {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        .order-date {
            color: #666;
            font-size: 0.95rem;
        }

        .contact-info {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid var(--accent-color);
        }

        .contact-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .whatsapp-links {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-color);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-btn:hover {
            background: #128c7e;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }

        .actions {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 179, 66, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--secondary-color);
            border: 2px solid #ddd;
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .steps {
            text-align: left;
            margin-top: 30px;
        }

        .steps h4 {
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .steps ol {
            color: #666;
            padding-left: 20px;
        }

        .steps li {
            margin-bottom: 8px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .success-container {
                margin: 20px;
                padding: 30px 20px;
            }

            .success-title {
                font-size: 2rem;
            }

            .actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 250px;
            }

            .whatsapp-links {
                flex-direction: column;
                align-items: center;
            }

            .whatsapp-btn {
                width: 100%;
                max-width: 200px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✅</div>
        
        <h1 class="success-title">¡Pedido Confirmado!</h1>
        
        <p class="success-message">
            Gracias por confiar en Green Valley. Tu pedido ha sido recibido exitosamente 
            y nuestro equipo se pondrá en contacto contigo muy pronto.
        </p>

        <div class="order-details">
            <div class="order-id">Número de Pedido: <strong>#<?php echo $pedido_id; ?></strong></div>
            <div class="order-date">Fecha: <?php echo date('d/m/Y H:i'); ?></div>
        </div>

        <div class="contact-info">
            <div class="contact-title">📱 Contacto Directo por WhatsApp</div>
            <p style="color: #666; margin-bottom: 15px; font-size: 0.95rem;">
                Para consultas inmediatas sobre tu pedido:
            </p>
            <div class="whatsapp-links">
                <a href="https://wa.me/56956397365?text=Hola,%20tengo%20una%20consulta%20sobre%20mi%20pedido%20#<?php echo $pedido_id; ?>" 
                   class="whatsapp-btn" target="_blank">
                    💬 +569 5309 7365
                </a>
                <a href="https://wa.me/56987037917?text=Hola,%20tengo%20una%20consulta%20sobre%20mi%20pedido%20#<?php echo $pedido_id; ?>" 
                   class="whatsapp-btn" target="_blank">
                    💬 +569 8703 7917
                </a>
            </div>
        </div>

        <div class="steps">
            <h4>📋 ¿Qué sigue ahora?</h4>
            <ol>
                <li><strong>Revisión:</strong> Nuestro equipo revisará tu pedido en las próximas 2-4 horas.</li>
                <li><strong>Contacto:</strong> Te llamaremos para confirmar detalles y coordinar la entrega.</li>
                <li><strong>Producción:</strong> Comenzaremos la preparación de tu casa prefabricada.</li>
                <li><strong>Entrega:</strong> Te mantendremos informado sobre los tiempos de entrega.</li>
            </ol>
        </div>

        <div class="actions">
            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
            <a href="index.php#catalog" class="btn btn-secondary">Ver Más Casas</a>
        </div>

        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #eee; color: #888; font-size: 0.9rem;">
            <p>📧 También recibirás un email de confirmación en breve</p>
            <p>🏠 <strong>Green Valley Estructuras</strong> - Hacemos realidad tus sueños</p>
        </div>
    </div>
</body>
</html>