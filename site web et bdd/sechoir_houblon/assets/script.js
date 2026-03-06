function startCycle() {
    fetch("api/demarrer_cycle.php")
    .then(response => response.json())
    .then(data => {
        alert(data.message)

        majDashboard()
    })
}

function stopCycle() {
    fetch("api/stopper_cycle.php")
    .then(response => response.json())
    .then(data => {
        alert(data.message)

        majDashboard()
    })
}

function majDashboard() {
    fetch("api/get_status.php")
        .then(response => response.json())
        .then(data => {

            // Afficher la moyenne
            document.getElementById("moyenne").textContent = data.moyenne + " °C";

            // Afficher état cycle
            document.getElementById("etat_cycle").textContent = data.etat_cycle;

            // Afficher alerte
            document.getElementById("alerte").textContent = data.alerte;

            // Afficher températures capteurs 
            data.temperatures.forEach(capteur => {
                let element = document.getElementById("capteur_" + capteur.capteur);
                if (element) {
                    element.textContent = capteur.valeur + " °C";
                }
            });
        })
        .catch(error => {
            console.error("Erreur :", error);
        });
}

// ---------------------------
// Mise à jour au chargement -
// ---------------------------
majDashboard();

// -----------------------------------
// Mise à jour toutes les 5 secondes -
// -----------------------------------
setInterval(majDashboard, 5000);