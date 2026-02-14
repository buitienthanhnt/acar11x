/**
 * Date -> local format
 * return format: mm/dd/YYYY(client use for calenda) from Date value
 */
const convertLocalDate = (date: string | Date): string => {
	const currentDate = typeof (date) === 'string' ? new Date(date) : date;
	return (currentDate.getMonth() + 1) + '/' + (currentDate.getDate() < 10 ? '0' + currentDate.getDate() : currentDate.getDate()) + '/' + currentDate.getFullYear()
}

const convertStringServerToDates = (dates: string[]): Date[] => {
	if (!dates) {
		return [];
	}
	return dates.map(d => new Date(d));
}

/**
 * server format -> local format
 * format date from "YYYY-MM-DD"(server format) to local: "mm/dd/YYYY"(client use for calenda string)
 */
const formatIsoStringToLocal = (date: string): string => {
	/**
	* format date from "YYYY-MM-DD" to local: "mm/dd/YYYY"
	* error by: https://github.com/wix/react-native-calendars/issues/1319
	*/
	const dAttr = date.slice(0, 10).split("-");
	return [dAttr[1], dAttr[2], dAttr[0]].join('/');
}

const formatDateToLocals = (dates: Date[]): string[] => {
	/**
	* format date from "YYYY-MM-DD" to local: "mm/dd/YYYY"
	* error by: https://github.com/wix/react-native-calendars/issues/1319
	*/
	return dates.map(date => date.toLocaleDateString())
}

/**
 * Date[] local format -> string[] server format
 * format from Date[](client format) to [YYYY-MM-DD](server format
 */
const listDateToArrayString = (dates: Date[]): string[] => {
	return dates.map(d => dateToServerString(d));
}

/**
 * format Date to server string: YYYY-MM-DD
 * returns: string in format YYYY-MM-DD
 */
const dateToServerString = (date: Date): string => {
	return [
		date.getFullYear(),
		date.getMonth() + 1 >= 10 ? date.getMonth() + 1 : '0' + (date.getMonth() + 1).toString(),
		date.getDate() < 10 ? '0' + date.getDate().toString() : date.getDate(),
	].join('-')
}

const sortDatesAsc = (dates: Date[]): Date[] => {
	return dates.sort((a, b) => { return a.getTime() - b.getTime(); })
}

const sortStringDatesAsc = (dates: string[]): string[] => {
	return dates.sort((a, b) => { return new Date(a).getTime() - new Date(b).getTime(); })
}

const listDateByRange = (dates: Date[]): Date[] => {
	const formatDate = sortDatesAsc(dates);
	let dateArray: Date[] = [];

	let currentDate = new Date(formatDate[0]);
	// set time to 00:00:00
	currentDate.setHours(0, 0, 0, 0);
	while (currentDate <= new Date(formatDate[formatDate.length - 1])) {
		dateArray.push(new Date(currentDate));
		currentDate.setDate(currentDate.getDate() + 1);
	}

	return dateArray;
}

const isDateInRange = (date: Date, dates: Date[]): boolean => {
	date.setHours(0, 0, 0, 0);
	const formatDate = sortDatesAsc([...dates]);	
	return (date >= formatDate[0]) && (date <= formatDate[formatDate.length - 1]);
}

export {
	convertLocalDate,
	formatIsoStringToLocal,
	listDateToArrayString,
	sortDatesAsc,
	sortStringDatesAsc,
	listDateByRange,
	formatDateToLocals,
	isDateInRange,
	convertStringServerToDates,
	dateToServerString,
};