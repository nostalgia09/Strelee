<?php
session_start();
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

$titulo = 'Pedido a medida · Strelee';
$pagina = 'personalizado';
$error = '';
$exito = false;
$wa = '';

// Éxito vía PRG (evita solicitudes duplicadas al refrescar)
if (isset($_GET['ok']) && !empty($_SESSION['personalizado_exito'])) {
    $exito = true;
    $wa = $_SESSION['personalizado_exito']['wa'] ?? '';
    unset($_SESSION['personalizado_exito']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_cliente'] ?? '');
    $telefono = trim($_POST['telefono_cliente'] ?? '');
    $descripcion = trim($_POST['descripcion_solicitud'] ?? '');
    $talla = trim($_POST['talla'] ?? '');
    if ($nombre === '' || $telefono === '' || $descripcion === '') {
        $error = 'Nombre, teléfono y descripción son obligatorios.';
    } else {
        if ($talla !== '') {
            $descripcion .= "\nTalla deseada: " . $talla;
        }
        $tipo = 'personalizado';
        $fecha = date('Y-m-d');
        $stmt = $mysqli->prepare(
            'INSERT INTO pedido (nombre_cliente, telefono_cliente, descripcion_solicitud, tipo, fecha, id_prenda)
             VALUES (?,?,?,?,?,NULL)'
        );
        $stmt->bind_param('sssss', $nombre, $telefono, $descripcion, $tipo, $fecha);
        $stmt->execute();
        $_SESSION['personalizado_exito'] = [
            'wa' => whatsappUrl("Hola Strelee, quiero un pedido personalizado.\n{$descripcion}\nSoy {$nombre}, tel {$telefono}."),
        ];
        header('Location: personalizado.php?ok=1');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>
<section class="pedido-layout">
<section class="seccion-titulo">
    <h2>A medida</h2>
    <p class="meta">Sin editor visual: describes la pieza → queda en bandeja personalizado → lo cerramos por WhatsApp.</p>
</section>

<?php if ($error): ?><p class="mensaje error"><?= e($error) ?></p><?php endif; ?>

<?php if ($exito): ?>
    <div class="exito-box">
        <h2>Solicitud lista</h2>
        <p>Tu pedido quedó marcado como <span class="badge badge-personalizado">personalizado</span>.</p>
        <div class="acciones" style="margin-top:1.25rem">
            <a class="btn btn-wa" href="<?= e($wa) ?>" target="_blank" rel="noopener">Abrir WhatsApp</a>
            <a class="btn btn-lima" href="catalogo.php">Ver catálogo</a>
        </div>
    </div>
<?php else: ?>
<form method="post" class="form">
    <label>Nombre <input name="nombre_cliente" required autocomplete="name" value="<?= e($_POST['nombre_cliente'] ?? '') ?>"></label>
    <label>Teléfono / WhatsApp <input name="telefono_cliente" required autocomplete="tel" value="<?= e($_POST['telefono_cliente'] ?? '') ?>"></label>
    <label>Talla deseada <input name="talla" value="<?= e($_POST['talla'] ?? '') ?>" placeholder="S, M, L, XL…"></label>
    <label>Qué quieres (prenda, color, detalles)
        <textarea name="descripcion_solicitud" rows="5" required placeholder="Ej: hoodie negro oversize con logo lima bordado en pecho, cordones lima, talla M…"><?= e($_POST['descripcion_solicitud'] ?? '') ?></textarea>
    </label>
    <button class="btn btn-lima" type="submit">Enviar solicitud</button>
    <a href="catalogo.php" style="color:var(--gris)">Preferí del catálogo</a>
</form>
<?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
