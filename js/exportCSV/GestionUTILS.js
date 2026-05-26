function getFilters(renderOnly = false) {
	return Object.entries(FILTER_IDS[currentType] || {}).reduce((acc, [field, id]) => {
	const el = document.getElementById(id);
	if (!el) return acc;
	if (el.type === 'checkbox') {
	if (!renderOnly) acc[field] = el.checked ? 1 : 0;
	} else if (el.value) {
	acc[field] = el.value;
	}
	return acc;
	}, {});
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