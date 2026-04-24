<div class="export-page">
 
    <!-- 1 — Type d'export -->
    <section class="compartiment" id="comp-type">
      <h2 class="comp-label">1 — Type d'export</h2>
      <div class="type-list">

        <button class="type-item" id="btn-type-lot" onclick="selectType('lot')">
          <span class="dot dot-green"></span> Par lot — export détaillé d'un lot
        </button>

        <button class="type-item" id="btn-type-production" disabled onclick="selectType('production')">
          <span class="dot dot-orange"></span> Par production — tous les lots d'une production finale
          <p style="color:red;">Work in progress</p>
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
              <option>Citra</option><option>Aramis</option>
              <option>Brewers Gold</option><option>Magnum</option>
            </select>
          </div>
          <div class="filter-group">
            <div class="filter-name">N° de lot</div>
            <input type="text" id="lot-numero" placeholder="ex. LOT-027">
          </div>

          <div class="filter-group" id="filter-etages">
            <div class="filter-name">Temps passé par étage</div>
            <div class="etage-checks">
              <label><input id="chexbox-etage1" type="checkbox" class="etage-check" value="1"> Étage 1</label>
              <label><input id="chexbox-etage2" type="checkbox" class="etage-check" value="2"> Étage 2</label>
              <label><input id="chexbox-etage3" type="checkbox" class="etage-check" value="3"> Étage 3</label>
              <label><input id="chexbox-etage4" type="checkbox" class="etage-check" value="4"> Étage 4</label>
            </div>
          </div>
          
          <div class="filter-group" id="filter-etages">
            <div class="filter-name">Températures</div>
            <div class="etage-checks">
              <label><input id="chexbox-temperature" type="checkbox" class="etage-check" checked>Export de toute les temperatures du lot</label>
            </div>
          </div>

          <div class="filter-group" id="filter-etages">
            <div class="filter-name">Evenement</div>
            <div class="etage-checks">
              <label><input id="chexbox-evenement" type="checkbox" class="etage-check">Export de tout événement du lot</label>
            </div>
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
        <button class="btn btn-search" onclick="loadTable()">Rechercher</button>
      </div>
    </section>
 
    <!-- 3 — Résultats -->
    <section class="compartiment" id="comp-results" hidden>
      <h2 class="comp-label">3 — Résultats</h2>
      <div class="results-meta">
            <span class="results-count" id="result-count">—</span>
            <button class="export-btn" id="btn-export" onclick="exportAll()" disabled>
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
                    <path d="M8 1v9M4 7l4 4 4-4M2 13h12"
                          stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Télécharger tout (CSV)
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
let COLUMNS_RESULT = {
  lot: {
    numero_lot:'N° lot', variete:'Variété',
    date_sechage:'Date', duree_minute:'Durée (m)', statut:'Statut'
  },
  production: {
    numero_lot:'N° prod', variete:'Variété', date_sechage:'Date',
    quantite_kg:'Quantité (kg)', duree_minute:'Durée (m)',
    humidite_fin:'Humidité fin (%)', statut:'Statut'
  }
};

let BADGE = { 'Terminé':'badge-done', 'En cours':'badge-prog', 'Erreur':'badge-err' };

// ── Filtres par type → { champ: id_element } ─────────────────
let FILTER_IDS = {
  lot: {
    variete: 'lot-variete',
    numero_lot: 'lot-numero',
    duree_etage1: 'chexbox-etage1',
    duree_etage2: 'chexbox-etage2',
    duree_etage3: 'chexbox-etage3',
    duree_etage4: 'chexbox-etage4',
    temperature: 'chexbox-temperature',
    evenement: 'chexbox-evenement'
  },

  production: {
    variete: 'prod-variete',
    numero_prod: 'prod-numero'
  }
};




