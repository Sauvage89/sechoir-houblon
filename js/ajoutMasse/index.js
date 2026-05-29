document.addEventListener("DOMContentLoaded", () => {
    const bouton = document.getElementById("btnAjouter");
    const masseInput = document.getElementById("masseInput");

    document.querySelectorAll(".type-item").forEach(item => {
        item.addEventListener("click", () => {
            const checkbox = item.querySelector("input");

            checkbox.checked = !checkbox.checked;
            item.classList.toggle("active", checkbox.checked);
        });
    });

    bouton.addEventListener("click", () => {
        const masse = masseInput.value;
        const lots = [];

        document.querySelectorAll('input[name="id_lots[]"]:checked').forEach(checkbox => {
            lots.push(checkbox.value);
        });

        if (lots.length === 0) {
            alert("Veuillez sélectionner au moins un lot.");
            return;
        }

        if (masse === "") {
            alert("Veuillez entrer une masse.");
            return;
        }

        fetch("?page=ajout_masse", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                masse: masse,
                id_lots: lots
            })
        })
        .then(() => {
            alert("Masse ajoutée avec succès.");
            location.reload();
        })
        .catch(error => {
            console.error("Erreur complète :", error);
            alert("Erreur lors de l'envoi au serveur.");
        });
    });
});
