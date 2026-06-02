function getFilters(renderOnly = false) {
	const result = {};
	const map = FILTER_IDS[currentType] || {}; // map contient les id des filtre par rapport au type d'export choisi

	// Récupére les valeur des données rentrées
	for (const key in map) {
		const id = map[key];
		const el = document.getElementById(id);

		if (!el) continue;

		if (el.type === "checkbox") {
			if (!renderOnly)
				result[key] = el.checked ? 1 : 0;
		}
		else {
			if (el.value)
				result[key] = el.value;
		}
	}

	return result;
}

function buildCsvBlock(title, headers, rows) {
	return `=== ${title} ===\n${headers.join(';')}\n${rows.map(r => r.join(';')).join('\n')}`;
}

function formatCell(k, v) {
	if (v == null) return '—';
	if (k === 'statut')       return `<span class="badge ${BADGE[v] || ''}">${v}</span>`;
	if (k === 'duree_minute') return `${parseInt(v)} min`;
	if (k === 'quantite_kg')  return `${parseFloat(v).toFixed(0)} kg`;
	if (k === 'humidite_fin') return `${parseFloat(v).toFixed(1)} %`;
	return v;
}

function constructCSVFile(data, filters, etagesSorted ) {
	const blocks = [
		buildCsvBlock('LOT',
			['id_lot','lot_remplissage','lot_dateHeureEntree','lot_dateHeureSortie','lot_dureeTheorique','variete_nom'],
			[[data.lot.id_lot, data.lot.lot_remplissage, data.lot.lot_dateHeureEntree,
			data.lot.lot_dateHeureSortie, data.lot.lot_dureeTheorique, data.lot.variete_nom]]
		),
		buildCsvBlock('ETAGES',
			['id_lot','id_etage','lotEtage_dateHeureDebut','lotEtage_dateHeureFin','duree_minute'],
			etagesSorted.map(e => [data.lot.id_lot, e.id_etage, e.lotEtage_dateDebut, e.lotEtage_dateFin, e.duree_minute])
		)
	];

	if (filters.temperature == 1) {
		blocks.push(buildCsvBlock('TEMPERATURES',
			['id_lot','id_etage','temperature_valeur','temperature_dateHeure','capteur_nom'],
			data.temperatures.map(t => [data.lot.id_lot, t.id_etage, t.temperature_valeur, t.temperature_dateHeure, t.capteur_nom])
		));
	}

	if (filters.evenement == 1) {
		blocks.push(buildCsvBlock('EVENEMENTS',
			['id_pause','pause_type','pause_dateHeureDebut','pause_dateHeureFin'],
			data.evenements.map(e => [e.id_pause, e.pause_type, e.pause_dateHeureDebut, e.pause_dateHeureFin])
		));
	}
	return (blocks);
}

function downloadFile(content, filename, type) {
	const a = Object.assign(document.createElement('a'), {
		href: URL.createObjectURL(new Blob([content], { type })),
		download: filename
	});
	document.body.appendChild(a);
	a.click();
	a.remove();
	URL.revokeObjectURL(a.href);
}