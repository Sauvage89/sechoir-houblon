<div class="container-fluid px-3 px-sm-4 pt-3" style="max-width:760px; margin:auto;">

  <!-- 1 — Type d'export -->
  <section class="compartiment" id="comp-type">
    <h2 class="comp-label">1 — Type d'export</h2>
    <div class="type-list">

      <button class="type-item" id="btn-type-lot" onclick="selectType('lot')">
        <span class="dot dot-green"></span>
        <span>Par lot — export détaillé d'un lot</span>
      </button>

      <button class="type-item" id="btn-type-production" disabled onclick="selectType('production')">
        <span class="dot dot-orange"></span>
        <span>
          Par production — tous les lots d'une production finale
          <span class="wip-tag d-block">Work in progress</span>
        </span>
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
          <select id="lot-variete" name="variete" required>
            <option value="" selected>Toutes</option>
          </select>
          <span id="variete-msg" class="form-msg"></span>
        </div>

        <div class="filter-group">
          <div class="filter-name">N° de lot</div>
          <input type="text" id="lot-numero" placeholder="ex. LOT-027">
        </div>

        <div class="filter-group">
          <div class="filter-name">Températures</div>
          <div class="etage-checks">
            <label><input id="chexbox-temperature" type="checkbox" class="etage-check" checked> Export de toutes les températures du lot</label>
          </div>
        </div>

        <div class="filter-group">
          <div class="filter-name">Événements</div>
          <div class="etage-checks">
            <label><input id="chexbox-evenement" type="checkbox" class="etage-check"> Export de tous les événements du lot</label>
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
            <option>Strisselspalt</option>
            <option>Aramis</option>
            <option>Brewers Gold</option>
            <option>Magnum</option>
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

<!-- JS identique à l'original, aucune modification fonctionnelle -->
<script>
let currentType = null;

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

document.addEventListener("DOMContentLoaded", get_variete);

async function get_variete() {
  const select = document.getElementById("lot-variete");
  if (!select) return;
  if (select.dataset.loaded === "true") return;
  try {
    const res = await fetch("../api/query_get_variete.php");
    const data = await res.json();
    if (!Array.isArray(data)) return;
    data.forEach(v => {
      const opt = document.createElement("option");
      opt.value = v.id_variete;
      opt.textContent = v.variete_nom;
      select.appendChild(opt);
    });
    select.dataset.loaded = "true";
  } catch (e) {
    console.error("Erreur chargement variétés", e);
  }
}

function selectType(type) {
  if (currentType == type) {
    currentType = null;
    document.querySelectorAll('.type-item').forEach(btn => btn.classList.remove('active'));
    document.getElementById('comp-filtres').hidden = true;
    document.getElementById('comp-results').hidden = true;
    resetResults();
  } else {
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
  renderTable(data.rows, data.count);
}

function getFiltersRender() {
  var filters = {};
  var ids = FILTER_IDS[currentType] || {};
  for (var field in ids) {
    var el = document.getElementById(ids[field]);
    if (!el || el.type === 'checkbox') continue;
    if (el.value) filters[field] = el.value;
  }
  return filters;
}

async function getValOfFiltersRender(filters) {
  const res = await fetch('api/query_get_lots_preview.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ action: 'preview', type_export: currentType, ...filters })
  });
  return await res.json();
}

function renderTable(rows, count) {
  const wrap = document.getElementById('result-table-wrap');

  if (!count) {
    wrap.innerHTML = '<p class="empty-state">Aucun résultat pour ces filtres.</p>';
    document.getElementById('result-count').textContent = '0 résultat';
    document.getElementById('btn-export').disabled = true;
    return;
  }

  document.getElementById('result-count').textContent = count + ' résultat' + (count > 1 ? 's' : '');
  document.getElementById('btn-export').disabled = false;

  const cols = COLUMNS_RESULT[currentType];
  const keys = Object.keys(cols);

  // ── TABLE (>= 576px) ─────────────────────────────────────
  const thead = keys.map(k => '<th>' + cols[k] + '</th>').join('') + '<th></th>';
  const tbody = rows.map(row => {
    const cells = keys.map(k => '<td>' + formatCell(k, row[k]) + '</td>').join('');
    const btn = '<td><button class="btn-export-line" onclick="exportRow(' + row.numero_lot + ')">CSV</button></td>';
    return '<tr>' + cells + btn + '</tr>';
  }).join('');

  const table = '<table class="result-table">'
    + '<thead><tr>' + thead + '</tr></thead>'
    + '<tbody>' + tbody + '</tbody>'
    + '</table>';

  // ── CARDS (mobile) ────────────────────────────────────────
  // Première colonne = titre de la card, reste = grille clé/valeur
  const [titleKey, ...bodyKeys] = keys;

  const cards = '<div class="result-cards">'
    + rows.map(row => {
        const header = '<div class="result-card-header">'
          + '<span class="result-card-title">' + (row[titleKey] ?? '—') + '</span>'
          + '<button class="btn-export-line" onclick="exportRow(' + row.numero_lot + ')">CSV</button>'
          + '</div>';

        const body = '<div class="result-card-body">'
          + bodyKeys.map(k =>
              '<span class="result-card-key">' + cols[k] + '</span>'
              + '<span class="result-card-val">' + formatCell(k, row[k]) + '</span>'
            ).join('')
          + '</div>';

        return '<div class="result-card">' + header + body + '</div>';
      }).join('')
    + '</div>';

  wrap.innerHTML = table + cards;
}

