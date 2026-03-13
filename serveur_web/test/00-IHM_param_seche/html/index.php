<?php
  $page = $_GET['page'] ?? 'accueil';
  $pages_autorisees = ["accueil", "nouvelle_seche", "visualisation"];
  if (!in_array($page, $pages_autorisees)) $page = "404";

  $titles = [
    "accueil"        => "Accueil",
    "nouvelle_seche" => "Nouvelle session",
    "visualisation"  => "Visualisation",
    "404"            => "Erreur 404"
  ];
  $title = $titles[$page];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?> — Séchoir Houblon</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header>
    <span class="header-left">
      <div class="logo">
        <div class="logo-mark">H</div>
      </div>
      <div class="title">Séchoir Houblon</div>
    </span>
    <span class="header-right">Raspberry Pi 5</span>
  </header>

  <nav>
    <a href="index.php?page=accueil"
       class="<?= $page === 'accueil' ? 'active' : '' ?>">Accueil</a>
    <a href="index.php?page=nouvelle_seche"
       class="<?= $page === 'nouvelle_seche' ? 'active' : '' ?>">Nouvelle session</a>
    <a href="index.php?page=visualisation"
       class="<?= $page === 'visualisation' ? 'active' : '' ?>">Visualisation</a>
  </nav>

  <main>
    <div class="content">
      <?php include "site/" . $page . ".php"; ?>
    </div>
  </main>

  <footer>
    <p>© 2026 — Séchoir Houblon</p>
  </footer>

</body>
</html>