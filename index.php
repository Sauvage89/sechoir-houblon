<?php
$PAGE_ACCUEIL = "accueil";
$PAGE_GESTION = "gestion_sechoir";
$pages_autorisees = [$PAGE_ACCUEIL , $PAGE_GESTION];
$page = $_GET['page'] ?? $PAGE_ACCUEIL;
if (!in_array($page, $pages_autorisees)) {
  header("Location: ?page=" . $PAGE_ACCUEIL);
  exit;
}

$titles = [
	$PAGE_ACCUEIL 	=>	"Accueil",
	$PAGE_GESTION	=>	"Gestion du séchoir",
	"404"		=>	"Erreur 404"
];
$title = $titles[$page] ?? "404";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?> - Séchoir Houblon</title>
  <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/style_adam.css">
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>
  <header>
    <div class="header-left">
      <div class="logo">
        <div class="logo-mark">H</div>
      </div>
      <div class="title">Séchoir Houblon</div>
    </div>
  </header>

  <nav id="main-nav">
    <a href="?page=<?= $PAGE_ACCUEIL ?>" class="<?= $page === $PAGE_ACCUEIL ? "active" : "" ?>">Accueil</a>
    <a href="?page=<?= $PAGE_GESTION ?>" class="<?= $page === $PAGE_GESTION ? "active" : "" ?>">Gestion séchoir</a>
  </nav>

  <main id="main-content" class="container-fluid px-3 px-md-4 py-3">
    <?php 
    $file = "site/{$page}.php";

    if (!file_exists($file)) {
    $file = "site/404.php";
    }
    include $file;
    ?>
  </main>

  <footer>
    <p>2026 - Séchoir Houblon</p>
  </footer>
  <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
