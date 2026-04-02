<?php
$PAGE_ACCUEIL = "acceuil";
$PAGE_GESTION = "gestion_sechoir";
$pages_autorisees = [$PAGE_ACCUEIL , $PAGE_GESTION];
$page = $_GET['page'] ?? $PAGE_ACCEUIL;
if (!in_array($page, $pages_autorisees)) {
	header("Location: ?page=acceuil");
	exit;
}

$titles = [
	$PAGE_ACCUEIL 	=>	"Acceuil",
	$PAGE_GESTION	=>	"Gestion du séchoir",
	"404"		=>	"Erreur 404"
];
$title = $titles[$page];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?> - Séchoir Houblon</title>
  <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/style_adam.css">
</head>

<body>
  <header>
    <span class="header-left">
      <div class="logo"><div class="logo-mark">H</div></div>
      <div class="title">Séchoir Houblon</div>
    </span>
  </header>

  <nav id="main-nav">
    <a href="?page=<?= $PAGE_ACCUEIL ?>" class="<?= $page === $PAGE_ACCEUIL ? "active" : "" ?>">Acceuil</a>
    <a href="?page=<?= $PAGE_GESTION ?>" class="<?= $page === $PAGE_GESTION ? "active" : "" ?>">Gestion séchoir</a>
  </nav>

  <main id="main-content" class="container-fluid px-3 px-md-4 py-3">
    <?php include "site/{$page}.php"; ?>
  </main>

  <footer>
    <p>2026 - Séchoir Houblon</p>
  </footer>
</body>
</html>
