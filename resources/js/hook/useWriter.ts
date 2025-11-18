import { WriterItemProp, WriterListProp } from "@/type/writer";
import useProps from "./useProps";
import { PagePaginate } from "@/type/paginate";
import { PageInfoProp } from "@/type/page";

export const useListWriter = () => {
	const { current_page, last_page, data } = useProps();

	return {
		current_page,
		last_page,
		data,
	} as WriterListProp
}

export const useWriterDetail = ()=>{
	const {writer, pages} = useProps();
	return {
		writer: writer as WriterItemProp,
		pages: pages as Omit<PagePaginate, 'data'>& {data: PageInfoProp[]},
	};
}