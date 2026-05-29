document.addEventListener("DOMContentLoaded", () => {
    setPauseButtonsState();
    rafraichirStatus();
    const btnPause = document.getElementById("btnPauseSechoir");
    const btnReprendre = document.getElementById("btnReprendreSechoir");
    const btnCommencer = document.getElementById("btnCommencerCycle");
    const btnTerminer = document.getElementById("btnTerminerCycle");

    if (btnCommencer) {
        btnCommencer.addEventListener("click", commencerSechoir);
    }

    if (btnTerminer) {
        btnTerminer.addEventListener("click", stopSechoir);
    }

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
            setPauseButtonsState("en cours");
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


async function commencerSechoir() {
    try {
        const res = await fetch("/../../api/query_start_sechoir.php", {
            method: "POST"
        });

        const data = await res.json();

        if (data.status === "ok") {
            console.log("Cycle de séchage commencé");
            setPauseButtonsState("en cours");
            rafraichirStatus();
        } else {
            console.error(data.message);
            alert(data.message || "Erreur lors du démarrage du cycle.");
        }
    } catch (e) {
        console.error(e);
        alert("Erreur serveur lors de la reprise.");
    }
}

async function stopSechoir() {
    try {
        const res = await fetch("/../../api/query_stop_sechoir.php", {
            method: "POST"
        });

        const data = await res.json();

        if (data.status === "ok") {
            console.log("Séchoir terminé");
            setPauseButtonsState("terminé");
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
    const btnCommencer = document.getElementById("btnCommencerCycle");
    const btnTerminer = document.getElementById("btnTerminerCycle");

    if (!btnPause || !btnReprendre) return;

    if (status === "pause") {
        btnPause.disabled = true;
        btnReprendre.disabled = false;
        btnCommencer.disabled = true;
        btnTerminer.disabled = true;
    } else if (status === "en cours") {
        btnPause.disabled = false;
        btnReprendre.disabled = true;
        btnCommencer.disabled = true;
        btnTerminer.disabled = false;
    } else if (status === "terminé") {
        btnPause.disabled = true;
        btnReprendre.disabled = true;
        btnCommencer.disabled = false;
        btnTerminer.disabled = true;
    }
}
