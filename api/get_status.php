<?php

header('Content-Type: application/json');

require "lib/bdd.php";

// -------------------------------------------------------
// Calcule la fin prévue en cascade avec les pauses
// -------------------------------------------------------
function calculerFinPrevue(PDO $pdo, string $dateEntree, int $dureeMinutes): string
{
    $debut        = new DateTime($dateEntree);
    $fin          = (clone $debut)->modify("+{$dureeMinutes} minutes");
    $pausesDejaComptees = []; // ← garder trace des pauses déjà traitées

    $continuer = true;
    while ($continuer)
    {
        $pauses = db_query(
            $pdo,
            "SELECT pause_dateHeureDebut, pause_dateHeureFin
             FROM pause
             WHERE pause_dateHeureDebut >= ?
             AND pause_dateHeureDebut < ?
             AND pause_dateHeureFin IS NOT NULL
             ORDER BY pause_dateHeureDebut ASC",
            [
                $debut->format("Y-m-d H:i:s"),
                $fin->format("Y-m-d H:i:s")
            ]
        )->fetchAll(PDO::FETCH_ASSOC);

        // Filtrer les pauses déjà comptées
        $nouvellesPauses = array_filter($pauses, function($p) use (&$pausesDejaComptees) {
            $cle = $p['pause_dateHeureDebut'];
            if (in_array($cle, $pausesDejaComptees)) return false;
            $pausesDejaComptees[] = $cle;
            return true;
        });

        if (empty($nouvellesPauses))
        {
            $continuer = false;
            break;
        }

        foreach ($nouvellesPauses as $pause)
        {
            $pauseDebut    = new DateTime($pause['pause_dateHeureDebut']);
            $pauseFin      = new DateTime($pause['pause_dateHeureFin']);
            $dureeSecondes = $pauseFin->getTimestamp() - $pauseDebut->getTimestamp();
            $fin->modify("+{$dureeSecondes} seconds");
        }
    }

    return $fin->format("Y-m-d H:i:s");
}

// -------------------------------------------------------

try
{
    $pdo = db_connect();

    // 1. VARIÉTÉS ACTIVES
    $varietes = db_query(
        $pdo,
        "SELECT variete_nom FROM variete WHERE variete_actif = 1"
    )->fetchAll(PDO::FETCH_ASSOC);

    // 2. ÉTAGES ET LOTS EN COURS
    $etages_actifs = db_query(
        $pdo,
        "SELECT
            e.id_etage              AS etage,
            v.variete_nom           AS variete,
            le.lotEtage_dateDebut   AS date_debut,
            l.lot_dateHeureEntree   AS date_entree,
            l.lot_dureeTheorique    AS duree_theorique
        FROM etage e
        JOIN lotEtage le ON e.id_etage = le.id_etage
        JOIN lot l       ON le.id_lot  = l.id_lot
        JOIN variete v   ON l.id_variete = v.id_variete
        WHERE l.lot_actif = 1
        AND le.lotEtage_dateFin IS NULL
        ORDER BY e.id_etage ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    // Calcul de la fin prévue pour chaque étage (cascade pauses)
    foreach ($etages_actifs as &$etage)
    {
        $etage['date_fin'] = calculerFinPrevue(
            $pdo,
            $etage['date_entree'],
            (int)$etage['duree_theorique']
        );

        // On n'expose pas ces champs intermédiaires au frontend
        unset($etage['date_entree'], $etage['duree_theorique']);
    }
    unset($etage);

    // 3. ÉTAT DU SÉCHOIR
    $status_row = db_query(
        $pdo,
        "SELECT etatSechoir_status
         FROM etatSechoir
         ORDER BY id_etatSechoir DESC
         LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);

    $etat_cycle = $status_row ? $status_row['etatSechoir_status'] : "Inconnu";

    // 4. DERNIÈRE PAUSE
    $pause_row = db_query(
        $pdo,
        "SELECT pause_type
         FROM pause
         ORDER BY pause_dateHeureDebut DESC
         LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);

    $derniere_alerte = $pause_row ? $pause_row['pause_type'] : "Aucune pause";

    // 5. DERNIÈRES TEMPÉRATURES PAR CAPTEUR
    $temperatures = db_query(
        $pdo,
        "SELECT c.capteur_nom, t.temperature_valeur, t.temperature_dateHeure
         FROM capteur c
         LEFT JOIN temperature t ON t.addresse_capteur = c.addresse_capteur
             AND t.id_temperature = (
                 SELECT MAX(t2.id_temperature)
                 FROM temperature t2
                 WHERE t2.addresse_capteur = c.addresse_capteur
             )
         WHERE c.capteur_actif = 1
         ORDER BY c.addresse_capteur ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    // 6. RÉPONSE
    echo json_encode([
        "etat_cycle"      => $etat_cycle,
        "derniere_alerte" => $derniere_alerte,
        "etages"          => $etages_actifs,
        "varietes"        => $varietes,
        "temperatures"    => $temperatures
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