    <div class="export-page">

        <!-- ══════════════════════════════════════
             COMPARTIMENT 1 — Type d'export
        ══════════════════════════════════════ -->
        <section class="compartiment" id="comp-type">
            <div class="comp-label">1 — Type d'export</div>

            <div class="type-list">
                <button class="type-item" data-type="lot" onclick="selectType('lot')">
                    <span class="type-dot dot-green"></span>
                    Par lot — export détaillé d'un lot
                </button>
                <button class="type-item" data-type="periode" onclick="selectType('production')">
                    <span class="type-dot dot-amber"></span>
                    Par production — tous les lots compris dans une production finale
                </button>
            </div>
        </section>

        <!-- ══════════════════════════════════════
             COMPARTIMENT 2 — Filtres
        ══════════════════════════════════════ -->
        <section class="compartiment" id="comp-filtres">
            <div class="comp-label">2 — Filtres</div>

            <!-- Filtres : Par lot -->
            <div class="filter-set" data-type="lot" style="display:none;">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-name" for="lot-variete">Variété</label>
                        <select id="lot-variete" name="variete">
                            <option value="">Toutes</option>
                            <option value="Strisselspalt">Strisselspalt</option>
                            <option value="Aramis">Aramis</option>
                            <option value="Brewers Gold">Brewers Gold</option>
                            <option value="Magnum">Magnum</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-name" for="lot-numero">N° de lot</label>
                        <input type="text" id="lot-numero" name="numero_lot" placeholder="ex. LOT-027">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn" onclick="selectType('lot')">Réinitialiser</button>
                    <button class="btn btn-primary" onclick="loadPreview()">Rechercher</button>
                </div>
            </div>

            <!-- Filtres : Par production -->
            <div class="filter-set" data-type="production" style="display:none;">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-name" for="lot-variete">Variété</label>
                        <select id="lot-variete" name="variete">
                            <option value="">Toutes</option>
                            <option value="Strisselspalt">Strisselspalt</option>
                            <option value="Aramis">Aramis</option>
                            <option value="Brewers Gold">Brewers Gold</option>
                            <option value="Magnum">Magnum</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-name" for="prod-numero">N° de production</label>
                        <input type="text" id="prod-numero" name="numero_prod" placeholder="ex. PROD-005">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn" onclick="selectType('lot')">Réinitialiser</button>
                    <button class="btn btn-primary" onclick="loadPreview()">Rechercher</button>
                </div>
            </div>

        <!-- ══════════════════════════════════════
             COMPARTIMENT 3 — Résultats
        ══════════════════════════════════════ -->
        <section class="compartiment" id="comp-results" style="display:none;">
            <div class="comp-label">3 — Résultats</div>

            <div class="results-meta">
                <span class="results-count" id="result-count">—</span>
                <button class="export-btn" id="btn-export" disabled onclick="submitExport()">
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

    </div><!-- /.export-page -->

<!-- Formulaire caché pour l'export CSV (soumission classique → téléchargement) -->
<form id="export-form" method="POST" action="export_handler.php" style="display:none;">
    <input type="hidden" id="hidden-action" name="action" value="export">
    <input type="hidden" id="hidden-type"   name="type_export" value="">
</form>



<script>
// ─── État ─────────────────────────────────────────────────────
let currentType = null;

// ─── Sélection du type d'export ──────────────────────────────
function selectType(type) {
    currentType = type;

    // Mise à jour visuelle des boutons
    document.querySelectorAll('.type-item').forEach(el => {
        el.classList.toggle('active', el.dataset.type === type);
    });

    // Affichage des filtres correspondants
    document.querySelectorAll('.filter-set').forEach(el => {
        el.style.display = el.dataset.type === type ? 'block' : 'none';
    });

    // Réinitialise les résultats
    resetResults();

    // Lance automatiquement une preview avec filtres vides
    loadPreview();
}

