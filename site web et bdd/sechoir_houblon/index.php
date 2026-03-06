<!DOCTYPE html>
<html lang="fr">




<head>
<meta charset="UTF-8">
<title>Controle du séchage - Houblon</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<h1>Contrôle du séchage du houblon</h1>
<section>
    <h2>Températures</h2>
    <ul id="temperatures">
        <li>Capteur 1 : <span id="capteur_1">--</span></li>
        <li>Capteur 2 : <span id="capteur_2">--</span></li>
        <li>Capteur 3 : <span id="capteur_3">--</span></li>
        <li>Capteur 4 : <span id="capteur_4">--</span></li>
        <li>Capteur 5 : <span id="capteur_5">--</span></li>
        <li>Capteur 6 : <span id="capteur_6">--</span></li>
    </ul>
<p><strong>Moyenne :</strong> <span id="moyenne">--</span></p>
<p><strong>Etat du cycle :</strong> <span id="etat_cycle">--</span></p>
</section>

<section>
    <h2>Commande</h2>
    <button onclick="startCycle()">Démarrer le cycle</button>
    <button onclick="stopCycle()">Arrêter le cycle</button>
</section>

<section>
    <h2>Alertes</h2>
    <p id="alerte">Aucune alerte</p>
</section>

<script src="assets/script.js"></script>
</body>
</html>