// Formatage centralisé (utilisé par table ET cards)
function formatCell(k, v) {
  if (v === undefined || v === null) return '—';
  if (k === 'statut')       return '<span class="badge ' + (BADGE[v] || '') + '">' + v + '</span>';
  if (k === 'duree_minute') return parseInt(v) + ' min';
  if (k === 'quantite_kg')  return parseFloat(v).toFixed(0) + ' kg';
  if (k === 'humidite_fin') return parseFloat(v).toFixed(1) + ' %';
  return v;
}

function getFilters() {
  var filters = {};
  var ids = FILTER_IDS[currentType] || {};
  for (var field in ids) {
    var el = document.getElementById(ids[field]);
    if (!el) continue;
    var val = (el.type === "checkbox") ? (el.checked ? 1 : 0) : el.value;
    if (val === null || val === "") continue;
    filters[field] = val;
  }
  return filters;
}

function resetFiltreResults() { resetFilters(); resetResults(); }

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

async function exportRow(numero_lot) {
  if (!currentType) return;

  const filters = getFilters();
  filters.numero_lot = numero_lot;
  filters.type_export = currentType;

  try {
    const res = await fetch("../api/query_export_csv.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams(filters)
    });

    const data = await res.json();

    // LOT
    const lotCsv = [
      "id_lot,lot_remplissage,lot_dateHeureEntree,lot_dateHeureSortie,lot_dureeTheorique,lot_actif,variete_nom",
      `${data.lot.id_lot},${data.lot.lot_remplissage},${data.lot.lot_dateHeureEntree},${data.lot.lot_dateHeureSortie},${data.lot.lot_dureeTheorique},${data.lot.lot_actif},${data.lot.variete_nom}`
    ].join("\n");

    // ETAGES
    const etagesCsv = [
      "id_lot,id_etage,lotEtage_dateDebut,lotEtage_dateFin,duree_minute",
      ...data.etages.map(e =>
        `${data.lot.id_lot},${e.id_etage},${e.lotEtage_dateDebut},${e.lotEtage_dateFin},${e.duree_minute}`
      )
    ].join("\n");

    // TEMPERATURES
    const tempBlock = filters.temperature == 1
      ? "\n\n=== TEMPERATURES ===\n" + [
          "id_lot,id_temperature,temperature_valeur,temperature_dateHeure,addresse_capteur",
          ...data.temperatures.map(t =>
            `${data.lot.id_lot},${t.id_temperature},${t.temperature_valeur},${t.temperature_dateHeure},${t.addresse_capteur}`
          )
        ].join("\n")
      : "";

    // EVENEMENTS
    const eventBlock = filters.evenement == 1
      ? "\n\n=== EVENEMENTS ===\n" + [
          "id_pause,pause_type,pause_dateHeureDebut,pause_dateHeureFin",
          ...data.evenements.map(e =>
            `${e.id_pause},${e.pause_type},${e.pause_dateHeureDebut},${e.pause_dateHeureFin}`
          )
        ].join("\n")
      : "";
      
    // Fusion finale
    const finalCsv =
      "=== LOT ===\n" + lotCsv + "\n\n" +
      "=== ETAGES ===\n" + etagesCsv +
      tempBlock +
      eventBlock;

    const blob = new Blob([finalCsv], { type: "text/csv" });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "export_" + numero_lot + ".csv";
    document.body.appendChild(a);
    a.click();
    a.remove();
    window.URL.revokeObjectURL(url);

  } catch (e) {
    console.error("Erreur export CSV :", e);
  }
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