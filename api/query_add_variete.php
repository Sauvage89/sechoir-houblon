<?php

header('Content-Type: application/json');

require "lib/bdd.php";

$nom = trim($_GET['nom'] ?? '');

if ($nom === '')
{
	echo json_encode([
		"status" => "error",
		"message" => "nom invalide"
	]);
	exit;
}

$pdo = db_connect();

db_query(
	$pdo,
	"INSERT INTO variete (
		variete_nom,
		variete_dateHeureCreation,
		variete_actif
	)
	VALUES (
		?,
		NOW(),
		1
	)",
	[$nom]
);

$idVariete = $pdo->lastInsertId();

echo json_encode([
	"status" => "ok",
	"id_variete" => $idVariete,
	"nom" => $nom
]);

?>