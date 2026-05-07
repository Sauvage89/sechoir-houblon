async function get_variete() {

	const select = document.getElementById("inputVariete");

	if (!select) return;

	try {

		const res = await fetch("../api/query_get_variete.php");

		const data = await res.json();

		if (!Array.isArray(data)) return;

		// Reset complet du select
		select.innerHTML = `
			<option value="" disabled selected>
				-- Sélectionner une variété --
			</option>
		`;

		data.forEach(v => {

			const opt = document.createElement("option");

			opt.value = v.id_variete;
			opt.textContent = v.variete_nom;

			select.appendChild(opt);

		});

	}
	catch (e) {

		console.error("Erreur chargement variétés", e);

	}
}

function removeSpanSupprimerVariete() {
	const msg = document.getElementById("supprimer-variete-msg");
	msg.textContent = "";
}

function removeSpanNouvelleVariete() {
	const msg = document.getElementById("nouvelle-variete-msg");
	msg.textContent = "";
}