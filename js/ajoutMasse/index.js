document.addEventListener("DOMContentLoaded", () => {
    const inputMasse = document.getElementById("masseInput");
    const btnAjouter = document.getElementById("btnAjouter");

    const overlay = document.getElementById("overlay");
    const closeOverlay = document.getElementById("closeOverlay");
    const cancelBtn = document.getElementById("cancelBtn");
    const confirmBtn = document.getElementById("confirmBtn");

    const confirmText = document.getElementById("confirmText");
    const confirmLot = document.getElementById("confirmLot");
    const confirmMasse = document.getElementById("confirmMasse");

    let masseSelectionnee = null;
    let lotsSelectionnes = [];

    function resetSelectionLots() {
    document.querySelectorAll('input[name="id_lots[]"]').forEach((checkbox) => {
        checkbox.checked = false;
    });

    document.querySelectorAll(".type-item").forEach((item) => {
        item.classList.remove("active");
    });
    }
    resetSelectionLots();


    // ======================================================
    // VALIDATION INPUT MASSE : MAX 2 DÉCIMALES
    // ======================================================

    inputMasse.addEventListener("input", () => {
        let valeur = inputMasse.value.replace(",", ".");

        if (!/^\d*\.?\d{0,2}$/.test(valeur)) {
            valeur = valeur.slice(0, -1);
        }

        inputMasse.value = valeur;
    });


    // ======================================================
    // SÉLECTION VISUELLE MULTIPLE DES LOTS
    // ======================================================

    document.querySelectorAll(".type-item").forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');

        // Force le clic sur tout le bloc
        item.addEventListener("mousedown", (event) => {
            event.preventDefault(); // 🔴 empêche le navigateur / CSS d'intercepter
            checkbox.checked = !checkbox.checked;
            item.classList.toggle("active", checkbox.checked);
        });

        // Sécurité si clic direct sur checkbox
        checkbox.addEventListener("change", () => {
            item.classList.toggle("active", checkbox.checked);
        });
    });

    // ======================================================
    // RÉCUPÉRER TOUS LES LOTS SÉLECTIONNÉS
    // ======================================================

    function getLotsSelectionnes() {
        const checkboxes = document.querySelectorAll('input[name="id_lots[]"]:checked');

        return Array.from(checkboxes).map((checkbox) => {
            const label = checkbox.closest(".type-item");
            const nom = label ? label.innerText.trim() : `Lot #${checkbox.value}`;

            return {
                id: checkbox.value,
                nom: nom
            };
        });
    }


    // ======================================================
    // OUVERTURE POPUP
    // ======================================================

    btnAjouter.addEventListener("click", () => {
        const masse = inputMasse.value.trim();
        const lots = getLotsSelectionnes();

        if (lots.length === 0) {
            alert("Veuillez sélectionner au moins un lot de houblon.");
            return;
        }

        if (!masse || !/^\d+(\.\d{1,2})?$/.test(masse)) {
            alert("Veuillez entrer une masse valide avec maximum 2 décimales.");
            return;
        }

        masseSelectionnee = masse;
        lotsSelectionnes = lots;

        confirmText.textContent = "Voulez-vous vraiment ajouter cette masse aux lots sélectionnés ?";
        confirmLot.textContent = lotsSelectionnes.map(lot => lot.nom).join(", ");
        confirmMasse.textContent = `${masseSelectionnee} kg`;

        overlay.style.display = "flex";
    });


    // ======================================================
    // FERMETURE POPUP
    // ======================================================

    function fermerOverlay() {
        overlay.style.display = "none";
    }

    closeOverlay.addEventListener("click", fermerOverlay);
    cancelBtn.addEventListener("click", fermerOverlay);

    overlay.addEventListener("click", (event) => {
        if (event.target === overlay) {
            fermerOverlay();
        }
    });


    // ======================================================
    // CONFIRMATION + ENVOI PHP
    // ======================================================

    confirmBtn.addEventListener("click", () => {
        confirmBtn.disabled = true;
        confirmBtn.textContent = "Enregistrement...";

        fetch("/site/ajout_masse.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                masse: masseSelectionnee,
                id_lots: lotsSelectionnes.map(lot => lot.id)
            })
        })
        .then(response => response.text())
        .then(text => {
            console.log("Réponse brute PHP :", text);

            let data;

            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("Réponse non JSON :", text);
                alert("Le serveur n'a pas renvoyé du JSON valide. Regarde la console.");
            }
        

            if (!data.success) {
                alert(data.message || "Erreur lors de l'ajout.");
                return;
            }

            alert("Masse ajoutée avec succès.");

            inputMasse.value = "";

            document.querySelectorAll(".type-item").forEach((item) => {
                item.classList.remove("active");
            });

            document.querySelectorAll('input[name="id_lots[]"]').forEach((checkbox) => {
                checkbox.checked = false;
            });

            fermerOverlay();
        })
        .catch(error => {
            console.error(error);
            alert("Erreur serveur.");
        })
        .finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.textContent = "Confirmer";
        });
    });
});
