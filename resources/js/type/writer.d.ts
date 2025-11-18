export interface WriterItemProp {
	id: number;
	name: number;
	email: string;
	alias: string;
	phone: string;
	address: string;
	image_path?: string;
	description?: string;
	date_of_birth?: string;

}

export interface WriterListProp {
	current_page: number;
	last_page: number;
	data?: WriterItemProp[];
	links?: any;
}