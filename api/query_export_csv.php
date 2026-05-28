<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$id_lot         = $_POST['numero_lot'] ?? null;
	$avec_temp      = isset($_POST['temperature']) ? (int)$_POST['temperature'] : 0;
	$avec_evenement = isset($_POST['evenement'])   ? (int)$_POST['evenement']   : 0;

	if ($id_lot == null)
		throw new Exception("Il manque le renseignement de l'id d'un lot");

	$pdo = db_connect();

	/* ─────────────────────────────
	1. INFOS LOT (sans lot_actif)
	───────────────────────────── */
	$stmt = db_query(
		$pdo,
		"SELECT 
		l.id_lot,
		l.lot_remplissage,
		l.lot_dateHeureEntree,
		l.lot_dateHeureSortie,
		l.lot_dureeTheorique,
		v.variete_nom
		FROM lot l
		JOIN variete v ON v.id_variete = l.id_variete
		WHERE l.id_lot = ?",
		[$id_lot]
	);

	$lot = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$lot)
		throw new Exception("Le lot n'est pas retrouvé dans la base de donnée");

	/* ─────────────────────────────
	2. TEMPS PAR ÉTAGE (ordre décroissant)
	───────────────────────────── */
	$stmt = db_query(
		$pdo,
		"SELECT 
		id_etage,
		lotEtage_dateDebut,
		lotEtage_dateFin,
		TIMESTAMPDIFF(
			MINUTE,
			lotEtage_dateDebut,
			COALESCE(lotEtage_dateFin, NOW())
		) AS duree_minute
		FROM lotEtage
		WHERE id_lot = ?
		ORDER BY id_etage DESC",
		[$id_lot]
	);

	$etages = $stmt->fetchAll(PDO::FETCH_ASSOC);

	/* ─────────────────────────────
	3. TEMPÉRATURES avec id_etage et capteur_nom (conditionnel)
	───────────────────────────── */
	$temperatures = [];
	if ($avec_temp) {
		$stmt = db_query(
		$pdo,
		"SELECT DISTINCT
			t.id_temperature,
			t.temperature_valeur,
			t.temperature_dateHeure,
			c.capteur_nom,
			le.id_etage
		FROM temperature t
		JOIN capteur c ON c.addresse_capteur = t.addresse_capteur
		JOIN lotEtage le
			ON le.id_lot = ?
			AND t.temperature_dateHeure >= le.lotEtage_dateDebut
			AND t.temperature_dateHeure <= COALESCE(le.lotEtage_dateFin, NOW())
		ORDER BY t.temperature_dateHeure ASC",
		[$id_lot]
		);
		$temperatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* ─────────────────────────────
	4. ÉVÉNEMENTS (conditionnel)
	───────────────────────────── */
	$evenements = [];
	if ($avec_evenement) {
		$stmt = db_query(
		$pdo,
		"SELECT
			p.id_pause,
			p.pause_type,
			p.pause_dateHeureDebut,
			p.pause_dateHeureFin
		FROM pause p
		WHERE p.pause_dateHeureDebut
		BETWEEN :entree AND COALESCE(:sortie, NOW())",
		[
			':entree' => $lot['lot_dateHeureEntree'],
			':sortie' => $lot['lot_dateHeureSortie']
		]
		);
		$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* ─────────────────────────────
	RÉSULTAT FINAL
	───────────────────────────── */
	echo json_encode([
		"status"       => "ok",
		"lot"          => $lot,
		"etages"       => $etages,
		"temperatures" => $temperatures,
		"evenements"   => $evenements,
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