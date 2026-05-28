<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idEtage = (int)$_GET['etage'];

	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"SELECT l.id_lot
		FROM lotEtage le
		JOIN lot l ON l.id_lot = le.id_lot
		WHERE le.id_etage = ?
		AND le.lotEtage_dateFin IS NULL
		LIMIT 1",
		[$idEtage]
	);

	$row = $stmt->fetch();

	echo json_encode([
		"status"  => "ok",
		"nom" => $row ? "LOT_" . $row['id_lot'] : null
	]);
}
catch (Throwable $e)
{
	http_response_code(500);

	echo json_encode([
		"status"  => "error",
		"message" => $e->getMessage()
	]);
}