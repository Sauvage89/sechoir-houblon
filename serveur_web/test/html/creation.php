<?php
// Récupère les paramètres POST
$nomFichier = isset($_POST['nomFichier']) ? $_POST['nomFichier'] : '';
$contenu = isset($_POST['contenu']) ? $_POST['contenu'] : '';

if ($nomFichier === '') {
    echo "Nom de fichier manquant !";
    exit;
}

// Sécurise le nom du fichier : retire les caractères dangereux
$nomFichier = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $nomFichier);

if (!file_exists($nomFichier)) {
    file_put_contents($nomFichier, "");
}
