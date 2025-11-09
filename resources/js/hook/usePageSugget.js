import { suggetPage } from "@/query/suggetPage";
import { useQuery } from "@tanstack/react-query";


const usePageSugget = (pageId) => {
	const { data, isFetching, error, isError} = useQuery({
		queryKey: ['pageSugget', pageId],
		queryFn: () => {
			return suggetPage(pageId);
		},
		refetchOnWindowFocus: false,
	})

	return {
		pages: data,
		isLoading: isFetching
	};
}

export {usePageSugget};