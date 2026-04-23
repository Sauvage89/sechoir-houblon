<div class="export-page">
 
    <!-- 1 — Type d'export -->
    <section class="compartiment" id="comp-type">
      <h2 class="comp-label">1 — Type d'export</h2>
      <div class="type-list">

        <button class="type-item" id="btn-type-lot" onclick="selectType('lot')">
          <span class="dot dot-green"></span> Par lot — export détaillé d'un lot
        </button>

        <button class="type-item" id="btn-type-production" onclick="selectType('production')">
          <span class="dot dot-orange"></span> Par production — tous les lots d'une production finale
        </button>

      </div>
    </section>
 
    <!-- 2 — Filtres -->
    <section class="compartiment" id="comp-filtres" hidden>
      <h2 class="comp-label">2 — Filtres</h2>
 
      <div id="filtres-lot" hidden>
        <div class="filter-grid">
          <div class="filter-group">
            <div class="filter-name">Variété</div>
            <select id="lot-variete">
              <option value="">Toutes</option>
              <option>Strisselspalt</option><option>Aramis</option>
              <option>Brewers Gold</option><option>Magnum</option>
            </select>
          </div>
          <div class="filter-group">
            <div class="filter-name">N° de lot</div>
            <input type="text" id="lot-numero" placeholder="ex. LOT-027">
          </div>
        </div>
      </div>

      
      <div id="filtres-production" hidden>
        <div class="filter-grid">
          <div class="filter-group">
            <div class="filter-name">Variété</div>
            <select id="prod-variete">
              <option value="">Toutes</option>
              <option>Strisselspalt</option><option>Aramis</option>
              <option>Brewers Gold</option><option>Magnum</option>
            </select>
          </div>
          <div class="filter-group">
            <div class="filter-name">N° de production</div>
            <input type="text" id="prod-numero" placeholder="ex. PROD-005">
          </div>
        </div>
      </div>

	    <div class="filter-actions">
        <button class="btn" onclick="resetFiltreResults()">Réinitialiser</button>
        <button class="btn btn-search" onclick="loadPreview()">Rechercher</button>
      </div>
    </section>
 
    <!-- 3 — Résultats -->
    <section class="compartiment" id="comp-results" hidden>
      <h2 class="comp-label">3 — Résultats</h2>
      <div class="results-meta">
            <span class="results-count" id="result-count">—</span>
            <button class="export-btn" id="btn-export" disabled>
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
                    <path d="M8 1v9M4 7l4 4 4-4M2 13h12"
                          stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Télécharger CSV
            </button>
        </div>
        <div id="result-table-wrap">
            <p class="empty-state">Applique les filtres pour voir les résultats.</p>
        </div>
    </section>
 
</div>
 
<!-- Formulaire caché pour l'export CSV -->
<form id="export-form" method="POST" action="export_handler.php" hidden>
    <input type="hidden" name="action" value="export">
    <input type="hidden" id="hidden-type" name="type_export" value="">
</form>


<script>
let currentType = null;
 
// ── Colonnes par type ─────────────────────────────────────────
let COLUMNS = {
  lot: {
    numero_lot:'N° lot', variete:'Variété', palier:'Palier',
    date_sechage:'Date', quantite_kg:'Quantité (kg)', duree_heures:'Durée (h)',
    humidite_fin:'Humidité fin (%)', statut:'Statut'
  },
  production: {
    numero_lot:'N° lot', variete:'Variété', sechoir:'Séchoir',
    date_sechage:'Date', quantite_kg:'Quantité (kg)', duree_heures:'Durée (h)',
    humidite_fin:'Humidité fin (%)', statut:'Statut'
  }
};
 
let BADGE = { 'Terminé':'badge-done', 'En cours':'badge-prog', 'Erreur':'badge-err' };
 
// ── Filtres par type → { champ: id_element } ─────────────────
let FILTER_IDS = {
  lot:        { variete: 'lot-variete',  numero_lot:  'lot-numero'  },
  production: { variete: 'prod-variete', numero_prod: 'prod-numero' }
};
 
// ── Sélection du type ─────────────────────────────────────────
function selectType(type) {
	if (currentType == type)
	{
		currentType = null;
		document.querySelectorAll('.type-item').forEach(btn => btn.classList.remove('active'));
		document.getElementById('comp-filtres').hidden = true;
		document.getElementById('comp-results').hidden = true;
		resetFilters();
		resetResults();
		return;
	}
	currentType = type;

	document.getElementById('btn-type-lot').classList.toggle('active', type === 'lot');
	document.getElementById('btn-type-production').classList.toggle('active', type === 'production');
	document.getElementById('filtres-lot').hidden = (type !== 'lot');
	document.getElementById('filtres-production').hidden = (type !== 'production');
	document.getElementById('comp-filtres').hidden = false;
	document.getElementById('comp-results').hidden = false;
	resetFilters();
	resetResults();
}
 
