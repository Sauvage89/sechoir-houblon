async function apiFetch(url, body = null) {
	const opts = body
	? { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(body) }
	: {};
	const res = await fetch(url, opts);
	return (res.json());
}