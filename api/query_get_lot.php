<?php

header('Content-Type: application/json');

require "lib/bdd.php";

$idEtage = (int)($_GET['etage'] ?? 0);

if ($idEtage <= 0)
{
	echo json_encode([
		"status" => "error",
		"message" => "etage invalide"
	]);
	exit;
}

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
	"lot" => $row ?: null
]);
?>