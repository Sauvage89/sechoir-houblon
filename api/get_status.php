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

    // -------------------------------------------------------
    // 1. ÉTAGES ACTIFS
    // Récupère les 4 étages actuellement actifs avec
    // la variété et les dates du lot associé
    // -------------------------------------------------------
    $sql_houbvar = "
        SELECT
            houbvar_type
        FROM 
            houbvar    
    ";
    $var = $pdo->query($sql_houbvar)->fetchAll(PDO::FETCH_ASSOC);


    $sql_etages = " 
        SELECT
            houbEtag.houbEtag_etage        AS etage,
            houbVar.houbVar_type            AS variete,
            houbLot.houbLot_dateDebut       AS date_debut,
            houbLot.houbLot_dateFin         AS date_fin
        FROM houbEtag
        JOIN houbLot ON houbLot.id_houbLot   = houbEtag.houbEtag_houbLot
        JOIN houbVar ON houbVar.id_houbVar   = houbLot.houbLot_houbVar
        WHERE houbEtag.houbEtag_actif = 1
        ORDER BY houbEtag.houbEtag_etage ASC
    ";
    $etages = $pdo->query($sql_etages)->fetchAll(PDO::FETCH_ASSOC);

    // -------------------------------------------------------
    // 2. ÉTAT DU CYCLE
    // S'il y a au moins un étage actif → séchoir actif
    // -------------------------------------------------------
    $etat_cycle = count($etages) > 0 ? "Actif" : "Inactif";

    // -------------------------------------------------------
    // 3. DERNIÈRE ALERTE
    // Récupère le dernier événement enregistré
    // -------------------------------------------------------
    $sql_alerte = "
        SELECT even_type
        FROM even
        ORDER BY even_date DESC
        LIMIT 1
    ";
    $alerte = $pdo->query($sql_alerte)->fetch(PDO::FETCH_ASSOC);
    $derniere_alerte = $alerte ? $alerte['even_type'] : "Aucune alerte";

    // -------------------------------------------------------
    // 4. CONSTRUCTION ET ENVOI DE LA RÉPONSE JSON
    // -------------------------------------------------------
    echo json_encode([
        "etat_cycle"      => $etat_cycle,
        "derniere_alerte" => $derniere_alerte,
        "etages"          => $etages,
        "var"             => $var
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
