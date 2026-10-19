</main>
<footer class="pie">
    <p><strong>STRELEE</strong> · Moda urbana con segunda vida · WhatsApp <?= e(WHATSAPP_STRELEE) ?></p>
    <p style="margin-top:0.35rem"><a href="catalogo.php">Catálogo</a> · <a href="personalizado.php">Pedido a medida</a></p>
</footer>
<script>
(function () {
  var header = document.querySelector('.site-header, .admin-header');
  if (!header) return;
  var btn = header.querySelector('.nav-toggle');
  var menu = header.querySelector('.nav-links');
  if (!btn || !menu) return;

  function setOpen(open) {
    header.classList.toggle('nav-open', open);
    menu.classList.toggle('is-open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    document.body.classList.toggle('nav-open', open);
  }

  btn.addEventListener('click', function () {
    setOpen(!menu.classList.contains('is-open'));
  });

  menu.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () { setOpen(false); });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });

  window.addEventListener('resize', function () {
    if (window.matchMedia('(min-width: 769px)').matches) setOpen(false);
  });
})();
</script>
</body>
</html>
