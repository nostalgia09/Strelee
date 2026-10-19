# Strelee

Vitrina streetwear de ropa urbana reutilizada y pedidos a medida (Gimnasio Cáceres · ficha 3260316).

## Instalación

1. Clona este repositorio dentro de la carpeta `htdocs` de XAMPP (queda la carpeta `Strelee`).
2. Enciende Apache y MySQL en XAMPP.
3. Importa la base de datos (ver abajo).

## URL local

- Público: http://localhost/Strelee/
- Catálogo: http://localhost/Strelee/catalogo.php
- A medida: http://localhost/Strelee/personalizado.php
- Admin: http://localhost/Strelee/admin/login.php

## Credenciales demo

- Correo: `admin@demo.com`
- Contraseña: `Admin123!`

## Importar base de datos

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root < /Applications/XAMPP/xamppfiles/htdocs/Strelee/bd/strelee.sql
```

O importa `bd/strelee.sql` desde phpMyAdmin.

## Flujo UX

1. Catálogo (filtros por categoría) → detalle de prenda → pedido tipo **disponible**
2. O “A medida” → solicitud tipo **personalizado**
3. Admin → bandeja de pedidos con pestañas Todos / Disponible / Personalizado

## Stack

PHP + mysqli + CSS propio + sesiones. Logo oficial en `img/logo.png`. Fotos locales en `img/`. Sin Supabase, sin carrito, sin editor visual.
