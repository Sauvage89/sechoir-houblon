<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idEtage = (int)$_POST['etage'];

	if ($idEtage !== 1)
	{
		throw new Exception("Retrait uniquement à l'étage 1");
	}

	$pdo = db_connect();
	$pdo->beginTransaction();

	// 1. récupérer lot actif
	$stmt = db_query(
		$pdo,
		"SELECT id_lot
		 FROM lotEtage
		 WHERE id_etage = ?
		 AND lotEtage_dateFin IS NULL
		 LIMIT 1",
		[$idEtage]
	);

	$lot = $stmt->fetch();

	if (!$lot)
	{
		throw new Exception("Aucun lot actif à retirer");
	}

	$idLot = $lot['id_lot'];

	// 2. fermer lien étage
	db_query(
		$pdo,
		"UPDATE lotEtage
		 SET lotEtage_dateFin = NOW()
		 WHERE id_lot = ?
		 AND id_etage = ?
		 AND lotEtage_dateFin IS NULL",
		[$idLot, $idEtage]
	);

	// 3. clôturer lot
	db_query(
		$pdo,
		"UPDATE lot
		 SET lot_dateHeureSortie = NOW(),
		     lot_actif = 0
		 WHERE id_lot = ?",
		[$idLot]
	);

	$pdo->commit();

	echo json_encode([
		"status" => "ok",
		"id_lot" => $idLot
	]);
}
catch (Throwable $e)
{
	if (isset($pdo))
	{
		$pdo->rollBack();
	}

	http_response_code(500);

	echo json_encode([
		"status" => "error",
		"message" => $e->getMessage()
	]);
}

?>