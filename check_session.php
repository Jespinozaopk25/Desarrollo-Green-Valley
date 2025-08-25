<?php
session_start();
require_once '../conexionDB.php';
require_once '../datosconexion.php';

header('Content-Type: application/json');

$response = [
    'logged_in' => false,
    'user' => null
];

// Verificar sesión activa
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $response['logged_in'] = true;
    $response['user'] = [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'nombre_completo' => $_SESSION['nombre_completo']
    ];
} else {
    // Verificar cookie "recordarme"
    if (isset($_COOKIE['remember_token'])) {
        try {
            $db = new conexionDB(
                $_SESSION['tipoBD'],
                $_SESSION['hostBD'],
                $_SESSION['usuarioBD'],
                $_SESSION['passwordBD'],
                $_SESSION['nombreBD'],
                $_SESSION['puertoBD']
            );

            if ($db->conectaDb()) {
                $token_hash = hash('sha256', $_COOKIE['remember_token']);
                
                $db->setQuery("SELECT id, username, email, nombre, apellido FROM usuarios WHERE remember_token = :token AND activo = 1");
                $db->sacaDatos();
                $db->parametroString('token', $token_hash);
                $db->ejecuta();

                if ($db->conDatos()) {
                    $user = $db->datos()[0];
                    
                    // Restaurar sesión
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['nombre_completo'] = $user['nombre'] . ' ' . $user['apellido'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['login_time'] = time();

                    $response['logged_in'] = true;
                    $response['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'nombre_completo' => $user['nombre'] . ' ' . $user['apellido']
                    ];
                }
                $db->cerrar();
            }
        } catch (Exception $e) {
            error_log("Error verificando remember token: " . $e->getMessage());
        }
    }
}

echo json_encode($response);
?>
