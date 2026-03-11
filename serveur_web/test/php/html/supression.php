<?php
$fichier = 'data.txt';

if (file_exists($fichier)) {
    unlink($fichier);
}