// ─── Chargement de la preview via AJAX ───────────────────────
function loadPreview() {
    if (!currentType) return;

    const resultsPanel = document.getElementById('comp-results');
    const tableWrap    = document.getElementById('result-table-wrap');
    const countEl      = document.getElementById('result-count');
    const exportBtn    = document.getElementById('btn-export');

    tableWrap.classList.add('loading');

    const formData = new FormData();
    formData.append('action', 'preview');
    formData.append('type_export', currentType);

    // Collecte les filtres actifs
    const activeFilters = document.querySelector(`.filter-set[data-type="${currentType}"]`);
    if (activeFilters) {
        activeFilters.querySelectorAll('select, input').forEach(el => {
            if (el.name && el.value) formData.append(el.name, el.value);
        });
    }

    fetch('export_handler.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            renderTable(data.rows, data.count);
            tableWrap.classList.remove('loading');
            resultsPanel.style.display = 'block';
            exportBtn.disabled = data.count === 0;
            countEl.textContent = data.count + ' résultat' + (data.count > 1 ? 's' : '');
        })
        .catch(() => {
            tableWrap.innerHTML = '<p class="empty-state">Erreur de connexion au serveur.</p>';
            tableWrap.classList.remove('loading');
        });
}

// ─── Rendu du tableau de résultats ───────────────────────────
function renderTable(rows, count) {
    const wrap = document.getElementById('result-table-wrap');

    if (count === 0) {
        wrap.innerHTML = '<p class="empty-state">Aucun résultat pour ces filtres.</p>';
        return;
    }

    // En-têtes lisibles selon le type
    const headers = {
        lot: {
            numero_lot:    'N° lot',
            variete:       'Variété',
            sechoir:       'Séchoir',
            palier:        'Palier',
            date_sechage:  'Date',
            quantite_kg:   'Quantité (kg)',
            duree_heures:  'Durée (h)',
            humidite_fin:  'Humidité fin (%)',
            statut:        'Statut',
        },
        periode: {
            numero_lot:    'N° lot',
            variete:       'Variété',
            sechoir:       'Séchoir',
            date_sechage:  'Date',
            quantite_kg:   'Quantité (kg)',
            duree_heures:  'Durée (h)',
            humidite_fin:  'Humidité fin (%)',
            statut:        'Statut',
        },
        variete: {
            variete:       'Variété',
            saison:        'Saison',
            nb_lots:       'Nb lots',
            total_kg:      'Total (kg)',
            humidite_moy:  'Humidité moy. (%)',
        }
    };

    const cols     = headers[currentType] || {};
    const colKeys  = Object.keys(cols);

    let html = '<table class="result-table"><thead><tr>';
    colKeys.forEach(k => { html += `<th>${cols[k]}</th>`; });
    html += '</tr></thead><tbody>';

    rows.forEach(row => {
        html += '<tr>';
        colKeys.forEach(k => {
            let val = row[k] ?? '—';

            if (k === 'statut') {
                const map = {
                    'Terminé':  'badge-done',
                    'En cours': 'badge-prog',
                    'Erreur':   'badge-err',
                };
                const cls = map[val] || '';
                val = `<span class="badge ${cls}">${val}</span>`;
            } else if (k === 'humidite_fin' || k === 'humidite_moy') {
                val = parseFloat(val).toFixed(1) + ' %';
            } else if (k === 'total_kg' || k === 'quantite_kg') {
                val = parseFloat(val).toFixed(0) + ' kg';
            } else if (k === 'duree_heures') {
                val = parseFloat(val).toFixed(1) + ' h';
            }

            html += `<td>${val}</td>`;
        });
        html += '</tr>';
    });

    html += '</tbody></table>';
    wrap.innerHTML = html;
}

// ─── Export CSV (soumission du formulaire) ────────────────────
function submitExport() {
    if (!currentType) return;

    const form = document.getElementById('export-form');
    document.getElementById('hidden-type').value  = currentType;
    document.getElementById('hidden-action').value = 'export';

    // Copie les valeurs des filtres actifs dans le formulaire
    const activeFilters = document.querySelector(`.filter-set[data-type="${currentType}"]`);
    if (activeFilters) {
        // Nettoie les champs cachés précédents
        form.querySelectorAll('.dyn-field').forEach(el => el.remove());

        activeFilters.querySelectorAll('select, input').forEach(el => {
            if (el.name && el.value) {
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = el.name;
                hidden.value = el.value;
                hidden.classList.add('dyn-field');
                form.appendChild(hidden);
            }
        });
    }

    form.submit();
}

// ─── Reset ───────────────────────────────────────────────────
function resetResults() {
    document.getElementById('result-table-wrap').innerHTML =
        '<p class="empty-state">Applique les filtres pour voir les résultats.</p>';
    document.getElementById('result-count').textContent = '—';
    document.getElementById('btn-export').disabled = true;
}

// ─── Init ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    resetResults();
});

</script>
