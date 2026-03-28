<?php

$page = $_GET['page'] ?? 'gestion_sechoir';
$pages_autorisees = ["gestion_sechoir", "nouvelle_seche", "visualisation"];
if (!in_array($page, $pages_autorisees)) $page = "404";

$titles = [
    "gestion_sechoir" => "Gestion séchoir",
    "nouvelle_seche"  => "Nouvelle session",
    "visualisation"   => "Visualisation",
    "404"             => "Erreur 404"
];
$title = $titles[$page];

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include "site/{$page}.php";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?> — Séchoir Houblon</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>

  <header>
    <span class="header-left">
      <div class="logo"><div class="logo-mark">H</div></div>
      <div class="title">Séchoir Houblon</div>
    </span>
    <span class="header-right">Raspberry Pi 5</span>
  </header>

  <nav id="main-nav">
    <a href="?page=gestion_sechoir" class="<?= $page === 'gestion_sechoir' ? 'active' : '' ?>">Gestion d'un séchoir</a>
    <a href="?page=nouvelle_seche"  class="<?= $page === 'nouvelle_seche'  ? 'active' : '' ?>">Nouvelle session</a>
    <a href="?page=visualisation"   class="<?= $page === 'visualisation'   ? 'active' : '' ?>">Visualisation</a>
  </nav>

  <main id="main-content">
    <?php include "site/{$page}.php"; ?>
  </main>

  <footer>
    <p>© 2026 — Séchoir Houblon</p>
  </footer>

  <script>
    const nav     = document.getElementById('main-nav');
    const content = document.getElementById('main-content');

    nav.addEventListener('click', async (e) => {
      const link = e.target.closest('a');
      if (!link) return;
      e.preventDefault();

      const page = new URL(link.href).searchParams.get('page');

      nav.querySelectorAll('a').forEach(a => a.classList.remove('active'));
      link.classList.add('active');

      history.pushState({ page }, '', link.href);

      const res  = await fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      content.innerHTML = await res.text();
    });

    window.addEventListener('popstate', async (e) => {
      const page = e.state?.page ?? 'gestion_sechoir';
      const res  = await fetch(`?page=${page}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      content.innerHTML = await res.text();

      nav.querySelectorAll('a').forEach(a => {
        a.classList.toggle('active', new URL(a.href).searchParams.get('page') === page);
      });
    });
  </script>

</body>
</html>