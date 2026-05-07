async function supprimerVariete() {
	console.log("[DEBUG] : into file '/js/accueil/GestionApi.js' : into function : 'supprimerVariete()");
	console.log("[DEBUG] : l'utilisateur essaye de supprimer une variete.");

	const select = document.getElementById("inputVariete");
	const msg    = document.getElementById("supprimer-variete-msg");
	const id_variete = select.value;

	if (!id_variete) {
		msg.style.color = "red";
		msg.textContent = "Veuillez sélectionner une variété.";
		console.log("[DEBUG] : l'utilisateur essaye de supprimer une variete or il n'a pas selectionner de variete.");
		return;
	}

	try {
		const res = await fetch(`../api/query_delete_variete.php?id_variete=${id_variete}`);
		const data = await res.json();

		if (data.status === "ok") {
			msg.style.color = "green";
			msg.textContent = "Variété supprimée avec succès.";
			get_variete();

		}
		else {
			msg.style.color = "red";
			msg.textContent = "Erreur serveur.";

		}

	}
	catch (error) {

		console.error(error);

		msg.textContent = "Erreur serveur.";

	}
}

async function ajouterVariete() {
	console.log("[DEBUG] : into file '/js/accueil/GestionApi.js' : into function : 'ajouterVariete()'");
	console.log("[DEBUG] : l'utilisateur essaye d'ajouter une variete.");

	const input = document.getElementById("inputNouvelleVariete");
	const msg   = document.getElementById("nouvelle-variete-msg");
	const select = document.getElementById("inputVariete");
	const nom = input.value.trim();

	if (!nom) {
		msg.style.color = "red";
		msg.textContent = "Veuillez entrer un nom de variété.";
		return;
	}

	try {
		const res = await fetch(`../api/query_add_variete.php?nom=${encodeURIComponent(nom)}`);
		const data = await res.json();

		if (data.status === "ok") {
			msg.style.color = "green";
			msg.textContent = "Variété ajoutée avec succès.";
			get_variete();
			input.value = "";

		}
		else {
			msg.style.color = "red";
			msg.textContent = "Erreur serveur.";
		}

	}
	catch (error) {
		console.error(error);
		msg.style.color = "red";
		msg.textContent = "Erreur serveur.";

	}
}