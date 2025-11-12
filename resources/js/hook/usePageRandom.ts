import randomPages from "@/query/randomPages";
import { useCallback, useEffect, useState } from "react";

const usePageRandom = () => {
	const [isFetching, setIsFetching] = useState(false);
	const [isError, setIsError] = useState(false);
	const [data, setData] = useState([]);

	const fetchData = useCallback(async () => {
		setIsFetching(true);
		try {
			const response = await randomPages(6);
			setData([...response]);
		} catch (error) {
			setIsError(true);
		}
		setIsFetching(false)
	}, [])

	useEffect(() => {
		fetchData();
	}, [fetchData])

	return {
		pages: data,
		isLoading: isFetching,
		isError: isError
	}
}

export default usePageRandom;