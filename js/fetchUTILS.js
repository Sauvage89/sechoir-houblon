// --------------------------- METHOD POST --------START---------------------------------

// Fait l'appel a l'API via des parametre formater et retourne un objet json
async function apiFetchPostForm(url, data = {}) {
	const res = await fetch(url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: new URLSearchParams(data)
	});

	return res.json();
}

// Fait l'appel a l'API via des parametre formater et retourne un objet json
async function apiFetchPostJson(url, data = {}) {
	const res = await fetch(url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(data)
	});

	return res.json();
}

// --------------------------- METHOD POST --------STOP---------------------------------

// --------------------------- METHOD GET --------START---------------------------------

// Reconstruit l'url avec des paramètre
function _buildUrl(url, params) {
	const query = new URLSearchParams(params).toString();
	return query ? `${url}?${query}` : url;
}

// Fait l'appel a l'API
async function _apiFetchGet(url, params = {}) {
	const fullUrl = _buildUrl(url, params);
	return await fetch(fullUrl, {
		method: 'GET',
		headers: {
			'Accept': 'application/json'
		}
	});
}

// Formate le retour de l'appel de l'API
async function apiFetchGetJson(url, params = {}) {
	const res = await _apiFetchGet(url, params);
	return res.json();
}

// Formate le retour de l'appel de l'API
async function apiFetchGetText(url, params = {}) {
	const res = await _apiFetchGet(url, params);
	return res.text();
}

// Formate le retour de l'appel de l'API
async function apiFetchGetBlob(url, params = {}) {
	const res = await _apiFetchGet(url, params);
	return res.blob();
}

// --------------------------- METHOD GET --------STOP---------------------------------