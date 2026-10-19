<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare(
    'SELECT p.*, c.nombre AS categoria_nombre FROM prenda p
     JOIN categoria c ON c.id_categoria = p.id_categoria
     WHERE p.id_prenda = ? AND p.disponible = 1'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$prenda = $stmt->get_result()->fetch_assoc();
if (!$prenda) {
    guardarMensaje('Prenda no encontrada o no disponible.');
    header('Location: catalogo.php');
    exit;
}

$titulo = $prenda['nombre'] . ' · Strelee';
$pagina = 'catalogo';
require __DIR__ . '/includes/header.php';
?>
<a class="volver" href="catalogo.php">‹ Volver al catálogo</a>
<section class="detalle">
    <div class="detalle-foto">
        <img src="<?= e(fotoSrc($prenda['foto_url'])) ?>" alt="<?= e($prenda['nombre']) ?>">
    </div>
    <div class="detalle-info">
        <p class="meta"><?= e($prenda['categoria_nombre']) ?> · segunda vida</p>
        <h1><?= e($prenda['nombre']) ?></h1>
        <p class="precio"><?= e(formatearPrecio($prenda['precio'])) ?></p>
        <p class="meta">Talla</p>
        <span class="talla-pill"><?= e($prenda['talla']) ?></span>
        <p class="detalle-desc"><?= e($prenda['descripcion'] ?? '') ?></p>
        <div class="acciones">
            <a class="btn btn-lima" href="pedido.php?id=<?= (int)$prenda['id_prenda'] ?>">Pedir esta prenda</a>
            <a class="btn btn-wa" target="_blank" rel="noopener"
               href="<?= e(whatsappUrl('Hola Strelee, quiero la prenda ' . $prenda['nombre'] . ' talla ' . $prenda['talla'] . '.')) ?>">WhatsApp</a>
            <a class="btn btn-outline" href="personalizado.php">Prefiero a medida</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
