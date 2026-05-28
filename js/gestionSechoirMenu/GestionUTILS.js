// Convertie le format (HH:MM)/minute en minute
function parseTime(value) {
  value = value.trim();

  // format hh:mm
  if (/^\d{1,2}:\d{2}$/.test(value)) {
    const [h, m] = value.split(':').map(Number);
    return (h * 60 + m);
  }

  // format minutes
  if (/^\d+$/.test(value))
    return (parseInt(value, 10));

  return (0);
}

// Format les minutes pour être sous la forme (HH:MM).
function formatTime(totalMinutes) {
	let heure;
	let minute;
	let formatedString;

	if (totalMinutes < 0)
		totalMinutes = 0;
	heure = Math.floor(totalMinutes / 60);
	minute = totalMinutes % 60;
	formatedString = String(heure).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
	return (formatedString);
}