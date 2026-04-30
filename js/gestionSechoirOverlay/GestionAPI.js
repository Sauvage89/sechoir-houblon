async function get_lot_nom(etage) {	
	const res = await fetch(`../api/query_get_nom_etage.php?etage=${etage}`);
	const data = await res.json();
	return (data?.nom ?? null);
}

async function get_lot(etage) {
  	const res = await fetch(`../api/query_get_lot.php?etage=${etage}`);
	const data = await res.json();
	return (data.lot);
}