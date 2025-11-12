import { getTopPages } from "@/query/pageQuery";
import { useQuery } from "@tanstack/react-query";

const useTopPage = () => {
	const query = useQuery({
		queryKey: ['get-top-Page'],
		queryFn: getTopPages,	
		retry: true,
		retryDelay: 3000,
	});
	return {...query};
}

export default useTopPage;