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
	const data = await apiFetchPostForm('api/query_get_lots_preview.php', {
		action: 'preview',
		type_export: currentType,
		...getFilters(true)
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

	// Affichage PC
	const table = `<table class="result-table">
	<thead><tr>${keys.map(k => `<th>${cols[k]}</th>`).join('')}<th></th></tr></thead>
	<tbody>${rows.map(row => `<tr>
	${keys.map(k => `<td>${formatCell(k, row[k])}</td>`).join('')}
	<td><button class="btn-export-line" onclick="exportRow(${row.numero_lot})">${svgIcon}</button></td>
	</tr>`).join('')}</tbody>
	</table>`;

	// Affichage portable
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
	let filters = FILTER_IDS[currentType];
	if (!filters)
		filters = {};

	let ids = Object.values(filters);

	let i = 0;
	while (i < ids.length)
	{
		let id = ids[i];
		let el = document.getElementById(id);

		if (el)
		{
			if (el.type === 'checkbox')
				el.checked = false;
			else
				el.value = '';
		}

		i++;
	}
	resetResults();
}

function resetResults() {
	document.getElementById('result-table-wrap').innerHTML = '<p class="empty-state">Applique les filtres pour voir les résultats.</p>';
	document.getElementById('result-count').textContent = '—';
}

// --------------------------- RESET --------STOP---------------------------------
// --------------------------- EXPORT --------START---------------------------------

async function exportRow(numero_lot) {
	try {
		if (!currentType)
			throw new Error("currentType non défini");

		const filters = getFilters();
		console.log("DEBUG:in function 'exportRow':var 'filters'");
		console.log(filters);

		const data = await apiFetchPostForm("../api/query_export_csv.php", {
			numero_lot,
			type_export: currentType
		});
		console.log("DEBUG:in function 'exportRow':var 'data'");
		console.log(data);

		// Trie en ordre décroisant les étage avec l'élement id_etage
		const etagesSorted = [...data.etages].sort((a, b) => b.id_etage - a.id_etage);
		console.log("DEBUG:in function 'exportRow':var 'etagesSorted'");
		console.log(etagesSorted);

		// Construction du fichier CSV final
		const blocks = constructCSVFile(data, filters, etagesSorted);
		console.log("DEBUG:in function 'exportRow':var 'blocks'");
		console.log(blocks);
		
		// Export du fichier CSV, formatage en UTF-8
		downloadFile("\uFEFF" + blocks.join("\n\n"), `export_${numero_lot}.csv`, "text/csv;charset=utf-8;");

	} catch (e) {
		console.error("Erreur export CSV :", e);
	}
}

// --------------------------- EXPORT --------STOP---------------------------------

async function getVariete() {
	try {
		const select = document.getElementById("lot-variete"); // Le select contenant les différente variete
		const data = await apiFetchPostForm("../api/query_get_variete.php");
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