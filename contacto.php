<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Configura el destinatario
    $destinatario = "javier.espinoza.y25@gmail.com"; // Cambiar correo a green valley 
    $asunto = "Consulta desde el sitio web";

    // Construye el cuerpo del mensaje
    $contenido = "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Mensaje:\n$mensaje\n";

    // Encabezados
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Envía el correo
    if (mail($destinatario, $asunto, $contenido, $headers)) {
        echo "<script>alert('¡Gracias por tu consulta! Nos pondremos en contacto contigo pronto.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Hubo un error al enviar el mensaje. Intenta nuevamente.'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
