<?php
function e($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

function adminLogueado() {
    return isset($_SESSION['admin']);
}

function requiereAdmin() {
    if (!adminLogueado()) {
        header('Location: login.php');
        exit;
    }
}

function guardarMensaje($texto) {
    $_SESSION['mensaje'] = $texto;
}

function obtenerMensaje() {
    $mensaje = $_SESSION['mensaje'] ?? '';
    unset($_SESSION['mensaje']);
    return $mensaje;
}

function formatearPrecio($precio) {
    return '$' . number_format((float)$precio, 0, ',', '.');
}

const WHATSAPP_STRELEE = '573001112233';

function whatsappUrl($mensaje) {
    return 'https://wa.me/' . WHATSAPP_STRELEE . '?text=' . rawurlencode($mensaje);
}

/** Ruta de foto relativa a la raíz del sitio (ej: img/hoodie.jpg) o URL absoluta. */
function fotoSrc($url, $desdeAdmin = false) {
    $url = trim((string)$url);
    if ($url === '') {
        return ($desdeAdmin ? '../' : '') . 'img/logo.png';
    }
    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }
    $rel = ltrim($url, '/');
    return ($desdeAdmin ? '../' : '') . $rel;
}
