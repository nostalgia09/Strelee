<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';
require_once __DIR__ . '/../includes/funciones.php';
requiereAdmin();

$titulo = 'Categorías · Strelee';
$paginaAdmin = 'categorias';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $id = (int)($_POST['id_categoria'] ?? 0);
    if ($accion === 'crear' || $accion === 'editar') {
        if ($nombre === '') {
            $error = 'Nombre obligatorio.';
        } elseif ($accion === 'crear') {
            $stmt = $mysqli->prepare('INSERT INTO categoria (nombre) VALUES (?)');
            $stmt->bind_param('s', $nombre);
            $stmt->execute();
            guardarMensaje('Categoría creada.');
            header('Location: categorias.php');
            exit;
        } else {
            $stmt = $mysqli->prepare('UPDATE categoria SET nombre=? WHERE id_categoria=?');
            $stmt->bind_param('si', $nombre, $id);
            $stmt->execute();
            guardarMensaje('Categoría actualizada.');
            header('Location: categorias.php');
            exit;
        }
    }
    if ($accion === 'eliminar') {
        try {
            $stmt = $mysqli->prepare('DELETE FROM categoria WHERE id_categoria=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            guardarMensaje('Categoría eliminada.');
            header('Location: categorias.php');
            exit;
        } catch (Throwable $e) {
            $error = 'No se puede eliminar: tiene prendas asociadas.';
        }
    }
}

$editar = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $stmt = $mysqli->prepare('SELECT * FROM categoria WHERE id_categoria=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $editar = $stmt->get_result()->fetch_assoc();
}

$cats = $mysqli->query('SELECT * FROM categoria ORDER BY nombre')->fetch_all(MYSQLI_ASSOC);
require __DIR__ . '/../includes/header_admin.php';
?>
<div class="toolbar"><h2>Categorías</h2></div>
<?php if ($error): ?><p class="mensaje error"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form" style="margin-bottom:1.5rem">
    <input type="hidden" name="accion" value="<?= $editar ? 'editar' : 'crear' ?>">
    <?php if ($editar): ?><input type="hidden" name="id_categoria" value="<?= (int)$editar['id_categoria'] ?>"><?php endif; ?>
    <label>Nombre <input name="nombre" required value="<?= e($editar['nombre'] ?? '') ?>"></label>
    <button class="btn btn-lima" type="submit">Guardar</button>
    <?php if ($editar): ?><a href="categorias.php" style="color:var(--lima)">Cancelar</a><?php endif; ?>
</form>
<div class="tabla-wrap">
<table>
    <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($cats as $c): ?>
        <tr>
            <td data-label="Nombre"><?= e($c['nombre']) ?></td>
            <td class="acciones" data-label="Acciones">
                <a class="btn btn-lima btn-sm" href="?editar=<?= (int)$c['id_categoria'] ?>">Editar</a>
                <form method="post" onsubmit="return confirm('¿Eliminar?')">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_categoria" value="<?= (int)$c['id_categoria'] ?>">
                    <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../includes/footer_admin.php'; ?>
