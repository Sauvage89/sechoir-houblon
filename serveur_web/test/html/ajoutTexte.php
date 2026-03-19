<?php
$fichier = 'data.txt';
$texte = "PHP exécuté à " . date('H:i:s') . "\n";

if (file_exists($fichier)) {
    file_put_contents($fichier, $texte, FILE_APPEND);
}