<?php

// -------------------------------------
// Activation de l'affichage des erreurs
// -------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ----------------------
// Réponse au format JSON
// ----------------------
header('Content-Type: application/json');

// ------------------------------
// Connexion à la base de données
// ------------------------------
require_once("../config/database.php");

try {

    // ------------------------------------------------------
    // 1 Récupération des dernière températures
    // ------------------------------------------------------
    // On récupère la dernière valeur de chaque capteur
    // GROUP BY capteur permet d'avoir une ligne par capteur
    // MAX(date_mesure) permet d'obtenir la plus récente
    // ------------------------------------------------------

    $sql_temp = "
        SELECT t1.capteur, t1.valeur
        FROM temperatures t1
        INNER JOIN (
            SELECT capteur, MAX(date_mesure) as max_date
            FROM temperatures
            GROUP BY capteur
        ) t2
        ON t1.capteur = t2.capteur
        AND t1.date_mesure = t2.max_date
        ORDER BY t1.capteur ASC
    ";

    $stmt_temp = $pdo->query($sql_temp);
    $temperatures = $stmt_temp->fetchAll(PDO::FETCH_ASSOC);

    // -----------------------
    // 2 Calcul de la moyenne
    // -----------------------

    $moyenne = 0;
    $total = 0;

    foreach ($temperatures as $temp) {
        $total += $temp['valeur'];
    }

    if (count($temperatures) > 0) {
        $moyenne = $total / count($temperatures);
    }

    // ----------------------------------
    // 3 Récupération de l'état du cycle
    // ----------------------------------

    $sql_cycle = "
        SELECT etat
        FROM cycles_sechage
        ORDER BY date_debut DESC
        LIMIT 1
    ";

    $stmt_cycle = $pdo->query($sql_cycle);
    $cycle = $stmt_cycle->fetch(PDO::FETCH_ASSOC);

    $etat_cycle = $cycle ? $cycle['etat'] : "inactif";

    // -------------------------------------
    // 4 Récupération de la dernière alerte
    // -------------------------------------

    $sql_alerte = "
        SELECT type
        FROM alertes
        ORDER BY date_alerte DESC
        LIMIT 1
    ";

    $stmt_alert = $pdo->query($sql_alerte);
    $alerte = $stmt_alert->fetch(PDO::FETCH_ASSOC);

    $derniere_alerte = $alerte ? $alerte['type'] : "Aucune alerte";

    // ----------------------------------
    // 5 Construction de la réponse JSON
    // ----------------------------------

    $response = [
        "temperatures" => $temperatures,
        "moyenne" => round($moyenne, 2),
        "etat_cycle" => $etat_cycle,
        "alerte" => $derniere_alerte
    ];

    // -------------------------------------
    // 6 Envoi de la réponse au format JSON
    // -------------------------------------

    echo json_encode($response);

} catch (PDOException $e) {

    // ------------------------
    // Gestion des erreurs SQL
    // ------------------------

    echo json_encode([
        "error" => $e->getMessage()
    ]);

}