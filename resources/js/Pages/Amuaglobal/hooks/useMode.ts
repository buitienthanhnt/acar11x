import usePageProps from "./usePageProps"

const useMode = (): {
	mode: 'list_date' | 'date_range',
	isDateRangeMode: boolean,
} => {
	const { mode } = usePageProps();
	return {
		mode: mode as unknown as 'list_date' | 'date_range',
		isDateRangeMode: mode === 'date_range',
	};
}

export default useMode;