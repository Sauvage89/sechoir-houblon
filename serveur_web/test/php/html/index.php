<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AJAX PHP</title>
</head>
<body>

	<button id="btn">Exécuter PHP</button>
	<div id="resultat"></div>

	<button id="createFile">Créer fichier</button>
	<div id="texteCreateFile">test</div>
	<button id="addTexte">Ajout du texte</button>
	<button id="deleteFile">supprimer fichier</button>

	<script>

		document.getElementById('btn').addEventListener('click', () => {
			fetch('test.php', {
				method: 'POST'
			})
			.then(response => response.text())
			.then(data => {
				document.getElementById('resultat').innerText = data;
			});
		});
		document.getElementById('createFile').addEventListener('click', () => {
			const texte = document.getElementById('texteCreateFile').innerText;
			const nomFichier = texte + '.txt';
			fetch('creation.php', {	
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'nomFichier=' + encodeURIComponent(nomFichier) + '&contenu=' + encodeURIComponent(texte)
			})
		});
		document.getElementById('addTexte').addEventListener('click', () => {
			fetch('ajoutTexte.php', {
				method: 'POST'
			})
		});
		document.getElementById('deleteFile').addEventListener('click', () => {
			fetch('supression.php', {
				method: 'POST'
			})
		});
	</script>
</body>
</html>
