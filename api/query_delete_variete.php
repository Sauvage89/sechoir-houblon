<?php

header('Content-Type: application/json');

require "lib/bdd.php";

$idVariete = (int)($_GET['id_variete'] ?? 0);

if ($idVariete <= 0)
{
	echo json_encode([
		"status" => "error",
		"message" => "id_variete invalide"
	]);
	exit;
}

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

?>