// ── Collecte des filtres actifs ───────────────────────────────
function getFilters() {
  var filters = {};
  var ids = FILTER_IDS[currentType] || {};
  for (var field in ids) {
    var val = document.getElementById(ids[field]).value;
    if (val) filters[field] = val;
  }
  return (filters);
}
 
// ── Aperçu (appel serveur) ────────────────────────────────────
function loadPreview() {
  if (!currentType) return;
 
  var formData = new FormData();
  formData.append('action', 'preview');
  formData.append('type_export', currentType);
  var filters = getFilters();
  for (var f in filters) formData.append(f, filters[f]);
 
  fetch('api/export_handler.php', { method: 'POST', body: formData })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      document.getElementById('comp-results').hidden = false;
      renderTable(data.rows, data.count);
      document.getElementById('btn-export').disabled  = (data.count === 0);
      document.getElementById('result-count').textContent =
        data.count + ' résultat' + (data.count > 1 ? 's' : '');
    })
    .catch(function() {
      document.getElementById('result-table-wrap').innerHTML =
        '<p class="empty-state">Erreur de connexion au serveur.</p>';
    });
}
 
// ── Rendu tableau ─────────────────────────────────────────────
function renderTable(rows, count) {
  var wrap = document.getElementById('result-table-wrap');
  if (!count) {
    wrap.innerHTML = '<p class="empty-state">Aucun résultat pour ces filtres.</p>';
    return;
  }
 
  var cols  = COLUMNS[currentType];
  var keys  = Object.keys(cols);
  var thead = keys.map(function(k) { return '<th>' + cols[k] + '</th>'; }).join('');
 
  var tbody = rows.map(function(row) {
    var cells = keys.map(function(k) {
      var v = (row[k] !== undefined && row[k] !== null) ? row[k] : '—';
      if      (k === 'statut')       v = '<span class="badge ' + (BADGE[v] || '') + '">' + v + '</span>';
      else if (k === 'humidite_fin') v = parseFloat(v).toFixed(1) + ' %';
      else if (k === 'quantite_kg')  v = parseFloat(v).toFixed(0) + ' kg';
      else if (k === 'duree_heures') v = parseFloat(v).toFixed(1) + ' h';
      return '<td>' + v + '</td>';
    }).join('');
    var exportBtn = '<td><button class="btn-export-line" onclick="exportRow(\'' + row.numero_lot + '\')">CSV</button></td>';
    return '<tr>' + cells + exportBtn + '</tr>';
  }).join('');
 
  wrap.innerHTML = '<table class="result-table"><thead><tr>' + thead +
                  '</tr></thead><tbody>' + tbody + '</tbody></table>';
}

function exportRow(id) {
  if (!currentType) return;

  var form = document.getElementById('export-form');

  // reset anciens champs
  form.querySelectorAll('.dyn').forEach(function(el) { el.remove(); });

  document.getElementById('hidden-type').value = currentType;

  // identifiant spécifique (lot ou prod)
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = (currentType === 'lot') ? 'numero_lot' : 'numero_prod';
  input.value = id;
  input.className = 'dyn';

  form.appendChild(input);

  form.submit();
}
 
// ── Export CSV ────────────────────────────────────────────────
function submitExport() {
  if (!currentType) return;

  document.getElementById('hidden-type').value = currentType;
  var form = document.getElementById('export-form');
  form.querySelectorAll('.dyn').forEach(function(el) { el.remove(); });
  var filters = getFilters();
  for (var f in filters) {
    var input = document.createElement('input');
    input.type = 'hidden'; input.name = f; input.value = filters[f]; input.className = 'dyn';
    form.appendChild(input);
  }
  form.submit();
}
 
// ── Reset ─────────────────────────────────────────────────────
function resetFiltreResults() {
	resetFilters();
	resetResults();
}
function resetFilters() {
  var ids = FILTER_IDS[currentType] || {};
  for (var f in ids) { document.getElementById(ids[f]).value = ''; }
}
 
function resetResults() {
  document.getElementById('result-table-wrap').innerHTML =
    '<p class="empty-state">Applique les filtres pour voir les résultats.</p>';
  document.getElementById('result-count').textContent = '—';
  document.getElementById('btn-export').disabled = true;
  
}
</script>