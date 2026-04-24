<?php

header('Content-Type: application/json');

require "lib/bdd.php";

$variete     = $_POST['variete']     ?? null;
$numero_lot  = $_POST['numero_lot']  ?? null;


try
{
	$variete    = $_POST['variete']    ?? null;
	$numero_lot = $_POST['numero_lot'] ?? null;

	$pdo = db_connect();

	$where  = [];
	$params = [];

	if ($variete)
	{
		$where[]  = "v.variete_nom = ?";
		$params[] = $variete;
	}

	if ($numero_lot)
	{
		$where[]  = "l.id_lot = ?";
		$params[] = (int)$numero_lot;
	}

	$sql = "SELECT
                l.id_lot        AS numero_lot,
                v.variete_nom   AS variete,
                l.lot_dateHeureEntree   AS date_sechage,
                l.lot_dureeTheorique    AS duree_minute,
                CASE
                    WHEN l.lot_actif = 1 THEN 'En cours'
                    WHEN l.lot_dateHeureSortie IS NOT NULL THEN 'Terminé'
                    ELSE 'Erreur'
                END AS statut
            FROM lot l
            JOIN variete v ON v.id_variete = l.id_variete";

    if ($where)
        $sql .= " WHERE " . implode(" AND ", $where);

    $sql .= " ORDER BY l.lot_dateHeureEntree DESC";

    $stmt = db_query($pdo, $sql, $params);
    $rows = $stmt->fetchAll();

    echo json_encode([
        "status" => "ok",
        "count"  => count($rows),
        "rows"   => $rows
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
?>