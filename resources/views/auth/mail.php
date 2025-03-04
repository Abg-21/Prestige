<!DOCTYPE html>
<html>
<head>
    <title>Enviar Correo</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <button type="submit">Enviar Correo</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $to = "helpdesk@grupoprestige.com.mx";
        $subject = "Asunto del correo desde el formulario";
        $message = "Este es el cuerpo del mensaje enviado desde el formulario";
        $headers = "From: abigail.garcia1942@gmail.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<p>El correo electrónico se ha enviado correctamente.</p>";
        } else {
            echo "<p>Hubo un error al enviar el correo electrónico.</p>";
        }
    }
    ?>
</body>
</html>