<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
requiereAdmin();

$titulo = 'Prendas · Strelee';
$paginaAdmin = 'prendas';

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $mysqli->query("UPDATE prenda SET disponible = IF(disponible=1,0,1) WHERE id_prenda = $id");
    guardarMensaje('Disponibilidad actualizada.');
    header('Location: prendas.php');
    exit;
}

if (($_POST['accion'] ?? '') === 'eliminar') {
    $id = (int)($_POST['id_prenda'] ?? 0);
    try {
        $stmt = $mysqli->prepare('DELETE FROM prenda WHERE id_prenda=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        guardarMensaje('Prenda eliminada.');
    } catch (Throwable $e) {
        guardarMensaje('No se pudo eliminar (tiene pedidos).');
    }
    header('Location: prendas.php');
    exit;
}

$prendas = $mysqli->query(
    'SELECT p.*, c.nombre AS categoria_nombre FROM prenda p
     JOIN categoria c ON c.id_categoria = p.id_categoria ORDER BY p.nombre'
)->fetch_all(MYSQLI_ASSOC);

require __DIR__ . '/../includes/header_admin.php';
?>
<div class="toolbar">
    <h2>Gestión de prendas</h2>
    <a class="btn btn-lima" href="prenda_form.php">+ Nuevo</a>
</div>
<div class="tabla-wrap">
<table>
    <thead><tr><th></th><th>Nombre</th><th>Talla</th><th>Categoría</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($prendas as $p): ?>
        <tr>
            <td><img class="thumb" src="<?= e(fotoSrc($p['foto_url'], true)) ?>" alt=""></td>
            <td data-label="Nombre"><?= e($p['nombre']) ?></td>
            <td data-label="Talla"><?= e($p['talla']) ?></td>
            <td data-label="Categoría"><?= e($p['categoria_nombre']) ?></td>
            <td data-label="Precio"><?= e(formatearPrecio($p['precio'])) ?></td>
            <td data-label="Estado"><a href="?toggle=<?= (int)$p['id_prenda'] ?>" style="color:var(--lima)"><?= (int)$p['disponible'] ? 'Disponible' : 'No' ?></a></td>
            <td class="acciones" data-label="Acciones">
                <a class="btn btn-lima btn-sm" href="prenda_form.php?id=<?= (int)$p['id_prenda'] ?>">Editar</a>
                <form method="post" onsubmit="return confirm('¿Eliminar?')">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_prenda" value="<?= (int)$p['id_prenda'] ?>">
                    <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../includes/footer_admin.php'; ?>
