<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

$titulo = 'Catálogo · Strelee';
$pagina = 'catalogo';
$categorias = $mysqli->query('SELECT id_categoria, nombre FROM categoria ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);
$idCat = (int)($_GET['categoria'] ?? 0);
$q = trim($_GET['q'] ?? '');

$sql = 'SELECT p.*, c.nombre AS categoria_nombre FROM prenda p
        JOIN categoria c ON c.id_categoria = p.id_categoria
        WHERE p.disponible = 1';
$types = '';
$params = [];
if ($idCat > 0) {
    $sql .= ' AND p.id_categoria = ?';
    $types .= 'i';
    $params[] = $idCat;
}
if ($q !== '') {
    $sql .= ' AND (p.nombre LIKE ? OR p.descripcion LIKE ?)';
    $types .= 'ss';
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
}
$sql .= ' ORDER BY p.nombre';
$stmt = $mysqli->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$prendas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

require __DIR__ . '/includes/header.php';
?>
<section class="seccion-titulo">
    <h2>Catálogo</h2>
    <p class="meta"><?= count($prendas) ?> pieza<?= count($prendas) === 1 ? '' : 's' ?> en rack · elige → detalle → pedido</p>
</section>

<div class="chips chips-scroll" style="margin-top:0;margin-bottom:1rem">
    <a class="chip <?= $idCat === 0 ? 'activo' : '' ?>" href="catalogo.php<?= $q !== '' ? '?q=' . urlencode($q) : '' ?>">Todas</a>
    <?php foreach ($categorias as $c): ?>
        <a class="chip <?= $idCat === (int)$c['id_categoria'] ? 'activo' : '' ?>"
           href="catalogo.php?categoria=<?= (int)$c['id_categoria'] ?><?= $q !== '' ? '&q=' . urlencode($q) : '' ?>">
            <?= e($c['nombre']) ?>
        </a>
    <?php endforeach; ?>
</div>

<form class="filtros" method="get">
    <?php if ($idCat > 0): ?><input type="hidden" name="categoria" value="<?= $idCat ?>"><?php endif; ?>
    <label>Buscar <input type="search" name="q" value="<?= e($q) ?>" placeholder="Jean, hoodie, cargo…"></label>
    <button class="btn btn-lima btn-sm" type="submit">Filtrar</button>
    <?php if ($q !== '' || $idCat > 0): ?>
        <a class="btn btn-ghost btn-sm" href="catalogo.php">Limpiar</a>
    <?php endif; ?>
</form>

<?php if ($prendas): ?>
<div class="grid">
<?php foreach ($prendas as $p): ?>
    <article class="card">
        <a class="card-media" href="prenda.php?id=<?= (int)$p['id_prenda'] ?>">
            <span class="card-tag"><?= e($p['categoria_nombre']) ?></span>
            <img src="<?= e(fotoSrc($p['foto_url'])) ?>" alt="<?= e($p['nombre']) ?>" loading="lazy">
        </a>
        <div class="card-body">
            <h3><?= e($p['nombre']) ?></h3>
            <p class="meta">Talla <?= e($p['talla']) ?></p>
            <p class="precio"><?= e(formatearPrecio($p['precio'])) ?></p>
            <div class="acciones" style="margin-top:0.75rem">
                <a class="btn btn-lima btn-sm" href="prenda.php?id=<?= (int)$p['id_prenda'] ?>">Ver detalle</a>
                <a class="btn btn-wa btn-sm" target="_blank" rel="noopener"
                   href="<?= e(whatsappUrl('Hola Strelee, me interesa ' . $p['nombre'] . ' talla ' . $p['talla'] . '.')) ?>">WA</a>
            </div>
        </div>
    </article>
<?php endforeach; ?>
</div>
<?php else: ?>
    <div class="empty">
        <p>No hay prendas con ese filtro.</p>
        <p style="margin-top:0.75rem"><a class="btn btn-outline btn-sm" href="personalizado.php">Pedir a medida</a></p>
    </div>
<?php endif; ?>

<div class="cta-banda">
    <div>
        <h3>Pieza a tu medida</h3>
        <p>Si el drop no trae lo que buscas, arma un pedido personalizado.</p>
    </div>
    <a class="btn btn-lima" href="personalizado.php">A medida</a>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
