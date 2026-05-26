// --------------------------- TYPE SELECTION --------START---------------------------------

function selectType(type) {
	const toggling = currentType === type;
	currentType = toggling ? null : type;

	document.getElementById('btn-type-lot').classList.toggle('active', currentType === 'lot');
	document.getElementById('btn-type-production').classList.toggle('active', currentType === 'production');
	document.getElementById('filtres-lot').hidden = currentType !== 'lot';
	document.getElementById('filtres-production').hidden = currentType !== 'production';
	document.getElementById('comp-filtres').hidden = !currentType;
	document.getElementById('comp-results').hidden = !currentType;
	resetResults();
}

// --------------------------- TYPE SELECTION --------STOP---------------------------------
// --------------------------- TABLE --------START---------------------------------

async function loadTable() {
	const data = await apiFetch('api/query_get_lots_preview.php', {
		action: 'preview', type_export: currentType, ...getFilters(true)
	});
	renderTable(data.rows, data.count);
}

function renderTable(rows, count) {
  const wrap = document.getElementById('result-table-wrap');
  const countEl = document.getElementById('result-count');

  if (!count) {
    wrap.innerHTML = '<p class="empty-state">Aucun résultat pour ces filtres.</p>';
    countEl.textContent = '0 résultat';
    return;
  }

  countEl.textContent = count + (count > 1 ? ' résultats' : ' résultat');

  const cols = COLUMNS[currentType];
  const keys = Object.keys(cols);
  const [titleKey, ...bodyKeys] = keys;

  const svgIcon = `<svg width="12" height="12" viewBox="0 0 16 16" fill="none">
    <path d="M8 1v9M4 7l4 4 4-4M2 13h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>`;

  // Table desktop
  const table = `<table class="result-table">
    <thead><tr>${keys.map(k => `<th>${cols[k]}</th>`).join('')}<th></th></tr></thead>
    <tbody>${rows.map(row => `<tr>
      ${keys.map(k => `<td>${formatCell(k, row[k])}</td>`).join('')}
      <td><button class="btn-export-line" onclick="exportRow(${row.numero_lot})">${svgIcon}</button></td>
    </tr>`).join('')}</tbody>
  </table>`;

  // Cards mobile
  const cards = `<div class="result-cards">${rows.map(row => `
    <div class="result-card">
      <div class="result-card-header">
        <span class="result-card-title">${row[titleKey] ?? '—'}</span>
        <button class="btn-export-line" onclick="exportRow(${row.numero_lot})">${svgIcon}</button>
      </div>
      <div class="result-card-body">
        ${bodyKeys.map(k => `
          <span class="result-card-key">${cols[k]}</span>
          <span class="result-card-val">${formatCell(k, row[k])}</span>
        `).join('')}
      </div>
    </div>`).join('')}
  </div>`;

  wrap.innerHTML = table + cards;
}

// --------------------------- TABLE --------STOP---------------------------------
// --------------------------- RESET --------START---------------------------------

function resetFiltreResults() {
  Object.values(FILTER_IDS[currentType] || {}).forEach(id => {
    const el = document.getElementById(id);
    if (el) el[el.type === 'checkbox' ? 'checked' : 'value'] = el.type === 'checkbox' ? false : '';
  });
  resetResults();
}

function resetResults() {
  document.getElementById('result-table-wrap').innerHTML =
    '<p class="empty-state">Applique les filtres pour voir les résultats.</p>';
  document.getElementById('result-count').textContent = '—';
}

// --------------------------- RESET --------STOP---------------------------------
// --------------------------- EXPORT --------START---------------------------------

async function exportRow(numero_lot) {
	if (!currentType) return;
	try {
		const data = await apiFetch("../api/query_export_csv.php", {
			...getFilters(), numero_lot, type_export: currentType
		});

		const filters = getFilters();
		console.log(data.etages);
		console.log(Array.isArray(data.etages));
		console.log(filters);
		const etagesSorted = [...data.etages].sort((a, b) => b.id_etage - a.id_etage);

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

		downloadFile("\uFEFF" + blocks.join("\n\n"), `export_${numero_lot}.csv`, "text/csv;charset=utf-8;");

	} catch (e) {
	console.error("Erreur export CSV :", e);
	}
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

// --------------------------- EXPORT --------STOP---------------------------------

async function getVariete() {
	const select = document.getElementById("lot-variete"); // Le select contenant les différente variete
	try {
		const data = await apiFetch("../api/query_get_variete.php");
		data.forEach(v => {
			const opt = document.createElement("option"); // Création d'une option
			opt.value = v.id_variete; // ID associé à l'option
			opt.textContent = v.variete_nom; // Texte associé à l'option
			select.appendChild(opt); // Ajout de l'option dans le select
		});
	} catch (e) {
		console.error("Erreur chargement variétés", e);
	}
}