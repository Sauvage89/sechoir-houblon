document.addEventListener("DOMContentLoaded", () => {
    const btnPause = document.getElementById("btnPauseSechoir");
    const btnReprendre = document.getElementById("btnReprendreSechoir");

    if (btnPause) {
        btnPause.addEventListener("click", pauseSechoir);
    }

    if (btnReprendre) {
        btnReprendre.addEventListener("click", reprendreSechoir);
    }
});

async function pauseSechoir() {
    try {
        const res = await fetch("/../../api/query_pause_sechoir.php", {
            method: "POST"
        });

        const data = await res.json();

        if (data.status === "ok") {
            console.log("Séchoir mis en pause");
            setPauseButtonsState("pause");
            rafraichirStatus();
        } else {
            console.error(data.message);
            alert(data.message || "Erreur lors de la mise en pause.");
        }
    } catch (e) {
        console.error(e);
        alert("Erreur serveur lors de la pause.");
    }
}

async function reprendreSechoir() {
    try {
        const res = await fetch("/../../api/query_reprendre_sechoir.php", {
            method: "POST"
        });

        const data = await res.json();

        if (data.status === "ok") {
            console.log("Séchoir repris");
            setPauseButtonsState("en_cours");
            rafraichirStatus();
        } else {
            console.error(data.message);
            alert(data.message || "Erreur lors de la reprise.");
        }
    } catch (e) {
        console.error(e);
        alert("Erreur serveur lors de la reprise.");
    }
}

function setPauseButtonsState(status) {
    const btnPause = document.getElementById("btnPauseSechoir");
    const btnReprendre = document.getElementById("btnReprendreSechoir");

    if (!btnPause || !btnReprendre) return;

    if (status === "pause") {
        btnPause.disabled = true;
        btnReprendre.disabled = false;
    } else {
        btnPause.disabled = false;
        btnReprendre.disabled = true;
    }
}