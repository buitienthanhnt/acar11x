/**
 * return format: mm/dd/YYYY
 * @param date 
 * @returns string
 */
const convertLocalDate = (date: string | Date): string => {
	const currentDate = typeof (date) === 'string' ? new Date(date) : date;
	return (currentDate.getMonth() + 1) + '/' + (currentDate.getDate() < 10 ? '0' + currentDate.getDate() : currentDate.getDate()) + '/' + currentDate.getFullYear()
}
const formatIsoStringToLocal = (date: string) => {
	/**
	* format date from "YYYY-MM-DD" to local: "MM/DD/YYYY"
	* error by: https://github.com/wix/react-native-calendars/issues/1319
	*/
	const dAttr = date.slice(0, 10).split("-");
	return [dAttr[1], dAttr[2], dAttr[0]].join('/');
}

export { convertLocalDate, formatIsoStringToLocal };