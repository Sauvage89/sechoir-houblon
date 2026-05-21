<?php

// Variable en liant avec les différente type de page possible
$PAGE_ACCUEIL = "accueil";
$PAGE_GESTION = "gestion_sechoir";
$PAGE_EXPORT = "exportation_csv";
$PAGE_AJOUTMASSE = "ajout_masse";
$PAGE_ERREUR404 = "404";

$pages_autorisees = [$PAGE_ACCUEIL, $PAGE_GESTION, $PAGE_EXPORT, $PAGE_AJOUTMASSE, $PAGE_ERREUR404]; // Liste des type de page
$page = $_GET['page']; // Récupére le type de la page

// Si le chargement du site ne dispose pas de l'attribut "page" alors on recharge la page a l'acceuil
if (!in_array($page, $pages_autorisees)) {
  header("Location: ?page=" . $PAGE_ACCUEIL);
  exit;
}

// Titre associé au type de la page
$titles = [
	$PAGE_ACCUEIL => "Accueil",
	$PAGE_GESTION => "Gestion du séchoir",
	$PAGE_EXPORT => "Exportation CSV",
	$PAGE_AJOUTMASSE => "Ajout Masse",
	$PAGE_ERREUR404 => "Erreur 404"
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
    <a href="?page=<?= $PAGE_EXPORT ?>" class="<?= $page === $PAGE_EXPORT ? "active" : "" ?>">Exportation CSV</a>
    <a href="?page=<?= $PAGE_AJOUTMASSE ?>" class="<?= $page === $PAGE_AJOUTMASSE ? "active" : "" ?>">Ajouter masse houblon</a>
  </nav>

  <main id="main-content" class="container-fluid px-3 px-md-4 py-3">
    <?php

    $file = "site/{$page}.php";

    // Si le fichier n'est pas trouvé on charge la page ERREUR 404
    if (!file_exists($file)) { 
    	header("Location: ?page=" . $PAGE_ERREUR404);
	exit;
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
