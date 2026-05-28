<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idEtage = (int)($_GET['etage'] ?? 0);

	if ($idEtage <= 0)
		throw new Exception("etage invalide");

	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"SELECT
			l.id_lot,
			l.lot_remplissage,
			l.lot_dateHeureEntree,
			l.lot_dureeTheorique,
			l.lot_actif,
			v.id_variete,
			v.variete_nom
		FROM lotEtage le
		JOIN lot l ON l.id_lot = le.id_lot
		JOIN variete v ON v.id_variete = l.id_variete
		WHERE le.id_etage = ?
		AND le.lotEtage_dateFin IS NULL
		LIMIT 1",
		[$idEtage]
	);

	$row = $stmt->fetch();

	echo json_encode([
		"status" => "ok",
		"lot" => $row ? : null
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