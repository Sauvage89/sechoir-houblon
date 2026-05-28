<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idVariete = (int)($_GET['id_variete'] ?? 0);

	if ($idVariete <= 0)
		throw new Exception("id_variete invalide");

	$pdo = db_connect();

	db_query(
		$pdo,
		"UPDATE variete
		SET variete_actif = 0
		WHERE id_variete = ?",
		[$idVariete]
	);

	echo json_encode([
		"status" => "ok",
		"id_variete" => $idVariete
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