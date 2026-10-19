<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

$titulo = 'Strelee · Streetwear segunda vida';
$pagina = 'inicio';
$fullBleed = true;
$categorias = $mysqli->query('SELECT id_categoria, nombre FROM categoria ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);
$destacadas = $mysqli->query(
    'SELECT p.*, c.nombre AS categoria_nombre FROM prenda p
     JOIN categoria c ON c.id_categoria = p.id_categoria
     WHERE p.disponible = 1
     ORDER BY p.precio DESC
     LIMIT 8'
)->fetch_all(MYSQLI_ASSOC);

require __DIR__ . '/includes/header.php';
?>
<section class="hero-bleed">
    <div class="hero-stamp stamp">DROP<br>01</div>
    <div class="hero-inner">
        <div class="hero-mark">
            <img src="img/logo.png" alt="Logo Strelee">
        </div>
        <h1>STRELEE</h1>
        <p class="tagline">Moda urbana con segunda vida. Piezas reutilizadas, unisex y a medida — sin carrito, directo contigo.</p>
        <div class="hero-actions">
            <a class="btn btn-lima" href="catalogo.php">Ver catálogo</a>
            <a class="btn btn-outline" href="personalizado.php">Quiero algo a medida</a>
        </div>
        <div class="chips chips-scroll">
            <?php foreach ($categorias as $c): ?>
                <a class="chip" href="catalogo.php?categoria=<?= (int)$c['id_categoria'] ?>"><?= e($c['nombre']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="contenedor" style="padding-top:0">
    <div class="drop-bar">
        <div>
            <h2>Drop actual</h2>
            <p class="meta">Prendas disponibles · una pieza, un pedido</p>
        </div>
        <a class="btn btn-outline btn-sm" href="catalogo.php">Ver todo</a>
    </div>

    <div class="grid">
    <?php foreach ($destacadas as $p): ?>
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
                    <a class="btn btn-lima btn-sm" href="prenda.php?id=<?= (int)$p['id_prenda'] ?>">Ver / pedir</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
    </div>

    <div class="cta-banda">
        <div>
            <h3>¿No está en el rack?</h3>
            <p>Describe tu hoodie, cargo o bomber ideal. Lo marcamos como personalizado y te respondemos por WhatsApp.</p>
        </div>
        <a class="btn btn-lima" href="personalizado.php">Pedido a medida</a>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
