<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idEtage = (int)$_POST['etage'];

	if ($idEtage <= 1)
	{
		throw new Exception("Impossible de descendre en dessous de l'étage 1");
	}

	$pdo = db_connect();
	$pdo->beginTransaction();

	// 1. récupérer lot actif de l'étage
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
		throw new Exception("Aucun lot actif sur cet étage");
	}

	$newEtage = $idEtage - 1;
	$idLot = (int)$lot['id_lot'];

	// sécurité : vérifier que l'étage inférieur est libre
	$stmt = db_query(
		$pdo,
		"SELECT 1
		FROM lotEtage
		WHERE id_etage = ?
		AND lotEtage_dateFin IS NULL
		LIMIT 1",
		[$newEtage]
	);

	$occupant = $stmt->fetch();

	if ($occupant)
	{
		throw new Exception("L'étage inférieur est déjà occupé");
	}

	// 2. fermer étage actuel
	db_query(
		$pdo,
		"UPDATE lotEtage
		 SET lotEtage_dateFin = NOW()
		 WHERE id_lot = ?
		 AND id_etage = ?
		 AND lotEtage_dateFin IS NULL",
		[$idLot, $idEtage]
	);

	// 3. créer nouveau lien sur étage inférieur
	db_query(
		$pdo,
		"INSERT INTO lotEtage (
			id_lot,
			id_etage,
			lotEtage_dateDebut,
			lotEtage_dateFin
		)
		VALUES (?, ?, NOW(), NULL)",
		[
			$idLot,
			$newEtage
		]
	);

	$pdo->commit();

	echo json_encode([
		"status" => "ok",
		"new_etage" => $newEtage
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
		"type" => "already_lot_bottom",
		"message" => $e->getMessage()
	]);
}

?>