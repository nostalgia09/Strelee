<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
requiereAdmin();

$titulo = 'Panel · Strelee';
$paginaAdmin = 'panel';
$totales = [
    'prendas' => (int)$mysqli->query('SELECT COUNT(*) c FROM prenda')->fetch_assoc()['c'],
    'rack' => (int)$mysqli->query('SELECT COUNT(*) c FROM prenda WHERE disponible=1')->fetch_assoc()['c'],
    'disp' => (int)$mysqli->query("SELECT COUNT(*) c FROM pedido WHERE tipo='disponible'")->fetch_assoc()['c'],
    'pers' => (int)$mysqli->query("SELECT COUNT(*) c FROM pedido WHERE tipo='personalizado'")->fetch_assoc()['c'],
];

require __DIR__ . '/../includes/header_admin.php';
?>
<section class="seccion-titulo">
    <h2>Panel</h2>
    <p class="meta">Hola, <?= e($_SESSION['admin']['nombre'] ?? 'admin') ?></p>
</section>
<div class="metricas">
    <div class="metrica"><div class="label">Prendas total</div><div class="valor"><?= $totales['prendas'] ?></div></div>
    <div class="metrica"><div class="label">En rack</div><div class="valor"><?= $totales['rack'] ?></div></div>
    <div class="metrica"><div class="label">Pedidos disponible</div><div class="valor"><?= $totales['disp'] ?></div></div>
    <div class="metrica"><div class="label">Pedidos a medida</div><div class="valor"><?= $totales['pers'] ?></div></div>
</div>
<div class="acciones">
    <a class="btn btn-lima" href="prendas.php">Gestionar prendas</a>
    <a class="btn btn-outline" href="pedidos.php?tipo=disponible">Bandeja disponible</a>
    <a class="btn btn-outline" href="pedidos.php?tipo=personalizado">Bandeja personalizado</a>
</div>
<?php require __DIR__ . '/../includes/footer_admin.php'; ?>
