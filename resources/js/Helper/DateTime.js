export function stringConvert(date = null) {
	const val = new Date(date);
	return val.toISOString().split('T')[0];
}