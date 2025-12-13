export interface PaginateProp {
	pageSize: number;
	currentPage: number;
	url?: string;
	pageName?: string;
}

type DataType = {
	[key: string]: any
}
export interface PagePaginate {
	current_page: number;
	data: DataType[];
	from: number;
	last_page: number;
	total: number;
	path: string;
}