// ── Sélection du type ─────────────────────────────────────────
function selectType(type) {
	if (currentType == type) {
		currentType = null;
		document.querySelectorAll('.type-item').forEach(btn => btn.classList.remove('active'));
		document.getElementById('comp-filtres').hidden = true;
		document.getElementById('comp-results').hidden = true;
		resetResults();
	}
  else {
    currentType = type;

    document.getElementById('btn-type-lot').classList.toggle('active', type === 'lot');
    document.getElementById('btn-type-production').classList.toggle('active', type === 'production');
    document.getElementById('filtres-lot').hidden = (type !== 'lot');
    document.getElementById('filtres-production').hidden = (type !== 'production');
    document.getElementById('comp-filtres').hidden = false;
    document.getElementById('comp-results').hidden = false;
    resetResults();
  }
}

async function loadTable() {
  var filters = getFiltersRender();
  var data = await getValOfFiltersRender(filters);

  var rows  = data.rows;
  var count = data.count;

  renderTable(rows, count);
}


function getFiltersRender() {
  var filters = {};
  var ids = FILTER_IDS[currentType] || {};

  for (var field in ids) {
    var el = document.getElementById(ids[field]);
    if (!el || el.type === 'checkbox') continue;
    if (el.value) filters[field] = el.value;
  }

  return (filters);
}

async function getValOfFiltersRender(filters) {
  const res = await fetch('api/query_get_lots_preview.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      action: 'preview',
      type_export: currentType,
      ...filters
    })
  });

  const data = await res.json();

  return (data);
}

function renderTable(rows, count) {
  const wrap = document.getElementById('result-table-wrap');

  console.log("dans renderTable les rows", rows);

  if (!count) {
    wrap.innerHTML = '<p class="empty-state">Aucun résultat pour ces filtres.</p>';
    return;
  }

  const cols = COLUMNS_RESULT[currentType];
  const keys = Object.keys(cols);

  const thead = keys.map(k => '<th>' + cols[k] + '</th>').join('') + '<th></th>';

  const tbody = rows.map(row => {
    const cells = keys.map(k => {
      let v = (row[k] !== undefined && row[k] !== null) ? row[k] : '—';
      if      (k === 'statut')       v = '<span class="badge ' + (BADGE[v] || '') + '">' + v + '</span>';
      else if (k === 'duree_minute') v = parseInt(v).toFixed(1) + ' m';
      else if (k === 'quantite_kg')  v = parseFloat(v).toFixed(0) + ' kg';
      else if (k === 'humidite_fin') v = parseFloat(v).toFixed(1) + ' %';
      return '<td>' + v + '</td>';
    }).join('');

    const exportBtn = '<td><button class="btn-export-line" onclick="exportRow(' + row.numero_lot + ')">CSV</button></td>';
    return '<tr>' + cells + exportBtn + '</tr>';
  }).join('');

  wrap.innerHTML = '<table class="result-table"><thead><tr>' + thead + '</tr></thead><tbody>' + tbody + '</tbody></table>';
}


// ── Collecte des filtres actifs ───────────────────────────────
// Retourne un tableau des valeur des filtre par rapport au type d'export qu'on voulait (ex : lot ou prod)
function getFilters() {
  var filters = {};
  var ids = FILTER_IDS[currentType] || {};

  for (var field in ids) {
    var el = document.getElementById(ids[field]);
    
    if (!el) continue;

    var val;
    if (el.type === "checkbox") val = el.checked ? 1 : 0;
    else {
      val = el.value;
    }

    if (val === null || val === "")
    {
      continue;
    }
    filters[field] = val;
  }
  return (filters);
}
 



// ── Reset ─────────────────────────────────────────────────────
function resetFiltreResults() {
	resetFilters();
	resetResults();
}
function resetFilters() {
  var ids = FILTER_IDS[currentType] || {};
  for (var f in ids) {
    var el = document.getElementById(ids[f]);

    if (el.type === "checkbox") el.checked = false;
    else el.value = '';
  }
}
 
function resetResults() {
  document.getElementById('result-table-wrap').innerHTML =
    '<p class="empty-state">Applique les filtres pour voir les résultats.</p>';
  document.getElementById('result-count').textContent = '—';
  document.getElementById('btn-export').disabled = true;
  
}



// ── Export CSV ────────────────────────────────────────────────
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
 
function exportAll() {
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

</script>