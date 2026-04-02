<?php

// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Paramètres de connexion 
$host = "localhost";
$dbname = "sechoir_houblon";
$user = "userdb";
$pass = "password";

try {
    
    // Création de l'objet PDO
    $pdo = new PDO(
        "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    // Active les exceptions en cas d'erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

    // Stoppe le script si la connexion échoue
    die("Erreur connexion BDD : " . $e->getMessage());
}
