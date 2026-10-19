<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';

if (adminLogueado()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';
    if ($correo === '' || $clave === '') {
        $error = 'Correo y contraseña obligatorios.';
    } else {
        $stmt = $mysqli->prepare('SELECT id_admin, nombre, correo, password_hash FROM administrador WHERE correo = ? LIMIT 1');
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        if (!$admin || !password_verify($clave, $admin['password_hash'])) {
            $error = 'Credenciales incorrectas.';
        } else {
            session_regenerate_id(true);
            $_SESSION['admin'] = [
                'id' => (int)$admin['id_admin'],
                'nombre' => $admin['nombre'],
                'correo' => $admin['correo'],
            ];
            guardarMensaje('Bienvenido/a, ' . $admin['nombre']);
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login · Strelee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="icon" href="../img/logo.png" type="image/png">
</head>
<body>
<div class="login-box">
    <div class="login-brand"><img src="../img/logo.png" alt="Strelee"></div>
    <h1>STRELEE</h1>
    <p class="sub">Acceso administrador</p>
    <?php if ($error): ?><p class="mensaje error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" class="form" style="border:none;padding:0;max-width:none;background:transparent;border-left:none">
        <label>Correo <input type="email" name="correo" required value="<?= e($_POST['correo'] ?? 'admin@demo.com') ?>"></label>
        <label>Contraseña <input type="password" name="clave" required></label>
        <button class="btn btn-lima" type="submit">Ingresar</button>
        <p class="meta">Demo: admin@demo.com / Admin123!</p>
        <p><a href="../index.php">← Volver a la vitrina</a></p>
    </form>
</div>
</body>
</html>
