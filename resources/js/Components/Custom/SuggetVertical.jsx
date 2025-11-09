import { usePageSugget } from "@/hook/usePageSugget";
import { PageSmList } from "../PageComponent";

const SuggetVertical = ({data, title})=>{
	// const {pages, isLoading} = usePageSugget(pageId);
	// if (isLoading || !pages) {return;}

	if (!data) {
		return null;
	}

	return(
		<PageSmList items={data} title={title}></PageSmList>
	)
}

export {SuggetVertical};