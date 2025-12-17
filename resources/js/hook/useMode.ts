import useProps from "./useProps"

const useMode = () => {
	const { mode } = useProps();
	return {
		mode: mode,
		isDateRangMode: mode === 'date_range',
	};
}

export default useMode;