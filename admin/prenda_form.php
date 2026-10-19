<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
requiereAdmin();

$titulo = 'Formulario prenda · Strelee';
$paginaAdmin = 'prendas';
$error = '';
$id = (int)($_GET['id'] ?? $_POST['id_prenda'] ?? 0);
$prenda = null;
if ($id) {
    $stmt = $mysqli->prepare('SELECT * FROM prenda WHERE id_prenda=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $prenda = $stmt->get_result()->fetch_assoc();
}
$cats = $mysqli->query('SELECT * FROM categoria ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $talla = trim($_POST['talla'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    $foto = trim($_POST['foto_url'] ?? '');
    $idCat = (int)($_POST['id_categoria'] ?? 0);
    $id = (int)($_POST['id_prenda'] ?? 0);
    if ($nombre === '' || $talla === '' || !$idCat) {
        $error = 'Nombre, talla y categoría obligatorios.';
    } elseif ($id > 0) {
        $stmt = $mysqli->prepare('UPDATE prenda SET nombre=?, talla=?, precio=?, descripcion=?, disponible=?, foto_url=?, id_categoria=? WHERE id_prenda=?');
        $stmt->bind_param('ssdsisii', $nombre, $talla, $precio, $descripcion, $disponible, $foto, $idCat, $id);
        $stmt->execute();
        guardarMensaje('Prenda actualizada.');
        header('Location: prendas.php');
        exit;
    } else {
        $stmt = $mysqli->prepare('INSERT INTO prenda (nombre, talla, precio, descripcion, disponible, foto_url, id_categoria) VALUES (?,?,?,?,?,?,?)');
        $stmt->bind_param('ssdsisi', $nombre, $talla, $precio, $descripcion, $disponible, $foto, $idCat);
        $stmt->execute();
        guardarMensaje('Prenda creada.');
        header('Location: prendas.php');
        exit;
    }
    $prenda = compact('nombre', 'talla', 'precio', 'descripcion', 'disponible', 'foto') + ['foto_url' => $foto, 'id_categoria' => $idCat, 'id_prenda' => $id];
}

require __DIR__ . '/../includes/header_admin.php';
?>
<p><a href="prendas.php" style="color:var(--lima)">‹ Volver</a></p>
<?php if ($error): ?><p class="mensaje error"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form">
    <h2><?= $prenda && !empty($prenda['id_prenda']) ? 'Editar' : 'Nueva' ?> prenda</h2>
    <?php if ($prenda && !empty($prenda['id_prenda'])): ?>
        <input type="hidden" name="id_prenda" value="<?= (int)$prenda['id_prenda'] ?>">
    <?php endif; ?>
    <label>Nombre <input name="nombre" required value="<?= e($prenda['nombre'] ?? '') ?>"></label>
    <label>Talla <input name="talla" required value="<?= e($prenda['talla'] ?? '') ?>"></label>
    <label>Precio <input type="number" step="100" min="0" name="precio" required value="<?= e((string)($prenda['precio'] ?? '0')) ?>"></label>
    <label>Descripción <textarea name="descripcion" rows="3"><?= e($prenda['descripcion'] ?? '') ?></textarea></label>
    <label>Foto (ruta relativa o URL) <input name="foto_url" value="<?= e($prenda['foto_url'] ?? '') ?>" placeholder="img/hoodie-negro.jpg"></label>
    <?php if (!empty($prenda['foto_url'])): ?>
        <img class="thumb" src="<?= e(fotoSrc($prenda['foto_url'], true)) ?>" alt="" style="width:120px;height:150px">
    <?php endif; ?>
    <label>Categoría
        <select name="id_categoria" required>
            <option value="">Seleccionar</option>
            <?php foreach ($cats as $c): ?>
                <option value="<?= (int)$c['id_categoria'] ?>" <?= ((int)($prenda['id_categoria'] ?? 0) === (int)$c['id_categoria']) ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label class="check-row"><input type="checkbox" name="disponible" <?= !isset($prenda) || (int)($prenda['disponible'] ?? 1) === 1 ? 'checked' : '' ?>> Disponible</label>
    <button class="btn btn-lima" type="submit">Guardar</button>
</form>
<?php require __DIR__ . '/../includes/footer_admin.php'; ?>
