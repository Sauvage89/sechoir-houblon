<?php
header('Content-Type: application/json');

require_once __DIR__ . "/lib/bdd.php";

$pdo = db_connect();

$id_lot        = $_POST['numero_lot']  ?? null;
$avec_temp     = isset($_POST['temperature']) ? (int)$_POST['temperature'] : 0;
$avec_evenement = isset($_POST['evenement'])  ? (int)$_POST['evenement']   : 0;

if (!$id_lot) {
    echo json_encode([
        "status"  => "error",
        "message" => "missing lot id"
    ]);
    exit;
}

try {

    /* ─────────────────────────────
       1. INFOS LOT
    ───────────────────────────── */
    $stmt = db_query(
        $pdo,
        "SELECT 
            l.id_lot,
            l.lot_remplissage,
            l.lot_dateHeureEntree,
            l.lot_dateHeureSortie,
            l.lot_dureeTheorique,
            l.lot_actif,
            v.variete_nom
         FROM lot l
         JOIN variete v ON v.id_variete = l.id_variete
         WHERE l.id_lot = ?",
        [$id_lot]
    );

    $lot = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lot) {
        echo json_encode([
            "status"  => "error",
            "message" => "lot not found"
        ]);
        exit;
    }

    /* ─────────────────────────────
       2. TEMPS PAR ÉTAGE
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
         ORDER BY id_etage",
        [$id_lot]
    );

    $etages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* ─────────────────────────────
       3. TEMPÉRATURES (conditionnel)
    ───────────────────────────── */
    $temperatures = [];
    if ($avec_temp) {
        $stmt = db_query(
            $pdo,
            "SELECT DISTINCT
                t.id_temperature,
                t.temperature_valeur,
                t.temperature_dateHeure,
                t.addresse_capteur
             FROM temperature t
             JOIN lotEtage le ON le.id_lot = ?
             WHERE t.temperature_dateHeure 
             BETWEEN le.lotEtage_dateDebut 
             AND COALESCE(le.lotEtage_dateFin, NOW())
             ORDER BY t.temperature_dateHeure",
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
        "temperatures" => $temperatures,  // [] si checkbox décochée
        "evenements"   => $evenements,    // [] si checkbox décochée
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}