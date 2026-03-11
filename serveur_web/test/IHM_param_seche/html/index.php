<!DOCTYPE html>

<html lang="fr">
	<head>
		<?php require "site/content-head.php"; ?>
		<link rel="stylesheet" href="css/style.css">
	</head>

	<body>
		<header>
			<h1>Sechoir houblon - accueil</h1>
		</header>

		<nav>
			<a href="index.php?page=accueil">Accueil</a>
			<a href="index.php?page=nouvelle_seche">Nouvelle session de sèche</a>
			<a href="index.php?page=visualisation">Visualisation d'une session</a>
		</nav>

		<main>
			<?php
			$page = $_GET['page'] ?? 'accueil';
			include "site/{$page}.php";
			?>
		</main>

		<footer>
		<p>© 2026</p>
		</footer>
	</body>
</html>