// Variable globale pour le type d'export choisie par le client
let currentType = null;

// Map de colonne visuelle du resultat en rapport avec le type d'export
const COLUMNS = {
	lot: {
	numero_lot: 'N° lot', variete: 'Variété', date_sechage: 'Date',
	duree_minute: 'Durée (m)', statut: 'Statut'
	},
	production: {
	numero_lot: 'N° prod', variete: 'Variété', date_sechage: 'Date',
	quantite_kg: 'Quantité (kg)', duree_minute: 'Durée (m)',
	statut: 'Statut'
	}
};

// Map texte de status en lien avec des class CSS
const BADGE = {
	'Terminé': 'badge-done',
	'En cours': 'badge-prog',
	'Erreur': 'badge-err'
};

// Map chaque champ de filtre vers son id HTML
const FILTER_IDS = {
	lot: {
	variete: 'lot-variete',
	numero_lot: 'lot-numero',
	temperature: 'chexbox-temperature',
	evenement: 'chexbox-evenement'
	},
	production: {
	variete: 'prod-variete',
	numero_prod: 'prod-numero'
	}
};