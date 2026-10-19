<?php
$mensaje = $mensaje ?? '';
if ($mensaje === '') {
    $mensaje = obtenerMensaje();
}
$paginaAdmin = $paginaAdmin ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($titulo ?? 'Panel Strelee') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="icon" href="../img/logo.png" type="image/png">
</head>
<body class="admin">
<header class="admin-header">
    <nav class="nav nav-admin" aria-label="Admin">
        <a href="index.php" class="brand brand-admin">
            <img src="../img/logo.png" alt="Strelee" class="logo">
            <span>STRELEE</span>
        </a>
        <button type="button" class="nav-toggle" aria-expanded="false" aria-controls="nav-menu-admin" aria-label="Abrir menú">
            <span class="nav-toggle-bar" aria-hidden="true"></span>
            <span class="nav-toggle-bar" aria-hidden="true"></span>
            <span class="nav-toggle-bar" aria-hidden="true"></span>
        </button>
        <div class="nav-links" id="nav-menu-admin">
            <a href="index.php" class="<?= $paginaAdmin === 'panel' ? 'activo' : '' ?>">Panel</a>
            <a href="categorias.php" class="<?= $paginaAdmin === 'categorias' ? 'activo' : '' ?>">Categorías</a>
            <a href="prendas.php" class="<?= $paginaAdmin === 'prendas' ? 'activo' : '' ?>">Prendas</a>
            <a href="pedidos.php" class="<?= $paginaAdmin === 'pedidos' ? 'activo' : '' ?>">Pedidos</a>
            <a href="logout.php" class="salir">Cerrar sesión</a>
        </div>
    </nav>
</header>
<?php if ($mensaje !== ''): ?>
    <p class="mensaje ok"><?= e($mensaje) ?></p>
<?php endif; ?>
<main class="contenedor">
