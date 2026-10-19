<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
requiereAdmin();

$titulo = 'Pedidos · Strelee';
$paginaAdmin = 'pedidos';
$tipo = trim($_GET['tipo'] ?? '');

$counts = [
    'todos' => (int)$mysqli->query('SELECT COUNT(*) c FROM pedido')->fetch_assoc()['c'],
    'disponible' => (int)$mysqli->query("SELECT COUNT(*) c FROM pedido WHERE tipo='disponible'")->fetch_assoc()['c'],
    'personalizado' => (int)$mysqli->query("SELECT COUNT(*) c FROM pedido WHERE tipo='personalizado'")->fetch_assoc()['c'],
];

$sql = 'SELECT pe.*, pr.nombre AS prenda_nombre, pr.foto_url, pr.talla AS prenda_talla
        FROM pedido pe
        LEFT JOIN prenda pr ON pr.id_prenda = pe.id_prenda
        WHERE 1=1';
if ($tipo === 'disponible' || $tipo === 'personalizado') {
    $sql .= ' AND pe.tipo = ?';
    $stmt = $mysqli->prepare($sql . ' ORDER BY pe.fecha DESC, pe.id_pedido DESC');
    $stmt->bind_param('s', $tipo);
    $stmt->execute();
    $pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $tipo = '';
    $pedidos = $mysqli->query($sql . ' ORDER BY pe.fecha DESC, pe.id_pedido DESC')->fetch_all(MYSQLI_ASSOC);
}

require __DIR__ . '/../includes/header_admin.php';
?>
<div class="toolbar">
    <h2>Bandeja de pedidos</h2>
</div>

<nav class="tabs" aria-label="Filtrar por tipo">
    <a href="pedidos.php" class="<?= $tipo === '' ? 'activo' : '' ?>">Todos <span class="count">(<?= $counts['todos'] ?>)</span></a>
    <a href="pedidos.php?tipo=disponible" class="<?= $tipo === 'disponible' ? 'activo' : '' ?>">Disponible <span class="count">(<?= $counts['disponible'] ?>)</span></a>
    <a href="pedidos.php?tipo=personalizado" class="<?= $tipo === 'personalizado' ? 'activo' : '' ?>">Personalizado <span class="count">(<?= $counts['personalizado'] ?>)</span></a>
</nav>

<div class="tabla-wrap">
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Teléfono</th>
            <th>Tipo</th>
            <th>Detalle</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pedidos as $p): ?>
        <tr>
            <td data-label="Fecha"><?= e($p['fecha']) ?></td>
            <td data-label="Cliente"><?= e($p['nombre_cliente']) ?></td>
            <td data-label="Teléfono">
                <a href="<?= e(whatsappUrl('Hola ' . $p['nombre_cliente'] . ', te escribe Strelee sobre tu pedido.')) ?>"
                   target="_blank" rel="noopener"><?= e($p['telefono_cliente']) ?></a>
            </td>
            <td data-label="Tipo"><span class="badge badge-<?= e($p['tipo']) ?>"><?= e($p['tipo']) ?></span></td>
            <td data-label="Detalle">
                <?php if ($p['tipo'] === 'disponible'): ?>
                    <div class="acciones">
                        <?php if (!empty($p['foto_url'])): ?>
                            <img class="thumb" src="<?= e(fotoSrc($p['foto_url'], true)) ?>" alt="">
                        <?php endif; ?>
                        <div>
                            <strong><?= e($p['prenda_nombre'] ?? '—') ?></strong>
                            <?php if (!empty($p['prenda_talla'])): ?>
                                <div class="meta">Talla <?= e($p['prenda_talla']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($p['descripcion_solicitud'])): ?>
                                <div class="meta"><?= e($p['descripcion_solicitud']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?= nl2br(e($p['descripcion_solicitud'] ?? '')) ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$pedidos): ?>
        <tr><td colspan="5">Sin pedidos en esta bandeja.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../includes/footer_admin.php'; ?>
