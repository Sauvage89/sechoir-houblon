<!DOCTYPE html>

<?php
$page = $_GET['page'] ?? 'accueil';
$pages_autorisees = [
	"accueil",
	"nouvelle_seche",
	"visualisation"
];
if (!in_array($page, $pages_autorisees))
{
	$page = "404";
}
$titles = [
	"accueil" => "Accueil",
	"nouvelle_seche" => "Nouvelle session",
	"visualisation" => "Visualisation",
	"404" => "ERROR 404"
];
$title = $titles[$page];
?>

<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title>Sechoir houblon</title>
		<link rel="stylesheet" href="css/style.css">
	</head>

	<body>
		<header>
			<h1><?= $title ?> - Sechoir houblon</h1>
		</header>

		<nav>
			<a href="index.php?page=accueil" class="<?= $page == 'accueil' ? 'active' : '' ?>">Accueil</a>
			<a href="index.php?page=nouvelle_seche" class="<?= $page == 'nouvelle_seche' ? 'active' : '' ?>">Nouvelle session de sèche</a>
			<a href="index.php?page=visualisation" class="<?= $page == 'visualisation' ? 'active' : '' ?>">Visualisation d'une session</a>
		</nav>

		<main>
			<div class="content">
				<?php include "site/" . $page . ".php";	?>
			</div>	
		</main>

		<footer>
			<p>© 2026</p>
		</footer>
	</body>
</html>