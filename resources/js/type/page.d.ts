import { Page, PageProps, SharedPageProps } from '@inertiajs/core';

export interface AppPageProps extends Page<PageProps> {
	auth: Auth;
}

export type BasePageProp = Page<PageProps>;

export type SourceValueInfo = {
	[key: string]: number
} 

export interface SourceInfoProp{
	id: number;
	value: {
		[key: string]: number
	};
	target_id: number
}

export interface PageInfoProp{
	id: number;
	title: string;
	alias: string;
	active: number;
	desciption?: string;
	image_path?: string;
	updated_at: string;
	writer: number;
	above: number;
	comments_count: number;
	source: SourceInfoProp;
}