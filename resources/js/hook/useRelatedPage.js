import { useCallback, useEffect, useMemo, useState } from "react";
import rApi from "@/network/rApi";
import Urls from "@/network/Urls";

const useRelatedPage = () => {
	const [data, setData] = useState([]);

	const relatedPage = useMemo(() => {
		return localStorage.getItem('page-info-like')?.split('|').slice(0,5) || [];
	}, [])

	const fetchData = useCallback(async () => {
		const response = await rApi.callRequest({
			url: Urls.relatedPage+ `?ids=${relatedPage.join(',')}`,
			method: 'GET',
		});

		setData([...response]);
	}, [relatedPage])

	useEffect(() => {
		fetchData();
	}, [fetchData])

	return {
		pages: data
	};
}

export default useRelatedPage;