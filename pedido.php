<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

$id = (int)($_GET['id'] ?? $_POST['id_prenda'] ?? 0);
$stmt = $mysqli->prepare(
    'SELECT p.*, c.nombre AS categoria_nombre FROM prenda p
     JOIN categoria c ON c.id_categoria = p.id_categoria
     WHERE p.id_prenda = ? AND p.disponible = 1'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$prenda = $stmt->get_result()->fetch_assoc();
if (!$prenda) {
    guardarMensaje('Prenda no disponible.');
    header('Location: catalogo.php');
    exit;
}

$titulo = 'Pedir · Strelee';
$pagina = 'catalogo';
$error = '';

// Éxito vía PRG (evita pedidos duplicados al refrescar)
if (isset($_GET['ok']) && !empty($_SESSION['pedido_exito']) && (int)($_SESSION['pedido_exito']['id_prenda'] ?? 0) === $id) {
    $exito = $_SESSION['pedido_exito'];
    unset($_SESSION['pedido_exito']);
    require __DIR__ . '/includes/header.php';
    ?>
    <div class="exito-box">
        <h2>Pedido enviado</h2>
        <p>Registramos tu pedido de <strong><?= e($exito['nombre_prenda']) ?></strong> (tipo <span class="badge badge-disponible">disponible</span>).</p>
        <div class="acciones" style="margin-top:1.25rem">
            <a class="btn btn-wa" href="<?= e($exito['wa']) ?>" target="_blank" rel="noopener">Confirmar por WhatsApp</a>
            <a class="btn btn-lima" href="catalogo.php">Seguir mirando</a>
        </div>
    </div>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_cliente'] ?? '');
    $telefono = trim($_POST['telefono_cliente'] ?? '');
    $notas = trim($_POST['descripcion_solicitud'] ?? '');
    if ($nombre === '' || $telefono === '') {
        $error = 'Nombre y teléfono son obligatorios.';
    } else {
        $tipo = 'disponible';
        $fecha = date('Y-m-d');
        $stmt = $mysqli->prepare(
            'INSERT INTO pedido (nombre_cliente, telefono_cliente, descripcion_solicitud, tipo, fecha, id_prenda)
             VALUES (?,?,?,?,?,?)'
        );
        $stmt->bind_param('sssssi', $nombre, $telefono, $notas, $tipo, $fecha, $id);
        $stmt->execute();
        $_SESSION['pedido_exito'] = [
            'id_prenda' => $id,
            'nombre_prenda' => $prenda['nombre'],
            'wa' => whatsappUrl("Hola Strelee, pedí la prenda {$prenda['nombre']} (talla {$prenda['talla']}). Soy {$nombre}, tel {$telefono}."),
        ];
        header('Location: pedido.php?id=' . $id . '&ok=1');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>
<a class="volver" href="prenda.php?id=<?= (int)$prenda['id_prenda'] ?>">‹ Volver a la prenda</a>
<section class="pedido-layout">
<section class="seccion-titulo">
    <h2>Confirmar pedido</h2>
    <p class="meta">Prenda del catálogo · tipo disponible</p>
</section>

<div class="pedido-resumen">
    <img src="<?= e(fotoSrc($prenda['foto_url'])) ?>" alt="">
    <div>
        <strong><?= e($prenda['nombre']) ?></strong>
        <p class="meta"><?= e($prenda['categoria_nombre']) ?> · Talla <?= e($prenda['talla']) ?></p>
        <p class="precio"><?= e(formatearPrecio($prenda['precio'])) ?></p>
    </div>
</div>

<?php if ($error): ?><p class="mensaje error"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form">
    <input type="hidden" name="id_prenda" value="<?= (int)$prenda['id_prenda'] ?>">
    <label>Nombre <input name="nombre_cliente" required autocomplete="name" value="<?= e($_POST['nombre_cliente'] ?? '') ?>"></label>
    <label>Teléfono / WhatsApp <input name="telefono_cliente" required autocomplete="tel" value="<?= e($_POST['telefono_cliente'] ?? '') ?>"></label>
    <label>Nota (opcional) <textarea name="descripcion_solicitud" rows="3" placeholder="Punto de encuentro, horario…"><?= e($_POST['descripcion_solicitud'] ?? '') ?></textarea></label>
    <button class="btn btn-lima" type="submit">Confirmar pedido</button>
    <a href="prenda.php?id=<?= (int)$prenda['id_prenda'] ?>" style="color:var(--gris)">Cancelar</a>
</form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
