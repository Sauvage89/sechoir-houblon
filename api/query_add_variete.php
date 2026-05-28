<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$nom = trim($_GET['nom'] ?? '');

	if ($nom === '')
		throw new Exception("nom invalide");

	$pdo = db_connect();

	db_query(
		$pdo,
		"INSERT INTO
		variete (
			variete_nom,
			variete_dateHeureCreation,
			variete_actif
		)
		VALUES
		(
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
		"variete_nom" => $nom
	]);
}
catch (Throwable $e)
{
	http_response_code(500);

	echo json_encode([
		"status" => "error",
		"message" => $e->getMessage()
	]);
}