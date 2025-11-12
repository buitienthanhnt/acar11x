import { getCenterCategory } from "@/query/category";
import { useQuery } from "@tanstack/react-query";

const useCenterCategory = ()=>{
	const query = useQuery({
		queryKey: ['centerCategory'],
		queryFn: getCenterCategory,
		retry: false,
		retryOnMount: false,
	})
	return {...query};
}

export {useCenterCategory}