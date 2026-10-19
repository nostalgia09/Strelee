<?php
$mensaje = $mensaje ?? '';
if ($mensaje === '') {
    $mensaje = obtenerMensaje();
}
$pagina = $pagina ?? '';
$fullBleed = !empty($fullBleed);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($titulo ?? 'Strelee') ?></title>
    <meta name="description" content="Strelee — moda urbana con segunda vida. Catálogo second-hand y pedidos a medida.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
<header class="site-header">
    <nav class="nav" aria-label="Principal">
        <a href="index.php" class="brand">
            <img src="img/logo.png" alt="Strelee" class="logo">
            <span>STRELEE</span>
        </a>
        <button type="button" class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Abrir menú">
            <span class="nav-toggle-bar" aria-hidden="true"></span>
            <span class="nav-toggle-bar" aria-hidden="true"></span>
            <span class="nav-toggle-bar" aria-hidden="true"></span>
        </button>
        <div class="nav-links" id="nav-menu">
            <a href="index.php" class="<?= $pagina === 'inicio' ? 'activo' : '' ?>">Inicio</a>
            <a href="catalogo.php" class="<?= $pagina === 'catalogo' ? 'activo' : '' ?>">Catálogo</a>
            <a href="personalizado.php" class="<?= $pagina === 'personalizado' ? 'activo' : '' ?>">A medida</a>
            <a href="admin/login.php">Admin</a>
        </div>
    </nav>
</header>
<?php if ($mensaje !== ''): ?>
    <p class="mensaje ok"><?= e($mensaje) ?></p>
<?php endif; ?>
<main<?= $fullBleed ? '' : ' class="contenedor"' ?>>
